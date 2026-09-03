{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 11. TUGAS LKPD ════════════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_lkpd)
<div x-show="activePage === 'lkpd'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-lkpd">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg font-bold shadow-xs">👥</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-600">Bagian 4 • Lembar Kerja Peserta Didik</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900">{{ $lkpdData['judul_lkpd'] ?? 'Tugas Lembar Kerja (LKPD)' }}</h2>
                </div>
            </div>
            @if($lkpdSubmission)
                <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $lkpdSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                    {{ $lkpdSubmission->manual_score !== null ? 'Nilai: ' . $lkpdSubmission->manual_score : 'Tugas LKPD Terkirim' }}
                </span>
            @endif
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            {{-- Instruksi & Download LKPD --}}
            <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-bold text-amber-950">Berkas Soal & Instruksi LKPD</h4>
                    <p class="text-xs text-amber-800 mt-0.5">Pelajari dan diskusikan soal LKPD bersama kelompok kerja Anda.</p>
                </div>
                @if(!empty($lkpd?->pdf_file_path))
                    <a href="{{ asset('storage/' . $lkpd->pdf_file_path) }}"
                       target="_blank"
                       download
                       class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2 shrink-0">
                        <span>Unduh Berkas LKPD PDF</span>
                        <span>📥</span>
                    </a>
                @endif
            </div>

            {{-- Form / Status Pengumpulan LKPD --}}
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>📑</span>
                        <span>Unggah Jawaban / Laporan LKPD (PDF)</span>
                    </h4>
                    @if($lkpdSubmission && $lkpdSubmission->manual_score === null)
                        <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'lkpd']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Apakah Anda ingin membatalkan berkas LKPD ini untuk mengunggah ulang?');"
                                    class="text-xs text-red-600 hover:text-red-700 font-bold underline cursor-pointer">
                                Batalkan / Unggah Ulang
                            </button>
                        </form>
                    @endif
                </div>

                @if($lkpdSubmission)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">PDF</span>
                            <div>
                                <p class="text-xs font-bold text-slate-900">Tugas LKPD Terkirim</p>
                                <p class="text-[11px] text-slate-500">Dikirim: {{ $lkpdSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($lkpdSubmission->manual_score !== null)
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                                    Nilai: {{ $lkpdSubmission->manual_score }}/100
                                </span>
                            @endif
                            <a href="{{ asset('storage/' . $lkpdSubmission->uploaded_file_path) }}"
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
                    <form action="{{ route('student.modules.lkpd.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-amber-400 bg-slate-50/50 transition">
                            <span class="text-3xl block mb-2">📄</span>
                            <p class="text-xs sm:text-sm font-bold text-slate-700">Pilih Berkas Jawaban LKPD</p>
                            <p class="text-xs text-slate-400 mt-1">Dokumen harus dalam format PDF (Maksimal 10 MB)</p>
                            <input type="file"
                                   name="lkpd_file"
                                   accept=".pdf,application/pdf"
                                   required
                                   class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 cursor-pointer">
                        </div>
                        <div class="flex justify-end">
                            <button type="button"
                                    class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md shadow-amber-600/20 transition cursor-pointer"
                                    @click="openSubmitModal({
                                        title: 'Kirim Jawaban LKPD?',
                                        description: 'File PDF jawaban LKPD Anda akan dikumpulkan dan diperiksa oleh guru. Pastikan semua tugas sudah dikerjakan dengan benar.',
                                        accentColor: 'amber',
                                        warningText: 'Tugas LKPD yang sudah dikumpulkan tidak dapat diganti.',
                                        confirmLabel: 'Kumpulkan Jawaban LKPD'
                                    }, $el.closest('form'))">
                                Kirim Jawaban LKPD PDF
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
