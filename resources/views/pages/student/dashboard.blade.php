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

{{-- ══ 3. Main Content: Modul Belajar Siswa + Struktur 5 Bagian Belajar Mandiri ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mb-8">

    {{-- ── Kolom Kiri (2 Kolom): Daftar Modul Belajar Siswa ── --}}
    <div class="lg:col-span-2 space-y-6">

        <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden">

            {{-- Header & Filter Tabs --}}
            <div class="border-b border-slate-100 p-6 sm:p-7">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                            <span>E-Modul Pembelajaran Kelas</span>
                            <span class="text-xs font-bold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                3 Modul Terbaru
                            </span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Daftar modul pembelajaran terbaru yang ditugaskan untuk kelas Anda (maks. 3 modul).
                        </p>
                    </div>

                    {{-- Status Filter Tabs --}}
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-2xl text-xs font-bold overflow-x-auto self-start sm:self-auto shrink-0">
                        <a href="{{ route('student.dashboard', ['status' => 'all']) }}"
                           class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $filterStatus === 'all' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                            Semua ({{ $stats['total_modules'] }})
                        </a>
                        <a href="{{ route('student.dashboard', ['status' => 'in_progress']) }}"
                           class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $filterStatus === 'in_progress' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                            Proses ({{ $stats['in_progress'] }})
                        </a>
                        <a href="{{ route('student.dashboard', ['status' => 'completed']) }}"
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
                                    @if($item['subject'])
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $item['subject']->badgeClasses() }}">
                                            <span>{{ $item['subject']->icon }}</span>
                                            <span>{{ $item['subject']->name }}</span>
                                        </span>
                                    @endif

                                    {{-- Teacher Badge --}}
                                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                        <span>👨‍🏫</span>
                                        <span>Guru: <strong class="text-slate-800">{{ $item['teacher_name'] }}</strong></span>
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
                                    <a href="#"
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
                        <h3 class="text-base font-bold text-slate-800">Tidak Ada Modul Pada Kategori Ini</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                            Pilih tab <strong>Semua</strong> untuk melihat seluruh daftar e-modul kelas yang tersedia.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── Kolom Kanan (1 Kolom): Struktur 5 Bagian Belajar Mandiri ── --}}
    <div class="space-y-6">

        {{-- Banner Utama: Struktur 5 Bagian Belajar Mandiri --}}
        <div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 rounded-3xl p-6 sm:p-7 text-white shadow-xl shadow-emerald-950/20 relative overflow-hidden border border-emerald-700/40">
            {{-- Decorative Background Blur Effects --}}
            <div class="absolute -right-10 -bottom-10 w-56 h-56 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-44 h-44 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-4">
                {{-- Top Badge Pill --}}
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-[11px] font-bold tracking-wide text-white shadow-sm">
                    <span class="flex items-center gap-1.5 text-emerald-200">
                        <span>⚡</span>
                        <span>Standar 5 Bagian E-Modul</span>
                    </span>
                </div>

                {{-- Title --}}
                <h3 class="text-base sm:text-lg font-extrabold tracking-tight text-white drop-shadow-sm leading-snug">
                    Struktur 5 Bagian Belajar Mandiri
                </h3>

                <p class="text-xs text-slate-200 leading-relaxed font-normal">
                    Alur sistematis pembelajaran vokasi kejuruan di SMKN 3 Yogyakarta:
                </p>

                <ul class="space-y-3 text-xs text-slate-100 pt-1">
                    <li class="flex items-start gap-2.5 bg-slate-950/40 p-3 rounded-2xl border border-white/10 backdrop-blur-sm shadow-sm">
                        <span class="font-black text-emerald-300 bg-emerald-500/25 border border-emerald-400/30 rounded-xl w-6 h-6 flex items-center justify-center text-xs shrink-0">1</span>
                        <div class="min-w-0">
                            <p class="font-bold text-white leading-tight">Bagian Awal</p>
                            <p class="text-[11px] text-slate-300 mt-0.5">Cover, Kata Pengantar, Daftar Isi & Petunjuk Penggunaan.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 bg-slate-950/40 p-3 rounded-2xl border border-white/10 backdrop-blur-sm shadow-sm">
                        <span class="font-black text-emerald-300 bg-emerald-500/25 border border-emerald-400/30 rounded-xl w-6 h-6 flex items-center justify-center text-xs shrink-0">2</span>
                        <div class="min-w-0">
                            <p class="font-bold text-white leading-tight">Pendahuluan</p>
                            <p class="text-[11px] text-slate-300 mt-0.5">Capaian Pembelajaran, Peta Konsep, Glosarium & Kuis Pre-test.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 bg-slate-950/40 p-3 rounded-2xl border border-white/10 backdrop-blur-sm shadow-sm">
                        <span class="font-black text-emerald-300 bg-emerald-500/25 border border-emerald-400/30 rounded-xl w-6 h-6 flex items-center justify-center text-xs shrink-0">3</span>
                        <div class="min-w-0">
                            <p class="font-bold text-white leading-tight">Kegiatan Belajar</p>
                            <p class="text-[11px] text-slate-300 mt-0.5">Materi Teks, Slide Presentasi PPT & Video Multimedia YouTube.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 bg-slate-950/40 p-3 rounded-2xl border border-white/10 backdrop-blur-sm shadow-sm">
                        <span class="font-black text-emerald-300 bg-emerald-500/25 border border-emerald-400/30 rounded-xl w-6 h-6 flex items-center justify-center text-xs shrink-0">4</span>
                        <div class="min-w-0">
                            <p class="font-bold text-white leading-tight">Evaluasi & Praktik</p>
                            <p class="text-[11px] text-slate-300 mt-0.5">Simulator Interaktif Embed, Praktik Job Sheet & Tugas LKPD.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 bg-slate-950/40 p-3 rounded-2xl border border-white/10 backdrop-blur-sm shadow-sm">
                        <span class="font-black text-emerald-300 bg-emerald-500/25 border border-emerald-400/30 rounded-xl w-6 h-6 flex items-center justify-center text-xs shrink-0">5</span>
                        <div class="min-w-0">
                            <p class="font-bold text-white leading-tight">Bagian Akhir</p>
                            <p class="text-[11px] text-slate-300 mt-0.5">Evaluasi Post-test Akhir Modul & Daftar Pustaka Rujukan.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="mt-6 pt-5 border-t border-white/15 relative z-10">
                <a href="{{ route('student.dashboard', ['status' => 'all']) }}" class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 text-xs font-bold shadow-lg shadow-emerald-950/40 transition-all border border-emerald-400/30">
                    <span>Mulai Belajar Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Kartu Info Tips Belajar Mandiri --}}
        <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-5 sm:p-6">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    💡
                </div>
                <h4 class="text-xs font-bold text-slate-900">Tips Belajar Mandiri</h4>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">
                Pelajari materi modul secara berurutan halaman demi halaman. Nilai tugas dan kuis otomatis dicatat ke sistem penilaian guru pengampu mata pelajaran.
            </p>
        </div>

    </div>

</div>

@endsection
