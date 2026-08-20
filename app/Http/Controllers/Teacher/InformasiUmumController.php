<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InformasiUmumController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    private function authorize(Module $module): void
    {
        abort_if($module->teacher_id !== $this->teacher()->id, 403);
    }

    /**
     * Halaman edit Informasi Umum.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        // Pastikan bagian_awal_data selalu array dengan keys lengkap
        $data = array_merge([
            'kata_pengantar'     => '',
            'daftar_isi'         => [],   // [{judul, anchor}]
            'peta_konsep_text'   => '',
            'glosarium'          => [],   // [{istilah, definisi}]
            'petunjuk_penggunaan' => '',
            'tujuan_pembelajaran' => '',
            'cover_image_path'   => null,
        ], is_array($module->bagian_awal_data) ? $module->bagian_awal_data : []);

        return view('pages.teacher.modules.informasi-umum', compact('module', 'data'));
    }

    /**
     * Proses simpan Informasi Umum.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            'kata_pengantar'      => ['required', 'string', 'min:20'],
            'peta_konsep_text'    => ['nullable', 'string'],
            'petunjuk_penggunaan' => ['nullable', 'string'],
            'tujuan_pembelajaran' => ['required', 'string', 'min:10'],

            // Glosarium array
            'glosarium'           => ['nullable', 'array'],
            'glosarium.*.istilah' => ['required_with:glosarium', 'string', 'max:100'],
            'glosarium.*.definisi'=> ['required_with:glosarium', 'string'],

            // Daftar Isi array
            'daftar_isi'          => ['nullable', 'array'],
            'daftar_isi.*.judul'  => ['required_with:daftar_isi', 'string', 'max:200'],

            // Cover
            'cover_image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        // --- Cover Image upload ---
        $existingData = is_array($module->bagian_awal_data) ? $module->bagian_awal_data : [];
        $coverPath = $existingData['cover_image_path'] ?? null;

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama jika ada
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')
                ->store("covers/teacher-{$this->teacher()->id}", 'public');
        }

        // Jika user ceklis hapus cover
        if ($request->boolean('remove_cover') && $coverPath) {
            if (Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = null;
        }

        // --- Bangun payload JSON ---
        $glosarium  = collect($request->glosarium ?? [])
            ->filter(fn($g) => !empty($g['istilah']) && !empty($g['definisi']))
            ->values()
            ->toArray();

        $daftarIsi  = collect($request->daftar_isi ?? [])
            ->filter(fn($d) => !empty($d['judul']))
            ->values()
            ->toArray();

        $bagianAwal = [
            'cover_image_path'    => $coverPath,
            'kata_pengantar'      => $request->kata_pengantar,
            'daftar_isi'          => $daftarIsi,
            'peta_konsep_text'    => $request->peta_konsep_text,
            'glosarium'           => $glosarium,
            'petunjuk_penggunaan' => $request->petunjuk_penggunaan,
            'tujuan_pembelajaran' => $request->tujuan_pembelajaran,
        ];

        $module->update(['bagian_awal_data' => $bagianAwal]);

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', 'Informasi Umum berhasil disimpan! ✅');
    }
}
