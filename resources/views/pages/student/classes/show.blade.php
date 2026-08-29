@extends('layouts.student.dashboardstudent')

@section('title', 'Mata Pelajaran ' . $class->full_name . ' — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Mata Pelajaran ' . $class->full_name)

@section('content')

<div class="space-y-8 pb-12">

    {{-- ══ Tombol Kembali & Breadcrumb Navigasi ══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-bold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Dashboard Siswa</span>
            </a>
            <span>/</span>
            <span class="text-slate-700 font-bold">{{ $class->full_name }}</span>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            <button type="button"
                    @click="leaveClassTarget = { id: {{ $class->id }}, name: '{{ addslashes($class->full_name) }}' }; leaveClassModalOpen = true"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-xs font-bold text-rose-700 shadow-2xs transition-all">
                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                </svg>
                <span>Keluar dari Kelas</span>
            </button>

            <a href="{{ route('student.dashboard') }}"
               class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs transition-all">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    {{-- ══ Hero Header Kelas ══ --}}
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        {{-- Decorative Blur Effects --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                {{-- Badges --}}
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
                    <span class="flex items-center gap-1.5 text-emerald-300">
                        <span>🏫</span>
                        <span>Rombel Kelas</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="font-mono text-emerald-300 font-black uppercase tracking-wider">
                        KODE: {{ $class->code }}
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="text-slate-200">{{ $class->major?->name ?? 'Kejuruan' }}</span>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    {{ $class->full_name }}
                </h1>

                {{-- Description --}}
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-2xl font-normal">
                    Daftar mata pelajaran yang diajarkan pada kelas <strong>{{ $class->full_name }}</strong>. Pilih mata pelajaran untuk mempelajari e-modul buatan guru.
                </p>
            </div>

            {{-- Ringkasan Capaian Kelas --}}
            <div class="flex items-center gap-4 bg-slate-950/50 border border-white/15 p-4 sm:p-5 rounded-2xl backdrop-blur-md shadow-sm shrink-0">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center text-2xl font-black shrink-0">
                    📚
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Mata Pelajaran</p>
                    <p class="text-lg sm:text-xl font-black text-white">
                        {{ $classStats['total_subjects'] }} Mapel Terdaftar
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-300 font-medium">{{ $classStats['completed_modules'] }}/{{ $classStats['total_modules'] }} Modul Selesai</span>
                        <span class="text-xs font-bold text-emerald-400">({{ $classStats['avg_progress'] }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Section Title ══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>📖</span>
                <span>Daftar Mata Pelajaran</span>
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pilih salah satu mata pelajaran di bawah untuk membuka seluruh e-modul pembelajaran yang telah dibuat oleh guru pengampu.
            </p>
        </div>
    </div>

    {{-- ══ Grid Card Mata Pelajaran (Hanya Menampilkan Mapel) ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subjectsWithSummary as $subj)
            <div class="group bg-white rounded-3xl border border-slate-200/90 hover:border-emerald-400 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                
                {{-- Konten Utama Card Mapel --}}
                <div class="p-6 sm:p-7 space-y-5">
                    {{-- Top Header: Icon, Code Pill, and Total Modul Badge --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-black text-2xl group-hover:scale-105 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-2xs">
                            {{ $subj['icon'] ?? '📚' }}
                        </div>
                        <div class="flex items-center gap-2">
                            @if($subj['code'])
                                <span class="font-mono text-xs font-black bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                    {{ $subj['code'] }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $subj['modules_count'] }} Modul
                            </span>
                        </div>
                    </div>

                    {{-- Judul Mata Pelajaran --}}
                    <div class="space-y-1">
                        <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors tracking-tight">
                            {{ $subj['name'] }}
                        </h3>
                        @if($subj['description'])
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $subj['description'] }}
                            </p>
                        @endif
                    </div>

                    {{-- Guru Pengampu Mapel --}}
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guru Pengampu</p>
                        <p class="text-xs font-bold text-slate-800 truncate" title="{{ $subj['teacher_display'] }}">
                            👨‍🏫 {{ $subj['teacher_display'] }}
                        </p>
                    </div>

                    {{-- Progres & Ringkasan Modul Belajar Mapel --}}
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-500">Progres Belajar:</span>
                            <span class="text-slate-900">{{ $subj['completed_count'] }}/{{ $subj['modules_count'] }} Modul ({{ $subj['avg_progress'] }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $subj['avg_progress'] }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5 font-medium">
                            <span>Sedang Berjalan: <strong class="text-amber-600">{{ $subj['in_progress_count'] }}</strong></span>
                            <span>Tuntas: <strong class="text-emerald-600">{{ $subj['completed_count'] }}</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Action Button Footer Menuju Halaman Modul Mapel --}}
                <div class="p-6 pt-0">
                    <a href="{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subj['id']]) }}"
                       class="w-full py-3 px-4 rounded-2xl bg-slate-900 group-hover:bg-emerald-600 text-white font-extrabold text-xs transition-all shadow-md group-hover:shadow-lg group-hover:shadow-emerald-600/20 flex items-center justify-center gap-2">
                        <span>Buka Mata Pelajaran & Modul</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200 space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-3xl font-bold">
                    📭
                </div>
                <div class="max-w-md mx-auto">
                    <h3 class="text-base font-bold text-slate-800">Belum Ada Mata Pelajaran</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Mata pelajaran untuk rombel kelas <strong>{{ $class->full_name }}</strong> belum tersedia.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

</div>

@endsection
