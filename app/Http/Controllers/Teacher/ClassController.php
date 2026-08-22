<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: ClassController
 * =============================================================================
 * MANAJEMEN KELAS BINAAN & DIREKTORI SISWA:
 * -----------------------------------------------------------------------------
 * Controller ini melayani kebutuhan guru dalam memantau kelas binaan, direktori
 * siswa per kelas, partisipasi pengerjaan modul, serta rekapitulasi progres
 * akademik dan nilai siswa di SMK Negeri 3 Yogyakarta.
 * =============================================================================
 */
class ClassController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * Halaman Utama: Katalog & Manajemen Kelas Binaan Guru.
     * Hanya menampilkan kelas-kelas yang menjadi target distribusi modul guru tersebut.
     */
    public function index(Request $request)
    {
        $teacher = $this->teacher();
        $teacherSubjects = $teacher->subjects()->get();
        $selectedSubjectId = $request->filled('subject_id') ? (int) $request->subject_id : null;

        // Query hanya kelas-kelas yang memiliki modul dari guru ini (bisa difilter per subject_id)
        $query = SchoolClass::whereHas('modules', function ($q) use ($teacher, $selectedSubjectId) {
                $q->where('teacher_id', $teacher->id);
                if ($selectedSubjectId) {
                    $q->where('subject_id', $selectedSubjectId);
                }
            })
            ->with(['students', 'modules' => function ($q) use ($teacher, $selectedSubjectId) {
                $q->where('teacher_id', $teacher->id)->with(['studentResults', 'subject']);
                if ($selectedSubjectId) {
                    $q->where('subject_id', $selectedSubjectId);
                }
            }]);

        // Filter Tingkat Kelas (X, XI, XII)
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        // Filter Jurusan (RPL, TKJ, dll.)
        if ($request->filled('major')) {
            $query->where('major_name', $request->major);
        }

        // Pencarian Nama Kelas atau Jurusan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('major_name', 'like', "%{$search}%")
                  ->orWhere('grade', 'like', "%{$search}%");
            });
        }

        $classes = $query->orderBy('grade')->orderBy('major_name')->get();

        // Hitung statistik per kelas untuk guru ini
        $classes->transform(function ($class) use ($teacher) {
            $class->stats = $class->statsForTeacher($teacher->id);
            $class->subjects_list = $class->modules->pluck('subject')->filter()->unique('id');
            return $class;
        });

        // Statistik Keseluruhan Guru
        $baseModulesQuery = Module::where('teacher_id', $teacher->id);
        if ($selectedSubjectId) {
            $baseModulesQuery->where('subject_id', $selectedSubjectId);
        }
        $allTeacherModules = $baseModulesQuery->with('studentResults')->get();
        $assignedClassIds = $allTeacherModules->pluck('class_id')->filter()->unique();
        $totalStudentsInAssignedClasses = Student::whereIn('class_id', $assignedClassIds)->count();
        $allResults = $allTeacherModules->pluck('studentResults')->flatten();
        $gradedResults = $allResults->where('grading_status', 'graded');
        $overallAvgScore = $gradedResults->count() > 0 ? (int) round($gradedResults->avg('summative_score')) : 0;

        $globalStats = [
            'total_assigned_classes' => $assignedClassIds->count(),
            'total_students'         => $totalStudentsInAssignedClasses,
            'total_modules'          => $allTeacherModules->count(),
            'published_modules'      => $allTeacherModules->where('status', 'published')->count(),
            'overall_avg_score'      => $overallAvgScore,
        ];

        // Data untuk dropdown filter (hanya dari kelas binaan guru ini)
        $teacherClassesQuery = SchoolClass::whereHas('modules', function ($q) use ($teacher, $selectedSubjectId) {
            $q->where('teacher_id', $teacher->id);
            if ($selectedSubjectId) {
                $q->where('subject_id', $selectedSubjectId);
            }
        });
        $availableGrades = (clone $teacherClassesQuery)->select('grade')->distinct()->orderBy('grade')->pluck('grade');
        $availableMajors = (clone $teacherClassesQuery)->select('major_name')->distinct()->orderBy('major_name')->pluck('major_name');

        return view('pages.teacher.classes.index', compact(
            'classes',
            'globalStats',
            'availableGrades',
            'availableMajors',
            'teacherSubjects',
            'selectedSubjectId'
        ));
    }

    /**
     * Halaman Detail Kelas Binaan & Direktori Siswa.
     */
    public function show(SchoolClass $class, Request $request)
    {
        $teacher = $this->teacher();

        // Modul guru pada kelas ini
        $teacherModules = Module::where('teacher_id', $teacher->id)
            ->where('class_id', $class->id)
            ->with(['studentResults', 'schoolClass'])
            ->latest()
            ->get();

        // Statistik Kelas Khusus Guru Ini
        $classStats = $class->statsForTeacher($teacher->id);

        // Ambil daftar siswa di kelas ini
        $studentsQuery = $class->students()->orderBy('name');

        if ($request->filled('search_student')) {
            $search = $request->search_student;
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        $students = $studentsQuery->get();

        // Hitung ringkasan performa per siswa untuk modul-modul guru ini
        $students->transform(function ($student) use ($teacherModules) {
            $moduleCount = $teacherModules->count();
            $submittedCount = 0;
            $gradedCount = 0;
            $scores = [];

            foreach ($teacherModules as $module) {
                $res = $module->studentResults->firstWhere('student_id', $student->id);
                if ($res) {
                    $submittedCount++;
                    if ($res->grading_status === 'graded' && $res->summative_score !== null) {
                        $gradedCount++;
                        $scores[] = (int) $res->summative_score;
                    }
                }
            }

            $avgScore = count($scores) > 0 ? (int) round(array_sum($scores) / count($scores)) : null;

            $kktpStatus = 'Belum Ada Nilai';
            if ($avgScore !== null) {
                $kktpStatus = ($avgScore >= 75) ? 'Tuntas' : 'Belum Tuntas (Remedial)';
            }

            $student->academic_summary = [
                'total_modules'     => $moduleCount,
                'submitted_count'   => $submittedCount,
                'graded_count'      => $gradedCount,
                'avg_score'         => $avgScore,
                'kktp_status'       => $kktpStatus,
                'has_submissions'   => $submittedCount > 0,
            ];

            return $student;
        });

        return view('pages.teacher.classes.show', compact(
            'class',
            'teacherModules',
            'classStats',
            'students'
        ));
    }

    /**
     * Endpoint API JSON: Rincian Nilai Akademik Siswa di Setiap Modul Guru.
     */
    public function getStudentAcademicSummary(SchoolClass $class, Student $student)
    {
        $teacher = $this->teacher();

        // Validasi siswa milik kelas ini
        if ($student->class_id !== $class->id) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak terdaftar di kelas ini.'], 404);
        }

        $teacherModules = Module::where('teacher_id', $teacher->id)
            ->where('class_id', $class->id)
            ->with([
                'studentResults' => fn ($q) => $q->where('student_id', $student->id),
                'videoSummaries' => fn ($q) => $q->where('student_id', $student->id),
                'embedSubmissions' => fn ($q) => $q->where('student_id', $student->id),
                'jobSheets.submissions' => fn ($q) => $q->where('student_id', $student->id),
                'lkpds.submissions' => fn ($q) => $q->where('student_id', $student->id),
            ])
            ->latest()
            ->get();

        $modulesSummary = [];
        $totalScores = [];

        foreach ($teacherModules as $module) {
            $result = $module->studentResults->first();
            $videoSummary = $module->videoSummaries->first();
            $embedSub = $module->embedSubmissions->first();
            
            $jobSheetSub = null;
            if ($module->jobSheets->isNotEmpty()) {
                $jobSheetSub = $module->jobSheets->first()->submissions->first();
            }

            $lkpdSub = null;
            if ($module->lkpds->isNotEmpty()) {
                $lkpdSub = $module->lkpds->first()->submissions->first();
            }

            $hasSubmitted = $result !== null || $videoSummary !== null || $embedSub !== null || $jobSheetSub !== null || $lkpdSub !== null;
            $summative = $result?->summative_score;

            if ($summative !== null) {
                $totalScores[] = (int) $summative;
            }

            $modulesSummary[] = [
                'module_id'        => $module->id,
                'module_title'     => $module->title,
                'status'           => $module->status,
                'has_submitted'    => $hasSubmitted,
                'grading_status'   => $result?->grading_status ?? ($hasSubmitted ? 'pending' : 'unsubmitted'),
                'pre_test_score'   => $result?->pre_test_score,
                'video_score'      => $result?->video_score ?? $videoSummary?->manual_score,
                'embed_score'      => $result?->embed_score ?? $embedSub?->manual_score,
                'job_sheet_score'  => $result?->job_sheet_score ?? $jobSheetSub?->manual_score,
                'lkpd_score'       => $result?->lkpd_score ?? $lkpdSub?->manual_score,
                'post_test_score'  => $result?->post_test_score,
                'summative_score'  => $summative,
                'active_components'=> array_keys($module->activeGradedComponents()),
            ];
        }

        $overallAvg = count($totalScores) > 0 ? (int) round(array_sum($totalScores) / count($totalScores)) : null;

        return response()->json([
            'success'          => true,
            'student'          => [
                'id'              => $student->id,
                'name'            => $student->name,
                'identity_number' => $student->identity_number,
                'class_name'      => $class->full_name,
            ],
            'class'            => [
                'id'        => $class->id,
                'full_name' => $class->full_name,
            ],
            'modules_summary'  => $modulesSummary,
            'overall_avg'      => $overallAvg,
            'kktp_status'      => $overallAvg !== null ? ($overallAvg >= 75 ? 'Tuntas' : 'Belum Tuntas (Remedial)') : 'Belum Ada Nilai',
        ]);
    }
}
