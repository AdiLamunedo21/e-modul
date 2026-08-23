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
            if ($student && $student->class_id) {
                $query = $student->subjects()->exists() ? $student->subjects() : Subject::query();
                $subjects = $query->withCount(['modules' => function ($q) use ($student) {
                    $q->where('class_id', $student->class_id)->where('status', 'published');
                }])->get();
            } else {
                $subjects = Subject::all();
            }
            $view->with('studentSidebarSubjects', $subjects);
        });
    }
}

