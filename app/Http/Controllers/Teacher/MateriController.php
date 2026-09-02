<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * =============================================================================
 * CONTROLLER: MateriController
 * =============================================================================
 * KLASIFIKASI E-MODUL: Bagian 3 — Kegiatan Belajar (Uraian Materi & PPT)
 * -----------------------------------------------------------------------------
 * Controller ini mengelola teks isi uraian materi pembelajaran, upload file PPT/PDF,
 * dan penyisipan gambar yang dikontrol oleh flag `has_materi`.
 * =============================================================================
 */
class MateriController extends Controller
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
     * Halaman Editor Materi & PPT.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $materiData = is_array($module->materi_data) ? $module->materi_data : [];

        $data = array_merge([
            'judul_materi'     => 'Kegiatan Belajar: ' . $module->title,
            'uraian_materi'    => '',
            'ringkasan_materi' => '',
            'ppt_file_path'    => null,
            'ppt_file_name'    => null,
            'ppt_file_size'    => null,
            'poin_penting'     => [],
        ], $materiData);

        return view('pages.teacher.modules.materi', compact('module', 'data'));
    }

    /**
     * Halaman Pratinjau Materi — tampilan mandiri tanpa dashboard.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $materiData = is_array($module->materi_data) ? $module->materi_data : [];

        $data = array_merge([
            'judul_materi'     => 'Kegiatan Belajar: ' . $module->title,
            'uraian_materi'    => '',
            'ringkasan_materi' => '',
            'ppt_file_path'    => null,
            'ppt_file_name'    => null,
            'ppt_file_size'    => null,
            'poin_penting'     => [],
        ], $materiData);

        return view('pages.teacher.modules.preview-materi', compact('module', 'data'));
    }

    /**
     * Simpan Uraian Materi & Unggah Berkas Slide PPT/PDF.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasMateri = $request->boolean('has_materi');

        $rules = [
            'judul_materi'     => [$hasMateri ? 'required' : 'nullable', 'string', 'max:255'],
            'uraian_materi'    => [$hasMateri ? 'required' : 'nullable', 'string', $hasMateri ? 'min:20' : 'nullable'],
            'ringkasan_materi' => ['nullable', 'string'],
            'poin_penting'     => ['nullable', 'array'],
            'poin_penting.*'   => ['nullable', 'string', 'max:255'],
            'ppt_file'         => ['nullable', 'file', 'mimes:pdf,ppt,pptx', 'max:15360'], // Maks 15MB
        ];

        $request->validate($rules, [
            'judul_materi.required'  => 'Judul materi wajib diisi jika fitur Materi diaktifkan.',
            'uraian_materi.required' => 'Uraian materi wajib diisi minimal 20 karakter jika fitur Materi diaktifkan.',
            'ppt_file.mimes'         => 'Berkas presentasi harus berformat PDF, PPT, atau PPTX.',
            'ppt_file.max'           => 'Ukuran berkas presentasi tidak boleh lebih dari 15 MB.',
        ]);

        $existingData = is_array($module->materi_data) ? $module->materi_data : [];
        $pptPath = $existingData['ppt_file_path'] ?? null;
        $pptName = $existingData['ppt_file_name'] ?? null;
        $pptSize = $existingData['ppt_file_size'] ?? null;

        // Upload berkas baru jika ada
        if ($request->hasFile('ppt_file')) {
            // Hapus berkas lama jika ada
            if ($pptPath && Storage::disk('public')->exists($pptPath)) {
                Storage::disk('public')->delete($pptPath);
            }

            $file = $request->file('ppt_file');
            $pptName = $file->getClientOriginalName();
            $pptSize = $file->getSize();
            $pptPath = $file->store("materi-slides/teacher-{$this->teacher()->id}", 'public');
        }

        // Hapus berkas jika dicentang hapus
        if ($request->boolean('remove_ppt_file') && $pptPath) {
            if (Storage::disk('public')->exists($pptPath)) {
                Storage::disk('public')->delete($pptPath);
            }
            $pptPath = null;
            $pptName = null;
            $pptSize = null;
        }

        // Filter poin penting
        $poinPenting = collect($request->input('poin_penting', []))
            ->map(fn($p) => trim($p))
            ->filter(fn($p) => !empty($p))
            ->values()
            ->toArray();

        $payload = [
            'judul_materi'     => $request->input('judul_materi', 'Materi Pembelajaran'),
            'uraian_materi'    => $request->input('uraian_materi', ''),
            'ringkasan_materi' => $request->input('ringkasan_materi', ''),
            'poin_penting'     => $poinPenting,
            'ppt_file_path'    => $pptPath,
            'ppt_file_name'    => $pptName,
            'ppt_file_size'    => $pptSize,
        ];

        $module->update([
            'has_materi'  => $hasMateri,
            'materi_data' => $payload,
        ]);

        $statusText = $hasMateri ? 'diaktifkan & disimpan' : 'disimpan (status Non-Aktif)';

        if ($request->expectsJson() || $request->ajax()) {
            $formattedSize = null;
            if ($pptSize) {
                $formattedSize = number_format($pptSize / (1024 * 1024), 2) . ' MB';
            }

            return response()->json([
                'success' => true,
                'message' => "Materi & Berkas Presentasi berhasil {$statusText}! ✅",
                'data'    => [
                    'has_materi'              => $hasMateri,
                    'judul_materi'            => $payload['judul_materi'],
                    'uraian_materi'           => $payload['uraian_materi'],
                    'ringkasan_materi'        => $payload['ringkasan_materi'],
                    'poin_penting'            => $payload['poin_penting'],
                    'ppt_file_path'           => $pptPath,
                    'ppt_file_name'           => $pptName,
                    'ppt_file_size'           => $pptSize,
                    'ppt_file_size_formatted' => $formattedSize,
                    'ppt_file_is_pdf'         => $pptName ? str_ends_with(strtolower($pptName), '.pdf') : false,
                    'ppt_download_url'        => $pptPath ? route('teacher.modules.materi.download-ppt', $module) : null,
                ],
            ]);
        }

        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', "Materi & Berkas Presentasi berhasil {$statusText}! ✅");
    }

    /**
     * Download berkas PPT/PDF yang terlampir.
     */
    public function downloadPpt(Module $module)
    {
        $this->authorize($module);

        $materiData = is_array($module->materi_data) ? $module->materi_data : [];
        $pptPath = $materiData['ppt_file_path'] ?? null;
        $pptName = $materiData['ppt_file_name'] ?? 'Materi_Presentasi.pdf';

        if (!$pptPath || !Storage::disk('public')->exists($pptPath)) {
            return back()->with('error', 'Berkas presentasi tidak ditemukan atau belum diunggah.');
        }

        return Storage::disk('public')->download($pptPath, $pptName);
    }

    /**
     * Toggle cepat status aktif/nonaktif materi.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $module->update([
            'has_materi' => !$module->has_materi,
        ]);

        $status = $module->has_materi ? 'diaktifkan' : 'dinonaktifkan';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'has_materi' => $module->has_materi,
                'message'    => "Komponen Materi & PPT berhasil {$status}! ✅",
            ]);
        }

        return back()->with('success', "Komponen Materi & PPT berhasil {$status}! ✅");
    }

    /**
     * Upload gambar dari editor uraian materi (rich text / notepad).
     */
    public function uploadImage(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'], // Maks 5MB
        ]);

        $file = $request->file('image');
        $path = $file->store("materi-content-images/teacher-{$this->teacher()->id}", 'public');

        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
        ]);
    }
}
