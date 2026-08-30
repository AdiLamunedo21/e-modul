<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ModuleLibraryController extends Controller
{
    /**
     * Menampilkan katalog supervisi seluruh modul yang dibagikan ke Library Sekolah.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $subjectId = $request->query('subject_id');
        $grade = $request->query('grade');
        $teacherId = $request->query('teacher_id');
        $sort = $request->query('sort', 'popular'); // 'popular', 'latest', 'title_asc'

        $query = Module::with(['teacher', 'schoolClass.major', 'subject', 'clonedFrom.teacher'])
            ->where('is_shared', true);

        // Filter Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function ($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%")
                        ->orWhere('identity_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subject', function ($s) use ($search) {
                      $s->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                  })
                  ->orWhereHas('schoolClass', function ($c) use ($search) {
                      $c->where('grade', 'like', "%{$search}%")
                        ->orWhere('major_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Mata Pelajaran
        if ($subjectId && $subjectId !== 'all') {
            $query->where('subject_id', $subjectId);
        }

        // Filter Tingkat Kelas
        if ($grade && $grade !== 'all') {
            $query->whereHas('schoolClass', function ($q) use ($grade) {
                $q->where('grade', $grade);
            });
        }

        // Filter Guru Kontributor
        if ($teacherId && $teacherId !== 'all') {
            $query->where('teacher_id', $teacherId);
        }

        // Sorting Urutan Tampilan
        if ($sort === 'latest') {
            $query->orderByDesc('shared_at')->orderByDesc('id');
        } elseif ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } else { // default: 'popular'
            $query->orderByDesc('clone_count')->orderByDesc('shared_at')->orderByDesc('id');
        }

        $modules = $query->paginate(9)->withQueryString();

        // Top 3 Modul Paling Banyak Dikloning / Direferensikan
        $topClonedModules = Module::with(['teacher', 'schoolClass', 'subject'])
            ->where('is_shared', true)
            ->orderByDesc('clone_count')
            ->take(3)
            ->get();

        // Agregat Metrik Statistik Perpustakaan
        $allShared = Module::where('is_shared', true)->get();
        $stats = [
            'total_shared'        => $allShared->count(),
            'total_clones'        => (int) $allShared->sum('clone_count'),
            'total_contributors'  => $allShared->pluck('teacher_id')->unique()->count(),
            'most_popular_count'  => (int) ($allShared->max('clone_count') ?? 0),
        ];

        // Data Opsi Dropdown Filter
        $subjects = Subject::orderBy('name')->get();
        $contributors = Teacher::whereIn('id', $allShared->pluck('teacher_id')->unique())->orderBy('name')->get();
        $grades = ['X', 'XI', 'XII', 'XIII'];

        return view('pages.admin.library.index', compact(
            'modules',
            'allShared',
            'topClonedModules',
            'stats',
            'subjects',
            'contributors',
            'grades',
            'search',
            'subjectId',
            'grade',
            'teacherId',
            'sort'
        ));
    }

    /**
     * Pratinjau struktur kurikulum 5 bagian E-Modul di Library oleh Admin.
     */
    public function show(Module $module)
    {
        $module->load([
            'teacher',
            'schoolClass.major',
            'subject',
            'clonedFrom.teacher',
            'clones.teacher',
            'clones.schoolClass',
            'preTest.questions',
            'postTest.questions',
            'jobSheets',
            'lkpds'
        ]);

        $informasiUmum = $module->informasi_umum_data ?? [];
        $materiData = $module->materi_data ?? [];
        $videoData = $module->video_data ?? [];
        $videosList = $module->videosList();
        $embedData = $module->embed_data ?? [];
        $jobSheetData = $module->job_sheet_data ?? [];
        $lkpdData = $module->lkpd_data ?? [];

        $jobSheet = $module->jobSheets->first();
        $lkpd = $module->lkpds->first();

        // 5 Bagian Kurikulum
        $sections = $module->moduleSectionsSummary();

        return view('pages.admin.library.show', compact(
            'module',
            'informasiUmum',
            'materiData',
            'videoData',
            'videosList',
            'embedData',
            'jobSheetData',
            'lkpdData',
            'jobSheet',
            'lkpd',
            'sections'
        ));
    }

    /**
     * Moderasi / Toggle status berbagi modul ke Library oleh Admin.
     */
    public function toggleShare(Module $module)
    {
        $module->is_shared = !$module->is_shared;

        if ($module->is_shared && !$module->shared_at) {
            $module->shared_at = now();
        }

        $module->save();

        $statusMsg = $module->is_shared
            ? "Modul \"{$module->title}\" berhasil dipublikasikan ke Library Sekolah."
            : "Modul \"{$module->title}\" ditarik dari Library Sekolah.";

        return redirect()->back()->with('success', $statusMsg);
    }
}
