{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 13. DAFTAR PUSTAKA ════════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="activePage === 'daftar_pustaka'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 p-5 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg font-bold shrink-0">📚</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-600">Bagian 5 • Bagian Akhir</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Daftar Pustaka & Rujukan</h2>
                </div>
            </div>
            <template x-if="isCompleted('daftar_pustaka')">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold shrink-0">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Selesai Dibaca</span>
                </span>
            </template>
        </div>

        @php
            $daftarPustakaList = [];
            if (isset($informasiUmum['daftar_pustaka'])) {
                $daftarPustakaList = is_array($informasiUmum['daftar_pustaka']) && isset($informasiUmum['daftar_pustaka']['daftar_pustaka'])
                    ? $informasiUmum['daftar_pustaka']['daftar_pustaka']
                    : (array) $informasiUmum['daftar_pustaka'];
            }
        @endphp

        <div class="space-y-3">
            @if(!empty($daftarPustakaList))
                @foreach($daftarPustakaList as $idx => $pustaka)
                    @php
                        $judul = is_array($pustaka) ? ($pustaka['judul'] ?? '') : $pustaka;
                        $penulis = is_array($pustaka) ? ($pustaka['penulis'] ?? '') : '';
                        $tahun = is_array($pustaka) ? ($pustaka['tahun'] ?? '') : '';
                        $tautan = is_array($pustaka) ? ($pustaka['tautan'] ?? '') : '';
                    @endphp
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-start gap-3.5 overflow-hidden">
                        <span class="w-6 h-6 rounded-lg bg-slate-200 text-slate-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                            {{ $idx + 1 }}
                        </span>
                        <div class="text-xs sm:text-sm text-slate-800 leading-relaxed flex-1 min-w-0 break-words">
                            @if($penulis)<strong>{{ $penulis }}</strong>. @endif
                            @if($tahun)({{ $tahun }}). @endif
                            <em class="font-bold text-slate-900">{{ $judul }}</em>.
                            @if($tautan)
                                <a href="{{ $tautan }}" target="_blank" rel="noopener" class="text-teal-600 hover:underline block mt-1.5 text-xs break-all">
                                    {{ $tautan }} ↗
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-slate-400 italic text-sm">Tidak ada daftar pustaka yang dicantumkan.</p>
            @endif
        </div>

        {{-- Tombol Tandai Selesai Dibaca --}}
        <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                💡 Klik tombol di samping setelah selesai membaca untuk membuka langkah Rekapitulasi Nilai pada navigasi bawah.
            </p>
            <div>
                <template x-if="!isCompleted('daftar_pustaka')">
                    <button type="button"
                            @click="markAsRead('daftar_pustaka')"
                            class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs sm:text-sm shadow-md shadow-emerald-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                        <span>✓</span>
                        <span>Tandai Selesai Dibaca</span>
                    </button>
                </template>
                <template x-if="isCompleted('daftar_pustaka')">
                    <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs sm:text-sm font-bold">
                        <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                        <span>Sudah Selesai Dibaca</span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
