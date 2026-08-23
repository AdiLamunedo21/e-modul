<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Query modul terbit yang ditugaskan untuk kelas siswa ini pada mapel terpilih
        $modulesQuery = Module::query()
            ->where('class_id', $student->class_id)
            ->where('subject_id', $subject->id)
            ->where('status', 'published')
            ->with([
                'teacher',
                'subject',
                'jobSheets.submissions' => fn($q) => $q->where('student_id', $student->id),
                'lkpds.submissions' => fn($q) => $q->where('student_id', $student->id),
                'studentResults' => fn($q) => $q->where('student_id', $student->id),
                'videoSummaries' => fn($q) => $q->where('student_id', $student->id),
                'embedSubmissions' => fn($q) => $q->where('student_id', $student->id),
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
                        'type' => 'Pre-Test',
                        'title' => 'Kuis Awal (Pre-test)',
                        'icon' => '📝',
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
