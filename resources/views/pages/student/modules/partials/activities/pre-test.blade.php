{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 6. PRE-TEST (SOAL LATIHAN DIAGNOSTIK) ═════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_pre_test && $module->preTest)
@php
    $preTestAttempts = $studentResult?->getTestAttempts('pre_test') ?? [];
    $preTestAttemptCount = $studentResult?->getTestAttemptCount('pre_test') ?? 0;
    $latestPreRetakeScore = $studentResult?->getLatestRetakeScore('pre_test');
    $initialPreScore = $studentResult?->pre_test_score;
    $hasRetake = $latestPreRetakeScore !== null;
@endphp

<div x-show="activePage === 'pre_test'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-teal-200/90 shadow-sm overflow-hidden" id="section-pre-test">
        {{-- Sembunyikan Header Informasi ini saat sedang mengerjakan soal agar fokus --}}
        <div x-show="!isTakingPreTest" class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <span class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-2xl font-bold shrink-0">⚡</span>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-600">Bagian {{ $secMap[2] ?? 2 }} • Latihan Diagnostik</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">Pre-test Diagnostik</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ $module->preTest->title ?? 'Pre-test Pembuka' }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $module->preTest->questionCount() }} Butir Soal • Target KKTP: {{ $module->preTest->kktp ?? 75 }}</p>
                </div>
            </div>

            @if($initialPreScore !== null)
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <div class="bg-teal-50/90 px-3.5 py-1.5 rounded-2xl border border-teal-200 text-center">
                        <span class="text-[9px] font-bold text-teal-700 uppercase tracking-wider block">Nilai Awal (Resmi)</span>
                        <span class="text-xl font-black text-teal-900">{{ $initialPreScore }}/100</span>
                    </div>
                    @if($hasRetake)
                        <div class="bg-indigo-50/90 px-3.5 py-1.5 rounded-2xl border border-indigo-200 text-center">
                            <span class="text-[9px] font-bold text-indigo-700 uppercase tracking-wider block">Latihan Terakhir</span>
                            <span class="text-xl font-black text-indigo-900">{{ $latestPreRetakeScore }}/100</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div :class="isTakingPreTest ? 'p-2 sm:p-6' : 'p-6 sm:p-8'">
            @if($initialPreScore !== null)
                {{-- ═══ 1. CARD HASIL & PERBANDINGAN NILAI (TAMPIL JIKA TIDAK SEDANG RETAKE) ═══ --}}
                <div x-show="!showPreRetakeForm" class="space-y-6">
                    <div class="rounded-3xl bg-gradient-to-br from-teal-50/60 via-emerald-50/40 to-slate-50 border border-teal-200/90 p-6 sm:p-8 space-y-6">
                        
                        {{-- Header Status --}}
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl shrink-0 shadow-md shadow-emerald-500/20">
                                ✓
                            </div>
                            <div class="space-y-1 flex-1">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <h3 class="text-xl font-black text-slate-900">Pre-test Diagnostik Selesai!</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        {{ $preTestAttemptCount > 1 ? $preTestAttemptCount . 'x Dikerjakan' : 'Percobaan Pertama' }}
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
                                    Soal Pre-test ini dapat Anda kerjakan ulang secara bebas untuk melatih dan memperdalam pemahaman materi. 
                                    <strong>Nilai awal resmi Anda tetap dikunci dan tidak akan berubah</strong>.
                                </p>
                            </div>
                        </div>

                        {{-- Panel Perbandingan Nilai Awal vs Latihan Pengulangan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            {{-- Box 1: Nilai Awal Resmi --}}
                            <div class="p-5 rounded-2xl bg-white border border-teal-200/80 shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Awal (Resmi)</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-teal-100 text-teal-800 border border-teal-200 uppercase">
                                        Permanen / Terkunci
                                    </span>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-teal-700">{{ $initialPreScore }}</span>
                                    <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-normal pt-1 border-t border-slate-100">
                                    📌 Diperoleh pada percobaan pertama. Nilai ini menjadi rekam jejak awal kemampuan diagnostik Anda.
                                </p>
                            </div>

                            {{-- Box 2: Nilai Latihan Pengulangan Terbaru --}}
                            <div class="p-5 rounded-2xl bg-white border {{ $hasRetake ? 'border-indigo-200/90' : 'border-slate-200/80 border-dashed' }} shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Latihan Terakhir</span>
                                    @if($hasRetake)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase">
                                            Percobaan ke-{{ $preTestAttemptCount }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            Belum Diulang
                                        </span>
                                    @endif
                                </div>

                                @if($hasRetake)
                                    @php $deltaPre = $latestPreRetakeScore - $initialPreScore; @endphp
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black text-indigo-700">{{ $latestPreRetakeScore }}</span>
                                        <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                    </div>
                                    <div class="pt-1 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold">
                                        @if($deltaPre > 0)
                                            <span class="text-emerald-600 flex items-center gap-1">
                                                <span>📈</span>
                                                <span>Perkembangan: +{{ $deltaPre }} Poin dari nilai awal</span>
                                            </span>
                                        @elseif($deltaPre === 0)
                                            <span class="text-blue-600 flex items-center gap-1">
                                                <span>🎯</span>
                                                <span>Konsisten: Sama dengan nilai awal ({{ $initialPreScore }})</span>
                                            </span>
                                        @else
                                            <span class="text-amber-600 flex items-center gap-1">
                                                <span>📉</span>
                                                <span>Selisih: {{ $deltaPre }} Poin dari nilai awal</span>
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="py-2 text-xs text-slate-400 italic">
                                        Anda belum pernah mengulang soal ini. Klik tombol <strong>"Latihan Ulang Soal"</strong> di bawah untuk menguji kembali kemampuan Anda.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Riwayat Percobaan Lengkap (Collapsible jika > 1 kali) --}}
                        @if(count($preTestAttempts) > 1)
                            <div class="pt-2">
                                <button type="button"
                                        @click="showPreHistory = !showPreHistory"
                                        class="text-xs font-bold text-teal-700 hover:text-teal-900 inline-flex items-center gap-1.5 cursor-pointer">
                                    <span x-text="showPreHistory ? '▼ Sembunyikan Riwayat Percobaan' : '▶ Tampilkan Riwayat Percobaan (' + {{ count($preTestAttempts) }} + 'x pengerjaan)'"></span>
                                </button>
                                
                                <div x-show="showPreHistory" x-cloak class="mt-3 rounded-2xl bg-white border border-slate-200 overflow-hidden">
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
                                            @foreach($preTestAttempts as $att)
                                                <tr class="{{ !empty($att['is_initial']) ? 'bg-teal-50/40 font-semibold' : '' }}">
                                                    <td class="py-2 px-4">Percobaan ke-{{ $att['attempt'] ?? 1 }}</td>
                                                    <td class="py-2 px-4">
                                                        @if(!empty($att['is_initial']))
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800">Nilai Awal Resmi</span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Latihan Ulang</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-4 text-center font-bold {{ !empty($att['is_initial']) ? 'text-teal-700' : 'text-slate-800' }}">
                                                        {{ $att['score'] ?? '-' }}/100
                                                    </td>
                                                    <td class="py-2 px-4 text-right text-slate-400 text-[11px]">
                                                        {{ !empty($att['timestamp']) ? \Carbon\Carbon::parse($att['timestamp'])->locale('id')->isoFormat('D MMM Y, HH:mm') : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="px-4 py-2 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                                        <span>💾 Riwayat dibatasi maksimal 3 percobaan pengerjaan</span>
                                        <span class="font-bold text-teal-700">Tersimpan: {{ count($preTestAttempts) }}/3</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Action Buttons: Latihan Ulang & Lanjut Halaman --}}
                        <div class="pt-4 border-t border-teal-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <button type="button"
                                    @click="showPreRetakeForm = true"
                                    class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-white hover:bg-teal-50 text-teal-700 border border-teal-300 font-bold text-xs sm:text-sm shadow-2xs transition flex items-center justify-center gap-2 cursor-pointer">
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

            {{-- ═══ 2. FORM PENGERJAAN SOAL (TAMPIL SAAT MENGERJAKAN SOAL) ═══ --}}
            <div x-show="isTakingPreTest">

                @if($module->preTest->questions->isEmpty())
                    <div class="py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-300 space-y-2">
                        <span class="text-3xl">📝</span>
                        <h4 class="text-sm font-bold text-slate-700">Belum Ada Soal Pre-test</h4>
                        <p class="text-xs text-slate-500">Guru belum menambahkan butir soal untuk sesi latihan diagnostik ini.</p>
                    </div>
                @else
                    @php
                        $preQuestionsCount = $module->preTest->questions->count();
                        $preQuestionsTimeLimits = [];
                        $preQuestionsIds = [];
                        foreach ($module->preTest->questions as $qIdx => $qItem) {
                            $preQuestionsTimeLimits[$qIdx] = (int) ($qItem->time_limit_seconds ?: 0);
                            $preQuestionsIds[$qIdx] = (string) $qItem->id;
                        }
                    @endphp

                    <div x-data="{
                            currentQuestion: 0,
                            totalQuestions: {{ $preQuestionsCount }},
                            questionIds: {{ json_encode($preQuestionsIds) }},
                            answers: {},
                            showWarningToast: false,
                            warningToastTimer: null,

                            // Per-question timer state
                            timeLimits: {{ json_encode($preQuestionsTimeLimits) }},
                            timeLeft: 0,
                            initialTime: 0,
                            timerInterval: null,
                            isPaused: false,
                            timeExpiredToast: false,
                            timeExpiredTimer: null,
                            expiredQuestions: {},
                            
                            init() {
                                this.$nextTick(() => {
                                    this.$el.querySelectorAll('input[type=radio]:checked').forEach(radio => {
                                        const match = radio.name.match(/answers\[(\d+)\]/);
                                        if (match) {
                                            this.answers[match[1]] = radio.value;
                                        }
                                    });
                                    this.startQuestionTimer();
                                });
                                this.$watch('submitModal.open', val => {
                                    this.isPaused = !!val;
                                });
                            },
                            getCurrentAllottedTime() {
                                return this.timeLimits[this.currentQuestion] || 0;
                            },
                            startQuestionTimer() {
                                if (this.timerInterval) clearInterval(this.timerInterval);
                                const allotted = this.getCurrentAllottedTime();
                                if (allotted > 0 && !this.expiredQuestions[this.currentQuestion]) {
                                    this.initialTime = allotted;
                                    this.timeLeft = allotted;
                                    this.isPaused = false;
                                    this.timerInterval = setInterval(() => {
                                        if (this.isPaused) return;
                                        if (this.timeLeft > 1) {
                                            this.timeLeft--;
                                        } else {
                                            this.timeLeft = 0;
                                            clearInterval(this.timerInterval);
                                            this.onQuestionTimeExpired();
                                        }
                                    }, 1000);
                                } else {
                                    this.timeLeft = 0;
                                    this.initialTime = 0;
                                }
                            },
                            onQuestionTimeExpired() {
                                this.expiredQuestions[this.currentQuestion] = true;
                                this.timeExpiredToast = true;
                                if (this.timeExpiredTimer) clearTimeout(this.timeExpiredTimer);
                                this.timeExpiredTimer = setTimeout(() => {
                                    this.timeExpiredToast = false;
                                }, 3500);

                                if (this.currentQuestion < this.totalQuestions - 1) {
                                    this.next(true);
                                } else {
                                    // Auto submit on last question
                                    const formEl = this.$el.querySelector('form');
                                    if (formEl) {
                                        formEl.submit();
                                    }
                                }
                            },
                            formatTime(seconds) {
                                const m = Math.floor(seconds / 60);
                                const s = seconds % 60;
                                return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                            },
                            get timerPercentage() {
                                if (!this.initialTime || this.initialTime <= 0) return 100;
                                return Math.max(0, Math.min(100, Math.round((this.timeLeft / this.initialTime) * 100)));
                            },
                            selectOption(questionId, optKey, qIndex) {
                                if (this.expiredQuestions[qIndex]) return;
                                if (qIndex !== this.currentQuestion) return;
                                this.answers[questionId] = optKey;
                            },
                            isAnswered(questionId) {
                                return !!this.answers[questionId];
                            },
                            isCurrentQuestionAnswered() {
                                const qId = this.questionIds[this.currentQuestion];
                                return !!(qId && this.answers[qId]);
                            },
                            get answeredCount() {
                                return Object.keys(this.answers).length;
                            },
                            next(force = false) {
                                if (!force && !this.isCurrentQuestionAnswered()) {
                                    return;
                                }
                                if (this.currentQuestion < this.totalQuestions - 1) {
                                    this.currentQuestion++;
                                    this.startQuestionTimer();
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
                                this.isPaused = true;
                                const missing = this.totalQuestions - this.answeredCount;
                                let warningMsg = '{{ $initialPreScore !== null ? 'Nilai awal resmi Anda tetap terkunci (' . $initialPreScore . '/100). Skor kali ini dicatat sebagai perbandingan latihan.' : 'Nilai pertama kali ini akan disimpan sebagai nilai awal resmi diagnostik.' }}';
                                
                                if (missing > 0) {
                                    warningMsg = '⚠️ Perhatian: Anda baru menjawab ' + this.answeredCount + ' dari ' + this.totalQuestions + ' soal (' + missing + ' soal belum dijawab). ' + warningMsg;
                                }

                                openSubmitModal({
                                    title: missing > 0 ? 'Kirim Jawaban (' + missing + ' Belum Terjawab)?' : '{{ $initialPreScore !== null ? 'Kirim Jawaban Latihan Ulang?' : 'Kirim Jawaban Pre-test?' }}',
                                    description: missing > 0 
                                        ? 'Masih ada ' + missing + ' butir soal yang belum Anda jawab. Apakah Anda yakin ingin mengirimkannya sekarang?'
                                        : 'Luar biasa! Anda telah menjawab seluruh ' + this.totalQuestions + ' butir soal. Siap untuk mengumpulkan?',
                                    accentColor: 'teal',
                                    warningText: warningMsg,
                                    confirmLabel: '{{ $initialPreScore !== null ? 'Kirim Jawaban Latihan' : 'Kirim Jawaban Pre-test' }}'
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

                        {{-- Floating Toast Waktu Habis Per Soal --}}
                        <div x-show="timeExpiredToast"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-3 scale-95"
                             x-cloak
                             class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] max-w-md w-[92%] bg-amber-950/95 text-white backdrop-blur-md px-4 py-3 rounded-2xl shadow-2xl border border-amber-500/40 flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center text-lg shrink-0 border border-amber-400/30">⏰</span>
                            <div class="text-xs leading-relaxed">
                                <strong class="font-bold text-amber-300 block">Waktu Soal Telah Habis!</strong>
                                Waktu pengerjaan butir soal ini telah selesai. Anda otomatis dialihkan ke butir soal berikutnya.
                            </div>
                        </div>

                        {{-- Floating Toast Anti-Cheat Warning --}}
                        <div x-show="showWarningToast"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-3 scale-95"
                             x-cloak
                             class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] max-w-md w-[92%] bg-slate-900/95 text-white backdrop-blur-md px-4 py-3 rounded-2xl shadow-2xl border border-teal-500/40 flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center text-lg shrink-0 border border-teal-400/30">🛡️</span>
                            <div class="text-xs leading-relaxed">
                                <strong class="font-bold text-teal-300 block">Akses Salin Teks Dinonaktifkan</strong>
                                <span class="text-slate-200">Soal ujian dilindungi anti-cheat. Teks tidak dapat disalin (copy) demi menjaga kejujuran dan integritas evaluasi.</span>
                            </div>
                        </div>

                        {{-- Top Examination Status & Progress Bar --}}
                        <div class="p-3 sm:p-4 rounded-2xl bg-teal-50/60 border border-teal-200/60 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-teal-600 text-white text-xs font-black tracking-wide uppercase">
                                        <span>Soal</span>
                                        <span x-text="currentQuestion + 1"></span>
                                        <span>/</span>
                                        <span x-text="totalQuestions"></span>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-teal-200 text-[11px] font-bold text-teal-800 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                                        <span>Satu per Satu</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-[11px] font-bold text-slate-600 shadow-2xs">
                                        <span>🔒</span>
                                        <span>Anti-Copy Aktif</span>
                                    </span>
                                    @if($initialPreScore !== null)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-100 border border-amber-300 text-[11px] font-bold text-amber-900 shadow-2xs">
                                            <span>🔄</span>
                                            <span>Mode Latihan</span>
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    @if($initialPreScore !== null)
                                        <button type="button"
                                                @click="showPreRetakeForm = false"
                                                class="text-xs font-bold text-slate-500 hover:text-rose-600 underline cursor-pointer transition">
                                            ✕ Batalkan Latihan
                                        </button>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-500">Kemajuan Menjawab:</span>
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-black transition-colors"
                                              :class="answeredCount === totalQuestions ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-teal-100/80 text-teal-900 border border-teal-200'">
                                            <span x-text="answeredCount"></span> / <span x-text="totalQuestions"></span> Terjawab
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Progress Bar Soal Terbuka --}}
                            <div class="space-y-1">
                                <div class="w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-2 rounded-full transition-all duration-300"
                                         :style="'width: ' + Math.round(((currentQuestion + 1) / totalQuestions) * 100) + '%'"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-slate-400 font-bold px-0.5">
                                    <span>Langkah Soal <span x-text="currentQuestion + 1"></span></span>
                                    <span x-text="Math.round(((currentQuestion + 1) / totalQuestions) * 100) + '%'"></span>
                                </div>
                            </div>
                        </div>


                        {{-- Form Utama dengan Kontainer Soal Satu per Satu --}}
                        <form action="{{ route('student.modules.pre-test.submit', $module) }}" method="POST" class="space-y-6 pb-24 lg:pb-0" id="pre-test-form">
                            @csrf

                            @foreach($module->preTest->questions as $idx => $q)
                                <div x-show="currentQuestion === {{ $idx }}"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-cloak
                                     class="p-3 sm:p-6 rounded-2xl sm:rounded-3xl bg-slate-50/60 border-0 sm:border sm:border-slate-200/70 space-y-5 sm:space-y-6">
                                    
                                    {{-- Header Butir Soal --}}
                                    <div class="flex flex-wrap items-center justify-between pb-3 border-b border-slate-200/80 gap-2 sm:gap-3">
                                        <div class="flex items-center gap-2">
                                            <span class="w-8 h-8 rounded-xl bg-teal-600 text-white text-xs font-black flex items-center justify-center shadow-xs shrink-0">
                                                {{ $idx + 1 }}
                                            </span>
                                            <div>
                                                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Butir Soal Nomor {{ $idx + 1 }}</h4>
                                                <span class="text-[10px] text-slate-400 font-medium">Pre-test Diagnostik</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                                            {{-- Countdown Timer Soal Ini --}}
                                            <template x-if="getCurrentAllottedTime() > 0">
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-black border transition-all duration-200 shadow-2xs"
                                                     :class="{
                                                         'bg-red-50 text-red-600 border-red-300 animate-pulse ring-2 ring-red-500/20': timeLeft <= 5,
                                                         'bg-amber-50 text-amber-700 border-amber-300': timeLeft > 5 && timeLeft <= 15,
                                                         'bg-teal-50 text-teal-800 border-teal-200': timeLeft > 15
                                                     }"
                                                     title="Sisa waktu untuk mengerjakan butir soal ini">
                                                    <svg class="w-3.5 h-3.5 shrink-0" :class="timeLeft <= 5 ? 'animate-bounce text-red-600' : 'text-current'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-[11px] font-semibold hidden sm:inline">Sisa:</span>
                                                    <span class="font-mono text-xs font-extrabold" x-text="formatTime(timeLeft)"></span>
                                                </div>
                                            </template>

                                            <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-[11px] font-bold text-slate-600">
                                                Bobot: {{ $q->score_weight ?: 10 }} Poin
                                            </span>
                                            <span x-show="expiredQuestions[{{ $idx }}]"
                                                  class="px-2.5 py-1 rounded-lg bg-rose-50 border border-rose-200 text-[11px] font-bold text-rose-700">
                                                ⏰ Waktu Habis
                                            </span>
                                            <span x-show="isAnswered('{{ $q->id }}') && !expiredQuestions[{{ $idx }}]"
                                                  class="px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-[11px] font-bold text-emerald-700">
                                                ✓ Terjawab
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Countdown Timer Slim Progress Bar --}}
                                    <template x-if="getCurrentAllottedTime() > 0">
                                        <div class="w-full bg-slate-200/70 rounded-full h-1.5 overflow-hidden -mt-3">
                                            <div class="h-1.5 rounded-full transition-all duration-1000 ease-linear"
                                                 :class="{
                                                     'bg-red-500 animate-pulse': timeLeft <= 5,
                                                     'bg-amber-500': timeLeft > 5 && timeLeft <= 15,
                                                     'bg-teal-500': timeLeft > 15
                                                 }"
                                                 :style="'width: ' + timerPercentage + '%'"></div>
                                        </div>
                                    </template>

                                    {{-- Teks Soal (Tanpa border dalam agar teks mobile leluasa) --}}
                                    <div class="py-1 px-1 sm:px-2">
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
                                                    <label @click="selectOption('{{ $q->id }}', '{{ $optKey }}', {{ $idx }})"
                                                           :class="[
                                                                expiredQuestions[{{ $idx }}] ? 'opacity-60 cursor-not-allowed pointer-events-none bg-slate-100 border-slate-200' : '',
                                                                answers['{{ $q->id }}'] === '{{ $optKey }}'
                                                                    ? 'border-teal-500 bg-teal-50/70 ring-2 ring-teal-500/25 shadow-xs'
                                                                    : 'border-slate-200 bg-white hover:border-teal-300 hover:bg-slate-50/80'
                                                           ]"
                                                           class="flex items-center gap-2.5 sm:gap-3.5 p-3 sm:p-4 rounded-2xl border cursor-pointer transition-all duration-150 select-none group">
                                                        <input type="radio"
                                                               name="answers[{{ $q->id }}]"
                                                               value="{{ $optKey }}"
                                                               :disabled="expiredQuestions[{{ $idx }}]"
                                                               x-model="answers['{{ $q->id }}']"
                                                               class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-slate-300">
                                                        <span :class="answers['{{ $q->id }}'] === '{{ $optKey }}'
                                                                    ? 'bg-teal-600 text-white shadow-xs'
                                                                    : 'bg-slate-100 text-slate-700 group-hover:bg-teal-100 group-hover:text-teal-800'"
                                                              class="w-7 h-7 rounded-xl flex items-center justify-center text-xs font-black shrink-0 transition-colors">
                                                            {{ $optKey }}
                                                        </span>
                                                        <span class="text-xs sm:text-sm font-semibold text-slate-800 leading-relaxed flex-1 select-none pointer-events-none">
                                                            {{ $q->options[$optKey] }}
                                                        </span>
                                                        <span x-show="answers['{{ $q->id }}'] === '{{ $optKey }}'"
                                                              class="text-xs font-bold text-teal-700 bg-teal-100 px-2 py-0.5 rounded-md shrink-0">
                                                            ✓ Dipilih
                                                        </span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Bottom Navigation Bar (Sequential Progression) -- TAMPIL DI DESKTOP, DI MOBILE PINDAH KE NAV BAWAH --}}
                            <div class="hidden lg:flex pt-3 items-center justify-between gap-3 border-t border-slate-200">
                                <div class="w-full sm:w-auto flex items-center gap-2 text-xs text-slate-500 font-medium">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-[11px] text-slate-600 font-semibold">
                                        <span>🔒</span>
                                        <span>Alur Satu Arah: Soal tidak dapat diulang setelah dijawab</span>
                                    </span>
                                </div>

                                <div class="w-full sm:w-auto flex items-center gap-2 justify-end">
                                    @if($initialPreScore !== null)
                                        <button type="button"
                                                @click="showPreRetakeForm = false"
                                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition cursor-pointer">
                                            Batal
                                        </button>
                                    @endif

                                    {{-- Tombol Lanjut ke Soal Berikutnya (Hanya jika belum di soal terakhir) --}}
                                    <template x-if="currentQuestion < totalQuestions - 1">
                                        <button type="button"
                                                @click="next()"
                                                :disabled="!isCurrentQuestionAnswered()"
                                                :style="isCurrentQuestionAnswered()
                                                    ? 'background: linear-gradient(135deg, #0d9488 0%, #059669 100%) !important; color: #ffffff !important;'
                                                    : 'background-color: #f1f5f9 !important; color: #64748b !important; border: 1px solid #cbd5e1 !important;'"
                                                :class="isCurrentQuestionAnswered()
                                                    ? 'shadow-md shadow-teal-600/25 cursor-pointer hover:opacity-95'
                                                    : 'cursor-not-allowed opacity-80'"
                                                class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-2">
                                            <span x-show="!isCurrentQuestionAnswered()" style="color: #64748b !important;">⚠️ Pilih Jawaban untuk Lanjut</span>
                                            <span x-show="isCurrentQuestionAnswered()" style="color: #ffffff !important;" class="flex items-center gap-1.5 font-bold">
                                                <span>Simpan & Lanjut ke Soal Berikutnya</span>
                                                <span>→</span>
                                            </span>
                                        </button>
                                    </template>

                                    {{-- Tombol Selesai & Kirim Ujian (Hanya muncul di soal terakhir) --}}
                                    <template x-if="currentQuestion === totalQuestions - 1">
                                        <button type="button"
                                                @click="attemptSubmit(document.getElementById('pre-test-form'))"
                                                :disabled="!isCurrentQuestionAnswered()"
                                                :style="isCurrentQuestionAnswered()
                                                    ? 'background: linear-gradient(135deg, #0d9488 0%, #059669 100%) !important; color: #ffffff !important;'
                                                    : 'background-color: #f1f5f9 !important; color: #64748b !important; border: 1px solid #cbd5e1 !important;'"
                                                :class="isCurrentQuestionAnswered()
                                                    ? 'shadow-md shadow-teal-600/25 cursor-pointer ring-4 ring-teal-500/20 hover:opacity-95'
                                                    : 'cursor-not-allowed opacity-80'"
                                                class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-black text-xs transition-all flex items-center justify-center gap-2">
                                            <span>🏁</span>
                                            <span x-show="!isCurrentQuestionAnswered()" style="color: #64748b !important;">Pilih Jawaban Terakhir</span>
                                            <span x-show="isCurrentQuestionAnswered()" style="color: #ffffff !important;" class="font-black">{{ $initialPreScore !== null ? 'Kirim Jawaban Latihan' : 'Kirim Jawaban Pre-test' }}</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </form>

                        {{-- ══ MOBILE FIXED BOTTOM ACTION BAR (KHUSUS SMARTPHONE / TABLET) ══ --}}
                        <div class="fixed bottom-0 inset-x-0 z-40 lg:hidden bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(15,23,42,0.12)] px-3 py-2.5 transition-all select-none"
                             style="padding-bottom: max(0.6rem, env(safe-area-inset-bottom));">
                            <div class="max-w-md mx-auto flex items-center justify-between gap-2">
                                {{-- Tombol Batal jika sedang mode Latihan Ulang --}}
                                @if($initialPreScore !== null)
                                    <button type="button"
                                            @click="showPreRetakeForm = false"
                                            class="py-2.5 px-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all active:scale-95 shrink-0 shadow-2xs">
                                        Batal
                                    </button>
                                @endif

                                {{-- Indikator Soal Aktif --}}
                                <div class="py-2 px-2.5 rounded-2xl bg-slate-100/90 text-slate-700 text-xs font-bold flex items-center gap-1 shrink-0">
                                    <span>Soal</span>
                                    <span class="font-black text-teal-700" x-text="currentQuestion + 1"></span>
                                    <span>/</span>
                                    <span x-text="totalQuestions"></span>
                                </div>

                                {{-- Tombol Lanjut ke Soal Berikutnya (Belum di Soal Terakhir) --}}
                                <template x-if="currentQuestion < totalQuestions - 1">
                                    <button type="button"
                                            @click="next()"
                                            :disabled="!isCurrentQuestionAnswered()"
                                            :class="{
                                                'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/25 cursor-pointer active:scale-95': isCurrentQuestionAnswered(),
                                                'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed opacity-75': !isCurrentQuestionAnswered()
                                            }"
                                            class="flex-1 py-2.5 px-3 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                                        <span x-show="!isCurrentQuestionAnswered()" class="truncate">Pilih Jawaban Dulu</span>
                                        <span x-show="isCurrentQuestionAnswered()" class="truncate flex items-center gap-1">
                                            <span>Lanjut ke Soal</span>
                                            <span x-text="currentQuestion + 2"></span>
                                            <span>→</span>
                                        </span>
                                    </button>
                                </template>

                                {{-- Tombol Kirim Jawaban (Di Soal Terakhir) --}}
                                <template x-if="currentQuestion === totalQuestions - 1">
                                    <button type="button"
                                            @click="attemptSubmit(document.getElementById('pre-test-form'))"
                                            :disabled="!isCurrentQuestionAnswered()"
                                            :class="{
                                                'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-md shadow-teal-600/30 ring-2 ring-teal-400/50 cursor-pointer active:scale-95 animate-pulse': isCurrentQuestionAnswered(),
                                                'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed opacity-75': !isCurrentQuestionAnswered()
                                            }"
                                            class="flex-1 py-2.5 px-3 rounded-2xl text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                                        <span>🏁</span>
                                        <span x-show="!isCurrentQuestionAnswered()" class="truncate">Pilih Jawaban Terakhir</span>
                                        <span x-show="isCurrentQuestionAnswered()" class="truncate">
                                            {{ $initialPreScore !== null ? 'Kirim Jawaban Latihan' : 'Kirim Jawaban Pre-test' }}
                                        </span>
                                    </button>
                                </template>
                            </div>
                        </div>
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

