@extends('layouts.student.dashboardstudent')

@section('title', 'Student Portal — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Dashboard Siswa')

@section('content')

{{-- ══ 1. Hero / Header Greeting Banner (Styled after Notepad Editor) ══ --}}
<div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/20 mb-8 sm:mb-10 relative overflow-hidden border border-emerald-700/40">
    {{-- Decorative Background Blur Effects --}}
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            {{-- Top Badge Pill --}}
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-emerald-200">
                    <span>🎓</span>
                    <span>Portal Belajar Siswa</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 uppercase tracking-wider">
                    E-Modul Pembelajaran
                </span>
                <span class="text-white/30 hidden sm:inline">•</span>
                <span class="text-emerald-100/80 hidden sm:inline text-xs font-medium">SMKN 3 Yogyakarta</span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                Selamat Datang, {{ $student->name ?? 'Siswa' }} 👋
            </h1>

            {{-- Description --}}
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Akses modul pembelajaran interaktif 5 bagian, pelajari materi & video, kerjakan tugas LKPD serta praktikum Job Sheet, dan pantau kemajuan belajar Anda secara mandiri.
            </p>
        </div>

        {{-- Identitas Rombel Kelas Badge Card --}}
        <div class="flex items-center gap-3.5 bg-slate-950/50 border border-white/20 p-4 rounded-2xl backdrop-blur-md shrink-0 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 text-2xl shrink-0 font-black">
                🎓
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-white uppercase tracking-wider">{{ $class->full_name ?? 'Siswa Kejuruan' }}</p>
                <p class="text-xs text-emerald-200/90 mt-0.5 font-medium">NISN: <span class="font-bold text-white">{{ $student->identity_number }}</span></p>
                <span class="inline-flex items-center gap-1.5 mt-1 text-[11px] font-bold text-emerald-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Status: Siap Belajar
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ══ 2. Real-Time KPI Stat Cards (3 Metrik Utama) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-10">

    {{-- KPI 1: Total Modul Kelas --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Modul Kelas</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 leading-none">
                    {{ $stats['total_modules'] }}
                </h3>
                <p class="mt-2 text-xs text-slate-500 truncate">
                    Tersedia untuk <strong class="text-slate-700">{{ $class->full_name ?? 'Kelas Anda' }}</strong>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0 ml-3 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
        </div>
        <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">Belum dimulai: <strong class="text-slate-700">{{ $stats['not_started'] }}</strong></span>
            <span class="text-emerald-600 font-bold">Terbit Aktif</span>
        </div>
    </div>

    {{-- KPI 2: Sedang Dipelajari --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Sedang Dikerjakan</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 leading-none">
                    {{ $stats['in_progress'] }}
                </h3>
                <p class="mt-2 text-xs text-slate-500 truncate">
                    Rata-rata progres: <strong class="text-slate-700">{{ $stats['avg_progress'] }}%</strong>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shrink-0 ml-3 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">Status Belajar:</span>
            <span class="text-amber-600 font-bold">Dalam Proses</span>
        </div>
    </div>

    {{-- KPI 3: Modul Tuntas Selesai --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Modul Tuntas</p>
                <h3 class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 leading-none">
                    {{ $stats['completed_modules'] }}
                </h3>
                <p class="mt-2 text-xs text-slate-500 truncate">
                    Modul yang telah diselesaikan 100%
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 ml-3 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">Capaian Tuntas:</span>
            <span class="text-blue-600 font-bold">100% Selesai</span>
        </div>
    </div>

</div>

{{-- ══ 3. Katalog Mata Pelajaran & Informasi Guru ══ --}}
<div class="mb-10">
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                Mata Pelajaran Kejuruan
            </span>
            <span class="text-xs font-medium text-slate-400">Kelas {{ $class->full_name ?? 'Siswa' }}</span>
        </div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
            Mata Pelajaran & Guru Pengampu
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Pilih mata pelajaran di bawah ini untuk membuka dan mempelajari e-modul yang ditugaskan oleh guru pengampu.
        </p>
    </div>

    {{-- Grid Kartu Mata Pelajaran --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($subjects as $subj)
            <div class="group relative rounded-3xl bg-white border border-slate-200/80 hover:border-emerald-300 transition-all duration-300 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md">

                {{-- Color Accent Bar --}}
                <div class="h-1.5 w-full {{ match($subj['color']) {
                    'amber', 'yellow' => 'bg-amber-500',
                    'emerald', 'green' => 'bg-emerald-500',
                    'indigo', 'purple' => 'bg-indigo-500',
                    'rose', 'red' => 'bg-rose-500',
                    'cyan', 'teal' => 'bg-teal-500',
                    default => 'bg-blue-500',
                } }}"></div>

                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    {{-- Top Badges: Code & Module Count --}}
                    <div class="flex items-center justify-between gap-2">
                        @if($subj['code'])
                            <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200 uppercase tracking-wider">
                                {{ $subj['code'] }}
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                Mapel
                            </span>
                        @endif

                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $subj['modules_count'] > 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                            {{ $subj['modules_count'] }} Modul
                        </span>
                    </div>

                    {{-- Title & Description --}}
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                            {{ $subj['name'] }}
                        </h3>
                        @if($subj['description'])
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $subj['description'] }}
                            </p>
                        @endif
                    </div>

                    {{-- Informasi Guru Pengampu --}}
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guru Pengampu</p>
                        <p class="text-xs font-bold text-slate-800 truncate" title="{{ $subj['teacher_name'] }}">
                            {{ $subj['teacher_name'] }}
                        </p>
                    </div>

                    {{-- Modul Count & Progress Stat --}}
                    @if($subj['modules_count'] > 0)
                        <div class="space-y-1.5 pt-1">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500">
                                <span>Progres Belajar:</span>
                                <span class="font-bold text-slate-800">{{ $subj['completed_count'] }}/{{ $subj['modules_count'] }} Tuntas ({{ $subj['avg_progress'] }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full bg-emerald-500 transition-all duration-300"
                                     style="width: {{ $subj['avg_progress'] }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="py-1.5 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <span class="text-[11px] text-slate-400 font-medium">Belum ada modul terbit</span>
                        </div>
                    @endif
                </div>

                {{-- Action Button Footer --}}
                <div class="p-4 pt-0">
                    <a href="{{ route('student.modules.subject', $subj['id']) }}"
                       class="w-full py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-sm
                       {{ $subj['modules_count'] > 0
                           ? 'bg-slate-900 hover:bg-emerald-600 text-white group-hover:shadow-md'
                           : 'bg-slate-100 text-slate-400 cursor-not-allowed pointer-events-none' }}">
                        <span>{{ $subj['modules_count'] > 0 ? 'Lihat Modul (' . $subj['modules_count'] . ')' : 'Belum Tersedia' }}</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-8 text-center bg-white rounded-3xl border border-slate-200">
                <p class="text-sm text-slate-500">Belum ada data mata pelajaran.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
