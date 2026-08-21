<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: EmbedController
 * =============================================================================
 * KLASIFIKASI E-MODUL: Bagian 4 — Evaluasi & Latihan (Game Edukasi & Praktik Embed)
 * -----------------------------------------------------------------------------
 * Controller ini mengelola kode embed simulator, kuis online (Wordwall, Quizizz, Geogebra),
 * dan pengunggahan bukti screenshot praktik siswa yang dikontrol oleh flag `has_embed`.
 * =============================================================================
 */
class EmbedController extends Controller
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
     * Halaman Editor Komponen Praktik Interaktif (Embed Code / Simulator).
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $embedData = is_array($module->embed_data) ? $module->embed_data : [];

        $defaultChecklist = [
            'Buka dan jalankan simulasi/media interaktif di bawah ini secara mandiri.',
            'Lakukan pengujian skenario sesuai instruksi kerja yang telah ditentukan.',
            'Pastikan hasil akhir / output simulasi telah berjalan dengan benar dan sukses.',
            'Ambil tangkapan layar (screenshot) layar simulasi Anda secara jelas dan utuh.',
            'Unggah file screenshot (JPG/PNG maks 2 MB) sebagai bukti penyelesaian praktik.',
        ];

        $data = array_merge([
            'embed_title'           => 'Praktik Interaktif: ' . $module->title,
            'embed_type'            => 'code', // 'code' (HTML/Iframe/JS) atau 'url' (Web Simulator URL)
            'embed_code'            => '<div style="text-align:center; padding: 40px 20px; font-family: sans-serif; background: #0f172a; color: #f8fafc; border-radius: 16px;">
  <h2 style="color: #38bdf8; margin-bottom: 8px;">🎮 Simulator & Editor Interaktif Siap Dijalankan</h2>
  <p style="color: #94a3b8; font-size: 14px; max-width: 500px; margin: 0 auto 20px;">Silakan tulis kode HTML/CSS/JS Anda atau sematkan iframe simulator (seperti CodePen, PhET, W3Schools, Tinkercad, dll).</p>
  <button onclick="alert(\'Simulasi Berhasil Dijalankan! 👍\')" style="background: #2563eb; color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: bold; cursor: pointer;">Uji Coba Interaksi</button>
</div>',
            'embed_url'             => '',
            'estimated_duration'    => 20,
            'instructions'          => "Jalankan simulasi interaktif pada jendela simulator di bawah ini. Ikuti setiap petunjuk instruksi kerja, amati alur proses dan output yang dihasilkan. Setelah simulasi berhasil, lakukan tangkapan layar (screenshot) sebagai bukti pengerjaan Anda.",
            'checklist_items'       => $defaultChecklist,
            'screenshot_guide'      => 'Unggah tangkapan layar (screenshot) bukti hasil eksekusi simulasi Anda. Pastikan nama/tampilan output terlihat jelas.',
            'max_file_size_mb'      => 2,
        ], $embedData);

        return view('pages.teacher.modules.embed', compact('module', 'data'));
    }

    /**
     * Halaman Pratinjau Praktik Interaktif — simulasi antarmuka belajar siswa.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $embedData = is_array($module->embed_data) ? $module->embed_data : [];

        $data = array_merge([
            'embed_title'           => 'Praktik Interaktif: ' . $module->title,
            'embed_type'            => 'code',
            'embed_code'            => '<div style="text-align:center; padding: 40px 20px; font-family: sans-serif; background: #0f172a; color: #f8fafc; border-radius: 16px;">
  <h2 style="color: #38bdf8; margin-bottom: 8px;">🎮 Simulator Interaktif</h2>
  <p style="color: #94a3b8; font-size: 14px; margin-bottom: 16px;">Silakan operasikan simulasi ini dan ambil tangkapan layar sebagai bukti pengerjaan.</p>
</div>',
            'embed_url'             => '',
            'estimated_duration'    => 20,
            'instructions'          => "Jalankan simulasi interaktif pada jendela simulator di bawah ini. Ikuti setiap petunjuk instruksi kerja, amati alur proses dan output yang dihasilkan.",
            'checklist_items'       => [],
            'screenshot_guide'      => 'Unggah tangkapan layar (screenshot) bukti hasil eksekusi simulasi Anda.',
            'max_file_size_mb'      => 2,
        ], $embedData);

        return view('pages.teacher.modules.preview-embed', compact('module', 'data'));
    }

    /**
     * Simpan Pengaturan Praktik Interaktif (Embed Media).
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasEmbed = $request->boolean('has_embed');
        $embedType = $request->input('embed_type', 'code');

        $rules = [
            'embed_title'          => [$hasEmbed ? 'required' : 'nullable', 'string', 'max:255'],
            'embed_type'           => ['required', 'in:code,url'],
            'embed_code'           => [$hasEmbed && $embedType === 'code' ? 'required' : 'nullable', 'string'],
            'embed_url'            => [$hasEmbed && $embedType === 'url' ? 'required' : 'nullable', 'url', 'max:500'],
            'estimated_duration'   => ['nullable', 'integer', 'min:1', 'max:300'],
            'instructions'         => ['nullable', 'string'],
            'checklist_items'      => ['nullable', 'array'],
            'checklist_items.*'    => ['nullable', 'string', 'max:255'],
            'screenshot_guide'     => ['nullable', 'string', 'max:500'],
        ];

        $request->validate($rules, [
            'embed_title.required' => 'Judul kegiatan praktik interaktif wajib diisi jika komponen diaktifkan.',
            'embed_code.required'  => 'Kode embed / iframe / HTML simulasi wajib diisi jika mode Kode dipilih.',
            'embed_url.required'   => 'URL / Tautan simulator web wajib diisi jika mode Tautan URL dipilih.',
            'embed_url.url'        => 'Format URL simulator tidak valid (harus diawali http:// atau https://).',
        ]);

        // Filter daftar checklist target indikator
        $checklistItems = collect($request->input('checklist_items', []))
            ->map(fn($item) => trim($item))
            ->filter(fn($item) => !empty($item))
            ->values()
            ->toArray();

        $payload = [
            'embed_title'          => $request->input('embed_title', 'Praktik Interaktif: ' . $module->title),
            'embed_type'           => $embedType,
            'embed_code'           => $request->input('embed_code', ''),
            'embed_url'            => $request->input('embed_url', ''),
            'estimated_duration'   => (int) $request->input('estimated_duration', 20),
            'instructions'         => $request->input('instructions', ''),
            'checklist_items'      => $checklistItems,
            'screenshot_guide'     => $request->input('screenshot_guide', 'Unggah tangkapan layar (screenshot) bukti hasil eksekusi simulasi Anda (JPG/PNG maks 2 MB).'),
            'max_file_size_mb'     => 2,
        ];

        $module->update([
            'has_embed'  => $hasEmbed,
            'embed_data' => $payload,
        ]);

        $statusText = $hasEmbed ? 'diaktifkan & disimpan' : 'disimpan (status Non-Aktif)';
        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', "Komponen Praktik Interaktif (Embed) berhasil {$statusText}! ✅");
    }

    /**
     * Toggle cepat status aktif/nonaktif Praktik Interaktif.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $module->update([
            'has_embed' => !$module->has_embed,
        ]);

        $status = $module->has_embed ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Komponen Praktik Interaktif (Embed) berhasil {$status}! ✅");
    }
}
