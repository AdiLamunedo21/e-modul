@extends('layouts.teacher.dashboardteacher')

@section('title', 'Modul ' . $subject->name . ' (' . $class->full_name . ') — Laporan Nilai Excel')
@section('page-title', 'Pilih Modul Pembelajaran')

@section('content')

{{-- ══ Header & Breadcrumb ══ --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">
            <a href="{{ route('teacher.dashboard') }}" class="hover:text-blue-600 transition-colors">Workspace</a>
            <span>/</span>
            <a href="{{ route('teacher.reports.index') }}" class="hover:text-blue-600 transition-colors">Laporan Excel</a>
            <span>/</span>
            <a href="{{ route('teacher.reports.class', $class->id) }}" class="hover:text-blue-600 transition-colors">{{ $class->full_name }}</a>
            <span>/</span>
            <span class="text-emerald-600">{{ $subject->name }}</span>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('teacher.reports.class', $class->id) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Daftar Mapel</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span>Modul {{ $subject->name }}</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $subject->badgeClasses() }}">
                    {{ $class->short_name }}
                </span>
            </h1>
        </div>
        <p class="mt-1.5 text-sm text-slate-500 max-w-3xl leading-relaxed">
            Daftar modul pembelajaran yang Anda buat untuk mata pelajaran <strong>{{ $subject->name }}</strong> pada <strong>{{ $class->full_name }}</strong>. Pilih modul untuk melihat rekapitulasi nilai atau langsung unduh file spreadsheet Microsoft Excel.
        </p>
    </div>

    <div class="flex items-center gap-3 shrink-0">
        <a href="{{ route('teacher.modules.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-600/25 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span>Buat Modul Baru</span>
        </a>
    </div>
</div>

{{-- ══ Stat Cards (3 Grid) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Modul Mapel Ini</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-slate-900">{{ $subjectStats['total_modules'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Modul ({{ $subjectStats['published_modules'] }} Terbit)</span>
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengumpulan Tugas</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-slate-900">{{ $subjectStats['total_submissions'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Submisi</span>
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
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tuntas Dinilai</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-emerald-600">{{ $subjectStats['total_graded'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Siswa Dinilai</span>
            </div>
        </div>
    </div>
</div>

{{-- ══ Grid Kartu Modul Pembelajaran (Tahap 3) ══ --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($modules as $m)
        @php
            $badge = $m->statusLabel();
            $activeComps = $m->activeComponents();
        @endphp
        <div class="group relative rounded-3xl bg-white border border-slate-200/80 hover:border-blue-300 transition-all duration-300 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md">
            
            {{-- Status Accent Top Bar --}}
            <div class="h-1.5 w-full {{ $m->status === 'published' ? 'bg-emerald-500' : ($m->status === 'closed' ? 'bg-slate-400' : 'bg-amber-500') }}"></div>

            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                {{-- Status & Updated Date --}}
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide border {{ $badge['color'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $m->status === 'published' ? 'bg-emerald-500' : ($m->status === 'closed' ? 'bg-slate-400' : 'bg-amber-500') }}"></span>
                        {{ $badge['label'] }}
                    </span>

                    <span class="text-[11px] font-medium text-slate-400">
                        {{ $m->updated_at ? $m->updated_at->format('d M Y') : '-' }}
                    </span>
                </div>

                {{-- Title & Active Components --}}
                <div class="space-y-2">
                    <h3 class="text-base font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        {{ $m->title }}
                    </h3>

                    <div class="flex flex-wrap gap-1">
                        @foreach($activeComps as $comp)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $comp }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Stats Summary --}}
                <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Submisi Masuk:</span>
                        <span class="font-bold text-slate-800">
                            {{ $m->submissions_count }} / {{ $m->total_students }} Siswa
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Tuntas Dinilai:</span>
                        <span class="font-bold text-emerald-700">
                            {{ $m->graded_count }} Siswa
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Rata-Rata Nilai:</span>
                        <span class="font-black text-blue-600">
                            {{ $m->avg_score }} / 100
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons Footer --}}
            <div class="p-4 pt-0 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <a href="{{ route('teacher.reports.module', $m->id) }}"
                   class="py-2.5 px-3 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-900 text-white flex items-center justify-center gap-1.5 transition-all shadow-sm">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span>Lihat Rekap Nilai</span>
                </a>

                <a href="{{ route('teacher.reports.export.module', $m->id) }}"
                   class="py-2.5 px-3 rounded-xl text-xs font-extrabold bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center gap-1.5 transition-all shadow-sm shadow-emerald-600/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Unduh Excel</span>
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
            <p class="text-sm font-bold text-slate-700">Belum ada modul yang dibuat untuk mata pelajaran ini pada kelas {{ $class->full_name }}.</p>
            <p class="text-xs text-slate-400 mt-1">Buat modul baru sekarang untuk mulai mendistribusikan materi dan mengumpulkan nilai.</p>
        </div>
    @endforelse
</div>

@endsection
