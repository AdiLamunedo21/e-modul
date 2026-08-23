@extends('layouts.teacher.dashboardteacher')
@section('title', 'Build Kelas & Direktori Siswa — Teacher Workspace')
@section('page-title', 'Build Kelas')

@section('content')
<div x-data="{
    createClassModalOpen: false,
    importModalOpen: false,
    deleteModalOpen: false,
    targetClassId: null,
    targetClassName: '',
    targetStudentsCount: 0,
    targetModulesCount: 0,
    importActionUrl: '',
    deleteActionUrl: '',
    openImportModal(classId, className, url) {
        this.targetClassId = classId;
        this.targetClassName = className;
        this.importActionUrl = url;
        this.importModalOpen = true;
    },
    openDeleteModal(classId, className, studentsCount, modulesCount, url) {
        this.targetClassId = classId;
        this.targetClassName = className;
        this.targetStudentsCount = studentsCount;
        this.targetModulesCount = modulesCount;
        this.deleteActionUrl = url;
        this.deleteModalOpen = true;
    }
}" class="space-y-8 pb-12">

    {{-- Flash Alert --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

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
                        <span>Build Kelas & Manajemen Rombel</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/40 uppercase tracking-wider">
                        SMK Negeri 3 Yogyakarta
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    Pusat Build Kelas & Direktori Siswa
                </h1>
                <p class="text-slate-200 text-sm max-w-2xl leading-relaxed font-normal">
                    Buat rombel kelas baru, salin modul yang pernah Anda buat di kelas lain ke kelas ini, dan lakukan pembersihan kelas alumni beserta datanya agar database tetap optimal.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <button @click="createClassModalOpen = true"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs shadow-lg shadow-blue-950/40 border border-blue-400/30 hover:-translate-y-0.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>+ Buat Kelas Baru</span>
                </button>
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
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Rombel</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $globalStats['total_assigned_classes'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Kelas</span>
                </div>
                <p class="text-[11px] text-blue-600 font-medium mt-0.5">Rombel aktif di sistem</p>
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
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Di seluruh kelas</p>
            </div>
        </div>

        {{-- Card 3: Modul Terbit --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4 hover:border-emerald-300 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Pembelajaran</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $globalStats['published_modules'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $globalStats['total_modules'] }} Terbit</span>
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
                    <span class="text-xs font-semibold text-slate-500">/ 100 Skala</span>
                </div>
                <p class="text-[11px] text-amber-600 font-medium mt-0.5">Evaluasi sumatif aktif</p>
            </div>
        </div>
    </div>

    {{-- ══ 3. FILTER BAR ══ --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm space-y-4">
        <form action="{{ route('teacher.classes.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            
            {{-- Search Bar --}}
            <div class="relative flex-1 min-w-[240px] flex items-center">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari tingkat kelas atau jurusan (misal: X TE 2, PPLG)..."
                       class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400">
            </div>

            {{-- Dropdown Filters & Actions --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Filter Tingkat --}}
                <select name="grade" onchange="this.form.submit()"
                        class="px-3.5 py-2.5 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-700 cursor-pointer">
                    <option value="all">Semua Tingkat (X, XI, XII, XIII)</option>
                    @foreach($availableGrades as $grade)
                        <option value="{{ $grade }}" {{ request('grade') === $grade ? 'selected' : '' }}>
                            Tingkat {{ $grade }}
                        </option>
                    @endforeach
                </select>

                {{-- Filter Jurusan --}}
                <select name="major_id" onchange="this.form.submit()"
                        class="px-3.5 py-2.5 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-700 cursor-pointer">
                    <option value="all">Semua Jurusan</option>
                    @foreach($majors as $m)
                        <option value="{{ $m->id }}" {{ (string)request('major_id') === (string)$m->id ? 'selected' : '' }}>
                            {{ $m->code }} - {{ $m->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Submit Button --}}
                <button type="submit"
                        class="px-4 py-2.5 text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white rounded-xl transition-colors shadow-sm shrink-0">
                    Filter
                </button>

                @if(request()->hasAny(['search', 'grade', 'major_id']))
                    <a href="{{ route('teacher.classes.index') }}"
                       class="px-3.5 py-2.5 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition-all shrink-0">
                        ✕ Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 4. GRID KARTU BUILD KELAS ══ --}}
    @if($classes->isEmpty())
        <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 border border-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Rombel Kelas</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mb-6">
                Buat rombel kelas baru sekarang dan mulai impor modul atau daftarkan siswa ke kelas tersebut.
            </p>
            <button @click="createClassModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition-all">
                + Buat Kelas Baru
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($classes as $class)
                @php
                    $stats = $class->stats;
                    $classSubjects = $class->subjects_list ?? collect();
                @endphp

                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
                    
                    {{-- Top Accent Line --}}
                    <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-sky-500"></div>

                    {{-- Top Header Card --}}
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-blue-600 text-white">
                                    Kelas {{ $class->grade }}
                                </span>
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    Rombel {{ $class->section }}
                                </span>
                                @foreach($classSubjects as $cSub)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $cSub->badgeClasses() }}">
                                        <span>{{ $cSub->icon }}</span>
                                        <span>{{ $cSub->name }}</span>
                                    </span>
                                @endforeach
                            </div>

                            {{-- Delete Class Button (Purge) --}}
                            <button @click="openDeleteModal({{ $class->id }}, '{{ addslashes($class->full_name) }}', {{ $class->students_count }}, {{ $class->teacher_modules_count }}, '{{ route('teacher.classes.destroy', $class) }}')"
                                    title="Hapus Kelas & Purge Alumni"
                                    type="button"
                                    class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>

                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                            {{ $class->full_name }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            {{ $class->major ? $class->major->name : $class->major_name }} • {{ $class->students_count }} Siswa Terdaftar
                        </p>
                    </div>

                    {{-- Data Metrics 2x2 Grid --}}
                    <div class="p-6 bg-slate-50/50 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            {{-- Metric 1: Modul Guru --}}
                            <div class="bg-white p-3 rounded-2xl border border-slate-200/60 shadow-2xs">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modul Anda</p>
                                <p class="text-base font-black text-slate-800 mt-0.5">
                                    {{ $class->teacher_published_count }} <span class="text-xs font-normal text-slate-500">/ {{ $class->teacher_modules_count }} Terbit</span>
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
                                    {{ $class->students_count }} <span class="text-xs font-normal text-slate-500">Anak</span>
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
                    </div>

                    {{-- Actions Bar --}}
                    <div class="p-4 bg-white border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('teacher.classes.show', $class) }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-sm">
                            <span>Detail & Siswa</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>

                        {{-- Import Modul Button --}}
                        <button @click="openImportModal({{ $class->id }}, '{{ addslashes($class->full_name) }}', '{{ route('teacher.classes.import-modules', $class) }}')"
                                type="button"
                                title="Import Modul dari Kelas Lain ke Kelas Ini"
                                class="px-3.5 py-2.5 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Import Modul</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL 1: BUAT KELAS BARU                                                   --}}
    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="createClassModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="createClassModalOpen = false"></div>

        {{-- Dialog Wrapper --}}
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
            <div @click.stop
                 class="relative w-full max-w-md transform rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-200 overflow-hidden my-8">
                
                {{-- Header --}}
                <div class="flex items-center justify-between px-5 sm:px-6 pt-5 pb-3.5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black border border-blue-100 shrink-0 text-base">
                            🏫
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 leading-tight">Buat Rombel Kelas Baru</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">Tambahkan rombel kelas untuk distribusi modul</p>
                        </div>
                    </div>
                    <button @click="createClassModalOpen = false" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center text-sm transition-colors shrink-0">&times;</button>
                </div>

                {{-- Form Body --}}
                <form action="{{ route('teacher.classes.store') }}" method="POST">
                    @csrf
                    <div class="p-6 sm:p-7 space-y-5">
                        {{-- Tingkat Kelas --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Tingkat Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="grade" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="" disabled selected>Pilih Tingkat Kelas...</option>
                                <option value="X">Tingkat X (Sepuluh)</option>
                                <option value="XI">Tingkat XI (Sebelas)</option>
                                <option value="XII">Tingkat XII (Dua Belas)</option>
                                <option value="XIII">Tingkat XIII (4 Tahun)</option>
                            </select>
                        </div>

                        {{-- Jurusan --}}
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Konsentrasi Keahlian / Jurusan <span class="text-red-500">*</span>
                            </label>
                            <select name="major_id" required class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <option value="" disabled selected>Pilih Konsentrasi Keahlian...</option>
                                @foreach($majors as $m)
                                    <option value="{{ $m->id }}">{{ $m->code }} — {{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Section / Rombel --}}
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Nomor / Nama Rombel (Pararel) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="section" required placeholder="Contoh: 1, 2, atau A, B"
                                   class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <p class="text-[11px] text-slate-400 mt-1.5">Misal: Diisi <strong>2</strong> untuk menghasilkan nama kelas <em>Kelas X TE 2</em></p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-2.5">
                        <button @click="createClassModalOpen = false" type="button" class="px-3.5 py-2 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-600/25 transition-all">
                            Simpan & Buat Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL 2: IMPORT MODUL DARI KELAS LAIN                                      --}}
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
                            <h3 class="text-lg font-black text-slate-900 leading-tight">Import Modul ke <span x-text="targetClassName" class="text-indigo-600"></span></h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih modul yang pernah Anda buat di kelas lain untuk disalin ke kelas ini</p>
                        </div>
                    </div>
                    <button @click="importModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center text-base transition-colors shrink-0">&times;</button>
                </div>

                {{-- Form Body --}}
                <form :action="importActionUrl" method="POST">
                    @csrf
                    <div class="p-6 sm:p-8 space-y-4">
                        @if($myModules->isEmpty())
                            <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-700">Anda belum memiliki modul di kelas lain.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Buat modul pembelajaran terlebih dahulu untuk dapat menduplikasikannya.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Pilih Modul Pembelajaran:
                                    </label>
                                    <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-200">
                                        {{ $myModules->count() }} Modul Tersedia
                                    </span>
                                </div>

                                {{-- Scrollable box strictly limited to ~5 items (280px) --}}
                                <div style="max-height: 280px; overflow-y: auto;" class="space-y-2 pr-1.5 [scrollbar-width:thin] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-slate-300 [&::-webkit-scrollbar-track]:bg-slate-100">
                                    @foreach($myModules as $mod)
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
                        @if($myModules->isNotEmpty())
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/25 transition-all">
                                Import & Salin ke Kelas
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL 3: HAPUS KELAS & PURGE ALUMNI/MODUL                                  --}}
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
                            <h3 class="text-lg font-black text-slate-900 leading-tight">Hapus Kelas & Purge Data?</h3>
                            <p class="text-xs text-red-600 font-semibold mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-red-50/70 border border-red-200 text-xs text-red-900 space-y-2">
                        <p>
                            Anda akan menghapus <strong x-text="targetClassName"></strong> secara permanen.
                        </p>
                        <p class="text-[11px] text-red-700 font-medium">
                            Menghapus kelas ini akan secara otomatis membersihkan:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-[11px] text-red-800 font-medium pl-1">
                            <li>Seluruh data siswa alumni (<span x-text="targetStudentsCount"></span> siswa)</li>
                            <li>Seluruh rekam jejak nilai & pengumpulan tugas</li>
                            <li>Seluruh modul pembelajaran pada kelas ini (<span x-text="targetModulesCount"></span> modul)</li>
                        </ul>
                        <p class="text-[10px] text-slate-500 pt-1">
                            Fitur ini membantu mengosongkan kapasitas database dari data angkatan alumni yang sudah tidak aktif.
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <form :action="deleteActionUrl" method="POST" class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button @click="deleteModalOpen = false" type="button" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md shadow-red-600/25 transition-all">
                        Ya, Hapus & Purge Bersih
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
