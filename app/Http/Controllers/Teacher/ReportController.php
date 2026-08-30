<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\ModuleGradesExport;
use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: ReportController
 * =============================================================================
 * PUSAT LAPORAN & EKSPOR SPREADSHEET (EXCEL .XLSX) BERJENJANG:
 * -----------------------------------------------------------------------------
 * 1. index()                : Menampilkan Direktori Nama Kelas Binaan Guru
 * 2. showClassSubjects()    : Menampilkan Daftar Mata Pelajaran di Kelas Terpilih
 * 3. showSubjectModules()   : Menampilkan Daftar Modul Pembelajaran per Mapel & Kelas
 * 4. showModuleReport()     : Menampilkan Tabel Rekapitulasi Nilai Siswa per Modul
 * 5. exportModule()         : Mengunduh Berkas Spreadsheet Microsoft Excel (.xlsx)
 * =============================================================================
 */
class ReportController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * TAHAP 1: Direktori Nama Kelas pada Pusat Laporan Excel.
     */
    public function index(Request $request)
    {
        $teacher = $this->teacher();
        $search = $request->query('search');
        $grade = $request->query('grade');
        $majorId = $request->query('major_id');

        $assignedClassIds = $teacher->classes()->pluck('classes.id')->toArray();
        $query = SchoolClass::whereIn('id', $assignedClassIds)->with(['major'])->withCount(['students']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('grade', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%")
                  ->orWhere('major_name', 'like', "%{$search}%")
                  ->orWhereHas('major', function ($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        if ($grade && $grade !== 'all') {
            $query->where('grade', $grade);
        }

        if ($majorId && $majorId !== 'all') {
            $query->where('major_id', $majorId);
        }

        $classesList = $query->orderBy('grade')->orderBy('section')->get();

        // Load semua modul guru ini beserta studentResults sekaligus (1 query)
        $allTeacherModules = Module::where('teacher_id', $teacher->id)->with('studentResults')->get();
        $modulesByClass = $allTeacherModules->groupBy('class_id');
        $hasSubjects = $teacher->subjects()->exists();
        $teacherSubjectsCount = $hasSubjects ? $teacher->subjects()->count() : 0;

        // Hitung metrik spesifik untuk guru yang login pada setiap kelas secara in-memory
        $classesList->transform(function (SchoolClass $cls) use ($modulesByClass, $hasSubjects, $teacherSubjectsCount) {
            $teacherModules = $modulesByClass->get($cls->id, collect());
            $cls->teacher_modules_count = $teacherModules->count();
            $cls->teacher_published_count = $teacherModules->where('status', 'published')->count();
            
            // Hitung berapa mapel yang diajar guru di kelas ini
            $mapelIds = $teacherModules->pluck('subject_id')->unique()->filter();
            if ($mapelIds->isEmpty() && $hasSubjects) {
                $cls->teacher_subjects_count = $teacherSubjectsCount;
            } else {
                $cls->teacher_subjects_count = $mapelIds->count();
            }

            return $cls;
        });

        // Global stats guru
        $allResults = $allTeacherModules->pluck('studentResults')->flatten()->filter();
        $gradedResults = $allResults->where('grading_status', 'graded');

        $stats = [
            'total_modules'     => $allTeacherModules->count(),
            'published_modules' => $allTeacherModules->where('status', 'published')->count(),
            'total_submissions' => $allResults->count(),
            'completed_grading' => $gradedResults->count(),
            'average_score'     => $gradedResults->count() > 0 ? (int) round($gradedResults->avg('summative_score')) : 0,
            'total_classes'     => $classesList->count(),
        ];

        $majors = Major::orderBy('name')->get();

        return view('pages.teacher.reports.index', compact(
            'classesList',
            'stats',
            'majors',
            'search',
            'grade',
            'majorId'
        ));
    }

    /**
     * TAHAP 2: Menampilkan daftar Mata Pelajaran yang diajar guru di kelas tertentu.
     */
    public function showClassSubjects(Request $request, SchoolClass $class)
    {
        $teacher = $this->teacher();
        $class->loadMissing(['major', 'students']);

        // Ambil mapel-mapel yang diajar guru
        $teacherSubjectIds = $teacher->subjects()->pluck('subjects.id')->toArray();
        $moduleSubjectIds = Module::where('teacher_id', $teacher->id)
            ->where('class_id', $class->id)
            ->pluck('subject_id')
            ->unique()
            ->filter()
            ->toArray();

        $mergedIds = array_unique(array_merge($teacherSubjectIds, $moduleSubjectIds));

        if (!empty($mergedIds)) {
            $subjects = Subject::whereIn('id', $mergedIds)->orderBy('name')->get();
        } else {
            $subjects = Subject::orderBy('name')->get();
        }

        // Kalkulasi per mapel di kelas ini
        $subjects->transform(function (Subject $sub) use ($teacher, $class) {
            $modules = Module::where('teacher_id', $teacher->id)
                ->where('class_id', $class->id)
                ->where('subject_id', $sub->id)
                ->with('studentResults')
                ->get();

            $results = $modules->pluck('studentResults')->flatten();
            $graded = $results->where('grading_status', 'graded');

            $sub->class_modules_count = $modules->count();
            $sub->class_published_count = $modules->where('status', 'published')->count();
            $sub->class_submissions_count = $results->count();
            $sub->class_graded_count = $graded->count();
            $sub->class_avg_score = $graded->count() > 0 ? (int) round($graded->avg('summative_score')) : 0;

            return $sub;
        });

        $classModules = Module::where('teacher_id', $teacher->id)->where('class_id', $class->id)->with('studentResults')->get();
        $classResults = $classModules->pluck('studentResults')->flatten();
        $classGraded = $classResults->where('grading_status', 'graded');

        $classStats = [
            'total_students'  => $class->students()->count(),
            'total_modules'   => $classModules->count(),
            'published_count' => $classModules->where('status', 'published')->count(),
            'total_graded'    => $classGraded->count(),
            'avg_score'       => $classGraded->count() > 0 ? (int) round($classGraded->avg('summative_score')) : 0,
        ];

        return view('pages.teacher.reports.class_subjects', compact(
            'class',
            'subjects',
            'classStats'
        ));
    }

    /**
     * TAHAP 3: Menampilkan daftar Modul Pembelajaran pada mata pelajaran dan kelas terpilih.
     */
    public function showSubjectModules(Request $request, SchoolClass $class, Subject $subject)
    {
        $teacher = $this->teacher();
        $class->loadMissing('major');

        $query = Module::where('teacher_id', $teacher->id)
            ->where('class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->with(['studentResults', 'schoolClass', 'subject'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $modules = $query->get();

        $totalClassStudents = $class->students()->count();

        // Hitung statistik per modul
        $modules->transform(function (Module $m) use ($totalClassStudents) {
            $results = $m->studentResults;
            $graded = $results->where('grading_status', 'graded');

            $m->total_students = $totalClassStudents;
            $m->submissions_count = $results->count();
            $m->graded_count = $graded->count();
            $m->avg_score = $graded->count() > 0 ? (int) round($graded->avg('summative_score')) : 0;

            return $m;
        });

        $subjectStats = [
            'total_modules'     => $modules->count(),
            'published_modules' => $modules->where('status', 'published')->count(),
            'total_submissions' => $modules->sum('submissions_count'),
            'total_graded'      => $modules->sum('graded_count'),
        ];

        return view('pages.teacher.reports.subject_modules', compact(
            'class',
            'subject',
            'modules',
            'subjectStats'
        ));
    }

    /**
     * TAHAP 4: Menampilkan Rekapitulasi Tabel Nilai Siswa Lengkap per Modul.
     */
    public function showModuleReport(Request $request, Module $module)
    {
        $this->authorizeTeacher($module);

        $module->loadMissing([
            'schoolClass.students',
            'subject',
            'teacher',
            'studentResults',
            'videoSummaries',
            'embedSubmissions',
            'jobSheets.submissions',
            'lkpds.submissions'
        ]);

        $search = $request->query('search');
        $status = $request->query('status');

        $class = $module->schoolClass;
        $allStudents = $class ? $class->students : collect();

        // Gabungkan data siswa dengan hasil nilai mereka pada modul ini
        $processedStudents = $allStudents->map(function (Student $student) use ($module) {
            $result = $module->studentResults->firstWhere('student_id', $student->id);
            $videoSummary = $module->videoSummaries->firstWhere('student_id', $student->id);
            $embedSub = $module->embedSubmissions->firstWhere('student_id', $student->id);

            // Job sheet submission
            $jobSheetSub = null;
            if ($module->jobSheets->isNotEmpty()) {
                $firstJobSheet = $module->jobSheets->first();
                $jobSheetSub = $firstJobSheet->submissions->firstWhere('student_id', $student->id);
            }

            // LKPD submission
            $lkpdSub = null;
            if ($module->lkpds->isNotEmpty()) {
                $firstLkpd = $module->lkpds->first();
                $lkpdSub = $firstLkpd->submissions->firstWhere('student_id', $student->id);
            }

            $preTestScore = $result?->pre_test_score;
            $videoScore = $result?->video_score ?? $videoSummary?->manual_score;
            $embedScore = $result?->embed_score ?? $embedSub?->manual_score;
            $jobSheetScore = $result?->job_sheet_score ?? $jobSheetSub?->manual_score;
            $lkpdScore = $result?->lkpd_score ?? $lkpdSub?->manual_score;
            $postTestScore = $result?->post_test_score;
            $summativeScore = $result?->summative_score;
            $gradingStatus = $result?->grading_status ?? 'not_submitted';

            $hasAnySubmission = $result !== null || $videoSummary !== null || $embedSub !== null || $jobSheetSub !== null || $lkpdSub !== null;
            if (!$result && $hasAnySubmission) {
                $gradingStatus = 'pending';
            }

            return (object) [
                'id'               => $student->id,
                'name'             => $student->name,
                'identity_number'  => $student->identity_number,
                'pre_test_score'   => $preTestScore,
                'video_score'      => $videoScore,
                'embed_score'      => $embedScore,
                'job_sheet_score'  => $jobSheetScore,
                'lkpd_score'       => $lkpdScore,
                'post_test_score'  => $postTestScore,
                'summative_score'  => $summativeScore,
                'grading_status'   => $gradingStatus,
            ];
        });

        // Filter pencarian
        if ($search) {
            $processedStudents = $processedStudents->filter(function ($st) use ($search) {
                return str_contains(strtolower($st->name), strtolower($search))
                    || str_contains(strtolower($st->identity_number), strtolower($search));
            });
        }

        // Filter status penilaian
        if ($status && in_array($status, ['graded', 'pending', 'not_submitted'])) {
            $processedStudents = $processedStudents->where('grading_status', $status);
        }

        $allProcessed = $allStudents->map(function ($st) use ($module) {
            $res = $module->studentResults->firstWhere('student_id', $st->id);
            return $res?->grading_status ?? 'not_submitted';
        });

        $reportStats = [
            'total_students' => $allStudents->count(),
            'graded'         => $allProcessed->where('grading_status', 'graded')->count() + $module->studentResults->where('grading_status', 'graded')->count(),
            'pending'        => $module->studentResults->where('grading_status', 'pending')->count(),
            'avg_score'      => $module->studentResults->where('grading_status', 'graded')->count() > 0 
                                ? (int) round($module->studentResults->where('grading_status', 'graded')->avg('summative_score')) 
                                : 0,
        ];

        return view('pages.teacher.reports.module_report', compact(
            'module',
            'processedStudents',
            'reportStats',
            'search',
            'status'
        ));
    }

    /**
     * Mengunduh rekapitulasi nilai satu modul dalam format Excel (.xlsx).
     */
    public function exportModule(Module $module)
    {
        $this->authorizeTeacher($module);

        $export = new ModuleGradesExport($module);

        return $export->download();
    }

    /**
     * Memastikan guru hanya dapat mengunduh laporan modul miliknya sendiri.
     */
    protected function authorizeTeacher(Module $module): void
    {
        if ($module->teacher_id !== $this->teacher()->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan nilai modul ini.');
        }
    }
}
