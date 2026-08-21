<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: DaftarPustakaController
 * =============================================================================
 * 
 * PANDUAN PENGEMBANG (DEVELOPER ARCHITECTURE NOTES):
 * -----------------------------------------------------------------------------
 * Controller ini khusus mengelola komponen "Daftar Pustaka" pada Bagian Akhir E-Modul.
 * 
 * Data referensi disimpan dalam format array [{judul, penulis, tahun, tautan}] 
 * pada kolom JSON `informasi_umum_data['daftar_pustaka']` pada tabel `modules`.
 * 
 * Controller ini memastikan pembaruan data referensi dilakukan secara aman 
 * tanpa menimpa data pada Bagian Awal, Pendahuluan, Kegiatan Belajar, maupun Evaluasi.
 * =============================================================================
 */
class DaftarPustakaController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    private function authorize(Module $module): void
    {
        abort_if($module->teacher_id !== $this->teacher()->id, 403, 'Anda tidak memiliki akses ke modul ini.');
    }

    /**
     * Menampilkan form editor khusus untuk Daftar Pustaka.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $infoData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];

        $data = [
            'daftar_pustaka' => $infoData['daftar_pustaka'] ?? [],
            'toggles'        => $infoData['toggles'] ?? [],
        ];

        return view('pages.teacher.modules.daftar-pustaka.edit', compact('module', 'data'));
    }

    /**
     * Menyimpan pembaruan Daftar Pustaka ke JSON `informasi_umum_data`.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            'daftar_pustaka'           => ['nullable', 'array'],
            'daftar_pustaka.*.judul'   => ['nullable', 'string', 'max:255'],
            'daftar_pustaka.*.penulis' => ['nullable', 'string', 'max:255'],
            'daftar_pustaka.*.tahun'   => ['nullable', 'string', 'max:50'],
            'daftar_pustaka.*.tautan'  => ['nullable', 'string', 'max:500'],
        ]);

        $existingData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];

        // Normalisasi Daftar Pustaka (Filter entri kosong)
        $daftarPustaka = collect($request->daftar_pustaka ?? [])
            ->filter(fn($p) => !empty($p['judul']) || !empty($p['penulis']))
            ->map(fn($p) => [
                'judul'   => trim($p['judul'] ?? ''),
                'penulis' => trim($p['penulis'] ?? ''),
                'tahun'   => trim($p['tahun'] ?? ''),
                'tautan'  => trim($p['tautan'] ?? ''),
            ])
            ->values()
            ->toArray();

        // Update data Daftar Pustaka ke dalam JSON tanpa menimpa bagian lain
        $updatedData = array_merge($existingData, [
            'daftar_pustaka' => $daftarPustaka,
        ]);

        $module->update(['informasi_umum_data' => $updatedData]);

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', 'Daftar Pustaka E-Modul berhasil disimpan! ✅');
    }

    /**
     * Mengubah status sakelar aktif/nonaktif khusus Daftar Pustaka.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $data = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];
        $toggles = $data['toggles'] ?? [];

        $current = $toggles['daftar_pustaka'] ?? true;
        $toggles['daftar_pustaka'] = !$current;

        $data['toggles'] = $toggles;
        $module->update(['informasi_umum_data' => $data]);

        $statusText = $toggles['daftar_pustaka'] ? 'diaktifkan (ON)' : 'dinonaktifkan (OFF)';

        return back()->with('success', "Komponen 'Daftar Pustaka' berhasil {$statusText}. ✅");
    }
}
