<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kuis Live Host — {{ $session->pin }} | E-Modul SMKN 3 Yogyakarta</title>

    {{-- Tailwind CSS & Fonts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #06090e;
            color: #f8fafc;
            overflow-x: hidden;
        }
        .font-mono-num {
            font-family: 'JetBrains+Mono', monospace;
        }
        [x-cloak] { display: none !important; }

        /* Glowing background ambient lights */
        .ambient-glow-emerald {
            background: radial-gradient(circle at 50% 20%, rgba(16, 185, 129, 0.12) 0%, rgba(6, 9, 14, 0) 70%);
        }
        .pin-neon-glow {
            text-shadow: 0 0 25px rgba(52, 211, 153, 0.45);
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 0.9; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.98); }
        }
        .animate-pulse-soft {
            animation: pulse-soft 3s infinite ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen bg-[#06090e] text-slate-100 flex flex-col justify-between selection:bg-emerald-500 selection:text-slate-950 ambient-glow-emerald"
      x-data="liveQuizHost({{ $session->id }}, '{{ $session->status }}', {{ $session->current_question_index }}, {{ $session->total_questions }})">

    {{-- ══ TOP NAVBAR (TEMA HIJAU KE HITAMAN) ══ --}}
    <header class="w-full bg-[#080d14]/90 backdrop-blur-xl border-b border-emerald-500/20 px-6 py-3.5 flex items-center justify-between shrink-0 z-30 shadow-lg shadow-black/40">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('teacher.live-quiz.index') }}"
               class="p-2 rounded-xl bg-slate-900 border border-slate-800 hover:border-emerald-500/40 text-slate-300 hover:text-white transition-colors"
               title="Kembali ke Daftar Sesi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <h1 class="text-xs font-black uppercase tracking-wider text-emerald-400">
                        Layar Proyektor Kuis Live
                    </h1>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase {{ $session->test_type === 'pre_test' ? 'bg-sky-500/20 text-sky-400 border border-sky-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' }}">
                        {{ $session->test_type === 'pre_test' ? 'Pre-Test' : 'Post-Test' }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 font-semibold truncate max-w-md mt-0.5">
                    {{ $session->module->title }} — <span class="text-slate-300">{{ $session->schoolClass->name ?? 'Semua Kelas' }}</span>
                </p>
            </div>
        </div>

        {{-- PIN Indicator & Action Controls --}}
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2.5 bg-slate-900/90 border border-emerald-500/30 px-4 py-2 rounded-xl shadow-inner">
                <span class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">PIN Game:</span>
                <span class="font-mono-num font-black text-lg text-emerald-300 tracking-widest">{{ $session->pin }}</span>
            </div>

            {{-- Sound Audio Toggle --}}
            <button @click="toggleSound()"
                    class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-emerald-500/40 text-slate-300 hover:text-white transition-colors"
                    :title="soundEnabled ? 'Suara Aktif' : 'Suara Hening'">
                <template x-if="soundEnabled">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z"/></svg>
                </template>
                <template x-if="!soundEnabled">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75L19.5 12m0 0l2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-1.5l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z"/></svg>
                </template>
            </button>

            {{-- Fullscreen Toggle --}}
            <button @click="toggleFullscreen()"
                    class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 hover:border-emerald-500/40 text-slate-300 hover:text-white transition-colors"
                    title="Layar Penuh (F11)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                </svg>
            </button>
        </div>
    </header>

    {{-- ══ MAIN INTERACTIVE VIEW CONTAINER ══ --}}
    <main class="flex-1 flex flex-col justify-center items-center p-4 sm:p-8 max-w-7xl mx-auto w-full relative">

        {{-- ══════════════════════════════════════════════════════════════
             STATE 1: LOBBY HERO (RUANG TUNGGU KELAS TEMA HIJAU KE HITAMAN)
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="status === 'lobby'" x-cloak class="w-full max-w-5xl flex flex-col items-center text-center space-y-7 animate-fade-in my-auto">
            
            {{-- Instruction & Big Neon PIN Card --}}
            <div class="w-full bg-gradient-to-b from-slate-900/95 via-[#0a1218]/90 to-slate-950/95 border border-emerald-500/30 rounded-3xl p-6 sm:p-10 shadow-2xl shadow-emerald-950/40 relative overflow-hidden">
                <div class="absolute -top-32 -left-32 w-72 h-72 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-32 -right-32 w-72 h-72 bg-teal-500/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col items-center space-y-5">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs font-black uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Ruang Tunggu Kelas Aktif
                        </div>
                        <p class="text-xs sm:text-sm font-bold uppercase tracking-widest text-slate-400">
                            Buka browser di HP / Laptop siswa dan kunjungi:
                        </p>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-950 border border-emerald-500/30 text-emerald-300 font-mono text-sm sm:text-base font-bold shadow-inner">
                            <span>{{ url('/student/live-quiz/join') }}</span>
                            <button type="button" @click="copyUrl()" class="p-1 text-slate-400 hover:text-white" title="Salin Tautan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Hero Neon PIN Box --}}
                    <div class="pt-2">
                        <p class="text-[11px] uppercase font-extrabold tracking-widest text-emerald-400/80 mb-2">
                            Lalu Masukkan PIN Game:
                        </p>
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                            <div class="relative bg-gradient-to-b from-[#071015] to-[#04080c] border-2 border-emerald-500/60 rounded-3xl px-8 sm:px-16 py-4 sm:py-6 shadow-2xl flex items-center justify-center gap-4">
                                <span class="font-mono-num font-black text-6xl sm:text-7xl lg:text-8xl text-emerald-300 tracking-[0.22em] pin-neon-glow">
                                    {{ $session->pin }}
                                </span>
                                <button type="button"
                                        @click="copyPin()"
                                        class="px-3 py-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold transition-all"
                                        title="Salin PIN">
                                    <span x-text="pinCopied ? '✓ Tersalin' : 'Salin'">Salin</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panggung Peserta / Live Student Room --}}
            <div class="w-full bg-[#080d14]/80 border border-slate-800 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xl">
                <div class="flex items-center justify-between px-2">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h2 class="text-base sm:text-lg font-extrabold text-white">Siswa Siap di Ruang Tunggu:</h2>
                        <span class="px-3 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-black text-sm"
                              x-text="participants.length">0</span>
                    </div>
                    <span class="text-xs text-slate-400" x-show="participants.length === 0">
                        Menunggu siswa pertama memasukkan PIN...
                    </span>
                    <span class="text-xs text-emerald-400 font-semibold" x-show="participants.length > 0">
                        ✓ Siswa sedang bersiap
                    </span>
                </div>

                {{-- Grid Peserta --}}
                <div class="min-h-[140px] flex flex-wrap gap-2.5 justify-center items-center p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80">
                    <template x-if="participants.length === 0">
                        <div class="text-slate-500 text-sm flex flex-col items-center gap-3 py-6">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center animate-pulse">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            </div>
                            <span>Belum ada siswa yang masuk. Tampilkan PIN di atas ke siswa di kelas.</span>
                        </div>
                    </template>
                    <template x-for="(p, idx) in participants" :key="p.id">
                        <div class="px-4 py-2.5 rounded-2xl bg-slate-900 border border-emerald-500/30 text-white text-sm font-bold shadow-md flex items-center gap-2.5 transition-all transform hover:scale-105">
                            <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-xs font-black"
                                  x-text="p.name.charAt(0)"></span>
                            <span x-text="p.name"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Floating / Master Start Button --}}
            <div class="pt-2">
                <button type="button"
                        @click="startQuiz()"
                        :disabled="loadingAction || participants.length === 0"
                        class="inline-flex items-center justify-center gap-3 px-12 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-400 hover:to-teal-400 disabled:opacity-40 disabled:pointer-events-none text-slate-950 font-black text-xl shadow-2xl shadow-emerald-500/30 transition-all transform hover:scale-105 active:scale-95 cursor-pointer">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z" />
                    </svg>
                    <span>Mulai Kuis Sekarang!</span>
                </button>
                <p class="text-xs text-slate-500 mt-2" x-show="participants.length === 0">
                    Tombol akan aktif setelah minimal 1 siswa bergabung.
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             STATE 2: ACTIVE QUESTION VIEW (SLIDE SOAL + COUNTDOWN)
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="status === 'question' || (status === 'reveal' && !showLeaderboard)" x-cloak class="w-full max-w-5xl flex flex-col space-y-6 my-auto">
            
            {{-- Question Top Bar: Index, Timer & Answers Counter --}}
            <div class="flex items-center justify-between bg-[#0a1218]/90 border border-emerald-500/20 rounded-2xl px-6 py-3.5 shadow-lg">
                {{-- Question Index --}}
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 font-extrabold text-sm border border-emerald-500/30">
                        Soal <span x-text="currentQuestionIndex + 1">1</span> / <span x-text="totalQuestions">5</span>
                    </span>
                </div>

                {{-- Circular Countdown Timer --}}
                <div class="flex items-center gap-3">
                    <div class="relative w-14 h-14 flex items-center justify-center">
                        <svg class="w-14 h-14 transform -rotate-90">
                            <circle cx="28" cy="28" r="24" stroke="currentColor" stroke-width="4" class="text-slate-800" fill="transparent" />
                            <circle cx="28" cy="28" r="24" stroke="currentColor" stroke-width="4"
                                    :class="timerSeconds <= 5 ? 'text-rose-500' : 'text-emerald-400'"
                                    fill="transparent"
                                    stroke-dasharray="150"
                                    :stroke-dashoffset="150 - (150 * timerProgress / 100)"
                                    class="transition-all duration-1000 ease-linear" />
                        </svg>
                        <span class="absolute font-mono-num font-black text-xl text-white" x-text="timerSeconds">30</span>
                    </div>
                </div>

                {{-- Live Answers Collected --}}
                <div class="flex items-center gap-2 text-right">
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-400">Jawaban Masuk</p>
                        <p class="font-mono-num font-black text-xl text-emerald-400">
                            <span x-text="answersCount">0</span> / <span x-text="participants.length">0</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Question Card --}}
            <div class="bg-gradient-to-b from-[#091117] to-[#060b10] border border-emerald-500/25 rounded-3xl p-8 sm:p-12 shadow-2xl text-center min-h-[180px] flex items-center justify-center relative">
                <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-snug tracking-tight"
                   x-text="currentQuestion.question_text || 'Memuat pertanyaan...'">
                </p>
            </div>

            {{-- 5 Color Answer Blocks (Kahoot Style Distinct Visual Keys) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Option A: Rose / Triangle --}}
                <div x-show="hasOption('A')"
                     class="relative rounded-2xl p-6 transition-all duration-300 flex items-center gap-4 text-white shadow-lg overflow-hidden"
                     :class="{
                         'bg-rose-600': !showAnswer,
                         'bg-rose-600 ring-4 ring-white': showAnswer && isOptionCorrect('A'),
                         'bg-rose-950/60 opacity-40 grayscale': showAnswer && !isOptionCorrect('A')
                     }">
                    <div class="w-12 h-12 rounded-xl bg-black/20 flex items-center justify-center font-black text-xl shrink-0">
                        ▲ A
                    </div>
                    <div class="flex-1 font-bold text-lg sm:text-xl text-left" x-text="getOptionText('A')"></div>
                    <template x-if="showAnswer">
                        <div class="font-mono-num font-black text-2xl px-3 py-1 rounded-lg bg-black/30" x-text="answerDistribution['A'] || 0"></div>
                    </template>
                </div>

                {{-- Option B: Blue / Diamond --}}
                <div x-show="hasOption('B')"
                     class="relative rounded-2xl p-6 transition-all duration-300 flex items-center gap-4 text-white shadow-lg overflow-hidden"
                     :class="{
                         'bg-blue-600': !showAnswer,
                         'bg-blue-600 ring-4 ring-white': showAnswer && isOptionCorrect('B'),
                         'bg-blue-950/60 opacity-40 grayscale': showAnswer && !isOptionCorrect('B')
                     }">
                    <div class="w-12 h-12 rounded-xl bg-black/20 flex items-center justify-center font-black text-xl shrink-0">
                        ◆ B
                    </div>
                    <div class="flex-1 font-bold text-lg sm:text-xl text-left" x-text="getOptionText('B')"></div>
                    <template x-if="showAnswer">
                        <div class="font-mono-num font-black text-2xl px-3 py-1 rounded-lg bg-black/30" x-text="answerDistribution['B'] || 0"></div>
                    </template>
                </div>

                {{-- Option C: Amber / Circle --}}
                <div x-show="hasOption('C')"
                     class="relative rounded-2xl p-6 transition-all duration-300 flex items-center gap-4 text-white shadow-lg overflow-hidden"
                     :class="{
                         'bg-amber-500 text-slate-950': !showAnswer,
                         'bg-amber-500 text-slate-950 ring-4 ring-white': showAnswer && isOptionCorrect('C'),
                         'bg-amber-950/60 text-white opacity-40 grayscale': showAnswer && !isOptionCorrect('C')
                     }">
                    <div class="w-12 h-12 rounded-xl bg-black/20 flex items-center justify-center font-black text-xl shrink-0">
                        ● C
                    </div>
                    <div class="flex-1 font-bold text-lg sm:text-xl text-left" x-text="getOptionText('C')"></div>
                    <template x-if="showAnswer">
                        <div class="font-mono-num font-black text-2xl px-3 py-1 rounded-lg bg-black/30" x-text="answerDistribution['C'] || 0"></div>
                    </template>
                </div>

                {{-- Option D: Emerald / Square --}}
                <div x-show="hasOption('D')"
                     class="relative rounded-2xl p-6 transition-all duration-300 flex items-center gap-4 text-white shadow-lg overflow-hidden"
                     :class="{
                         'bg-emerald-600': !showAnswer,
                         'bg-emerald-600 ring-4 ring-white': showAnswer && isOptionCorrect('D'),
                         'bg-emerald-950/60 opacity-40 grayscale': showAnswer && !isOptionCorrect('D')
                     }">
                    <div class="w-12 h-12 rounded-xl bg-black/20 flex items-center justify-center font-black text-xl shrink-0">
                        ■ D
                    </div>
                    <div class="flex-1 font-bold text-lg sm:text-xl text-left" x-text="getOptionText('D')"></div>
                    <template x-if="showAnswer">
                        <div class="font-mono-num font-black text-2xl px-3 py-1 rounded-lg bg-black/30" x-text="answerDistribution['D'] || 0"></div>
                    </template>
                </div>

                {{-- Option E: Violet / Star (jika soal memiliki opsi E) --}}
                <div x-show="hasOption('E')"
                     class="relative rounded-2xl p-6 transition-all duration-300 flex items-center gap-4 text-white shadow-lg overflow-hidden sm:col-span-2"
                     :class="{
                         'bg-violet-600': !showAnswer,
                         'bg-violet-600 ring-4 ring-white': showAnswer && isOptionCorrect('E'),
                         'bg-violet-950/60 opacity-40 grayscale': showAnswer && !isOptionCorrect('E')
                     }">
                    <div class="w-12 h-12 rounded-xl bg-black/20 flex items-center justify-center font-black text-xl shrink-0">
                        ★ E
                    </div>
                    <div class="flex-1 font-bold text-lg sm:text-xl text-left" x-text="getOptionText('E')"></div>
                    <template x-if="showAnswer">
                        <div class="font-mono-num font-black text-2xl px-3 py-1 rounded-lg bg-black/30" x-text="answerDistribution['E'] || 0"></div>
                    </template>
                </div>
            </div>

            {{-- Host Actions Bottom Toolbar --}}
            <div class="flex items-center justify-between pt-2">
                <div class="text-xs text-slate-400">
                    <span x-show="!showAnswer">Siswa sedang menjawab di layar masing-masing...</span>
                    <span x-show="showAnswer" class="text-emerald-400 font-bold">Kunci jawaban terbuka! Tinjau pilihan siswa sebelum lanjut.</span>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Reveal button (if not revealed yet) --}}
                    <button type="button"
                            x-show="!showAnswer"
                            @click="revealAnswer()"
                            :disabled="loadingAction"
                            class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-emerald-300 text-sm font-bold border border-emerald-500/30 transition-colors">
                        Kunci Jawaban Sekarang
                    </button>

                    {{-- Next Step button (when answer is revealed) --}}
                    <button type="button"
                            x-show="showAnswer"
                            @click="goToLeaderboard()"
                            :disabled="loadingAction"
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 text-sm font-black shadow-lg shadow-emerald-500/25 transition-all">
                        Tampilkan Papan Skor ->
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             STATE 3: LEADERBOARD SLIDE (PAPAN SKOR SEMENTARA)
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="status === 'leaderboard'" x-cloak class="w-full max-w-3xl flex flex-col space-y-6 my-auto animate-fade-in">
            <div class="text-center space-y-2">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-black uppercase tracking-wider">
                    ⚡ Papan Peringkat Teratas
                </div>
                <h2 class="text-3xl font-black text-white">Klasemen Sementara</h2>
                <p class="text-xs text-slate-400">Poin dihitung dari ketepatan dan kecepatan menjawab</p>
            </div>

            {{-- Leaderboard Top 5 List --}}
            <div class="bg-[#091117] border border-emerald-500/20 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-3">
                <template x-for="(entry, index) in leaderboard.slice(0, 5)" :key="entry.participant_id">
                    <div class="flex items-center justify-between p-4 rounded-2xl transition-all"
                         :class="{
                             'bg-gradient-to-r from-emerald-500/20 to-teal-500/5 border border-emerald-500/40 text-emerald-300': index === 0,
                             'bg-slate-900/80 border border-slate-800 text-slate-200': index > 0
                         }">
                        <div class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-base"
                                  :class="{
                                      'bg-amber-400 text-slate-950': index === 0,
                                      'bg-slate-700 text-slate-300': index === 1,
                                      'bg-amber-800/50 text-amber-300': index === 2,
                                      'bg-slate-800 text-slate-400': index > 2
                                  }"
                                  x-text="index + 1">
                            </span>
                            <div>
                                <p class="font-extrabold text-base text-white" x-text="entry.name"></p>
                                <p class="text-xs text-slate-400" x-text="entry.class_name || 'Siswa'"></p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="font-mono-num font-black text-xl"
                                  :class="index === 0 ? 'text-emerald-400' : 'text-slate-100'"
                                  x-text="entry.total_score.toLocaleString()">
                            </span>
                            <span class="text-xs text-slate-500 ml-1">pts</span>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Button to Next Question or Finish --}}
            <div class="flex justify-center pt-2">
                <template x-if="currentQuestionIndex + 1 < totalQuestions">
                    <button type="button"
                            @click="nextQuestion()"
                            :disabled="loadingAction"
                            class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-black text-base shadow-xl shadow-emerald-500/25 transition-all transform hover:scale-105">
                        Lanjut ke Soal Berikutnya (<span x-text="currentQuestionIndex + 2"></span>/<span x-text="totalQuestions"></span>) ->
                    </button>
                </template>
                <template x-if="currentQuestionIndex + 1 >= totalQuestions">
                    <button type="button"
                            @click="finishQuiz()"
                            :disabled="loadingAction"
                            class="px-10 py-4 rounded-2xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-slate-950 font-black text-lg shadow-xl shadow-amber-500/25 transition-all transform hover:scale-105">
                        🏆 Tampilkan Podium Juara Akhir!
                    </button>
                </template>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             STATE 4: PODIUM AKHIR (BERSIH, TANPA KARAKTER KARTUN / RINGAN)
             ══════════════════════════════════════════════════════════════ --}}
        <div x-show="status === 'finished'" x-cloak class="w-full max-w-4xl flex flex-col space-y-8 my-auto animate-fade-in">
            
            <div class="text-center space-y-2">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-black uppercase tracking-wider">
                    🎉 Kuis Selesai
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-white">Podium Juara Kuis Live</h2>
                <p class="text-sm text-slate-400">Selamat kepada seluruh siswa yang telah berpartisipasi dengan luar biasa!</p>
            </div>

            {{-- 3-Tier Clean CSS Podium (No cartoon characters, lightweight) --}}
            <div class="flex items-end justify-center gap-3 sm:gap-6 pt-10 pb-4">
                
                {{-- JUARA 2 (SILVER - KIRI) --}}
                <div class="flex-1 max-w-[200px] flex flex-col items-center">
                    <template x-if="podium.second">
                        <div class="w-full flex flex-col items-center space-y-2 mb-2 animate-scale-in">
                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-slate-300/20 text-slate-200 border border-slate-400/30">
                                Juara 2
                            </span>
                            <p class="font-extrabold text-sm sm:text-base text-white text-center line-clamp-1" x-text="podium.second.name"></p>
                            <p class="font-mono-num font-black text-slate-300 text-xs sm:text-sm">
                                <span x-text="podium.second.score.toLocaleString()"></span> pts
                            </p>
                        </div>
                    </template>
                    <div class="w-full h-32 sm:h-40 rounded-t-2xl bg-gradient-to-t from-slate-800 to-slate-600 border-t-2 border-slate-400 flex items-center justify-center shadow-lg">
                        <span class="font-mono-num font-black text-4xl text-slate-900/40">2</span>
                    </div>
                </div>

                {{-- JUARA 1 (GOLD - TENGAH, PALING TINGGI) --}}
                <div class="flex-1 max-w-[240px] flex flex-col items-center">
                    <template x-if="podium.first">
                        <div class="w-full flex flex-col items-center space-y-2 mb-2 animate-scale-in">
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-amber-400 text-slate-950 shadow-md">
                                👑 Juara 1
                            </span>
                            <p class="font-black text-base sm:text-lg text-amber-300 text-center line-clamp-1" x-text="podium.first.name"></p>
                            <p class="font-mono-num font-black text-amber-400 text-sm sm:text-base">
                                <span x-text="podium.first.score.toLocaleString()"></span> pts
                            </p>
                        </div>
                    </template>
                    <div class="w-full h-44 sm:h-56 rounded-t-2xl bg-gradient-to-t from-amber-600 to-amber-400 border-t-2 border-amber-200 flex items-center justify-center shadow-2xl relative">
                        <span class="font-mono-num font-black text-6xl text-amber-950/40">1</span>
                    </div>
                </div>

                {{-- JUARA 3 (BRONZE - KANAN) --}}
                <div class="flex-1 max-w-[200px] flex flex-col items-center">
                    <template x-if="podium.third">
                        <div class="w-full flex flex-col items-center space-y-2 mb-2 animate-scale-in">
                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-900/30 text-amber-300 border border-amber-800/40">
                                Juara 3
                            </span>
                            <p class="font-extrabold text-sm sm:text-base text-white text-center line-clamp-1" x-text="podium.third.name"></p>
                            <p class="font-mono-num font-black text-amber-400 text-xs sm:text-sm">
                                <span x-text="podium.third.score.toLocaleString()"></span> pts
                            </p>
                        </div>
                    </template>
                    <div class="w-full h-24 sm:h-28 rounded-t-2xl bg-gradient-to-t from-amber-950 to-amber-800 border-t-2 border-amber-600 flex items-center justify-center shadow-md">
                        <span class="font-mono-num font-black text-3xl text-amber-950/40">3</span>
                    </div>
                </div>
            </div>

            {{-- One-Click Action: Simpan Nilai ke Pusat Penilaian --}}
            <div class="bg-[#091117] border border-emerald-500/25 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl">
                <div>
                    <h3 class="font-bold text-white text-base">Sinkronisasi Nilai Kuis</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-md">
                        Konversikan skor kuis live siswa ke skala 0–100 dan rekam langsung ke <strong>Pusat Penilaian</strong> modul pembelajaran ini.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <button type="button"
                            @click="saveGrades()"
                            :disabled="savingGrades || gradesSaved"
                            class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 disabled:opacity-60 text-white font-extrabold text-sm shadow-lg shadow-emerald-600/25 transition-all flex items-center gap-2">
                        <template x-if="savingGrades">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="!savingGrades && !gradesSaved">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </template>
                        <span x-text="gradesSaved ? '✓ Nilai Berhasil Disimpan' : 'Simpan ke Pusat Penilaian'"></span>
                    </button>

                    <a href="{{ route('teacher.live-quiz.index') }}"
                       class="px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 text-sm font-semibold border border-slate-800 transition-colors">
                        Tutup Sesi
                    </a>
                </div>
            </div>
        </div>

    </main>

    {{-- ══ FOOTER BRANDING ══ --}}
    <footer class="w-full bg-[#06090e]/80 border-t border-slate-900 px-6 py-2.5 flex items-center justify-between text-xs text-slate-500 shrink-0">
        <span>SMKN 3 Yogyakarta — E-Modul Interaktif</span>
        <span>Mode Pantau Proyektor (Live Quiz)</span>
    </footer>

    {{-- ══ ALPINE HOST CONTROLLER SCRIPT ══ --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('liveQuizHost', (sessionId, initialStatus, initialQIndex, totalQ) => ({
                sessionId: sessionId,
                status: initialStatus,
                currentQuestionIndex: initialQIndex,
                totalQuestions: totalQ,
                timeLimit: 30,
                timerSeconds: 30,
                timerProgress: 100,
                timerInterval: null,
                pollInterval: null,
                startedAtTs: null,
                showAnswer: false,
                showLeaderboard: false,
                participants: [],
                answersCount: 0,
                answerDistribution: { A: 0, B: 0, C: 0, D: 0, E: 0 },
                leaderboard: [],
                podium: { first: null, second: null, third: null },
                currentQuestion: { question_text: '', options: [] },
                loadingAction: false,
                savingGrades: false,
                gradesSaved: false,
                pinCopied: false,
                soundEnabled: true,
                audioCtx: null,

                init() {
                    this.poll();
                    this.pollInterval = setInterval(() => this.poll(), 1200);

                    // Countdown timer lokal untuk visual yang mulus setiap detik
                    this.timerInterval = setInterval(() => {
                        this.tickTimer();
                    }, 500);
                },

                tickTimer() {
                    if ((this.status === 'question' || this.status === 'active') && !this.showAnswer && this.startedAtTs) {
                        const nowTs = Math.floor(Date.now() / 1000);
                        const elapsed = Math.max(0, nowTs - this.startedAtTs);
                        const remaining = Math.max(0, this.timeLimit - elapsed);
                        this.timerSeconds = remaining;
                        this.timerProgress = Math.round((remaining / this.timeLimit) * 100);

                        // Soft tick chime for last 3 seconds
                        if (remaining <= 3 && remaining > 0) {
                            this.playChime(440, 0.08);
                        }

                        // Auto reveal if timer hits 0
                        if (remaining <= 0 && !this.showAnswer && !this.loadingAction) {
                            this.revealAnswer();
                        }
                    }
                },

                playChime(freq = 587.33, duration = 0.2) {
                    if (!this.soundEnabled) return;
                    try {
                        if (!this.audioCtx) {
                            this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                        }
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, this.audioCtx.currentTime);
                        gain.gain.setValueAtTime(0.15, this.audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + duration);
                        osc.connect(gain);
                        gain.connect(this.audioCtx.destination);
                        osc.start();
                        osc.stop(this.audioCtx.currentTime + duration);
                    } catch (e) {}
                },

                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                    if (this.soundEnabled) this.playChime(880, 0.15);
                },

                copyPin() {
                    navigator.clipboard.writeText('{{ $session->pin }}');
                    this.pinCopied = true;
                    setTimeout(() => { this.pinCopied = false; }, 2000);
                },

                copyUrl() {
                    navigator.clipboard.writeText('{{ url("/student/live-quiz/join") }}');
                    alert('Tautan bergabung kuis berhasil disalin!');
                },

                async poll() {
                    try {
                        const res = await fetch(`/teacher/live-quiz/${this.sessionId}/host-poll`);
                        if (!res.ok) return;
                        const data = await res.json();

                        const oldParticipantsCount = this.participants.length;
                        this.status = data.status;
                        this.currentQuestionIndex = data.current_question_index;
                        this.totalQuestions = data.total_questions;
                        this.timeLimit = data.time_limit || 30;
                        this.showAnswer = data.show_answer;
                        this.showLeaderboard = data.show_leaderboard;
                        this.participants = data.participants || [];
                        this.answersCount = data.answers_count || 0;
                        this.answerDistribution = data.answer_distribution || { A: 0, B: 0, C: 0, D: 0, E: 0 };
                        this.leaderboard = data.leaderboard || [];
                        this.podium = data.podium || { first: null, second: null, third: null };

                        // Sound notification if a new participant joined lobby
                        if (this.status === 'lobby' && this.participants.length > oldParticipantsCount) {
                            this.playChime(659.25, 0.25);
                        }

                        if (data.question) {
                            this.currentQuestion = data.question;
                        }

                        if (data.started_at) {
                            this.startedAtTs = data.started_at;
                        }

                        // Update timer countdown
                        this.tickTimer();
                    } catch (e) {
                        console.error('Poll error:', e);
                    }
                },

                async startQuiz() {
                    this.loadingAction = true;
                    this.playChime(523.25, 0.3);
                    try {
                        const res = await fetch(`/teacher/live-quiz/${this.sessionId}/start`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            await this.poll();
                        }
                    } finally {
                        this.loadingAction = false;
                    }
                },

                async revealAnswer() {
                    this.loadingAction = true;
                    this.playChime(783.99, 0.35);
                    try {
                        const res = await fetch(`/teacher/live-quiz/${this.sessionId}/reveal`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showAnswer = true;
                            await this.poll();
                        }
                    } finally {
                        this.loadingAction = false;
                    }
                },

                async goToLeaderboard() {
                    this.loadingAction = true;
                    try {
                        const res = await fetch(`/teacher/live-quiz/${this.sessionId}/leaderboard`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showLeaderboard = true;
                            await this.poll();
                        }
                    } finally {
                        this.loadingAction = false;
                    }
                },

                async nextQuestion() {
                    this.loadingAction = true;
                    try {
                        const res = await fetch(`/teacher/live-quiz/${this.sessionId}/next-question`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showAnswer = false;
                            this.showLeaderboard = false;
                            await this.poll();
                        }
                    } finally {
                        this.loadingAction = false;
                    }
                },

                async finishQuiz() {
                    this.loadingAction = true;
                    try {
                        const res = await fetch(`/teacher/live-quiz/${this.sessionId}/finish`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.status = 'finished';
                            await this.poll();
                        }
                    } finally {
                        this.loadingAction = false;
                    }
                },

                async saveGrades() {
                    this.savingGrades = true;
                    try {
                        const res = await fetch(`/teacher/live-quiz/${this.sessionId}/save-grades`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.gradesSaved = true;
                            alert(`Berhasil menyimpan ${data.count} nilai siswa ke Pusat Penilaian!`);
                        } else {
                            alert(data.message || 'Gagal menyimpan nilai.');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan saat menyimpan nilai.');
                    } finally {
                        this.savingGrades = false;
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
                        const val = opts[key] || opts[key.toLowerCase()] || '';
                        return typeof val === 'object' && val !== null ? (val.option_text || val.text || '') : val;
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

                isOptionCorrect(key) {
                    if (!this.currentQuestion || !this.currentQuestion.correct_answer) return false;
                    return String(this.currentQuestion.correct_answer).trim().toUpperCase() === key.toUpperCase();
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    } else {
                        document.exitFullscreen().catch(() => {});
                    }
                }
            }));
        });
    </script>
</body>
</html>
