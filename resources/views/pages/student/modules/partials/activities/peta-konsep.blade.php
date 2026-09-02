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

        @php
            $petaKonsepImagePath = $informasiUmum['peta_konsep']['peta_konsep_image_path'] ?? ($informasiUmum['peta_konsep_image_path'] ?? null);
            $petaKonsepText = $informasiUmum['peta_konsep_text']
                ?? ($informasiUmum['peta_konsep']['peta_konsep_text']
                ?? ($informasiUmum['peta_konsep']['text']
                ?? (is_string($informasiUmum['peta_konsep'] ?? null) ? $informasiUmum['peta_konsep'] : '')));
        @endphp

        @if(!empty($petaKonsepImagePath))
            <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-200/70">
                <img src="{{ asset('storage/' . $petaKonsepImagePath) }}"
                     alt="Peta Konsep"
                     class="max-h-96 mx-auto rounded-xl border border-slate-200 shadow-sm object-contain">
            </div>
        @endif

        @if(!empty($petaKonsepText))
            <div class="p-5 rounded-2xl bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed border border-slate-200/70 font-mono">
                {!! nl2br(e($petaKonsepText)) !!}
            </div>
        @endif

        {{-- Tombol Tandai Selesai Dibaca --}}
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                💡 Amati struktur keterkaitan materi pada bagan di atas, lalu tandai selesai untuk membuka langkah berikutnya pada navigasi bawah.
            </p>
            <div>
                <template x-if="!isCompleted('peta_konsep')">
                    <button type="button"
                            @click="markAsRead('peta_konsep')"
                            class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs sm:text-sm shadow-md shadow-teal-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                        <span>✓</span>
                        <span>Tandai Selesai Dibaca</span>
                    </button>
                </template>
                <template x-if="isCompleted('peta_konsep')">
                    <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs sm:text-sm font-bold">
                        <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                        <span>Sudah Selesai Dibaca</span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
