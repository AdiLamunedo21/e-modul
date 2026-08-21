<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * =============================================================================
 * CONTROLLER: BagianAwalController
 * =============================================================================
 * 
 * PANDUAN PENGEMBANG (DEVELOPER ARCHITECTURE NOTES):
 * -----------------------------------------------------------------------------
 * Controller ini khusus mengelola "1. Bagian Awal" E-Modul yang terdiri dari 
 * tepat 4 komponen:
 * 
 * 1. Halaman Sampul (Cover Image)  => 'cover_image_path'
 * 2. Kata Pengantar               => 'kata_pengantar'
 * 3. Daftar Isi (Navigasi Modul)  => 'daftar_isi' (Array [{judul, anchor}])
 * 4. Petunjuk Penggunaan          => 'petunjuk_penggunaan'
 * 
 * Data disimpan secara terisolasi dan aman ke dalam kolom JSON `informasi_umum_data`
 * pada tabel `modules` tanpa mengganggu data Pendahuluan, Kegiatan Belajar, 
 * Evaluasi, maupun Bagian Akhir.
 * =============================================================================
 */
class BagianAwalController extends Controller
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
     * Menampilkan form editor khusus untuk 4 Komponen Bagian Awal.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $infoData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];

        $data = [
            'cover_image_path'    => $infoData['cover_image_path'] ?? null,
            'kata_pengantar'      => $infoData['kata_pengantar'] ?? '',
            'daftar_isi'          => $infoData['daftar_isi'] ?? [],
            'petunjuk_penggunaan' => $infoData['petunjuk_penggunaan'] ?? '',
            'toggles'             => $infoData['toggles'] ?? [],
        ];

        return view('pages.teacher.modules.bagian-awal.edit', compact('module', 'data'));
    }

    /**
     * Menyimpan pembaruan khusus 4 Komponen Bagian Awal ke JSON `informasi_umum_data`.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            'cover_image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_cover'        => ['nullable', 'boolean'],
            'kata_pengantar'      => ['nullable', 'string'],
            'petunjuk_penggunaan' => ['nullable', 'string'],
            'daftar_isi'          => ['nullable', 'array'],
            'daftar_isi.*.judul'  => ['required_with:daftar_isi', 'string', 'max:200'],
            'daftar_isi.*.anchor' => ['nullable', 'string', 'max:200'],
        ]);

        $existingData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];
        $coverPath = $existingData['cover_image_path'] ?? null;

        // 1. Upload Cover Baru jika ada
        if ($request->hasFile('cover_image')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')
                ->store("covers/teacher-{$this->teacher()->id}", 'public');
        }

        // 2. Hapus Cover jika opsi hapus dipilih
        if ($request->boolean('remove_cover') && $coverPath) {
            if (Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = null;
        }

        // 3. Normalisasi Daftar Isi (Filter array kosong)
        $daftarIsi = collect($request->daftar_isi ?? [])
            ->filter(fn($d) => !empty($d['judul']))
            ->map(fn($d) => [
                'judul'  => trim($d['judul']),
                'anchor' => !empty($d['anchor']) ? trim($d['anchor']) : '',
            ])
            ->values()
            ->toArray();

        // 4. Update data Bagian Awal ke dalam JSON tanpa menimpa bagian lain
        $updatedData = array_merge($existingData, [
            'cover_image_path'    => $coverPath,
            'kata_pengantar'      => $request->kata_pengantar ?? '',
            'daftar_isi'          => $daftarIsi,
            'petunjuk_penggunaan' => $request->petunjuk_penggunaan ?? '',
        ]);

        $module->update(['informasi_umum_data' => $updatedData]);

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', 'Bagian Awal E-Modul (4 Komponen) berhasil disimpan! ✅');
    }

    /**
     * Mengubah status sakelar aktif/nonaktif khusus 4 komponen Bagian Awal.
     */
    public function toggle(Request $request, Module $module, string $component)
    {
        $this->authorize($module);

        $allowed = [
            'cover'               => 'Halaman Sampul (Cover)',
            'kata_pengantar'      => 'Kata Pengantar',
            'daftar_isi'          => 'Daftar Isi',
            'petunjuk_penggunaan' => 'Petunjuk Penggunaan',
        ];

        if (!array_key_exists($component, $allowed)) {
            return back()->with('error', 'Komponen Bagian Awal tidak valid.');
        }

        $data = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];
        $toggles = $data['toggles'] ?? [];

        $current = $toggles[$component] ?? true;
        $toggles[$component] = !$current;

        $data['toggles'] = $toggles;
        $module->update(['informasi_umum_data' => $data]);

        $statusText = $toggles[$component] ? 'diaktifkan (ON)' : 'dinonaktifkan (OFF)';
        $componentLabel = $allowed[$component];

        return back()->with('success', "Komponen '{$componentLabel}' berhasil {$statusText}. ✅");
    }
}
