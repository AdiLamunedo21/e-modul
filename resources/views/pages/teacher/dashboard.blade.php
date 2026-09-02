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

        {{-- Fitur Cepat: Rekap Nilai Excel --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <a href="{{ route('teacher.reports.index') }}" class="group/link inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors" title="Buka Direktori Rekap Nilai Excel">
                <span>Rekap Nilai Excel</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="{{ route('teacher.reports.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-100 transition-colors" title="Ekspor Spreadsheet Resmi (.xlsx)">
                <span>Ekspor Rekap</span>
            </a>
        </div>
    </div>
</div>

{{-- ══ Section: E-Modul Terbaru & Draf Pengerjaan (Maksimal 3 Modul) ══ --}}
<div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm overflow-hidden mb-8">
    {{-- Header & Tabs --}}
    <div class="border-b border-slate-100 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span>E-Modul Terbaru & Draf Pengerjaan</span>
                </h2>
                <span class="text-[11px] font-extrabold bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full">
                    3 Teratas
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Daftar 3 e-modul yang baru dibuat, ditambahkan, atau masih dalam tahap penyusunan draf.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Filter Status Tabs --}}
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold">
                <a href="{{ route('teacher.dashboard', ['status' => 'all']) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Semua
                </a>
                <a href="{{ route('teacher.dashboard', ['status' => 'draft']) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $statusFilter === 'draft' ? 'bg-white text-amber-900 shadow-sm' : 'text-slate-600 hover:text-amber-700' }}">
                    Draf ({{ $counts['draft'] }})
                </a>
                <a href="{{ route('teacher.dashboard', ['status' => 'published']) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $statusFilter === 'published' ? 'bg-white text-emerald-900 shadow-sm' : 'text-slate-600 hover:text-emerald-700' }}">
                    Terbit ({{ $counts['published'] }})
                </a>
                <a href="{{ route('teacher.dashboard', ['status' => 'shared']) }}" 
                   class="px-3 py-1.5 rounded-lg transition-all {{ $statusFilter === 'shared' ? 'bg-white text-indigo-900 shadow-sm' : 'text-slate-600 hover:text-indigo-700' }}">
                    Di Library ({{ $counts['shared'] }})
                </a>
            </div>

            {{-- Link ke Manajer Modul Lengkap --}}
            <a href="{{ route('teacher.modules.index') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700">
                <span>Manajer Modul ({{ $counts['all'] }})</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>

    {{-- Module Cards List (Maksimal 3 Modul) --}}
    <div class="p-5 sm:p-6 space-y-4">
        @forelse($modulesData as $item)
            <div class="p-5 rounded-2xl border {{ $item['status'] === 'draft' ? 'border-amber-200 bg-amber-50/15' : 'border-slate-200/70 bg-white hover:bg-blue-50/15' }} hover:border-blue-300 transition-all">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    {{-- Modul Info --}}
                    <div class="space-y-2 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Status Badge --}}
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide border {{ $item['status_label']['color'] }}">
                                ● {{ $item['status_label']['label'] }}
                            </span>

                            {{-- Subject Badge --}}
                            @if($item['model']->subject)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $item['model']->subject->badgeClasses() }}">
                                    <span>{{ $item['model']->subject->icon }}</span>
                                    <span>{{ $item['model']->subject->name }}</span>
                                </span>
                            @endif

                            {{-- Shared to Library Badge --}}
                            @if($item['is_shared'])
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                                    <span>Di Library</span>
                                    @if($item['clone_count'] > 0)
                                        <span class="text-[10px] bg-indigo-200 text-indigo-900 px-1.5 py-0.2 rounded-md font-bold">{{ $item['clone_count'] }} Klon</span>
                                    @endif
                                </span>
                            @endif

                            {{-- Target Class Badge --}}
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                {{ $item['class_name'] }}
                            </span>
                            @if(isset($item['model']))
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $item['model']->semester_badge['color'] }}">
                                    {{ $item['model']->semester_badge['short'] }}
                                </span>
                            @endif
                            <span class="text-xs text-slate-400">Diperbarui: {{ $item['updated_at_formatted'] }}</span>
                        </div>

                        {{-- Module Title --}}
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 hover:text-blue-600 transition-colors">
                            <a href="{{ route('teacher.modules.show', $item['id']) }}">
                                {{ $item['title'] }}
                            </a>
                        </h3>
                        
                        {{-- 7 Komponen Inti yang Aktif --}}
                        <div class="flex flex-wrap items-center gap-1.5 pt-1">
                            <span class="text-[11px] font-bold text-slate-500 mr-1">Komponen Inti Aktif ({{ $item['active_components_count'] }}):</span>
                            @if(count($item['active_components']) > 0)
                                @foreach($item['active_components'] as $componentName)
                                    <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">
                                        {{ $componentName }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-[11px] text-slate-400 italic">Belum ada komponen inti yang diaktifkan (Dalam pengerjaan draf)</span>
                            @endif
                        </div>
                    </div>

                    {{-- Progress Bar & Pengumpulan Siswa --}}
                    <div class="w-full lg:w-72 bg-slate-50 p-4 rounded-xl border border-slate-200/60 shrink-0">
                        <div class="flex justify-between items-center text-xs font-semibold mb-2">
                            <span class="text-slate-600">Pengumpulan Siswa</span>
                            <span class="text-blue-600 font-bold">
                                {{ $item['submitted_count'] }} / {{ $item['total_students'] }} Siswa ({{ $item['submission_percent'] }}%)
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $item['submission_percent'] }}%"></div>
                        </div>
                        <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Pending Nilai: <strong class="{{ $item['pending_count'] > 0 ? 'text-amber-600' : 'text-slate-600' }}">{{ $item['pending_count'] }} Siswa</strong></span>
                            <span class="text-emerald-600 font-bold">Selesai: {{ $item['graded_count'] }}</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 lg:flex-col lg:items-end shrink-0">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('teacher.modules.show', $item['id']) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all shadow-sm">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                <span>{{ $item['status'] === 'draft' ? 'Lanjutkan di Builder' : 'Kelola (5 Bagian)' }}</span>
                            </a>
                            <a href="{{ route('teacher.grading.show', $item['id']) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Grading ({{ $item['pending_count'] }})</span>
                            </a>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('teacher.reports.export.module', $item['id']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold text-slate-600 bg-slate-50 border border-slate-200 hover:bg-slate-100 hover:text-emerald-700 rounded-lg transition-all">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5"/></svg>
                                <span>Excel .xlsx</span>
                            </a>
                            <form action="{{ route('teacher.modules.toggle-share', $item['id']) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold {{ $item['is_shared'] ? 'text-indigo-700 bg-indigo-50 border-indigo-200' : 'text-slate-600 bg-slate-50 border-slate-200' }} border hover:bg-indigo-100 rounded-lg transition-all" title="{{ $item['is_shared'] ? 'Batalkan Berbagi ke Library' : 'Bagikan ke Perpustakaan Modul' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                                    <span>{{ $item['is_shared'] ? 'Unshare' : 'Share' }}</span>
                                </button>
                            </form>
                            <button type="button"
                                    @click="deleteModalOpen = true; deleteUrl = '{{ route('teacher.modules.destroy', $item['id']) }}'; deleteTitle = '{{ addslashes($item['title']) }}'"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 rounded-lg transition-all"
                                    title="Hapus Modul Ini">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 px-4 rounded-2xl bg-slate-50/60 border border-dashed border-slate-200">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum ada modul pada kategori ini</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-5">
                    Mulai rancang modul ajar modular 5 bagian atau duplikasi modul dari katalog perpustakaan bersama.
                </p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('teacher.modules.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all">
                        <span>Buat Modul Baru</span>
                    </a>
                    <a href="{{ route('teacher.library.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                        <span>Jelajahi Library Modul</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Footer Info: Jika modul lebih dari 3 --}}
    @if(($counts['all'] ?? 0) > 3)
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <span class="text-slate-500">
                Menampilkan <strong>{{ count($modulesData) }}</strong> modul terbaru dari total <strong>{{ $counts['all'] }}</strong> modul portofolio Anda.
            </span>
            <a href="{{ route('teacher.modules.index') }}" class="inline-flex items-center gap-1.5 font-bold text-blue-600 hover:text-blue-700 hover:underline">
                <span>Buka Manajer Modul Lengkap ({{ $counts['all'] }})</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    @endif
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
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold text-slate-800">{{ $sub['student_name'] }}</p>
                                        <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                                            {{ $sub['class_name'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Modul: <span class="font-medium text-slate-700">{{ $sub['module_title'] }}</span> • 
                                        Tugas: <span class="font-semibold text-slate-800">{{ $sub['type_label'] }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg border {{ $sub['badge_color'] }}">
                                    {{ $sub['file_badge'] }}
                                </span>
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
            <a href="{{ route('teacher.reports.index') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5"/></svg>
                <span>Unduh Rekap Nilai Excel</span>
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
