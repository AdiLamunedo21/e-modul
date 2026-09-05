<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Helper untuk mendapatkan model Student yang sedang login.
     */
    private function student(): Student
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user();
        return $student;
    }

    /**
     * Dashboard Portal Siswa:
     * Menyajikan ringkasan KPI belajar real-time, katalog mata pelajaran & guru pengampu,
     * modul kelas yang ditugaskan per mata pelajaran, serta persentase progres belajar
     * dari seluruh rombel kelas yang diikuti oleh siswa.
     */
    public function index(Request $request)
    {
        $student = $this->student();

        // Ambil seluruh rombel kelas yang diikuti siswa
        $joinedClasses = $student->classes()->with('major')->get();

        // Fallback backward compatibility jika siswa memiliki class_id lama tetapi belum di-pivot
        if ($joinedClasses->isEmpty() && $student->class_id) {
            $fallbackClass = SchoolClass::find($student->class_id);
            if ($fallbackClass) {
                $student->classes()->syncWithoutDetaching([$fallbackClass->id]);
                $joinedClasses = $student->classes()->with('major')->get();
            }
        }

        $joinedClassIds = $joinedClasses->pluck('id')->toArray();
        $filterClassId = $request->query('class_id', 'all');
        $class = $joinedClasses->first();

        // Query modul terbit yang ditugaskan untuk seluruh kelas yang diikuti siswa secara efisien
        $studentSubjectIds = $student->subjects()->pluck('subjects.id')->toArray();

        if (!empty($joinedClassIds)) {
            $modulesQuery = Module::select([
                    'id', 'teacher_id', 'class_id', 'subject_id', 'title',
                    'semester', 'status', 'is_active', 'created_at', 'updated_at',
                    'has_pre_test', 'has_materi', 'has_video', 'has_embed',
                    'has_job_sheet', 'has_lkpd', 'has_post_test'
                ])
                ->whereIn('class_id', $joinedClassIds)
                ->where('status', 'published')
                ->with([
                    'teacher:id,name',
                    'subject:id,name,code,icon,color',
                    'schoolClass:id,major_name,grade,section,code,major_id',
                    'schoolClass.major:id,name,code',
                    'jobSheets'             => fn($q) => $q->select(['id', 'module_id']),
                    'jobSheets.submissions' => fn($q) => $q->select(['id', 'job_sheet_id', 'student_id'])->where('student_id', $student->id),
                    'lkpds'                 => fn($q) => $q->select(['id', 'module_id']),
                    'lkpds.submissions'     => fn($q) => $q->select(['id', 'lkpd_id', 'student_id'])->where('student_id', $student->id),
                    'studentResults'        => fn($q) => $q->select(['id', 'module_id', 'student_id', 'pre_test_score', 'post_test_score', 'read_components', 'summative_score', 'grading_status', 'updated_at'])->where('student_id', $student->id),
                    'videoSummaries'        => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
                    'embedSubmissions'      => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
                ])
                ->latest('updated_at');

            if (!empty($studentSubjectIds)) {
                $modulesQuery->whereIn('subject_id', $studentSubjectIds);
            }

            $allModules = $modulesQuery->get();
        } else {
            $allModules = collect();
        }

        // Olah data setiap modul untuk mendapatkan progres belajar siswa secara akurat
        $processedModules = $allModules->map(function (Module $module) use ($student) {
            $result = $module->studentResults->first();
            $activeComps = $module->activeComponents();
            $totalActive = count($activeComps);

            // Hitung berapa instrumen yang sudah diselesaikan siswa
            $completedTasks = 0;
            $pendingTasksList = [];

            // 1. Pre-Test
            if ($module->pre_test_active) {
                if ($result && $result->pre_test_score !== null) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type' => 'Pre-Test',
                        'title' => 'Kuis Awal (Pre-test)',
                        'icon' => '📝',
                        'badge' => 'Kuis',
                    ];
                }
            }

            // 2. Materi & PPT
            if ($module->materi_active) {
                if ($result && $result->isComponentRead('materi')) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type' => 'Materi',
                        'title' => 'Pelajari Uraian Materi & PPT',
                        'icon' => '📖',
                        'badge' => 'Materi Inti',
                    ];
                }
            }

            // 2. Ringkasan Video
            if ($module->video_active) {
                $videoSummary = $module->videoSummaries->first();
                if ($videoSummary) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type' => 'Video',
                        'title' => 'Tonton Video & Tulis Ringkasan',
                        'icon' => '🎬',
                        'badge' => 'Materi Video',
                    ];
                }
            }

            // 3. Praktik Simulator / Embed
            if ($module->embed_active) {
                $embedSub = $module->embedSubmissions->first();
                if ($embedSub) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type' => 'Embed',
                        'title' => 'Eksplorasi Simulator / Embed Code',
                        'icon' => '⚡',
                        'badge' => 'Praktik Interaktif',
                    ];
                }
            }

            // 4. Job Sheet Praktikum
            if ($module->job_sheet_active) {
                $hasJobSheetSub = $module->jobSheets->some(fn($js) => $js->submissions->isNotEmpty());
                if ($hasJobSheetSub) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type' => 'Job Sheet',
                        'title' => 'Unggah Lembar Kerja Job Sheet',
                        'icon' => '📋',
                        'badge' => 'Praktikum PDF',
                    ];
                }
            }

            // 5. Tugas LKPD
            if ($module->lkpd_active) {
                $hasLkpdSub = $module->lkpds->some(fn($lkpd) => $lkpd->submissions->isNotEmpty());
                if ($hasLkpdSub) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type' => 'LKPD',
                        'title' => 'Kirim Jawaban Tugas LKPD',
                        'icon' => '📑',
                        'badge' => 'Lembar Kerja',
                    ];
                }
            }

            // 6. Post-Test
            if ($module->post_test_active) {
                if ($result && $result->post_test_score !== null) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type' => 'Post-Test',
                        'title' => 'Kuis Penutup (Post-test)',
                        'icon' => '🎯',
                        'badge' => 'Evaluasi',
                    ];
                }
            }

            // Kalkulasi persentase kemajuan belajar
            $progressPercent = $totalActive > 0 ? (int) round(($completedTasks / $totalActive) * 100) : 0;
            if ($progressPercent > 100) {
                $progressPercent = 100;
            }

            // Status Belajar Siswa
            $progressStatus = match (true) {
                $progressPercent >= 100 => 'completed',
                $progressPercent > 0    => 'in_progress',
                default                 => 'not_started',
            };

            return [
                'id'                => $module->id,
                'title'             => $module->title,
                'description'       => $module->description,
                'class_id'          => $module->class_id,
                'class_name'        => $module->schoolClass?->full_name ?? 'Rombel Kelas',
                'class_short_name'  => $module->schoolClass?->short_name ?? 'Kelas',
                'class_code'        => $module->schoolClass?->code ?? '',
                'major_name'        => $module->schoolClass?->major?->name ?? $module->schoolClass?->major_name ?? '',
                'subject_id'        => $module->subject_id,
                'semester'          => (string) ($module->semester ?? '1'),
                'semester_label'    => $module->semester_label,
                'semester_short'    => $module->semester_short,
                'semester_badge'    => $module->semester_badge,
                'subject'           => $module->subject,
                'subject_name'      => $module->subject?->name ?? 'Mata Pelajaran',
                'subject_code'      => $module->subject?->code ?? '',
                'teacher_name'      => $module->teacher->name ?? 'Guru Pengampu',
                'created_at'        => $module->created_at,
                'updated_at'        => $module->updated_at,
                'is_new'            => $module->created_at && $module->created_at->diffInDays(now()) <= 7,
                'active_components' => $activeComps,
                'total_components'  => $totalActive,
                'completed_tasks'   => $completedTasks,
                'progress_percent'  => $progressPercent,
                'progress_status'   => $progressStatus,
                'pending_tasks'     => $pendingTasksList,
                'student_result'    => $result,
                'summative_score'   => $result?->summative_score,
                'grading_status'    => $result?->grading_status,
                'has_pre_test'      => $module->pre_test_active,
                'has_post_test'     => $module->post_test_active,
                'has_materi'        => $module->materi_active,
                'has_video'         => $module->video_active,
                'has_embed'         => $module->embed_active,
                'has_job_sheet'     => $module->job_sheet_active,
                'has_lkpd'          => $module->lkpd_active,
                'is_active_in_class'=> (bool) $module->is_active,
            ];
        });

        // Struktur data Mata Pelajaran (Subjects) yang ditempuh siswa beserta informasi Guru Pengampu & Jumlah Modul
        if (!empty($studentSubjectIds)) {
            $allSubjectsList = $student->subjects()->with('teachers')->get();
        } else {
            $allSubjectsList = Subject::with('teachers')->get();
        }

        $subjects = $allSubjectsList->map(function (Subject $subject) use ($processedModules) {
            $subjectModules = $processedModules->where('subject_id', $subject->id)->values();

            // Kumpulkan nama guru dari modul atau dari relasi Subject->teachers
            $teacherNames = $subjectModules->pluck('teacher_name')->unique()->filter()->values();
            if ($teacherNames->isEmpty()) {
                $teacherNames = $subject->teachers->pluck('name')->unique()->values();
            }
            $teacherDisplay = $teacherNames->isNotEmpty() ? $teacherNames->join(', ') : 'Guru Pengampu';

            $modulesCount = $subjectModules->count();
            $completedCount = $subjectModules->where('progress_status', 'completed')->count();
            $inProgressCount = $subjectModules->where('progress_status', 'in_progress')->count();
            $notStartedCount = $subjectModules->where('progress_status', 'not_started')->count();
            $avgProgress = $modulesCount > 0 ? (int) round($subjectModules->avg('progress_percent')) : 0;

            return [
                'id'                => $subject->id,
                'name'              => $subject->name,
                'code'              => $subject->code,
                'icon'              => $subject->icon ?: '📚',
                'color'             => $subject->color ?: 'blue',
                'description'       => $subject->description,
                'teacher_name'      => $teacherDisplay,
                'teacher_count'     => $teacherNames->count(),
                'modules_count'     => $modulesCount,
                'completed_count'   => $completedCount,
                'in_progress_count' => $inProgressCount,
                'not_started_count' => $notStartedCount,
                'avg_progress'      => $avgProgress,
                'modules'           => $subjectModules,
                'badge_classes'     => $subject->badgeClasses(),
            ];
        });

        // Kategori modul terpisah untuk akses cepat tab dashboard:
        // Sedang Dikerjakan memprioritaskan modul yang aktif diajarkan oleh guru di kelas!
        $inProgressModules = $processedModules->filter(fn($m) => $m['is_active_in_class'] || $m['progress_status'] === 'in_progress')->sortByDesc('is_active_in_class')->values();
        $completedModules = $processedModules->where('progress_status', 'completed')->values();

        // Tentukan default tab pembukaan jika tidak ada parameter status di URL:
        // Jika ada pembelajaran aktif (in_progress > 0), buka 'in_progress' (Sedang Dikerjakan).
        // Jika tidak ada pembelajaran aktif, buka 'classes' (Kelas Saya).
        $defaultTab = $inProgressModules->isNotEmpty() ? 'in_progress' : 'classes';
        $filterStatus = $request->query('status', $defaultTab);
        $filterSubject = $request->query('subject', 'all');

        $filteredModules = $processedModules;

        if ($filterSubject !== 'all') {
            $filteredModules = $filteredModules->where('subject_id', (int) $filterSubject);
        }

        if ($filterStatus === 'in_progress') {
            $filteredModules = $inProgressModules;
        } elseif ($filterStatus === 'completed') {
            $filteredModules = $completedModules;
        } elseif ($filterStatus === 'not_started') {
            $filteredModules = $filteredModules->where('progress_status', 'not_started');
        }

        // Metrik KPI Statistik Siswa
        $totalModulesCount = $processedModules->count();
        $completedModulesCount = $completedModules->count();
        $inProgressModulesCount = $inProgressModules->count();
        $notStartedModulesCount = $processedModules->where('progress_status', 'not_started')->count();

        // Kumpulan tugas belum selesai (To-Do List 5 Teratas)
        $allPendingTasks = $processedModules->flatMap(function ($item) {
            return collect($item['pending_tasks'])->map(function ($task) use ($item) {
                return array_merge($task, [
                    'module_id'    => $item['id'],
                    'module_title' => $item['title'],
                    'teacher_name' => $item['teacher_name'],
                ]);
            });
        })->take(5);

        // Nilai rata-rata siswa
        $gradedScores = $processedModules->pluck('summative_score')->filter(fn($v) => !is_null($v));
        $avgScore = $gradedScores->count() > 0 ? (int) round($gradedScores->avg()) : 0;

        // Rata-rata kemajuan belajar
        $avgProgress = $totalModulesCount > 0 ? (int) round($processedModules->avg('progress_percent')) : 0;

        $stats = [
            'total_modules'       => $totalModulesCount,
            'completed_modules'   => $completedModulesCount,
            'in_progress'         => $inProgressModulesCount,
            'not_started'         => $notStartedModulesCount,
            'pending_tasks_count' => $processedModules->sum(fn($m) => count($m['pending_tasks'])),
            'avg_score'           => $avgScore,
            'avg_progress'        => $avgProgress,
            'total_subjects'      => $subjects->count(),
            'active_subjects'     => $subjects->where('modules_count', '>', 0)->count(),
            'total_joined_classes'=> $joinedClasses->count(),
        ];

        // Kelompokkan Modul Belajar Berdasarkan Rombel Kelas yang Diikuti Siswa
        $classesWithModules = $joinedClasses->map(function (SchoolClass $cls) use ($processedModules, $filterStatus, $filterSubject) {
            $classModules = $processedModules->where('class_id', $cls->id)->sortByDesc('is_active_in_class')->values();

            $filteredClassModules = $classModules;
            if ($filterSubject !== 'all') {
                $filteredClassModules = $filteredClassModules->where('subject_id', (int) $filterSubject)->values();
            }
            if ($filterStatus === 'in_progress') {
                $filteredClassModules = $filteredClassModules->filter(fn($m) => $m['is_active_in_class'] || $m['progress_status'] === 'in_progress')->sortByDesc('is_active_in_class')->values();
            } elseif ($filterStatus === 'completed') {
                $filteredClassModules = $filteredClassModules->where('progress_status', 'completed')->sortByDesc('is_active_in_class')->values();
            } elseif ($filterStatus === 'not_started') {
                $filteredClassModules = $filteredClassModules->where('progress_status', 'not_started')->sortByDesc('is_active_in_class')->values();
            }

            $modulesCount = $classModules->count();
            $completedCount = $classModules->where('progress_status', 'completed')->count();
            $inProgressCount = $classModules->filter(fn($m) => $m['is_active_in_class'] || $m['progress_status'] === 'in_progress')->count();
            $notStartedCount = $classModules->where('progress_status', 'not_started')->count();
            $avgProgress = $modulesCount > 0 ? (int) round($classModules->avg('progress_percent')) : 0;
            $teacherNames = $classModules->pluck('teacher_name')->unique()->filter()->values();

            return [
                'id'                     => $cls->id,
                'full_name'              => $cls->full_name,
                'short_name'             => $cls->short_name,
                'grade'                  => $cls->grade,
                'code'                   => $cls->code,
                'major_name'             => $cls->major?->name ?? $cls->major_name,
                'major_code'             => $cls->major?->code ?? $cls->major_name,
                'modules_count'          => $modulesCount,
                'completed_count'        => $completedCount,
                'in_progress_count'      => $inProgressCount,
                'not_started_count'      => $notStartedCount,
                'avg_progress'           => $avgProgress,
                'teachers'               => $teacherNames,
                'teacher_display'        => $teacherNames->isNotEmpty() ? $teacherNames->join(', ') : 'Guru Pengampu',
                'modules'                => $classModules,
                'filtered_modules'       => $filteredClassModules,
                'filtered_modules_count' => $filteredClassModules->count(),
            ];
        });

        $displayedClasses = $classesWithModules;

        // Ekstrak daftar tingkat/jenjang kelas yang diikuti siswa untuk chip filter cepat
        $availableGrades = $joinedClasses->pluck('grade')->unique()->filter()->values()->toArray();

        // Banner selamat datang hanya aktif selama 10 menit pertama sejak siswa terdaftar
        $isNewlyRegistered = $student->created_at ? $student->created_at->gte(now()->subMinutes(10)) : false;

        return view('pages.student.dashboard', compact(
            'student',
            'class',
            'joinedClasses',
            'classesWithModules',
            'displayedClasses',
            'availableGrades',
            'filterClassId',
            'subjects',
            'processedModules',
            'filteredModules',
            'inProgressModules',
            'completedModules',
            'stats',
            'filterStatus',
            'filterSubject',
            'defaultTab',
            'allPendingTasks',
            'isNewlyRegistered'
        ));
    }

    /**
     * Memproses permintaan siswa untuk bergabung ke kelas tertentu menggunakan Kode Kelas.
     */
    public function joinClass(Request $request)
    {
        $validated = $request->validate([
            'class_code' => ['required', 'string', 'max:20'],
        ], [
            'class_code.required' => 'Masukkan kode kelas yang diberikan oleh guru Anda.',
        ]);

        $code = strtoupper(trim($validated['class_code']));
        $schoolClass = SchoolClass::where('code', $code)->first();

        if (!$schoolClass) {
            return back()->withErrors([
                'class_code' => "Kode kelas '{$code}' tidak ditemukan. Pastikan kode yang Anda masukkan sudah benar.",
            ])->withInput();
        }

        $student = $this->student();
        $student->joinClass($schoolClass);

        return redirect()->route('student.dashboard')
            ->with('success', "Selamat! Anda berhasil bergabung ke {$schoolClass->full_name}. Seluruh modul pembelajaran kelas ini telah ditambahkan ke dashboard Anda.");
    }

    /**
     * Memproses permintaan siswa untuk keluar dari rombel kelas tertentu.
     * Menghapus seluruh data nilai, progres belajar, dan submission siswa pada modul-modul di kelas tersebut,
     * tanpa menghapus data kelas dari database maupun dashboard guru.
     */
    public function leaveClass(Request $request, SchoolClass $class)
    {
        $student = $this->student();

        // Validasi apakah siswa memang terdaftar di kelas ini
        if (!in_array($class->id, $student->joinedClassIds())) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Anda tidak terdaftar pada rombel kelas ini.');
        }

        $className = $class->full_name;

        // Jalankan proses keluar kelas dan penghapusan data nilai/submission siswa pada kelas ini
        $student->leaveClass($class);

        return redirect()->route('student.dashboard')
            ->with('success', "Anda telah berhasil keluar dari rombel {$className}. Seluruh progres dan nilai Anda pada kelas tersebut telah dibersihkan.");
    }

    /**
     * Halaman Rombel Kelas Siswa:
     * Menyajikan daftar Mata Pelajaran yang diajarkan pada kelas ini
     * (tanpa menampilkan modul secara langsung).
     */
    public function showClass(Request $request, SchoolClass $class)
    {
        $student = $this->student();

        // Validasi apakah siswa telah bergabung ke kelas ini
        if (!in_array($class->id, $student->joinedClassIds())) {
            abort(403, 'Anda belum terdaftar pada rombel kelas ini. Silakan masukkan kode kelas pada dashboard untuk bergabung.');
        }

        $class->load('major');

        // Query modul terbit di kelas ini untuk menghitung statistik per mapel (hanya kolom metadata)
        $allModules = Module::select([
                'id', 'teacher_id', 'class_id', 'subject_id', 'title',
                'semester', 'status', 'is_active', 'created_at', 'updated_at',
                'has_pre_test', 'has_materi', 'has_video', 'has_embed',
                'has_job_sheet', 'has_lkpd', 'has_post_test'
            ])
            ->where('class_id', $class->id)
            ->where('status', 'published')
            ->with([
                'teacher:id,name',
                'subject:id,name,code,icon,color',
                'jobSheets'             => fn($q) => $q->select(['id', 'module_id']),
                'jobSheets.submissions' => fn($q) => $q->select(['id', 'job_sheet_id', 'student_id'])->where('student_id', $student->id),
                'lkpds'                 => fn($q) => $q->select(['id', 'module_id']),
                'lkpds.submissions'     => fn($q) => $q->select(['id', 'lkpd_id', 'student_id'])->where('student_id', $student->id),
                'studentResults'        => fn($q) => $q->select(['id', 'module_id', 'student_id', 'pre_test_score', 'post_test_score', 'read_components', 'summative_score', 'grading_status', 'updated_at'])->where('student_id', $student->id),
                'videoSummaries'        => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
                'embedSubmissions'      => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
            ])
            ->get();

        // Olah data modul
        $processedModules = $this->processStudentModules($allModules, $student);

        // Ambil daftar mata pelajaran terkait kelas ini
        $subjectIds = $processedModules->pluck('subject_id')->unique()->filter()->values()->toArray();
        if (empty($subjectIds)) {
            $classSubjectsList = Subject::with('teachers')->get();
        } else {
            $classSubjectsList = Subject::whereIn('id', $subjectIds)->with('teachers')->get();
        }

        // Susun data Mapel
        $subjectsWithSummary = $classSubjectsList->map(function (Subject $subj) use ($processedModules) {
            $subjModules = $processedModules->where('subject_id', $subj->id)->values();
            $s1SubjModules = $subjModules->where('semester', '1')->values();
            $s2SubjModules = $subjModules->where('semester', '2')->values();

            $teacherNames = $subjModules->pluck('teacher_name')->unique()->filter()->values();
            if ($teacherNames->isEmpty()) {
                $teacherNames = $subj->teachers->pluck('name')->unique()->values();
            }

            $modulesCount = $subjModules->count();
            $completedCount = $subjModules->where('progress_status', 'completed')->count();
            $inProgressCount = $subjModules->where('progress_status', 'in_progress')->count();
            $notStartedCount = $subjModules->where('progress_status', 'not_started')->count();
            $avgProgress = $modulesCount > 0 ? (int) round($subjModules->avg('progress_percent')) : 0;

            $hasS1 = $s1SubjModules->isNotEmpty();
            $hasS2 = $s2SubjModules->isNotEmpty();

            // Status progres agregat mapel
            $overallStatus = 'not_started';
            if ($modulesCount > 0 && $completedCount === $modulesCount) {
                $overallStatus = 'completed';
            } elseif ($inProgressCount > 0 || $completedCount > 0) {
                $overallStatus = 'in_progress';
            }

            return [
                'id'                => $subj->id,
                'name'              => $subj->name,
                'code'              => $subj->code,
                'icon'              => $subj->icon ?: '📚',
                'color'             => $subj->color ?: 'blue',
                'description'       => $subj->description,
                'teacher_display'   => $teacherNames->isNotEmpty() ? $teacherNames->join(', ') : 'Guru Pengampu',
                'modules_count'     => $modulesCount,
                'completed_count'   => $completedCount,
                'in_progress_count' => $inProgressCount,
                'not_started_count' => $notStartedCount,
                'avg_progress'      => $avgProgress,
                'status'            => $overallStatus,
                'has_s1'            => $hasS1,
                'has_s2'            => $hasS2,
                's1_modules_count'  => $s1SubjModules->count(),
                's2_modules_count'  => $s2SubjModules->count(),
                'semesters'         => array_values(array_filter([
                    $hasS1 ? '1' : null,
                    $hasS2 ? '2' : null,
                ])),
            ];
        });

        // Statistik Keseluruhan Kelas
        $totalModules = $processedModules->count();
        $completedModules = $processedModules->where('progress_status', 'completed')->count();
        $classAvgProgress = $totalModules > 0 ? (int) round($processedModules->avg('progress_percent')) : 0;

        $s1Modules = $processedModules->where('semester', '1');
        $s2Modules = $processedModules->where('semester', '2');

        $classStats = [
            'total_subjects'    => $subjectsWithSummary->count(),
            'active_subjects'   => $subjectsWithSummary->where('modules_count', '>', 0)->count(),
            'total_modules'     => $totalModules,
            'completed_modules' => $completedModules,
            'avg_progress'      => $classAvgProgress,
            's1_total_subjects' => $subjectsWithSummary->where('has_s1', true)->count(),
            's2_total_subjects' => $subjectsWithSummary->where('has_s2', true)->count(),
            's1_total_modules'  => $s1Modules->count(),
            's2_total_modules'  => $s2Modules->count(),
        ];

        return view('pages.student.classes.show', compact(
            'student',
            'class',
            'subjectsWithSummary',
            'classStats'
        ));
    }

    /**
     * Halaman Modul Pembelajaran per Mata Pelajaran pada Rombel Kelas Tertentu:
     * Menyajikan daftar E-Modul yang dibuat oleh guru pengampu untuk mapel tersebut.
     */
    public function showClassSubjectModules(Request $request, SchoolClass $class, Subject $subject)
    {
        $student = $this->student();

        // Validasi apakah siswa telah bergabung ke kelas ini
        if (!in_array($class->id, $student->joinedClassIds())) {
            abort(403, 'Anda belum terdaftar pada rombel kelas ini.');
        }

        $class->load('major');

        // Semester filter jika ada
        $selectedSemester = $request->query('semester', 'all');
        if (!in_array($selectedSemester, ['1', '2'])) {
            $selectedSemester = 'all';
        }

        // Query seluruh modul terbit khusus untuk kelas dan mapel ini (hanya kolom metadata)
        $modulesQuery = Module::select([
                'id', 'teacher_id', 'class_id', 'subject_id', 'title',
                'semester', 'status', 'is_active', 'created_at', 'updated_at',
                'has_pre_test', 'has_materi', 'has_video', 'has_embed',
                'has_job_sheet', 'has_lkpd', 'has_post_test'
            ])
            ->where('class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->where('status', 'published');

        if ($selectedSemester !== 'all') {
            $modulesQuery->where('semester', $selectedSemester);
        }

        $modulesQuery->with([
            'teacher:id,name',
            'subject:id,name,code,icon,color',
            'jobSheets'             => fn($q) => $q->select(['id', 'module_id']),
            'jobSheets.submissions' => fn($q) => $q->select(['id', 'job_sheet_id', 'student_id'])->where('student_id', $student->id),
            'lkpds'                 => fn($q) => $q->select(['id', 'module_id']),
            'lkpds.submissions'     => fn($q) => $q->select(['id', 'lkpd_id', 'student_id'])->where('student_id', $student->id),
            'studentResults'        => fn($q) => $q->select(['id', 'module_id', 'student_id', 'pre_test_score', 'post_test_score', 'read_components', 'summative_score', 'grading_status', 'updated_at'])->where('student_id', $student->id),
            'videoSummaries'        => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
            'embedSubmissions'      => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
        ])->orderByDesc('is_active')->latest('updated_at');

        $allModules = $modulesQuery->get();
        // Seluruh modul diproses
        $processedModules = $this->processStudentModules($allModules, $student);

        // 1. Modul yang baru dibuka / terakhir diakses siswa (Recent Opened Modules - Bagian Teratas, maks 3)
        $recentOpenedModules = $processedModules
            ->filter(fn($m) => !empty($m['last_accessed_at']) || $m['progress_percent'] > 0)
            ->sortByDesc(fn($m) => [($m['is_active_in_class'] ?? false) ? 1 : 0, $m['last_accessed_at'] ?? $m['updated_at']])
            ->take(3)
            ->values();

        // 2. Modul yang baru dibuat / ditambahkan oleh guru (Newest Added Modules - Di Bawahnya)
        $newlyAddedModules = $processedModules
            ->sortByDesc(fn($m) => [($m['is_active_in_class'] ?? false) ? 1 : 0, $m['created_at']])
            ->values();

        // Filter status belajar
        $filterStatus = $request->query('status', 'all');
        $filteredModules = match ($filterStatus) {
            'in_progress' => $newlyAddedModules->where('progress_status', 'in_progress')->values(),
            'completed'   => $newlyAddedModules->where('progress_status', 'completed')->values(),
            'not_started' => $newlyAddedModules->where('progress_status', 'not_started')->values(),
            default       => $newlyAddedModules->values(),
        };

        // Guru Pengampu
        $teacherNames = $processedModules->pluck('teacher_name')->unique()->filter()->values();
        if ($teacherNames->isEmpty()) {
            $subject->loadMissing('teachers');
            $teacherNames = $subject->teachers->pluck('name')->unique()->values();
        }
        $teacherDisplay = $teacherNames->isNotEmpty() ? $teacherNames->join(', ') : 'Guru Pengampu';

        // Statistik Mapel di Kelas Ini
        $totalModulesCount = $processedModules->count();
        $completedModulesCount = $processedModules->where('progress_status', 'completed')->count();
        $inProgressModulesCount = $processedModules->where('progress_status', 'in_progress')->count();
        $notStartedModulesCount = $processedModules->where('progress_status', 'not_started')->count();
        $avgProgress = $totalModulesCount > 0 ? (int) round($processedModules->avg('progress_percent')) : 0;

        $stats = [
            'total_modules'     => $totalModulesCount,
            'completed_modules' => $completedModulesCount,
            'in_progress'       => $inProgressModulesCount,
            'not_started'       => $notStartedModulesCount,
            'avg_progress'      => $avgProgress,
        ];

        return view('pages.student.classes.subject_modules', compact(
            'student',
            'class',
            'subject',
            'processedModules',
            'recentOpenedModules',
            'newlyAddedModules',
            'filteredModules',
            'teacherDisplay',
            'stats',
            'filterStatus',
            'selectedSemester'
        ));
    }

    /**
     * Helper pemrosesan kalkulasi progres modul belajar siswa.
     */
    private function processStudentModules($allModules, Student $student)
    {
        return $allModules->map(function (Module $module) use ($student) {
            $result = $module->studentResults->first();
            $activeComps = $module->activeComponents();
            $totalActive = count($activeComps);

            $completedTasks = 0;
            $pendingTasksList = [];

            // 1. Pre-Test
            if ($module->pre_test_active) {
                if ($result && !is_null($result->pre_test_score)) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = ['type' => 'pre_test', 'label' => 'Pre-Test'];
                }
            }

            // 2. Materi & PPT
            if ($module->materi_active) {
                if ($result && $result->isComponentRead('materi')) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = ['type' => 'materi', 'label' => 'Materi & PPT'];
                }
            }

            // 2. Ringkasan Video
            if ($module->video_active) {
                $hasVideo = $module->videoSummaries->isNotEmpty();
                if ($hasVideo) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = ['type' => 'video', 'label' => 'Ringkasan Video'];
                }
            }

            // 3. Praktik Embed
            if ($module->embed_active) {
                $hasEmbed = $module->embedSubmissions->isNotEmpty();
                if ($hasEmbed) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = ['type' => 'embed', 'label' => 'Praktik Embed'];
                }
            }

            // 4. Job Sheet
            if ($module->job_sheet_active) {
                $jsSubmissions = $module->jobSheets->flatMap->submissions;
                if ($jsSubmissions->isNotEmpty()) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = ['type' => 'job_sheet', 'label' => 'Pengumpulan Job Sheet'];
                }
            }

            // 5. LKPD
            if ($module->lkpd_active) {
                $lkpdSubmissions = $module->lkpds->flatMap->submissions;
                if ($lkpdSubmissions->isNotEmpty()) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = ['type' => 'lkpd', 'label' => 'Pengumpulan LKPD'];
                }
            }

            // 6. Post-Test
            if ($module->post_test_active) {
                if ($result && !is_null($result->post_test_score)) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = ['type' => 'post_test', 'label' => 'Post-Test'];
                }
            }

            $progressPercent = $totalActive > 0 ? (int) round(($completedTasks / $totalActive) * 100) : 100;
            if ($progressPercent >= 100) {
                $progressStatus = 'completed';
            } elseif ($progressPercent > 0) {
                $progressStatus = 'in_progress';
            } else {
                $progressStatus = 'not_started';
            }

            return [
                'id'                => $module->id,
                'title'             => $module->title,
                'description'       => $module->description,
                'class_id'          => $module->class_id,
                'subject_id'        => $module->subject_id,
                'subject'           => $module->subject,
                'subject_name'      => $module->subject?->name ?? 'Mata Pelajaran',
                'teacher_name'      => $module->teacher->name ?? 'Guru Pengampu',
                'created_at'        => $module->created_at,
                'updated_at'        => $module->updated_at,
                'last_accessed_at'  => $result?->updated_at,
                'is_new'            => $module->created_at && $module->created_at->diffInDays(now()) <= 7,
                'active_components' => $activeComps,
                'total_components'  => $totalActive,
                'completed_tasks'   => $completedTasks,
                'progress_percent'  => $progressPercent,
                'progress_status'   => $progressStatus,
                'pending_tasks'     => $pendingTasksList,
                'student_result'    => $result,
                'summative_score'   => $result?->summative_score,
                'grading_status'    => $result?->grading_status,
                'has_pre_test'      => $module->pre_test_active,
                'has_post_test'     => $module->post_test_active,
                'has_materi'        => $module->materi_active,
                'has_video'         => $module->video_active,
                'has_embed'         => $module->embed_active,
                'has_job_sheet'     => $module->job_sheet_active,
                'has_lkpd'          => $module->lkpd_active,
                'semester'          => (string) ($module->semester ?? ''),
                'semester_badge'    => $module->semester ? [
                    'number' => (string) $module->semester,
                    'label'  => $module->semester == '2' ? 'Semester 2 (Genap)' : 'Semester 1 (Ganjil)',
                    'short'  => $module->semester == '2' ? 'S2 Genap' : 'S1 Ganjil',
                    'color'  => $module->semester == '2' ? 'bg-cyan-50 text-cyan-700 border-cyan-200' : 'bg-amber-50 text-amber-700 border-amber-200',
                    'icon'   => $module->semester == '2' ? '📘' : '📙',
                ] : null,
                'is_active_in_class'=> (bool) $module->is_active,
            ];
        });
    }
}
