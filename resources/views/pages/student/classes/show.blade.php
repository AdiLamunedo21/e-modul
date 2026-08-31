@extends('layouts.student.dashboardstudent')

@section('title', ($selectedSemester ? ($selectedSemester == '2' ? 'Semester 2 (Genap)' : 'Semester 1 (Ganjil)') . ' — ' : '') . 'Mata Pelajaran ' . $class->full_name . ' — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Mata Pelajaran ' . $class->full_name)

@section('content')

<div class="space-y-8 pb-12">

    {{-- ══ Tombol Kembali & Breadcrumb Navigasi ══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 flex-wrap">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-bold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Dashboard Siswa</span>
            </a>
            <span>/</span>
            @if($selectedSemester)
                <a href="{{ route('student.classes.show', $class->id) }}" class="text-slate-600 hover:text-emerald-700 font-bold transition-colors">
                    {{ $class->full_name }}
                </a>
                <span>/</span>
                <span class="text-slate-900 font-extrabold">
                    {{ $selectedSemester == '2' ? 'Semester 2 (Genap)' : 'Semester 1 (Ganjil)' }}
                </span>
            @else
                <span class="text-slate-700 font-bold">{{ $class->full_name }}</span>
            @endif
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            @if($selectedSemester)
                <a href="{{ route('student.classes.show', $class->id) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 shadow-2xs transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Pilihan Semester</span>
                </a>
            @endif

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
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                {{-- Badges --}}
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
                    <span class="text-emerald-300 font-bold">Rombel Kelas</span>
                    <span class="text-white/30">•</span>
                    <span class="font-mono text-emerald-300 font-black uppercase tracking-wider">
                        KODE: {{ $class->code }}
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="text-slate-200">{{ $class->major?->name ?? 'Kejuruan' }}</span>
                    @if($selectedSemester)
                        <span class="text-white/30">•</span>
                        <span class="{{ $selectedSemester == '2' ? 'text-cyan-300' : 'text-amber-300' }} font-extrabold">
                            {{ $selectedSemester == '2' ? 'Semester 2 (Genap)' : 'Semester 1 (Ganjil)' }}
                        </span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    {{ $class->full_name }}
                </h1>

                {{-- Description --}}
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-2xl font-normal">
                    @if($selectedSemester)
                        Daftar mata pelajaran untuk <strong>{{ $selectedSemester == '2' ? 'Semester 2 (Genap)' : 'Semester 1 (Ganjil)' }}</strong> pada kelas <strong>{{ $class->full_name }}</strong>.
                    @else
                        Silakan pilih semester pembelajaran (Ganjil / Genap) untuk melihat mata pelajaran dan mempelajari modul.
                    @endif
                </p>
            </div>

            {{-- Ringkasan Capaian Kelas --}}
            <div class="flex items-center gap-4 bg-slate-950/50 border border-white/15 p-4 sm:p-5 rounded-2xl backdrop-blur-md shadow-sm shrink-0">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center font-bold text-sm shrink-0">
                    <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
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

    @if(!$selectedSemester)
        {{-- ══ TAMPILAN 1: HANYA 2 KARTU PILIHAN SEMESTER (GANJIL & GENAP) ══ --}}
        <div class="space-y-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Pilihan Semester Pembelajaran
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pilih periode semester di bawah untuk membuka direktori mata pelajaran dan e-modul pembelajaran:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Card Semester 1 (Ganjil) --}}
                <div class="rounded-3xl border border-slate-200/90 bg-white hover:border-amber-500 hover:shadow-xl transition-all duration-300 p-6 sm:p-8 flex flex-col justify-between group">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200 uppercase tracking-wider">
                                Periode Ganjil (1)
                            </span>
                            <span class="text-xs font-bold text-slate-400">
                                {{ $s1Stats['active_subjects'] }} Mapel
                            </span>
                        </div>

                        <div>
                            <h3 class="text-2xl font-black text-slate-900 group-hover:text-amber-700 transition-colors">
                                Semester 1 (Ganjil)
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                                Kurikulum pembelajaran untuk periode semester awal tahun ajaran.
                            </p>
                        </div>

                        {{-- Metrics Panel --}}
                        <div class="grid grid-cols-3 gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Modul</p>
                                <p class="text-base font-black text-slate-900 mt-0.5">{{ $s1Stats['total_modules'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mapel</p>
                                <p class="text-base font-black text-slate-900 mt-0.5">{{ $s1Stats['active_subjects'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Progres</p>
                                <p class="text-base font-black text-amber-700 mt-0.5">{{ $s1Stats['avg_progress'] }}%</p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                                <span>Ketuntasan Modul: {{ $s1Stats['completed_modules'] }}/{{ $s1Stats['total_modules'] }}</span>
                                <span class="font-bold text-amber-700">{{ $s1Stats['avg_progress'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full bg-amber-500 transition-all duration-500" style="width: {{ $s1Stats['avg_progress'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <a href="{{ route('student.classes.show', ['class' => $class->id, 'semester' => 1]) }}"
                           class="w-full py-3.5 px-5 rounded-2xl bg-slate-900 group-hover:bg-amber-600 text-white font-bold text-xs sm:text-sm transition-all shadow-md group-hover:shadow-amber-600/25 flex items-center justify-center gap-2">
                            <span>Buka Semester 1 (Ganjil)</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Card Semester 2 (Genap) --}}
                <div class="rounded-3xl border border-slate-200/90 bg-white hover:border-cyan-500 hover:shadow-xl transition-all duration-300 p-6 sm:p-8 flex flex-col justify-between group">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-black bg-cyan-50 text-cyan-800 border border-cyan-200 uppercase tracking-wider">
                                Periode Genap (2)
                            </span>
                            <span class="text-xs font-bold text-slate-400">
                                {{ $s2Stats['active_subjects'] }} Mapel
                            </span>
                        </div>

                        <div>
                            <h3 class="text-2xl font-black text-slate-900 group-hover:text-cyan-700 transition-colors">
                                Semester 2 (Genap)
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                                Kurikulum pembelajaran untuk periode semester lanjutan tahun ajaran.
                            </p>
                        </div>

                        {{-- Metrics Panel --}}
                        <div class="grid grid-cols-3 gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Modul</p>
                                <p class="text-base font-black text-slate-900 mt-0.5">{{ $s2Stats['total_modules'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mapel</p>
                                <p class="text-base font-black text-slate-900 mt-0.5">{{ $s2Stats['active_subjects'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Progres</p>
                                <p class="text-base font-black text-cyan-700 mt-0.5">{{ $s2Stats['avg_progress'] }}%</p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                                <span>Ketuntasan Modul: {{ $s2Stats['completed_modules'] }}/{{ $s2Stats['total_modules'] }}</span>
                                <span class="font-bold text-cyan-700">{{ $s2Stats['avg_progress'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full bg-cyan-500 transition-all duration-500" style="width: {{ $s2Stats['avg_progress'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100">
                        <a href="{{ route('student.classes.show', ['class' => $class->id, 'semester' => 2]) }}"
                           class="w-full py-3.5 px-5 rounded-2xl bg-slate-900 group-hover:bg-cyan-600 text-white font-bold text-xs sm:text-sm transition-all shadow-md group-hover:shadow-cyan-600/25 flex items-center justify-center gap-2">
                            <span>Buka Semester 2 (Genap)</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- ══ TAMPILAN 2: DAFTAR MATA PELAJARAN SETELAH MEMILIH SEMESTER ══ --}}
        <div class="space-y-6">
            {{-- Toolbar Semester Terpilih & Switcher --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-white rounded-2xl border border-slate-200 shadow-2xs">
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-xl text-xs font-black {{ $selectedSemester == '2' ? 'bg-cyan-100 text-cyan-900 border border-cyan-200' : 'bg-amber-100 text-amber-900 border border-amber-200' }}">
                        {{ $selectedSemester == '2' ? 'Semester 2 (Genap)' : 'Semester 1 (Ganjil)' }}
                    </span>
                    <span class="text-xs text-slate-500 font-medium">
                        Menampilkan {{ $subjectsWithSummary->count() }} mata pelajaran
                    </span>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold text-slate-400">Ganti Semester:</span>
                    <a href="{{ route('student.classes.show', ['class' => $class->id, 'semester' => 1]) }}"
                       class="px-3 py-1 rounded-xl text-xs font-bold transition-all {{ $selectedSemester === '1' ? 'bg-amber-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Semester 1 (Ganjil)
                    </a>
                    <a href="{{ route('student.classes.show', ['class' => $class->id, 'semester' => 2]) }}"
                       class="px-3 py-1 rounded-xl text-xs font-bold transition-all {{ $selectedSemester === '2' ? 'bg-cyan-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Semester 2 (Genap)
                    </a>
                    <a href="{{ route('student.classes.show', $class->id) }}"
                       class="px-3 py-1 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-900 transition-all">
                        Pilihan Semester
                    </a>
                </div>
            </div>

            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Daftar Mata Pelajaran
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pilih salah satu mata pelajaran di bawah untuk membuka seluruh e-modul pembelajaran yang telah dibuat oleh guru pengampu.
                </p>
            </div>

            {{-- Grid Card Mata Pelajaran --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($subjectsWithSummary as $subj)
                    <div class="group bg-white rounded-3xl border border-slate-200/90 hover:border-emerald-400 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                        
                        {{-- Konten Utama Card Mapel --}}
                        <div class="p-6 sm:p-7 space-y-5">
                            {{-- Top Header: Code Pill, and Total Modul Badge --}}
                            <div class="flex items-center justify-between gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 font-mono font-black text-sm flex items-center justify-center group-hover:scale-105 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-2xs">
                                    {{ $subj['code'] ?: 'MAPEL' }}
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $subj['modules_count'] }} Modul
                                </span>
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
                                    {{ $subj['teacher_display'] }}
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
                            <a href="{{ route('student.classes.subject', array_filter(['class' => $class->id, 'subject' => $subj['id'], 'semester' => $selectedSemester])) }}"
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
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl font-bold">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </div>
                        <div class="max-w-md mx-auto">
                            <h3 class="text-base font-bold text-slate-800">Belum Ada Mata Pelajaran</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Mata pelajaran untuk rombel kelas <strong>{{ $class->full_name }}</strong> {{ $selectedSemester == '2' ? 'pada Semester 2 (Genap)' : 'pada Semester 1 (Ganjil)' }} belum tersedia.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

</div>

@endsection
