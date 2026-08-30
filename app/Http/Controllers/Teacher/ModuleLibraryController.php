<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: ModuleLibraryController
 * =============================================================================
 * PERPUSTAKAAN & REPOSITORI MODUL KOLABORATIF (MODULE LIBRARY):
 * -----------------------------------------------------------------------------
 * Controller ini melayani fitur Library Modul antar-guru di SMK Negeri 3 Yogyakarta.
 * Guru dapat:
 * 1. Menjelajahi instrumen modul yang telah dibagikan oleh rekan guru lain.
 * 2. Melakukan pratinjau struktur kurikulum 5 bagian E-Modul.
 * 3. Menyalin / Kloning modul secara mendalam (deep copy) ke workspace pribadi
 *    tanpa memengaruhi modul sumber asli.
 * 4. Menyalakan / mematikan sakelar izin berbagi modul miliknya ke library.
 * =============================================================================
 */
class ModuleLibraryController extends Controller
{
    private function teacher(): Teacher
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * Halaman Utama: Katalog & Repositori Modul Pembelajaran Kolaboratif.
     */
    public function index(Request $request)
    {
        $currentTeacher = $this->teacher();

        $query = Module::with(['teacher', 'schoolClass', 'clonedFrom.teacher', 'subject'])
            ->where('is_shared', true);

        // Tab Filter: 'all' (semua modul bersama), 'others' (guru lain), 'my_shared' (milik saya yang dibagikan)
        $tab = $request->get('tab', 'all');
        if ($tab === 'others') {
            $query->where('teacher_id', '!=', $currentTeacher->id);
        } elseif ($tab === 'my_shared') {
            $query->where('teacher_id', $currentTeacher->id);
        }

        // Filter Pencarian Judul Modul atau Nama Guru Penyusun
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function ($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tingkat Kelas (X, XI, XII)
        if ($request->filled('grade')) {
            $query->whereHas('schoolClass', function ($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        // Filter Jurusan (RPL, TKJ, dll.)
        if ($request->filled('major')) {
            $query->whereHas('schoolClass', function ($q) use ($request) {
                $q->where('major_name', $request->major);
            });
        }

        // Filter Spesifik Guru Kontributor
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter Komponen Pembelajaran Khusus
        if ($request->filled('component')) {
            $compField = match($request->component) {
                'pre_test'  => 'has_pre_test',
                'materi'    => 'has_materi',
                'video'     => 'has_video',
                'embed'     => 'has_embed',
                'job_sheet' => 'has_job_sheet',
                'lkpd'      => 'has_lkpd',
                'post_test' => 'has_post_test',
                default     => null,
            };
            if ($compField) {
                $query->where($compField, true);
            }
        }

        // Sorting Urutan
        $sort = $request->get('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderByDesc('clone_count')->orderByDesc('shared_at')->orderByDesc('id');
        } elseif ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } else {
            $query->latest('shared_at')->latest('id');
        }

        $modules = $query->paginate(9)->withQueryString();

        // Statistik Agregat Perpustakaan Modul
        $allSharedModules = Module::where('is_shared', true)->get();
        $stats = [
            'total_shared'       => $allSharedModules->count(),
            'total_contributors' => $allSharedModules->pluck('teacher_id')->unique()->count(),
            'total_cloned'       => (int) $allSharedModules->sum('clone_count'),
            'my_shared_count'    => Module::where('teacher_id', $currentTeacher->id)->where('is_shared', true)->count(),
            'my_total_modules'   => Module::where('teacher_id', $currentTeacher->id)->count(),
        ];

        // Opsi Dropdown Filter
        $availableGrades = SchoolClass::select('grade')->distinct()->orderBy('grade')->pluck('grade');
        $availableMajors = SchoolClass::select('major_name')->distinct()->orderBy('major_name')->pluck('major_name');
        $contributors = Teacher::whereIn('id', $allSharedModules->pluck('teacher_id')->unique())->orderBy('name')->get();

        // Daftar kelas binaan guru yang sedang login (untuk target salinan modul)
        $allClasses = SchoolClass::orderBy('grade')->orderBy('major_name')->get();

        return view('pages.teacher.library.index', compact(
            'modules',
            'stats',
            'tab',
            'sort',
            'availableGrades',
            'availableMajors',
            'contributors',
            'allClasses'
        ));
    }

    /**
     * Pratinjau Modul di Library (Read-only Preview bagi Guru Lain).
     */
    public function show(Module $module)
    {
        $currentTeacher = $this->teacher();

        // Hanya modul yang dibagikan atau milik guru itu sendiri yang dapat diakses
        if (!$module->is_shared && $module->teacher_id !== $currentTeacher->id) {
            abort(404, 'Modul ini tidak tersedia di Library Modul publik.');
        }

        $module->load(['teacher', 'schoolClass', 'clonedFrom.teacher', 'preTest.questions', 'postTest.questions', 'jobSheets', 'lkpds']);

        $allClasses = SchoolClass::orderBy('grade')->orderBy('major_name')->get();

        return view('pages.teacher.library.show', compact('module', 'allClasses'));
    }

    /**
     * Proses Kloning / Salin Modul dari Library ke Workspace Pribadi Guru.
     */
    public function clone(Request $request, Module $module)
    {
        $currentTeacher = $this->teacher();

        if (!$module->is_shared && $module->teacher_id !== $currentTeacher->id) {
            abort(403, 'Anda tidak memiliki izin untuk menyalin modul ini.');
        }

        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'title'    => ['nullable', 'string', 'max:255'],
        ], [
            'class_id.required' => 'Pilih target kelas binaan untuk modul salinan ini.',
            'class_id.exists'   => 'Kelas binaan yang dipilih tidak valid.',
        ]);

        $customTitle = $validated['title'] ? trim($validated['title']) : null;

        // Eksekusi Deep Copy
        $newModule = $module->cloneToTeacher($currentTeacher, (int) $validated['class_id'], $customTitle);

        return redirect()
            ->route('teacher.modules.show', $newModule)
            ->with('success', "Modul \"{$newModule->title}\" berhasil disalin ke workspace Anda! Seluruh komponen siap disesuaikan secara mandiri.");
    }

    /**
     * Sakelar Cepat: Membagikan atau Membatalkan Pembagian Modul ke Library.
     */
    public function toggleShare(Module $module)
    {
        $currentTeacher = $this->teacher();

        if ($module->teacher_id !== $currentTeacher->id) {
            abort(403, 'Anda hanya dapat membagikan modul milik Anda sendiri.');
        }

        $isShared = !$module->is_shared;

        $module->update([
            'is_shared' => $isShared,
            'shared_at' => $isShared ? now() : null,
        ]);

        $message = $isShared
            ? 'Modul berhasil dibagikan ke Library Modul! Rekan guru lain kini dapat melihat dan menyalin instrumen pembelajaran ini.'
            : 'Modul telah ditarik dari Library Modul dan kembali berstatus Pribadi.';

        return back()->with('success', $message);
    }
}
