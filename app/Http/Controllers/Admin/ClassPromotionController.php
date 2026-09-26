<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmbedSubmission;
use App\Models\JobSheetSubmission;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Submission;
use App\Services\GraduationReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClassPromotionController extends Controller
{
    /**
     * Menampilkan antarmuka terpadu Kenaikan Kelas & Kelulusan Siswa.
     */
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'promotion'); // 'promotion' or 'graduation'
        $selectedSourceClassId = $request->query('source_class_id');
        $selectedGraduationClassId = $request->query('graduation_class_id');

        // Kelompok kelas berdasarkan tingkat
        $classesX = SchoolClass::with('major')->where('grade', 'X')->orderBy('major_id')->orderBy('section')->get();
        $classesXI = SchoolClass::with('major')->where('grade', 'XI')->orderBy('major_id')->orderBy('section')->get();
        $classesXII = SchoolClass::with('major')->where('grade', 'XII')->withCount('students')->orderBy('major_id')->orderBy('section')->get();

        $allClasses = SchoolClass::with('major')->orderBy('grade')->orderBy('major_id')->orderBy('section')->get();

        // Statistik
        $totalStudentsX = Student::whereHas('schoolClass', fn($q) => $q->where('grade', 'X'))->count();
        $totalStudentsXI = Student::whereHas('schoolClass', fn($q) => $q->where('grade', 'XI'))->count();
        $totalStudentsXII = Student::whereHas('schoolClass', fn($q) => $q->where('grade', 'XII'))->count();

        $stats = [
            'total_x'   => $totalStudentsX,
            'total_xi'  => $totalStudentsXI,
            'total_xii' => $totalStudentsXII,
        ];

        return view('pages.admin.promotions.index', compact(
            'classesX',
            'classesXI',
            'classesXII',
            'allClasses',
            'stats',
            'activeTab',
            'selectedSourceClassId',
            'selectedGraduationClassId'
        ));
    }

    /**
     * Endpoint API internal untuk mengambil daftar siswa di kelas tertentu (JSON).
     */
    public function getStudentsByClass(SchoolClass $class)
    {
        $students = $class->students()
            ->orderBy('name')
            ->get(['students.id', 'students.name', 'students.identity_number']);

        if ($students->isEmpty()) {
            $students = Student::where('class_id', $class->id)
                ->orderBy('name')
                ->get(['id', 'name', 'identity_number']);
        }

        return response()->json([
            'class'    => [
                'id'        => $class->id,
                'name'      => $class->full_name,
                'short_name'=> $class->short_name,
                'grade'     => $class->grade,
            ],
            'students' => $students,
            'count'    => $students->count(),
        ]);
    }

    /**
     * Memproses Kenaikan Kelas (Promosi Rombel Siswa).
     */
    public function promote(Request $request)
    {
        $validated = $request->validate([
            'source_class_id' => ['required', 'exists:classes,id'],
            'target_class_id' => ['required', 'exists:classes,id', 'different:source_class_id'],
            'student_ids'     => ['required', 'array', 'min:1'],
            'student_ids.*'   => ['exists:students,id'],
        ], [
            'source_class_id.required' => 'Kelas asal wajib dipilih.',
            'target_class_id.required' => 'Kelas tujuan wajib dipilih.',
            'target_class_id.different'=> 'Kelas tujuan harus berbeda dengan kelas asal.',
            'student_ids.required'     => 'Pilih minimal satu siswa yang akan dipromosikan naik kelas.',
            'student_ids.min'          => 'Pilih minimal satu siswa yang akan dipromosikan naik kelas.',
        ]);

        $sourceClass = SchoolClass::findOrFail($validated['source_class_id']);
        $targetClass = SchoolClass::findOrFail($validated['target_class_id']);

        $students = Student::whereIn('id', $validated['student_ids'])->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa yang dipilih.');
        }

        // Ambil mapel aktif di kelas tujuan untuk langsung disinkronkan
        $targetSubjectIds = $targetClass->getAvailableSubjectIds();

        DB::transaction(function () use ($students, $targetClass, $targetSubjectIds) {
            foreach ($students as $student) {
                // 1. Pindahkan class_id siswa ke kelas tujuan
                $student->class_id = $targetClass->id;
                $student->save();

                // 2. Sinkronkan pivot class_student ke kelas baru
                $student->classes()->sync([$targetClass->id]);

                // 3. Selaraskan seluruh mata pelajaran siswa dengan mata pelajaran kelas tujuan
                if (!empty($targetSubjectIds)) {
                    $student->subjects()->sync($targetSubjectIds);
                }
            }
        });

        $promotedCount = $students->count();

        return redirect()->route('admin.promotions.index', ['tab' => 'promotion'])
            ->with('success', "Selamat! Berhasil menaikkan {$promotedCount} siswa dari {$sourceClass->full_name} ke {$targetClass->full_name}. Mata pelajaran siswa telah otomatis diselaraskan dengan kelas tujuan.");
    }

    /**
     * Mengunduh file Excel Rekapitulasi Nilai Kelulusan Siswa Kelas XII.
     */
    public function exportGraduationReport(Request $request, GraduationReportService $reportService)
    {
        $classId = $request->query('class_id');
        $targetClass = null;

        if ($classId && $classId !== 'all') {
            $targetClass = SchoolClass::findOrFail($classId);
            if ($targetClass->grade !== 'XII') {
                return back()->with('error', 'Hanya kelas tingkat XII yang dapat diunduh rekap kelulusannya.');
            }
        }

        $binary = $reportService->generateReport($targetClass);
        $cleanClassName = $targetClass ? str_replace(' ', '_', $targetClass->short_name) : 'Semua_Kelas_XII';
        $filename = "Rekap_Kelulusan_{$cleanClassName}_" . date('Ymd_His') . ".xlsx";

        return response()->streamDownload(function () use ($binary) {
            echo $binary;
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Memproses Kelulusan Siswa Kelas XII & Menghapus Akun Beserta File Tugas.
     */
    public function graduate(Request $request)
    {
        $validated = $request->validate([
            'class_id' => ['required', 'string'],
        ], [
            'class_id.required' => 'Pilih rombel kelas XII yang akan diproses kelulusannya.',
        ]);

        $classId = $validated['class_id'];

        if ($classId === 'all') {
            $students = Student::whereHas('schoolClass', fn($q) => $q->where('grade', 'XII'))->get();
            $label = 'seluruh siswa kelas XII';
        } else {
            $class = SchoolClass::findOrFail($classId);
            if ($class->grade !== 'XII') {
                return back()->with('error', 'Pengamanan: Hanya kelas tingkat XII yang dapat diproses kelulusannya.');
            }
            $students = Student::where('class_id', $class->id)->get();
            $label = "siswa {$class->full_name}";
        }

        if ($students->isEmpty()) {
            return back()->with('info', 'Tidak ada data siswa kelas XII yang ditemukan untuk diproses kelulusannya.');
        }

        $studentIds = $students->pluck('id')->toArray();
        $deletedCount = count($studentIds);

        // 1. Kumpulkan seluruh file tugas siswa (LKPD, Job Sheet, screenshot embed) untuk dibersihkan dari server
        $lkpdFiles = Submission::whereIn('student_id', $studentIds)
            ->whereNotNull('uploaded_file_path')
            ->pluck('uploaded_file_path')
            ->toArray();

        $jobSheetFiles = JobSheetSubmission::whereIn('student_id', $studentIds)
            ->whereNotNull('uploaded_file_path')
            ->pluck('uploaded_file_path')
            ->toArray();

        $embedFiles = EmbedSubmission::whereIn('student_id', $studentIds)
            ->whereNotNull('screenshot_path')
            ->pluck('screenshot_path')
            ->toArray();

        $allFiles = array_filter(array_merge($lkpdFiles, $jobSheetFiles, $embedFiles));

        // Hapus file fisik dari storage
        foreach ($allFiles as $filePath) {
            Storage::disk('public')->delete($filePath);
        }

        // 2. Hapus akun siswa (cascade delete otomatis menghapus results, submissions, pivot class_student, dsb.)
        Student::whereIn('id', $studentIds)->delete();

        $filesCount = count($allFiles);

        return redirect()->route('admin.promotions.index', ['tab' => 'graduation'])
            ->with('success', "Proses kelulusan selesai! Berhasil meluluskan dan membersihkan {$deletedCount} akun {$label}. Sebanyak {$filesCount} file tugas fisik di storage juga berhasil dibersihkan.");
    }
}
