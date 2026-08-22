@extends('layouts.teacher.dashboardteacher')
@section('title', 'Daftar Kelas Binaan — Teacher Workspace')
@section('page-title', 'Daftar Kelas Binaan')

@section('content')
<div class="space-y-8 pb-12">

    {{-- ══ 1. HEADER BANNER INSTITUSIONAL ══ --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 border border-blue-700/40">
        {{-- Glow Elements --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                    <span class="flex items-center gap-1.5 text-blue-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Data Akademik & Siswa</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/40 uppercase tracking-wider">
                        SMK Negeri 3 Yogyakarta
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    Pusat Kelas Binaan & Direktori Siswa
                </h1>
                <p class="text-slate-200 text-sm max-w-2xl leading-relaxed font-normal">
                    Kelola dan pantau seluruh kelas yang menjadi target distribusi modul Anda, periksa data siswa terdaftar, partisipasi pengumpulan tugas, dan capaian nilai kelas secara terpadu.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('teacher.modules.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg shadow-blue-950/40 border border-blue-400/30 hover:-translate-y-0.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Buat Modul Baru</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ══ 2. RINGKASAN METRIK AGREGAT GURU ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Card 1: Kelas Binaan Aktif --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4 hover:border-blue-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas Binaan</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $globalStats['total_assigned_classes'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Kelas Aktif</span>
                </div>
                <p class="text-[11px] text-blue-600 font-medium mt-0.5">Menerima modul Anda</p>
            </div>
        </div>

        {{-- Card 2: Total Siswa Binaan --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4 hover:border-indigo-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Terdaftar</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $globalStats['total_students'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Peserta Didik</span>
                </div>
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Di seluruh kelas binaan</p>
            </div>
        </div>

        {{-- Card 3: Modul Terbit --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4 hover:border-emerald-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Terbit</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $globalStats['published_modules'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $globalStats['total_modules'] }} Modul</span>
                </div>
                <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Siap diakses siswa</p>
            </div>
        </div>

        {{-- Card 4: Rata-rata Nilai Siswa --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4 hover:border-amber-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.003 0H9.497m5.003 0a3.375 3.375 0 00-6.003 0" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $globalStats['overall_avg_score'] > 0 ? $globalStats['overall_avg_score'] : '-' }}</span>
                    <span class="text-xs font-semibold text-slate-500">Poin</span>
                </div>
                <p class="text-[11px] text-amber-600 font-medium mt-0.5">Evaluasi sumatif aktif</p>
            </div>
        </div>
    </div>

    {{-- ══ 3. FILTER BAR & TAB NAVIGASI ══ --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm">
        <form action="{{ route('teacher.classes.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                {{-- Search Bar --}}
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari tingkat kelas atau jurusan..."
                           class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400">
                </div>

                {{-- Dropdown Filters --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    {{-- Filter Tingkat --}}
                    <select name="grade" onchange="this.form.submit()"
                            class="px-3.5 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-700">
                        <option value="">Semua Tingkat (X, XI, XII)</option>
                        @foreach($availableGrades as $grade)
                            <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                                Tingkat {{ $grade }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter Jurusan --}}
                    <select name="major" onchange="this.form.submit()"
                            class="px-3.5 py-2 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-700">
                        <option value="">Semua Jurusan</option>
                        @foreach($availableMajors as $major)
                            <option value="{{ $major }}" {{ request('major') == $major ? 'selected' : '' }}>
                                Jurusan {{ $major }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="px-4 py-2 text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white rounded-xl transition-colors shadow-sm">
                        Terapkan Filter
                    </button>

                    @if(request()->hasAny(['search', 'grade', 'major', 'filter']))
                        <a href="{{ route('teacher.classes.index') }}"
                           class="px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-xl border border-red-200 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            {{-- Quick Filter Pills --}}
            <div class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-400 font-semibold mr-1">Tampilkan:</span>
                <a href="{{ route('teacher.classes.index', array_merge(request()->except('filter'), ['filter' => 'all'])) }}"
                   class="px-3 py-1 rounded-lg font-bold transition-all {{ request('filter') !== 'assigned' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua Kelas di Sekolah ({{ $classes->count() }})
                </a>
                <a href="{{ route('teacher.classes.index', array_merge(request()->except('filter'), ['filter' => 'assigned'])) }}"
                   class="px-3 py-1 rounded-lg font-bold transition-all {{ request('filter') === 'assigned' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Hanya Kelas Binaan Aktif ({{ $globalStats['total_assigned_classes'] }})
                </a>
            </div>
        </form>
    </div>

    {{-- ══ 4. GRID KARTU KELAS BINAAN ══ --}}
    @if($classes->isEmpty())
        <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Kelas yang Sesuai</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-5">
                Tidak ditemukan data kelas dengan filter pencarian saat ini. Silakan ubah filter atau reset pencarian.
            </p>
            <a href="{{ route('teacher.classes.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition-colors">
                Tampilkan Semua Kelas
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($classes as $class)
                @php
                    $stats = $class->stats;
                    $isAssigned = $stats['is_assigned'];
                @endphp

                <div class="bg-white rounded-3xl border {{ $isAssigned ? 'border-blue-200/80 ring-1 ring-blue-500/10' : 'border-slate-200/80' }} shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
                    
                    {{-- Top Header Card --}}
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-xl text-xs font-extrabold {{ $isAssigned ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                                    Kelas {{ $class->grade }}
                                </span>
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $class->major_name }}
                                </span>
                            </div>

                            @if($isAssigned)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Binaan Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500">
                                    Tersedia
                                </span>
                            @endif
                        </div>

                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                            {{ $class->full_name }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">
                            SMK Negeri 3 Yogyakarta • {{ $stats['total_students'] }} Siswa Terdaftar
                        </p>
                    </div>

                    {{-- Data Metrics 2x2 Grid --}}
                    <div class="p-6 bg-slate-50/50 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            {{-- Metric 1: Modul Guru --}}
                            <div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modul Guru</p>
                                <p class="text-base font-black text-slate-800 mt-0.5">
                                    {{ $stats['published_modules'] }} <span class="text-xs font-normal text-slate-500">/ {{ $stats['total_modules'] }} Total</span>
                                </p>
                            </div>

                            {{-- Metric 2: Rata-rata Nilai --}}
                            <div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai</p>
                                <p class="text-base font-black {{ $stats['avg_score'] >= 75 ? 'text-emerald-600' : ($stats['avg_score'] > 0 ? 'text-amber-600' : 'text-slate-400') }} mt-0.5">
                                    {{ $stats['avg_score'] > 0 ? $stats['avg_score'] . ' Poin' : '-' }}
                                </p>
                            </div>

                            {{-- Metric 3: Siswa di Kelas --}}
                            <div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                                <p class="text-base font-black text-slate-800 mt-0.5">
                                    {{ $stats['total_students'] }} <span class="text-xs font-normal text-slate-500">Anak</span>
                                </p>
                            </div>

                            {{-- Metric 4: Pengumpulan --}}
                            <div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pengumpulan</p>
                                <p class="text-base font-black text-slate-800 mt-0.5">
                                    {{ $stats['total_submissions'] }} <span class="text-xs font-normal text-slate-500">Tugas</span>
                                </p>
                            </div>
                        </div>

                        {{-- Progress Info --}}
                        @if($isAssigned)
                            <div class="flex items-center justify-between text-xs pt-1">
                                <span class="text-slate-500 font-medium">Status Penilaian Modul:</span>
                                <span class="font-bold text-slate-700">
                                    {{ $stats['graded_count'] }} Selesai Dinilai
                                </span>
                            </div>
                        @else
                            <p class="text-[11px] text-slate-400 italic">
                                Belum ada modul yang ditugaskan untuk kelas ini.
                            </p>
                        @endif
                    </div>

                    {{-- Actions Bar --}}
                    <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between gap-2">
                        <a href="{{ route('teacher.classes.show', $class) }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-sm">
                            <span>Lihat Detail & Siswa</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>

                        @if($isAssigned)
                            {{-- Quick Link: Grading Center --}}
                            <a href="{{ route('teacher.grading.index', ['class_id' => $class->id]) }}"
                               title="Buka Matriks Penilaian Kelas Ini"
                               class="p-2.5 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 border border-slate-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                </svg>
                            </a>

                            {{-- Quick Link: Laporan Excel --}}
                            <a href="{{ route('teacher.reports.index', ['class_id' => $class->id]) }}"
                               title="Rekap Laporan Excel Kelas Ini"
                               class="p-2.5 rounded-xl text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 border border-slate-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
