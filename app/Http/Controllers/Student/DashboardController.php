<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Student;
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
     * Menyajikan ringkasan KPI belajar real-time, modul kelas yang ditugaskan,
     * persentase progres 5 bagian modul, antrean tugas To-Do, dan rekap nilai mandiri.
     */
    public function index(Request $request)
    {
        $student = $this->student();
        $class = $student->schoolClass;

        // Query modul terbit yang ditugaskan untuk kelas siswa ini
        $modulesQuery = Module::query()
            ->where('class_id', $student->class_id)
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
                'subject'           => $module->subject,
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

        // Filter tab berdasarkan status belajar (all, in_progress, completed, not_started) dan batasi maksimal 3 modul
        $filterStatus = $request->query('status', 'all');
        $filteredModules = match ($filterStatus) {
            'in_progress' => $processedModules->where('progress_status', 'in_progress'),
            'completed'   => $processedModules->where('progress_status', 'completed'),
            'not_started' => $processedModules->where('progress_status', 'not_started'),
            default       => $processedModules,
        };
        $filteredModules = $filteredModules->take(3);

        // KPI Metrik Statistik Siswa
        $totalModulesCount = $processedModules->count();
        $completedModulesCount = $processedModules->where('progress_status', 'completed')->count();
        $inProgressModulesCount = $processedModules->where('progress_status', 'in_progress')->count();
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
        ];

        return view('pages.student.dashboard', compact(
            'student',
            'class',
            'filteredModules',
            'stats',
            'filterStatus',
            'allPendingTasks'
        ));
    }
}
