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

        // ── 3. Feed Aktivitas Belajar Siswa Terbaru (Real-Time Submissions Log) ──
        $recentLkpd = Submission::with(['student.schoolClass', 'lkpd.module'])
            ->latest('updated_at')
            ->take(6)
            ->get()
            ->map(function ($item) {
                return [
                    'type'         => 'lkpd',
                    'badge'        => 'LKPD',
                    'badge_class'  => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                    'student_name' => $item->student->name ?? 'Siswa',
                    'class_name'   => $item->student->schoolClass->full_name ?? ($item->student->schoolClass ? ($item->student->schoolClass->grade . ' ' . $item->student->schoolClass->major_name) : '-'),
                    'module_title' => $item->lkpd->module->title ?? 'E-Modul',
                    'score'        => $item->manual_score,
                    'time'         => $item->updated_at,
                ];
            });

        $recentJobSheets = JobSheetSubmission::with(['student.schoolClass', 'jobSheet.module'])
            ->latest('updated_at')
            ->take(6)
            ->get()
            ->map(function ($item) {
                return [
                    'type'         => 'job_sheet',
                    'badge'        => 'Job Sheet',
                    'badge_class'  => 'bg-amber-100 text-amber-800 border-amber-200',
                    'student_name' => $item->student->name ?? 'Siswa',
                    'class_name'   => $item->student->schoolClass->full_name ?? ($item->student->schoolClass ? ($item->student->schoolClass->grade . ' ' . $item->student->schoolClass->major_name) : '-'),
                    'module_title' => $item->jobSheet->module->title ?? 'E-Modul',
                    'score'        => $item->manual_score,
                    'time'         => $item->updated_at,
                ];
            });

        $recentResults = StudentResult::with(['student.schoolClass', 'module'])
            ->latest('updated_at')
            ->take(6)
            ->get()
            ->map(function ($item) {
                return [
                    'type'         => 'quiz_result',
                    'badge'        => 'Evaluasi / Kuis',
                    'badge_class'  => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'student_name' => $item->student->name ?? 'Siswa',
                    'class_name'   => $item->student->schoolClass->full_name ?? ($item->student->schoolClass ? ($item->student->schoolClass->grade . ' ' . $item->student->schoolClass->major_name) : '-'),
                    'module_title' => $item->module->title ?? 'E-Modul',
                    'score'        => $item->post_test_score ?? $item->pre_test_score,
                    'time'         => $item->updated_at,
                ];
            });

        $recentActivities = $recentLkpd->concat($recentJobSheets)->concat($recentResults)
            ->sortByDesc('time')
            ->take(8)
            ->values();

        // ── 4. Ringkasan Mata Pelajaran & Kelas ──
        $subjects = Subject::withCount(['modules', 'teachers'])->get();
        $classes = SchoolClass::withCount(['students', 'modules'])->get();

        return view('pages.admin.dashboard', compact(
            'stats',
            'teachers',
            'recentActivities',
            'subjects',
            'classes'
        ));
    }
}
