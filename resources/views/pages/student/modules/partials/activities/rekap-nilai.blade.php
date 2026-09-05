{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 14. REKAPITULASI NILAI BELAJAR SISWA ══════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="activePage === 'rekap_nilai'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold">📊</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-600">Bagian {{ $secMap[5] ?? 5 }} • Ringkasan Akhir</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Transparansi Rekapitulasi Nilai</h2>
                </div>
            </div>
            @if($studentResult)
                <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $studentResult->grading_status === 'graded' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                    {{ $studentResult->grading_status === 'graded' ? 'Telah Dinilai Guru' : 'Menunggu Penilaian Manual' }}
                </span>
            @endif
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
            <table class="w-full text-xs sm:text-sm text-left">
                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Instrumen Aktivitas Evaluasi</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Skor / Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($module->has_pre_test)
                        <tr class="bg-blue-50/20">
                            <td class="py-3 px-4 font-semibold text-slate-800">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span>1. Kuis Awal (Pre-test)</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200 uppercase tracking-wide">
                                        Diagnostik Awal
                                    </span>
                                </div>
                                <span class="text-[11px] text-slate-400 font-normal block mt-0.5">*Asesmen awal pemetaan materi (tidak dihitung ke Nilai Akhir)</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($studentResult && $studentResult->pre_test_score !== null)
                                    <span class="text-xs font-bold text-emerald-600">✓ Selesai</span>
                                @else
                                    <span class="text-xs text-slate-400">Belum</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-700">
                                {{ $studentResult?->pre_test_score !== null ? $studentResult->pre_test_score : '-' }}
                            </td>
                        </tr>
                    @endif

                    @if($module->has_video)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-800">2. Ringkasan Video YouTube</td>
                            <td class="py-3 px-4 text-center">
                                @if($videoSummary)
                                    <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                @else
                                    <span class="text-xs text-slate-400">Belum</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-bold">
                                {{ $studentResult?->video_score !== null ? $studentResult->video_score : ($videoSummary ? 'Pending' : '-') }}
                            </td>
                        </tr>
                    @endif

                    @if($module->has_embed)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-800">3. Praktik Simulator / Embed</td>
                            <td class="py-3 px-4 text-center">
                                @if($embedSubmission)
                                    <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                @else
                                    <span class="text-xs text-slate-400">Belum</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-bold">
                                {{ $studentResult?->embed_score !== null ? $studentResult->embed_score : ($embedSubmission ? 'Pending' : '-') }}
                            </td>
                        </tr>
                    @endif

                    @if($module->has_job_sheet)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-800">4. Lembar Kerja Praktikum (Job Sheet)</td>
                            <td class="py-3 px-4 text-center">
                                @if($jobSheetSubmission)
                                    <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                @else
                                    <span class="text-xs text-slate-400">Belum</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-bold">
                                {{ $studentResult?->job_sheet_score !== null ? $studentResult->job_sheet_score : ($jobSheetSubmission ? 'Pending' : '-') }}
                            </td>
                        </tr>
                    @endif

                    @if($module->has_lkpd)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-800">5. Tugas LKPD</td>
                            <td class="py-3 px-4 text-center">
                                @if($lkpdSubmission)
                                    <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                @else
                                    <span class="text-xs text-slate-400">Belum</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-bold">
                                {{ $studentResult?->lkpd_score !== null ? $studentResult->lkpd_score : ($lkpdSubmission ? 'Pending' : '-') }}
                            </td>
                        </tr>
                    @endif

                    @if($module->has_post_test)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-800">6. Evaluasi Akhir (Post-test)</td>
                            <td class="py-3 px-4 text-center">
                                @if($studentResult && $studentResult->post_test_score !== null)
                                    <span class="text-xs font-bold text-emerald-600">✓ Selesai</span>
                                @else
                                    <span class="text-xs text-slate-400">Belum</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-bold">
                                {{ $studentResult?->post_test_score !== null ? $studentResult->post_test_score : '-' }}
                            </td>
                        </tr>
                    @endif

                    <tr class="bg-slate-50/90 font-black text-slate-900 border-t-2 border-slate-200">
                        <td class="py-4 px-4 text-sm uppercase">
                            <div class="flex items-center gap-2">
                                <span>NILAI AKHIR SUMATIF</span>
                            </div>
                            <span class="text-[11px] text-slate-500 font-normal normal-case block mt-0.5">Rata-rata penilaian aktivitas belajar & evaluasi akhir (tanpa pembobotan Pre-test)</span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'TUNTAS' : 'BELUM TUNTAS' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center text-lg {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $studentResult?->summative_score ?? 0 }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
