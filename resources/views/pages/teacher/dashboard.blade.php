@extends('layouts.teacher.dashboardteacher')

@section('title', 'Teacher Workspace — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Dashboard Workspace Guru')

@section('content')

<div x-data="{ deleteModalOpen: false, deleteUrl: '', deleteTitle: '' }">

{{-- ══ Header Workspace & Contextual Greeting ══ --}}
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
    <div>
        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                E-Modul Pembelajaran Interaktif
            </span>
            <span class="text-xs font-medium text-slate-400">SMKN 3 Yogyakarta</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Selamat Datang, {{ $teacher->name ?? 'Bapak/Ibu Guru' }} 👋
        </h1>
        <p class="mt-1 text-sm text-slate-500 max-w-3xl">
            Kelola modul modular 5 bagian, bagikan ke perpustakaan bersama, pantau perkembangan siswa binaan, dan lakukan penilaian adaptif di <strong>Grading Center</strong>.
        </p>

        {{-- Banner Tanggung Jawab Mata Pelajaran Guru --}}
        @if($teacher->subjects->isNotEmpty())
            <div class="mt-3.5 inline-flex items-center gap-2.5 px-3.5 py-2 rounded-2xl bg-slate-900 text-white shadow-sm border border-slate-700/60 flex-wrap">
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
        <a href="{{ route('teacher.modules.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 hover:shadow-blue-600/35 transition-all">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Buat Modul Baru</span>
        </a>
    </div>
</div>

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

        {{-- Fitur Cepat: Manajer Modul & Perpustakaan --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <a href="{{ route('teacher.modules.index') }}" class="group/link inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors" title="Buka Katalog & Manajer Modul">
                <span>Manajer Modul</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="{{ route('teacher.library.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg border border-indigo-100 transition-colors" title="Perpustakaan Bersama (Kloning & Berbagi Modul)">
                <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                <span>Library ({{ $stats['shared_modules'] }})</span>
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
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <a href="{{ route('teacher.classes.index') }}" class="group/link inline-flex items-center gap-1.5 text-xs font-bold text-sky-600 hover:text-sky-700 transition-colors" title="Lihat Direktori Siswa & Rombel Kelas">
                <span>Kelas Binaan</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="{{ route('teacher.classes.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-sky-700 bg-sky-50 hover:bg-sky-100 px-2.5 py-1 rounded-lg border border-sky-100 transition-colors">
                <span>Direktori Siswa</span>
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
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <a href="{{ route('teacher.grading.index') }}" class="group/link inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 hover:text-amber-700 transition-colors" title="Buka Pusat Penilaian Adaptif">
                <span>Grading Center</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            @if(($stats['pending_grading'] ?? 0) > 0)
                <a href="{{ route('teacher.grading.index') }}" class="inline-flex items-center gap-1 text-[11px] font-extrabold text-amber-800 bg-amber-100 hover:bg-amber-200 px-2.5 py-1 rounded-lg animate-pulse transition-colors">
                    <span>Periksa ({{ $stats['pending_grading'] }})</span>
                </a>
            @else
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                    <span>Selesai ✓</span>
                </span>
            @endif
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

        {{-- Fitur Cepat: Pusat Penilaian --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <a href="{{ route('teacher.grading.index') }}" class="group/link inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors" title="Buka Pusat Penilaian Adaptif">
                <span>Pusat Penilaian</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="{{ route('teacher.grading.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg border border-indigo-100 transition-colors" title="Buka Penilaian & Rekap Nilai">
                <span>Kelola Nilai</span>
            </a>
        </div>
    </div>
</div>

{{-- ══ Bottom Section: Grading Center Queue, Assigned Classes & Builder Guide ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Antrean Grading Center Live (2 Kolom) --}}
    <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200/80 shadow-sm p-5 sm:p-6 flex flex-col justify-between">
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
        <div class="pt-4 mt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Sistem penilaian adaptif sinkron dengan komponen modul</span>
            <a href="{{ route('teacher.grading.index') }}" class="font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>
                <span>Buka Pusat Penilaian</span>
            </a>
        </div>
    </div>

    {{-- Kolom Kanan: Kelas Binaan & Panduan Arsitektur E-Modul (1 Kolom) --}}
    <div class="space-y-6">

        {{-- Ringkasan Kelas Binaan --}}
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-5">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    <span>Kelas Binaan Saya</span>
                </h3>
                <a href="{{ route('teacher.classes.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Lihat Semua →</a>
            </div>

            @if(count($classesSummary) > 0)
                <div class="space-y-3">
                    @foreach($classesSummary as $cls)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center justify-between gap-3 hover:border-indigo-200 transition-colors">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-bold text-slate-800">{{ $cls['full_name'] }}</p>
                                    <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.2 rounded border border-indigo-100">
                                        {{ $cls['total_students'] }} Siswa
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $cls['published_modules'] }} Modul Terbit • Rata-rata Skor: <strong>{{ $cls['avg_score'] }}</strong>
                                </p>
                            </div>
                            <a href="{{ route('teacher.classes.show', $cls['id']) }}" class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 shadow-sm transition-all" title="Buka Detail Kelas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-3 text-center">Belum ada kelas yang terhubung dengan modul Anda.</p>
            @endif
        </div>

        {{-- Banner Arsitektur E-Module Builder (5 Bagian & 7 Sakelar) --}}
        <div class="rounded-2xl bg-gradient-to-br from-blue-700 to-indigo-800 p-5 text-white flex flex-col justify-between shadow-lg relative overflow-hidden">
            {{-- Background decorative circles --}}
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-blue-400/10 blur-lg"></div>

            <div class="relative z-10">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/20 text-[10px] font-bold tracking-wider uppercase backdrop-blur-md mb-3 border border-white/20">
                    ⚡ Standar 5 Bagian E-Modul
                </span>
                <h3 class="text-base font-black leading-snug">
                    Arsitektur E-Modul 5 Bagian Pedagogis
                </h3>
                <p class="mt-2 text-xs text-blue-100 leading-relaxed">
                    Struktur sistematis terpadu untuk pembelajaran kejuruan SMK:
                </p>

                <ul class="mt-3 space-y-1.5 text-xs text-blue-50">
                    <li class="flex items-start gap-1.5">
                        <span class="font-bold text-white bg-blue-500/40 rounded px-1 text-[10px]">1</span>
                        <span><strong>Bagian Awal:</strong> Kata Pengantar, Petunjuk Penggunaan.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="font-bold text-white bg-blue-500/40 rounded px-1 text-[10px]">2</span>
                        <span><strong>Pendahuluan:</strong> Capaian, Peta Konsep, Pre-test.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="font-bold text-white bg-blue-500/40 rounded px-1 text-[10px]">3</span>
                        <span><strong>Kegiatan Belajar:</strong> Materi PPT, Video, Simulator Embed.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="font-bold text-white bg-blue-500/40 rounded px-1 text-[10px]">4</span>
                        <span><strong>Evaluasi:</strong> Job Sheet PDF, LKPD & Post-test.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="font-bold text-white bg-blue-500/40 rounded px-1 text-[10px]">5</span>
                        <span><strong>Bagian Akhir:</strong> Daftar Pustaka & Rekap Nilai Excel.</span>
                    </li>
                </ul>
            </div>

            <div class="mt-5 pt-3 border-t border-white/15 relative z-10">
                <a href="{{ route('teacher.modules.create') }}" class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-blue-700 shadow hover:bg-blue-50 transition-colors">
                    <span>Rakit Modul Baru Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Include Delete Confirmation Modal --}}
@include('pages.teacher.modules.partials.delete-modal')

</div>
@endsection
