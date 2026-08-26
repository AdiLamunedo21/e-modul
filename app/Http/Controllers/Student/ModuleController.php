<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\EmbedSubmission;
use App\Models\JobSheet;
use App\Models\JobSheetSubmission;
use App\Models\Lkpd;
use App\Models\Module;
use App\Models\PostTest;
use App\Models\PreTest;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\VideoSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
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
     * Helper otorisasi akses modul bagi siswa.
     */
    private function authorizeStudentAccess(Module $module): void
    {
        $student = $this->student();

        // 1. Pastikan modul berstatus published
        abort_if($module->status !== 'published', 404, 'Modul ini belum dipublikasikan oleh guru.');

        // 2. Pastikan modul ditugaskan untuk rombel kelas siswa
        abort_if($module->class_id !== $student->class_id, 403, 'Modul ini tidak ditugaskan untuk kelas Anda.');

        // 3. Pastikan siswa terdaftar pada mapel modul jika ada plotting
        if ($student->subjects()->exists() && !$student->subjects->contains($module->subject_id)) {
            abort(403, "Anda tidak terdaftar pada mata pelajaran {$module->subject->name}.");
        }
    }

    /**
     * Tampilan Seluruh Modul Belajar Siswa (Redirect ke Dashboard Siswa).
     */
    public function index(Request $request)
    {
        return redirect()->route('student.dashboard');
    }

    /**
     * Tampilan Halaman Khusus Modul per Mata Pelajaran (misal: Informatika, Elektro, dll).
     */
    public function bySubject(Request $request, Subject $subject)
    {
        $student = $this->student();
        $class = $student->schoolClass;

        // Validasi: Pastikan siswa terdaftar pada mata pelajaran ini jika sudah ada plotting
        if ($student->subjects()->exists() && !$student->subjects->contains($subject->id)) {
            return redirect()->route('student.dashboard')
                ->with('error', "Anda tidak terdaftar pada mata pelajaran {$subject->name}.");
        }

        // Query modul terbit yang ditugaskan untuk kelas siswa ini pada mapel terpilih
        $modulesQuery = Module::query()
            ->where('class_id', $student->class_id)
            ->where('subject_id', $subject->id)
            ->where('status', 'published')
            ->with([
                'teacher',
                'subject',
                'jobSheets.submissions' => fn($q) => $q->where('student_id', $student->id),
                'lkpds.submissions'     => fn($q) => $q->where('student_id', $student->id),
                'studentResults'        => fn($q) => $q->where('student_id', $student->id),
                'videoSummaries'        => fn($q) => $q->where('student_id', $student->id),
                'embedSubmissions'      => fn($q) => $q->where('student_id', $student->id),
            ])
            ->latest('updated_at');

        $allModules = $modulesQuery->get();
        $processedModules = $this->processModules($allModules, $student);

        // Guru Pengampu Mapel: ambil dari modul jika ada, atau dari relasi subject->teachers
        $teacherNames = $processedModules->pluck('teacher_name')->unique()->filter()->values();
        if ($teacherNames->isEmpty()) {
            $subject->loadMissing('teachers');
            $teacherNames = $subject->teachers->pluck('name')->unique()->values();
        }
        $teacherDisplay = $teacherNames->isNotEmpty() ? $teacherNames->join(', ') : 'Guru Pengampu Belum Ditugaskan';

        // Filter tab berdasarkan status belajar
        $filterStatus = $request->query('status', 'all');
        $filteredModules = match ($filterStatus) {
            'in_progress' => $processedModules->where('progress_status', 'in_progress'),
            'completed'   => $processedModules->where('progress_status', 'completed'),
            'not_started' => $processedModules->where('progress_status', 'not_started'),
            default       => $processedModules,
        };

        // Metrik Statistik khusus mapel ini
        $totalModulesCount = $processedModules->count();
        $completedModulesCount = $processedModules->where('progress_status', 'completed')->count();
        $inProgressModulesCount = $processedModules->where('progress_status', 'in_progress')->count();
        $notStartedModulesCount = $processedModules->where('progress_status', 'not_started')->count();
        $avgProgress = $totalModulesCount > 0 ? (int) round($processedModules->avg('progress_percent')) : 0;

        $gradedScores = $processedModules->pluck('summative_score')->filter(fn($v) => !is_null($v));
        $avgScore = $gradedScores->count() > 0 ? (int) round($gradedScores->avg()) : 0;

        $stats = [
            'total_modules'     => $totalModulesCount,
            'completed_modules' => $completedModulesCount,
            'in_progress'       => $inProgressModulesCount,
            'not_started'       => $notStartedModulesCount,
            'avg_progress'      => $avgProgress,
            'avg_score'         => $avgScore,
        ];

        return view('pages.student.modules.subject', compact(
            'student',
            'class',
            'subject',
            'teacherDisplay',
            'teacherNames',
            'processedModules',
            'filteredModules',
            'stats',
            'filterStatus'
        ));
    }

    /**
     * =========================================================================
     * ANTARMUKA INTERAKTIF MULAI BELAJAR E-MODUL (5 Bagian & 15 Komponen)
     * =========================================================================
     * Menampilkan antarmuka player pembelajaran terpadu bagi siswa yang meliputi:
     * - Bagian 1: Bagian Awal (Cover, Kata Pengantar, Daftar Isi, Petunjuk)
     * - Bagian 2: Pendahuluan (Tujuan, Peta Konsep, Glosarium, Pre-test Diagnostik)
     * - Bagian 3: Kegiatan Belajar (Materi, PPT Slide, Video YouTube & Form Resume)
     * - Bagian 4: Evaluasi & Latihan (Simulator Embed & Bukti, Job Sheet PDF, LKPD PDF)
     * - Bagian 5: Bagian Akhir (Post-test Evaluasi, Daftar Pustaka & Rekap Nilai)
     */
    public function show(Request $request, Module $module)
    {
        $this->authorizeStudentAccess($module);
        $student = $this->student();

        // Eager load seluruh relasi modul dan submissions siswa ini
        $module->load([
            'teacher',
            'subject',
            'schoolClass.major',
            'preTest.questions',
            'postTest.questions',
            'jobSheets.submissions' => fn($q) => $q->where('student_id', $student->id),
            'lkpds.submissions'     => fn($q) => $q->where('student_id', $student->id),
            'studentResults'        => fn($q) => $q->where('student_id', $student->id),
            'videoSummaries'        => fn($q) => $q->where('student_id', $student->id),
            'embedSubmissions'      => fn($q) => $q->where('student_id', $student->id),
        ]);

        // Hasil belajar dan tugas siswa
        $studentResult = $module->studentResults->first();
        $videoSummary = $module->videoSummaries->first();
        $embedSubmission = $module->embedSubmissions->first();

        $jobSheet = $module->jobSheets->first();
        $jobSheetSubmission = $jobSheet ? $jobSheet->submissions->first() : null;

        $lkpd = $module->lkpds->first();
        $lkpdSubmission = $lkpd ? $lkpd->submissions->first() : null;

        // Data JSON komponen modul
        $informasiUmum = $module->informasi_umum_data ?? [];
        $materiData = $module->materi_data ?? [];
        $videoData = $module->video_data ?? [];
        $embedData = $module->embed_data ?? [];
        $jobSheetData = $module->job_sheet_data ?? [];
        $lkpdData = $module->lkpd_data ?? [];

        // Struktur 5 Bagian
        $sections = $module->moduleSectionsSummary();

        // Hitung progres belajar
        $activeComps = $module->activeComponents();
        $totalActive = count($activeComps);
        $completedTasks = 0;

        if ($module->pre_test_active && $studentResult && $studentResult->pre_test_score !== null) {
            $completedTasks++;
        }
        if ($module->video_active && $videoSummary) {
            $completedTasks++;
        }
        if ($module->embed_active && $embedSubmission) {
            $completedTasks++;
        }
        if ($module->job_sheet_active && $jobSheetSubmission) {
            $completedTasks++;
        }
        if ($module->lkpd_active && $lkpdSubmission) {
            $completedTasks++;
        }
        if ($module->post_test_active && $studentResult && $studentResult->post_test_score !== null) {
            $completedTasks++;
        }

        $progressPercent = $totalActive > 0 ? min(100, (int) round(($completedTasks / $totalActive) * 100)) : 100;

        // Bagian / Tab aktif saat ini (1 sampai 5)
        $currentSection = (int) $request->query('section', 1);
        if ($currentSection < 1 || $currentSection > 5) {
            $currentSection = 1;
        }

        return view('pages.student.modules.show', compact(
            'student',
            'module',
            'sections',
            'currentSection',
            'informasiUmum',
            'materiData',
            'videoData',
            'embedData',
            'jobSheetData',
            'lkpdData',
            'studentResult',
            'videoSummary',
            'embedSubmission',
            'jobSheet',
            'jobSheetSubmission',
            'lkpd',
            'lkpdSubmission',
            'completedTasks',
            'totalActive',
            'progressPercent'
        ));
    }

    /**
     * Pengerjaan Kuis Diagnostik Pre-test Siswa (Evaluasi Otomatis).
     */
    public function submitPreTest(Request $request, Module $module)
    {
        $this->authorizeStudentAccess($module);
        abort_if(!$module->has_pre_test, 403, 'Komponen Pre-test tidak aktif pada modul ini.');

        $preTest = $module->preTest()->with('questions')->first();
        abort_if(!$preTest || $preTest->questions->isEmpty(), 422, 'Soal Pre-test belum dikonfigurasi oleh guru.');

        $student = $this->student();
        $answers = $request->input('answers', []);
        $questions = $preTest->questions;
        $totalQuestions = $questions->count();

        $correctCount = 0;
        $earnedScore = 0;
        $totalPossibleScore = (int) $questions->sum('score_weight');

        foreach ($questions as $q) {
            $studentAns = strtoupper(trim((string) ($answers[$q->id] ?? '')));
            if ($studentAns !== '' && $studentAns === strtoupper(trim((string) $q->correct_answer))) {
                $correctCount++;
                $earnedScore += (int) ($q->score_weight ?: 10);
            }
        }

        // Kalkulasi skor persentase 0 - 100
        $finalScore = $totalPossibleScore > 0
            ? (int) round(($earnedScore / $totalPossibleScore) * 100)
            : (int) round(($correctCount / max(1, $totalQuestions)) * 100);

        if ($finalScore > 100) $finalScore = 100;
        if ($finalScore < 0) $finalScore = 0;

        // Catat ke model StudentResult
        $result = StudentResult::firstOrNew([
            'student_id' => $student->id,
            'module_id'  => $module->id,
        ]);

        $result->pre_test_score = $finalScore;
        $result->summative_score = $result->calculateSummativeScore($module);
        if (!$result->grading_status) {
            $result->grading_status = 'pending';
        }
        $result->save();

        return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 2])
            ->with('success', "Kuis Pre-test berhasil diselesaikan! Skor Anda: {$finalScore}/100 ({$correctCount} dari {$totalQuestions} soal benar).");
    }

    /**
     * Pengumpulan Resume Video Pembelajaran Siswa.
     */
    public function submitVideoSummary(Request $request, Module $module)
    {
        $this->authorizeStudentAccess($module);
        abort_if(!$module->has_video, 403, 'Komponen Video tidak aktif pada modul ini.');

        $validated = $request->validate([
            'summary_text' => ['required', 'string', 'min:20'],
        ], [
            'summary_text.required' => 'Teks ringkasan materi video wajib diisi.',
            'summary_text.min'      => 'Ringkasan materi video minimal harus terdiri dari 20 karakter.',
        ]);

        $student = $this->student();

        $existing = VideoSummary::where('module_id', $module->id)->where('student_id', $student->id)->first();
        if ($existing && $existing->manual_score !== null) {
            return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 3])
                ->with('error', 'Tugas ringkasan video telah dinilai oleh guru dan tidak dapat diubah lagi.');
        }

        VideoSummary::updateOrCreate(
            ['module_id' => $module->id, 'student_id' => $student->id],
            ['summary_text' => $validated['summary_text']]
        );

        $this->ensureStudentResultExists($module, $student);

        return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 3])
            ->with('success', 'Ringkasan materi video YouTube berhasil disimpan!');
    }

    /**
     * Pengunggahan Bukti Screenshot Praktik Simulator Embed Siswa.
     */
    public function submitEmbed(Request $request, Module $module)
    {
        $this->authorizeStudentAccess($module);
        abort_if(!$module->has_embed, 403, 'Komponen Praktik Embed tidak aktif pada modul ini.');

        $validated = $request->validate([
            'screenshot' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'screenshot.required' => 'Berkas gambar tangkapan layar praktik wajib dipilih.',
            'screenshot.image'    => 'Berkas harus berupa file gambar valid.',
            'screenshot.mimes'    => 'Format gambar yang didukung: JPG, PNG, WEBP.',
            'screenshot.max'      => 'Ukuran berkas gambar maksimal 5 MB.',
        ]);

        $student = $this->student();

        $existing = EmbedSubmission::where('module_id', $module->id)->where('student_id', $student->id)->first();
        if ($existing && $existing->manual_score !== null) {
            return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 4])
                ->with('error', 'Tugas praktik simulator telah dinilai oleh guru dan tidak dapat diubah lagi.');
        }

        // Simpan berkas ke storage
        $path = $request->file('screenshot')->store('submissions/embed', 'public');

        if ($existing && $existing->screenshot_path) {
            Storage::disk('public')->delete($existing->screenshot_path);
        }

        EmbedSubmission::updateOrCreate(
            ['module_id' => $module->id, 'student_id' => $student->id],
            ['screenshot_path' => $path]
        );

        $this->ensureStudentResultExists($module, $student);

        return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 4])
            ->with('success', 'Bukti tangkapan layar (screenshot) praktik simulator berhasil diunggah!');
    }

    /**
     * Pengunggahan Lembar Kerja Praktik (Job Sheet PDF) Siswa.
     */
    public function submitJobSheet(Request $request, Module $module)
    {
        $this->authorizeStudentAccess($module);
        abort_if(!$module->has_job_sheet, 403, 'Komponen Job Sheet tidak aktif pada modul ini.');

        $validated = $request->validate([
            'job_sheet_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'job_sheet_file.required' => 'Berkas laporan Job Sheet wajib dipilih.',
            'job_sheet_file.file'     => 'Berkas tidak valid.',
            'job_sheet_file.mimes'    => 'Format laporan Job Sheet harus berupa dokumen PDF (.pdf).',
            'job_sheet_file.max'      => 'Ukuran berkas laporan maksimal 10 MB.',
        ]);

        $student = $this->student();
        $jobSheet = $module->jobSheets()->firstOrCreate(['module_id' => $module->id], ['pdf_file_path' => '']);

        $existing = JobSheetSubmission::where('job_sheet_id', $jobSheet->id)->where('student_id', $student->id)->first();
        if ($existing && $existing->manual_score !== null) {
            return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 4])
                ->with('error', 'Laporan Job Sheet telah dinilai oleh guru dan tidak dapat diubah lagi.');
        }

        $path = $request->file('job_sheet_file')->store('submissions/job-sheet', 'public');

        if ($existing && $existing->uploaded_file_path) {
            Storage::disk('public')->delete($existing->uploaded_file_path);
        }

        JobSheetSubmission::updateOrCreate(
            ['job_sheet_id' => $jobSheet->id, 'student_id' => $student->id],
            ['uploaded_file_path' => $path]
        );

        $this->ensureStudentResultExists($module, $student);

        return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 4])
            ->with('success', 'Berkas laporan praktikum Job Sheet PDF berhasil dikirim!');
    }

    /**
     * Pengunggahan Tugas LKPD (PDF) Siswa.
     */
    public function submitLkpd(Request $request, Module $module)
    {
        $this->authorizeStudentAccess($module);
        abort_if(!$module->has_lkpd, 403, 'Komponen LKPD tidak aktif pada modul ini.');

        $validated = $request->validate([
            'lkpd_file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'lkpd_file.required' => 'Berkas jawaban LKPD wajib dipilih.',
            'lkpd_file.file'     => 'Berkas tidak valid.',
            'lkpd_file.mimes'    => 'Format dokumen tugas LKPD harus berupa berkas PDF (.pdf).',
            'lkpd_file.max'      => 'Ukuran berkas tugas LKPD maksimal 10 MB.',
        ]);

        $student = $this->student();
        $lkpd = $module->lkpds()->firstOrCreate(['module_id' => $module->id], ['pdf_file_path' => '']);

        $existing = Submission::where('lkpd_id', $lkpd->id)->where('student_id', $student->id)->first();
        if ($existing && $existing->manual_score !== null) {
            return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 4])
                ->with('error', 'Tugas LKPD telah dinilai oleh guru dan tidak dapat diubah lagi.');
        }

        $path = $request->file('lkpd_file')->store('submissions/lkpd', 'public');

        if ($existing && $existing->uploaded_file_path) {
            Storage::disk('public')->delete($existing->uploaded_file_path);
        }

        Submission::updateOrCreate(
            ['lkpd_id' => $lkpd->id, 'student_id' => $student->id],
            ['uploaded_file_path' => $path]
        );

        $this->ensureStudentResultExists($module, $student);

        return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 4])
            ->with('success', 'Dokumen tugas LKPD PDF berhasil dikirim!');
    }

    /**
     * Pengerjaan Evaluasi Akhir Post-test Siswa (Evaluasi Otomatis & Agregasi Nilai Sumatif).
     */
    public function submitPostTest(Request $request, Module $module)
    {
        $this->authorizeStudentAccess($module);
        abort_if(!$module->has_post_test, 403, 'Komponen Post-test tidak aktif pada modul ini.');

        $postTest = $module->postTest()->with('questions')->first();
        abort_if(!$postTest || $postTest->questions->isEmpty(), 422, 'Soal Post-test belum dikonfigurasi oleh guru.');

        $student = $this->student();
        $answers = $request->input('answers', []);
        $questions = $postTest->questions;
        $totalQuestions = $questions->count();

        $correctCount = 0;
        $earnedScore = 0;
        $totalPossibleScore = (int) $questions->sum('score_weight');

        foreach ($questions as $q) {
            $studentAns = strtoupper(trim((string) ($answers[$q->id] ?? '')));
            if ($studentAns !== '' && $studentAns === strtoupper(trim((string) $q->correct_answer))) {
                $correctCount++;
                $earnedScore += (int) ($q->score_weight ?: 10);
            }
        }

        // Kalkulasi skor persentase 0 - 100
        $finalScore = $totalPossibleScore > 0
            ? (int) round(($earnedScore / $totalPossibleScore) * 100)
            : (int) round(($correctCount / max(1, $totalQuestions)) * 100);

        if ($finalScore > 100) $finalScore = 100;
        if ($finalScore < 0) $finalScore = 0;

        // Catat ke model StudentResult
        $result = StudentResult::firstOrNew([
            'student_id' => $student->id,
            'module_id'  => $module->id,
        ]);

        $result->post_test_score = $finalScore;
        $result->summative_score = $result->calculateSummativeScore($module);
        if (!$result->grading_status) {
            $result->grading_status = 'pending';
        }
        $result->save();

        return redirect()->route('student.modules.show', ['module' => $module->id, 'section' => 5])
            ->with('success', "Evaluasi Post-test berhasil diselesaikan! Skor akhir Anda: {$finalScore}/100 ({$correctCount} dari {$totalQuestions} soal benar).");
    }

    /**
     * Pembatalan Unggahan Tugas Mandiri yang Masih Berstatus Pending (Re-submission Policy).
     */
    public function cancelSubmission(Request $request, Module $module, string $type)
    {
        $this->authorizeStudentAccess($module);
        $student = $this->student();

        switch ($type) {
            case 'video':
                $sub = VideoSummary::where('module_id', $module->id)->where('student_id', $student->id)->first();
                if ($sub && $sub->manual_score === null) {
                    $sub->delete();
                    return back()->with('success', 'Ringkasan video dibatalkan. Anda dapat menulis ulang ringkasan Anda.');
                }
                break;

            case 'embed':
                $sub = EmbedSubmission::where('module_id', $module->id)->where('student_id', $student->id)->first();
                if ($sub && $sub->manual_score === null) {
                    if ($sub->screenshot_path) {
                        Storage::disk('public')->delete($sub->screenshot_path);
                    }
                    $sub->delete();
                    return back()->with('success', 'Unggahan screenshot simulator dibatalkan. Anda dapat mengunggah ulang.');
                }
                break;

            case 'job_sheet':
                $jobSheet = $module->jobSheets->first();
                if ($jobSheet) {
                    $sub = JobSheetSubmission::where('job_sheet_id', $jobSheet->id)->where('student_id', $student->id)->first();
                    if ($sub && $sub->manual_score === null) {
                        if ($sub->uploaded_file_path) {
                            Storage::disk('public')->delete($sub->uploaded_file_path);
                        }
                        $sub->delete();
                        return back()->with('success', 'Unggahan laporan Job Sheet PDF dibatalkan. Anda dapat mengunggah ulang.');
                    }
                }
                break;

            case 'lkpd':
                $lkpd = $module->lkpds->first();
                if ($lkpd) {
                    $sub = Submission::where('lkpd_id', $lkpd->id)->where('student_id', $student->id)->first();
                    if ($sub && $sub->manual_score === null) {
                        if ($sub->uploaded_file_path) {
                            Storage::disk('public')->delete($sub->uploaded_file_path);
                        }
                        $sub->delete();
                        return back()->with('success', 'Unggahan tugas LKPD PDF dibatalkan. Anda dapat mengunggah ulang.');
                    }
                }
                break;
        }

        return back()->with('error', 'Tugas tidak dapat dibatalkan karena telah dinilai oleh guru atau tidak ditemukan.');
    }

    /**
     * Memastikan record StudentResult dibuat jika belum ada.
     */
    private function ensureStudentResultExists(Module $module, Student $student): StudentResult
    {
        $result = StudentResult::firstOrNew([
            'student_id' => $student->id,
            'module_id'  => $module->id,
        ]);

        if (!$result->exists) {
            $result->summative_score = 0;
            $result->grading_status = 'pending';
            $result->save();
        }

        return $result;
    }

    /**
     * Helper pemrosesan kalkulasi status dan progres modul siswa.
     */
    private function processModules($modules, Student $student)
    {
        return $modules->map(function (Module $module) use ($student) {
            $result = $module->studentResults->first();
            $activeComps = $module->activeComponents();
            $totalActive = count($activeComps);

            $completedTasks = 0;
            $pendingTasksList = [];

            // 1. Pre-Test
            if ($module->pre_test_active) {
                if ($result && $result->pre_test_score !== null) {
                    $completedTasks++;
                } else {
                    $pendingTasksList[] = [
                        'type'  => 'Pre-Test',
                        'title' => 'Kuis Awal (Pre-test)',
                        'icon'  => '📝',
                        'badge' => 'Kuis',
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
                        'type'  => 'Video',
                        'title' => 'Tonton Video & Tulis Ringkasan',
                        'icon'  => '🎬',
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
                        'type'  => 'Embed',
                        'title' => 'Eksplorasi Simulator / Embed Code',
                        'icon'  => '⚡',
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
                        'type'  => 'Job Sheet',
                        'title' => 'Unggah Lembar Kerja Job Sheet',
                        'icon'  => '📋',
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
                        'type'  => 'LKPD',
                        'title' => 'Kirim Jawaban Tugas LKPD',
                        'icon'  => '📑',
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
                        'type'  => 'Post-Test',
                        'title' => 'Kuis Penutup (Post-test)',
                        'icon'  => '🎯',
                        'badge' => 'Evaluasi',
                    ];
                }
            }

            $progressPercent = $totalActive > 0 ? (int) round(($completedTasks / $totalActive) * 100) : 0;
            if ($progressPercent > 100) {
                $progressPercent = 100;
            }

            $progressStatus = match (true) {
                $progressPercent >= 100 => 'completed',
                $progressPercent > 0    => 'in_progress',
                default                 => 'not_started',
            };

            return [
                'id'                => $module->id,
                'title'             => $module->title,
                'description'       => $module->description,
                'subject_id'        => $module->subject_id,
                'subject'           => $module->subject,
                'subject_name'      => $module->subject?->name ?? 'Mata Pelajaran',
                'teacher_name'      => $module->teacher->name ?? 'Guru Pengampu',
                'updated_at'        => $module->updated_at,
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
            ];
        });
    }
}
