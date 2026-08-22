<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Submission;
use App\Models\JobSheetSubmission;
use App\Models\EmbedSubmission;
use App\Models\VideoSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: DashboardController (Teacher Workspace)
 * =============================================================================
 * PUSAT KENDALI WORKSPACE GURU:
 * -----------------------------------------------------------------------------
 * Controller ini merangkum seluruh fitur inti E-Modul yang telah dibangun:
 * 1. Quick Action Hub (Buat Modul, Library Modul, Grading Center, Laporan Excel, Kelas)
 * 2. Real-time Metric & Stat Cards (Total Modul, Siswa Terhubung, Pending Grading, Rata-rata Nilai)
 * 3. Interactive Module Workspace (Filter status modul, indikator 7 komponen inti, progress pengumpulan)
 * 4. Live Grading Center Queue (Antrean tugas pending dengan tombol nilai instan)
 * 5. Ringkasan Kelas Binaan & Direktori Akademik
 * =============================================================================
 */
class DashboardController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * Menampilkan antarmuka terpadu Dashboard Workspace Guru.
     */
    public function index(Request $request)
    {
        $teacher = $this->teacher();
        $teacherId = $teacher->id;

        // 1. Query seluruh modul milik guru
        $allTeacherModules = Module::where('teacher_id', $teacherId)
            ->with(['schoolClass.students', 'studentResults.student', 'clonedFrom', 'subject'])
            ->latest()
            ->get();

        // 2. Metrik Global
        $totalModulesCount = $allTeacherModules->count();
        $publishedCount    = $allTeacherModules->where('status', 'published')->count();
        $draftCount        = $allTeacherModules->where('status', 'draft')->count();
        $closedCount       = $allTeacherModules->where('status', 'closed')->count();
        $sharedCount       = $allTeacherModules->where('is_shared', true)->count();

        // Kelas Binaan & Siswa
        $assignedClasses = $teacher->assignedClasses();
        $totalStudentsCount = $assignedClasses->sum(function ($c) {
            return $c->students ? $c->students->count() : 0;
        });

        // Hasil Evaluasi & Penilaian
        $allStudentResults = $allTeacherModules->pluck('studentResults')->flatten();
        $pendingResultsCount = $allStudentResults->where('grading_status', 'pending')->count();
        $gradedResults = $allStudentResults->where('grading_status', 'graded');
        $averageScore = $gradedResults->count() > 0 ? (int) round($gradedResults->avg('summative_score')) : 0;
        
        // Tingkat Ketuntasan Siswa (Skor >= 75)
        $passedCount = $gradedResults->where('summative_score', '>=', 75)->count();
        $completionRate = $gradedResults->count() > 0 
            ? round(($passedCount / $gradedResults->count()) * 100, 1) 
            : 0;

        // Total modul bersama di Library
        $totalSharedInLibrary = Module::where('is_shared', true)->count();

        // 3. Filter Tab Status Modul (Dibatasi maksimal 3 modul terbaru / draf)
        $statusFilter = $request->query('status', 'all');
        $filteredModules = match ($statusFilter) {
            'draft'     => $allTeacherModules->where('status', 'draft'),
            'published' => $allTeacherModules->where('status', 'published'),
            'shared'    => $allTeacherModules->where('is_shared', true),
            default     => $allTeacherModules,
        };

        // Batasi maksimal 3 modul saja yang tampil di workspace dashboard
        $limitedModules = $filteredModules->take(3);

        // Format data modul untuk antarmuka
        $modulesData = $limitedModules->map(function ($mod) {
            $classStudentsCount = $mod->schoolClass && $mod->schoolClass->students ? $mod->schoolClass->students->count() : 0;
            $submittedCount     = $mod->studentResults ? $mod->studentResults->count() : 0;
            $pendingCount       = $mod->studentResults ? $mod->studentResults->where('grading_status', 'pending')->count() : 0;
            $gradedCount        = $mod->studentResults ? $mod->studentResults->where('grading_status', 'graded')->count() : 0;
            $submissionPercent  = $classStudentsCount > 0 ? min(100, round(($submittedCount / $classStudentsCount) * 100)) : 0;

            return [
                'model'                   => $mod,
                'id'                      => $mod->id,
                'title'                   => $mod->title,
                'status'                  => $mod->status,
                'status_label'            => $mod->statusLabel(),
                'is_shared'               => (bool) $mod->is_shared,
                'clone_count'             => (int) ($mod->clone_count ?? 0),
                'class_name'              => $mod->schoolClass ? $mod->schoolClass->full_name : 'Semua Kelas',
                'active_components'       => $mod->activeComponents(),
                'active_components_count' => count($mod->activeComponents()),
                'total_students'          => $classStudentsCount,
                'submitted_count'         => $submittedCount,
                'pending_count'           => $pendingCount,
                'graded_count'            => $gradedCount,
                'submission_percent'      => $submissionPercent,
                'updated_at_formatted'    => $mod->updated_at ? $mod->updated_at->translatedFormat('d M Y') : '-',
            ];
        });

        // 4. Live Antrean Tugas Perlu Dinilai (Pending Submissions Queue)
        $pendingQueue = collect();
        $moduleIds = $allTeacherModules->pluck('id')->toArray();

        if (!empty($moduleIds)) {
            // LKPD
            $pendingLkpd = Submission::whereHas('lkpd', fn($q) => $q->whereIn('module_id', $moduleIds))
                ->whereNull('manual_score')
                ->with(['student.schoolClass', 'lkpd.module'])
                ->latest()
                ->take(6)
                ->get();

            foreach ($pendingLkpd as $sub) {
                if ($sub->student && $sub->lkpd && $sub->lkpd->module) {
                    $pendingQueue->push([
                        'type'         => 'lkpd',
                        'type_label'   => 'Tugas LKPD Kelompok',
                        'badge_color'  => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'file_badge'   => 'Berkas LKPD',
                        'student_name' => $sub->student->name,
                        'student_nisn' => $sub->student->identity_number,
                        'class_name'   => $sub->student->schoolClass ? $sub->student->schoolClass->full_name : 'Kelas Binaan',
                        'module_id'    => $sub->lkpd->module_id,
                        'module_title' => $sub->lkpd->module->title,
                        'submitted_at' => $sub->created_at,
                        'student_id'   => $sub->student_id,
                    ]);
                }
            }

            // Job Sheet
            $pendingJobSheet = JobSheetSubmission::whereHas('jobSheet', fn($q) => $q->whereIn('module_id', $moduleIds))
                ->whereNull('manual_score')
                ->with(['student.schoolClass', 'jobSheet.module'])
                ->latest()
                ->take(6)
                ->get();

            foreach ($pendingJobSheet as $sub) {
                if ($sub->student && $sub->jobSheet && $sub->jobSheet->module) {
                    $pendingQueue->push([
                        'type'         => 'jobsheet',
                        'type_label'   => 'Job Sheet Praktikum',
                        'badge_color'  => 'bg-rose-50 text-rose-700 border-rose-200',
                        'file_badge'   => 'PDF Praktik',
                        'student_name' => $sub->student->name,
                        'student_nisn' => $sub->student->identity_number,
                        'class_name'   => $sub->student->schoolClass ? $sub->student->schoolClass->full_name : 'Kelas Binaan',
                        'module_id'    => $sub->jobSheet->module_id,
                        'module_title' => $sub->jobSheet->module->title,
                        'submitted_at' => $sub->created_at,
                        'student_id'   => $sub->student_id,
                    ]);
                }
            }

            // Embed Screenshot
            $pendingEmbed = EmbedSubmission::whereIn('module_id', $moduleIds)
                ->whereNull('manual_score')
                ->with(['student.schoolClass', 'module'])
                ->latest()
                ->take(6)
                ->get();

            foreach ($pendingEmbed as $sub) {
                if ($sub->student && $sub->module) {
                    $pendingQueue->push([
                        'type'         => 'embed',
                        'type_label'   => 'Screenshot Praktik',
                        'badge_color'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'file_badge'   => 'Tangkapan Layar',
                        'student_name' => $sub->student->name,
                        'student_nisn' => $sub->student->identity_number,
                        'class_name'   => $sub->student->schoolClass ? $sub->student->schoolClass->full_name : 'Kelas Binaan',
                        'module_id'    => $sub->module_id,
                        'module_title' => $sub->module->title,
                        'submitted_at' => $sub->created_at,
                        'student_id'   => $sub->student_id,
                    ]);
                }
            }

            // Video Summary
            $pendingVideo = VideoSummary::whereIn('module_id', $moduleIds)
                ->whereNull('manual_score')
                ->with(['student.schoolClass', 'module'])
                ->latest()
                ->take(6)
                ->get();

            foreach ($pendingVideo as $sub) {
                if ($sub->student && $sub->module) {
                    $pendingQueue->push([
                        'type'         => 'video',
                        'type_label'   => 'Ringkasan Video',
                        'badge_color'  => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                        'file_badge'   => 'Teks Resume',
                        'student_name' => $sub->student->name,
                        'student_nisn' => $sub->student->identity_number,
                        'class_name'   => $sub->student->schoolClass ? $sub->student->schoolClass->full_name : 'Kelas Binaan',
                        'module_id'    => $sub->module_id,
                        'module_title' => $sub->module->title,
                        'submitted_at' => $sub->created_at,
                        'student_id'   => $sub->student_id,
                    ]);
                }
            }
        }

        // Urutkan antrean berdasarkan waktu pengumpulan terbaru
        $pendingQueueSorted = $pendingQueue->sortByDesc('submitted_at')->values()->take(5);
        $totalPendingCount = max($pendingResultsCount, $pendingQueue->count());

        // 5. Ringkasan Kelas Binaan
        $classesSummary = $assignedClasses->map(function ($cls) use ($teacherId) {
            $classStats = $cls->statsForTeacher($teacherId);
            return [
                'id'                => $cls->id,
                'full_name'         => $cls->full_name,
                'grade'             => $cls->grade,
                'major_name'        => $cls->major_name,
                'total_students'    => $classStats['total_students'],
                'total_modules'     => $classStats['total_modules'],
                'published_modules' => $classStats['published_modules'],
                'avg_score'         => $classStats['avg_score'],
            ];
        });

        $counts = [
            'all'       => $totalModulesCount,
            'published' => $publishedCount,
            'draft'     => $draftCount,
            'shared'    => $sharedCount,
        ];

        $stats = [
            'total_modules'        => $totalModulesCount,
            'published_modules'    => $publishedCount,
            'draft_modules'        => $draftCount,
            'shared_modules'       => $sharedCount,
            'total_classes'        => $assignedClasses->count(),
            'total_students'       => $totalStudentsCount,
            'pending_grading'      => $totalPendingCount,
            'average_score'        => $averageScore,
            'completion_rate'      => $completionRate,
            'total_shared_library' => $totalSharedInLibrary,
        ];

        return view('pages.teacher.dashboard', compact(
            'teacher',
            'stats',
            'counts',
            'modulesData',
            'statusFilter',
            'pendingQueueSorted',
            'classesSummary'
        ));
    }
}
