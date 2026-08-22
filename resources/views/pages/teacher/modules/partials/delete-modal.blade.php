{{-- ══ Modal Konfirmasi Hapus Modul Terpadu ══ --}}
<div x-cloak
     x-show="deleteModalOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="deleteModalOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="delete-modal-title" role="dialog" aria-modal="true">

    {{-- Backdrop Blur Overlay --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
         @click="deleteModalOpen = false"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div x-show="deleteModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80">

            {{-- Modal Header & Danger Icon --}}
            <div class="bg-gradient-to-b from-rose-50/70 to-white px-6 pt-6 pb-4 sm:p-7">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 border border-rose-200 shadow-sm">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight" id="delete-modal-title">
                            Hapus E-Modul Pembelajaran?
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Konfirmasi penghapusan modul dan seluruh instrumen terkait dari sistem.
                        </p>
                    </div>
                    <button type="button" @click="deleteModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Target Module Preview Card --}}
                <div class="mt-5 rounded-2xl bg-rose-50/50 border border-rose-200/80 p-4">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-600">Modul yang akan dihapus:</span>
                    <h4 class="text-sm font-bold text-slate-900 mt-0.5 line-clamp-2" x-text="deleteTitle"></h4>
                </div>

                {{-- Warning Bullets --}}
                <div class="mt-4 space-y-2 text-xs text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-200/70">
                    <p class="font-bold text-slate-800 flex items-center gap-1.5">
                        <span class="text-rose-500 font-black">⚠️</span> Konsekuensi penghapusan:
                    </p>
                    <ul class="space-y-1.5 text-[11px] text-slate-500 pl-4 list-disc">
                        <li>Seluruh konten 5 Bagian E-Modul (Materi PPT, Video, Pre-test & Post-test, Job Sheet, LKPD, Embed) akan dihapus.</li>
                        <li>Seluruh riwayat pengumpulan tugas siswa dan rekap nilai terkait akan ikut terhapus.</li>
                        <li>Tindakan ini <strong>permanen</strong> dan data tidak dapat dipulihkan kembali.</li>
                    </ul>
                </div>
            </div>

            {{-- Modal Actions --}}
            <div class="bg-slate-50/80 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-7 gap-3 border-t border-slate-100">
                <form :action="deleteUrl" method="POST" class="inline w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-600/25 hover:bg-rose-700 hover:shadow-rose-600/35 transition-all">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        <span>Ya, Hapus Modul Permanen</span>
                    </button>
                </form>
                <button type="button"
                        @click="deleteModalOpen = false"
                        class="mt-3 sm:mt-0 inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-white px-5 py-2.5 text-xs font-bold text-slate-700 border border-slate-200 hover:bg-slate-50 transition-all shadow-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
