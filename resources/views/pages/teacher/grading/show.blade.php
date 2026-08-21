@extends('layouts.teacher.dashboardteacher')

@section('title', 'Matriks Penilaian — ' . $module->title)
@section('page-title', 'Matriks Penilaian Modul')

@section('content')

{{-- ══ Header Workspace & Actions ══ --}}
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">
            <a href="{{ route('teacher.dashboard') }}" class="hover:text-blue-600 transition-colors">Workspace</a>
            <span>/</span>
            <a href="{{ route('teacher.grading.index') }}" class="hover:text-blue-600 transition-colors">Grading Center</a>
            <span>/</span>
            <span class="text-blue-600">Matriks Penilaian</span>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ $module->title }}
            </h1>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                {{ $module->schoolClass ? $module->schoolClass->full_name : 'Kelas' }}
            </span>
            @php $status = $module->statusLabel(); @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase border {{ $status['color'] }}">
                {{ $status['label'] }}
            </span>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('teacher.grading.index') }}"
           class="px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200/90 rounded-xl hover:bg-slate-50 transition-all inline-flex items-center gap-1.5 shadow-sm">
            <span>←</span> Kembali ke Antrean
        </a>
        <a href="{{ route('teacher.reports.index') }}"
           class="px-4 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all inline-flex items-center gap-2 shadow-md shadow-indigo-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Cetak Rekap Nilai PDF</span>
        </a>
    </div>
</div>

{{-- ══ Banner Ringkasan Penilaian ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Metric 1: Total Siswa --}}
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 leading-none select-none">
            👥
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Siswa Kelas</span>
            <p class="text-xl font-black text-slate-900">{{ $stats['total_students'] }} <span class="text-xs font-semibold text-slate-500">Siswa</span></p>
        </div>
    </div>

    {{-- Metric 2: Pending Penilaian --}}
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 leading-none select-none">
            ⏳
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Menunggu Dinilai</span>
            <p class="text-xl font-black text-amber-600">{{ $stats['pending_count'] }} <span class="text-xs font-semibold text-slate-500">Tugas</span></p>
        </div>
    </div>

    {{-- Metric 3: Selesai Dinilai --}}
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 leading-none select-none">
            ✅
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tuntas Dinilai</span>
            <p class="text-xl font-black text-emerald-600">{{ $stats['graded_count'] }} <span class="text-xs font-semibold text-slate-500">Siswa</span></p>
        </div>
    </div>

    {{-- Metric 4: Rata-rata Sumatif --}}
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0 leading-none select-none">
            🏆
        </div>
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Nilai Kelas</span>
            <p class="text-xl font-black text-indigo-600">{{ $stats['avg_score'] }} <span class="text-xs font-semibold text-slate-500">Poin</span></p>
        </div>
    </div>
</div>

{{-- ══ Komponen Penilaian Aktif Info Bar (Praktik Interaktif Cyan-Teal-Slate Theme) ══ --}}
<div class="bg-gradient-to-r from-cyan-800 via-teal-800 to-slate-900 rounded-2xl p-4 sm:p-5 text-white shadow-xl shadow-cyan-950/20 border border-cyan-700/40 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h3 class="text-sm font-extrabold text-white flex items-center gap-2">
            <span class="text-base leading-none select-none">⚙️</span>
            <span>Komponen Penilaian Adaptif Modul:</span>
        </h3>
        <p class="text-xs text-cyan-100/90 mt-0.5 font-normal">
            Kolom matriks di bawah ini beradaptasi secara otomatis menampilkan {{ count($activeComponents) }} komponen yang aktif pada modul ini.
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        @foreach($activeComponents as $compKey => $compData)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-950/60 backdrop-blur-md text-white border border-white/20 shadow-sm">
                <span class="text-sm leading-none select-none">{{ $compData['icon'] }}</span>
                <span>{{ $compData['name'] }}</span>
                <span class="text-[10px] px-2 py-0.5 rounded-md font-extrabold uppercase tracking-wider {{ $compData['type'] === 'auto' ? 'bg-cyan-400/20 text-cyan-200 border border-cyan-400/40' : 'bg-amber-400/20 text-amber-300 border border-amber-400/40' }}">
                    {{ $compData['type'] === 'auto' ? 'Otomatis' : 'Manual' }}
                </span>
            </span>
        @endforeach
    </div>
