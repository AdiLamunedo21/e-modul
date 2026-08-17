<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Pre-test — {{ $data['judul'] ?? $module->title }}</title>
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
                    <p class="text-[11px] text-slate-400">Pratinjau tampilan kuis interaktif yang akan diselesaikan siswa.</p>
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
                            ⏱️ Durasi: {{ $data['durasi_menit'] ?? 15 }} Menit
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200/70">
                            🎯 KKTP: {{ $data['kktp'] ?? 75 }}
                        </span>
                        @if(!empty($data['acak_soal']))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                            🔀 Urutan Acak
                        </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        {{ $data['judul'] ?? 'Pre-test' }}
                    </h1>
                    <p class="text-sm text-slate-600 mt-2 font-medium">
                        {{ $module->title }} — {{ $module->schoolClass->name ?? 'Kelas' }}
                    </p>

                    @if(!empty($data['petunjuk']))
                        <div class="mt-4 p-4 bg-white/80 backdrop-blur-sm rounded-xl border border-blue-200/50 text-xs text-slate-700 leading-relaxed">
                            <p class="font-bold text-slate-800 mb-1">📋 Petunjuk Pengerjaan:</p>
                            <p>{{ $data['petunjuk'] }}</p>
                        </div>
                    @endif
                </div>

                {{-- Daftar Soal --}}
                <div class="p-6 sm:p-8 space-y-5">
                    @php $questions = $data['questions'] ?? []; @endphp

                    @if(count($questions) === 0)
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4">📝</div>
                            <p class="text-slate-500 font-medium text-sm">Belum ada soal yang dibuat.</p>
                            <p class="text-slate-400 text-xs mt-1">Kembali ke editor untuk menambahkan soal pre-test.</p>
                        </div>
                    @else
                        @foreach($questions as $idx => $q)
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-extrabold text-blue-600">Soal #{{ $idx + 1 }}</span>
                                    <span class="text-slate-400 font-medium">
                                        Kunci Guru: <strong class="text-emerald-600">{{ $q['kunci_jawaban'] ?? '-' }}</strong>
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-slate-900 leading-relaxed">{{ $q['pertanyaan'] ?? '' }}</p>

                                <div class="space-y-2 pt-2">
                                    @foreach($q['pilihan'] ?? [] as $opt => $text)
                                        @if(!empty(trim($text)))
                                            @php $isCorrect = ($opt === ($q['kunci_jawaban'] ?? '')); @endphp
                                            <label class="flex items-center gap-3 p-3 rounded-xl border text-sm transition-colors cursor-pointer
                                                {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                                <input type="radio" name="preview_q_{{ $idx }}" value="{{ $opt }}" class="w-4 h-4 text-blue-600" disabled>
                                                <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[11px] font-bold shrink-0
                                                    {{ $isCorrect ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                                    {{ $opt }}
                                                </span>
                                                <span class="font-medium">{{ $text }}</span>
                                                @if($isCorrect)
                                                    <span class="ml-auto text-[10px] font-bold text-emerald-600">✓ Jawaban Benar</span>
                                                @endif
                                            </label>
                                        @endif
                                    @endforeach
                                </div>

                                @if(!empty($q['pembahasan']))
                                    <div class="mt-2 p-3 bg-indigo-50 rounded-xl text-xs text-indigo-800 border border-indigo-100">
                                        <p class="font-bold mb-0.5">💡 Pembahasan:</p>
                                        <p class="leading-relaxed">{{ $q['pembahasan'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5 font-medium text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Sistem penilaian otomatis aktif (Auto-grading)
                    </span>
                    <span class="text-slate-400 font-medium">{{ count($questions) }} soal · Data tersimpan terakhir</span>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
