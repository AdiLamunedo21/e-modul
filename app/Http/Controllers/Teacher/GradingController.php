<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\VideoSummary;
use App\Models\EmbedSubmission;
use App\Models\JobSheetSubmission;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * =============================================================================
 * CONTROLLER: GradingController
 * =============================================================================
 * PUSAT PENILAIAN ADAPTIF (GRADING CENTER):
 * -----------------------------------------------------------------------------
 * Controller ini mengelola penilaian gabungan otomatis dan manual untuk seluruh
 * komponen evaluasi yang diaktifkan guru pada modul:
 * - Pre-test (Otomatis)          -> Bagian 4. Evaluasi & Latihan
 * - Resume Video (Manual)        -> Bagian 3. Kegiatan Belajar
 * - Screenshot Embed (Manual)    -> Bagian 4. Evaluasi & Latihan
 * - Berkas Job Sheet PDF (Manual)-> Bagian 3. Kegiatan Belajar
 * - Berkas Tugas LKPD (Manual)   -> Bagian 4. Evaluasi & Latihan
 * - Post-test (Otomatis)         -> Bagian 5. Bagian Akhir
 * 
 * Matriks penilaian di frontend dan ekspor nilai secara otomatis beradaptasi
 * hanya merender kolom komponen yang aktif pada modul (`Module::activeGradedComponents()`).
 * =============================================================================
 */
class GradingController extends Controller
{
    /**
     * Menampilkan daftar modul untuk pusat penilaian (Grading Center Overview).
     */
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $query = Module::where('teacher_id', $teacher->id)
            ->with(['schoolClass.students', 'studentResults']);

        // Filter status modul jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter kelas jika ada
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Filter pencarian judul
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $modules = $query->latest()->get();

        // Hitung statistik global penilaian guru
        $allTeacherModules = Module::where('teacher_id', $teacher->id)->with('studentResults')->get();
        $allResults = $allTeacherModules->pluck('studentResults')->flatten();

        $stats = [
            'total_modules'     => $allTeacherModules->count(),
            'published_modules' => $allTeacherModules->where('status', 'published')->count(),
            'total_submissions' => $allResults->count(),
            'pending_grading'   => $allResults->where('grading_status', 'pending')->count(),
            'completed_grading' => $allResults->where('grading_status', 'graded')->count(),
            'average_score'     => $allResults->where('grading_status', 'graded')->count() > 0
                ? (int) round($allResults->where('grading_status', 'graded')->avg('summative_score'))
                : 0,
        ];

        $classes = $teacher->modules()->with('schoolClass')->get()->pluck('schoolClass')->filter()->unique('id');

