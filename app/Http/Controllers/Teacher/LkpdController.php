<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Lkpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LkpdController extends Controller
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
     * Halaman Editor Komponen Tugas LKPD (Lembar Kerja Peserta Didik).
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $lkpdData = is_array($module->lkpd_data) ? $module->lkpd_data : [];

        $defaultCriteria = [
            'Pemahaman & Identifikasi Masalah pada Skenario Kasus',
            'Ketepatan Metodologi & Rancangan Solusi Teknis',
            'Sistematika Analisis & Argumentasi Solusi',
            'Kerapian Penyusunan Dokumen Laporan Akhir (PDF)',
        ];

        $defaultCaseStudy = "Sebuah unit produksi/laboratorium kejuruan membutuhkan rancangan solusi terpadu untuk menyelesaikan kendala teknis dan alur operasional. Lakukan analisis kebutuhan, perumusan arsitektur/skema kerja, evaluasi alternatif solusi, dan susun rekomendasi implementasi terbaik beserta dokumentasi pendukung.";

        $defaultInstructions = "1. Cermati skenario studi kasus teknis dan unduh berkas panduan LKPD jika tersedia.\n2. Lakukan pembagian peran dan analisis mendalam bersama rekan atau secara mandiri sesuai mode yang ditentukan.\n3. Susun laporan analisis, skema rancangan, dan kesimpulan ke dalam dokumen rapi.\n4. Konversi laporan akhir ke format PDF (Maksimal 5 MB) dan unggah berkas jawaban Anda secara individu pada portal ini.";

        $data = array_merge([
            'lkpd_title'          => 'Lembar Kerja Peserta Didik: ' . $module->title,
            'work_mode'           => 'group', // 'group' (Kelompok) atau 'individual' (Individu)
            'group_size'          => '3 - 4 Siswa',
            'estimated_duration'  => 90,
            'case_study'          => $defaultCaseStudy,
            'instructions'        => $defaultInstructions,
            'assessment_rubric'   => $defaultCriteria,
            'pdf_file_path'       => null,
            'pdf_file_name'       => null,
            'pdf_file_size'       => null,
            'max_file_size_mb'    => 5,
        ], $lkpdData);

        return view('pages.teacher.modules.lkpd', compact('module', 'data'));
    }

    /**
     * Halaman Pratinjau LKPD — simulasi antarmuka belajar siswa.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $lkpdData = is_array($module->lkpd_data) ? $module->lkpd_data : [];

        $data = array_merge([
            'lkpd_title'          => 'Lembar Kerja Peserta Didik: ' . $module->title,
            'work_mode'           => 'group',
            'group_size'          => '3 - 4 Siswa',
            'estimated_duration'  => 90,
            'case_study'          => 'Skenario studi kasus pemecahan masalah kejuruan.',
            'instructions'        => 'Pelajari skenario studi kasus, selesaikan analisis, dan unggah salinan laporan PDF secara mandiri.',
            'assessment_rubric'   => [],
            'pdf_file_path'       => null,
            'pdf_file_name'       => null,
            'pdf_file_size'       => null,
            'max_file_size_mb'    => 5,
        ], $lkpdData);

        return view('pages.teacher.modules.preview-lkpd', compact('module', 'data'));
    }

    /**
     * Simpan Pengaturan LKPD & Unggah Berkas Panduan PDF LKPD Guru.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasLkpd = $request->boolean('has_lkpd');

        $rules = [
            'lkpd_title'          => [$hasLkpd ? 'required' : 'nullable', 'string', 'max:255'],
            'work_mode'           => ['required', 'in:group,individual'],
            'group_size'          => ['nullable', 'string', 'max:100'],
            'estimated_duration'  => ['nullable', 'integer', 'min:5', 'max:300'],
            'case_study'          => ['nullable', 'string'],
            'instructions'        => ['nullable', 'string'],
            'assessment_rubric'   => ['nullable', 'array'],
            'assessment_rubric.*' => ['nullable', 'string', 'max:255'],
            'pdf_file'            => ['nullable', 'file', 'mimes:pdf', 'max:15360'], // Maks 15MB untuk guru
        ];

        $request->validate($rules, [
            'lkpd_title.required' => 'Judul Lembar Kerja Peserta Didik (LKPD) wajib diisi jika komponen diaktifkan.',
            'work_mode.in'        => 'Mode pengerjaan harus berupa Kelompok atau Individu.',
            'pdf_file.mimes'      => 'Berkas panduan LKPD harus berformat PDF resmi.',
            'pdf_file.max'        => 'Ukuran berkas panduan LKPD tidak boleh lebih dari 15 MB.',
        ]);

        $existingData = is_array($module->lkpd_data) ? $module->lkpd_data : [];
        $pdfPath = $existingData['pdf_file_path'] ?? null;
        $pdfName = $existingData['pdf_file_name'] ?? null;
        $pdfSize = $existingData['pdf_file_size'] ?? null;

        // Upload berkas panduan PDF baru jika dilampirkan
        if ($request->hasFile('pdf_file')) {
            if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

            $file = $request->file('pdf_file');
            $pdfName = $file->getClientOriginalName();
            $pdfSize = $file->getSize();
            $pdfPath = $file->store("lkpds/teacher-{$this->teacher()->id}", 'public');

            // Sinkronisasi record ke tabel lkpds
            Lkpd::updateOrCreate(
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

            Lkpd::where('module_id', $module->id)->delete();
        }

        // Filter kriteria penilaian
        $rubrics = collect($request->input('assessment_rubric', []))
            ->map(fn($r) => trim($r))
            ->filter(fn($r) => !empty($r))
            ->values()
            ->toArray();

        $workMode = $request->input('work_mode', 'group');
        $groupSize = $workMode === 'group' ? $request->input('group_size', '3 - 4 Siswa') : '1 Siswa (Mandiri)';

        $payload = [
            'lkpd_title'          => $request->input('lkpd_title', 'Lembar Kerja Peserta Didik: ' . $module->title),
            'work_mode'           => $workMode,
            'group_size'          => $groupSize,
            'estimated_duration'  => (int) $request->input('estimated_duration', 90),
            'case_study'          => $request->input('case_study', ''),
            'instructions'        => $request->input('instructions', ''),
            'assessment_rubric'   => $rubrics,
            'pdf_file_path'       => $pdfPath,
            'pdf_file_name'       => $pdfName,
            'pdf_file_size'       => $pdfSize,
            'max_file_size_mb'    => 5,
        ];

        $module->update([
            'has_lkpd'  => $hasLkpd,
            'lkpd_data' => $payload,
        ]);

        $statusText = $hasLkpd ? 'diaktifkan & disimpan' : 'disimpan (status Non-Aktif)';
        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', "Komponen Lembar Kerja Peserta Didik (LKPD) berhasil {$statusText}! ✅");
    }

    /**
     * Unduh Berkas Panduan PDF LKPD Guru.
     */
    public function downloadPdf(Module $module)
    {
        $this->authorize($module);

        $lkpdData = is_array($module->lkpd_data) ? $module->lkpd_data : [];
        $pdfPath = $lkpdData['pdf_file_path'] ?? null;
        $pdfName = $lkpdData['pdf_file_name'] ?? "LKPD-{$module->id}.pdf";

        if (!$pdfPath || !Storage::disk('public')->exists($pdfPath)) {
            return back()->with('error', 'Berkas PDF panduan LKPD tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($pdfPath, $pdfName);
    }

    /**
     * Toggle cepat status aktif/nonaktif LKPD.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $module->update([
            'has_lkpd' => !$module->has_lkpd,
        ]);

        $status = $module->has_lkpd ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Komponen LKPD berhasil {$status}! ✅");
    }
}
