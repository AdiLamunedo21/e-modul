<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer untuk Sidebar & Mobile Nav Siswa: Membagikan daftar Mata Pelajaran & Jumlah Modul
        View::composer(['layouts.student.sidebar', 'layouts.student.mobile-nav'], function ($view) {
            $student = Auth::guard('student')->user();
            $sidebarStats = [
                'in_progress'   => 0,
                'completed'     => 0,
                'total_modules' => 0,
            ];

            if ($student) {
                $joinedClassIds = $student->joinedClassIds();
                if (!empty($joinedClassIds)) {
                    $hasCustomSubjects = $student->relationLoaded('subjects')
                        ? $student->subjects->isNotEmpty()
                        : $student->subjects()->exists();

                    $query = $hasCustomSubjects ? $student->subjects() : Subject::query();
                    $subjects = $query->select(['subjects.id', 'subjects.name', 'subjects.code', 'subjects.icon', 'subjects.color'])
                        ->withCount(['modules' => function ($q) use ($joinedClassIds) {
                            $q->whereIn('class_id', $joinedClassIds)->where('status', 'published');
                        }])->get();

                    // Hitung jumlah modul dalam progres dan selesai untuk badge sidebar (hanya kolom metadata penting)
                    $modules = Module::select([
                            'id', 'class_id', 'is_active',
                            'has_pre_test', 'has_materi', 'has_video', 'has_embed',
                            'has_job_sheet', 'has_lkpd', 'has_post_test'
                        ])
                        ->whereIn('class_id', $joinedClassIds)
                        ->where('status', 'published')
                        ->with([
                            'studentResults'        => fn($q) => $q->select(['id', 'module_id', 'student_id', 'pre_test_score', 'post_test_score', 'read_components'])->where('student_id', $student->id),
                            'jobSheets'             => fn($q) => $q->select(['id', 'module_id']),
                            'jobSheets.submissions' => fn($q) => $q->select(['id', 'job_sheet_id', 'student_id'])->where('student_id', $student->id),
                            'lkpds'                 => fn($q) => $q->select(['id', 'module_id']),
                            'lkpds.submissions'     => fn($q) => $q->select(['id', 'lkpd_id', 'student_id'])->where('student_id', $student->id),
                            'videoSummaries'        => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
                            'embedSubmissions'      => fn($q) => $q->select(['id', 'module_id', 'student_id'])->where('student_id', $student->id),
                        ])->get();

                    $inProgressCount = 0;
                    $completedCount = 0;

                    foreach ($modules as $mod) {
                        $res = $mod->studentResults->first();
                        $activeCount = count($mod->activeComponents());
                        if ($activeCount === 0) continue;

                        $doneCount = 0;
                        if ($mod->pre_test_active && $res && $res->pre_test_score !== null) $doneCount++;
                        if ($mod->materi_active && $res && $res->isComponentRead('materi')) $doneCount++;
                        if ($mod->video_active && $mod->videoSummaries->isNotEmpty()) $doneCount++;
                        if ($mod->embed_active && $mod->embedSubmissions->isNotEmpty()) $doneCount++;
                        if ($mod->job_sheet_active && $mod->jobSheets->some(fn($js) => $js->submissions->isNotEmpty())) $doneCount++;
                        if ($mod->lkpd_active && $mod->lkpds->some(fn($lk) => $lk->submissions->isNotEmpty())) $doneCount++;
                        if ($mod->post_test_active && $res && $res->post_test_score !== null) $doneCount++;

                        $pct = (int) round(($doneCount / $activeCount) * 100);
                        if ($pct >= 100) {
                            $completedCount++;
                        } elseif ($pct > 0 || (bool) $mod->is_active) {
                            $inProgressCount++;
                        }
                    }

                    $sidebarStats['in_progress']   = $inProgressCount;
                    $sidebarStats['completed']     = $completedCount;
                    $sidebarStats['total_modules'] = $modules->count();
                } else {
                    $subjects = Subject::all();
                }
            } else {
                $subjects = Subject::all();
            }
            $view->with('studentSidebarSubjects', $subjects);
            $view->with('sidebarStats', $sidebarStats);
        });
    }
}

