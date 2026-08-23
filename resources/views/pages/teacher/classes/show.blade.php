@extends('layouts.teacher.dashboardteacher')
@section('title', $class->full_name . ' — Detail Kelas & Siswa')
@section('page-title', 'Detail Kelas Binaan')

@section('content')
<div x-data="classDetailPage()" class="space-y-8 pb-12">

    {{-- ══ 1. HEADER & BREADCRUMB ══ --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 p-6 sm:p-8 text-white shadow-xl">
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-4">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-300">
                <a href="{{ route('teacher.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('teacher.classes.index') }}" class="hover:text-white transition-colors">Kelas Binaan</a>
                <span class="text-slate-500">/</span>
                <span class="text-blue-300 font-bold">{{ $class->full_name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-blue-500/20 text-blue-300 border border-blue-400/30">
                            Kelas {{ $class->grade }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                            Jurusan {{ $class->major_name }}
                        </span>
                        <span class="text-xs text-slate-300">
                            • SMK Negeri 3 Yogyakarta
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                        {{ $class->full_name }}
                    </h1>
                    <p class="text-slate-300 text-sm max-w-2xl">
                        Direktori lengkap data siswa, portofolio modul pembelajaran, serta catatan performa nilai siswa untuk kelas ini.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a href="{{ route('teacher.classes.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/15 backdrop-blur-sm transition-all">
                        ← Kembali ke Daftar Kelas
                    </a>
                    <a href="{{ route('teacher.modules.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-lg shadow-blue-900/40 border border-blue-400/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Buat Modul untuk Kelas Ini</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ 2. RINGKASAN METRIK KELAS KHUSUS GURU ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Siswa Terdaftar --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Terdaftar</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['total_students'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Siswa</span>
                </div>
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Dalam rombel kelas</p>
            </div>
        </div>

        {{-- Modul Guru Terbit --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Guru</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['published_modules'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $classStats['total_modules'] }} Modul</span>
                </div>
                <p class="text-[11px] text-blue-600 font-medium mt-0.5">Diberikan ke kelas ini</p>
            </div>
        </div>

        {{-- Total Pengumpulan Tugas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengumpulan Tugas</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['total_submissions'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Tugas</span>
                </div>
                <p class="text-[11px] text-emerald-600 font-medium mt-0.5">{{ $classStats['graded_count'] }} sudah dinilai</p>
            </div>
        </div>

        {{-- Rata-rata Nilai Kelas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.003 0H9.497m5.003 0a3.375 3.375 0 00-6.003 0" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai Kelas</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black {{ $classStats['avg_score'] >= 75 ? 'text-emerald-600' : ($classStats['avg_score'] > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                        {{ $classStats['avg_score'] > 0 ? $classStats['avg_score'] : '-' }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500">Poin</span>
                </div>
                <p class="text-[11px] text-amber-600 font-medium mt-0.5">Standar ketuntasan ≥ 75</p>
            </div>
        </div>
    </div>

    {{-- ══ 3. TAB NAVIGASI: DIREKTORI SISWA VS PORTOFOLIO MODUL ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        {{-- Tab Switcher --}}
        <div class="flex items-center justify-between border-b border-slate-200/80 px-6 pt-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <button type="button" @click="activeTab = 'students'"
                        class="pb-3 text-sm font-bold border-b-2 transition-all flex items-center gap-2"
                        :class="activeTab === 'students' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span>Direktori Siswa ({{ $students->count() }})</span>
                </button>

                <button type="button" @click="activeTab = 'modules'"
                        class="pb-3 text-sm font-bold border-b-2 transition-all flex items-center gap-2"
                        :class="activeTab === 'modules' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span>Portofolio Modul Guru ({{ $teacherModules->count() }})</span>
                </button>
            </div>

            {{-- Quick Action Links --}}
            <div class="hidden sm:flex items-center gap-2 pb-2">
                <a href="{{ route('teacher.grading.index', ['class_id' => $class->id]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition-colors border border-blue-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    <span>Matriks Penilaian</span>
                </a>
                <a href="{{ route('teacher.reports.index', ['class_id' => $class->id]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-xs font-bold transition-colors border border-emerald-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5" />
                    </svg>
                    <span>Laporan Excel</span>
                </a>
            </div>
        </div>

        {{-- ── TAB CONTENT 1: DIREKTORI SISWA ─────────────────────────────── --}}
        <div x-show="activeTab === 'students'" class="p-6 space-y-6">
            {{-- Search Bar Siswa --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form action="{{ route('teacher.classes.show', $class) }}" method="GET" class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="search_student" value="{{ request('search_student') }}"
                           placeholder="Cari nama atau NISN siswa..."
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400">
                </form>

                <p class="text-xs text-slate-400">
                    Menampilkan <strong class="text-slate-700">{{ $students->count() }}</strong> Siswa
                </p>
            </div>

            {{-- Tabel Siswa --}}
            @if($students->isEmpty())
                <div class="rounded-2xl bg-slate-50 p-8 text-center border border-slate-200">
                    <p class="text-sm font-bold text-slate-700">Tidak ada data siswa ditemukan.</p>
                    <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan kata kunci pencarian Anda.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-900 text-white font-bold text-xs uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-4 w-12 text-center">No</th>
                                <th class="py-3.5 px-4">Nama Siswa</th>
                                <th class="py-3.5 px-4">NISN</th>
                                <th class="py-3.5 px-4 text-center">Modul Diikuti</th>
                                <th class="py-3.5 px-4 text-center">Rata-rata Nilai</th>
                                <th class="py-3.5 px-4 text-center">Status Nilai</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($students as $index => $student)
                                @php
                                    $acad = $student->academic_summary;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors {{ $index % 2 === 1 ? 'bg-slate-50/30' : '' }}">
                                    {{-- No --}}
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                        {{ $index + 1 }}
                                    </td>

                                    {{-- Nama Siswa & Avatar --}}
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                                {{ strtoupper(substr($student->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 leading-tight">
                                                    {{ $student->name }}
                                                </p>
                                                <p class="text-[11px] text-slate-400">
                                                    {{ $class->full_name }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- NISN --}}
                                    <td class="py-3.5 px-4 font-mono font-medium text-slate-600">
                                        {{ $student->identity_number }}
                                    </td>

                                    {{-- Modul Diikuti --}}
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center gap-1 font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg text-xs">
                                            {{ $acad['submitted_count'] }} <span class="font-normal text-slate-400">/ {{ $acad['total_modules'] }} Modul</span>
                                        </span>
                                    </td>

                                    {{-- Rata-rata Nilai --}}
                                    <td class="py-3.5 px-4 text-center font-black">
                                        @if($acad['avg_score'] !== null)
                                            <span class="text-sm {{ $acad['avg_score'] >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                                {{ $acad['avg_score'] }} Poin
                                            </span>
                                        @else
                                            <span class="text-slate-300 font-normal">-</span>
                                        @endif
                                    </td>

                                    {{-- Status Ketuntasan Nilai --}}
                                    <td class="py-3.5 px-4 text-center">
                                        @if($acad['kktp_status'] === 'Tuntas')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Tuntas
                                            </span>
                                        @elseif($acad['kktp_status'] === 'Belum Tuntas (Remedial)')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Remedial
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                                Belum Ada Nilai
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Tombol Aksi Detail --}}
                                    <td class="py-3.5 px-4 text-center">
                                        <button type="button"
                                                @click="fetchStudentSummary({{ $student->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-bold text-xs transition-all border border-blue-200 hover:border-transparent">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Rincian Nilai</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── TAB CONTENT 2: PORTOFOLIO MODUL GURU ──────────────────────── --}}
        <div x-show="activeTab === 'modules'" class="p-6 space-y-6">
            @if($teacherModules->isEmpty())
                <div class="rounded-2xl bg-slate-50 p-12 text-center border border-slate-200">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Modul untuk Kelas Ini</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                        Anda belum membuat atau mendistribusikan modul pembelajaran untuk {{ $class->full_name }}.
                    </p>
                    <a href="{{ route('teacher.modules.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Buat Modul Baru</span>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($teacherModules as $mod)
                        @php
                            $activeComps = $mod->activeGradedComponents();
                            $stats = $mod->gradingStats();
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
                            <div class="space-y-3">
                                {{-- Status Badges --}}
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $mod->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($mod->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700') }}">
                                        {{ ucfirst($mod->status) }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        Batas Nilai: <strong class="text-slate-600">{{ $mod->postTestKktp() }} Poin</strong>
                                    </span>
                                </div>

                                <h4 class="text-base font-bold text-slate-900 leading-snug">
                                    {{ $mod->title }}
                                </h4>

                                {{-- Active Components Pills --}}
                                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                    @forelse($activeComps as $k => $cfg)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $cfg['name'] }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 italic">Hanya Materi Teks</span>
                                    @endforelse
                                </div>

                                {{-- Progress Pengumpulan --}}
                                <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-500">Partisipasi Siswa:</span>
                                        <span class="font-bold text-slate-800">{{ $stats['submitted_count'] }} / {{ $stats['total_students'] }} Siswa</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-blue-600 h-full rounded-full transition-all" style="width: {{ $stats['progress_pct'] }}%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 pt-4 mt-4 border-t border-slate-100">
                                <a href="{{ route('teacher.modules.show', $mod) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors">
                                    <span>Buka Builder</span>
                                </a>
                                <a href="{{ route('teacher.grading.show', $mod) }}"
                                   class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold text-xs transition-colors border border-blue-200">
                                    <span>Penilaian</span>
                                </a>
                                <a href="{{ route('teacher.reports.export.module', $mod) }}"
                                   class="inline-flex items-center justify-center p-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs transition-colors border border-emerald-200"
                                   title="Unduh Rekap Excel (.xlsx)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ══ 4. MODAL POP-UP: RINCIAN NILAI AKADEMIK SISWA ══ --}}
    <div x-cloak x-show="modalOpen"
         @keydown.escape.window="modalOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="student-modal-title" role="dialog" aria-modal="true"
         style="display: none;">
        
        {{-- Backdrop Blur (Strictly behind the card) --}}
        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="modalOpen = false" aria-hidden="true"></div>

        <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 text-center">
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 bg-white rounded-3xl max-w-2xl w-full mx-auto shadow-2xl overflow-hidden border border-slate-200 flex flex-col max-h-[90vh] text-left my-8">
            
            {{-- Modal Header --}}
            <div class="p-6 bg-slate-900 text-white flex items-center justify-between shrink-0">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500/30 text-blue-300">
                            Rapor Performa Siswa
                        </span>
                        <span class="text-xs text-slate-300" x-text="selectedStudent.class_name"></span>
                    </div>
                    <h3 class="text-xl font-extrabold text-white" x-text="selectedStudent.name"></h3>
                    <p class="text-xs text-slate-400 font-mono" x-text="'NISN: ' + selectedStudent.identity_number"></p>
                </div>

                <button @click="modalOpen = false"
                        class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto space-y-6 flex-1">
                {{-- Overall Score Card --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Rata-rata Nilai</p>
                        <p class="text-2xl font-black text-blue-900 mt-1" x-text="overallAvg !== null ? overallAvg + ' Poin' : '-'"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ketuntasan Nilai</p>
                        <p class="text-base font-black mt-1.5"
                           :class="kktpStatus === 'Tuntas' ? 'text-emerald-600' : (kktpStatus === 'Belum Tuntas (Remedial)' ? 'text-rose-600' : 'text-slate-400')"
                           x-text="kktpStatus"></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 col-span-2 sm:col-span-1">
                        <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Modul Terdaftar</p>
                        <p class="text-2xl font-black text-indigo-900 mt-1" x-text="modulesList.length + ' Modul'"></p>
                    </div>
                </div>

                {{-- Table Breakdown Modul --}}
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rincian Per Modul Guru</h4>
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-3">Judul Modul</th>
                                    <th class="py-3 px-2 text-center">Pre-Test</th>
                                    <th class="py-3 px-2 text-center">Video</th>
                                    <th class="py-3 px-2 text-center">Embed</th>
                                    <th class="py-3 px-2 text-center">Job Sheet</th>
                                    <th class="py-3 px-2 text-center">LKPD</th>
                                    <th class="py-3 px-2 text-center">Post-Test</th>
                                    <th class="py-3 px-3 text-center bg-slate-200/70 font-black">Nilai Akhir</th>
                                    <th class="py-3 px-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <template x-for="mod in modulesList" :key="mod.module_id">
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3 px-3 font-bold text-slate-900 max-w-[180px] truncate" x-text="mod.module_title"></td>
                                        <td class="py-3 px-2 text-center" x-text="mod.pre_test_score !== null ? mod.pre_test_score : '-'"></td>
                                        <td class="py-3 px-2 text-center" x-text="mod.video_score !== null ? mod.video_score : '-'"></td>
                                        <td class="py-3 px-2 text-center" x-text="mod.embed_score !== null ? mod.embed_score : '-'"></td>
                                        <td class="py-3 px-2 text-center" x-text="mod.job_sheet_score !== null ? mod.job_sheet_score : '-'"></td>
                                        <td class="py-3 px-2 text-center" x-text="mod.lkpd_score !== null ? mod.lkpd_score : '-'"></td>
                                        <td class="py-3 px-2 text-center" x-text="mod.post_test_score !== null ? mod.post_test_score : '-'"></td>
                                        <td class="py-3 px-3 text-center font-black bg-blue-50/40 text-blue-900" x-text="mod.summative_score !== null ? mod.summative_score : '-'"></td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                                                  :class="mod.grading_status === 'graded' ? 'bg-emerald-50 text-emerald-700' : (mod.grading_status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-400')"
                                                  x-text="mod.grading_status === 'graded' ? 'Selesai' : (mod.grading_status === 'pending' ? 'Menunggu' : 'Belum Submit')">
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end shrink-0">
                <button type="button" @click="modalOpen = false"
                        class="px-5 py-2 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function classDetailPage() {
        return {
            activeTab: 'students',
            modalOpen: false,
            loading: false,
            selectedStudent: {},
            modulesList: [],
            overallAvg: null,
            kktpStatus: '',

            fetchStudentSummary(studentId) {
                this.loading = true;
                const url = `{{ url('/teacher/classes/' . $class->id . '/students') }}/${studentId}/summary`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.selectedStudent = data.student;
                            this.modulesList = data.modules_summary;
                            this.overallAvg = data.overall_avg;
                            this.kktpStatus = data.kktp_status;
                            this.modalOpen = true;
                        } else {
                            alert(data.message || 'Gagal memuat ringkasan performa siswa.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan saat memuat data performa siswa.');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>
@endsection
