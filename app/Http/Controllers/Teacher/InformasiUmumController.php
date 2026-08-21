<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * =============================================================================
 * CONTROLLER: InformasiUmumController
 * =============================================================================
 * 
 * PANDUAN PENGEMBANG (DEVELOPER ARCHITECTURE NOTES):
 * -----------------------------------------------------------------------------
 * E-Modul pada aplikasi ini mengikuti standar pedagogis 5 Bagian Umum:
 * 
 * 1. Bagian Awal          : Cover, Kata Pengantar, Daftar Isi, Petunjuk Penggunaan
 * 2. Pendahuluan          : Tujuan Pembelajaran & Capaian, Peta Konsep, Glosarium
 * 3. Kegiatan Belajar     : Materi + PPT, Video YouTube, Job Sheet PDF (Fitur Interaktif)
 * 4. Evaluasi & Latihan   : Pre-test, Praktik Embed (Game/Kuis), Tugas LKPD (Fitur Interaktif)
 * 5. Bagian Akhir         : Post-test (Fitur Interaktif), Daftar Pustaka
 * 
 * Controller ini (InformasiUmumController) bertugas mengelola seluruh elemen 
 * konten statis pembuka & referensi yang disimpan secara fleksibel di kolom JSON 
 * `informasi_umum_data` pada tabel `modules`.
 * 
 * PEMETAAN KEY JSON `informasi_umum_data`:
 * -----------------------------------------------------------------------------
 * 1) ELEMEN BAGIAN AWAL:
 *    - 'cover_image_path'    => (string|null) Path gambar sampul di storage
 *    - 'kata_pengantar'      => (string) Teks prakata guru
 *    - 'daftar_isi'          => (array) List bab/subbab [{judul, anchor}]
 *    - 'petunjuk_penggunaan' => (string) Panduan belajar bagi siswa & guru
 * 
 * 2) ELEMEN PENDAHULUAN:
 *    - 'tujuan_pembelajaran' => (string) Rumusan capaian & tujuan pembelajaran
 *    - 'peta_konsep_text'    => (string) Deskripsi / struktur alur konsep materi
 *    - 'glosarium'           => (array) Daftar istilah penting [{istilah, definisi}]
 * 
 * 3) ELEMEN BAGIAN AKHIR:
 *    - 'daftar_pustaka'      => (array) Daftar referensi [{judul, penulis, tahun, tautan}]
 * 
 * 4) STATUS SAKELAR TOGGLE PER-ELEMEN:
 *    - 'toggles'             => (array) ['cover' => bool, 'kata_pengantar' => bool, ...]
 * 
 * CATATAN PENTING:
 * Komponen interaktif lainnya (Pre-test, Materi, Video, Embed, Job Sheet, LKPD, Post-test)
 * dikelola oleh masing-masing Controller terpisah (PreTestController, MateriController, dll).
 * =============================================================================
 */
