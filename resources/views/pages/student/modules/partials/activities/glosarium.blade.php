{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 5. GLOSARIUM ══════════════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="activePage === 'glosarium'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">📖</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Bagian 2: Pendahuluan</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Glosarium Kata Kunci</h2>
                </div>
            </div>
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text"
                       x-model="searchGlosarium"
                       placeholder="Cari istilah teknis..."
                       class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition font-medium text-slate-800 placeholder-slate-400">
            </div>
        </div>

        @php
            $glosariumItems = [];
            if (isset($informasiUmum['glosarium'])) {
                $glosariumItems = is_array($informasiUmum['glosarium']) && isset($informasiUmum['glosarium']['glosarium'])
                    ? $informasiUmum['glosarium']['glosarium']
                    : (array) $informasiUmum['glosarium'];
            }
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
            @foreach($glosariumItems as $item)
                @php
                    $istilah = is_array($item) ? ($item['istilah'] ?? '') : '';
                    $definisi = is_array($item) ? ($item['definisi'] ?? '') : $item;
                @endphp
                <div x-show="!searchGlosarium || '{{ strtolower($istilah . ' ' . $definisi) }}'.includes(searchGlosarium.toLowerCase())"
                     class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 hover:border-teal-300 transition">
                    <h5 class="text-xs font-bold text-teal-900 mb-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        <span>{{ $istilah ?: 'Istilah' }}</span>
                    </h5>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $definisi }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Tombol Tandai Selesai Dibaca & Lanjut --}}
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                💡 Pelajari istilah-istilah di atas, lalu tandai selesai untuk masuk ke tahap kuis/materi berikutnya.
            </p>
            <button type="button"
                    @click="markAsReadAndGoNext('glosarium', nextPage ? nextPage.id : null)"
                    class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs sm:text-sm shadow-md shadow-teal-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                <span>Tandai Selesai Dibaca & Lanjut</span>
                <span>→</span>
            </button>
        </div>
    </div>
</div>
