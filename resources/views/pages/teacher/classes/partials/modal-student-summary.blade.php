{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL 3: RINCIAN NILAI AKADEMIK SISWA (AJAX JSON MODAL)                   --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div x-show="studentModalOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="studentModalOpen = false"></div>

    {{-- Dialog Wrapper --}}
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div @click.stop
             class="relative w-full max-w-3xl transform rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-200 overflow-hidden my-8">
            
            {{-- Header Modal --}}
            <div class="flex items-start justify-between px-6 sm:px-8 pt-6 sm:pt-7 pb-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-lg border border-blue-100 shrink-0">
                        📊
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 leading-tight" x-text="selectedStudent.name || 'Memuat...'"></h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            NISN: <span class="font-mono text-slate-600 font-bold" x-text="selectedStudent.identity_number || '-'"></span> • <span x-text="selectedStudent.class_name || ''"></span>
                        </p>
                    </div>
                </div>

                <button @click="studentModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center text-base transition-colors shrink-0 cursor-pointer">&times;</button>
            </div>

            {{-- Loading State --}}
            <div x-show="loadingSummary" class="p-12 text-center space-y-3">
                <div class="w-8 h-8 border-3 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                <p class="text-xs text-slate-400 font-medium">Mengambil rekap nilai akademik siswa...</p>
            </div>

            {{-- Summary Content --}}
            <div x-show="!loadingSummary" class="p-6 sm:p-8 space-y-5">
                {{-- Quick Aggregate Summary --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase">Rata-rata Nilai</p>
                        <p class="text-xl font-black mt-0.5"
                           :class="studentOverallAvg >= 75 ? 'text-emerald-600' : (studentOverallAvg > 0 ? 'text-amber-600' : 'text-slate-400')"
                           x-text="studentOverallAvg ? studentOverallAvg + ' Poin' : '-'"></p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase">Status Kelulusan</p>
                        <p class="text-xs font-bold mt-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full"
                           :class="studentKktpStatus === 'Tuntas' ? 'bg-emerald-100 text-emerald-800' : (studentKktpStatus === 'Belum Tuntas (Remedial)' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-600')"
                           x-text="studentKktpStatus"></p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-[11px] font-bold text-slate-400 uppercase">Total Modul</p>
                        <p class="text-xl font-black text-slate-800 mt-0.5" x-text="studentModulesSummary.length + ' Modul'"></p>
                    </div>
                </div>

                {{-- Table Rincian Modul --}}
                <div class="overflow-x-auto max-h-72 border border-slate-200 rounded-2xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-900 text-white font-bold uppercase sticky top-0">
                            <tr>
                                <th class="py-2.5 px-3">Modul</th>
                                <th class="py-2.5 px-3 text-center">Pre-Test</th>
                                <th class="py-2.5 px-3 text-center">Video</th>
                                <th class="py-2.5 px-3 text-center">Simulasi</th>
                                <th class="py-2.5 px-3 text-center">Job Sheet</th>
                                <th class="py-2.5 px-3 text-center">LKPD</th>
                                <th class="py-2.5 px-3 text-center">Post-Test</th>
                                <th class="py-2.5 px-3 text-center">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <template x-for="mod in studentModulesSummary" :key="mod.module_id">
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2.5 px-3 font-semibold text-slate-800 max-w-[160px] truncate" x-text="mod.module_title"></td>
                                    <td class="py-2.5 px-3 text-center" x-text="mod.pre_test_score !== null ? mod.pre_test_score : '-'"></td>
                                    <td class="py-2.5 px-3 text-center" x-text="mod.video_score !== null ? mod.video_score : '-'"></td>
                                    <td class="py-2.5 px-3 text-center" x-text="mod.embed_score !== null ? mod.embed_score : '-'"></td>
                                    <td class="py-2.5 px-3 text-center" x-text="mod.job_sheet_score !== null ? mod.job_sheet_score : '-'"></td>
                                    <td class="py-2.5 px-3 text-center" x-text="mod.lkpd_score !== null ? mod.lkpd_score : '-'"></td>
                                    <td class="py-2.5 px-3 text-center" x-text="mod.post_test_score !== null ? mod.post_test_score : '-'"></td>
                                    <td class="py-2.5 px-3 text-center font-bold"
                                        :class="mod.summative_score >= 75 ? 'text-emerald-600' : (mod.summative_score !== null ? 'text-amber-600' : 'text-slate-300')"
                                        x-text="mod.summative_score !== null ? mod.summative_score : '-'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end">
                <button @click="studentModalOpen = false" type="button"
                        class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
