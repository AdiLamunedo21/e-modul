{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- ═══ BOTTOM SEQUENTIAL NAVIGATION BAR (NEXT / PREV) ════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div class="w-full rounded-3xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 text-left">
    {{-- Tombol Sebelumnya --}}
    <div class="flex flex-wrap items-center gap-2">
        <template x-if="prevPage">
            <button type="button"
                    @click="goToPage(prevPage.id)"
                    class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                <span>←</span>
                <span>Sebelumnya: <strong x-text="prevPage.title"></strong></span>
            </button>
        </template>
        <template x-if="!prevPage">
            <button type="button"
                    @click="viewMode = 'overview'"
                    class="w-full sm:w-auto px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                <span>📋 Detail Modul</span>
            </button>
        </template>
    </div>

    {{-- Tombol Selanjutnya --}}
    <div>
        <template x-if="nextPage">
            <button type="button"
                    @click="goToPage(nextPage.id)"
                    :disabled="!isUnlocked(nextPage.id)"
                    :class="{
                        'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/25 cursor-pointer': isUnlocked(nextPage.id),
                        'bg-slate-200 text-slate-400 cursor-not-allowed opacity-75': !isUnlocked(nextPage.id)
                    }"
                    class="w-full sm:w-auto px-6 py-3 rounded-2xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2">
                <span x-show="isUnlocked(nextPage.id)">Lanjut: <strong x-text="nextPage.title"></strong> →</span>
                <span x-show="!isUnlocked(nextPage.id)">🔒 Selesaikan Langkah Ini Terlebih Dahulu</span>
            </button>
        </template>
        <template x-if="!nextPage">
            <button type="button"
                    @click="viewMode = 'overview'"
                    class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
                <span>Selesai & Lihat Detail Nilai</span>
                <span>✓</span>
            </button>
        </template>
    </div>
</div>
