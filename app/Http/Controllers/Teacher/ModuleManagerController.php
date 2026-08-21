<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
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
     * Mendukung tab filter status: all, draft, published, closed.
     */
    public function index(Request $request)
    {
        $query = Module::with(['schoolClass'])
            ->where('teacher_id', $this->teacher()->id)
            ->latest();

        // Filter berdasarkan tab status jika ada
        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'closed'])) {
            $query->where('status', $request->status);
        }

        $modules = $query->paginate(10)->withQueryString();

        $counts = [
            'all'       => Module::where('teacher_id', $this->teacher()->id)->count(),
            'published' => Module::where('teacher_id', $this->teacher()->id)->where('status', 'published')->count(),
            'draft'     => Module::where('teacher_id', $this->teacher()->id)->where('status', 'draft')->count(),
            'closed'    => Module::where('teacher_id', $this->teacher()->id)->where('status', 'closed')->count(),
        ];

        return view('pages.teacher.modules.index', compact('modules', 'counts'));
    }

    /**
     * Form buat modul baru (langkah 1: input judul modul & target kelas).
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('grade')->orderBy('major_name')->get();
        return view('pages.teacher.modules.create', compact('classes'));
    }

    /**
     * Menyimpan modul baru ke database dengan status default 'draft',
     * lalu mengarahkan guru ke halaman Detail Modul 5 Bagian.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'exists:classes,id'],
        ]);

        $module = Module::create([
            'teacher_id' => $this->teacher()->id,
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
        $module->load('schoolClass');
        return view('pages.teacher.modules.show', compact('module'));
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
