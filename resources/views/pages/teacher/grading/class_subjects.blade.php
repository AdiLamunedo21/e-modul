@extends('layouts.teacher.dashboardteacher')

@section('title', 'Mata Pelajaran ' . $class->full_name . ' — Grading Center')
@section('page-title', 'Pilih Mata Pelajaran')

@section('content')

{{-- ══ Header & Breadcrumb ══ --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">
            <a href="{{ route('teacher.dashboard') }}" class="hover:text-blue-600 transition-colors">Workspace</a>
            <span>/</span>
            <a href="{{ route('teacher.grading.index') }}" class="hover:text-blue-600 transition-colors">Grading Center</a>
            <span>/</span>
            <span class="text-blue-600">{{ $class->full_name }}</span>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('teacher.grading.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Daftar Kelas</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span>Mata Pelajaran di {{ $class->full_name }}</span>
            </h1>
        </div>
        <p class="mt-1.5 text-sm text-slate-500 max-w-3xl leading-relaxed">
            Jurusan: <strong class="text-slate-700">{{ $class->major ? $class->major->name : $class->major_name }}</strong> • Tingkat {{ $class->grade }} • Rombel {{ $class->section }}. Pilih mata pelajaran untuk membuka modul dan memulai pengisian nilai.
        </p>
    </div>

    <div class="flex items-center gap-3 shrink-0">
        <a href="{{ route('teacher.reports.class', $class->id) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200/90 rounded-xl hover:bg-slate-50 hover:border-slate-300 shadow-sm transition-all">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Laporan Excel Kelas Ini</span>
        </a>
    </div>
</div>

{{-- ══ Stat Cards Kelas (4 Grid) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100">
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
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Pembelajaran</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-slate-900">{{ $classStats['total_modules'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Modul ({{ $classStats['published_count'] }} Terbit)</span>
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Diperiksa</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-amber-600">{{ $classStats['pending_count'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Tugas Pending</span>
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-Rata Nilai</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-emerald-600">{{ $classStats['avg_score'] }}</span>
                <span class="text-xs font-semibold text-slate-500">/ 100 Skala</span>
            </div>
        </div>
    </div>
</div>

{{-- ══ Grid Kartu Mata Pelajaran di Kelas Ini (Tahap 2) ══ --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($subjects as $sub)
        <div class="group relative rounded-3xl bg-white border border-slate-200/80 hover:border-blue-300 transition-all duration-300 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md">
            
            {{-- Accent Top Bar --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-sky-500"></div>

            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold {{ $sub->badgeClasses() }} uppercase tracking-wider">
                        {{ $sub->code ?: 'MAPEL' }}
                    </span>

                    <span class="text-xs font-bold text-slate-400">
                        {{ $class->short_name }}
                    </span>
                </div>

                <div class="space-y-1.5">
                    <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        {{ $sub->name }}
                    </h3>
                    @if($sub->description)
                        <p class="text-xs text-slate-500 line-clamp-2">
                            {{ $sub->description }}
                        </p>
                    @endif
                </div>

                {{-- Stats Mata Pelajaran --}}
                <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Modul Ajar:</span>
                        <span class="font-bold text-slate-900 px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $sub->class_modules_count }} Modul
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Pengumpulan Tugas:</span>
                        <span class="font-bold text-slate-700">
                            {{ $sub->class_submissions_count }} Submisi
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Tugas Pending:</span>
                        <span class="font-bold text-amber-600">
                            {{ $sub->class_pending_count }} Menunggu Nilai
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Tuntas Dinilai:</span>
                        <span class="font-bold text-emerald-700">
                            {{ $sub->class_graded_count }} Siswa (Rata-rata: {{ $sub->class_avg_score }})
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Button Footer --}}
            <div class="p-4 pt-0">
                <a href="{{ route('teacher.grading.class.subject', [$class->id, $sub->id]) }}"
                   class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center gap-2 transition-all shadow-sm shadow-blue-600/25 group-hover:shadow-md">
                    <span>Buka Modul Penilaian ({{ $sub->class_modules_count }})</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
            <p class="text-sm font-bold text-slate-700">Belum ada mata pelajaran yang terkait dengan Anda di kelas ini.</p>
            <p class="text-xs text-slate-400 mt-1">Buat modul baru untuk kelas ini untuk memulai.</p>
        </div>
    @endforelse
</div>

@endsection
