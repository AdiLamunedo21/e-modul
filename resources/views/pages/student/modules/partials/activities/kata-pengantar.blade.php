{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 1. KATA PENGANTAR ═════════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="activePage === 'kata_pengantar'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">✏️</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian Awal • Langkah 1</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kata Pengantar</h2>
                </div>
            </div>
            <template x-if="isCompleted('kata_pengantar')">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sudah Selesai Dibaca</span>
                </span>
            </template>
        </div>

        <div class="prose prose-slate max-w-none text-sm sm:text-base text-slate-700 leading-relaxed space-y-3">
            @if(!empty($informasiUmum['kata_pengantar']['kata_pengantar_text']))
                {!! nl2br(e($informasiUmum['kata_pengantar']['kata_pengantar_text'])) !!}
            @elseif(!empty($informasiUmum['kata_pengantar']) && is_string($informasiUmum['kata_pengantar']))
                {!! nl2br(e($informasiUmum['kata_pengantar'])) !!}
            @else
                <p class="italic text-slate-400">
                    Puji syukur ke hadirat Tuhan Yang Maha Esa atas tersusunnya E-Modul ini sebagai media pembelajaran interaktif bagi siswa SMKN 3 Yogyakarta. Semoga modul ini dapat memfasilitasi pembelajaran mandiri yang efektif dan menyenangkan.
                </p>
            @endif
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 text-right text-xs text-slate-500">
            <p>{{ $informasiUmum['kata_pengantar']['tempat_tanggal'] ?? 'Yogyakarta, ' . date('d F Y') }}</p>
            <p class="font-bold text-slate-900 mt-1 text-sm">{{ $informasiUmum['kata_pengantar']['nama_penyusun'] ?? $module->teacher->name }}</p>
            <p class="text-[11px] text-slate-400">Guru Pengampu Mata Pelajaran</p>
        </div>

        {{-- Tombol Tandai Selesai Dibaca --}}
        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <p class="text-xs text-slate-500">
                    💡 Klik tombol di samping setelah selesai membaca untuk membuka langkah berikutnya pada navigasi bawah.
                </p>
            </div>
            <div>
                <template x-if="!isCompleted('kata_pengantar')">
                    <button type="button"
                            @click="markAsRead('kata_pengantar')"
                            class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs sm:text-sm shadow-md shadow-indigo-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                        <span>✓</span>
                        <span>Tandai Selesai Dibaca</span>
                    </button>
                </template>
                <template x-if="isCompleted('kata_pengantar')">
                    <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs sm:text-sm font-bold">
                        <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                        <span>Sudah Selesai Dibaca</span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