class InformasiUmumController extends Controller
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
     * Menampilkan halaman formulir editor Informasi Umum (mencakup elemen Bagian Awal, Pendahuluan, dan Daftar Pustaka).
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        // Pastikan informasi_umum_data selalu array dengan default key lengkap
        $data = array_merge([
            // Bagian Awal
            'cover_image_path'    => null,
            'kata_pengantar'      => '',
            'daftar_isi'          => [],   // [{judul, anchor}]
            'petunjuk_penggunaan' => '',
            // Pendahuluan
            'tujuan_pembelajaran' => '',
            'peta_konsep_text'    => '',
            'glosarium'           => [],   // [{istilah, definisi}]
            // Bagian Akhir
            'daftar_pustaka'      => [],   // [{judul, penulis, tahun, tautan}]
            // Status Toggle Elemen
            'toggles'             => [],
        ], is_array($module->informasi_umum_data) ? $module->informasi_umum_data : []);

        return view('pages.teacher.modules.informasi-umum', compact('module', 'data'));
    }

    /**
     * Memproses dan menyimpan seluruh data formulir Informasi Umum ke kolom JSON `informasi_umum_data`.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            // Validasi Bagian Awal
            'kata_pengantar'      => ['required', 'string', 'min:20'],
            'petunjuk_penggunaan' => ['nullable', 'string'],
            'daftar_isi'          => ['nullable', 'array'],
            'daftar_isi.*.judul'  => ['required_with:daftar_isi', 'string', 'max:200'],
            'cover_image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],

            // Validasi Pendahuluan
            'tujuan_pembelajaran' => ['required', 'string', 'min:10'],
            'peta_konsep_text'    => ['nullable', 'string'],
            'glosarium'           => ['nullable', 'array'],
            'glosarium.*.istilah' => ['required_with:glosarium', 'string', 'max:100'],
            'glosarium.*.definisi'=> ['required_with:glosarium', 'string'],

            // Validasi Bagian Akhir (Daftar Pustaka)
            'daftar_pustaka'           => ['nullable', 'array'],
            'daftar_pustaka.*.judul'   => ['nullable', 'string', 'max:255'],
            'daftar_pustaka.*.penulis' => ['nullable', 'string', 'max:255'],
            'daftar_pustaka.*.tahun'   => ['nullable', 'string', 'max:50'],
            'daftar_pustaka.*.tautan'  => ['nullable', 'string', 'max:500'],
        ]);

        // --- 1. Manajemen Upload Gambar Cover (Bagian Awal) ---
        $existingData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];
        $coverPath = $existingData['cover_image_path'] ?? null;

        if ($request->hasFile('cover_image')) {
            // Hapus file cover fisik lama jika ada
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')
                ->store("covers/teacher-{$this->teacher()->id}", 'public');
        }

        // Opsi jika guru mencentang checkbox untuk menghapus cover
        if ($request->boolean('remove_cover') && $coverPath) {
            if (Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = null;
        }

        // --- 2. Normalisasi Data Array (Glosarium, Daftar Isi, Daftar Pustaka) ---
        $glosarium = collect($request->glosarium ?? [])
            ->filter(fn($g) => !empty($g['istilah']) && !empty($g['definisi']))
            ->values()
            ->toArray();

        $daftarIsi = collect($request->daftar_isi ?? [])
            ->filter(fn($d) => !empty($d['judul']))
            ->values()
            ->toArray();

        $daftarPustaka = collect($request->daftar_pustaka ?? [])
            ->filter(fn($p) => !empty($p['judul']) || !empty($p['penulis']) || !empty($p['tautan']))
            ->values()
            ->toArray();

        // --- 3. Satukan Struktur JSON Informasi Umum ---
        $informasiUmum = [
            // Elemen Bagian Awal
            'cover_image_path'    => $coverPath,
            'kata_pengantar'      => $request->kata_pengantar,
            'daftar_isi'          => $daftarIsi,
            'petunjuk_penggunaan' => $request->petunjuk_penggunaan,
            // Elemen Pendahuluan
            'tujuan_pembelajaran' => $request->tujuan_pembelajaran,
            'peta_konsep_text'    => $request->peta_konsep_text,
            'glosarium'           => $glosarium,
            // Elemen Bagian Akhir
            'daftar_pustaka'      => $daftarPustaka,
            // Pertahankan state toggle sakelar
            'toggles'             => $existingData['toggles'] ?? [],
        ];

        $module->update(['informasi_umum_data' => $informasiUmum]);

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', 'Informasi Umum E-Modul berhasil disimpan! ✅');
    }

    /**
     * Mengubah status sakelar (ON/OFF) dari salah satu elemen pembuka / rujukan pada Informasi Umum.
     * Komponen yang dapat di-toggle: cover, kata_pengantar, daftar_isi, petunjuk_penggunaan,
     * tujuan_pembelajaran, peta_konsep, glosarium, daftar_pustaka.
     */
    public function toggle(Request $request, Module $module, string $component)
    {
        $this->authorize($module);

        $labels = [
            // Bagian Awal
            'cover'               => 'Halaman Cover',
            'kata_pengantar'      => 'Kata Pengantar',
            'daftar_isi'          => 'Daftar Isi',
            'petunjuk_penggunaan' => 'Petunjuk Penggunaan',
            // Pendahuluan
            'tujuan_pembelajaran' => 'Tujuan Pembelajaran',
            'peta_konsep'         => 'Peta Konsep',
            'glosarium'           => 'Glosarium',
            // Bagian Akhir
            'daftar_pustaka'      => 'Daftar Pustaka',
        ];

        if (!array_key_exists($component, $labels)) {
            return back()->with('error', 'Komponen Informasi Umum tidak valid.');
        }

        $data = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];
        $toggles = $data['toggles'] ?? [];

        // State default bernilai true (aktif) jika belum pernah di-toggle
        $current = $toggles[$component] ?? true;
        $toggles[$component] = !$current;

        $data['toggles'] = $toggles;
        $module->update(['informasi_umum_data' => $data]);

        $statusText = $toggles[$component] ? 'diaktifkan (ON)' : 'dinonaktifkan (OFF)';
        $componentLabel = $labels[$component];

        return back()->with('success', "Komponen '{$componentLabel}' berhasil {$statusText}. ✅");
    }
}
