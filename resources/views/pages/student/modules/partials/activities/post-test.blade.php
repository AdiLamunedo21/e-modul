{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 12. POST-TEST (EVALUASI AKHIR MODUL) ══════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_post_test && $module->postTest)
@php
    $postTestAttempts = $studentResult?->getTestAttempts('post_test') ?? [];
    $postTestAttemptCount = $studentResult?->getTestAttemptCount('post_test') ?? 0;
    $latestPostRetakeScore = $studentResult?->getLatestRetakeScore('post_test');
    $initialPostScore = $studentResult?->post_test_score;
    $hasRetake = $latestPostRetakeScore !== null;
@endphp

<div x-show="activePage === 'post_test'" x-cloak class="w-full space-y-6 text-left"
     x-data="{ showRetakeForm: false, showHistory: false }">
    <div class="rounded-3xl bg-white border border-rose-200/90 shadow-sm overflow-hidden" id="section-post-test">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <span class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold shrink-0">🏆</span>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-600">Bagian 5 • Evaluasi Akhir</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Post-test Sumatif</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ $module->postTest->title ?? 'Post-test: Evaluasi Pemahaman' }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Durasi: {{ $module->postTest->duration_minutes ?? 20 }} Menit • Target KKTP: {{ $module->postTest->kktp ?? 75 }}</p>
                </div>
            </div>

            @if($initialPostScore !== null)
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <div class="bg-rose-50/90 px-3.5 py-1.5 rounded-2xl border border-rose-200 text-center">
                        <span class="text-[9px] font-bold text-rose-700 uppercase tracking-wider block">Nilai Awal (Resmi)</span>
                        <span class="text-xl font-black text-rose-900">{{ $initialPostScore }}/100</span>
                    </div>
                    @if($hasRetake)
                        <div class="bg-indigo-50/90 px-3.5 py-1.5 rounded-2xl border border-indigo-200 text-center">
                            <span class="text-[9px] font-bold text-indigo-700 uppercase tracking-wider block">Latihan Terakhir</span>
                            <span class="text-xl font-black text-indigo-900">{{ $latestPostRetakeScore }}/100</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-6 sm:p-8">
            @if($initialPostScore !== null)
                {{-- ═══ 1. CARD HASIL & PERBANDINGAN NILAI POST-TEST ═══ --}}
                <div x-show="!showRetakeForm" class="space-y-6">
                    <div class="rounded-3xl bg-gradient-to-br from-rose-50/70 via-pink-50/40 to-slate-50 border border-rose-200/90 p-6 sm:p-8 space-y-6">
                        
                        {{-- Header Status --}}
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                            <div class="w-14 h-14 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-2xl shrink-0 shadow-md shadow-rose-600/20">
                                🏆
                            </div>
                            <div class="space-y-1 flex-1">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <h3 class="text-xl font-black text-slate-900">Post-test Berhasil Diselesaikan!</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        {{ $postTestAttemptCount > 1 ? $postTestAttemptCount . 'x Dikerjakan' : 'Percobaan Pertama' }}
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
                                    Anda dapat mengerjakan ulang soal Post-test ini untuk mengukur kemajuan penguasaan materi. 
                                    <strong>Nilai awal resmi (percobaan pertama) tetap menjadi acuan permanen nilai akhir modul</strong>.
                                </p>
                            </div>
                        </div>

                        {{-- Panel Perbandingan Nilai Awal vs Latihan Pengulangan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            {{-- Box 1: Nilai Awal Resmi --}}
                            <div class="p-5 rounded-2xl bg-white border border-rose-200/80 shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Awal (Resmi)</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-100 text-rose-800 border border-rose-200 uppercase">
                                        Permanen / Penentu Nilai
                                    </span>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-rose-600">{{ $initialPostScore }}</span>
                                    <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                </div>
                                <div class="pt-1 border-t border-slate-100 text-[11px] text-slate-500 leading-normal">
                                    @if($studentResult->pre_test_score !== null)
                                        @php $deltaPrePost = $initialPostScore - $studentResult->pre_test_score; @endphp
                                        <span class="font-semibold {{ $deltaPrePost >= 0 ? 'text-emerald-700' : 'text-slate-600' }}">
                                            📊 Kemajuan dari Pre-test: {{ $deltaPrePost >= 0 ? '+' . $deltaPrePost : $deltaPrePost }} Poin
                                        </span>
                                    @else
                                        <span>📌 Nilai ini tersimpan pada rekapitulasi nilai akhir modul Anda.</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Box 2: Nilai Latihan Pengulangan Terbaru --}}
                            <div class="p-5 rounded-2xl bg-white border {{ $hasRetake ? 'border-indigo-200/90' : 'border-slate-200/80 border-dashed' }} shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Latihan Terakhir</span>
                                    @if($hasRetake)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase">
                                            Percobaan ke-{{ $postTestAttemptCount }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            Belum Diulang
                                        </span>
                                    @endif
                                </div>

                                @if($hasRetake)
                                    @php $deltaPost = $latestPostRetakeScore - $initialPostScore; @endphp
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black text-indigo-700">{{ $latestPostRetakeScore }}</span>
                                        <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                    </div>
                                    <div class="pt-1 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold">
                                        @if($deltaPost > 0)
                                            <span class="text-emerald-600 flex items-center gap-1">
                                                <span>📈</span>
                                                <span>Peningkatan: +{{ $deltaPost }} Poin dari nilai awal</span>
                                            </span>
                                        @elseif($deltaPost === 0)
                                            <span class="text-blue-600 flex items-center gap-1">
                                                <span>🎯</span>
                                                <span>Konsisten: Sama dengan nilai awal ({{ $initialPostScore }})</span>
                                            </span>
                                        @else
                                            <span class="text-amber-600 flex items-center gap-1">
                                                <span>📉</span>
                                                <span>Selisih: {{ $deltaPost }} Poin dari nilai awal</span>
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="py-2 text-xs text-slate-400 italic">
                                        Anda belum pernah mengulang soal Post-test ini. Klik tombol <strong>"Latihan Ulang Soal"</strong> di bawah untuk menguji kembali pemahaman Anda.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Riwayat Percobaan Lengkap (Collapsible jika > 1 kali) --}}
                        @if(count($postTestAttempts) > 1)
                            <div class="pt-2">
                                <button type="button"
                                        @click="showHistory = !showHistory"
                                        class="text-xs font-bold text-rose-700 hover:text-rose-900 inline-flex items-center gap-1.5 cursor-pointer">
                                    <span x-text="showHistory ? '▼ Sembunyikan Riwayat Percobaan' : '▶ Tampilkan Riwayat Percobaan (' + {{ count($postTestAttempts) }} + 'x pengerjaan)'"></span>
                                </button>
                                
                                <div x-show="showHistory" x-cloak class="mt-3 rounded-2xl bg-white border border-slate-200 overflow-hidden">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                                            <tr>
                                                <th class="py-2.5 px-4">Percobaan</th>
                                                <th class="py-2.5 px-4">Status Nilai</th>
                                                <th class="py-2.5 px-4 text-center">Skor</th>
                                                <th class="py-2.5 px-4 text-right">Waktu Pengerjaan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($postTestAttempts as $att)
                                                <tr class="{{ !empty($att['is_initial']) ? 'bg-rose-50/40 font-semibold' : '' }}">
                                                    <td class="py-2 px-4">Percobaan ke-{{ $att['attempt'] ?? 1 }}</td>
                                                    <td class="py-2 px-4">
                                                        @if(!empty($att['is_initial']))
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Nilai Awal Resmi</span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Latihan Ulang</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-4 text-center font-bold {{ !empty($att['is_initial']) ? 'text-rose-700' : 'text-slate-800' }}">
                                                        {{ $att['score'] ?? '-' }}/100
                                                    </td>
                                                    <td class="py-2 px-4 text-right text-slate-400 text-[11px]">
                                                        {{ !empty($att['timestamp']) ? \Carbon\Carbon::parse($att['timestamp'])->locale('id')->isoFormat('D MMM Y, HH:mm') : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- Action Buttons: Latihan Ulang & Lanjut Halaman --}}
                        <div class="pt-4 border-t border-rose-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <button type="button"
                                    @click="showRetakeForm = true"
                                    class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-white hover:bg-rose-50 text-rose-700 border border-rose-300 font-bold text-xs sm:text-sm shadow-2xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <span>🔄</span>
                                <span>Kerjakan Ulang Soal (Latihan Mandiri)</span>
                            </button>

                            <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs sm:text-sm font-bold">
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                                <span>Sudah Selesai Dikerjakan</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ═══ 2. FORM PENGERJAAN SOAL POST-TEST (TAMPIL JIKA BELUM PERNAH ATAU SEDANG RETAKE) ═══ --}}
            <div x-show="showRetakeForm || {{ $initialPostScore === null ? 'true' : 'false' }}">
                @if($initialPostScore !== null)
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">💡</span>
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <strong class="font-bold block text-sm mb-0.5">Mode Latihan Ulang Soal Post-Test</strong>
                                Anda sedang mengerjakan ulang soal sebagai pengayaan mandiri. 
                                <strong>Nilai awal resmi Anda ({{ $initialPostScore }}/100) tetap terkunci</strong> sebagai penentu nilai akhir modul. Hasil pengulangan ini dicatat sebagai perbandingan kemajuan belajar Anda.
                            </div>
                        </div>
                        <button type="button"
                                @click="showRetakeForm = false"
                                class="px-3 py-1.5 rounded-xl bg-white text-slate-700 hover:bg-slate-100 border border-amber-300 text-xs font-bold shrink-0 transition cursor-pointer">
                            ✕ Batal
                        </button>
                    </div>
                @endif

                @if($module->postTest->questions->isEmpty())
                    <div class="py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-300 space-y-2">
                        <span class="text-3xl">🏆</span>
                        <h4 class="text-sm font-bold text-slate-700">Belum Ada Soal Post-test</h4>
                        <p class="text-xs text-slate-500">Guru belum menambahkan butir soal untuk sesi evaluasi akhir modul ini.</p>
                    </div>
                @else
                    @php $postQuestionsCount = $module->postTest->questions->count(); @endphp

                    <div x-data="{
                            currentQuestion: 0,
                            totalQuestions: {{ $postQuestionsCount }},
                            answers: {},
                            showWarningToast: false,
                            warningToastTimer: null,
                            
                            init() {
                                this.$nextTick(() => {
                                    this.$el.querySelectorAll('input[type=radio]:checked').forEach(radio => {
                                        const match = radio.name.match(/answers\[(\d+)\]/);
                                        if (match) {
                                            this.answers[match[1]] = radio.value;
                                        }
                                    });
                                });
                            },
                            selectOption(questionId, optKey) {
                                this.answers[questionId] = optKey;
                            },
                            isAnswered(questionId) {
                                return !!this.answers[questionId];
                            },
                            get answeredCount() {
                                return Object.keys(this.answers).length;
                            },
                            jumpTo(index) {
                                if (index >= 0 && index < this.totalQuestions) {
                                    this.currentQuestion = index;
                                }
                            },
                            next() {
                                if (this.currentQuestion < this.totalQuestions - 1) {
                                    this.currentQuestion++;
                                }
                            },
                            prev() {
                                if (this.currentQuestion > 0) {
                                    this.currentQuestion--;
                                }
                            },
                            triggerAntiCopyWarning() {
                                this.showWarningToast = true;
                                if (this.warningToastTimer) clearTimeout(this.warningToastTimer);
                                this.warningToastTimer = setTimeout(() => {
                                    this.showWarningToast = false;
                                }, 3500);
                            },
                            handleKeyDown(e) {
                                if ((e.ctrlKey || e.metaKey) && ['c', 'C', 'a', 'A', 'x', 'X', 'u', 'U', 'p', 'P'].includes(e.key)) {
                                    e.preventDefault();
                                    this.triggerAntiCopyWarning();
                                }
                            },
                            attemptSubmit(formEl) {
                                const missing = this.totalQuestions - this.answeredCount;
                                let warningMsg = '{{ $initialPostScore !== null ? 'Nilai awal resmi Anda tetap terkunci (' . $initialPostScore . '/100). Skor kali ini dicatat sebagai perbandingan latihan.' : 'Post-test ini menentukan nilai akhir modul Anda.' }}';
                                
                                if (missing > 0) {
                                    warningMsg = '⚠️ Perhatian: Anda baru menjawab ' + this.answeredCount + ' dari ' + this.totalQuestions + ' soal (' + missing + ' soal belum dijawab). ' + warningMsg;
                                }

                                openSubmitModal({
                                    title: missing > 0 ? 'Kirim Jawaban (' + missing + ' Belum Terjawab)?' : '{{ $initialPostScore !== null ? 'Kirim Jawaban Latihan Ulang?' : 'Kirim Jawaban Post-test?' }}',
                                    description: missing > 0 
                                        ? 'Masih ada ' + missing + ' butir soal evaluasi yang belum Anda jawab. Apakah Anda yakin ingin mengirimkannya sekarang?'
                                        : 'Luar biasa! Anda telah menjawab seluruh ' + this.totalQuestions + ' butir soal post-test. Siap untuk mengumpulkan?',
                                    accentColor: 'rose',
                                    warningText: warningMsg,
                                    confirmLabel: '{{ $initialPostScore !== null ? 'Kirim Jawaban Latihan' : 'Kirim Jawaban Post-test' }}'
                                }, formEl);
                            }
                        }"
                        @keydown="handleKeyDown($event)"
                        @copy.prevent="triggerAntiCopyWarning()"
                        @cut.prevent="triggerAntiCopyWarning()"
                        @contextmenu.prevent="triggerAntiCopyWarning()"
                        @dragstart.prevent="triggerAntiCopyWarning()"
                        class="protected-exam-card relative space-y-6 select-none"
                        style="-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; -webkit-touch-callout: none;">

                        {{-- Floating Toast Anti-Cheat Warning --}}
                        <div x-show="showWarningToast"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-3 scale-95"
                             x-cloak
                             class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] max-w-md w-[92%] bg-slate-900/95 text-white backdrop-blur-md px-4 py-3 rounded-2xl shadow-2xl border border-rose-500/40 flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-300 flex items-center justify-center text-lg shrink-0 border border-rose-400/30">🛡️</span>
                            <div class="text-xs leading-relaxed">
                                <strong class="font-bold text-rose-300 block">Akses Salin Teks Dinonaktifkan</strong>
                                <span class="text-slate-200">Soal evaluasi akhir dilindungi anti-cheat. Teks tidak dapat disalin (copy) demi menjaga integritas penilaian akhir.</span>
                            </div>
                        </div>

                        {{-- Top Examination Status & Progress Bar --}}
                        <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-rose-50/80 to-slate-50 border border-rose-200/80 shadow-2xs space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-600 text-white text-xs font-black tracking-wide uppercase">
                                        <span>Soal</span>
                                        <span x-text="currentQuestion + 1"></span>
                                        <span>/</span>
                                        <span x-text="totalQuestions"></span>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-rose-200 text-[11px] font-bold text-rose-800 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        <span>Satu per Satu</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-[11px] font-bold text-slate-600 shadow-2xs">
                                        <span>🔒</span>
                                        <span>Anti-Copy Aktif</span>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-xs font-bold text-slate-500">Kemajuan Menjawab:</span>
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-black transition-colors"
                                          :class="answeredCount === totalQuestions ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100/80 text-rose-900 border border-rose-200'">
                                        <span x-text="answeredCount"></span> / <span x-text="totalQuestions"></span> Terjawab
                                    </span>
                                </div>
                            </div>

                            {{-- Progress Bar Soal Terbuka --}}
                            <div class="space-y-1">
                                <div class="w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-rose-500 to-pink-500 h-2 rounded-full transition-all duration-300"
                                         :style="'width: ' + Math.round(((currentQuestion + 1) / totalQuestions) * 100) + '%'"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-slate-400 font-bold px-0.5">
                                    <span>Langkah Soal <span x-text="currentQuestion + 1"></span></span>
                                    <span x-text="Math.round(((currentQuestion + 1) / totalQuestions) * 100) + '%'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Peta Navigasi Nomor Soal (Interactive Quick-Jump Grid) --}}
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>🧭</span>
                                    <span>Peta Nomor Soal:</span>
                                </span>
                                <div class="flex items-center gap-3 text-[11px] text-slate-500">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-2.5 h-2.5 rounded bg-rose-600"></span>
                                        <span>Terjawab</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-2.5 h-2.5 rounded bg-white border border-slate-300"></span>
                                        <span>Belum</span>
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 pt-1">
                                @foreach($module->postTest->questions as $idx => $q)
                                    <button type="button"
                                            @click="jumpTo({{ $idx }})"
                                            :class="{
                                                'ring-2 ring-rose-500 ring-offset-2 border-rose-600 scale-105 font-black shadow-sm': currentQuestion === {{ $idx }},
                                                'bg-rose-600 text-white border-rose-600': isAnswered('{{ $q->id }}') && currentQuestion !== {{ $idx }},
                                                'bg-white text-slate-700 border-slate-200 hover:border-rose-300 hover:bg-rose-50/30': !isAnswered('{{ $q->id }}') && currentQuestion !== {{ $idx }},
                                                'bg-rose-600 text-white': isAnswered('{{ $q->id }}') && currentQuestion === {{ $idx }}
                                            }"
                                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl border text-xs font-bold transition-all duration-150 flex items-center justify-center cursor-pointer select-none">
                                        <span>{{ $idx + 1 }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Form Utama dengan Kontainer Soal Satu per Satu --}}
                        <form action="{{ route('student.modules.post-test.submit', $module) }}" method="POST" class="space-y-6">
                            @csrf

                            @foreach($module->postTest->questions as $idx => $q)
                                <div x-show="currentQuestion === {{ $idx }}"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-cloak
                                     class="p-6 sm:p-7 rounded-3xl bg-slate-50 border border-slate-200/90 shadow-2xs space-y-6">
                                    
                                    {{-- Header Butir Soal --}}
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-200/80 gap-3">
                                        <div class="flex items-center gap-2">
                                            <span class="w-8 h-8 rounded-xl bg-rose-600 text-white text-xs font-black flex items-center justify-center shadow-xs">
                                                {{ $idx + 1 }}
                                            </span>
                                            <div>
                                                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Butir Soal Nomor {{ $idx + 1 }}</h4>
                                                <span class="text-[10px] text-slate-400 font-medium">Post-test Sumatif</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-[11px] font-bold text-slate-600">
                                                Bobot: {{ $q->score_weight ?: 10 }} Poin
                                            </span>
                                            <span x-show="isAnswered('{{ $q->id }}')"
                                                  class="px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-[11px] font-bold text-emerald-700">
                                                ✓ Terjawab
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Teks Soal (Dilindungi Anti-Copy & Anti-Drag) --}}
                                    <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
                                        <p class="text-sm sm:text-base font-bold text-slate-900 leading-relaxed select-none pointer-events-none"
                                           style="-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;">
                                            {{ $q->question_text }}
                                        </p>
                                    </div>

                                    {{-- Pilihan Ganda (A, B, C, D, E) --}}
                                    <div class="space-y-3">
                                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 block px-1">
                                            Pilihan Jawaban:
                                        </span>
                                        <div class="grid grid-cols-1 gap-2.5">
                                            @foreach(['A', 'B', 'C', 'D', 'E'] as $optKey)
                                                @if(!empty($q->options[$optKey]))
                                                    <label @click="selectOption('{{ $q->id }}', '{{ $optKey }}')"
                                                           :class="answers['{{ $q->id }}'] === '{{ $optKey }}'
                                                                ? 'border-rose-500 bg-rose-50/70 ring-2 ring-rose-500/25 shadow-xs'
                                                                : 'border-slate-200 bg-white hover:border-rose-300 hover:bg-slate-50/80'"
                                                           class="flex items-center gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 cursor-pointer transition-all duration-150 select-none group">
                                                        <input type="radio"
                                                               name="answers[{{ $q->id }}]"
                                                               value="{{ $optKey }}"
                                                               x-model="answers['{{ $q->id }}']"
                                                               class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-slate-300">
                                                        <span :class="answers['{{ $q->id }}'] === '{{ $optKey }}'
                                                                    ? 'bg-rose-600 text-white shadow-xs'
                                                                    : 'bg-slate-100 text-slate-700 group-hover:bg-rose-100 group-hover:text-rose-800'"
                                                              class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black shrink-0 transition-colors">
                                                            {{ $optKey }}
                                                        </span>
                                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 leading-relaxed flex-1 select-none pointer-events-none">
                                                            {{ $q->options[$optKey] }}
                                                        </span>
                                                        <span x-show="answers['{{ $q->id }}'] === '{{ $optKey }}'"
                                                              class="text-xs font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded-md shrink-0">
                                                            ✓ Dipilih
                                                        </span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Bottom Navigation Bar (Prev, Next, Finish) --}}
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-200">
                                <div class="w-full sm:w-auto flex items-center gap-2">
                                    <button type="button"
                                            @click="prev()"
                                            :disabled="currentQuestion === 0"
                                            :class="currentQuestion === 0 ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 cursor-pointer shadow-2xs'"
                                            class="flex-1 sm:flex-initial px-5 py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5">
                                        <span>←</span>
                                        <span>Soal Sebelumnya</span>
                                    </button>

                                    <button type="button"
                                            @click="next()"
                                            x-show="currentQuestion < totalQuestions - 1"
                                            class="flex-1 sm:flex-initial px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                                        <span>Soal Berikutnya</span>
                                        <span>→</span>
                                    </button>
                                </div>

                                <div class="w-full sm:w-auto flex items-center gap-2 justify-end">
                                    @if($initialPostScore !== null)
                                        <button type="button"
                                                @click="showRetakeForm = false"
                                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition cursor-pointer">
                                            Batal
                                        </button>
                                    @endif

                                    <button type="button"
                                            @click="attemptSubmit($el.closest('form'))"
                                            :class="currentQuestion === totalQuestions - 1 ? 'ring-4 ring-rose-500/20' : ''"
                                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white font-black text-xs shadow-md shadow-rose-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
                                        <span>🏁</span>
                                        <span>{{ $initialPostScore !== null ? 'Kirim Jawaban Latihan' : 'Kirim Jawaban Post-test' }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<style>
    @media print {
        .protected-exam-card { display: none !important; }
    }
</style>

