@extends('layouts.teacher.dashboardteacher')

@section('title', 'Rekap Nilai ' . $module->title . ' — Laporan Excel')
@section('page-title', 'Rekapitulasi Nilai Modul')

@section('content')

@php
    $class = $module->schoolClass;
    $subject = $module->subject;
@endphp

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
            <a href="{{ route('teacher.reports.class.subject', [$class->id, $subject->id]) }}" class="hover:text-blue-600 transition-colors">{{ $subject->name }}</a>
            <span>/</span>
            <span class="text-emerald-600 truncate max-w-xs">{{ $module->title }}</span>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('teacher.reports.class.subject', [$class->id, $subject->id]) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Daftar Modul</span>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <span>{{ $module->title }}</span>
            </h1>
        </div>
        <p class="mt-1.5 text-sm text-slate-500 max-w-3xl leading-relaxed">
            Mata Pelajaran: <strong class="text-slate-700">{{ $subject->name }}</strong> • Rombel: <strong class="text-slate-700">{{ $class->full_name }}</strong>. Rekapitulasi perolehan nilai siswa siap diekspor ke format spreadsheet Microsoft Excel.
        </p>
    </div>

    {{-- Export Button Header --}}
    <div class="flex items-center gap-3 shrink-0">
        <a href="{{ route('teacher.reports.export.module', $module->id) }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-lg shadow-emerald-600/30 hover:-translate-y-0.5 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Unduh Spreadsheet Excel (.xlsx)</span>
        </a>
    </div>
</div>

{{-- ══ Stat Cards Modul (4 Grid) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Siswa Kelas</span>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-black text-slate-900">{{ $reportStats['total_students'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Siswa Terdaftar</span>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Tuntas Dinilai</span>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-black text-emerald-600">{{ $reportStats['graded'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Siswa Selesai</span>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Menunggu Penilaian</span>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-black text-amber-600">{{ $reportStats['pending'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Perlu Diperiksa</span>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rata-Rata Nilai Akhir</span>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-black text-blue-600">{{ $reportStats['avg_score'] }}</span>
            <span class="text-xs font-semibold text-slate-500">/ 100 Skala</span>
        </div>
    </div>
</div>

{{-- ══ Filter & Search Bar ══ --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-8">
    <form method="GET" action="{{ route('teacher.reports.module', $module->id) }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        {{-- Search Bar --}}
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </div>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari nama siswa atau NISN..."
                   class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
        </div>

        {{-- Filter Status Penilaian --}}
        <div class="flex items-center gap-3 flex-wrap">
            <select name="status"
                    onchange="this.form.submit()"
                    class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-700 focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                <option value="">-- Semua Status Penilaian --</option>
                <option value="graded" {{ $status === 'graded' ? 'selected' : '' }}>Tuntas Dinilai</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Penilaian</option>
                <option value="not_submitted" {{ $status === 'not_submitted' ? 'selected' : '' }}>Belum Mengumpulkan</option>
            </select>

            <button type="submit"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                Filter
            </button>

            @if($search || $status)
                <a href="{{ route('teacher.reports.module', $module->id) }}"
                   class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- ══ Tabel Rekapitulasi Nilai Siswa (Tahap 4) ══ --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">Identitas Siswa</th>
                    @if($module->has_pre_test)
                        <th class="py-4 px-3 text-center">Pre-Test</th>
                    @endif
                    @if($module->has_video)
                        <th class="py-4 px-3 text-center">Video Resume</th>
                    @endif
                    @if($module->has_embed)
                        <th class="py-4 px-3 text-center">Praktik Embed</th>
                    @endif
                    @if($module->has_job_sheet)
                        <th class="py-4 px-3 text-center">Job Sheet</th>
                    @endif
                    @if($module->has_lkpd)
                        <th class="py-4 px-3 text-center">Tugas LKPD</th>
                    @endif
                    @if($module->has_post_test)
                        <th class="py-4 px-3 text-center">Post-Test</th>
                    @endif
                    <th class="py-4 px-4 text-center font-black text-slate-900 bg-slate-100/70">Nilai Akhir</th>
                    <th class="py-4 px-6 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($processedStudents as $st)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        {{-- Identitas Siswa --}}
                        <td class="py-4 px-6">
                            <div>
                                <p class="font-bold text-slate-900">{{ $st->name }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">NISN: {{ $st->identity_number }}</p>
                            </div>
                        </td>

                        {{-- Pre-Test --}}
                        @if($module->has_pre_test)
                            <td class="py-4 px-3 text-center font-mono">
                                @if($st->pre_test_score !== null)
                                    <span class="font-bold text-slate-800">{{ $st->pre_test_score }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endif

                        {{-- Video --}}
                        @if($module->has_video)
                            <td class="py-4 px-3 text-center font-mono">
                                @if($st->video_score !== null)
                                    <span class="font-bold text-slate-800">{{ $st->video_score }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endif

                        {{-- Embed --}}
                        @if($module->has_embed)
                            <td class="py-4 px-3 text-center font-mono">
                                @if($st->embed_score !== null)
                                    <span class="font-bold text-slate-800">{{ $st->embed_score }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endif

                        {{-- Job Sheet --}}
                        @if($module->has_job_sheet)
                            <td class="py-4 px-3 text-center font-mono">
                                @if($st->job_sheet_score !== null)
                                    <span class="font-bold text-slate-800">{{ $st->job_sheet_score }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endif

                        {{-- LKPD --}}
                        @if($module->has_lkpd)
                            <td class="py-4 px-3 text-center font-mono">
                                @if($st->lkpd_score !== null)
                                    <span class="font-bold text-slate-800">{{ $st->lkpd_score }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endif

                        {{-- Post-Test --}}
                        @if($module->has_post_test)
                            <td class="py-4 px-3 text-center font-mono">
                                @if($st->post_test_score !== null)
                                    <span class="font-bold text-slate-800">{{ $st->post_test_score }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        @endif

                        {{-- Nilai Akhir --}}
                        <td class="py-4 px-4 text-center font-mono font-black bg-slate-50/50">
                            @if($st->summative_score !== null)
                                <span class="text-base {{ $st->summative_score >= 75 ? 'text-emerald-600' : ($st->summative_score >= 60 ? 'text-blue-600' : 'text-red-500') }}">
                                    {{ $st->summative_score }}
                                </span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>

                        {{-- Status Penilaian --}}
                        <td class="py-4 px-6 text-center">
                            @if($st->grading_status === 'graded')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Tuntas Dinilai
                                </span>
                            @elseif($st->grading_status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Menunggu Nilai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                    Belum Kumpul
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-slate-400 text-xs">
                            Tidak ada data siswa yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
