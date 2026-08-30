{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 4. PETA KONSEP ════════════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="activePage === 'peta_konsep'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">🗺️</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Bagian 2: Pendahuluan</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Peta Konsep Materi</h2>
                </div>
            </div>
            <template x-if="isCompleted('peta_konsep')">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Selesai Dibaca</span>
                </span>
            </template>
        </div>

        @if(!empty($informasiUmum['peta_konsep']['peta_konsep_image_path']))
            <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-200/70">
                <img src="{{ asset('storage/' . $informasiUmum['peta_konsep']['peta_konsep_image_path']) }}"
                     alt="Peta Konsep"
                     class="max-h-96 mx-auto rounded-xl border border-slate-200 shadow-sm object-contain">
            </div>
        @endif

        @if(!empty($informasiUmum['peta_konsep']['peta_konsep_text']))
            <div class="p-5 rounded-2xl bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed border border-slate-200/70 font-mono">
                {!! nl2br(e($informasiUmum['peta_konsep']['peta_konsep_text'])) !!}
            </div>
        @elseif(!empty($informasiUmum['peta_konsep']) && is_string($informasiUmum['peta_konsep']))
            <div class="p-5 rounded-2xl bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed border border-slate-200/70">
                {!! nl2br(e($informasiUmum['peta_konsep'])) !!}
            </div>
        @endif

        {{-- Tombol Tandai Selesai Dibaca & Lanjut --}}
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                💡 Amati struktur keterkaitan materi pada bagan di atas sebelum melangkah ke tahap berikutnya.
            </p>
            <button type="button"
                    @click="markAsReadAndGoNext('peta_konsep', nextPage ? nextPage.id : null)"
                    class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs sm:text-sm shadow-md shadow-teal-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                <span>Tandai Selesai Dibaca & Lanjut</span>
                <span>→</span>
            </button>
        </div>
    </div>
</div>
