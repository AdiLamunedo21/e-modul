@extends('layouts.teacher.dashboardteacher')
@section('title', 'Kelas Didik & Direktori Siswa — Teacher Workspace')
@section('page-title', 'Kelas Didik')

@section('content')
<div x-data="{
    importModalOpen: false,
    targetClassId: null,
    targetClassName: '',
    importActionUrl: '',
    openImportModal(classId, className, url) {
        this.targetClassId = classId;
        this.targetClassName = className;
        this.importActionUrl = url;
        this.importModalOpen = true;
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
                        <span>Kelas Didik & Tanggung Jawab Mengajar</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/40 uppercase tracking-wider">
                        SMK Negeri 3 Yogyakarta
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    Pusat Kelas Didik & Direktori Siswa
                </h1>
                <p class="text-slate-200 text-sm max-w-2xl leading-relaxed font-normal">
                    Daftar rombongan belajar kelas binaan yang menjadi tanggung jawab mengajar Anda sesuai penugasan Admin Kurikulum. Kelola modul pembelajaran, pantau ketuntasan belajar siswa, dan bagikan kode kelas.
                </p>
            </div>

            <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-xs text-blue-100 font-bold shrink-0">
                <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <span>Dikelola oleh Admin</span>
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
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas Didik</p>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $globalStats['total_assigned_classes'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Rombel</span>
                </div>
                <p class="text-[11px] text-blue-600 font-medium mt-0.5">Ditugaskan kepada Anda</p>
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
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Di kelas didik Anda</p>
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
                       placeholder="Cari tingkat kelas atau jurusan (misal: X TE 1, DPIB)..."
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

    {{-- ══ 4. GRID KARTU KELAS DIDIK ══ --}}
    @if($classes->isEmpty())
        <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 border border-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Kelas Didik Ditugaskan</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mb-2">
                Akun guru Anda belum ditugaskan ke rombongan belajar kelas oleh Admin Kurikulum.
            </p>
            <p class="text-xs text-blue-600 font-medium max-w-md mx-auto">
                💡 Silakan hubungi Admin Kurikulum untuk memilihkan rombel kelas didik yang menjadi tanggung jawab mengajar Anda.
            </p>
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
                        </div>

                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                            {{ $class->full_name }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            {{ $class->major ? $class->major->name : $class->major_name }} • {{ $class->students_count }} Siswa Terdaftar
                        </p>

                        {{-- ══ KODE KELAS GURU & FITUR SHARE SISWA ══ --}}
                        <div class="mt-4 p-3 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50/70 border border-blue-200/80 flex items-center justify-between gap-3 shadow-2xs">
                            <div class="min-w-0">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600">Kode Gabung Kelas</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="font-mono text-base font-black text-slate-900 tracking-wider select-all">{{ $class->code }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0" x-data="{ copied: false, shared: false }">
                                <button type="button"
                                        @click="navigator.clipboard.writeText('{{ $class->code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        :class="copied ? 'bg-emerald-600 text-white' : 'bg-white text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 shadow-2xs'"
                                        class="px-2.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                                        title="Salin Kode Kelas">
                                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                    <svg x-show="copied" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span x-text="copied ? 'Disalin!' : 'Salin'"></span>
                                </button>
                                <button type="button"
                                        @click="navigator.clipboard.writeText('Silakan bergabung ke kelas {{ addslashes($class->full_name) }} di E-Modul SMKN 3 Yogyakarta menggunakan Kode Kelas: {{ $class->code }} (Link: {{ url('/register/student') }}?code={{ $class->code }})'); shared = true; setTimeout(() => shared = false, 2000)"
                                        :class="shared ? 'bg-emerald-600 text-white' : 'bg-white text-indigo-700 hover:bg-indigo-600 hover:text-white border border-indigo-200 shadow-2xs'"
                                        class="p-1.5 rounded-xl text-xs font-bold transition-all"
                                        title="Bagikan Teks Undangan Siswa">
                                    <svg x-show="!shared" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                                    <svg x-show="shared" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </button>
                            </div>
                        </div>
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
                                title="Salin / Impor Modul ke Kelas Ini"
                                type="button"
                                class="px-3 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200/70 text-xs font-bold transition-all flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                            </svg>
                            <span class="hidden sm:inline">Impor Modul</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    {{-- ══ 5. MODAL: IMPOR MODUL DARI KELAS LAIN ══ --}}
    <div x-cloak 
         x-show="importModalOpen" 
         @keydown.escape.window="importModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

        <div x-show="importModalOpen" 
             x-transition.opacity 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"
             @click="importModalOpen = false" 
             aria-hidden="true"></div>

        <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 lg:px-8 text-center">
            <div x-show="importModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 w-full max-w-lg mx-auto transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all my-8 border border-slate-100">
                
                <form :action="importActionUrl" method="POST">
                    @csrf
                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Salin & Impor Modul</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Target: <strong class="text-blue-600" x-text="targetClassName"></strong></p>
                            </div>
                            <button type="button" @click="importModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            <p class="text-slate-600 leading-relaxed">
                                Pilih satu atau beberapa modul pembelajaran yang pernah Anda buat untuk disalin ke kelas target ini:
                            </p>

                            @if($myModules->isEmpty())
                                <div class="p-6 rounded-2xl bg-slate-50 text-center border border-slate-200">
                                    <p class="text-slate-500 font-medium">Anda belum memiliki modul ajar yang dapat diimpor.</p>
                                </div>
                            @else
                                <div class="max-h-60 overflow-y-auto space-y-2 p-1">
                                    @foreach($myModules as $mMod)
                                        <label class="flex items-start gap-3 p-3 rounded-2xl border border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/20 cursor-pointer transition-all">
                                            <input type="checkbox" name="module_ids[]" value="{{ $mMod->id }}" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 mt-0.5">
                                            <div class="min-w-0 flex-1">
                                                <p class="font-bold text-slate-900">{{ $mMod->title }}</p>
                                                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                                    <span>{{ $mMod->subject->name ?? 'Mapel' }}</span>
                                                    <span>•</span>
                                                    <span>Asal: {{ $mMod->schoolClass->full_name ?? 'Kelas' }}</span>
                                                    <span>•</span>
                                                    <span class="font-semibold {{ $mMod->status === 'published' ? 'text-emerald-600' : 'text-slate-400' }}">{{ ucfirst($mMod->status) }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button type="button" @click="importModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                {{ $myModules->isEmpty() ? 'disabled' : '' }}
                                class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/25 transition-all disabled:opacity-50">
                            Impor Modul Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
