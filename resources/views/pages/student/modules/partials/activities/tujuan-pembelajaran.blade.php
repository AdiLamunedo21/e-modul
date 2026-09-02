{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 3. TUJUAN PEMBELAJARAN ════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="activePage === 'tujuan_pembelajaran'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">🎯</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Bagian 2: Pendahuluan</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Tujuan Pembelajaran & Capaian</h2>
                </div>
            </div>
            <template x-if="isCompleted('tujuan_pembelajaran')">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Selesai Dibaca</span>
                </span>
            </template>
        </div>

        @if(!empty($informasiUmum['tujuan_pembelajaran']['capaian_pembelajaran']))
            <div class="p-5 rounded-2xl bg-teal-50/50 border border-teal-200/70">
                <h4 class="text-xs font-bold uppercase tracking-wider text-teal-800 mb-2">Capaian Pembelajaran (CP)</h4>
                <p class="text-sm text-slate-800 leading-relaxed font-medium">
                    {{ $informasiUmum['tujuan_pembelajaran']['capaian_pembelajaran'] }}
                </p>
            </div>
        @endif

        @if(!empty($informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran']))
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Tujuan Khusus Pembelajaran (TP)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach((array) $informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran'] as $tp)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-start gap-3">
                            <span class="w-6 h-6 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                            <span class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">
                                {{ is_array($tp) ? ($tp['text'] ?? '') : $tp }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif(!empty($informasiUmum['tujuan_pembelajaran']) && is_string($informasiUmum['tujuan_pembelajaran']))
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium">
                {!! nl2br(e($informasiUmum['tujuan_pembelajaran'])) !!}
            </div>
        @endif

        {{-- Tombol Tandai Selesai Dibaca --}}
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                💡 Pahami tujuan di atas, lalu tandai selesai untuk membuka langkah berikutnya pada navigasi bawah.
            </p>
            <div>
                <template x-if="!isCompleted('tujuan_pembelajaran')">
                    <button type="button"
                            @click="markAsRead('tujuan_pembelajaran')"
                            class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs sm:text-sm shadow-md shadow-teal-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                        <span>✓</span>
                        <span>Tandai Selesai Dibaca</span>
                    </button>
                </template>
                <template x-if="isCompleted('tujuan_pembelajaran')">
                    <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs sm:text-sm font-bold">
                        <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                        <span>Sudah Selesai Dibaca</span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
