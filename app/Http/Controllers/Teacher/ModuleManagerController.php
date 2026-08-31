<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: ModuleManagerController
 * =============================================================================
 * 
 * PANDUAN PENGEMBANG (DEVELOPER ARCHITECTURE NOTES):
 * -----------------------------------------------------------------------------
 * Controller ini adalah pusat kendali utama bagi Guru untuk mengelola siklus 
 * hidup E-Modul (CRUD, Status Publikasi, dan Visualisasi Detail Modul).
 * 
 * STRUKTUR MODUL TERPADU (5 BAGIAN UTAMA):
 * Saat method `show(Module $module)` dipanggil, view `pages.teacher.modules.show` 
 * akan merender data 5 Bagian Umum E-Modul secara dinamis menggunakan helper 
 * method di Model `Module.php`:
 * 
 * 1. Bagian Awal          : `Module::bagianAwalComponents()` (Cover, Kata Pengantar, Daftar Isi, Petunjuk)
 * 2. Pendahuluan          : `Module::pendahuluanComponents()` (Tujuan & Capaian, Peta Konsep, Glosarium)
 * 3. Kegiatan Belajar     : `Module::kegiatanBelajarComponents()` (Materi & PPT, Video YouTube, Job Sheet)
 * 4. Evaluasi & Latihan   : `Module::evaluasiLatihanComponents()` (Pre-test, Praktik Embed, Tugas LKPD)
 * 5. Bagian Akhir         : `Module::bagianAkhirComponents()` (Post-test, Daftar Pustaka)
 * 
 * Seluruh ringkasan metrik, persentase komponen aktif, dan navigasi anchor
 * dihasilkan secara terpusat oleh method `Module::moduleSectionsSummary()`.
 * =============================================================================
 */
