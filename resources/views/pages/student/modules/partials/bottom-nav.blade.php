{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- ═══ BOTTOM SEQUENTIAL NAVIGATION BAR (NEXT / PREV / FINISH) ═ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}

{{-- ── 1. TAMPILAN DESKTOP (INLINE CARD DI BAWAH KONTEN) ── --}}
<div class="hidden lg:flex w-full rounded-3xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-sm items-center justify-between gap-4 text-left">
    {{-- Tombol Kiri: Sebelumnya / Detail Modul --}}
    <div class="flex items-center gap-2">
        <template x-if="prevPage">
            <button type="button"
                    @click="goToPage(prevPage.id)"
                    class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-xs active:scale-95">
                <span>←</span>
                <span>Kembali: <strong x-text="prevPage.title"></strong></span>
            </button>
        </template>
        <template x-if="!prevPage">
            <button type="button"
                    @click="viewMode = 'overview'"
                    class="px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer active:scale-95">
                <span>← Kembali ke Detail Modul</span>
            </button>
        </template>
    </div>

    {{-- Tombol Tengah: Status Selesai / Tandai Selesai Dibaca --}}
    <div class="flex items-center justify-center">
        {{-- Kondisi A: Sudah Selesai --}}
        <template x-if="isCompleted(activePage)">
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-300 text-xs sm:text-sm font-bold shadow-2xs">
                <span class="text-sm font-black text-emerald-600">✓</span>
                <span>Langkah Ini Sudah Selesai</span>
            </div>
        </template>

        {{-- Kondisi B: Belum Selesai & Belum Scroll Sampai Bawah (Terkunci) --}}
        <template x-if="!isCompleted(activePage) && isCurrentPageReading && !hasScrolledToEnd">
            <div title="Silakan scroll konten materi ke bawah hingga selesai membaca"
                 class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-100 text-slate-400 border border-slate-200 text-xs sm:text-sm font-semibold cursor-not-allowed opacity-75">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <span>Scroll ke Bawah untuk Membuka</span>
            </div>
        </template>

        {{-- Kondisi C: Belum Selesai & Sudah Scroll Sampai Bawah (Terbuka & Siap Diklik) --}}
        <template x-if="!isCompleted(activePage) && isCurrentPageReading && hasScrolledToEnd">
            <button type="button"
                    @click="markCurrentAsCompleted()"
                    class="px-6 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-black shadow-md shadow-emerald-600/30 transition transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2 cursor-pointer ring-2 ring-emerald-400/50 animate-pulse">
                <span class="text-sm font-black">✓</span>
                <span>Tandai Selesai Dibaca</span>
            </button>
        </template>

        {{-- Kondisi D: Belum Selesai & Halaman Tugas/Kuis (Bukan tipe read) --}}
        <template x-if="!isCompleted(activePage) && !isCurrentPageReading">
            <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                <span>⏳ Kerjakan & Kirim Tugas/Kuis untuk Menyelesaikan</span>
            </div>
        </template>
    </div>

    {{-- Tombol Kanan: Selanjutnya --}}
    <div>
        <template x-if="nextPage">
            <button type="button"
                    @click="goToPage(nextPage.id)"
                    :disabled="!isCompleted(activePage)"
                    :class="{
                        'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 cursor-pointer active:scale-95': isCompleted(activePage),
                        'bg-slate-200 text-slate-400 border border-slate-200 cursor-not-allowed opacity-75': !isCompleted(activePage)
                    }"
                    class="px-6 py-3 rounded-2xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2">
                <span x-show="isCompleted(activePage)">Selanjutnya: <strong x-text="nextPage.title"></strong> →</span>
                <span x-show="!isCompleted(activePage)">🔒 Selesaikan Langkah Ini Terlebih Dahulu</span>
            </button>
        </template>
        <template x-if="!nextPage">
            <button type="button"
                    @click="viewMode = 'overview'"
                    class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-600/25 transition flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                <span>Selesai & Lihat Detail Nilai</span>
                <span>✓</span>
            </button>
        </template>
    </div>
