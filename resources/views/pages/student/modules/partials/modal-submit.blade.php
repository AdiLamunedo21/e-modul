{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL KONFIRMASI PENGIRIMAN JAWABAN (UNIVERSAL, ALPINE.JS POWERED)    --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div x-show="submitModal.open"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @keydown.escape.window="closeSubmitModal()"
     class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center p-0 sm:p-6"
     role="dialog"
     aria-modal="true"
     :aria-label="submitModal.title">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-[2px]"
         @click="closeSubmitModal()">
    </div>

    {{-- Modal Sheet --}}
    <div class="relative w-full sm:max-w-lg bg-white sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95 sm:translate-y-0"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95 sm:translate-y-0">

        {{-- Handle bar (mobile only) --}}
        <div class="sm:hidden flex justify-center pt-3 pb-1">
            <div class="w-10 h-1 rounded-full bg-slate-200"></div>
        </div>

        {{-- Header --}}
        <div class="px-6 pt-4 pb-5 sm:pt-6 border-b border-slate-100">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg shrink-0 shadow-2xs"
                         :class="{
                             'bg-teal-100 text-teal-600': submitModal.accentColor === 'teal',
                             'bg-rose-100 text-rose-600': submitModal.accentColor === 'rose',
                             'bg-blue-100 text-blue-600': submitModal.accentColor === 'blue',
                             'bg-indigo-100 text-indigo-600': submitModal.accentColor === 'indigo',
                             'bg-amber-100 text-amber-600': submitModal.accentColor === 'amber',
                             'bg-emerald-100 text-emerald-600': submitModal.accentColor === 'emerald'
                         }">
                        <span x-text="submitModal.accentColor === 'rose' ? '🗑️' : '🚀'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-widest"
                           :class="{
                               'text-teal-600': submitModal.accentColor === 'teal',
                               'text-rose-600': submitModal.accentColor === 'rose',
                               'text-blue-600': submitModal.accentColor === 'blue',
                               'text-indigo-600': submitModal.accentColor === 'indigo',
                               'text-amber-600': submitModal.accentColor === 'amber',
                               'text-emerald-600': submitModal.accentColor === 'emerald'
                           }"
                           x-text="submitModal.category || (submitModal.accentColor === 'rose' ? 'Konfirmasi Pembatalan' : 'Konfirmasi Pengiriman')"></p>
                        <h3 class="mt-1 text-[17px] font-black text-slate-900 leading-snug tracking-tight"
                            x-text="submitModal.title"></h3>
                    </div>
                </div>
                <button type="button"
                        @click="closeSubmitModal()"
                        :disabled="submitModal.submitting"
                        class="shrink-0 w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-all disabled:opacity-30 disabled:cursor-not-allowed mt-0.5"
                        title="Tutup">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 space-y-4">

            {{-- Deskripsi --}}
            <p class="text-sm text-slate-600 leading-relaxed" x-text="submitModal.description"></p>

            {{-- Peringatan (conditional) --}}
            <template x-if="submitModal.warningText">
                <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl border"
                     :class="{
                         'bg-teal-50 border-teal-200/70': submitModal.accentColor === 'teal',
                         'bg-rose-50 border-rose-200/70': submitModal.accentColor === 'rose',
                         'bg-blue-50 border-blue-200/70': submitModal.accentColor === 'blue',
                         'bg-indigo-50 border-indigo-200/70': submitModal.accentColor === 'indigo',
                         'bg-amber-50 border-amber-200/70': submitModal.accentColor === 'amber',
                         'bg-emerald-50 border-emerald-200/70': submitModal.accentColor === 'emerald'
                     }">
                    <svg class="w-4 h-4 shrink-0 mt-px"
                         :class="{
                             'text-teal-500': submitModal.accentColor === 'teal',
                             'text-rose-500': submitModal.accentColor === 'rose',
                             'text-blue-500': submitModal.accentColor === 'blue',
                             'text-indigo-500': submitModal.accentColor === 'indigo',
                             'text-amber-500': submitModal.accentColor === 'amber',
                             'text-emerald-500': submitModal.accentColor === 'emerald'
                         }"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs font-medium leading-relaxed"
                       :class="{
                           'text-teal-800': submitModal.accentColor === 'teal',
                           'text-rose-800': submitModal.accentColor === 'rose',
                           'text-blue-800': submitModal.accentColor === 'blue',
                           'text-indigo-800': submitModal.accentColor === 'indigo',
                           'text-amber-800': submitModal.accentColor === 'amber',
                           'text-emerald-800': submitModal.accentColor === 'emerald'
                       }"
                       x-text="submitModal.warningText">
                    </p>
                </div>
            </template>
        </div>

        {{-- Footer Buttons --}}
        <div class="px-6 pb-6 pt-1 flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-2.5">

            {{-- Batal --}}
            <button type="button"
                    @click="closeSubmitModal()"
                    :disabled="submitModal.submitting"
                    class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm text-center transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                Batal
            </button>

            {{-- Konfirmasi Submit --}}
            <button type="button"
                    @click="confirmSubmit()"
                    :disabled="submitModal.submitting"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-white font-bold text-sm text-center transition-all disabled:opacity-75 disabled:cursor-wait flex items-center justify-center gap-2"
                    :class="{
                        'bg-teal-600 hover:bg-teal-700 active:bg-teal-800 shadow-sm shadow-teal-600/30': submitModal.accentColor === 'teal',
                        'bg-rose-600 hover:bg-rose-700 active:bg-rose-800 shadow-sm shadow-rose-600/30': submitModal.accentColor === 'rose',
                        'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 shadow-sm shadow-blue-600/30': submitModal.accentColor === 'blue',
                        'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm shadow-indigo-600/30': submitModal.accentColor === 'indigo',
                        'bg-amber-600 hover:bg-amber-700 active:bg-amber-800 shadow-sm shadow-amber-600/30': submitModal.accentColor === 'amber',
                        'bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 shadow-sm shadow-emerald-600/30': submitModal.accentColor === 'emerald'
                    }">

                {{-- Idle state --}}
                <template x-if="!submitModal.submitting">
                    <span x-text="submitModal.confirmLabel"></span>
                </template>

                {{-- Loading state --}}
                <template x-if="submitModal.submitting">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="submitModal.loadingLabel || 'Mengirim...'"></span>
                    </span>
                </template>

            </button>
        </div>

    </div>
</div>
