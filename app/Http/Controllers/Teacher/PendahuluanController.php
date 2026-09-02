<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: PendahuluanController
 * =============================================================================
 * 
 * PANDUAN PENGEMBANG (DEVELOPER ARCHITECTURE NOTES):
 * -----------------------------------------------------------------------------
 * Controller ini khusus mengelola "2. Pendahuluan" E-Modul yang terdiri dari 
 * tepat 3 komponen capaian dan kerangka konsep:
 * 
 * 1. Tujuan Pembelajaran & Capaian => 'tujuan_pembelajaran' (Teks Capaian / Bloom's Taxonomy)
 * 2. Peta Konsep                   => 'peta_konsep_text' (Alur / Hierarki Konsep Materi)
 * 3. Glosarium                     => 'glosarium' (Array [{istilah, definisi}])
 * 
 * Data disimpan secara terisolasi dan aman ke dalam kolom JSON `informasi_umum_data`
 * pada tabel `modules` tanpa mengganggu data Bagian Awal, Kegiatan Belajar, 
 * Evaluasi, maupun Bagian Akhir.
 * =============================================================================
 */
class PendahuluanController extends Controller
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
     * Menampilkan form editor khusus untuk 3 Komponen Pendahuluan.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $infoData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];

        // Normalisasi tujuan_pembelajaran
        $tujuan = '';
        if (isset($infoData['tujuan_pembelajaran'])) {
            if (is_array($infoData['tujuan_pembelajaran'])) {
                $parts = [];
                if (!empty($infoData['tujuan_pembelajaran']['capaian_pembelajaran'])) {
                    $parts[] = "Capaian Pembelajaran (CP):\n" . $infoData['tujuan_pembelajaran']['capaian_pembelajaran'];
                }
                if (!empty($infoData['tujuan_pembelajaran']['tujuan_pembelajaran'])) {
                    $tps = array_map(fn($t) => is_array($t) ? ($t['text'] ?? '') : $t, (array)$infoData['tujuan_pembelajaran']['tujuan_pembelajaran']);
                    $parts[] = "Tujuan Pembelajaran (TP):\n" . implode("\n", $tps);
                }
                $tujuan = !empty($parts) ? implode("\n\n", $parts) : ($infoData['tujuan_pembelajaran']['text'] ?? '');
            } else {
                $tujuan = (string) $infoData['tujuan_pembelajaran'];
            }
        }

        // Normalisasi peta_konsep_text
        $petaKonsep = '';
        if (isset($infoData['peta_konsep'])) {
            if (is_array($infoData['peta_konsep'])) {
                $petaKonsep = $infoData['peta_konsep']['peta_konsep_text'] ?? ($infoData['peta_konsep']['text'] ?? '');
            } else {
                $petaKonsep = (string) $infoData['peta_konsep'];
            }
        } elseif (isset($infoData['peta_konsep_text'])) {
            $petaKonsep = is_string($infoData['peta_konsep_text']) ? $infoData['peta_konsep_text'] : '';
        }

        // Normalisasi glosarium
        $glosarium = [];
        if (isset($infoData['glosarium'])) {
            if (is_array($infoData['glosarium'])) {
                $glosarium = isset($infoData['glosarium']['glosarium']) && is_array($infoData['glosarium']['glosarium'])
                    ? $infoData['glosarium']['glosarium']
                    : $infoData['glosarium'];
            }
        }

        $data = [
            'tujuan_pembelajaran' => $tujuan,
            'peta_konsep_text'    => $petaKonsep,
            'glosarium'           => $glosarium,
            'toggles'             => $infoData['toggles'] ?? [],
        ];

        return view('pages.teacher.modules.pendahuluan.edit', compact('module', 'data'));
    }

    /**
     * Menyimpan pembaruan khusus 3 Komponen Pendahuluan ke JSON `informasi_umum_data`.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            'tujuan_pembelajaran'  => ['required', 'string', 'min:10'],
            'peta_konsep_text'     => ['nullable', 'string'],
            'glosarium'            => ['nullable', 'array'],
            'glosarium.*.istilah'  => ['required_with:glosarium', 'string', 'max:100'],
            'glosarium.*.definisi' => ['required_with:glosarium', 'string'],
        ], [
            'tujuan_pembelajaran.required' => 'Tujuan Pembelajaran wajib diisi.',
            'tujuan_pembelajaran.min'      => 'Tujuan Pembelajaran minimal berisi 10 karakter.',
            'glosarium.*.istilah.required_with' => 'Istilah pada glosarium wajib diisi.',
            'glosarium.*.definisi.required_with' => 'Definisi istilah pada glosarium wajib diisi.',
        ]);

        $existingData = is_array($module->informasi_umum_data) ? $module->informasi_umum_data : [];

        // Normalisasi Glosarium (Filter entri kosong)
        $glosarium = collect($request->glosarium ?? [])
            ->filter(fn($g) => !empty($g['istilah']) && !empty($g['definisi']))
            ->map(fn($g) => [
                'istilah'  => trim($g['istilah']),
                'definisi' => trim($g['definisi']),
            ])
            ->values()
            ->toArray();

        // Update data Pendahuluan ke dalam JSON tanpa menimpa bagian lain
        $updatedData = array_merge($existingData, [
            'tujuan_pembelajaran' => $request->tujuan_pembelajaran ?? '',
            'peta_konsep_text'    => $request->peta_konsep_text ?? '',
            'peta_konsep'         => [
                'peta_konsep_text'       => $request->peta_konsep_text ?? '',
                'peta_konsep_image_path' => $existingData['peta_konsep']['peta_konsep_image_path'] ?? ($existingData['peta_konsep_image_path'] ?? null),
            ],
            'glosarium'           => $glosarium,
        ]);

        $module->update(['informasi_umum_data' => $updatedData]);

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', 'Pendahuluan E-Modul (3 Komponen) berhasil disimpan! ✅');
    }

    /**
     * Mengubah status sakelar aktif/nonaktif khusus 3 komponen Pendahuluan.
     */
    public function toggle(Request $request, Module $module, string $component)
    {
        $this->authorize($module);

        $allowed = [
            'tujuan_pembelajaran' => 'Tujuan Pembelajaran & Capaian',
            'peta_konsep'         => 'Peta Konsep',
            'glosarium'           => 'Glosarium',
        ];

        if (!array_key_exists($component, $allowed)) {
            return back()->with('error', 'Komponen Pendahuluan tidak valid.');
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