        return view('pages.teacher.grading.index', compact('modules', 'stats', 'classes'));
    }

    /**
     * Menampilkan tabel matriks penilaian adaptif untuk modul tertentu.
     */
    public function show(Module $module, Request $request)
    {
        $this->authorizeTeacher($module);

        $module->load([
            'schoolClass.students',
            'studentResults',
            'videoSummaries',
            'embedSubmissions',
            'jobSheets.submissions',
            'lkpds.submissions',
        ]);

        $activeComponents = $module->activeGradedComponents();
        $classStudents = $module->schoolClass ? $module->schoolClass->students : collect();

        // Filter pencarian nama / NISN siswa
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $classStudents = $classStudents->filter(function ($student) use ($search) {
                return str_contains(strtolower($student->name), $search) ||
                       str_contains(strtolower($student->identity_number), $search);
            });
        }

        // Memetakan data lengkap setiap siswa di kelas
        $studentsData = $classStudents->map(function ($student) use ($module) {
            $result = $module->studentResults->firstWhere('student_id', $student->id);

            $videoSummary = $module->has_video
                ? $module->videoSummaries->firstWhere('student_id', $student->id)
                : null;

            $embedSubmission = $module->has_embed
                ? $module->embedSubmissions->firstWhere('student_id', $student->id)
                : null;

            $jobSheetSubmission = null;
            if ($module->has_job_sheet) {
                $jobSheet = $module->jobSheets->first();
                if ($jobSheet) {
                    $jobSheetSubmission = $jobSheet->submissions->firstWhere('student_id', $student->id);
                }
            }

            $lkpdSubmission = null;
            if ($module->has_lkpd) {
                $lkpd = $module->lkpds->first();
                if ($lkpd) {
                    $lkpdSubmission = $lkpd->submissions->firstWhere('student_id', $student->id);
                }
            }

            // Tentukan status pengerjaan / penilaian
            $hasAnySubmission = $result !== null ||
                                $videoSummary !== null ||
                                $embedSubmission !== null ||
                                $jobSheetSubmission !== null ||
                                $lkpdSubmission !== null;

            $status = 'not_submitted';
            if ($result) {
                $status = $result->grading_status; // 'pending' | 'graded'
            } elseif ($hasAnySubmission) {
                $status = 'pending';
            }

            return [
                'student'              => $student,
                'result'               => $result,
                'status'               => $status,
                'pre_test_score'       => $result?->pre_test_score,
                'video_summary'        => $videoSummary,
                'video_score'          => $result?->video_score ?? $videoSummary?->manual_score,
                'embed_submission'     => $embedSubmission,
                'embed_score'          => $result?->embed_score ?? $embedSubmission?->manual_score,
                'job_sheet_submission' => $jobSheetSubmission,
                'job_sheet_score'      => $result?->job_sheet_score ?? $jobSheetSubmission?->manual_score,
                'lkpd_submission'      => $lkpdSubmission,
                'lkpd_score'           => $result?->lkpd_score ?? $lkpdSubmission?->manual_score,
                'post_test_score'      => $result?->post_test_score,
                'summative_score'      => $result?->summative_score ?? 0,
            ];
        });

        // Filter status penilaian jika ada
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $filter = $request->status_filter;
            $studentsData = $studentsData->filter(fn($item) => $item['status'] === $filter);
        }

        $stats = $module->gradingStats();

        return view('pages.teacher.grading.show', compact('module', 'studentsData', 'activeComponents', 'stats'));
    }

    /**
     * Endpoint JSON untuk memuat detail berkas/tugas siswa ke modal penilaian.
     */
    public function getStudentDetail(Module $module, Student $student)
    {
        $this->authorizeTeacher($module);

        $result = StudentResult::where('module_id', $module->id)
            ->where('student_id', $student->id)
            ->first();

        $videoSummary = VideoSummary::where('module_id', $module->id)
            ->where('student_id', $student->id)
            ->first();

        $embedSubmission = EmbedSubmission::where('module_id', $module->id)
            ->where('student_id', $student->id)
            ->first();

        $jobSheetSubmission = JobSheetSubmission::whereHas('jobSheet', function ($q) use ($module) {
            $q->where('module_id', $module->id);
        })->where('student_id', $student->id)->first();

        $lkpdSubmission = Submission::whereHas('lkpd', function ($q) use ($module) {
            $q->where('module_id', $module->id);
        })->where('student_id', $student->id)->first();

        return response()->json([
            'student' => [
                'id'              => $student->id,
                'name'            => $student->name,
                'identity_number' => $student->identity_number,
                'class'           => $student->schoolClass ? $student->schoolClass->full_name : '-',
            ],
            'result' => $result ? [
                'pre_test_score'   => $result->pre_test_score,
                'video_score'      => $result->video_score,
                'embed_score'      => $result->embed_score,
                'job_sheet_score'  => $result->job_sheet_score,
                'lkpd_score'       => $result->lkpd_score,
                'post_test_score'  => $result->post_test_score,
                'summative_score'  => $result->summative_score,
                'grading_status'   => $result->grading_status,
                'updated_at'       => $result->updated_at?->format('d M Y, H:i'),
            ] : null,
            'submissions' => [
                'video' => $videoSummary ? [
                    'summary_text' => $videoSummary->summary_text,
                    'manual_score' => $videoSummary->manual_score,
                    'created_at'   => $videoSummary->created_at?->format('d M Y, H:i'),
                ] : null,
                'embed' => $embedSubmission ? [
                    'screenshot_path' => asset('storage/' . $embedSubmission->screenshot_path),
                    'manual_score'    => $embedSubmission->manual_score,
                    'created_at'      => $embedSubmission->created_at?->format('d M Y, H:i'),
                ] : null,
                'job_sheet' => $jobSheetSubmission ? [
                    'file_path'    => asset('storage/' . $jobSheetSubmission->uploaded_file_path),
                    'file_name'    => basename($jobSheetSubmission->uploaded_file_path),
                    'manual_score' => $jobSheetSubmission->manual_score,
                    'created_at'   => $jobSheetSubmission->created_at?->format('d M Y, H:i'),
                ] : null,
                'lkpd' => $lkpdSubmission ? [
                    'file_path'    => asset('storage/' . $lkpdSubmission->uploaded_file_path),
                    'file_name'    => basename($lkpdSubmission->uploaded_file_path),
                    'manual_score' => $lkpdSubmission->manual_score,
                    'created_at'   => $lkpdSubmission->created_at?->format('d M Y, H:i'),
                ] : null,
            ],
            'active_components' => $module->activeGradedComponents(),
        ]);
    }

    /**
     * Menyimpan skor penilaian manual untuk satu siswa.
     */
    public function updateStudentGrade(Request $request, Module $module, Student $student)
    {
        $this->authorizeTeacher($module);

        $request->validate([
            'video_score'     => 'nullable|numeric|min:0|max:100',
            'embed_score'     => 'nullable|numeric|min:0|max:100',
            'job_sheet_score' => 'nullable|numeric|min:0|max:100',
            'lkpd_score'      => 'nullable|numeric|min:0|max:100',
            'pre_test_score'  => 'nullable|numeric|min:0|max:100',
            'post_test_score' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $module, $student) {
            // 1. Simpan skor ke tabel VideoSummary jika aktif
            if ($module->has_video && $request->has('video_score')) {
                VideoSummary::updateOrCreate(
                    ['module_id' => $module->id, 'student_id' => $student->id],
                    ['manual_score' => $request->video_score, 'summary_text' => $request->video_summary_text ?? 'Ringkasan oleh siswa']
                );
            }

            // 2. Simpan skor ke tabel EmbedSubmission jika aktif
            if ($module->has_embed && $request->has('embed_score')) {
                EmbedSubmission::updateOrCreate(
                    ['module_id' => $module->id, 'student_id' => $student->id],
                    ['manual_score' => $request->embed_score]
                );
            }

            // 3. Simpan skor ke tabel JobSheetSubmission jika aktif
            if ($module->has_job_sheet && $request->has('job_sheet_score')) {
                $jobSheet = $module->jobSheets()->firstOrCreate(['module_id' => $module->id], ['pdf_file_path' => 'default.pdf']);
                JobSheetSubmission::updateOrCreate(
                    ['job_sheet_id' => $jobSheet->id, 'student_id' => $student->id],
                    ['manual_score' => $request->job_sheet_score]
                );
            }

            // 4. Simpan skor ke tabel Submission LKPD jika aktif
            if ($module->has_lkpd && $request->has('lkpd_score')) {
                $lkpd = $module->lkpds()->firstOrCreate(['module_id' => $module->id], ['pdf_file_path' => 'default.pdf']);
                Submission::updateOrCreate(
                    ['lkpd_id' => $lkpd->id, 'student_id' => $student->id],
                    ['manual_score' => $request->lkpd_score]
                );
            }

            // 5. Simpan / Perbarui StudentResult
            $result = StudentResult::firstOrNew([
                'module_id'  => $module->id,
                'student_id' => $student->id,
            ]);

            if ($request->has('pre_test_score')) {
                $result->pre_test_score = $request->pre_test_score;
            }
            if ($request->has('video_score')) {
                $result->video_score = $request->video_score;
            }
            if ($request->has('embed_score')) {
                $result->embed_score = $request->embed_score;
            }
            if ($request->has('job_sheet_score')) {
                $result->job_sheet_score = $request->job_sheet_score;
            }
            if ($request->has('lkpd_score')) {
                $result->lkpd_score = $request->lkpd_score;
            }
            if ($request->has('post_test_score')) {
                $result->post_test_score = $request->post_test_score;
            }

            // Hitung nilai akhir sumatif
            $result->summative_score = $result->calculateSummativeScore($module);
            $result->grading_status  = 'graded';
            $result->save();
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Penilaian untuk {$student->name} berhasil disimpan.",
            ]);
        }

        return redirect()->back()->with('success', "Penilaian untuk {$student->name} berhasil disimpan.");
    }

    /**
     * Menyimpan nilai massal (Batch Grade) langsung dari tabel matriks.
     */
    public function batchUpdate(Request $request, Module $module)
    {
        $this->authorizeTeacher($module);

        $grades = $request->input('grades', []);

        DB::transaction(function () use ($grades, $module) {
            foreach ($grades as $studentId => $scores) {
                $student = Student::find($studentId);
                if (!$student) continue;

                $result = StudentResult::firstOrNew([
                    'module_id'  => $module->id,
                    'student_id' => $student->id,
                ]);

                if (isset($scores['video_score']) && $scores['video_score'] !== '') {
                    $result->video_score = (int) $scores['video_score'];
                    VideoSummary::updateOrCreate(
                        ['module_id' => $module->id, 'student_id' => $student->id],
                        ['manual_score' => (int) $scores['video_score']]
                    );
                }

                if (isset($scores['embed_score']) && $scores['embed_score'] !== '') {
                    $result->embed_score = (int) $scores['embed_score'];
                    EmbedSubmission::updateOrCreate(
                        ['module_id' => $module->id, 'student_id' => $student->id],
                        ['manual_score' => (int) $scores['embed_score']]
                    );
                }

                if (isset($scores['job_sheet_score']) && $scores['job_sheet_score'] !== '') {
                    $result->job_sheet_score = (int) $scores['job_sheet_score'];
                    $jobSheet = $module->jobSheets()->firstOrCreate(['module_id' => $module->id], ['pdf_file_path' => 'default.pdf']);
                    JobSheetSubmission::updateOrCreate(
                        ['job_sheet_id' => $jobSheet->id, 'student_id' => $student->id],
                        ['manual_score' => (int) $scores['job_sheet_score']]
                    );
                }

                if (isset($scores['lkpd_score']) && $scores['lkpd_score'] !== '') {
                    $result->lkpd_score = (int) $scores['lkpd_score'];
                    $lkpd = $module->lkpds()->firstOrCreate(['module_id' => $module->id], ['pdf_file_path' => 'default.pdf']);
                    Submission::updateOrCreate(
                        ['lkpd_id' => $lkpd->id, 'student_id' => $student->id],
                        ['manual_score' => (int) $scores['lkpd_score']]
                    );
                }

                $result->summative_score = $result->calculateSummativeScore($module);
                $result->grading_status  = 'graded';
                $result->save();
            }
        });

        return redirect()->back()->with('success', 'Semua perubahan penilaian berhasil disimpan secara massal!');
    }

    /**
     * Memastikan guru hanya dapat mengelola modul miliknya sendiri.
     */
    protected function authorizeTeacher(Module $module): void
    {
        if ($module->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menilai modul ini.');
        }
    }
}
