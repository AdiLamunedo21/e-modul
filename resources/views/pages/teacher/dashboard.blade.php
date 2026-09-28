@extends('layouts.teacher.dashboardteacher')

@section('title', 'Teacher Workspace — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Dashboard Workspace Guru')

@section('content')

<div x-data="{ deleteModalOpen: false, deleteUrl: '', deleteTitle: '' }">

{{-- ══ Banner Sambutan Selamat Datang (Otomatis Hilang dalam 5 Detik) ══ --}}
<div x-data="{
        showBanner: true,
        progressWidth: 100,
        timer: null,
        init() {
            this.$nextTick(() => {
                setTimeout(() => { this.progressWidth = 0; }, 50);
            });
            this.timer = setTimeout(() => {
                this.close();
            }, 5000);
        },
        close() {
            if (this.timer) clearTimeout(this.timer);
            this.showBanner = false;
        }
     }"
     x-show="showBanner"
     x-cloak
     x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
     x-transition:enter-start="opacity-0 -translate-y-4 scale-[0.98]"
     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
     x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
     x-transition:leave-start="opacity-100 max-h-[350px] mb-6"
     x-transition:leave-end="opacity-0 max-h-0 mb-0 py-0 -translate-y-4 overflow-hidden"
     class="relative overflow-hidden mb-6 rounded-3xl bg-gradient-to-r from-blue-700 via-indigo-800 to-slate-900 text-white p-5 sm:p-6 shadow-xl shadow-indigo-950/20 border border-blue-400/30">
    
    {{-- Aksen Latar Belakang --}}
    <div class="absolute -right-8 -top-8 w-44 h-44 rounded-full bg-blue-400/10 blur-2xl pointer-events-none"></div>
    <div class="absolute -left-8 -bottom-8 w-44 h-44 rounded-full bg-indigo-400/10 blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex items-start justify-between gap-4">
        <div class="space-y-2 max-w-3xl">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/15 text-blue-100 border border-white/20 backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-300 animate-pulse"></span>
                    E-Modul Pembelajaran Interaktif
                </span>
                <span class="text-xs font-medium text-slate-300">SMKN 3 Yogyakarta</span>
            </div>
            
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight">
                Selamat Datang, {{ $teacher->name ?? 'Bapak/Ibu Guru' }} 👋
            </h1>

            <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                Kelola modul modular 5 bagian, bagikan ke perpustakaan bersama, pantau perkembangan siswa binaan, dan lakukan penilaian adaptif di <strong class="text-white font-bold">Grading Center</strong>.
            </p>
        </div>

        {{-- Tombol Tutup Banner --}}
        <button type="button"
                @click="close()"
                class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center text-xs font-bold transition-all cursor-pointer shrink-0 border border-white/10"
                title="Tutup banner">
            ✕
        </button>
    </div>

    {{-- Progress Bar Countdown 5 Detik --}}
    <div class="relative z-10 w-full bg-white/15 rounded-full h-1 overflow-hidden mt-4">
        <div class="bg-gradient-to-r from-blue-300 via-indigo-200 to-teal-300 h-full rounded-full transition-all duration-[5000ms] ease-linear"
             :style="'width: ' + progressWidth + '%'"></div>
    </div>
</div>

