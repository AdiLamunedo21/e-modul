<?php

namespace App\Providers;

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
            if ($student) {
                $joinedClassIds = $student->joinedClassIds();
                if (!empty($joinedClassIds)) {
                    $query = $student->subjects()->exists() ? $student->subjects() : Subject::query();
                    $subjects = $query->withCount(['modules' => function ($q) use ($joinedClassIds) {
                        $q->whereIn('class_id', $joinedClassIds)->where('status', 'published');
                    }])->get();
                } else {
                    $subjects = Subject::all();
                }
            } else {
                $subjects = Subject::all();
            }
            $view->with('studentSidebarSubjects', $subjects);
        });
    }
}

