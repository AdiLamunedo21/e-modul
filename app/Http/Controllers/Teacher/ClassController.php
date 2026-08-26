<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\VideoSummary;
use App\Models\EmbedSubmission;
use App\Models\JobSheetSubmission;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * =============================================================================
 * CONTROLLER: ClassController
 * =============================================================================
 * MANAJEMEN BUILD KELAS, DIREKTORI SISWA & PURGE ALUMNI:
 * -----------------------------------------------------------------------------
 * 1. index()                : Menampilkan Katalog Build Kelas & Modul
 * 2. store()                : Membuat Rombel Kelas Baru
 * 3. show()                 : Menampilkan Detail Kelas (Tab Siswa & Tab Modul)
 * 4. importModules()        : Mengimpor/Menduplikasi Modul dari Kelas Lain
 * 5. destroy()              : Menghapus Kelas Beserta Alumni Siswa & Modulnya
 * 6. getStudentAcademicSummary() : Endpoint JSON Rincian Akademik Siswa
 * =============================================================================
 */
class ClassController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * Halaman Utama: Katalog & Manajemen Build Kelas Guru.
     */
    public function index(Request $request)
    {
        $teacher = $this->teacher();
        $teacherSubjects = $teacher->subjects()->get();
        $selectedSubjectId = $request->filled('subject_id') ? (int) $request->subject_id : null;

        $query = SchoolClass::with(['major', 'students', 'modules' => function ($q) use ($teacher, $selectedSubjectId) {
            $q->where('teacher_id', $teacher->id)->with(['studentResults', 'subject']);
            if ($selectedSubjectId) {
                $q->where('subject_id', $selectedSubjectId);
            }
        }])->withCount(['students']);

        // Filter Tingkat Kelas (X, XI, XII, XIII)
        if ($request->filled('grade') && $request->grade !== 'all') {
            $query->where('grade', $request->grade);
        }

        // Filter Jurusan
        if ($request->filled('major_id') && $request->major_id !== 'all') {
            $query->where('major_id', $request->major_id);
        }

        // Pencarian Nama Kelas atau Jurusan
        if ($request->filled('search')) {
            $search = $request->search;
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

        $classes = $query->orderBy('grade')->orderBy('section')->get();

        // Hitung statistik per kelas untuk guru ini secara in-memory tanpa query N+1
        $classes->transform(function ($class) use ($teacher) {
            $teacherModules = $class->modules;
            $class->teacher_modules_count = $teacherModules->count();
            $class->teacher_published_count = $teacherModules->where('status', 'published')->count();
            $class->stats = $class->statsForTeacher($teacher->id);
            $class->subjects_list = $teacherModules->pluck('subject')->filter()->unique('id');
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
            'total_assigned_classes' => $classes->count(),
            'total_students'         => $classes->sum('students_count'),
            'total_modules'          => $allTeacherModules->count(),
            'published_modules'      => $allTeacherModules->where('status', 'published')->count(),
            'overall_avg_score'      => $overallAvgScore,
        ];

        // Daftar modul guru yang tersedia untuk diimpor ke kelas lain
        $myModules = Module::where('teacher_id', $teacher->id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('title')
            ->get();

        $majors = Major::orderBy('name')->get();
        $availableGrades = ['X', 'XI', 'XII', 'XIII'];

        return view('pages.teacher.classes.index', compact(
            'classes',
            'globalStats',
            'availableGrades',
            'majors',
            'teacherSubjects',
            'selectedSubjectId',
            'myModules'
        ));
    }

    /**
     * Membuat rombongan belajar kelas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade'    => ['required', 'string', Rule::in(['X', 'XI', 'XII', 'XIII'])],
            'major_id' => ['required', 'exists:majors,id'],
            'section'  => ['required', 'string', 'max:10'],
        ], [
            'grade.required'    => 'Pilih tingkat kelas.',
            'grade.in'          => 'Tingkat kelas harus salah satu dari X, XI, XII, XIII.',
            'major_id.required' => 'Pilih konsentrasi keahlian / jurusan.',
            'major_id.exists'   => 'Jurusan yang dipilih tidak valid.',
            'section.required'  => 'Nomor atau nama rombel wajib diisi.',
        ]);

        $major = Major::findOrFail($validated['major_id']);

        $schoolClass = SchoolClass::create([
            'grade'      => $validated['grade'],
            'major_id'   => $major->id,
            'section'    => $validated['section'],
            'major_name' => $major->code,
        ]);

        return redirect()->route('teacher.classes.show', $schoolClass)
            ->with('success', "Rombel {$schoolClass->full_name} berhasil dibuat! Anda dapat mulai mengimpor modul atau mendaftarkan siswa.");
    }

    /**
     * Halaman Detail Kelas Binaan: Tab Siswa & Tab Modul Pembelajaran.
     */
    public function show(SchoolClass $class, Request $request)
    {
        $teacher = $this->teacher();
        $class->loadMissing(['major', 'students']);

        // Modul guru pada kelas ini
        $teacherModules = Module::where('teacher_id', $teacher->id)
            ->where('class_id', $class->id)
            ->with(['studentResults', 'subject', 'schoolClass'])
            ->latest()
            ->get();

        // Modul guru dari kelas LAIN yang siap diimpor ke kelas ini
        $otherClassModules = Module::where('teacher_id', $teacher->id)
            ->where('class_id', '!=', $class->id)
            ->with(['schoolClass', 'subject'])
            ->latest()
            ->get();

        // Statistik Kelas Khusus Guru Ini
        $classStats = $class->statsForTeacher($teacher->id);

        // Ambil daftar siswa di kelas ini
        $studentsQuery = $class->students()->with('subjects')->orderBy('name');

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

        $tab = $request->query('tab', 'students'); // 'students' atau 'modules'

        return view('pages.teacher.classes.show', compact(
            'class',
            'teacherModules',
            'otherClassModules',
            'classStats',
            'students',
            'tab'
        ));
    }

    /**
     * Mengimpor atau menduplikasi modul dari kelas lain ke kelas target ini.
     */
    public function importModules(Request $request, SchoolClass $class)
    {
        $teacher = $this->teacher();

        $validated = $request->validate([
            'module_ids' => ['required', 'array', 'min:1'],
            'module_ids.*' => ['exists:modules,id'],
        ], [
            'module_ids.required' => 'Pilih minimal satu modul yang ingin diimpor ke kelas ini.',
            'module_ids.min'      => 'Pilih minimal satu modul yang ingin diimpor ke kelas ini.',
        ]);

        $importedCount = 0;

        DB::transaction(function () use ($validated, $class, $teacher, &$importedCount) {
            foreach ($validated['module_ids'] as $moduleId) {
                $sourceModule = Module::find($moduleId);
                if ($sourceModule && ($sourceModule->teacher_id === $teacher->id || $sourceModule->is_shared)) {
                    $sourceModule->cloneToTeacher($teacher, $class->id);
                    $importedCount++;
                }
            }
        });

        return redirect()->route('teacher.classes.show', ['class' => $class->id, 'tab' => 'modules'])
            ->with('success', "Berhasil mengimpor {$importedCount} modul pembelajaran ke dalam kelas {$class->full_name}!");
    }

    /**
     * Menghapus kelas rombel beserta seluruh data siswa alumni dan modulnya secara bersih (Purge).
     */
    public function destroy(SchoolClass $class)
    {
        $className = $class->full_name;
        $studentsCount = $class->students()->count();
        $modulesCount = $class->modules()->count();

        DB::transaction(function () use ($class) {
            // 1. Hapus seluruh data siswa di kelas ini beserta relasi-relasinya
            foreach ($class->students as $student) {
                $student->subjects()->detach();
                StudentResult::where('student_id', $student->id)->delete();
                VideoSummary::where('student_id', $student->id)->delete();
                EmbedSubmission::where('student_id', $student->id)->delete();
                JobSheetSubmission::where('student_id', $student->id)->delete();
                Submission::where('student_id', $student->id)->delete();
                $student->delete();
            }

            // 2. Hapus seluruh modul di kelas ini
            foreach ($class->modules as $module) {
                $module->delete();
            }

            // 3. Hapus kelas itu sendiri
            $class->delete();
        });

        return redirect()->route('teacher.classes.index')
            ->with('success', "Kelas {$className} beserta seluruh data alumni ({$studentsCount} siswa) dan {$modulesCount} modul berhasil dihapus secara bersih dari database.");
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
