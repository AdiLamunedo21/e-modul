@extends('layouts.teacher.dashboardteacher')

@section('title', 'Mulai Kuis Live — Teacher Workspace')
@section('page-title', 'Mulai Kuis Live')

@section('content')
<script>
function liveQuizCreateForm() {
    return {
        moduleData: @json($moduleData),
        testType: '{{ old('test_type', $initialTestType ?? 'pre_test') }}',
        timeLimit: {{ old('default_time_limit', 30) }},

        get canPreTest() {
            return this.moduleData ? (Boolean(this.moduleData.has_pre_test) && Number(this.moduleData.pre_test_count) > 0) : false;
        },

        get canPostTest() {
            return this.moduleData ? (Boolean(this.moduleData.has_post_test) && Number(this.moduleData.post_test_count) > 0) : false;
        },

        get hasAnyQuestions() {
            return this.canPreTest || this.canPostTest;
        },

        autoSelectTestType() {
            if (this.canPostTest && !this.canPreTest) {
                this.testType = 'post_test';
            } else if (this.canPreTest && !this.canPostTest) {
                this.testType = 'pre_test';
            } else if (!this.canPreTest && !this.canPostTest) {
                this.testType = '';
            }
        },

        init() {
            this.autoSelectTestType();
        }
    };
}
</script>

