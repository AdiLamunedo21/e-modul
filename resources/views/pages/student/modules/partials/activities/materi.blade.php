{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 7. URAIAN MATERI PEMBELAJARAN & PPT ═══════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_materi)
<div x-show="activePage === 'materi'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-blue-50/70 to-slate-50">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-slate-200/80 text-slate-800 text-xs font-bold">
                        📖 Bagian 3: Kegiatan Belajar
                    </span>
                </div>
                <template x-if="isCompleted('materi')">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Selesai Dipelajari</span>
                    </span>
                </template>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ $materiData['judul_materi'] ?? $module->title }}
            </h2>
        </div>

        {{-- Teks Uraian --}}
        <div class="p-6 sm:p-8 space-y-6">
            <div class="materi-prose text-slate-800 leading-relaxed text-sm sm:text-base">
                @if(!empty($materiData['uraian_materi']))
                    {!! $materiData['uraian_materi'] !!}
                @else
                    <p class="text-slate-400 italic">Materi pembelajaran belum diunggah oleh guru pengampu.</p>
                @endif
            </div>

            {{-- Unduh Berkas PPT / Slide Pembelajaran --}}
            @if(!empty($materiData['ppt_file_path']))
                <div class="mt-8 p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow-md">
                            📊
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Dokumen Slide Presentasi (PPT / PDF)</h4>
                            <p class="text-xs text-slate-500">{{ $materiData['ppt_file_name'] ?? 'Slide Materi' }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $materiData['ppt_file_path']) }}"
                       target="_blank"
                       download
                       class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2">
                        <span>Unduh Slide Presentasi</span>
                        <span>📥</span>
                    </a>
                </div>
            @endif

            {{-- Tombol Tandai Selesai Membaca Materi & Lanjut --}}
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">
                    💡 Tandai materi ini telah dipelajari untuk membuka video pembelajaran & tugas berikutnya.
                </p>
                <button type="button"
                        @click="markAsReadAndGoNext('materi', nextPage ? nextPage.id : null)"
                        class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs sm:text-sm shadow-md shadow-blue-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                    <span>Tandai Selesai Mempelajari Materi & Lanjut</span>
                    <span>→</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