</div>

{{-- ══ Flash Alerts ══ --}}
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 rounded-2xl text-xs font-bold text-emerald-800 flex items-center gap-3 shadow-sm">
        <span class="text-base">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ══ Form Matriks Penilaian Massal (Batch Scoring Form) ══ --}}
<form action="{{ route('teacher.grading.batch.update', $module) }}" method="POST" id="batch-grade-form">
    @csrf

    {{-- Toolbar Filter & Batch Save --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm mb-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        
        {{-- Search & Status Filter (Icon Presisi di dalam Input) --}}
        <div class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[240px] flex items-center">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       onchange="window.location.href = updateQueryString('search', this.value)"
                       placeholder="Cari nama siswa atau NISN..."
                       class="w-full pl-10 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>

            <select onchange="window.location.href = updateQueryString('status_filter', this.value)"
                    class="px-3.5 py-2 text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>Semua Status Siswa</option>
                <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Menunggu Penilaian (Pending)</option>
                <option value="graded" {{ request('status_filter') == 'graded' ? 'selected' : '' }}>Selesai Dinilai (Graded)</option>
                <option value="not_submitted" {{ request('status_filter') == 'not_submitted' ? 'selected' : '' }}>Belum Mengumpulkan</option>
            </select>
        </div>

        {{-- Batch Save Button --}}
        <div class="flex items-center gap-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>Simpan Semua Nilai Massal</span>
            </button>
        </div>
    </div>

    {{-- ══ Tabel Matriks Adaptif ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-600 uppercase tracking-wider font-extrabold text-[11px]">
                        <th class="py-4 px-4 text-center w-14">No</th>
                        <th class="py-4 px-4 min-w-[200px]">Data Siswa</th>
                        <th class="py-4 px-3 text-center min-w-[120px]">Status</th>

                        {{-- Kolom Dinamis Berdasarkan Komponen Aktif --}}
                        @if($module->has_pre_test)
                            <th class="py-4 px-3 text-center min-w-[110px] bg-blue-50/50 text-blue-900">
                                Pre-test
                            </th>
                        @endif

                        @if($module->has_video)
                            <th class="py-4 px-3 text-center min-w-[140px] bg-red-50/50 text-red-900">
                                Ringkasan Video
                            </th>
                        @endif

                        @if($module->has_embed)
                            <th class="py-4 px-3 text-center min-w-[140px] bg-violet-50/50 text-violet-900">
                                Bukti Praktik
                            </th>
                        @endif

                        @if($module->has_job_sheet)
                            <th class="py-4 px-3 text-center min-w-[140px] bg-amber-50/50 text-amber-900">
                                Job Sheet (PDF)
                            </th>
                        @endif

                        @if($module->has_lkpd)
                            <th class="py-4 px-3 text-center min-w-[140px] bg-cyan-50/50 text-cyan-900">
                                LKPD (PDF)
                            </th>
                        @endif

                        @if($module->has_post_test)
                            <th class="py-4 px-3 text-center min-w-[110px] bg-teal-50/50 text-teal-900">
                                Post-test
                            </th>
                        @endif

                        <th class="py-4 px-4 text-center min-w-[110px] bg-indigo-50/80 text-indigo-900 font-black">
                            Nilai Akhir
                        </th>
                        <th class="py-4 px-4 text-center min-w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($studentsData as $index => $row)
                        @php
                            $student = $row['student'];
                            $stStatus = $row['status'];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            {{-- Nomer Urut Siswa --}}
                            <td class="py-3.5 px-4 text-center font-bold text-slate-600 text-xs">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Data Siswa (Tanpa Bulatan Inisial) --}}
                            <td class="py-3.5 px-4">
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $student->name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">NISN: {{ $student->identity_number }}</p>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="py-3.5 px-3 text-center">
                                @if($stStatus === 'graded')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                        <span>Graded</span>
                                    </span>
                                @elseif($stStatus === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                        <span>Pending</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                        Belum Submit
                                    </span>
                                @endif
                            </td>

                            {{-- Pre-test (Auto) --}}
                            @if($module->has_pre_test)
                                <td class="py-3.5 px-3 text-center bg-blue-50/30">
                                    @if($row['pre_test_score'] !== null)
                                        <span class="font-black text-blue-700 bg-blue-100/70 px-2 py-1 rounded-md inline-block">
                                            {{ $row['pre_test_score'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            @endif

                            {{-- Video Summary (Manual) --}}
                            @if($module->has_video)
                                <td class="py-3.5 px-3 text-center bg-red-50/20">
                                    @if($row['video_summary'])
                                        <div class="flex items-center justify-center gap-1.5">
                                            <input type="number" min="0" max="100"
                                                   name="grades[{{ $student->id }}][video_score]"
                                                   value="{{ $row['video_score'] ?? '' }}"
                                                   placeholder="0-100"
                                                   class="w-14 text-center py-1 px-1 text-xs font-bold border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                                            <button type="button" onclick="openStudentModal({{ $student->id }})"
                                                    title="Baca Ringkasan Siswa"
                                                    class="p-1.5 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors inline-flex items-center justify-center text-sm leading-none">
                                                👁️
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-[11px]">Belum submit</span>
                                    @endif
                                </td>
                            @endif

                            {{-- Embed Practice (Manual) --}}
                            @if($module->has_embed)
                                <td class="py-3.5 px-3 text-center bg-violet-50/20">
                                    @if($row['embed_submission'])
                                        <div class="flex items-center justify-center gap-1.5">
                                            <input type="number" min="0" max="100"
                                                   name="grades[{{ $student->id }}][embed_score]"
                                                   value="{{ $row['embed_score'] ?? '' }}"
                                                   placeholder="0-100"
                                                   class="w-14 text-center py-1 px-1 text-xs font-bold border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500">
                                            <button type="button" onclick="openStudentModal({{ $student->id }})"
                                                    title="Lihat Screenshot Praktik"
                                                    class="p-1.5 rounded-lg text-slate-500 hover:text-violet-600 hover:bg-violet-50 transition-colors inline-flex items-center justify-center text-sm leading-none">
                                                🖼️
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-[11px]">Belum submit</span>
                                    @endif
                                </td>
                            @endif

                            {{-- Job Sheet PDF (Manual) --}}
                            @if($module->has_job_sheet)
                                <td class="py-3.5 px-3 text-center bg-amber-50/20">
                                    @if($row['job_sheet_submission'])
                                        <div class="flex items-center justify-center gap-1.5">
                                            <input type="number" min="0" max="100"
                                                   name="grades[{{ $student->id }}][job_sheet_score]"
                                                   value="{{ $row['job_sheet_score'] ?? '' }}"
                                                   placeholder="0-100"
                                                   class="w-14 text-center py-1 px-1 text-xs font-bold border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                                            <button type="button" onclick="openStudentModal({{ $student->id }})"
                                                    title="Lihat / Unduh Tugas Job Sheet"
                                                    class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors inline-flex items-center justify-center text-sm leading-none">
                                                📥
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-[11px]">Belum submit</span>
                                    @endif
                                </td>
                            @endif

                            {{-- LKPD PDF (Manual) --}}
                            @if($module->has_lkpd)
                                <td class="py-3.5 px-3 text-center bg-cyan-50/20">
                                    @if($row['lkpd_submission'])
                                        <div class="flex items-center justify-center gap-1.5">
                                            <input type="number" min="0" max="100"
                                                   name="grades[{{ $student->id }}][lkpd_score]"
                                                   value="{{ $row['lkpd_score'] ?? '' }}"
                                                   placeholder="0-100"
                                                   class="w-14 text-center py-1 px-1 text-xs font-bold border border-slate-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                                            <button type="button" onclick="openStudentModal({{ $student->id }})"
                                                    title="Lihat / Unduh Tugas LKPD"
                                                    class="p-1.5 rounded-lg text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 transition-colors inline-flex items-center justify-center text-sm leading-none">
                                                📑
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-[11px]">Belum submit</span>
                                    @endif
                                </td>
                            @endif

                            {{-- Post-test (Auto) --}}
                            @if($module->has_post_test)
                                <td class="py-3.5 px-3 text-center bg-teal-50/30">
                                    @if($row['post_test_score'] !== null)
                                        <span class="font-black text-teal-700 bg-teal-100/70 px-2 py-1 rounded-md inline-block">
                                            {{ $row['post_test_score'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            @endif

                            {{-- Nilai Akhir Sumatif --}}
                            <td class="py-3.5 px-4 text-center bg-indigo-50/50">
                                <span class="text-sm font-black text-indigo-700">
                                    {{ $row['summative_score'] }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" onclick="openStudentModal({{ $student->id }})"
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors inline-flex items-center gap-1">
                                    <span>Detail / Nilai</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 5 + count($activeComponents) }}" class="py-12 text-center text-slate-400">
                                <div class="text-3xl mb-2">🔍</div>
                                <p class="text-base font-bold text-slate-600 mb-1">Tidak ada data siswa ditemukan</p>
                                <p class="text-xs text-slate-400">Coba ubah kata kunci pencarian atau filter status siswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

{{-- ══ MODAL DETAIL & PENILAIAN SISWA (Widescreen Horizontal Layout) ══ --}}
<div id="studentModal" class="fixed inset-0 z-50 hidden bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5 md:p-6 overflow-hidden">
    <div class="bg-white rounded-3xl max-w-5xl xl:max-w-6xl w-full h-full max-h-[88vh] shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden animate-fadeInUp">
        
        {{-- 1. Modal Top Bar (Sticky) --}}
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <span class="text-xs font-black uppercase tracking-wider text-slate-400">Pusat Penilaian Guru</span>
                <span class="text-slate-600">/</span>
                <span class="text-xs font-bold text-cyan-300 truncate max-w-xs sm:max-w-md">Modul: {{ $module->title }}</span>
            </div>
            <button type="button" onclick="closeStudentModal()" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-sm font-bold transition-colors leading-none">
                ✕
            </button>
        </div>

        {{-- 2. Modal Body (Scrollable Inside, Never Cut Off) --}}
        <div class="p-5 sm:p-6 overflow-y-auto flex-1 min-h-0 bg-slate-50/60">
            
            {{-- Banner Data Siswa (Sangat Jelas, Terstruktur & Ergonomis) --}}
            <div class="bg-gradient-to-r from-cyan-800 via-teal-800 to-slate-900 rounded-2xl p-5 text-white shadow-md border border-cyan-700/40 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 divide-y sm:divide-y-0 sm:divide-x divide-cyan-700/40">
                    {{-- Nama Siswa --}}
                    <div class="sm:pr-4">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-cyan-200/80">Nama Lengkap Siswa</span>
                        <h2 id="modalStudentName" class="text-lg sm:text-xl font-black text-white tracking-tight mt-0.5">Nama Siswa</h2>
                    </div>

                    {{-- NISN --}}
                    <div class="pt-3 sm:pt-0 sm:px-4">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-cyan-200/80">Nomor Induk Siswa (NISN)</span>
                        <p id="modalStudentNisn" class="text-base sm:text-lg font-bold font-mono text-cyan-100 mt-0.5">-</p>
                    </div>

                    {{-- Kelas --}}
                    <div class="pt-3 sm:pt-0 sm:pl-4 flex flex-col justify-center">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-cyan-200/80 mb-1">Rombel / Kelas</span>
                        <div>
                            <span id="modalStudentClass" class="inline-flex items-center px-3.5 py-1 rounded-xl text-xs font-black bg-cyan-400/20 text-cyan-100 border border-cyan-400/40 shadow-xs">
                                Kelas
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Penilaian Siswa --}}
            <form id="individualGradeForm" onsubmit="submitIndividualGrade(event)">
                @csrf
                <input type="hidden" id="modalStudentId" name="student_id">

                {{-- Task Sections (2-Column Horizontal Grid) --}}
                <div id="modalSubmissionsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                    {{-- Diisi secara dinamis oleh JavaScript --}}
                    <div class="col-span-full text-center py-12 text-slate-400 text-xs font-medium">
                        Memuat data tugas siswa...
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. Modal Sticky Footer Action Bar (Always Visible at Bottom) --}}
        <div class="px-6 py-4 bg-white border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
            <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                <span>💡</span>
                <span>Nilai akhir sumatif otomatis dikalkulasi dari rata-rata seluruh instrumen aktif.</span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <button type="button" onclick="closeStudentModal()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors">
                    Batal / Tutup
                </button>
                <button type="submit" form="individualGradeForm" id="modalSubmitBtn" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 transition-all inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Simpan Penilaian Siswa</span>
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function updateQueryString(key, value) {
        const url = new URL(window.location.href);
        if (value && value !== 'all') {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        return url.toString();
    }

    const moduleId = {{ $module->id }};

    function openStudentModal(studentId) {
        const modal = document.getElementById('studentModal');
        modal.classList.remove('hidden');
        document.getElementById('modalStudentId').value = studentId;

        const container = document.getElementById('modalSubmissionsContainer');
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="inline-block animate-spin w-9 h-9 border-4 border-blue-600 border-t-transparent rounded-full mb-2.5"></div>
                <p class="text-xs text-slate-500 font-bold">Memuat berkas pengerjaan siswa...</p>
            </div>
        `;

        fetch(`/teacher/grading/modules/${moduleId}/students/${studentId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalStudentName').innerText = data.student.name;
                document.getElementById('modalStudentNisn').innerText = data.student.identity_number || '-';
                document.getElementById('modalStudentClass').innerText = data.student.class || '-';

                let html = '';
                let stepNum = 1;

                // 1. Pre-test
                if (data.active_components.pre_test) {
                    html += `
                        <div class="p-4 rounded-2xl bg-white border border-blue-200 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-blue-600 text-white font-bold text-xs inline-flex items-center justify-center shrink-0 leading-none">
                                            ${stepNum++}
                                        </span>
                                        <span class="font-extrabold text-slate-900 text-xs">
                                            Pre-test (Diagnostik Awal)
                                        </span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        Otomatis
                                    </span>
                                </div>
                                <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-blue-950">Skor Hasil Ujian:</span>
                                    <span class="text-sm font-black text-blue-700">
                                        ${data.result && data.result.pre_test_score !== null ? data.result.pre_test_score + ' / 100 Poin' : '<span class="text-xs text-slate-400 font-normal italic">Belum Dikerjakan</span>'}
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" name="pre_test_score" value="${data.result && data.result.pre_test_score !== null ? data.result.pre_test_score : ''}">
                        </div>
                    `;
                }

                // 2. Video Summary
                if (data.active_components.video) {
                    const videoSub = data.submissions.video;
                    html += `
                        <div class="p-4 rounded-2xl bg-white border border-red-200 shadow-xs flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-red-600 text-white font-bold text-xs inline-flex items-center justify-center shrink-0 leading-none">
                                            ${stepNum++}
                                        </span>
                                        <span class="font-extrabold text-slate-900 text-xs">
                                            Ringkasan Video Pembelajaran
                                        </span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-red-100 text-red-800 border border-red-200">
                                        Manual
                                    </span>
                                </div>
                                ${videoSub ? `
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-xs text-slate-800 leading-relaxed max-h-32 overflow-y-auto">
                                        <p class="font-bold text-red-900 mb-1 text-[11px] flex items-center justify-between">
                                            <span>Teks Ringkasan Siswa:</span>
                                            <span class="text-[10px] font-normal text-slate-400">${videoSub.created_at}</span>
                                        </p>
                                        <p class="whitespace-pre-wrap">${videoSub.summary_text}</p>
                                    </div>
                                ` : '<div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-rose-600 font-medium italic text-center">Siswa belum mengetik ringkasan video.</div>'}
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700">Beri Nilai (0-100):</label>
                                <input type="number" name="video_score" min="0" max="100" value="${videoSub && videoSub.manual_score !== null ? videoSub.manual_score : ''}"
                                       placeholder="Skor 0-100" ${videoSub ? 'required' : ''}
                                       class="w-24 px-3 py-1.5 text-xs font-bold text-center border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500">
                            </div>
                        </div>
                    `;
                }

                // 3. Embed Practice Screenshot
                if (data.active_components.embed) {
                    const embedSub = data.submissions.embed;
                    html += `
                        <div class="p-4 rounded-2xl bg-white border border-teal-200 shadow-xs flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-teal-600 text-white font-bold text-xs inline-flex items-center justify-center shrink-0 leading-none">
                                            ${stepNum++}
                                        </span>
                                        <span class="font-extrabold text-slate-900 text-xs">
                                            Bukti Screenshot Praktik Embed
                                        </span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                        Manual
                                    </span>
                                </div>
                                ${embedSub ? `
                                    <div class="p-2 bg-slate-50 rounded-xl border border-slate-200/80 text-center">
                                        <a href="${embedSub.screenshot_path}" target="_blank" class="block group relative overflow-hidden rounded-lg">
                                            <img src="${embedSub.screenshot_path}" alt="Bukti Praktik" class="max-h-36 mx-auto rounded-lg object-contain">
                                            <span class="absolute inset-0 bg-black/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-xs font-bold">
                                                🔍 Klik untuk perbesar gambar
                                            </span>
                                        </a>
                                    </div>
                                ` : '<div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-rose-600 font-medium italic text-center">Siswa belum mengunggah tangkapan layar simulasi.</div>'}
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700">Beri Nilai (0-100):</label>
                                <input type="number" name="embed_score" min="0" max="100" value="${embedSub && embedSub.manual_score !== null ? embedSub.manual_score : ''}"
                                       placeholder="Skor 0-100" ${embedSub ? 'required' : ''}
                                       class="w-24 px-3 py-1.5 text-xs font-bold text-center border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                            </div>
                        </div>
                    `;
                }

                // 4. Job Sheet PDF
                if (data.active_components.job_sheet) {
                    const jsSub = data.submissions.job_sheet;
                    html += `
                        <div class="p-4 rounded-2xl bg-white border border-amber-200 shadow-xs flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-amber-600 text-white font-bold text-xs inline-flex items-center justify-center shrink-0 leading-none">
                                            ${stepNum++}
                                        </span>
                                        <span class="font-extrabold text-slate-900 text-xs">
                                            Lembar Job Sheet (PDF)
                                        </span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Manual
                                    </span>
                                </div>
                                ${jsSub ? `
                                    <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-100 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="text-2xl leading-none">📄</span>
                                            <span class="text-xs font-bold text-slate-800 truncate">${jsSub.file_name}</span>
                                        </div>
                                        <a href="${jsSub.file_path}" target="_blank" download
                                           class="px-3 py-1.5 rounded-lg bg-amber-100 text-amber-900 font-bold text-xs hover:bg-amber-200 transition-colors shrink-0 inline-flex items-center gap-1">
                                            📥 Unduh Berkas
                                        </a>
                                    </div>
                                ` : '<div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-rose-600 font-medium italic text-center">Siswa belum mengunggah berkas Job Sheet.</div>'}
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700">Beri Nilai (0-100):</label>
                                <input type="number" name="job_sheet_score" min="0" max="100" value="${jsSub && jsSub.manual_score !== null ? jsSub.manual_score : ''}"
                                       placeholder="Skor 0-100" ${jsSub ? 'required' : ''}
                                       class="w-24 px-3 py-1.5 text-xs font-bold text-center border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                            </div>
                        </div>
                    `;
                }

                // 5. LKPD PDF
                if (data.active_components.lkpd) {
                    const lkpdSub = data.submissions.lkpd;
                    html += `
                        <div class="p-4 rounded-2xl bg-white border border-cyan-200 shadow-xs flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-cyan-600 text-white font-bold text-xs inline-flex items-center justify-center shrink-0 leading-none">
                                            ${stepNum++}
                                        </span>
                                        <span class="font-extrabold text-slate-900 text-xs">
                                            Lembar LKPD (PDF)
                                        </span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                        Manual
                                    </span>
                                </div>
                                ${lkpdSub ? `
                                    <div class="p-3 bg-cyan-50/50 rounded-xl border border-cyan-100 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="text-2xl leading-none">📑</span>
                                            <span class="text-xs font-bold text-slate-800 truncate">${lkpdSub.file_name}</span>
                                        </div>
                                        <a href="${lkpdSub.file_path}" target="_blank" download
                                           class="px-3 py-1.5 rounded-lg bg-cyan-100 text-cyan-900 font-bold text-xs hover:bg-cyan-200 transition-colors shrink-0 inline-flex items-center gap-1">
                                            📥 Unduh Berkas
                                        </a>
                                    </div>
                                ` : '<div class="p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-rose-600 font-medium italic text-center">Siswa belum mengunggah salinan file LKPD.</div>'}
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-700">Beri Nilai (0-100):</label>
                                <input type="number" name="lkpd_score" min="0" max="100" value="${lkpdSub && lkpdSub.manual_score !== null ? lkpdSub.manual_score : ''}"
                                       placeholder="Skor 0-100" ${lkpdSub ? 'required' : ''}
                                       class="w-24 px-3 py-1.5 text-xs font-bold text-center border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                            </div>
                        </div>
                    `;
                }

                // 6. Post-test
                if (data.active_components.post_test) {
                    html += `
                        <div class="p-4 rounded-2xl bg-white border border-teal-200 shadow-xs flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-teal-600 text-white font-bold text-xs inline-flex items-center justify-center shrink-0 leading-none">
                                            ${stepNum++}
                                        </span>
                                        <span class="font-extrabold text-slate-900 text-xs">
                                            Post-test (Evaluasi Akhir)
                                        </span>
                                    </div>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-teal-100 text-teal-800 border border-teal-200">
                                        Otomatis
                                    </span>
                                </div>
                                <div class="p-3 bg-teal-50/60 rounded-xl border border-teal-100 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-teal-950">Skor Hasil Evaluasi:</span>
                                    <span class="text-sm font-black text-teal-700">
                                        ${data.result && data.result.post_test_score !== null ? data.result.post_test_score + ' / 100 Poin' : '<span class="text-xs text-slate-400 font-normal italic">Belum Dikerjakan</span>'}
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" name="post_test_score" value="${data.result && data.result.post_test_score !== null ? data.result.post_test_score : ''}">
                        </div>
                    `;
                }

                container.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `
                    <div class="col-span-full p-4 bg-rose-50 text-rose-700 rounded-2xl text-xs font-bold text-center">
                        Gagal memuat data pengerjaan siswa. Silakan coba lagi.
                    </div>
                `;
            });
    }

    function closeStudentModal() {
        document.getElementById('studentModal').classList.add('hidden');
    }

    function submitIndividualGrade(e) {
        e.preventDefault();
        const studentId = document.getElementById('modalStudentId').value;
        const form = document.getElementById('individualGradeForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('modalSubmitBtn');

        submitBtn.disabled = true;
        submitBtn.innerText = 'Menyimpan...';

        fetch(`/teacher/grading/modules/${moduleId}/students/${studentId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeStudentModal();
                window.location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan saat menyimpan nilai.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menyimpan nilai.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Simpan Penilaian Siswa';
        });
    }
</script>
@endpush

@endsection