{{-- ══ Header Workspace & Contextual Actions ══ --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        {{-- Banner Tanggung Jawab Mata Pelajaran Guru --}}
        @if($teacher->subjects->isNotEmpty())
            <div class="inline-flex items-center gap-2.5 px-3.5 py-2 rounded-2xl bg-slate-900 text-white shadow-sm border border-slate-700/60 flex-wrap">
                <span class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
                    <span>🎯</span>
                    <span>Tanggung Jawab Mapel:</span>
                </span>
                <div class="flex items-center gap-1.5 flex-wrap">
                    @foreach($teacher->subjects as $subj)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-xl text-xs font-bold {{ $subj->badgeClasses() }}">
                            <span>{{ $subj->icon }}</span>
                            <span>{{ $subj->name }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <h2 class="text-base font-bold text-slate-800 tracking-tight">Workspace Guru</h2>
            </div>
        @endif
    </div>
    
    {{-- Quick Action Hub --}}
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('teacher.library.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
            </svg>
            <span>Perpustakaan Modul</span>
            @if(($stats['total_shared_library'] ?? 0) > 0)
                <span class="ml-0.5 px-1.5 py-0.2 rounded-md text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ $stats['total_shared_library'] }}
                </span>
            @endif
        </a>
        <a href="{{ route('teacher.live-quiz.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-emerald-700 hover:border-emerald-300 transition-all">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>
            <span>Kuis Live</span>
        </a>
        <a href="{{ route('teacher.modules.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 hover:shadow-blue-600/35 transition-all">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Buat Modul Baru</span>
        </a>
    </div>
</div>

@if(!empty($activeLiveQuiz))
    {{-- Banner Kuis Live Sedang Berlangsung di Kelas --}}
    <div class="mb-8 rounded-3xl bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-950 p-6 sm:p-7 text-white shadow-xl shadow-emerald-950/30 border border-emerald-500/40 relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1.5 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-xs font-black uppercase tracking-wider text-emerald-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span>Sesi Kuis Live Sedang Berlangsung di Kelas</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black tracking-tight text-white flex flex-wrap items-center gap-2">
                <span>{{ $activeLiveQuiz->module->title ?? 'Kuis Live' }}</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase">
                    {{ $activeLiveQuiz->test_type === 'pre_test' ? 'Pre-Test' : 'Post-Test' }}
                </span>
            </h3>
            <p class="text-slate-300 text-xs sm:text-sm">
                Target Kelas: <strong class="text-white">{{ $activeLiveQuiz->schoolClass?->full_name ?? 'Umum' }}</strong> • Siswa dapat langsung bergabung melalui tombol <strong class="text-emerald-300">Gabung Kuis Sekarang</strong> di dashboard masing-masing.
            </p>
        </div>
        <div class="flex items-center gap-2.5 z-10 shrink-0">
            <a href="{{ route('teacher.live-quiz.host', $activeLiveQuiz) }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black text-xs sm:text-sm shadow-xl shadow-emerald-500/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                <span>Buka Layar Pantau Proyektor</span>
            </a>
        </div>
    </div>
@endif

{{-- ══ Stat Cards & Fitur Cepat (4 Dynamic Cards Grid) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Card 1: Total Modul Saya & Library Modul --}}
    <div class="group relative flex flex-col justify-between rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-300 transition-all">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total E-Modul Saya</span>
                <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_modules'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Modul Ajar</span>
            </div>
            <div class="mt-2.5 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $stats['published_modules'] }} Terbit
                </span>
                <span>•</span>
                <span class="text-amber-600 font-semibold">{{ $stats['draft_modules'] }} Draf</span>
            </div>
        </div>

        {{-- Fitur Cepat: Manajer Modul --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
            <a href="{{ route('teacher.modules.index') }}" class="group/link inline-flex items-center justify-between w-full text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors" title="Buka Katalog & Manajer Modul">
                <span>Buka Manajer Modul</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>

    {{-- Card 2: Siswa & Kelas Binaan --}}
    <div class="group relative flex flex-col justify-between rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-sky-300 transition-all">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Siswa & Kelas Binaan</span>
                <div class="p-2.5 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 group-hover:bg-sky-600 group-hover:text-white group-hover:border-sky-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_students'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Siswa Aktif</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-sky-700 font-medium">
                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21"/></svg>
                <span>Tersebar di {{ $stats['total_classes'] }} Kelas Binaan</span>
            </div>
        </div>

        {{-- Fitur Cepat: Kelas Binaan --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
            <a href="{{ route('teacher.classes.index') }}" class="group/link inline-flex items-center justify-between w-full text-xs font-bold text-sky-600 hover:text-sky-700 transition-colors" title="Lihat Direktori Siswa & Rombel Kelas">
                <span>Lihat Kelas Binaan</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>

    {{-- Card 3: Antrean Grading Center --}}
    <div class="group relative flex flex-col justify-between rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-amber-300 transition-all">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Perlu Dinilai (Grading)</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 group-hover:bg-amber-600 group-hover:text-white group-hover:border-amber-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-amber-600">{{ $stats['pending_grading'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Antrean Tugas</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs font-semibold text-amber-600">
                @if(($stats['pending_grading'] ?? 0) > 0)
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                    <span>Tugas Menunggu Pemeriksaan</span>
                @else
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-emerald-600 font-semibold">Semua Berkas Dinilai ✓</span>
                @endif
            </div>
        </div>

        {{-- Fitur Cepat: Grading Center --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
            <a href="{{ route('teacher.grading.index') }}" class="group/link inline-flex items-center justify-between w-full text-xs font-bold text-amber-600 hover:text-amber-700 transition-colors" title="Buka Pusat Penilaian Adaptif">
                <span>Grading Center</span>
                <span class="inline-flex items-center gap-1.5">
                    @if(($stats['pending_grading'] ?? 0) > 0)
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                            {{ $stats['pending_grading'] }} Antrean
                        </span>
                    @else
                        <span class="text-[11px] text-emerald-600 font-semibold">Selesai ✓</span>
                    @endif
                    <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>
        </div>
    </div>

    {{-- Card 4: Laporan Excel (Rekap Nilai Siswa) --}}
    <div class="group relative flex flex-col justify-between rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all">
        <div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Laporan Excel</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-black text-emerald-600">.xlsx</span>
                <span class="text-xs font-semibold text-slate-500">Spreadsheet Resmi</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Skor Rata-rata: {{ $stats['average_score'] }}</span>
                </span>
                <span>•</span>
                <span class="text-slate-600 font-medium">{{ $stats['completion_rate'] }}% KKM</span>
            </div>
        </div>

        {{-- Fitur Cepat: Pusat Penilaian & Rekap --}}
        <div class="mt-4 pt-3 border-t border-slate-100">
            <a href="{{ route('teacher.grading.index') }}" class="group/link inline-flex items-center justify-between w-full text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors" title="Buka Penilaian & Unduh Rekap Nilai Excel">
                <span>Buka Rekap & Nilai</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- ══ Bottom Section: Grading Center Queue ══ --}}
<div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>Antrean Penilaian Adaptif (Grading Center)</span>
                    @if(count($pendingQueueSorted) > 0)
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    @endif
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Berkas kiriman siswa yang memerlukan verifikasi dan penilaian manual guru.</p>
            </div>
            <a href="{{ route('teacher.grading.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                Buka Grading Center ({{ $stats['pending_grading'] }}) →
            </a>
        </div>

        @if(count($pendingQueueSorted) > 0)
            <div class="divide-y divide-slate-100">
                @foreach($pendingQueueSorted as $sub)
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 shrink-0 text-xs">
                                {{ strtoupper(substr($sub['student_name'] ?? 'S', 0, 2)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-bold text-slate-800">{{ $sub['student_name'] }}</p>
                                    <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                                        {{ $sub['class_name'] }}
                                    </span>
                                    @if(($sub['pending_tasks_count'] ?? 1) > 1)
                                        <span class="text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full">
                                            {{ $sub['pending_tasks_count'] }} Berkas Dikumpulkan
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Modul: <span class="font-medium text-slate-700">{{ $sub['module_title'] }}</span>@if(count($sub['module_titles'] ?? []) > 1)<span class="text-slate-400 text-[11px]"> (+{{ count($sub['module_titles']) - 1 }} modul lain)</span>@endif • 
                                    Tugas: <span class="font-semibold text-slate-800">{{ implode(', ', $sub['task_labels'] ?? [$sub['type_label']]) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            @if(($sub['pending_tasks_count'] ?? 1) > 1)
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg border border-amber-200 bg-amber-50 text-amber-700">
                                    {{ $sub['pending_tasks_count'] }} Berkas Pending
                                </span>
                            @else
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg border {{ $sub['badge_color'] }}">
                                    {{ $sub['file_badge'] }}
                                </span>
                            @endif
                            <a href="{{ route('teacher.grading.show', $sub['module_id']) }}" class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all">
                                Beri Nilai
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-10 text-center">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Semua Tugas Telah Dinilai</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    Tidak ada antrean tugas siswa yang pending. Seluruh pengumpulan tugas telah diperiksa.
                </p>
            </div>
        @endif
    </div>

    {{-- Footer Antrean --}}
    <div class="pt-3 mt-2 border-t border-slate-100 text-xs text-slate-400">
        <span>💡 Nilai tugas siswa disinkronkan langsung ke rekap capaian belajar.</span>
    </div>
</div>

{{-- Include Delete Confirmation Modal --}}
@include('pages.teacher.modules.partials.delete-modal')

</div>
@endsection
