<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Pre-test — {{ $preTest->title ?? $module->title }}</title>
    <link rel="icon" href="{{ asset('lgsmk.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp .4s ease-out; }
    </style>
</head>
<body class="bg-slate-100 antialiased text-slate-900 min-h-screen">

    {{-- ═══ TOP BAR ═══ --}}
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 shadow-xl">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-lg shrink-0">📱</div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan Pre-test Siswa</h1>
                    <p class="text-[11px] text-slate-400">Pratinjau kuis pre-test tersimpan di database.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.pre-test.edit', $module) }}"
                   class="px-4 py-2 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition-all flex items-center gap-1.5">
                    <span>←</span> Kembali ke Editor
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ KONTEN PRATINJAU ═══ --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-16">
        <div class="w-full animate-fadeInUp">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200/60">

                {{-- Header Pre-test --}}
                <div class="p-6 sm:p-8 bg-gradient-to-br from-blue-50 to-indigo-50 border-b border-slate-200/70">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold border border-blue-200/70">
                            📝 {{ $preTest->questionCount() }} Butir Soal
                        </span>
                        @if($preTest->totalDurationSeconds() > 0)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200/70">
                            ⏱️ Total Akumulasi: {{ $preTest->totalDurationSeconds() }} Detik
                        </span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200/70">
                            🎯 Batas Nilai: {{ $preTest->kktp ?? 75 }} Poin
                        </span>
                        @if(!empty($preTest->randomize_questions))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                            🔀 Urutan Acak
                        </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        {{ $preTest->title ?? 'Pre-test' }}
                    </h1>
                    <p class="text-sm text-slate-600 mt-2 font-medium">
                        {{ $module->title }} — {{ $module->schoolClass->name ?? 'Kelas' }}
                    </p>

                    @if(!empty($preTest->instructions))
                        <div class="mt-4 p-4 bg-white/80 backdrop-blur-sm rounded-xl border border-blue-200/50 text-xs text-slate-700 leading-relaxed">
                            <p class="font-bold text-slate-800 mb-1">📋 Petunjuk Pengerjaan:</p>
                            <p>{{ $preTest->instructions }}</p>
                        </div>
                    @endif
                </div>

                {{-- Daftar Soal --}}
                <div class="p-6 sm:p-8 space-y-5">
                    @php 
                        $questions = $preTest->questions ?? collect(); 
                    @endphp

                    @if($questions->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4">📝</div>
                            <p class="text-slate-500 font-medium text-sm">Belum ada soal yang tersimpan di basis data.</p>
                            <p class="text-slate-400 text-xs mt-1">Kembali ke editor untuk menambahkan butir soal pre-test.</p>
                        </div>
                    @else
                        @foreach($questions as $idx => $q)
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-extrabold text-blue-600">Soal #{{ $idx + 1 }}</span>
                                    <div class="flex items-center gap-2">
                                        @if(!empty($q->time_limit_seconds))
                                            <span class="text-blue-700 font-bold bg-blue-50 border border-blue-200/70 px-2.5 py-0.5 rounded-md">⏱️ {{ $q->time_limit_seconds }} Detik</span>
                                        @endif
                                        <span class="text-slate-500 font-bold bg-slate-100 px-2.5 py-0.5 rounded-md">Bobot: {{ $q->score_weight }} Poin</span>
                                        <span class="text-slate-400 font-medium">
                                            Kunci Guru: <strong class="text-emerald-600 font-black">{{ $q->correct_answer }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm font-semibold text-slate-900 leading-relaxed">{{ $q->question_text }}</p>

                                <div class="space-y-2 pt-2">
                                    @foreach($q->options ?? [] as $opt => $text)
                                        @if(!empty(trim($text)))
                                            @php $isCorrect = ($opt === $q->correct_answer); @endphp
                                            <label class="flex items-center gap-3 p-3 rounded-xl border text-sm transition-colors cursor-pointer
                                                {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                                <input type="radio" name="preview_q_{{ $idx }}" value="{{ $opt }}" class="w-4 h-4 text-blue-600" disabled {{ $isCorrect ? 'checked' : '' }}>
                                                <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[11px] font-bold shrink-0
                                                    {{ $isCorrect ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                                    {{ $opt }}
                                                </span>
                                                <span class="font-medium">{{ $text }}</span>
                                                @if($isCorrect)
                                                    <span class="ml-auto text-[10px] font-bold text-emerald-600">✓ Kunci Jawaban</span>
                                                @endif
                                            </label>
                                        @endif
                                    @endforeach
                                </div>

                                @if(!empty($q->explanation))
                                    <div class="mt-2 p-3 bg-indigo-50 rounded-xl text-xs text-indigo-800 border border-indigo-100">
                                        <p class="font-bold mb-0.5">💡 Pembahasan:</p>
                                        <p>{{ $q->explanation }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>

</body>
</html>