<div class="max-w-3xl mx-auto space-y-6" x-data="liveQuizCreateForm()">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('teacher.live-quiz.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Sesi
        </a>
    </div>

    {{-- Header Banner: Tema Hijau ke Hitaman --}}
    <div class="rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-slate-950/40 relative overflow-hidden border border-emerald-500/30"
         style="background: linear-gradient(135deg, #064e3b 0%, #03251e 45%, #04080c 100%); background-color: #04080c;">
        <div class="absolute -right-10 -bottom-10 w-52 h-52 bg-emerald-600/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-8 -top-8 w-44 h-44 bg-teal-600/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-xs font-black uppercase tracking-wider text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Mode Pantau Proyektor
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Mulai Sesi Kuis Live</h1>
                <p class="text-slate-300 text-sm leading-relaxed max-w-xl font-normal">
                    Sesi kuis live langsung menggunakan modul yang sedang aktif Anda ajarkan di kelas. Tentukan <strong class="text-emerald-300 font-semibold">Tipe Evaluasi</strong> dan <strong class="text-emerald-300 font-semibold">Batas Waktu</strong>, lalu tampilkan PIN di layar proyektor.
                </p>
            </div>

            <div class="hidden sm:flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-950/70 border border-emerald-500/30 text-emerald-400 shadow-xl shadow-emerald-950/50 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Validation Error Alerts --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 space-y-1">
            <div class="font-bold flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                Terjadi kesalahan input:
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Konten Utama Form --}}
    @if(!$activeModule)
        {{-- Empty State jika belum ada modul yang diaktifkan di kelas --}}
        <div class="bg-white rounded-3xl border border-amber-200 p-8 text-center space-y-5 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 mx-auto flex items-center justify-center shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
            <div class="max-w-md mx-auto space-y-2">
                <h2 class="text-xl font-bold text-slate-800">Belum Ada E-Modul yang Diaktifkan di Kelas</h2>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Kuis Live hanya dapat dimulai dari modul yang sedang <strong class="text-slate-700">aktif diajarkan di kelas</strong>. Silakan buka menu <strong>Kelas Didik</strong> untuk mengaktifkan modul materi yang sedang berlangsung.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('teacher.classes.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white text-sm font-bold shadow-md shadow-emerald-700/20 transition-all">
                    <span>Buka Menu Kelas Didik</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <form action="{{ route('teacher.live-quiz.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="module_id" value="{{ $activeModule->id }}">
                <input type="hidden" name="class_id" value="{{ $activeModule->class_id }}">

                {{-- 1. E-Modul Aktif di Kelas --}}
                <div>
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            1. E-Modul Aktif di Kelas <span class="text-rose-500">*</span>
                            <span class="sr-only">Pilih E-Modul Sumber Soal</span>
                        </label>
                    </div>

                    <div class="mb-3 flex items-center justify-between gap-2 text-[11px] text-emerald-900 font-semibold bg-emerald-50 border border-emerald-200/80 px-4 py-2.5 rounded-2xl">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                            <span>Menampilkan modul yang sedang aktif diajarkan di kelas.</span>
                        </div>
                        <span class="text-[10px] uppercase font-extrabold tracking-wider px-2.5 py-0.5 rounded-md bg-emerald-200/80 text-emerald-900 shrink-0">
                            Aktif di Kelas
                        </span>
                    </div>

                    {{-- Kartu Modul Aktif Terpilih (Satu-satunya Modul) --}}
                    <div class="rounded-2xl border-2 border-emerald-500/40 bg-gradient-to-br from-emerald-50/50 via-teal-50/20 to-white p-5 shadow-sm space-y-3.5 transition-all">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-200 animate-pulse"></span>
                                    Modul Aktif di Kelas
                                </span>

                                {{-- Label Kelas --}}
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                    Kelas: {{ $activeModule->schoolClass->name ?? 'Semua Kelas' }}
                                </span>

                                {{-- Label Mapel --}}
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $activeModule->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                            </div>

                            <span class="text-[11px] font-bold text-emerald-800 bg-emerald-100/70 border border-emerald-200 px-2 py-0.5 rounded-md inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                                Rombel kelas otomatis terwakili
                            </span>
                        </div>

                        {{-- Judul Modul --}}
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 leading-snug">
                                {{ $activeModule->title }}
                            </h2>
                        </div>

                        {{-- Status Ketersediaan Soal --}}
                        <div class="pt-2 border-t border-slate-200/80 flex flex-wrap items-center gap-3 text-xs">
                            {{-- Pre-Test Status --}}
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-medium transition-colors"
                                 :class="canPreTest ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-slate-100 text-slate-500 border border-slate-200'">
                                <span x-show="canPreTest" class="text-emerald-700 font-bold">✓</span>
                                <span x-show="!canPreTest">🔒</span>
                                <span>Pre-Test:</span>
                                <strong>{{ $activeModule->preTest ? $activeModule->preTest->questions->count() : $activeModule->preTestQuestionCount() }} soal</strong>
                                <span x-show="!canPreTest" class="text-[10px] text-slate-400 font-normal">(Tidak ada soal)</span>
                            </div>

                            {{-- Post-Test Status --}}
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-medium transition-colors"
                                 :class="canPostTest ? 'bg-teal-100 text-teal-900 border border-teal-300' : 'bg-slate-100 text-slate-500 border border-slate-200'">
                                <span x-show="canPostTest" class="text-teal-700 font-bold">✓</span>
                                <span x-show="!canPostTest">🔒</span>
                                <span>Post-Test:</span>
                                <strong>{{ $activeModule->postTest ? $activeModule->postTest->questions->count() : $activeModule->postTestQuestionCount() }} soal</strong>
                                <span x-show="!canPostTest" class="text-[10px] text-slate-400 font-normal">(Tidak ada soal)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Jenis Kuis: Pre-Test vs Post-Test (Dengan Smart Locking) --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        2. Tipe Evaluasi <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- Option 1: Pre-Test --}}
                        <div @click="canPreTest ? testType = 'pre_test' : null"
                             class="rounded-2xl border-2 p-5 transition-all duration-200 flex items-start gap-3.5 relative select-none"
                             :class="!canPreTest 
                                 ? 'opacity-40 cursor-not-allowed bg-slate-100/90 border-slate-200 pointer-events-none' 
                                 : (testType === 'pre_test'
                                     ? 'border-emerald-600 bg-emerald-50/80 shadow-md ring-2 ring-emerald-500/30 cursor-pointer'
                                     : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 cursor-pointer')">
                            
                            <input type="radio" name="test_type" value="pre_test" x-model="testType" :disabled="!canPreTest" class="sr-only">
                            
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 font-extrabold text-xs transition-colors"
                                 :class="!canPreTest 
                                     ? 'bg-slate-200 text-slate-400' 
                                 : (testType === 'pre_test' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600')">
                                PRE
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="font-bold text-sm" :class="!canPreTest ? 'text-slate-400' : (testType === 'pre_test' ? 'text-emerald-950' : 'text-slate-800')">
                                        Pre-Test (Awal)
                                    </span>
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center transition-all shrink-0"
                                          :class="!canPreTest 
                                              ? 'border border-slate-200 bg-slate-100 text-slate-400 text-xs' 
                                              : (testType === 'pre_test' ? 'bg-emerald-600 text-white' : 'border border-slate-300 bg-white')">
                                        <template x-if="!canPreTest">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        </template>
                                        <template x-if="canPreTest && testType === 'pre_test'">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        </template>
                                    </span>
                                </div>

                                <template x-if="!canPreTest">
                                    <div class="mt-1.5 inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded">
                                        🔒 Terkunci (Tidak ada soal)
                                    </div>
                                </template>
                                <template x-if="canPreTest">
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                        Diagnostik pemahaman awal siswa sebelum materi dimulai. (<span class="font-semibold text-emerald-700">{{ $activeModule->preTest ? $activeModule->preTest->questions->count() : $activeModule->preTestQuestionCount() }} soal</span>)
                                    </p>
                                </template>
                            </div>
                        </div>

                        {{-- Option 2: Post-Test --}}
                        <div @click="canPostTest ? testType = 'post_test' : null"
                             class="rounded-2xl border-2 p-5 transition-all duration-200 flex items-start gap-3.5 relative select-none"
                             :class="!canPostTest 
                                 ? 'opacity-40 cursor-not-allowed bg-slate-100/90 border-slate-200 pointer-events-none' 
                                 : (testType === 'post_test'
                                     ? 'border-teal-600 bg-teal-50/80 shadow-md ring-2 ring-teal-500/30 cursor-pointer'
                                     : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50 cursor-pointer')">
                            
                            <input type="radio" name="test_type" value="post_test" x-model="testType" :disabled="!canPostTest" class="sr-only">
                            
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 font-extrabold text-xs transition-colors"
                                 :class="!canPostTest 
                                     ? 'bg-slate-200 text-slate-400' 
                                     : (testType === 'post_test' ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600')">
                                POST
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="font-bold text-sm" :class="!canPostTest ? 'text-slate-400' : (testType === 'post_test' ? 'text-teal-950' : 'text-slate-800')">
                                        Post-Test (Akhir)
                                    </span>
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center transition-all shrink-0"
                                          :class="!canPostTest 
                                              ? 'border border-slate-200 bg-slate-100 text-slate-400 text-xs' 
                                              : (testType === 'post_test' ? 'bg-teal-600 text-white' : 'border border-slate-300 bg-white')">
                                        <template x-if="!canPostTest">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        </template>
                                        <template x-if="canPostTest && testType === 'post_test'">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        </template>
                                    </span>
                                </div>

                                <template x-if="!canPostTest">
                                    <div class="mt-1.5 inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded">
                                        🔒 Terkunci (Tidak ada soal)
                                    </div>
                                </template>
                                <template x-if="canPostTest">
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                        Evaluasi pemahaman kompetensi di penutup materi. (<span class="font-semibold text-teal-700">{{ $activeModule->postTestQuestionCount() }} soal{{ $activeModule->isPostTestInheritingPreTest() ? ' otomatis dari Pre-test' : '' }}</span>)
                                    </p>
                                </template>
                            </div>
                        </div>

                    </div>

                    {{-- Alert jika modul tidak punya soal sama sekali --}}
                    <div x-show="!hasAnyQuestions" x-cloak class="mt-3 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <div>
                            <span class="font-bold">Modul ini belum memiliki butir soal evaluasi (Pre-Test maupun Post-Test).</span>
                            <p class="mt-0.5 leading-relaxed text-rose-700">
                                Silakan tambahkan butir soal terlebih dahulu di E-Modul sebelum memulai Kuis Live.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 3. Batas Waktu Default per Soal --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        3. Waktu Hitung Mundur per Soal
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach([15 => '15 Detik (Cepat)', 20 => '20 Detik (Ideal)', 30 => '30 Detik (Standar)', 60 => '60 Detik (Analitis)'] as $sec => $label)
                            <div @click="timeLimit = {{ $sec }}"
                                 class="cursor-pointer rounded-xl border-2 p-3 text-center transition-all select-none"
                                 :class="timeLimit === {{ $sec }}
                                     ? 'border-emerald-600 bg-emerald-50 text-emerald-950 font-bold ring-2 ring-emerald-500/30'
                                     : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="radio" name="default_time_limit" value="{{ $sec }}" x-model.number="timeLimit" class="sr-only">
                                <span class="text-xs">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        Siswa yang menjawab benar lebih awal akan mendapatkan poin lebih tinggi (kecepatan respon).
                    </p>
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('teacher.live-quiz.index') }}"
                       class="w-full sm:w-auto px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 text-center transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            :disabled="!hasAnyQuestions || !testType"
                            :class="(!hasAnyQuestions || !testType) ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 shadow-lg shadow-emerald-700/25 cursor-pointer'"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-white text-sm font-bold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z" />
                        </svg>
                        Buka Ruang Pantau (Lobby)
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