class ModuleManagerController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * Halaman Manajer Modul — menampilkan daftar semua modul milik guru yang sedang login.
     * Mendukung pemilahan mata pelajaran (Subject Switcher) dan tab filter status.
     */
    public function index(Request $request)
    {
        $teacher = $this->teacher();
        $teacherSubjects = $teacher->subjects()->get();

        $query = Module::with(['schoolClass', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->latest();

        // Filter berdasarkan Mata Pelajaran jika dipilih
        $selectedSubjectId = $request->filled('subject_id') ? (int) $request->subject_id : null;
        if ($selectedSubjectId) {
            $query->where('subject_id', $selectedSubjectId);
        }

        // Filter berdasarkan Semester (1: Ganjil, 2: Genap)
        $selectedSemester = $request->filled('semester') && in_array($request->semester, ['1', '2']) ? $request->semester : null;
        if ($selectedSemester) {
            $query->where('semester', $selectedSemester);
        }

        // Filter berdasarkan tab status jika ada
        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'closed'])) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kata kunci pencarian (search)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('schoolClass', function ($qc) use ($search) {
                      $qc->where('major_name', 'like', "%{$search}%")
                         ->orWhere('grade', 'like', "%{$search}%")
                         ->orWhere('section', 'like', "%{$search}%")
                         ->orWhereHas('major', function ($qm) use ($search) {
                             $qm->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                         });
                  })
                  ->orWhereHas('subject', function ($qs) use ($search) {
                      $qs->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        $modules = $query->paginate(10)->withQueryString();

        // Hitung statistik modul global & per subjek untuk guru ini
        $baseQuery = Module::where('teacher_id', $teacher->id);
        if ($selectedSubjectId) {
            $baseQuery->where('subject_id', $selectedSubjectId);
        }
        if ($selectedSemester) {
            $baseQuery->where('semester', $selectedSemester);
        }

        $counts = [
            'all'       => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'draft'     => (clone $baseQuery)->where('status', 'draft')->count(),
            'closed'    => (clone $baseQuery)->where('status', 'closed')->count(),
        ];

        // Hitung total modul per mata pelajaran milik guru
        $subjectCounts = [];
        $totalAllSubjects = Module::where('teacher_id', $teacher->id)->count();
        foreach ($teacherSubjects as $sub) {
            $subjectCounts[$sub->id] = Module::where('teacher_id', $teacher->id)->where('subject_id', $sub->id)->count();
        }

        return view('pages.teacher.modules.index', compact(
            'modules',
            'counts',
            'teacherSubjects',
            'subjectCounts',
            'totalAllSubjects',
            'selectedSubjectId',
            'selectedSemester'
        ));
    }

    /**
     * Form buat modul baru (langkah 1: input judul modul, mata pelajaran & target kelas).
     */
    public function create()
    {
        $teacher = $this->teacher();
        $teacherSubjects = $teacher->subjects()->get();
        if ($teacherSubjects->isEmpty()) {
            $teacherSubjects = Subject::orderBy('name')->get();
        }
        $classes = $teacher->classes()->with('major')->orderBy('grade')->orderBy('section')->get();
        if ($classes->isEmpty()) {
            $classes = SchoolClass::orderBy('grade')->orderBy('major_name')->get();
        }

        return view('pages.teacher.modules.create', compact('classes', 'teacherSubjects'));
    }

    /**
     * Menyimpan modul baru ke database dengan status default 'draft',
     * lalu mengarahkan guru ke halaman Detail Modul 5 Bagian.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'class_id'   => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester'   => ['nullable', 'in:1,2'],
        ], [
            'title.required'      => 'Judul E-Modul wajib diisi.',
            'class_id.required'   => 'Pilih target kelas / jurusan.',
            'subject_id.required' => 'Pilih mata pelajaran pengampu untuk modul ini.',
            'subject_id.exists'   => 'Mata pelajaran yang dipilih tidak valid.',
        ]);

        $module = Module::create([
            'teacher_id' => $this->teacher()->id,
            'subject_id' => $validated['subject_id'],
            'semester'   => $validated['semester'] ?? '1',
            'title'      => $validated['title'],
            'class_id'   => $validated['class_id'],
            'status'     => 'draft',
        ]);

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', 'Modul baru berhasil dibuat! Lanjutkan dengan mengisi konten 5 Bagian E-Modul.');
    }

    /**
     * Halaman Detail Modul:
     * Menampilkan dashboard visual 5 Bagian Standar E-Modul, progress bar aktivasi komponen,
     * tombol edit langsung, serta sakelar toggle instan untuk setiap komponen.
     */
    public function show(Module $module)
    {
        $this->authorizeModule($module);
        $module->load(['schoolClass', 'subject']);

        $teacher = $this->teacher();
        $teacherSubjects = $teacher->subjects()->get();
        if ($teacherSubjects->isEmpty()) {
            $teacherSubjects = Subject::orderBy('name')->get();
        }
        $classes = $teacher->classes()->with('major')->orderBy('grade')->orderBy('section')->get();
        if ($classes->isEmpty()) {
            $classes = SchoolClass::orderBy('grade')->orderBy('major_name')->get();
        }

        return view('pages.teacher.modules.show', compact('module', 'teacherSubjects', 'classes'));
    }

    /**
     * Memperbarui informasi nama dan identitas umum modul (Judul, Mapel, Kelas, Semester).
     */
    public function update(Request $request, Module $module)
    {
        $this->authorizeModule($module);

        $validated = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'class_id'   => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester'   => ['nullable', 'in:1,2'],
        ], [
            'title.required'      => 'Judul E-Modul wajib diisi.',
            'class_id.required'   => 'Pilih target kelas / jurusan.',
            'subject_id.required' => 'Pilih mata pelajaran pengampu.',
            'subject_id.exists'   => 'Mata pelajaran yang dipilih tidak valid.',
        ]);

        $module->update([
            'title'      => $validated['title'],
            'class_id'   => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            'semester'   => $validated['semester'] ?? '1',
        ]);

        return redirect()->route('teacher.modules.show', $module)
            ->with('success', 'Nama dan identitas modul berhasil diperbarui!');
    }

    /**
     * Memperbarui status publikasi modul (draft -> published -> closed).
     */
    public function updateStatus(Request $request, Module $module)
    {
        $this->authorizeModule($module);

        $validated = $request->validate([
            'status' => ['required', 'in:draft,published,closed'],
        ]);

        $module->update(['status' => $validated['status']]);

        $label = match($validated['status']) {
            'published' => 'Modul berhasil dipublikasikan dan dapat diakses siswa!',
            'closed'    => 'Modul ditutup dan tidak bisa diakses siswa.',
            default     => 'Modul dikembalikan ke status Draft.',
        };

        return back()->with('success', $label);
    }

    /**
     * Menghapus modul beserta seluruh relasi data anak (cascade).
     */
    public function destroy(Module $module)
    {
        $this->authorizeModule($module);
        $module->delete();
        return redirect()->route('teacher.modules.index')->with('success', 'Modul berhasil dihapus.');
    }

    private function authorizeModule(Module $module)
    {
        abort_if($module->teacher_id !== $this->teacher()->id, 403, 'Anda tidak memiliki akses ke modul ini.');
    }
}
