{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- ═══ VIEW 1: TAMPILAN AWAL DETAIL MODUL SISWA (FULL WIDTH CARD) ═════ --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div x-show="viewMode === 'overview'" x-cloak class="w-full space-y-6">

    {{-- ══════════════════════════════════════════════════════════════════════
         STRUKTUR 5 BAGIAN E-MODUL (1 KOLOM TUNGGAL BERURUTAN & TERPROTEKSI)
         ══════════════════════════════════════════════════════════════════════ --}}
    <div class="space-y-6">

        {{-- ── 1. BAGIAN AWAL ── --}}
        <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 hover:shadow-md transition">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center font-black text-2xl text-indigo-600 shrink-0">
                        1
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Bagian Awal</h3>
                        <p class="text-xs text-slate-500">Kata Pengantar & Petunjuk Pembelajaran</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                    Pengantar
                </span>
            </div>

            <div class="space-y-3">
                {{-- Item: Kata Pengantar --}}
                @if($module->isInfoComponentActive('kata_pengantar'))
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">✏️</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Kata Pengantar</h4>
                                <p class="text-[11px] text-slate-500">Sambutan dan motivasi dari guru pengampu</p>
                            </div>
                        </div>
                        <div>
                            <template x-if="isCompleted('kata_pengantar')">
                                <button type="button"
                                        @click="goToPage('kata_pengantar')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Selesai Dibaca</span>
                                </button>
                            </template>
                            <template x-if="!isCompleted('kata_pengantar')">
                                <button type="button"
                                        @click="goToPage('kata_pengantar')"
                                        class="px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                    Baca →
                                </button>
                            </template>
                        </div>
                    </div>
                @endif

                {{-- Item: Petunjuk Penggunaan --}}
                @if($module->isInfoComponentActive('petunjuk_penggunaan'))
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">💡</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Petunjuk Penggunaan</h4>
                                <p class="text-[11px] text-slate-500">Panduan langkah belajar mandiri peserta didik</p>
                            </div>
                        </div>
                        <div>
                            <template x-if="isCompleted('petunjuk_penggunaan')">
                                <button type="button"
                                        @click="goToPage('petunjuk_penggunaan')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Selesai Dibaca</span>
                                </button>
                            </template>
                            <template x-if="!isCompleted('petunjuk_penggunaan')">
                                <template x-if="isUnlocked('petunjuk_penggunaan')">
                                    <button type="button"
                                            @click="goToPage('petunjuk_penggunaan')"
                                            class="px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                        Baca →
                                    </button>
                                </template>
                            </template>
                            <template x-if="!isUnlocked('petunjuk_penggunaan')">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                    <span>🔒</span>
                                    <span>Terkunci</span>
                                </span>
                            </template>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── 2. PENDAHULUAN ── --}}
        <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 hover:shadow-md transition">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center font-black text-2xl text-teal-600 shrink-0">
                        2
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Pendahuluan</h3>
                        <p class="text-xs text-slate-500">Capaian Pembelajaran, Peta Konsep, Glosarium & Pre-test Diagnostik</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
                    Orientasi
                </span>
            </div>

            <div class="space-y-3">
                {{-- Item: Tujuan Pembelajaran --}}
                @if($module->isInfoComponentActive('tujuan_pembelajaran'))
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">🎯</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Tujuan & Capaian</h4>
                                <p class="text-[11px] text-slate-500">Rumusan kompetensi CP & TP modul</p>
                            </div>
                        </div>
                        <div>
                            <template x-if="isCompleted('tujuan_pembelajaran')">
                                <button type="button"
                                        @click="goToPage('tujuan_pembelajaran')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Selesai Dibaca</span>
                                </button>
                            </template>
                            <template x-if="!isCompleted('tujuan_pembelajaran') && isUnlocked('tujuan_pembelajaran')">
                                <button type="button"
                                        @click="goToPage('tujuan_pembelajaran')"
                                        class="px-4 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                    Lihat →
                                </button>
                            </template>
                            <template x-if="!isUnlocked('tujuan_pembelajaran')">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                    <span>🔒</span>
                                    <span>Terkunci</span>
                                </span>
                            </template>
                        </div>
                    </div>
                @endif

                {{-- Item: Peta Konsep --}}
                @php
                    $hasPetaKonsep = !empty($informasiUmum['peta_konsep_text'])
                        || !empty($informasiUmum['peta_konsep']['peta_konsep_text'])
                        || !empty($informasiUmum['peta_konsep']['peta_konsep_image_path'])
                        || (!empty($informasiUmum['peta_konsep']) && is_string($informasiUmum['peta_konsep']));
                @endphp
                @if($module->isInfoComponentActive('peta_konsep') && $hasPetaKonsep)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">🗺️</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Peta Konsep Materi</h4>
                                <p class="text-[11px] text-slate-500">Alur keterkaitan materi kejuruan</p>
                            </div>
                        </div>
                        <div>
                            <template x-if="isCompleted('peta_konsep')">
                                <button type="button"
                                        @click="goToPage('peta_konsep')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Selesai Dibaca</span>
                                </button>
                            </template>
                            <template x-if="!isCompleted('peta_konsep') && isUnlocked('peta_konsep')">
                                <button type="button"
                                        @click="goToPage('peta_konsep')"
                                        class="px-4 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                    Buka →
                                </button>
                            </template>
                            <template x-if="!isUnlocked('peta_konsep')">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                    <span>🔒</span>
                                    <span>Terkunci</span>
                                </span>
                            </template>
                        </div>
                    </div>
                @endif

                {{-- Item: Glosarium --}}
                @php
                    $hasGlosarium = !empty($informasiUmum['glosarium']) && (
                        (is_array($informasiUmum['glosarium']) && count($informasiUmum['glosarium']) > 0)
                        || (is_string($informasiUmum['glosarium']) && trim($informasiUmum['glosarium']) !== '')
                    );
                @endphp
                @if($module->isInfoComponentActive('glosarium') && $hasGlosarium)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">📖</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Glosarium Istilah</h4>
                                <p class="text-[11px] text-slate-500">Kamus istilah teknis & konsep penting</p>
                            </div>
                        </div>
                        <div>
                            <template x-if="isCompleted('glosarium')">
                                <button type="button"
                                        @click="goToPage('glosarium')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Selesai Dibaca</span>
                                </button>
                            </template>
                            <template x-if="!isCompleted('glosarium') && isUnlocked('glosarium')">
                                <button type="button"
                                        @click="goToPage('glosarium')"
                                        class="px-4 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                    Buka →
                                </button>
                            </template>
                            <template x-if="!isUnlocked('glosarium')">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                    <span>🔒</span>
                                    <span>Terkunci</span>
                                </span>
                            </template>
                        </div>
                    </div>
                @endif

                {{-- Item: Pre-test --}}
                @if($module->has_pre_test && $module->preTest)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-teal-50/70 border border-teal-200 hover:bg-teal-100/50 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">⚡</span>
                            <div>
                                <h4 class="text-xs font-bold text-teal-950">Pre-test (Diagnostik)</h4>
                                <p class="text-[11px] text-teal-700">Kuis diagnostik sebelum membaca materi</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($studentResult && $studentResult->pre_test_score !== null)
                                <button type="button"
                                        @click="goToPage('pre_test')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-extrabold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Skor: {{ $studentResult->pre_test_score }}</span>
                                </button>
                            @else
                                <template x-if="isUnlocked('pre_test')">
                                    <button type="button"
                                            @click="goToPage('pre_test')"
                                            class="px-4 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                                        Kerjakan Kuis →
                                    </button>
                                </template>
                                <template x-if="!isUnlocked('pre_test')">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                        <span>🔒</span>
                                        <span>Terkunci</span>
                                    </span>
                                </template>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── 3. KEGIATAN BELAJAR ── --}}
        <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 hover:shadow-md transition">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center font-black text-2xl text-blue-600 shrink-0">
                        3
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Kegiatan Belajar</h3>
                        <p class="text-xs text-slate-500">Materi Inti & Multi-Video YouTube</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                    Isi Materi
                </span>
            </div>

            <div class="space-y-3">
                {{-- Item: Uraian Materi --}}
                @if($module->has_materi)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">📖</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Uraian Materi & PPT</h4>
                                <p class="text-[11px] text-slate-500">Materi teori komprehensif & slide presentasi</p>
                            </div>
                        </div>
                        <div>
                            <template x-if="isCompleted('materi')">
                                <button type="button"
                                        @click="goToPage('materi')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Selesai Dibaca</span>
                                </button>
                            </template>
                            <template x-if="!isCompleted('materi') && isUnlocked('materi')">
                                <button type="button"
                                        @click="goToPage('materi')"
                                        class="px-4 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                    Pelajari →
                                </button>
                            </template>
                            <template x-if="!isUnlocked('materi')">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                    <span>🔒</span>
                                    <span>Terkunci</span>
                                </span>
                            </template>
                        </div>
                    </div>
                @endif

                {{-- Item: Multi-Video YouTube --}}
                @if($module->has_video)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">▶️</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Video & Resume YouTube</h4>
                                <p class="text-[11px] text-slate-500">Tonton video pembelajaran & kirim resume</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($videoSummary)
                                <button type="button"
                                        @click="goToPage('video')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>{{ $videoSummary->manual_score !== null ? 'Nilai: ' . $videoSummary->manual_score : 'Resume Terkirim' }}</span>
                                </button>
                            @else
                                <template x-if="isUnlocked('video')">
                                    <button type="button"
                                            @click="goToPage('video')"
                                            class="px-4 py-1.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                        Tonton →
                                    </button>
                                </template>
                                <template x-if="!isUnlocked('video')">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                        <span>🔒</span>
                                        <span>Terkunci</span>
                                    </span>
                                </template>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── 4. EVALUASI & PRAKTIK ── --}}
        <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 hover:shadow-md transition">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center font-black text-2xl text-violet-600 shrink-0">
                        4
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Evaluasi & Praktik</h3>
                        <p class="text-xs text-slate-500">Simulator Interaktif, Job Sheet & LKPD</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase px-3 py-1 rounded-full bg-violet-50 text-violet-700 border border-violet-100">
                    Praktik & Tugas
                </span>
            </div>

            <div class="space-y-3">
                {{-- Item: Simulator Embed --}}
                @if($module->has_embed)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">🎮</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Simulator Embed</h4>
                                <p class="text-[11px] text-slate-500">Praktik langsung & unggah bukti screenshot</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($embedSubmission)
                                <button type="button"
                                        @click="goToPage('embed')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>{{ $embedSubmission->manual_score !== null ? 'Nilai: ' . $embedSubmission->manual_score : 'Terkirim' }}</span>
                                </button>
                            @else
                                <template x-if="isUnlocked('embed')">
                                    <button type="button"
                                            @click="goToPage('embed')"
                                            class="px-4 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                        Praktik →
                                    </button>
                                </template>
                                <template x-if="!isUnlocked('embed')">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                        <span>🔒</span>
                                        <span>Terkunci</span>
                                    </span>
                                </template>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Item: Job Sheet --}}
                @if($module->has_job_sheet)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">📑</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Job Sheet Praktikum</h4>
                                <p class="text-[11px] text-slate-500">Unduh panduan & unggah laporan PDF</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($jobSheetSubmission)
                                <button type="button"
                                        @click="goToPage('job_sheet')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>{{ $jobSheetSubmission->manual_score !== null ? 'Nilai: ' . $jobSheetSubmission->manual_score : 'Terkirim' }}</span>
                                </button>
                            @else
                                <template x-if="isUnlocked('job_sheet')">
                                    <button type="button"
                                            @click="goToPage('job_sheet')"
                                            class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                        Tugas →
                                    </button>
                                </template>
                                <template x-if="!isUnlocked('job_sheet')">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                        <span>🔒</span>
                                        <span>Terkunci</span>
                                    </span>
                                </template>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Item: LKPD --}}
                @if($module->has_lkpd)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">📋</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Tugas LKPD</h4>
                                <p class="text-[11px] text-slate-500">Lembar kerja peserta didik & umpan balik</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($lkpdSubmission)
                                <button type="button"
                                        @click="goToPage('lkpd')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>{{ $lkpdSubmission->manual_score !== null ? 'Nilai: ' . $lkpdSubmission->manual_score : 'Terkirim' }}</span>
                                </button>
                            @else
                                <template x-if="isUnlocked('lkpd')">
                                    <button type="button"
                                            @click="goToPage('lkpd')"
                                            class="px-4 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                        Tugas →
                                    </button>
                                </template>
                                <template x-if="!isUnlocked('lkpd')">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                        <span>🔒</span>
                                        <span>Terkunci</span>
                                    </span>
                                </template>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── 5. BAGIAN AKHIR ── --}}
        <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 hover:shadow-md transition">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center font-black text-2xl text-rose-600 shrink-0">
                        5
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Bagian Akhir & Evaluasi Sumatif</h3>
                        <p class="text-xs text-slate-500">Post-test Penutup, Daftar Rujukan, dan Rekap Nilai Siswa</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100">
                    Evaluasi Akhir
                </span>
            </div>

            <div class="space-y-3">
                {{-- Item: Post-test --}}
                @if($module->has_post_test && $module->postTest)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-rose-50/70 border border-rose-200 hover:bg-rose-100/50 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">🏆</span>
                            <div>
                                <h4 class="text-xs font-bold text-rose-950">Post-test (Evaluasi Akhir)</h4>
                                <p class="text-[11px] text-rose-800">Uji pemahaman komprehensif setelah menuntaskan materi</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($studentResult && $studentResult->post_test_score !== null)
                                <button type="button"
                                        @click="goToPage('post_test')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-extrabold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Skor: {{ $studentResult->post_test_score }}</span>
                                </button>
                            @else
                                <template x-if="isUnlocked('post_test')">
                                    <button type="button"
                                            @click="goToPage('post_test')"
                                            class="px-4 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                                        Kerjakan Post-test →
                                    </button>
                                </template>
                                <template x-if="!isUnlocked('post_test')">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                        <span>🔒</span>
                                        <span>Terkunci</span>
                                    </span>
                                </template>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Item: Daftar Pustaka --}}
                @if($module->isInfoComponentActive('daftar_pustaka'))
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">📚</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Daftar Pustaka</h4>
                                <p class="text-[11px] text-slate-500">Rujukan buku referensi, standar kejuruan, dan modul</p>
                            </div>
                        </div>
                        <div>
                            <template x-if="isCompleted('daftar_pustaka')">
                                <button type="button"
                                        @click="goToPage('daftar_pustaka')"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-emerald-600 font-black text-sm">✓</span>
                                    <span>Selesai Dibaca</span>
                                </button>
                            </template>
                            <template x-if="!isCompleted('daftar_pustaka') && isUnlocked('daftar_pustaka')">
                                <button type="button"
                                        @click="goToPage('daftar_pustaka')"
                                        class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                                    Lihat →
                                </button>
                            </template>
                            <template x-if="!isUnlocked('daftar_pustaka')">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 border border-slate-200 text-xs font-medium cursor-not-allowed opacity-75">
                                    <span>🔒</span>
                                    <span>Terkunci</span>
                                </span>
                            </template>
                        </div>
                    </div>
                @endif

                {{-- Item: Rekap Nilai --}}
                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200 hover:bg-emerald-100/50 transition">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 flex items-center justify-center text-lg shrink-0">📊</span>
                        <div>
                            <h4 class="text-xs font-bold text-emerald-950">Rekapitulasi Nilai</h4>
                            <p class="text-[11px] text-emerald-800">Transparansi skor perolehan tugas mandiri & kuis evaluasi</p>
                        </div>
                    </div>
                    <button type="button"
                            @click="goToPage('rekap_nilai')"
                            class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                        Lihat Rekap →
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
