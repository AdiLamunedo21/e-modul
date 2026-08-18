<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\JobSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobSheetController extends Controller
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
     * Halaman Editor Komponen Lembar Praktikum (Job Sheet PDF).
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $jobSheetData = is_array($module->job_sheet_data) ? $module->job_sheet_data : [];

        $defaultTools = [
            '1 unit PC / Laptop dengan spesifikasi standar laboratorium',
            'Sistem Operasi dan Perangkat Lunak Praktikum terkait',
            'Buku catatan praktikum dan lembar verifikasi instruktur',
        ];

        $defaultCriteria = [
            'Kesesuaian langkah kerja dengan prosedur SOP',
            'Kerapian dan ketelitian hasil kerja teknis',
            'Penerapan Keselamatan dan Kesehatan Kerja (K3)',
            'Kelengkapan laporan dan analisis hasil praktikum',
        ];

        $data = array_merge([
            'job_sheet_title'     => 'Lembar Praktikum: ' . $module->title,
            'estimated_duration'  => 60,
            'instructions'        => "Pelajari prosedur kerja teknis pada berkas Job Sheet yang disediakan. Laksanakan setiap tahapan praktikum secara mandiri dan cermat di bengkel/laboratorium, kemudian buat laporan hasil pengerjaan lalu unggah berkas PDF Anda pada kolom yang tersedia.",
            'safety_guidelines'   => "1. Gunakan pakaian praktik (wearpack) dan perlengkapan pelindung diri (APD) sesuai standar bengkel.\n2. Pastikan aliran daya listrik dan peralatan dalam kondisi aman sebelum digunakan.\n3. Laporkan segera kepada guru/instruktur jika terjadi kendala teknis atau kondisi tidak aman.",
            'tools_and_materials' => $defaultTools,
            'assessment_rubric'   => $defaultCriteria,
            'pdf_file_path'       => null,
            'pdf_file_name'       => null,
            'pdf_file_size'       => null,
            'max_file_size_mb'    => 5,
        ], $jobSheetData);

        return view('pages.teacher.modules.job-sheet', compact('module', 'data'));
    }

    /**
     * Halaman Pratinjau Lembar Praktikum — simulasi antarmuka belajar siswa.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $jobSheetData = is_array($module->job_sheet_data) ? $module->job_sheet_data : [];

        $data = array_merge([
            'job_sheet_title'     => 'Lembar Praktikum: ' . $module->title,
            'estimated_duration'  => 60,
            'instructions'        => "Pelajari prosedur kerja teknis pada berkas Job Sheet yang disediakan. Laksanakan setiap tahapan praktikum secara mandiri, kemudian unggah berkas PDF laporan hasil Anda.",
            'safety_guidelines'   => "Gunakan APD dan patuhi prosedur SOP bengkel/laboratorium.",
            'tools_and_materials' => [],
            'assessment_rubric'   => [],
            'pdf_file_path'       => null,
            'pdf_file_name'       => null,
            'pdf_file_size'       => null,
            'max_file_size_mb'    => 5,
        ], $jobSheetData);

        return view('pages.teacher.modules.preview-job-sheet', compact('module', 'data'));
    }

    /**
     * Simpan Pengaturan Lembar Praktikum & Unggah Berkas PDF Job Sheet.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasJobSheet = $request->boolean('has_job_sheet');

        $rules = [
            'job_sheet_title'       => [$hasJobSheet ? 'required' : 'nullable', 'string', 'max:255'],
            'estimated_duration'    => ['nullable', 'integer', 'min:5', 'max:300'],
            'instructions'          => ['nullable', 'string'],
            'safety_guidelines'     => ['nullable', 'string'],
            'tools_and_materials'   => ['nullable', 'array'],
            'tools_and_materials.*' => ['nullable', 'string', 'max:255'],
            'assessment_rubric'     => ['nullable', 'array'],
            'assessment_rubric.*'   => ['nullable', 'string', 'max:255'],
            'pdf_file'              => ['nullable', 'file', 'mimes:pdf', 'max:15360'], // Maks 15MB untuk guru
        ];

        $request->validate($rules, [
            'job_sheet_title.required' => 'Judul Lembar Praktikum wajib diisi jika komponen diaktifkan.',
            'pdf_file.mimes'           => 'Berkas Job Sheet harus berformat PDF resmi.',
            'pdf_file.max'             => 'Ukuran berkas Job Sheet PDF tidak boleh lebih dari 15 MB.',
        ]);

        $existingData = is_array($module->job_sheet_data) ? $module->job_sheet_data : [];
        $pdfPath = $existingData['pdf_file_path'] ?? null;
        $pdfName = $existingData['pdf_file_name'] ?? null;
        $pdfSize = $existingData['pdf_file_size'] ?? null;

        // Upload berkas baru jika dilampirkan
        if ($request->hasFile('pdf_file')) {
            if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

            $file = $request->file('pdf_file');
            $pdfName = $file->getClientOriginalName();
            $pdfSize = $file->getSize();
            $pdfPath = $file->store("job-sheets/teacher-{$this->teacher()->id}", 'public');

            // Sinkronisasi record ke tabel job_sheets
            JobSheet::updateOrCreate(
                ['module_id' => $module->id],
                ['pdf_file_path' => $pdfPath]
            );
        }

        // Hapus berkas jika dicentang hapus
        if ($request->boolean('remove_pdf_file') && $pdfPath) {
            if (Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }
            $pdfPath = null;
            $pdfName = null;
            $pdfSize = null;

            JobSheet::where('module_id', $module->id)->delete();
        }

        // Filter daftar alat & bahan
        $tools = collect($request->input('tools_and_materials', []))
            ->map(fn($t) => trim($t))
            ->filter(fn($t) => !empty($t))
            ->values()
            ->toArray();

        // Filter kriteria penilaian
        $rubrics = collect($request->input('assessment_rubric', []))
            ->map(fn($r) => trim($r))
            ->filter(fn($r) => !empty($r))
            ->values()
            ->toArray();

        $payload = [
            'job_sheet_title'     => $request->input('job_sheet_title', 'Lembar Praktikum: ' . $module->title),
            'estimated_duration'  => (int) $request->input('estimated_duration', 60),
            'instructions'        => $request->input('instructions', ''),
            'safety_guidelines'   => $request->input('safety_guidelines', ''),
            'tools_and_materials' => $tools,
            'assessment_rubric'   => $rubrics,
            'pdf_file_path'       => $pdfPath,
            'pdf_file_name'       => $pdfName,
            'pdf_file_size'       => $pdfSize,
            'max_file_size_mb'    => 5,
        ];

        $module->update([
            'has_job_sheet'  => $hasJobSheet,
            'job_sheet_data' => $payload,
        ]);

        $statusText = $hasJobSheet ? 'diaktifkan & disimpan' : 'disimpan (status Non-Aktif)';
        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', "Komponen Lembar Praktikum (Job Sheet) berhasil {$statusText}! ✅");
    }

    /**
     * Unduh Berkas PDF Job Sheet Guru.
     */
    public function downloadPdf(Module $module)
    {
        $this->authorize($module);

        $jobSheetData = is_array($module->job_sheet_data) ? $module->job_sheet_data : [];
        $pdfPath = $jobSheetData['pdf_file_path'] ?? null;
        $pdfName = $jobSheetData['pdf_file_name'] ?? "JobSheet-{$module->id}.pdf";

        if (!$pdfPath || !Storage::disk('public')->exists($pdfPath)) {
            return back()->with('error', 'Berkas PDF Job Sheet tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($pdfPath, $pdfName);
    }

    /**
     * Toggle cepat status aktif/nonaktif Job Sheet.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $module->update([
            'has_job_sheet' => !$module->has_job_sheet,
        ]);

        $status = $module->has_job_sheet ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Komponen Lembar Praktikum (Job Sheet) berhasil {$status}! ✅");
    }
}
