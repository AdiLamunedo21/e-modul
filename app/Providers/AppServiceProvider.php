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
        // View Composer untuk Sidebar Siswa: Membagikan daftar Mata Pelajaran & Jumlah Modul
        View::composer('layouts.student.sidebar', function ($view) {
            $student = Auth::guard('student')->user();
            $sidebarStats = [
                'in_progress' => 0,
                'completed'   => 0,
            ];

            if ($student) {
                $joinedClassIds = $student->joinedClassIds();
                if (!empty($joinedClassIds)) {
                    $query = $student->subjects()->exists() ? $student->subjects() : Subject::query();
                    $subjects = $query->withCount(['modules' => function ($q) use ($joinedClassIds) {
                        $q->whereIn('class_id', $joinedClassIds)->where('status', 'published');
                    }])->get();

                    // Hitung jumlah modul dalam progres dan selesai untuk badge sidebar
                    $modules = Module::whereIn('class_id', $joinedClassIds)
                        ->where('status', 'published')
                        ->with([
                            'studentResults'        => fn($q) => $q->where('student_id', $student->id),
                            'jobSheets.submissions' => fn($q) => $q->where('student_id', $student->id),
                            'lkpds.submissions'     => fn($q) => $q->where('student_id', $student->id),
                            'videoSummaries'        => fn($q) => $q->where('student_id', $student->id),
                            'embedSubmissions'      => fn($q) => $q->where('student_id', $student->id),
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

                    $sidebarStats['in_progress'] = $inProgressCount;
                    $sidebarStats['completed']   = $completedCount;
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

