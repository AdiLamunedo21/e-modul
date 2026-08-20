<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Post-test — {{ $data['judul'] ?? $module->title }}</title>
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
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 shadow-xl">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center text-lg shrink-0 border border-teal-400/30">🎯</div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan Post-test Siswa</h1>
                    <p class="text-[11px] text-slate-400">Pratinjau kuis evaluasi formatif penutup Komponen Inti.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.post-test.edit', $module) }}"
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

                {{-- Header Post-test --}}
                <div class="p-6 sm:p-8 bg-gradient-to-br from-teal-50 via-emerald-50 to-slate-50 border-b border-slate-200/70">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-100 text-teal-800 text-xs font-bold border border-teal-200/70">
                            ⏱️ Durasi: {{ $data['durasi_menit'] ?? 20 }} Menit
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200/70">
                            🎯 KKTP: {{ $data['kktp'] ?? 75 }} Poin
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold border border-indigo-200/70">
                            🏁 Komponen 7 (Penutup Inti)
                        </span>
                        @if(!empty($data['acak_soal']))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-violet-100 text-violet-700 text-xs font-bold">
                            🔀 Urutan Acak
                        </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        {{ $data['judul'] ?? 'Post-test: Evaluasi Pemahaman Materi' }}
                    </h1>
                    <p class="text-sm text-slate-600 mt-2 font-medium">
                        {{ $module->title }} — {{ $module->schoolClass ? 'Kelas ' . $module->schoolClass->grade . ' ' . $module->schoolClass->major_name : 'Semua Kelas' }}
                    </p>

                    @if(!empty($data['petunjuk']))
                        <div class="mt-4 p-4 bg-white/80 backdrop-blur-sm rounded-xl border border-teal-200/60 text-xs text-slate-700 leading-relaxed">
                            <p class="font-bold text-teal-900 mb-1 flex items-center gap-1.5">
                                <span>📋</span> Petunjuk Pengerjaan:
                            </p>
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
                            <h3 class="text-slate-700 font-bold text-sm">Belum ada butir soal yang dibuat.</h3>
                            <p class="text-slate-400 text-xs mt-1">Kembali ke editor untuk menambahkan butir soal post-test.</p>
                            <a href="{{ route('teacher.modules.post-test.edit', $module) }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 text-xs font-bold text-teal-700 bg-teal-50 rounded-xl hover:bg-teal-100 transition-colors">
                                ← Buka Editor Post-test
                            </a>
                        </div>
                    @else
                        @foreach($questions as $idx => $q)
                            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold">
                                            {{ $idx + 1 }}
                                        </span>
                                        <span class="font-extrabold text-teal-800">Soal Evaluasi #{{ $idx + 1 }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold text-[11px]">
                                            Bobot: {{ $q['bobot'] ?? 20 }} Poin
                                        </span>
                                        <span class="text-slate-400 font-medium">
                                            Kunci Guru: <strong class="text-emerald-600">{{ $q['kunci_jawaban'] ?? '-' }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <p class="text-sm font-semibold text-slate-900 leading-relaxed pt-1">
                                    {{ $q['pertanyaan'] ?? '' }}
                                </p>

                                <div class="space-y-2 pt-2">
                                    @foreach($q['pilihan'] ?? [] as $opt => $text)
                                        @if(!empty(trim($text)))
                                            @php $isCorrect = ($opt === ($q['kunci_jawaban'] ?? '')); @endphp
                                            <label class="flex items-center gap-3 p-3 rounded-xl border text-sm transition-colors cursor-pointer
                                                {{ $isCorrect ? 'bg-emerald-50/80 border-emerald-300 text-emerald-900' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                                <input type="radio" name="preview_q_{{ $idx }}" value="{{ $opt }}" class="w-4 h-4 text-teal-600" disabled>
                                                <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[11px] font-bold shrink-0
                                                    {{ $isCorrect ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                                    {{ $opt }}
                                                </span>
                                                <span class="font-medium">{{ $text }}</span>
                                                @if($isCorrect)
                                                    <span class="ml-auto text-[10px] font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">
                                                        ✓ Kunci Jawaban Benar
                                                    </span>
                                                @endif
                                            </label>
                                        @endif
                                    @endforeach
                                </div>

                                @if(!empty($q['pembahasan']))
                                    <div class="mt-3 p-3.5 bg-teal-50/80 rounded-xl text-xs text-teal-900 border border-teal-100">
                                        <p class="font-bold mb-0.5 flex items-center gap-1">
                                            <span>💡</span> Pembahasan:
                                        </p>
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
                        Sistem penilaian otomatis aktif (Auto-grading & Penentuan KKTP)
                    </span>
                    <span class="text-slate-400 font-medium">{{ count($questions) }} butir soal tersimpan</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
