@extends('layouts.admin.dashboardadmin')

@section('title', 'Dashboard Supervisi Real-Time — Admin E-Modul')
@section('page-title', 'Dashboard Supervisi')

@section('content')

{{-- ══ 1. HEADER & QUICK ACTIONS ══ --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
            <span>Dashboard Supervisi</span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Real-Time Monitor
            </span>
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Pantau produktivitas guru pengampu, interaksi belajar peserta didik, dan tata kelola kurikulum E-Modul.
        </p>
    </div>

    {{-- Action Buttons --}}
    <div class="flex items-center gap-3 flex-wrap">
        <a href="{{ route('admin.teachers.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Kelola / Tambah Guru</span>
        </a>
        <a href="{{ route('admin.students.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md shadow-slate-900/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Kelola / Tambah Siswa</span>
        </a>
    </div>
</div>

{{-- ══ 2. REAL-TIME STATISTIC METRIC CARDS ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Card 1: Total Guru --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-indigo-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Guru Aktif</span>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_teachers'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Pendidik</span>
            </div>
            <a href="{{ route('admin.teachers.index') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-700 mt-2 inline-flex items-center gap-1">
                <span>Lihat Master Guru</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

    {{-- Card 2: Total Siswa --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-sky-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Peserta Didik</span>
            <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_students'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Siswa ({{ $stats['total_classes'] }} Kelas)</span>
            </div>
            <a href="{{ route('admin.students.index') }}" class="text-[11px] font-bold text-sky-600 hover:text-sky-700 mt-2 inline-flex items-center gap-1">
                <span>Lihat Master Siswa</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

    {{-- Card 3: Total E-Modul --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-emerald-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">E-Modul Terbit</span>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['published_modules'] }}</span>
                <span class="text-xs font-semibold text-slate-500">/ {{ $stats['total_modules'] }} Total</span>
            </div>
            <p class="text-[11px] text-emerald-600 font-medium mt-1">
                {{ $stats['draft_modules'] }} modul dalam status draft
            </p>
        </div>
    </div>

    {{-- Card 4: Total Interaksi Belajar --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-purple-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas & Submisi</span>
            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_submissions'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Total Log</span>
            </div>
            <p class="text-[11px] text-purple-600 font-medium mt-1">
                LKPD: {{ $stats['total_lkpd_submissions'] }} | JobSheet: {{ $stats['total_jobsheet_submissions'] }} | Kuis: {{ $stats['total_student_results'] }}
            </p>
        </div>
    </div>
</div>

{{-- ══ 3. DUA KOLOM MONITORING: GURU & LOG AKTIVITAS SISWA ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

    {{-- ── Kolom Kiri (2 Kolom): Monitoring Produktivitas Guru ── --}}
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span>Monitoring Produktivitas Guru</span>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                            {{ $teachers->count() }} Guru Aktif
                        </span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pemantauan penerbitan modul ajar dan mata pelajaran yang diampu.</p>
                </div>
                <a href="{{ route('admin.teachers.index') }}"
                   class="text-xs font-bold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1">
                    <span>Semua Guru &rarr;</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Nama & NIP Guru</th>
                            <th class="py-3.5 px-4">Mata Pelajaran</th>
                            <th class="py-3.5 px-4 text-center">Modul Terbit</th>
                            <th class="py-3.5 px-4 text-center">Kelas Binaan</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($teachers as $teacher)
                            @php
                                $assignedClassesCount = $teacher->assignedClasses()->count();
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center border border-indigo-100 shrink-0">
                                            {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs">{{ $teacher->name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono">NIP: {{ $teacher->identity_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1 max-w-[200px]">
                                        @forelse($teacher->subjects as $subj)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $subj->name }}
                                            </span>
                                        @empty
                                            <span class="text-[11px] text-slate-400 italic">Belum ada mapel</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $teacher->published_modules_count }} Terbit
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="text-xs font-bold text-slate-700">
                                        {{ $assignedClassesCount }} Rombel
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.teachers.index', ['search' => $teacher->identity_number]) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada data guru terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Distribusi Modul per Mata Pelajaran --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center justify-between">
                <span>Distribusi Modul per Mata Pelajaran</span>
                <span class="text-xs text-slate-400 font-semibold">{{ $subjects->count() }} Mapel Aktif</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($subjects as $subj)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $subj->badgeClasses() }} mb-1">
                                {{ $subj->code }}
                            </span>
                            <h4 class="text-xs font-bold text-slate-800 truncate">{{ $subj->name }}</h4>
                            <p class="text-[10px] text-slate-400">{{ $subj->teachers_count }} Guru Pengampu</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xl font-black text-slate-900">{{ $subj->modules_count }}</span>
                            <span class="text-[10px] text-slate-500 font-medium block">Modul</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Kolom Kanan (1 Kolom): Feed Aktivitas Siswa Terbaru (Real-Time Submissions) ── --}}
    <div class="space-y-8">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span>Aktivitas Siswa Terbaru</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Log pengerjaan tugas & submisi modul.</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">
                    Master Siswa &rarr;
                </a>
            </div>

            <div class="space-y-3.5">
                @forelse($recentActivities as $act)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition-all flex flex-col gap-2">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-extrabold border {{ $act['badge_class'] }}">
                                {{ $act['badge'] }}
                            </span>
                            <span class="text-[10px] font-medium text-slate-400">
                                {{ $act['time'] ? $act['time']->diffForHumans() : '-' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900 leading-tight">
                                {{ $act['student_name'] }}
                                <span class="text-[10px] font-normal text-slate-500">({{ $act['class_name'] }})</span>
                            </p>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5" title="{{ $act['module_title'] }}">
                                Modul: <strong class="text-slate-700">{{ $act['module_title'] }}</strong>
                            </p>
                        </div>
                        @if($act['score'] !== null)
                            <div class="pt-1.5 border-t border-slate-200/50 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 font-medium">Nilai Diperoleh:</span>
                                <span class="font-black text-indigo-600">{{ $act['score'] }}/100</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">
                        Belum ada riwayat aktivitas submisi siswa.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Ringkasan Rombongan Belajar / Kelas --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center justify-between">
                <span>Rombongan Belajar (Kelas)</span>
                <span class="text-xs font-bold text-slate-400">{{ $classes->count() }} Kelas</span>
            </h3>

            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1 [scrollbar-width:thin]">
                @foreach($classes as $cls)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                        <div>
                            <span class="font-bold text-slate-800">{{ $cls->full_name }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $cls->modules_count }} Modul Ditugaskan</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-white text-slate-700 border border-slate-200 shadow-2xs">
                            {{ $cls->students_count }} Siswa
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
