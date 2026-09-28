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
            {{-- ══ PRATINJAU DOKUMEN PANDUAN JOB SHEET (GAYA GOOGLE SITES) ══ --}}
            @if(!empty($jobSheet?->pdf_file_path))
                @php
                    $jsPdfUrl = route('student.modules.job-sheet.stream-pdf', $module);
                    $jsPdfName = $jobSheetData['judul_jobsheet'] ?? 'Panduan Praktikum Job Sheet';
                @endphp
                <div x-data="{
                        isFullscreen: false,
                        toggleFullscreen() {
                            const el = this.$refs.jsPreviewContainer;
                            if (!document.fullscreenElement) {
                                if (el.requestFullscreen) {
                                    el.requestFullscreen();
                                } else if (el.webkitRequestFullscreen) {
                                    el.webkitRequestFullscreen();
                                }
                                this.isFullscreen = true;
                            } else {
                                if (document.exitFullscreen) {
                                    document.exitFullscreen();
                                }
                                this.isFullscreen = false;
                            }
                        }
                     }"
                     @fullscreenchange.window="isFullscreen = !!document.fullscreenElement"
                     class="space-y-3">
                    
                    {{-- Container Frame Pratinjau Dokumen --}}
                    <div x-ref="jsPreviewContainer"
                         class="rounded-3xl border border-rose-200/90 shadow-md bg-slate-900 overflow-hidden flex flex-col transition-all"
                         :class="isFullscreen ? 'fixed inset-0 z-[99999] rounded-none border-none shadow-none h-screen w-screen' : 'w-full'">
                        
                        {{-- Top Header / Toolbar ala Google Sites --}}
                        <div class="px-4 sm:px-6 py-3.5 bg-slate-900 text-white flex flex-wrap items-center justify-between gap-3 shrink-0 border-b border-slate-800">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center text-xs font-black shadow-sm shrink-0">
                                    PDF
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-xs sm:text-sm font-bold text-white truncate max-w-[200px] sm:max-w-md">
                                            {{ $jsPdfName }}
                                        </h4>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500/20 text-rose-300 border border-rose-400/30 shrink-0">
                                            <span>👁️</span> Lembar Panduan
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        Lembar Instruksi & Prosedur Kerja Praktik Laboratorium
                                    </p>
                                </div>
                            </div>

                            {{-- Action Toolbar: Layar Penuh, Buka Tab Baru, Unduh --}}
                            <div class="flex items-center gap-2 shrink-0">
                                {{-- Tombol Mode Layar Penuh --}}
                                <button type="button"
                                        @click="toggleFullscreen()"
                                        class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-700 cursor-pointer shadow-xs"
                                        :title="isFullscreen ? 'Keluar dari Layar Penuh' : 'Buka dalam Layar Penuh (Fullscreen)'">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/>
                                    </svg>
                                    <span class="hidden sm:inline" x-text="isFullscreen ? 'Keluar' : 'Layar Penuh'">Layar Penuh</span>
                                </button>

                                {{-- Buka di Tab Baru --}}
                                <a href="{{ $jsPdfUrl }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-700 shadow-xs"
                                   title="Buka dokumen di tab browser baru">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                    </svg>
                                    <span class="hidden sm:inline">Tab Baru</span>
                                </a>

                                {{-- Tombol Unduh Berkas --}}
                                <a href="{{ $jsPdfUrl }}"
                                   target="_blank"
                                   download
                                   class="px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm shadow-rose-600/30"
                                   title="Unduh berkas PDF Job Sheet">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                    </svg>
                                    <span>Unduh PDF</span>
                                </a>
                            </div>
                        </div>

                        {{-- Frame Iframe Pratinjau Native PDF --}}
                        <div class="relative w-full bg-slate-950 flex-1 overflow-hidden"
                             :class="isFullscreen ? 'h-full' : 'h-[460px] sm:h-[560px] lg:h-[620px]'">
                            <iframe src="{{ $jsPdfUrl }}#toolbar=1&navpanes=1"
                                    class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                    type="application/pdf"
                                    allowfullscreen
                                    title="{{ $jsPdfName }}">
                            </iframe>
                        </div>

                        {{-- Footer Informasi --}}
                        <div class="px-4 sm:px-6 py-2 bg-slate-900 border-t border-slate-800 text-[11px] sm:text-xs text-slate-400 flex items-center justify-between shrink-0">
                            <span class="flex items-center gap-1.5">
                                <span>💡</span>
                                <span>Pelajari langkah kerja di atas, lalu kerjakan praktikum dan unggah hasil laporan pada form di bawah.</span>
                            </span>
                        </div>
                    </div>
                </div>
            @endif

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
