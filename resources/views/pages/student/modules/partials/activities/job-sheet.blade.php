{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 10. LEMBAR KERJA PRAKTIK (JOB SHEET PDF) ══════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_job_sheet)
<div x-show="activePage === 'job_sheet'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-jobsheet">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg font-bold shadow-xs">📋</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-600">Bagian {{ $secMap[4] ?? 4 }} • Lembar Kerja Praktik</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900">{{ $jobSheetData['judul_jobsheet'] ?? 'Job Sheet Praktikum Bengkel/Lab' }}</h2>
                </div>
            </div>
            @if($jobSheetSubmission)
                <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $jobSheetSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                    {{ $jobSheetSubmission->manual_score !== null ? 'Nilai: ' . $jobSheetSubmission->manual_score : 'Laporan PDF Terkirim' }}
                </span>
            @endif
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            {{-- Instruksi & Download Berkas Panduan Job Sheet --}}
            <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-rose-950">Panduan & Lembar Instruksi Praktikum</h4>
                    <p class="text-xs text-rose-800 mt-0.5">Unduh berkas PDF panduan praktikum sebelum memulai pekerjaan laboratorium.</p>
                </div>
                @if(!empty($jobSheet?->pdf_file_path))
                    <a href="{{ asset('storage/' . $jobSheet->pdf_file_path) }}"
                       target="_blank"
                       download
                       class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2 shrink-0">
                        <span>Unduh Panduan Job Sheet PDF</span>
                        <span>📥</span>
                    </a>
                @endif
            </div>

            {{-- Form / Status Pengumpulan Laporan Job Sheet --}}
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>📑</span>
                        <span>Unggah Laporan Hasil Praktikum (PDF)</span>
                    </h4>
                    @if($jobSheetSubmission && $jobSheetSubmission->manual_score === null)
                        <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'job_sheet']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    @click="openCancelModal({
                                        title: 'Batalkan Berkas Job Sheet?',
                                        description: 'Apakah Anda yakin ingin membatalkan berkas Job Sheet ini untuk mengunggah ulang dokumen baru?',
                                        warningText: 'File dokumen laporan Job Sheet yang sebelumnya diunggah akan dihapus dari sistem dan status pengerjaan modul akan direset sampai Anda mengunggah berkas baru.',
                                        confirmLabel: 'Ya, Batalkan Berkas'
                                    }, $el.closest('form'))"
                                    class="text-xs text-rose-600 hover:text-rose-700 font-bold underline cursor-pointer">
                                Batalkan / Unggah Ulang
                            </button>
                        </form>
                    @endif
                </div>

                @if($jobSheetSubmission)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm">PDF</span>
                            <div>
                                <p class="text-xs font-bold text-slate-900">Laporan Job Sheet Terkirim</p>
                                <p class="text-[11px] text-slate-500">Dikirim: {{ $jobSheetSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($jobSheetSubmission->manual_score !== null)
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                                    Nilai: {{ $jobSheetSubmission->manual_score }}/100
                                </span>
                            @endif
                            <a href="{{ asset('storage/' . $jobSheetSubmission->uploaded_file_path) }}"
                               target="_blank"
                               class="px-4 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                                Lihat Berkas ↗
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black">✓</span>
                                <span>Sudah Selesai Dikerjakan</span>
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('student.modules.job-sheet.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-rose-400 bg-slate-50/50 transition">
                            <span class="text-3xl block mb-2">📄</span>
                            <p class="text-xs sm:text-sm font-bold text-slate-700">Pilih Berkas Laporan Praktikum Job Sheet</p>
                            <p class="text-xs text-slate-400 mt-1">Dokumen harus dalam format PDF (Maksimal 10 MB)</p>
                            <input type="file"
                                   name="job_sheet_file"
                                   accept=".pdf,application/pdf"
                                   required
                                   class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer">
                        </div>
                        <div class="flex justify-end">
                            <button type="button"
                                    class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition cursor-pointer"
                                    @click="openSubmitModal({
                                        title: 'Kirim Laporan Job Sheet?',
                                        description: 'File PDF laporan praktikum Anda akan diunggah ke sistem. Pastikan dokumen sudah lengkap dan sesuai format.',
                                        accentColor: 'rose',
                                        warningText: 'Laporan yang sudah dikirim tidak dapat diganti.',
                                        confirmLabel: 'Kirim Laporan PDF'
                                    }, $el.closest('form'))">
                                Kirim Laporan Job Sheet PDF
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
