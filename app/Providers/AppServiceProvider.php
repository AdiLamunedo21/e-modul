<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
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
        // Paksa skema HTTPS jika diakses di production, URL https, ngrok, atau proxy dengan SSL
        if (app()->environment('production') || str_starts_with((string) config('app.url'), 'https://') || request()->header('x-forwarded-proto') === 'https' || str_contains(request()->header('host', ''), 'ngrok')) {
            URL::forceScheme('https');
        }

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
                    $studentSubjectIds = $student->subjects()->pluck('subjects.id')->toArray();

                    // Hitung jumlah modul dalam progres dan selesai untuk badge sidebar (hanya kolom metadata penting)
                    $modulesQuery = Module::select([
                            'id', 'class_id', 'subject_id', 'is_active',
                            'has_pre_test', 'has_materi', 'has_video', 'has_embed',
                            'has_job_sheet', 'has_lkpd', 'has_post_test'
                        ])
                        ->whereIn('class_id', $joinedClassIds)
                        ->where(function ($q) {
                            $q->where('status', 'published')
                              ->orWhere('is_active', true);
                        });

                    if (!empty($studentSubjectIds)) {
                        $modulesQuery->whereIn('subject_id', $studentSubjectIds);
                    }

                    $modules = $modulesQuery->with([
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

                    // Fallback jika belum ada modul dalam progres, namun ada modul yang belum selesai
                    if ($inProgressCount === 0 && $modules->isNotEmpty() && $completedCount < $modules->count()) {
                        $inProgressCount = 1;
                    }

                    $sidebarStats['in_progress']   = $inProgressCount;
                    $sidebarStats['completed']     = $completedCount;
                    $sidebarStats['total_modules'] = $modules->count();

                    // Cek apakah ada Kuis Live aktif untuk kelas yang diikuti siswa
                    $hasActiveLiveQuiz = \App\Models\LiveQuizSession::where('is_active', true)
                        ->where('status', '!=', 'finished')
                        ->whereHas('module', fn($mq) => $mq->where('is_active', true))
                        ->where(function ($q) use ($joinedClassIds) {
                            $q->whereNull('class_id')
                              ->orWhereIn('class_id', $joinedClassIds)
                              ->orWhereHas('module', fn($mq) => $mq->whereIn('class_id', $joinedClassIds));
                        })
                        ->exists();
                }
            }
            $view->with('sidebarStats', $sidebarStats)
                 ->with('hasActiveLiveQuiz', $hasActiveLiveQuiz ?? false);
        });

        // View Composer untuk Sidebar Guru: Menentukan apakah ada modul pembelajaran yang aktif di kelas
        View::composer('layouts.teacher.sidebar', function ($view) {
            $teacher = Auth::guard('teacher')->user();
            $hasActiveModule = false;

            if ($teacher) {
                $hasActiveModule = Module::where('teacher_id', $teacher->id)
                    ->where('is_active', true)
                    ->exists();
            }

            $view->with('hasActiveModule', $hasActiveModule);
        });
    }
}

