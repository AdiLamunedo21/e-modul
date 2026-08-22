<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\ModuleGradesExport;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: ReportController
 * =============================================================================
 * PUSAT LAPORAN & EKSPOR SPREADSHEET (EXCEL .XLSX):
 * -----------------------------------------------------------------------------
 * Controller ini melayani kebutuhan guru dan manajemen sekolah dalam memantau
 * rekapitulasi nilai modul serta mengekspor data hasil belajar ke format berkas
 * spreadsheet (.xlsx / Microsoft Excel).
 * =============================================================================
 */
class ReportController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * Halaman Utama Pusat Laporan (Report Center Hub).
     */
    public function index(Request $request)
    {
        $teacher = $this->teacher();

        $query = Module::where('teacher_id', $teacher->id)
            ->with(['schoolClass.students', 'studentResults']);

        // Filter status modul (published / draft / closed)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter target kelas
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Pencarian judul modul
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $modules = $query->latest()->paginate(10)->withQueryString();

        // Hitung statistik rekapitulasi laporan
        $allTeacherModules = Module::where('teacher_id', $teacher->id)->with('studentResults')->get();
        $allResults = $allTeacherModules->pluck('studentResults')->flatten();
        $gradedResults = $allResults->where('grading_status', 'graded');

        $stats = [
            'total_modules'     => $allTeacherModules->count(),
            'published_modules' => $allTeacherModules->where('status', 'published')->count(),
            'total_submissions' => $allResults->count(),
            'completed_grading' => $gradedResults->count(),
            'average_score'     => $gradedResults->count() > 0 ? (int) round($gradedResults->avg('summative_score')) : 0,
        ];

        $classes = $teacher->modules()->with('schoolClass')->get()->pluck('schoolClass')->filter()->unique('id');

        return view('pages.teacher.reports.index', compact('modules', 'stats', 'classes'));
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
