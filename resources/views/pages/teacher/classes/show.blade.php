@extends('layouts.teacher.dashboardteacher')
@section('title', $class->full_name . ' — Detail Kelas & Siswa')
@section('page-title', 'Detail Kelas & Siswa')

@section('content')
<div x-data="classDetailPage()" class="space-y-8 pb-12">

    {{-- Flash Alert --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ══ 1. HEADER & BREADCRUMB ══ --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 border border-blue-700/40 mb-8">
        {{-- Glow Elements --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-4">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-semibold text-blue-200/90">
                <a href="{{ route('teacher.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span class="text-blue-300/40">/</span>
                <a href="{{ route('teacher.classes.index') }}" class="hover:text-white transition-colors">Build Kelas</a>
                <span class="text-blue-300/40">/</span>
                <span class="text-white font-bold">{{ $class->full_name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
                        <span class="flex items-center gap-1.5 text-blue-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                            <span>Kelas {{ $class->grade }} &bull; Rombel {{ $class->section }}</span>
                        </span>
                        <span class="text-white/30">•</span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/40 uppercase tracking-wider">
                            {{ $class->major ? $class->major->code : $class->major_name }}
                        </span>
                        <span class="text-white/30">•</span>
                        <span class="text-[11px] text-slate-300 font-medium">SMK Negeri 3 Yogyakarta</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                        {{ $class->full_name }}
                    </h1>
                    <p class="text-slate-200 text-sm max-w-2xl leading-relaxed font-normal">
                        {{ $class->major ? $class->major->name : $class->major_name }} &bull; Kelola data siswa, portofolio modul, impor modul dari kelas lain, dan rekapitulasi nilai akademik.
                    </p>

                    {{-- ══ KODE KELAS & SHARE SISWA ══ --}}
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <div class="inline-flex items-center gap-3 bg-slate-950/60 border border-white/20 px-4 py-2 rounded-2xl backdrop-blur-md shadow-sm" x-data="{ copied: false }">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-blue-200">KODE KELAS:</span>
                                <span class="font-mono text-base font-black text-yellow-300 tracking-widest select-all">{{ $class->code }}</span>
                            </div>
                            <div class="h-4 w-px bg-white/20"></div>
                            <button type="button"
                                    @click="navigator.clipboard.writeText('{{ $class->code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="inline-flex items-center gap-1.5 text-xs font-extrabold text-white hover:text-blue-300 transition-colors"
                                    title="Salin Kode Kelas">
                                <svg x-show="!copied" class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                <span x-text="copied ? 'Tersalin!' : 'Salin Kode'"></span>
                            </button>
                        </div>

                        <button type="button"
                                x-data="{ shared: false }"
                                @click="navigator.clipboard.writeText('Silakan bergabung ke kelas {{ addslashes($class->full_name) }} di E-Modul SMKN 3 Yogyakarta menggunakan Kode Kelas: {{ $class->code }} (Link Pendaftaran: {{ url('/register/student') }}?code={{ $class->code }})'); shared = true; setTimeout(() => shared = false, 2000)"
                                class="px-3.5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold transition-all flex items-center gap-1.5 backdrop-blur-md">
                            <svg x-show="!shared" class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                            <svg x-show="shared" x-cloak class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span x-text="shared ? 'Teks Undangan Disalin!' : 'Bagikan ke Siswa'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a href="{{ route('teacher.classes.index') }}"
                       class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                        ← Kembali ke Build Kelas
                    </a>

                    {{-- Tombol Import Modul --}}
                    <button @click="importModalOpen = true"
                            type="button"
                            class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-lg shadow-indigo-950/40 flex items-center gap-2 border border-indigo-400/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Import Modul Lain</span>
                    </button>

                    {{-- Tombol Hapus Kelas (Purge) --}}
                    <button @click="deleteModalOpen = true"
                            type="button"
                            class="px-4 py-2.5 rounded-xl bg-rose-600/80 hover:bg-rose-600 text-white text-xs font-bold transition-all shadow-lg shadow-rose-950/40 flex items-center gap-2 border border-rose-400/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        <span>Hapus Kelas</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ 2. RINGKASAN METRIK KELAS KHUSUS GURU ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Siswa Terdaftar --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Terdaftar</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['total_students'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Siswa</span>
                </div>
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Dalam rombel kelas</p>
            </div>
        </div>

        {{-- Modul Guru Terbit --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Guru</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['published_modules'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $classStats['total_modules'] }} Modul</span>
                </div>
                <p class="text-[11px] text-blue-600 font-medium mt-0.5">Diberikan ke kelas ini</p>
            </div>
        </div>

        {{-- Total Pengumpulan Tugas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengumpulan Tugas</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['total_submissions'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Tugas</span>
                </div>
                <p class="text-[11px] text-emerald-600 font-medium mt-0.5">{{ $classStats['graded_count'] }} sudah dinilai</p>
            </div>
        </div>

        {{-- Rata-rata Nilai Kelas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.003 0H9.497m5.003 0a3.375 3.375 0 00-6.003 0" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai Kelas</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black {{ $classStats['avg_score'] >= 75 ? 'text-emerald-600' : ($classStats['avg_score'] > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                        {{ $classStats['avg_score'] > 0 ? $classStats['avg_score'] : '-' }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500">/ 100 Skala</span>
                </div>
                <p class="text-[11px] text-amber-600 font-medium mt-0.5">Standar ketuntasan &ge; 75</p>
            </div>
        </div>
    </div>

    {{-- ══ 3. TAB NAVIGASI: DIREKTORI SISWA VS PORTOFOLIO MODUL ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        {{-- Tab Switcher --}}
        <div class="flex items-center justify-between border-b border-slate-200/80 px-6 pt-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <button type="button" @click="activeTab = 'students'"
                        class="pb-3 text-sm font-bold border-b-2 transition-all flex items-center gap-2"
                        :class="activeTab === 'students' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span>Direktori Siswa ({{ $students->count() }})</span>
                </button>

                <button type="button" @click="activeTab = 'modules'"
                        class="pb-3 text-sm font-bold border-b-2 transition-all flex items-center gap-2"
                        :class="activeTab === 'modules' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span>Modul di Kelas Ini ({{ $teacherModules->count() }})</span>
                </button>
            </div>

            {{-- Quick Action Links --}}
            <div class="hidden sm:flex items-center gap-2 pb-2">
                <a href="{{ route('teacher.grading.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition-colors border border-blue-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    <span>Grading Center</span>
                </a>
                <a href="{{ route('teacher.reports.class', $class->id) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold transition-colors border border-emerald-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5" />
                    </svg>
                    <span>Laporan Excel</span>
                </a>
            </div>
        </div>

        {{-- ── TAB CONTENT 1: DIREKTORI SISWA ─────────────────────────────── --}}
        <div x-show="activeTab === 'students'" class="p-6 space-y-6">
            {{-- Search Bar Siswa --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form action="{{ route('teacher.classes.show', $class) }}" method="GET" class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search_student" value="{{ request('search_student') }}"
                           placeholder="Cari nama atau NISN siswa..."
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400">
                </form>

                <p class="text-xs text-slate-400">
                    Menampilkan <strong class="text-slate-700">{{ $students->count() }}</strong> Siswa
                </p>
            </div>

            {{-- Tabel Siswa --}}
            @if($students->isEmpty())
                <div class="rounded-2xl bg-slate-50 p-8 text-center border border-slate-200">
                    <p class="text-sm font-bold text-slate-700">Tidak ada data siswa ditemukan.</p>
                    <p class="text-xs text-slate-400 mt-1">Belum ada siswa terdaftar pada kelas ini atau hasil pencarian nihil.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-900 text-white font-bold text-xs uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">Nama Siswa</th>
                                <th class="py-3.5 px-4">NISN</th>
                                <th class="py-3.5 px-4 text-center">Modul Diikuti</th>
                                <th class="py-3.5 px-4 text-center">Rata-rata Nilai</th>
                                <th class="py-3.5 px-4 text-center">Status Nilai</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($students as $index => $student)
                                @php
                                    $acad = $student->academic_summary;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors {{ $index % 2 === 1 ? 'bg-slate-50/30' : '' }}">
                                    {{-- No --}}
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- Nama Siswa & Avatar --}}
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                                {{ strtoupper(substr($student->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 leading-tight">
                                                    {{ $student->name }}
                                                </p>
                                                <p class="text-[11px] text-slate-400">
                                                    {{ $class->full_name }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- NISN --}}
                                    <td class="py-3.5 px-4 font-mono font-medium text-slate-600">
                                        {{ $student->identity_number }}
                                    </td>

                                    {{-- Modul Diikuti --}}
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center gap-1 font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg text-xs">
                                            {{ $acad['submitted_count'] }} <span class="font-normal text-slate-400">/ {{ $acad['total_modules'] }} Modul</span>
                                        </span>
                                    </td>

                                    {{-- Rata-rata Nilai --}}
                                    <td class="py-3.5 px-4 text-center font-black">
                                        @if($acad['avg_score'] !== null)
                                            <span class="text-sm {{ $acad['avg_score'] >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                                {{ $acad['avg_score'] }} Poin
                                            </span>
                                        @else
                                            <span class="text-slate-300 font-normal">-</span>
                                        @endif
                                    </td>

                                    {{-- Status Ketuntasan Nilai --}}
                                    <td class="py-3.5 px-4 text-center">
                                        @if($acad['kktp_status'] === 'Tuntas')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Tuntas
                                            </span>
                                        @elseif($acad['kktp_status'] === 'Belum Tuntas (Remedial)')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Remedial
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                                Belum Ada Nilai
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Tombol Aksi Detail --}}
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button"
                                                @click="fetchStudentSummary({{ $student->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-bold text-xs transition-all border border-blue-200 hover:border-transparent">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Rincian Nilai</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── TAB CONTENT 2: PORTOFOLIO MODUL GURU ──────────────────────── --}}
        <div x-show="activeTab === 'modules'" class="p-6 space-y-6">
            <div class="flex items-center justify-between gap-4 flex-wrap pb-2 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900">Daftar Modul Pembelajaran di Kelas Ini</h3>
                    <p class="text-xs text-slate-500">Modul yang didistribusikan untuk siswa rombel {{ $class->full_name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="importModalOpen = true"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Import Modul dari Kelas Lain</span>
                    </button>
                    <a href="{{ route('teacher.modules.create') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all">
                        <span>Buat Modul Baru</span>
                    </a>
                </div>
            </div>

            @if($teacherModules->isEmpty())
                <div class="rounded-2xl bg-slate-50 p-12 text-center border border-slate-200">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Modul untuk Kelas Ini</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                        Anda dapat mengimpor modul yang sudah pernah dibuat di kelas lain atau membuat modul baru dari awal.
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <button @click="importModalOpen = true"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md transition-all">
                            <span>📥 Import dari Kelas Lain</span>
                        </button>
                        <a href="{{ route('teacher.modules.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition-all">
                            <span>Buat Modul Baru</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($teacherModules as $mod)
                        @php
                            $activeComps = $mod->activeComponents();
                            $stats = $mod->gradingStats();
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                            <div class="space-y-3">
                                {{-- Status Badges --}}
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $mod->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($mod->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700') }}">
                                        {{ ucfirst($mod->status) }}
                                    </span>
                                    @if($mod->subject)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold {{ $mod->subject->badgeClasses() }}">
                                            {{ $mod->subject->name }}
                                        </span>
                                    @endif
                                </div>

                                <h4 class="text-base font-bold text-slate-900 leading-snug">
                                    {{ $mod->title }}
                                </h4>

                                {{-- Komponen Penilaian Aktif --}}
                                <div class="flex flex-wrap gap-1 pt-1">
                                    @foreach($activeComps as $comp)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            {{ $comp }}
                                        </span>
                                    @endforeach
                                </div>

                                {{-- Metrik Modul --}}
                                <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                                    <div class="p-2 rounded-xl bg-slate-50">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Submisi</p>
                                        <p class="text-sm font-black text-slate-800">{{ $stats['submitted_count'] }}</p>
                                    </div>
                                    <div class="p-2 rounded-xl bg-slate-50">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Dinilai</p>
                                        <p class="text-sm font-black text-emerald-600">{{ $stats['graded_count'] }}</p>
                                    </div>
                                    <div class="p-2 rounded-xl bg-slate-50">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Rata-rata</p>
                                        <p class="text-sm font-black text-blue-600">{{ $stats['avg_score'] > 0 ? $stats['avg_score'] : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Aksi Modul --}}
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2 mt-4">
                                <a href="{{ route('teacher.modules.show', $mod) }}"
                                   class="flex-1 text-center py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                                    Buka Builder Modul
                                </a>
                                <a href="{{ route('teacher.grading.show', $mod) }}"
                                   class="flex-1 text-center py-2 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors shadow-xs">
                                    Beri Nilai
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL 1: IMPORT MODUL DARI KELAS LAIN KE KELAS INI                           --}}
    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="importModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="importModalOpen = false"></div>

        {{-- Dialog Wrapper --}}
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
            <div @click.stop
                 class="relative w-full max-w-xl transform rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-200 overflow-hidden my-8">
                
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 sm:px-8 pt-6 sm:pt-7 pb-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-lg border border-indigo-100 shrink-0">
                            📥
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 leading-tight">Import Modul ke {{ $class->full_name }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Salin instrumen e-modul dari kelas lain ke kelas ini secara mandiri</p>
                        </div>
                    </div>
                    <button @click="importModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center text-base transition-colors shrink-0">&times;</button>
                </div>

                {{-- Form Body --}}
                <form action="{{ route('teacher.classes.import-modules', $class) }}" method="POST">
                    @csrf
                    <div class="p-6 sm:p-8 space-y-4">
                        @if($otherClassModules->isEmpty())
                            <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-700">Tidak ada modul dari kelas lain.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Buat modul baru di kelas lain terlebih dahulu untuk dapat diimpor.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Pilih Modul yang Ingin Diimpor:
                                    </label>
                                    <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-200">
                                        {{ $otherClassModules->count() }} Modul Tersedia
                                    </span>
                                </div>

                                {{-- Scrollable box strictly limited to ~5 items (280px) --}}
                                <div style="max-height: 280px; overflow-y: auto;" class="space-y-2 pr-1.5 [scrollbar-width:thin] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-slate-300 [&::-webkit-scrollbar-track]:bg-slate-100">
                                    @foreach($otherClassModules as $mod)
                                        <label class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200/90 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all cursor-pointer group">
                                            <input type="checkbox" name="module_ids[]" value="{{ $mod->id }}"
                                                   class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="font-bold text-xs text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $mod->title }}</span>
                                                    @if($mod->subject)
                                                        <span class="px-2 py-0.2 rounded-md text-[10px] font-extrabold {{ $mod->subject->badgeClasses() }}">
                                                            {{ $mod->subject->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                                    <span class="font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md text-[10px]">
                                                        {{ $mod->schoolClass ? $mod->schoolClass->full_name : 'Modul Umum' }}
                                                    </span>
                                                    <span>&bull;</span>
                                                    <span class="text-[10px]">Dibuat {{ $mod->created_at ? $mod->created_at->format('d M Y') : '-' }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-3">
                        <button @click="importModalOpen = false" type="button" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors">
                            Batal
                        </button>
                        @if($otherClassModules->isNotEmpty())
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/25 transition-all">
                                Import & Salin ke Kelas Ini
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL 2: HAPUS KELAS & PURGE ALUMNI/MODUL                                   --}}
    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="deleteModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="deleteModalOpen = false"></div>

        {{-- Dialog Wrapper --}}
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
            <div @click.stop
                 class="relative w-full max-w-md transform rounded-3xl bg-white text-left shadow-2xl transition-all border border-red-100 overflow-hidden my-8">
                
                {{-- Header --}}
                <div class="p-6 sm:p-8 space-y-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-black text-xl border border-red-100 shrink-0">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 leading-tight">Hapus {{ $class->full_name }}?</h3>
                            <p class="text-xs text-red-600 font-semibold mt-0.5">Modul akan dihapus dan siswa dilepaskan</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-red-50/70 border border-red-200 text-xs text-red-900 space-y-2">
                        <p>
                            Anda akan menghapus rombel <strong>{{ $class->full_name }}</strong>.
                        </p>
                        <p class="text-[11px] text-red-700 font-medium">
                            Ketentuan penghapusan kelas ini:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-[11px] text-red-800 font-medium pl-1">
                            <li>Seluruh modul pembelajaran pada kelas ini ({{ $teacherModules->count() }} modul) akan dihapus.</li>
                            <li>Siswa terdaftar ({{ $students->count() }} siswa) akan <strong>dilepaskan status kelasnya</strong>.</li>
                            <li><strong>Akun siswa TIDAK DIHAPUS</strong> (NISN, nama, dan akses login siswa tetap aman untuk bergabung ke kelas lain).</li>
                        </ul>
                    </div>
                </div>

                {{-- Footer --}}
                <form action="{{ route('teacher.classes.destroy', $class) }}" method="POST" class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button @click="deleteModalOpen = false" type="button" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md shadow-red-600/25 transition-all">
                        Ya, Hapus Kelas
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL 3: RINCIAN NILAI AKADEMIK SISWA (AJAX JSON MODAL)                   --}}
    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="studentModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="studentModalOpen = false"></div>

        {{-- Dialog Wrapper --}}
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
            <div @click.stop
                 class="relative w-full max-w-3xl transform rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-200 overflow-hidden my-8">
                
                {{-- Header Modal --}}
                <div class="flex items-start justify-between px-6 sm:px-8 pt-6 sm:pt-7 pb-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-lg border border-blue-100 shrink-0">
                            📊
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 leading-tight" x-text="selectedStudent.name || 'Memuat...'"></h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                NISN: <span class="font-mono text-slate-600 font-bold" x-text="selectedStudent.identity_number || '-'"></span> • <span x-text="selectedStudent.class_name || ''"></span>
                            </p>
                        </div>
                    </div>

                    <button @click="studentModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center text-base transition-colors shrink-0">&times;</button>
                </div>

                {{-- Loading State --}}
                <div x-show="loadingSummary" class="p-12 text-center space-y-3">
                    <div class="w-8 h-8 border-3 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                    <p class="text-xs text-slate-400 font-medium">Mengambil rekap nilai akademik siswa...</p>
                </div>

                {{-- Summary Content --}}
                <div x-show="!loadingSummary" class="p-6 sm:p-8 space-y-5">
                    {{-- Quick Aggregate Summary --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase">Rata-rata Nilai</p>
                            <p class="text-xl font-black mt-0.5"
                               :class="studentOverallAvg >= 75 ? 'text-emerald-600' : (studentOverallAvg > 0 ? 'text-amber-600' : 'text-slate-400')"
                               x-text="studentOverallAvg ? studentOverallAvg + ' Poin' : '-'"></p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase">Status Kelulusan</p>
                            <p class="text-xs font-bold mt-1.5 inline-flex items-center px-2.5 py-0.5 rounded-full"
                               :class="studentKktpStatus === 'Tuntas' ? 'bg-emerald-100 text-emerald-800' : (studentKktpStatus === 'Belum Tuntas (Remedial)' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-600')"
                               x-text="studentKktpStatus"></p>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <p class="text-[11px] font-bold text-slate-400 uppercase">Total Modul</p>
                            <p class="text-xl font-black text-slate-800 mt-0.5" x-text="studentModulesSummary.length + ' Modul'"></p>
                        </div>
                    </div>

                    {{-- Table Rincian Modul --}}
                    <div class="overflow-x-auto max-h-72 border border-slate-200 rounded-2xl">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-900 text-white font-bold uppercase sticky top-0">
                                <tr>
                                    <th class="py-2.5 px-3">Modul</th>
                                    <th class="py-2.5 px-3 text-center">Pre-Test</th>
                                    <th class="py-2.5 px-3 text-center">Video</th>
                                    <th class="py-2.5 px-3 text-center">Simulasi</th>
                                    <th class="py-2.5 px-3 text-center">Job Sheet</th>
                                    <th class="py-2.5 px-3 text-center">LKPD</th>
                                    <th class="py-2.5 px-3 text-center">Post-Test</th>
                                    <th class="py-2.5 px-3 text-center">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <template x-for="mod in studentModulesSummary" :key="mod.module_id">
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-3 font-semibold text-slate-800 max-w-[160px] truncate" x-text="mod.module_title"></td>
                                        <td class="py-2.5 px-3 text-center" x-text="mod.pre_test_score !== null ? mod.pre_test_score : '-'"></td>
                                        <td class="py-2.5 px-3 text-center" x-text="mod.video_score !== null ? mod.video_score : '-'"></td>
                                        <td class="py-2.5 px-3 text-center" x-text="mod.embed_score !== null ? mod.embed_score : '-'"></td>
                                        <td class="py-2.5 px-3 text-center" x-text="mod.job_sheet_score !== null ? mod.job_sheet_score : '-'"></td>
                                        <td class="py-2.5 px-3 text-center" x-text="mod.lkpd_score !== null ? mod.lkpd_score : '-'"></td>
                                        <td class="py-2.5 px-3 text-center" x-text="mod.post_test_score !== null ? mod.post_test_score : '-'"></td>
                                        <td class="py-2.5 px-3 text-center font-bold"
                                            :class="mod.summative_score >= 75 ? 'text-emerald-600' : (mod.summative_score !== null ? 'text-amber-600' : 'text-slate-300')"
                                            x-text="mod.summative_score !== null ? mod.summative_score : '-'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end">
                    <button @click="studentModalOpen = false" type="button"
                            class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function classDetailPage() {
    return {
        activeTab: '{{ $tab }}',
        importModalOpen: false,
        deleteModalOpen: false,
        studentModalOpen: false,
        loadingSummary: false,
        selectedStudent: {},
        studentModulesSummary: [],
        studentOverallAvg: null,
        studentKktpStatus: '',

        async fetchStudentSummary(studentId) {
            this.studentModalOpen = true;
            this.loadingSummary = true;
            this.selectedStudent = {};
            this.studentModulesSummary = [];

            try {
                const response = await fetch(`/teacher/classes/{{ $class->id }}/students/${studentId}/summary`);
                const data = await response.json();

                if (data.success) {
                    this.selectedStudent = data.student;
                    this.studentModulesSummary = data.modules_summary;
                    this.studentOverallAvg = data.overall_avg;
                    this.studentKktpStatus = data.kktp_status;
                }
            } catch (e) {
                console.error("Gagal mengambil rincian nilai siswa:", e);
            } finally {
                this.loadingSummary = false;
            }
        }
    }
}
</script>
@endsection
