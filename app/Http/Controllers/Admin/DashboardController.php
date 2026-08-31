<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobSheetSubmission;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilan Dashboard Utama Supervisi Admin.
     */
    public function index()
    {
        // ── 1. Metrik Utama Statistik Real-Time ──
        $totalTeachers = Teacher::count();
        $totalStudents = Student::count();
        $totalClasses = SchoolClass::count();
        $totalSubjects = Subject::count();
        $totalModules = Module::count();
        $publishedModules = Module::where('status', 'published')->count();
        $draftModules = Module::where('status', 'draft')->count();

        $totalLkpdSubmissions = Submission::count();
        $totalJobSheetSubmissions = JobSheetSubmission::count();
        $totalStudentResults = StudentResult::count();
        $totalSubmissions = $totalLkpdSubmissions + $totalJobSheetSubmissions + $totalStudentResults;

        $stats = [
            'total_teachers'            => $totalTeachers,
            'total_students'            => $totalStudents,
            'total_classes'             => $totalClasses,
            'total_subjects'            => $totalSubjects,
            'total_modules'             => $totalModules,
            'published_modules'         => $publishedModules,
            'draft_modules'             => $draftModules,
            'total_lkpd_submissions'    => $totalLkpdSubmissions,
            'total_jobsheet_submissions'=> $totalJobSheetSubmissions,
            'total_student_results'     => $totalStudentResults,
            'total_submissions'         => $totalSubmissions,
        ];

        // ── 2. Produktivitas & Monitoring Guru ──
        $teachers = Teacher::with(['subjects', 'modules.schoolClass'])
            ->withCount([
                'modules',
                'modules as published_modules_count' => fn($q) => $q->where('status', 'published'),
                'modules as draft_modules_count'     => fn($q) => $q->where('status', 'draft'),
            ])
            ->latest('updated_at')
            ->take(10)
            ->get();

        // ── 3. Ringkasan Mata Pelajaran ──
        $subjects = Subject::withCount(['modules', 'teachers'])->get();

        return view('pages.admin.dashboard', compact(
            'stats',
            'teachers',
            'subjects'
        ));
    }
}
