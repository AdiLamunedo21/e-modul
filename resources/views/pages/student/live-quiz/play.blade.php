<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Quiz Controller — {{ $session->pin }}</title>

    {{-- Tailwind CSS & Fonts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #090d16;
            color: #f8fafc;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        .font-mono-num {
            font-family: 'JetBrains+Mono', monospace;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between select-none"
      x-data="studentQuizController({{ $session->id }}, {{ $participant->id }}, '{{ in_array($session->status, ['question', 'reveal', 'leaderboard']) ? 'active' : $session->status }}', {{ (int) ($session->time_limit_seconds ?: 30) }})">

    {{-- ══ TOP STATUS BAR ══ --}}
    <header class="w-full bg-slate-900/95 backdrop-blur-md border-b border-slate-800 px-4 py-3 flex items-center justify-between shrink-0 z-30 sticky top-0">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
            <span class="font-bold text-xs text-emerald-400 uppercase tracking-wider">PIN: {{ $session->pin }}</span>
            <template x-if="totalQuestions > 0 && status !== 'lobby' && status !== 'finished'">
                <span class="hidden sm:inline-flex px-2.5 py-0.5 rounded-full bg-slate-800 border border-slate-700 text-[11px] font-extrabold text-slate-300">
                    Soal <span x-text="currentQuestionIndex + 1">1</span>/<span x-text="totalQuestions">1</span>
                </span>
            </template>
        </div>

        <div class="flex items-center gap-2.5">
            {{-- Personal Score --}}
            <div class="bg-slate-800/90 px-3 py-1 rounded-xl border border-slate-700 flex items-center gap-1.5 shadow-sm">
                <span class="text-[10px] uppercase font-bold text-slate-400">Skor:</span>
                <span class="font-mono-num font-black text-xs sm:text-sm text-amber-400" x-text="myTotalScore.toLocaleString()">{{ $participant->total_score }}</span>
            </div>

            {{-- Rank Badge --}}
            <div class="bg-slate-800/90 px-3 py-1 rounded-xl border border-slate-700 flex items-center gap-1.5 shadow-sm" x-show="status !== 'lobby'">
                <span class="text-[10px] uppercase font-bold text-slate-400">Rank:</span>
                <span class="font-mono-num font-black text-xs sm:text-sm text-emerald-400">#<span x-text="myRank">1</span></span>
            </div>
        </div>
    </header>

    {{-- ══ MAIN CONTROLLER INTERACTION AREA ══ --}}
    <main class="flex-1 flex flex-col justify-center items-center p-3 sm:p-5 max-w-2xl mx-auto w-full relative">

        {{-- ══════════════════════════════════════════════════════════════
             STATE 1: LOBBY
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="status === 'lobby'" x-cloak class="w-full text-center space-y-6 my-auto animate-fade-in py-8">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-500/10 border-2 border-emerald-500/20 text-emerald-400 flex items-center justify-center shadow-lg shadow-emerald-950/20">
                <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>

            <div class="space-y-2">
                <h2 class="text-2xl sm:text-3xl font-black text-white">Kamu Sudah Masuk!</h2>
                <p class="text-slate-400 text-sm sm:text-base">
                    Halo, <span class="font-bold text-emerald-400">{{ $participant->student->name }}</span>!
                </p>
            </div>

            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 text-sm text-slate-300 space-y-2 max-w-md mx-auto shadow-xl">
                <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Menunggu Guru Memulai Kuis
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Kuis live akan segera dimulai. Pertanyaan dan pilihan jawaban akan tampil langsung di layar HP/laptop kamu!
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             STATE 2: ACTIVE QUESTION (SOAL & KETERANGAN PILIHAN JAWABAN)
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="(status === 'active' || status === 'question') && !showAnswer && !showLeaderboard" x-cloak class="w-full flex flex-col space-y-4 my-auto py-2">
            
            {{-- Top Info Bar: Question Counter & Countdown Timer --}}
            <div class="flex items-center justify-between bg-slate-900/80 border border-slate-800 rounded-2xl px-4 py-2.5 shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-extrabold">
                        Soal <span x-text="currentQuestionIndex + 1">1</span> / <span x-text="totalQuestions">1</span>
                    </span>
                    <template x-if="hasAnswered">
                        <span class="px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 text-[10px] font-black uppercase tracking-wider">
                            Terkirim ✓
                        </span>
                    </template>
                </div>

                {{-- Countdown Timer --}}
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-800 border border-slate-700 font-mono-num font-black text-sm"
                     :class="remainingSeconds <= 5 ? 'text-rose-400 animate-pulse border-rose-500/50' : 'text-amber-400'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="remainingSeconds">{{ (int) ($session->time_limit_seconds ?: 30) }}</span>s
                </div>
            </div>

            {{-- ═════ KARTU SOAL (QUESTION CARD) ═════ --}}
            <div class="w-full bg-gradient-to-b from-slate-900 to-slate-950 border border-emerald-500/30 rounded-3xl p-5 sm:p-7 text-center shadow-xl backdrop-blur-md relative overflow-hidden">
                <div class="text-[10px] uppercase font-black tracking-widest text-emerald-400 mb-2">Pertanyaan:</div>
                <p class="text-lg sm:text-xl md:text-2xl font-black text-white leading-snug break-words tracking-tight"
                   x-text="currentQuestion ? currentQuestion.question_text : 'Memuat pertanyaan...'">
                </p>
            </div>

            {{-- Prompt Bar --}}
            <div class="flex items-center justify-between px-1 text-xs">
                <span class="font-extrabold uppercase tracking-wider text-rose-400 flex items-center gap-1.5" x-show="!hasAnswered && remainingSeconds <= 0">
                    ⏰ Waktu Menjawab Telah Habis
                </span>
                <span class="font-extrabold uppercase tracking-wider text-slate-400" x-show="!hasAnswered && remainingSeconds > 0">
                    Pilih Salah Satu Jawaban:
                </span>
                <span class="font-extrabold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5" x-show="hasAnswered">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Jawaban Kamu: <span class="font-mono-num font-black underline text-sm" x-text="myAnswer ? (myAnswer.chosen_option || myAnswer.selected_option) : ''"></span> (Terkunci)
                </span>
                <span class="text-slate-500 text-[11px]" x-show="hasAnswered">
                    Menunggu waktu habis...
                </span>
            </div>

            {{-- ═════ KARTU & TOMBOL PILIHAN JAWABAN (OPTIONS LIST) ═════ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full">
                
                {{-- Option A: Rose Triangle --}}
                <button type="button"
                        x-show="hasOption('A')"
                        @click="submitAnswer('A')"
                        :disabled="submittingAnswer || hasAnswered || remainingSeconds <= 0"
                        class="w-full rounded-2xl p-4 sm:p-4.5 text-left transition-all duration-200 flex items-center gap-3.5 shadow-lg relative overflow-hidden group"
                        :class="{
                            'bg-rose-600 hover:bg-rose-500 text-white shadow-rose-600/30 active:scale-[0.98] cursor-pointer hover:shadow-xl': !hasAnswered && remainingSeconds > 0,
                            'bg-rose-950/30 text-slate-500 border border-rose-900/30 opacity-40 grayscale cursor-not-allowed': !hasAnswered && remainingSeconds <= 0,
                            'bg-rose-600 text-white ring-4 ring-white shadow-2xl scale-[1.02] z-10': hasAnswered && isMyChosenOption('A'),
                            'bg-rose-950/40 text-slate-400 border border-rose-900/40 opacity-40 grayscale cursor-not-allowed': hasAnswered && !isMyChosenOption('A')
                        }">
                    <div class="w-11 h-11 rounded-xl bg-black/25 flex items-center justify-center font-black text-base shrink-0 shadow-inner">
                        ▲ A
                    </div>
                    <div class="flex-1 font-bold text-sm sm:text-base leading-snug break-words"
                         x-text="getOptionText('A') || 'Pilihan A'">
                    </div>
                    <template x-if="hasAnswered && isMyChosenOption('A')">
                        <span class="px-2.5 py-1 rounded-lg bg-white text-rose-600 text-xs font-black uppercase tracking-wider shrink-0 shadow">
                            Pilihanmu ✓
                        </span>
                    </template>
                </button>

                {{-- Option B: Blue Diamond --}}
                <button type="button"
                        x-show="hasOption('B')"
                        @click="submitAnswer('B')"
                        :disabled="submittingAnswer || hasAnswered || remainingSeconds <= 0"
                        class="w-full rounded-2xl p-4 sm:p-4.5 text-left transition-all duration-200 flex items-center gap-3.5 shadow-lg relative overflow-hidden group"
                        :class="{
                            'bg-blue-600 hover:bg-blue-500 text-white shadow-blue-600/30 active:scale-[0.98] cursor-pointer hover:shadow-xl': !hasAnswered && remainingSeconds > 0,
                            'bg-blue-950/30 text-slate-500 border border-blue-900/30 opacity-40 grayscale cursor-not-allowed': !hasAnswered && remainingSeconds <= 0,
                            'bg-blue-600 text-white ring-4 ring-white shadow-2xl scale-[1.02] z-10': hasAnswered && isMyChosenOption('B'),
                            'bg-blue-950/40 text-slate-400 border border-blue-900/40 opacity-40 grayscale cursor-not-allowed': hasAnswered && !isMyChosenOption('B')
                        }">
                    <div class="w-11 h-11 rounded-xl bg-black/25 flex items-center justify-center font-black text-base shrink-0 shadow-inner">
                        ◆ B
                    </div>
                    <div class="flex-1 font-bold text-sm sm:text-base leading-snug break-words"
                         x-text="getOptionText('B') || 'Pilihan B'">
                    </div>
                    <template x-if="hasAnswered && isMyChosenOption('B')">
                        <span class="px-2.5 py-1 rounded-lg bg-white text-blue-600 text-xs font-black uppercase tracking-wider shrink-0 shadow">
                            Pilihanmu ✓
                        </span>
                    </template>
                </button>

                {{-- Option C: Amber Circle --}}
                <button type="button"
                        x-show="hasOption('C')"
                        @click="submitAnswer('C')"
                        :disabled="submittingAnswer || hasAnswered || remainingSeconds <= 0"
                        class="w-full rounded-2xl p-4 sm:p-4.5 text-left transition-all duration-200 flex items-center gap-3.5 shadow-lg relative overflow-hidden group"
                        :class="{
                            'bg-amber-500 hover:bg-amber-400 text-slate-950 shadow-amber-500/30 active:scale-[0.98] cursor-pointer hover:shadow-xl': !hasAnswered && remainingSeconds > 0,
                            'bg-amber-950/30 text-slate-500 border border-amber-900/30 opacity-40 grayscale cursor-not-allowed': !hasAnswered && remainingSeconds <= 0,
                            'bg-amber-500 text-slate-950 ring-4 ring-white shadow-2xl scale-[1.02] z-10': hasAnswered && isMyChosenOption('C'),
                            'bg-amber-950/40 text-slate-400 border border-amber-900/40 opacity-40 grayscale cursor-not-allowed': hasAnswered && !isMyChosenOption('C')
                        }">
                    <div class="w-11 h-11 rounded-xl bg-black/25 text-white flex items-center justify-center font-black text-base shrink-0 shadow-inner">
                        ● C
                    </div>
                    <div class="flex-1 font-bold text-sm sm:text-base leading-snug break-words"
                         x-text="getOptionText('C') || 'Pilihan C'">
                    </div>
                    <template x-if="hasAnswered && isMyChosenOption('C')">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-950 text-amber-400 text-xs font-black uppercase tracking-wider shrink-0 shadow">
                            Pilihanmu ✓
                        </span>
                    </template>
                </button>

                {{-- Option D: Emerald Square --}}
                <button type="button"
                        x-show="hasOption('D')"
                        @click="submitAnswer('D')"
                        :disabled="submittingAnswer || hasAnswered || remainingSeconds <= 0"
                        class="w-full rounded-2xl p-4 sm:p-4.5 text-left transition-all duration-200 flex items-center gap-3.5 shadow-lg relative overflow-hidden group"
                        :class="{
                            'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/30 active:scale-[0.98] cursor-pointer hover:shadow-xl': !hasAnswered && remainingSeconds > 0,
                            'bg-emerald-950/30 text-slate-500 border border-emerald-900/30 opacity-40 grayscale cursor-not-allowed': !hasAnswered && remainingSeconds <= 0,
                            'bg-emerald-600 text-white ring-4 ring-white shadow-2xl scale-[1.02] z-10': hasAnswered && isMyChosenOption('D'),
                            'bg-emerald-950/40 text-slate-400 border border-emerald-900/40 opacity-40 grayscale cursor-not-allowed': hasAnswered && !isMyChosenOption('D')
                        }">
                    <div class="w-11 h-11 rounded-xl bg-black/25 flex items-center justify-center font-black text-base shrink-0 shadow-inner">
                        ■ D
                    </div>
                    <div class="flex-1 font-bold text-sm sm:text-base leading-snug break-words"
                         x-text="getOptionText('D') || 'Pilihan D'">
                    </div>
                    <template x-if="hasAnswered && isMyChosenOption('D')">
                        <span class="px-2.5 py-1 rounded-lg bg-white text-emerald-700 text-xs font-black uppercase tracking-wider shrink-0 shadow">
                            Pilihanmu ✓
                        </span>
                    </template>
                </button>

                {{-- Option E: Violet Star (jika soal memiliki opsi E) --}}
                <button type="button"
                        x-show="hasOption('E')"
                        @click="submitAnswer('E')"
                        :disabled="submittingAnswer || hasAnswered || remainingSeconds <= 0"
                        class="w-full rounded-2xl p-4 sm:p-4.5 text-left transition-all duration-200 flex items-center gap-3.5 shadow-lg relative overflow-hidden group sm:col-span-2"
                        :class="{
                            'bg-violet-600 hover:bg-violet-500 text-white shadow-violet-600/30 active:scale-[0.98] cursor-pointer hover:shadow-xl': !hasAnswered && remainingSeconds > 0,
                            'bg-violet-950/30 text-slate-500 border border-violet-900/30 opacity-40 grayscale cursor-not-allowed': !hasAnswered && remainingSeconds <= 0,
                            'bg-violet-600 text-white ring-4 ring-white shadow-2xl scale-[1.02] z-10': hasAnswered && isMyChosenOption('E'),
                            'bg-violet-950/40 text-slate-400 border border-violet-900/40 opacity-40 grayscale cursor-not-allowed': hasAnswered && !isMyChosenOption('E')
                        }">
                    <div class="w-11 h-11 rounded-xl bg-black/25 flex items-center justify-center font-black text-base shrink-0 shadow-inner">
                        ★ E
                    </div>
                    <div class="flex-1 font-bold text-sm sm:text-base leading-snug break-words"
                         x-text="getOptionText('E') || 'Pilihan E'">
                    </div>
                    <template x-if="hasAnswered && isMyChosenOption('E')">
                        <span class="px-2.5 py-1 rounded-lg bg-white text-violet-700 text-xs font-black uppercase tracking-wider shrink-0 shadow">
                            Pilihanmu ✓
                        </span>
                    </template>
                </button>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             STATE 3: ANSWER REVEALED (HASIL & PEMBAHASAN SOAL)
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="(status === 'active' || status === 'reveal') && showAnswer && !showLeaderboard" x-cloak class="w-full flex flex-col space-y-4 my-auto animate-scale-in py-3">
            
            {{-- Feedback Header Banner --}}
            <template x-if="myAnswer && myAnswer.is_correct">
                <div class="w-full bg-emerald-950/60 border-2 border-emerald-500 rounded-3xl p-5 text-center shadow-2xl shadow-emerald-500/20">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-500 text-slate-950 flex items-center justify-center shadow-lg shadow-emerald-500/40 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-emerald-400 tracking-tight">JAWABAN BENAR! 🎯</h2>
                    <p class="text-xs text-emerald-300 font-mono-num font-bold mt-1">
                        +<span x-text="myAnswer.points">0</span> poin diperoleh!
                    </p>
                </div>
            </template>

            <template x-if="!myAnswer || !myAnswer.is_correct">
                <div class="w-full bg-rose-950/60 border-2 border-rose-600 rounded-3xl p-5 text-center shadow-2xl shadow-rose-600/20">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-600/40 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-rose-500 tracking-tight">JAWABAN KURANG TEPAT!</h2>
                    <p class="text-xs text-slate-400 font-mono-num mt-1">
                        <template x-if="myAnswer && myAnswer.chosen_option">
                            <span>Pilihan kamu: <strong class="text-rose-400" x-text="myAnswer.chosen_option"></strong> • </span>
                        </template>
                        +0 poin
                    </p>
                </div>
            </template>

            {{-- Question Card Recap --}}
            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-4 sm:p-5 text-center shadow-md">
                <p class="text-xs text-slate-400 uppercase font-black tracking-widest mb-1">Soal:</p>
                <p class="text-sm sm:text-base font-bold text-white leading-snug"
                   x-text="currentQuestion ? currentQuestion.question_text : ''">
                </p>
            </div>

            {{-- Kunci Jawaban Card --}}
            <div class="w-full bg-slate-900 border border-emerald-500/30 rounded-2xl p-4 text-left shadow-lg space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] uppercase font-black tracking-wider text-emerald-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Kunci Jawaban yang Benar:
                    </span>
                    <span class="font-mono-num font-black text-xs px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/40"
                          x-text="revealData ? revealData.correct_answer : ''">
                    </span>
                </div>
                <p class="text-sm sm:text-base font-extrabold text-white pl-3.5 border-l-2 border-emerald-500"
                   x-text="revealData ? getOptionText(revealData.correct_answer) : ''">
                </p>
            </div>

            {{-- Pembahasan Soal (jika ada) --}}
            <div x-show="revealData && revealData.explanation" class="w-full bg-slate-900/80 border border-slate-800 rounded-2xl p-4 text-left shadow-md space-y-1">
                <span class="text-[10px] uppercase font-bold tracking-wider text-amber-400 flex items-center gap-1">
                    💡 Pembahasan:
                </span>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed" x-text="revealData ? revealData.explanation : ''"></p>
            </div>

            <p class="text-center text-xs text-slate-500">
                Menunggu guru melanjutkan ke pertanyaan berikutnya...
            </p>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             STATE 4: LEADERBOARD NOTICE
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="(status === 'active' || status === 'leaderboard') && showLeaderboard" x-cloak class="w-full text-center space-y-6 my-auto animate-fade-in py-6">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
            </div>
            <div>
                <h3 class="text-xl sm:text-2xl font-black text-white">Klasemen Sementara</h3>
                <p class="text-xs text-slate-400 mt-1">Perhatikan proyektor untuk klasemen lengkap kelas!</p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-3 max-w-sm mx-auto shadow-2xl">
                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Peringkat Kamu Saat Ini:</p>
                <div class="font-mono-num font-black text-4xl text-amber-400">
                    #<span x-text="myRank">1</span> <span class="text-xs font-normal text-slate-400">dari <span x-text="totalParticipants">1</span> siswa</span>
                </div>
                <div class="pt-2 border-t border-slate-800">
                    <p class="text-xs uppercase font-extrabold tracking-wider text-slate-400">Total Skor:</p>
                    <p class="font-mono-num font-black text-xl text-emerald-400">
                        <span x-text="myTotalScore.toLocaleString()"></span> pts
                    </p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             STATE 5: FINISHED / FINAL PODIUM RESULT
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="status === 'finished'" x-cloak class="w-full text-center space-y-6 my-auto animate-fade-in py-8">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-xs font-black uppercase tracking-wider">
                    🎉 Kuis Selesai
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white">Hasil Kuis Live</h2>
            </div>

            <div class="bg-gradient-to-b from-slate-900 to-slate-950 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl max-w-sm mx-auto">
                <div>
                    <p class="text-xs uppercase font-extrabold tracking-widest text-slate-400">Peringkat Akhir</p>
                    <p class="font-mono-num font-black text-4xl sm:text-5xl text-amber-400 mt-1">
                        #<span x-text="myRank">1</span>
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">dari <span x-text="totalParticipants">1</span> peserta kelas</p>
                </div>

                <div class="pt-4 border-t border-slate-800/80">
                    <p class="text-xs uppercase font-extrabold tracking-widest text-slate-400">Total Perolehan Skor</p>
                    <p class="font-mono-num font-black text-2xl sm:text-3xl text-white mt-1">
                        <span x-text="myTotalScore.toLocaleString()"></span> <span class="text-xs text-slate-400">pts</span>
                    </p>
                </div>
            </div>

            <div class="pt-2 max-w-sm mx-auto">
                <a href="{{ route('student.dashboard') }}"
                   class="inline-block w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition-all shadow-lg shadow-emerald-950/40">
                    Kembali ke Dashboard Siswa
                </a>
            </div>
        </div>

    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="w-full bg-slate-900/70 border-t border-slate-800 px-4 py-2.5 flex items-center justify-between text-[11px] text-slate-400 shrink-0">
        <span>👤 {{ $participant->student->name }}</span>
        <span>Kuis Live E-Modul</span>
    </footer>

    {{-- ══ ALPINE SCRIPT CONTROLLER ══ --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('studentQuizController', (sessionId, participantId, initialStatus, initialTimeLimit = 30) => ({
                sessionId: sessionId,
                participantId: participantId,
                status: initialStatus,
                currentQuestionIndex: 0,
                totalQuestions: 1,
                timeLimit: initialTimeLimit,
                remainingSeconds: initialTimeLimit,
                startedAtTs: null,
                currentQuestion: null,
                revealData: null,
                showAnswer: false,
                showLeaderboard: false,
                hasAnswered: false,
                submittingAnswer: false,
                myAnswer: null,
                myTotalScore: 0,
                myRank: 1,
                totalParticipants: 1,
                questionStartedAt: null,
                pollInterval: null,
                timerInterval: null,

                init() {
                    this.poll();
                    this.pollInterval = setInterval(() => this.poll(), 1200);

                    // Countdown timer lokal yang disinkronkan dengan jam server
                    this.timerInterval = setInterval(() => {
                        this.tickTimer();
                    }, 500);
                },

                tickTimer() {
                    if ((this.status === 'active' || this.status === 'question') && !this.showAnswer && !this.showLeaderboard) {
                        if (this.startedAtTs) {
                            const nowTs = Math.floor(Date.now() / 1000);
                            const elapsed = Math.max(0, nowTs - this.startedAtTs);
                            this.remainingSeconds = Math.max(0, this.timeLimit - elapsed);
                        } else if (this.remainingSeconds > 0) {
                            this.remainingSeconds--;
                        }
                    }
                },

                async poll() {
                    try {
                        const res = await fetch(`/student/live-quiz/${this.sessionId}/poll`);
                        if (!res.ok) return;
                        const data = await res.json();

                        const wasLobby = (this.status === 'lobby');
                        const isQuestionStatus = (data.status === 'active' || data.status === 'question');
                        const isNewQuestion = (data.current_question_index !== this.currentQuestionIndex) || 
                                              (wasLobby && isQuestionStatus);

                        if (isNewQuestion) {
                            this.currentQuestionIndex = data.current_question_index;
                            this.hasAnswered = false;
                            this.myAnswer = null;
                            this.questionStartedAt = data.started_at ? (data.started_at * 1000) : Date.now();
                        }

                        this.status = data.status;
                        this.showAnswer = data.show_answer;
                        this.showLeaderboard = data.show_leaderboard;
                        this.hasAnswered = data.has_answered;
                        this.myAnswer = data.my_answer;
                        this.myTotalScore = data.my_total_score || 0;
                        this.myRank = data.my_rank || 1;
                        this.totalParticipants = data.total_participants || 1;
                        this.totalQuestions = data.total_questions || 1;

                        if (data.time_limit) {
                            this.timeLimit = data.time_limit;
                        }

                        if (data.started_at) {
                            this.startedAtTs = data.started_at;
                            if (!this.questionStartedAt) {
                                this.questionStartedAt = data.started_at * 1000;
                            }
                        }

                        // Sinkronkan sisa detik dengan formula yang persis sama dengan layar Host Guru
                        if (isQuestionStatus && !this.showAnswer && !this.showLeaderboard) {
                            if (this.startedAtTs) {
                                const nowTs = Math.floor(Date.now() / 1000);
                                const elapsed = Math.max(0, nowTs - this.startedAtTs);
                                this.remainingSeconds = Math.max(0, this.timeLimit - elapsed);
                            } else if (data.remaining_seconds !== undefined && data.remaining_seconds !== null) {
                                this.remainingSeconds = data.remaining_seconds;
                            }
                        } else if (this.status === 'lobby') {
                            // Saat masih di lobby, tampilkan batas waktu kuis (misal 60s atau 20s) bukannya 0s
                            this.remainingSeconds = this.timeLimit;
                        } else if (data.remaining_seconds !== undefined && data.remaining_seconds !== null) {
                            this.remainingSeconds = data.remaining_seconds;
                        }

                        if (data.question) {
                            this.currentQuestion = data.question;
                        }

                        if (data.reveal) {
                            this.revealData = data.reveal;
                        } else if (!this.showAnswer) {
                            this.revealData = null;
                        }
                    } catch (e) {
                        console.error('Player poll error:', e);
                    }
                },

                async submitAnswer(option) {
                    if (this.hasAnswered || this.submittingAnswer || this.remainingSeconds <= 0) return;
                    this.submittingAnswer = true;

                    const nowMs = Date.now();
                    const startMs = this.questionStartedAt || (this.startedAtTs ? (this.startedAtTs * 1000) : nowMs);
                    const responseTimeMs = Math.max(100, nowMs - startMs);

                    try {
                        const res = await fetch(`/student/live-quiz/${this.sessionId}/answer`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                chosen_option: option,
                                response_time_ms: responseTimeMs
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.hasAnswered = true;
                            this.myAnswer = {
                                chosen_option: option,
                                is_correct: data.is_correct,
                                points: data.points
                            };
                            this.myTotalScore = data.total_score;
                        }
                    } catch (e) {
                        console.error('Answer submission error:', e);
                    } finally {
                        this.submittingAnswer = false;
                    }
                },

                getOptionText(key) {
                    if (!this.currentQuestion || !this.currentQuestion.options) return '';
                    const opts = this.currentQuestion.options;
                    if (Array.isArray(opts)) {
                        const map = { 'A': 0, 'B': 1, 'C': 2, 'D': 3, 'E': 4 };
                        const idx = map[key];
                        return opts[idx] ? (opts[idx].option_text || opts[idx]) : '';
                    } else if (typeof opts === 'object') {
                        return opts[key] || opts[key.toLowerCase()] || '';
                    }
                    return '';
                },

                hasOption(key) {
                    if (!this.currentQuestion || !this.currentQuestion.options) return key !== 'E';
                    const opts = this.currentQuestion.options;
                    if (Array.isArray(opts)) {
                        const map = { 'A': 0, 'B': 1, 'C': 2, 'D': 3, 'E': 4 };
                        return opts[map[key]] !== undefined;
                    } else if (typeof opts === 'object') {
                        return opts[key] !== undefined || opts[key.toLowerCase()] !== undefined;
                    }
                    return key !== 'E';
                },

                isMyChosenOption(key) {
                    if (!this.myAnswer) return false;
                    const chosen = this.myAnswer.chosen_option || this.myAnswer.selected_option;
                    return String(chosen).toUpperCase() === key.toUpperCase();
                }
            }));
        });
    </script>
</body>
</html>