</div>

{{-- ── 2. TAMPILAN MOBILE (FIXED DOCK MENEMPEL DI BAWAH LAYAR SMARTPHONE) ── --}}
<div class="fixed bottom-0 inset-x-0 z-40 lg:hidden bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(15,23,42,0.12)] px-3 py-2.5 transition-all select-none"
     style="padding-bottom: max(0.6rem, env(safe-area-inset-bottom));">
    
    <div class="max-w-md mx-auto flex items-center justify-between gap-2">
        {{-- Tombol Kiri: Kembali --}}
        <button type="button"
                @click="if (prevPage) { goToPage(prevPage.id); } else { viewMode = 'overview'; }"
                class="flex-1 py-2.5 px-2 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center justify-center gap-1 active:scale-95 shadow-2xs">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            <span>Kembali</span>
        </button>

        {{-- Tombol Tengah: Sudah Selesai / Selesai Dibaca --}}
        <div class="flex-1.2 flex justify-center min-w-0">
            {{-- Kondisi A: Sudah Selesai --}}
            <template x-if="isCompleted(activePage)">
                <button type="button"
                        class="w-full py-2.5 px-2 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-300 text-xs font-black transition-all flex items-center justify-center gap-1 cursor-default shadow-2xs">
                    <span class="text-sm font-black text-emerald-600">✓</span>
                    <span class="truncate">Sudah Selesai</span>
                </button>
            </template>

            {{-- Kondisi B: Belum Selesai & Belum Scroll Sampai Bawah (Terkunci) --}}
            <template x-if="!isCompleted(activePage) && isCurrentPageReading && !hasScrolledToEnd">
                <button type="button"
                        disabled
                        title="Silakan scroll konten materi ke bawah hingga selesai membaca"
                        class="w-full py-2.5 px-2 rounded-2xl bg-slate-100 text-slate-400 border border-slate-200 text-[11px] font-semibold flex items-center justify-center gap-1 cursor-not-allowed opacity-80">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    <span class="truncate">Scroll ke Bawah</span>
                </button>
            </template>

            {{-- Kondisi C: Belum Selesai & Sudah Scroll Sampai Bawah (Terbuka & Siap Diklik) --}}
            <template x-if="!isCompleted(activePage) && isCurrentPageReading && hasScrolledToEnd">
                <button type="button"
                        @click="markCurrentAsCompleted()"
                        class="w-full py-2.5 px-2 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black shadow-md shadow-emerald-600/30 transition-all active:scale-95 flex items-center justify-center gap-1 cursor-pointer ring-2 ring-emerald-400/40 animate-pulse">
                    <span class="text-sm font-black">✓</span>
                    <span class="truncate">Selesai Dibaca</span>
                </button>
            </template>

            {{-- Kondisi D: Belum Selesai & Halaman Tugas/Kuis (Bukan tipe read) --}}
            <template x-if="!isCompleted(activePage) && !isCurrentPageReading">
                <div class="w-full py-2.5 px-2 rounded-2xl bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-extrabold flex items-center justify-center gap-1 truncate">
                    <span>⏳ Selesaikan Tugas</span>
                </div>
            </template>
        </div>

        {{-- Tombol Kanan: Selanjutnya --}}
        <button type="button"
                @click="if (isCompleted(activePage)) { if (nextPage) { goToPage(nextPage.id); } else { viewMode = 'overview'; } }"
                :disabled="!isCompleted(activePage)"
                :class="{
                    'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 cursor-pointer active:scale-95': isCompleted(activePage),
                    'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed opacity-75': !isCompleted(activePage)
                }"
                class="flex-1 py-2.5 px-2 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-1 shadow-2xs">
            <span class="truncate" x-text="nextPage ? 'Selanjutnya' : 'Selesai'">Selanjutnya</span>
            <svg x-show="isCompleted(activePage)" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
            <svg x-show="!isCompleted(activePage)" class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </button>
    </div>
</div>

