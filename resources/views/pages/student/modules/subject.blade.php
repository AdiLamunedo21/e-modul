@extends('layouts.student.dashboardstudent')

@section('title', 'Modul ' . $subject->name . ' — Student Portal SMKN 3 Yogyakarta')
@section('page-title', 'Modul ' . $subject->name)

@section('content')

{{-- ══ 1. Breadcrumb Navigasi ══ --}}
<div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6 flex-wrap">
    <a href="{{ route('student.dashboard') }}" class="hover:text-emerald-700 transition-colors flex items-center gap-1">
        <span>🏠</span>
        <span>Dashboard Siswa</span>
    </a>
    <span>/</span>
    <span class="text-slate-400 font-medium">Modul Belajar</span>
    <span>/</span>
    <span class="text-slate-800 font-bold bg-slate-200/70 px-2.5 py-0.5 rounded-full">
        {{ $subject->name }}
    </span>
</div>

{{-- ══ 2. Subject Hero Header Banner (Matching Dashboard Banner Aesthetic in Blue) ══ --}}
<div class="bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 mb-8 relative overflow-hidden border border-blue-700/40">
    {{-- Decorative Background Blur Effects --}}
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/15 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3 max-w-2xl">
            {{-- Top Badge Pill --}}
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
                <span class="flex items-center gap-1.5 text-blue-200">
                    <span>Mata Pelajaran</span>
                </span>
                @if($subject->code)
                    <span class="text-white/30">•</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/40 uppercase tracking-wider">
                        {{ $subject->code }}
                    </span>
                @endif
                <span class="text-white/30 hidden sm:inline">•</span>
                <span class="text-blue-100/80 hidden sm:inline text-xs font-medium">SMKN 3 Yogyakarta</span>
            </div>

            {{-- Subject Title --}}
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                {{ $subject->name }}
            </h1>

            {{-- Description --}}
            @if($subject->description)
                <p class="text-slate-200 text-sm leading-relaxed font-normal">
                    {{ $subject->description }}
                </p>
            @endif

            {{-- Guru Pengampu Card Pill --}}
            <div class="pt-1 flex items-center gap-3">
                <div class="inline-flex items-center px-3.5 py-2 rounded-2xl bg-slate-950/50 border border-white/20 backdrop-blur-md">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Guru Pengampu</p>
                        <p class="text-xs font-bold text-white">{{ $teacherDisplay }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Circle / Summary Card --}}
        <div class="bg-slate-950/50 border border-white/20 p-5 rounded-2xl backdrop-blur-md shrink-0 shadow-sm space-y-3 min-w-[200px]">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Kemajuan Mapel</span>
                <span class="text-xs font-extrabold text-blue-400">{{ $stats['avg_progress'] }}%</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-700/50">
                <div class="h-2 rounded-full bg-blue-500 transition-all duration-500" style="width: {{ $stats['avg_progress'] }}%"></div>
            </div>
            <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs text-slate-300">
                <span>Modul Tuntas:</span>
                <span class="font-bold text-white">{{ $stats['completed_modules'] }} / {{ $stats['total_modules'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ══ 3. KPI Metrik Singkat Mata Pelajaran (3 Cards) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
    {{-- Total Modul --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Modul Mapel</p>
                <h3 class="mt-1 text-2xl font-black text-slate-900">{{ $stats['total_modules'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold">
                📚
            </div>
        </div>
        <p class="mt-2 text-xs text-slate-500">Ditugaskan untuk kelas Anda</p>
    </div>

    {{-- Sedang Dikerjakan --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Sedang Dikerjakan</p>
                <h3 class="mt-1 text-2xl font-black text-slate-900">{{ $stats['in_progress'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center font-bold">
                ⏳
            </div>
        </div>
        <p class="mt-2 text-xs text-slate-500">Dalam proses pengerjaan mandiri</p>
    </div>

    {{-- Modul Tuntas --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Modul Tuntas 100%</p>
                <h3 class="mt-1 text-2xl font-black text-slate-900">{{ $stats['completed_modules'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-bold">
                ✓
            </div>
        </div>
        <p class="mt-2 text-xs text-slate-500">Telah diselesaikan seluruh komponennya</p>
    </div>
</div>

{{-- ══ 4. Daftar E-Modul Pembelajaran Mapel Ini ══ --}}
<div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden mb-10">

    {{-- Header & Status Filter Tabs --}}
    <div class="border-b border-slate-100 p-6 sm:p-7">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg sm:text-xl font-black text-slate-900 flex items-center gap-2">
                    <span>Daftar E-Modul {{ $subject->name }}</span>
                    <span class="text-xs font-bold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full border border-emerald-200">
                        {{ $stats['total_modules'] }} Modul
                    </span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Pilih modul di bawah ini untuk mulai belajar materi, menonton video, mengerjakan LKPD & Job Sheet.
                </p>
            </div>

            {{-- Filter Status Tabs --}}
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-2xl text-xs font-bold overflow-x-auto self-start sm:self-auto shrink-0">
                <a href="{{ route('student.modules.subject', ['subject' => $subject->id, 'status' => 'all']) }}"
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $filterStatus === 'all' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Semua ({{ $stats['total_modules'] }})
                </a>
                <a href="{{ route('student.modules.subject', ['subject' => $subject->id, 'status' => 'in_progress']) }}"
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $filterStatus === 'in_progress' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Proses ({{ $stats['in_progress'] }})
                </a>
                <a href="{{ route('student.modules.subject', ['subject' => $subject->id, 'status' => 'completed']) }}"
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $filterStatus === 'completed' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Selesai ({{ $stats['completed_modules'] }})
                </a>
            </div>
        </div>
    </div>

    {{-- Module Cards List --}}
    <div class="p-6 sm:p-7 space-y-6">
        @forelse($filteredModules as $item)
            <div class="rounded-2xl border transition-all overflow-hidden
                {{ $item['progress_status'] === 'completed'
                    ? 'bg-emerald-50/20 border-emerald-200/80 hover:border-emerald-300'
                    : ($item['progress_status'] === 'in_progress'
                        ? 'bg-white border-slate-200/90 hover:border-emerald-300 hover:shadow-md'
                        : 'bg-slate-50/60 border-slate-200/70 hover:border-slate-300') }}">

                <div class="p-6 space-y-5">
                    {{-- Top Metadata Row --}}
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-2.5">
                            {{-- Status Badge --}}
                            @if($item['progress_status'] === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span>✓</span>
                                    <span>Tuntas 100%</span>
                                </span>
                            @elseif($item['progress_status'] === 'in_progress')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>Sedang Belajar ({{ $item['progress_percent'] }}%)</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-200/70 text-slate-700 border border-slate-300/60">
                                    <span>●</span>
                                    <span>Belum Dimulai</span>
                                </span>
                            @endif

                            {{-- Subject Badge --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $subject->badgeClasses() }}">
                                {{ $subject->name }}
                            </span>

                            {{-- Teacher Badge --}}
                            <span class="text-xs text-slate-500 font-medium">
                                Guru: <strong class="text-slate-800">{{ $item['teacher_name'] }}</strong>
                            </span>
                        </div>

                        {{-- Score Tag (If Graded) --}}
                        @if($item['summative_score'] !== null)
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm">
                                <span>⭐ Nilai: {{ $item['summative_score'] }}</span>
                                <span class="text-[10px] font-normal text-indigo-500">({{ $item['grading_status'] === 'graded' ? 'Dinilai' : 'Pending' }})</span>
                            </span>
                        @endif
                    </div>

                    {{-- Title & Description --}}
                    <div class="space-y-1.5">
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 hover:text-emerald-700 transition-colors leading-snug">
                            {{ $item['title'] }}
                        </h3>

                        @if($item['description'])
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed line-clamp-2">
                                {{ $item['description'] }}
                            </p>
                        @endif
                    </div>

                    {{-- Active Components Tags Cloud --}}
                    @if(count($item['active_components']) > 0)
                        <div class="pt-0.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[11px] font-bold text-slate-400 mr-1">Instrumen Modul:</span>
                                @foreach($item['active_components'] as $comp)
                                    <span class="text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200/80 px-2.5 py-1 rounded-lg">
                                        {{ $comp }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Bottom Progress & Action Row --}}
                    <div class="mt-5 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                        {{-- Progress Indicator --}}
                        <div class="flex-1 max-w-md">
                            <div class="flex justify-between items-center text-xs font-bold mb-2">
                                <span class="text-slate-600">Progres Belajar Mandiri</span>
                                <span class="{{ $item['progress_percent'] >= 100 ? 'text-emerald-600' : 'text-slate-800' }}">
                                    {{ $item['completed_tasks'] }} dari {{ $item['total_components'] }} Komponen ({{ $item['progress_percent'] }}%)
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200/70">
                                <div class="h-2.5 rounded-full transition-all duration-500 {{ $item['progress_percent'] >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-emerald-500 to-teal-500' }}"
                                     style="width: {{ $item['progress_percent'] }}%"></div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="shrink-0">
                            <a href="{{ route('student.modules.show', $item['id']) }}"
                               class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-bold rounded-xl shadow-sm transition-all
                                {{ $item['progress_status'] === 'completed'
                                    ? 'text-slate-700 bg-white border border-slate-300 hover:bg-slate-50'
                                    : ($item['progress_status'] === 'in_progress'
                                        ? 'text-white bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/25'
                                        : 'text-white bg-blue-600 hover:bg-blue-700 shadow-blue-600/25') }}">
                                <span>{{ $item['progress_status'] === 'completed' ? 'Tinjau Materi' : ($item['progress_status'] === 'in_progress' ? 'Lanjutkan Belajar' : 'Mulai Belajar') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-12 px-4 rounded-2xl bg-slate-50/80 border border-dashed border-slate-200">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Modul Pada Mata Pelajaran Ini</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-4">
                    Guru pengampu belum menerbitkan modul untuk mata pelajaran <strong>{{ $subject->name }}</strong> di kelas Anda.
                </p>
                <a href="{{ route('student.dashboard') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-all">
                    <span>← Kembali ke Dashboard Siswa</span>
                </a>
            </div>
        @endforelse
    </div>
</div>

@endsection
