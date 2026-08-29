<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: BagianAwalController
 * =============================================================================
 * 
 * PANDUAN PENGEMBANG (DEVELOPER ARCHITECTURE NOTES):
 * -----------------------------------------------------------------------------
 * Controller ini khusus mengelola "1. Bagian Awal" E-Modul yang terdiri dari 
 * tepat 2 komponen:
 * 
 * 1. Kata Pengantar      => 'kata_pengantar'
 * 2. Petunjuk Penggunaan => 'petunjuk_penggunaan'
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
     * Menampilkan form editor khusus untuk 2 Komponen Bagian Awal.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $infoData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];

        // Normalisasi kata_pengantar (mendukung string langsung maupun array berstruktur)
        $kataPengantar = '';
        if (isset($infoData['kata_pengantar'])) {
            if (is_array($infoData['kata_pengantar'])) {
                $kataPengantar = $infoData['kata_pengantar']['kata_pengantar_text'] ?? ($infoData['kata_pengantar']['text'] ?? '');
            } else {
                $kataPengantar = (string) $infoData['kata_pengantar'];
            }
        }

        // Normalisasi petunjuk_penggunaan
        $petunjuk = '';
        if (isset($infoData['petunjuk_penggunaan'])) {
            if (is_array($infoData['petunjuk_penggunaan'])) {
                $parts = [];
                if (!empty($infoData['petunjuk_penggunaan']['petunjuk_siswa'])) {
                    $parts[] = "Petunjuk Bagi Siswa:\n" . implode("\n", array_map(fn($item) => is_array($item) ? ($item['text'] ?? '') : $item, (array)$infoData['petunjuk_penggunaan']['petunjuk_siswa']));
                }
                if (!empty($infoData['petunjuk_penggunaan']['petunjuk_guru'])) {
                    $parts[] = "Petunjuk Bagi Guru:\n" . implode("\n", array_map(fn($item) => is_array($item) ? ($item['text'] ?? '') : $item, (array)$infoData['petunjuk_penggunaan']['petunjuk_guru']));
                }
                $petunjuk = !empty($parts) ? implode("\n\n", $parts) : ($infoData['petunjuk_penggunaan']['text'] ?? '');
            } else {
                $petunjuk = (string) $infoData['petunjuk_penggunaan'];
            }
        }

        $data = [
            'kata_pengantar'      => $kataPengantar,
            'petunjuk_penggunaan' => $petunjuk,
            'toggles'             => $infoData['toggles'] ?? [],
        ];

        return view('pages.teacher.modules.bagian-awal.edit', compact('module', 'data'));
    }

    /**
     * Menyimpan pembaruan khusus 2 Komponen Bagian Awal ke JSON `informasi_umum_data`.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            'kata_pengantar'      => ['nullable', 'string'],
            'petunjuk_penggunaan' => ['nullable', 'string'],
        ]);

        $existingData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];

        // Update data Bagian Awal ke dalam JSON tanpa menimpa bagian lain
        $updatedData = array_merge($existingData, [
            'kata_pengantar'      => $request->kata_pengantar ?? '',
            'petunjuk_penggunaan' => $request->petunjuk_penggunaan ?? '',
        ]);

        $module->update(['informasi_umum_data' => $updatedData]);

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', 'Bagian Awal E-Modul (2 Komponen) berhasil disimpan! ✅');
    }

    /**
     * Mengubah status sakelar aktif/nonaktif khusus 2 komponen Bagian Awal.
     */
    public function toggle(Request $request, Module $module, string $component)
    {
        $this->authorize($module);

        $allowed = [
            'kata_pengantar'      => 'Kata Pengantar',
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
