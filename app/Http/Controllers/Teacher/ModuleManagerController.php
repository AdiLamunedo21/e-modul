<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleManagerController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    /**
     * Halaman Manajer Modul — daftar semua modul milik guru ini.
     */
    public function index(Request $request)
    {
        $query = Module::with(['schoolClass'])
            ->where('teacher_id', $this->teacher()->id)
            ->latest();

        // Filter by status tab
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
     * Form buat modul baru (langkah 1: isi judul & target kelas).
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('grade')->orderBy('major_name')->get();
        return view('pages.teacher.modules.create', compact('classes'));
    }

    /**
     * Simpan modul baru sebagai draft.
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
            ->with('success', 'Modul baru berhasil dibuat! Lanjutkan dengan mengisi Bagian Awal.');
    }

    /**
     * Halaman detail modul (ringkasan progress & navigasi ke bagian-bagian).
     */
    public function show(Module $module)
    {
        $this->authorizeModule($module);
        $module->load('schoolClass');
        return view('pages.teacher.modules.show', compact('module'));
    }

    /**
     * Ubah status publish / draft / closed.
     */
    public function updateStatus(Request $request, Module $module)
    {
        $this->authorizeModule($module);

        $validated = $request->validate([
            'status' => ['required', 'in:draft,published,closed'],
        ]);

        $module->update(['status' => $validated['status']]);

        $label = match($validated['status']) {
            'published' => 'Modul berhasil dipublikasikan!',
            'closed'    => 'Modul ditutup dan tidak bisa diakses siswa.',
            default     => 'Modul dikembalikan ke Draft.',
        };

        return back()->with('success', $label);
    }

    /**
     * Hapus modul beserta semua relasinya.
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
