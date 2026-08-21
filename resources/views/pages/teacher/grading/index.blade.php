@extends('layouts.teacher.dashboardteacher')

@section('title', 'Grading Center — Pusat Penilaian Adaptif')
@section('page-title', 'Grading Center')

@section('content')

{{-- ══ Header Workspace & Title ══ --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">
            <a href="{{ route('teacher.dashboard') }}" class="hover:text-blue-600 transition-colors">Workspace</a>
            <span>/</span>
            <span class="text-blue-600">Evaluasi & Penilaian</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            <span>Grading Center</span>
            @if($stats['pending_grading'] > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    {{ $stats['pending_grading'] }} Tugas Menunggu
                </span>
            @endif
        </h1>
        <p class="mt-1.5 text-sm text-slate-500 max-w-2xl">
            Pusat penilaian terpadu. Kolom dan instrumen penilaian beradaptasi secara dinamis menyesuaikan komponen kegiatan belajar yang Anda aktifkan pada setiap modul.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('teacher.reports.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200/90 rounded-xl hover:bg-slate-50 hover:border-slate-300 shadow-sm transition-all">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Rekap Laporan Nilai (PDF)</span>
        </a>
    </div>
</div>

{{-- ══ Stat Cards (4 Cards Grid) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Card 1: Total Modul Ajar --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Modul Pembelajaran</span>
            <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-slate-900">{{ $stats['total_modules'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Modul Terdaftar</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $stats['published_modules'] }} Published
            </span>
            <span>•</span>
            <span class="text-slate-400">{{ $stats['total_modules'] - $stats['published_modules'] }} Draft/Closed</span>
        </div>
    </div>

    {{-- Card 2: Total Tugas Masuk --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Tugas Masuk</span>
            <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-indigo-600">{{ $stats['total_submissions'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Pengerjaan Siswa</span>
        </div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-indigo-600 font-medium">
            <span>Pre-test, Video, Embed, LKPD & Post-test</span>
        </div>
    </div>

    {{-- Card 3: Menunggu Penilaian Guru --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Menunggu Penilaian</span>
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-amber-600">{{ $stats['pending_grading'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Tugas Pending</span>
        </div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-amber-700 font-semibold">
            @if($stats['pending_grading'] > 0)
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                <span>Perlu diperiksa guru</span>
            @else
                <span class="text-emerald-600">✓ Semua tugas tuntas dinilai</span>
            @endif
        </div>
    </div>

    {{-- Card 4: Selesai Dinilai & Rata-rata --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rata-rata Nilai Kelas</span>
            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-emerald-600">{{ $stats['average_score'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Poin Sumatif</span>
        </div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-slate-500">
            <span class="font-bold text-slate-700">{{ $stats['completed_grading'] }}</span>
            <span>siswa telah dinilai tuntas</span>
        </div>
    </div>
</div>

{{-- ══ Toolbar & Filter ══ --}}
<div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-sm mb-6">
    <form method="GET" action="{{ route('teacher.grading.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        {{-- Search Input --}}
        <div class="relative flex-1 min-w-[240px] flex items-center">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul modul..."
                   class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>

        {{-- Dropdowns & Filter Buttons --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Filter Kelas --}}
            <select name="class_id" onchange="this.form.submit()"
                    class="px-3.5 py-2.5 text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                <option value="">Semua Kelas Target</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->full_name }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Status --}}
            <select name="status" onchange="this.form.submit()"
                    class="px-3.5 py-2.5 text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                <option value="">Semua Status Modul</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Aktif)</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed (Selesai)</option>
            </select>

            @if(request()->hasAny(['search', 'class_id', 'status']))
                <a href="{{ route('teacher.grading.index') }}"
                   class="px-3 py-2.5 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors">
                    ✕ Reset Filter
                </a>
            @endif
        </div>
    </form>
</div>

{{-- ══ Daftar Modul untuk Penilaian (Grid Cards) ══ --}}
@if($modules->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-4 text-3xl">
            📚
        </div>
        <h3 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Modul Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
            Belum ada modul yang cocok dengan kriteria pencarian atau filter yang Anda pilih.
        </p>
        <a href="{{ route('teacher.modules.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-600/20 transition-all">
            + Buat Modul Baru
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($modules as $module)
            @php
                $mStats = $module->gradingStats();
                $statusInfo = $module->statusLabel();
                $activeComps = $module->activeGradedComponents();
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm hover:shadow-md hover:border-blue-200 transition-all flex flex-col justify-between group">
                
                {{-- Top Bar: Status & Kelas --}}
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase border {{ $statusInfo['color'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                        <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                            {{ $module->schoolClass ? $module->schoolClass->full_name : 'Semua Kelas' }}
                        </span>
                    </div>

                    {{-- Judul Modul --}}
                    <h2 class="text-base font-bold text-slate-900 line-clamp-2 group-hover:text-blue-600 transition-colors leading-snug mb-3">
                        {{ $module->title }}
                    </h2>

                    {{-- Komponen Aktif yang Dinilai --}}
                    <div class="mb-5">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                            Instrumen Penilaian Aktif ({{ count($activeComps) }}):
                        </p>
                        @if(count($activeComps) === 0)
                            <span class="text-xs text-slate-400 italic">Tidak ada komponen evaluasi diaktifkan</span>
                        @else
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($activeComps as $cKey => $cData)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-md border {{ $cData['badge'] }}">
                                        <span>{{ $cData['icon'] }}</span>
                                        <span>{{ $cData['name'] }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Bottom Area: Progres Penilaian & Action --}}
                <div class="pt-4 border-t border-slate-100 space-y-4">
                    
                    {{-- Progress Bar --}}
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-bold text-slate-700">Progres Penilaian</span>
                            <span class="font-extrabold text-blue-600">{{ $mStats['graded_count'] }} / {{ $mStats['total_students'] }} Siswa ({{ $mStats['progress_pct'] }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-500"
                                 style="width: {{ $mStats['progress_pct'] }}%;"></div>
                        </div>
                    </div>

                    {{-- Task Counters --}}
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="p-2.5 rounded-xl bg-amber-50/80 border border-amber-100">
                            <span class="block text-lg font-black text-amber-700">{{ $mStats['pending_count'] }}</span>
                            <span class="text-[10px] font-bold uppercase text-amber-800/80">Pending Grade</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-emerald-50/80 border border-emerald-100">
                            <span class="block text-lg font-black text-emerald-700">{{ $mStats['avg_score'] }}</span>
                            <span class="text-[10px] font-bold uppercase text-emerald-800/80">Rata-rata Nilai</span>
                        </div>
                    </div>

                    {{-- CTA Button --}}
                    <a href="{{ route('teacher.grading.show', $module) }}"
                       class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 hover:shadow-blue-600/30 transition-all">
                        <span>Buka Matriks Penilaian</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

            </div>
        @endforeach
    </div>
@endif

@endsection
