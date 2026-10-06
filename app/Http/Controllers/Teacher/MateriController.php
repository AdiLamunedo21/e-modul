<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Services\PptConverterService;
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
        abort_if((int) $module->teacher_id !== (int) $this->teacher()?->id, 403, 'Anda tidak memiliki akses ke modul ini.');
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
        $pdfPreviewPath = $existingData['pdf_preview_path'] ?? null;

        // Upload berkas baru jika ada
        if ($request->hasFile('ppt_file')) {
            // Hapus berkas lama jika ada
            if ($pptPath && Storage::disk('public')->exists($pptPath)) {
                PptConverterService::deletePreviews($pptPath);
                Storage::disk('public')->delete($pptPath);
            }

            $file = $request->file('ppt_file');
            $pptName = $file->getClientOriginalName();
            $pptSize = $file->getSize();
            $pptPath = $file->store("materi-slides/teacher-{$this->teacher()->id}", 'public');

            // Konversi otomatis PPT/PPTX ke PDF pratinjau visual berkualitas tinggi
            $pdfPreviewPath = PptConverterService::convertToPdf($pptPath);
        }

        // Hapus berkas jika dicentang hapus
        if ($request->boolean('remove_ppt_file') && $pptPath) {
            PptConverterService::deletePreviews($pptPath);
            if (Storage::disk('public')->exists($pptPath)) {
                Storage::disk('public')->delete($pptPath);
            }
            $pptPath = null;
            $pptName = null;
            $pptSize = null;
            $pdfPreviewPath = null;
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
            'pdf_preview_path' => $pdfPreviewPath,
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
     * Stream berkas PPT/PDF secara inline untuk pratinjau browser guru.
     */
    public function streamPpt(Module $module)
    {
        $this->authorize($module);

        $materiData = is_array($module->materi_data) ? $module->materi_data : [];
        $pptPath = $materiData['ppt_file_path'] ?? null;
        $pptName = $materiData['ppt_file_name'] ?? 'Materi_Presentasi.pdf';

        if (!$pptPath || !Storage::disk('public')->exists($pptPath)) {
            abort(404, 'Berkas presentasi tidak ditemukan atau belum diunggah.');
        }

        // Cek pratinjau PDF visual (hasil konversi otomatis atau dokumen asli PDF)
        $previewPath = PptConverterService::getPdfPreviewPath($pptPath, $materiData['pdf_preview_path'] ?? null);
        if ($previewPath && Storage::disk('public')->exists($previewPath)) {
            $pdfFullPath = Storage::disk('public')->path($previewPath);
            return response()->file($pdfFullPath, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . pathinfo($pptName, PATHINFO_FILENAME) . '.pdf"',
                'X-Frame-Options'     => 'SAMEORIGIN',
                'Cache-Control'       => 'no-cache, no-store, must-revalidate',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ]);
        }

        $fullPath = Storage::disk('public')->path($pptPath);
        $mime = 'application/octet-stream';
        if (str_ends_with(strtolower($pptName), '.pdf')) {
            $mime = 'application/pdf';
        } elseif (str_ends_with(strtolower($pptName), '.pptx')) {
            $mime = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        } elseif (str_ends_with(strtolower($pptName), '.ppt')) {
            $mime = 'application/vnd.ms-powerpoint';
        }

        return response()->file($fullPath, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . $pptName . '"',
            'X-Frame-Options'     => 'SAMEORIGIN',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
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
     * Upload gambar dari editor uraian materi (rich text / notepad / clipboard paste).
     */
    public function uploadImage(Request $request, Module $module)
    {
        $this->authorize($module);

        $request->validate([
            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg,bmp', 'max:15360'], // Maks 15MB
        ], [
            'image.required' => 'Berkas gambar belum dipilih.',
            'image.image'    => 'Berkas yang diunggah harus berupa gambar yang valid.',
            'image.mimes'    => 'Format gambar harus JPG, JPEG, PNG, WEBP, GIF, atau SVG.',
            'image.max'      => 'Ukuran gambar maksimal 15 MB.',
        ]);

        $file = $request->file('image');
        $path = $file->store("materi-content-images/teacher-{$this->teacher()->id}", 'public');

        return response()->json([
            'success' => true,
            'url'     => Storage::url($path),
        ]);
    }
}
