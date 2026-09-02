@extends('layouts.student.dashboardstudent')

@section('title', 'Mata Pelajaran ' . $class->full_name . ' — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Mata Pelajaran ' . $class->full_name)

@section('content')

@php
    $subjectsJson = $subjectsWithSummary->map(function($s) {
        return [
            'id'                => $s['id'],
            'name'              => $s['name'],
            'code'              => $s['code'] ?: 'MAPEL',
            'description'       => $s['description'] ?: '',
            'teacher_display'   => $s['teacher_display'],
            'modules_count'     => (int) $s['modules_count'],
            'completed_count'   => (int) $s['completed_count'],
            'in_progress_count' => (int) $s['in_progress_count'],
            'not_started_count' => (int) $s['not_started_count'],
            'avg_progress'      => (int) $s['avg_progress'],
            'status'            => $s['status'],
            'has_s1'            => (bool) $s['has_s1'],
            'has_s2'            => (bool) $s['has_s2'],
            's1_modules_count'  => (int) $s['s1_modules_count'],
            's2_modules_count'  => (int) $s['s2_modules_count'],
            'semesters'         => $s['semesters'],
        ];
    })->values()->toArray();
@endphp

<div class="space-y-8 pb-12"
     x-data="{
        selectedSemester: 'all',
        selectedStatus: 'all',
        searchKeyword: '',
        explorerView: 'grid',
        items: {{ json_encode($subjectsJson) }},
        matches(s) {
            // 1. Semester Filter (Dropdown)
            if (this.selectedSemester === '1' && !s.has_s1) return false;
            if (this.selectedSemester === '2' && !s.has_s2) return false;

            // 2. Status Progres Filter
            if (this.selectedStatus === 'in_progress') {
                if (!(s.in_progress_count > 0 || (s.avg_progress > 0 && s.avg_progress < 100))) return false;
            } else if (this.selectedStatus === 'completed') {
                if (!(s.modules_count > 0 && s.completed_count === s.modules_count)) return false;
            } else if (this.selectedStatus === 'not_started') {
                if (!(s.modules_count > 0 && s.completed_count === 0 && s.in_progress_count === 0)) return false;
            }

            // 3. Keyword Search
            if (this.searchKeyword.trim() !== '') {
                const kw = this.searchKeyword.toLowerCase();
                const matchName = (s.name || '').toLowerCase().includes(kw);
                const matchCode = (s.code || '').toLowerCase().includes(kw);
                const matchTeacher = (s.teacher_display || '').toLowerCase().includes(kw);
                const matchDesc = (s.description || '').toLowerCase().includes(kw);
                if (!matchName && !matchCode && !matchTeacher && !matchDesc) return false;
            }

            return true;
        },
        get totalVisible() {
            return this.items.filter(item => this.matches(item)).length;
        },
        resetFilters() {
            this.selectedSemester = 'all';
            this.selectedStatus = 'all';
            this.searchKeyword = '';
        },
        get isFiltered() {
            return (this.selectedSemester !== 'all' || this.selectedStatus !== 'all' || this.searchKeyword.trim() !== '');
        }
     }">

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
            <span class="text-slate-800 font-extrabold">{{ $class->full_name }}</span>
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
                </div>

                {{-- Title --}}
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    {{ $class->full_name }}
                </h1>

                {{-- Description --}}
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-2xl font-normal">
                    Direktori mata pelajaran pada rombel <strong>{{ $class->full_name }}</strong>. Pilih mata pelajaran di bawah untuk langsung membuka seluruh e-modul pembelajaran digital.
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

    {{-- ═══ 3. FILTER & SEARCH BAR ═══ --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm space-y-3">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">

            {{-- Live Search Input --}}
            <div class="relative flex-1 min-w-[220px] flex items-center">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text"
                       x-model="searchKeyword"
                       placeholder="Cari mata pelajaran atau nama guru pengampu..."
                       class="w-full pl-10 pr-9 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400 font-medium">
                
                {{-- Clear Search Button --}}
                <button type="button"
                        x-show="searchKeyword && searchKeyword.length > 0"
                        x-cloak
                        @click="searchKeyword = ''"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                    <span class="w-4 h-4 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[9px] font-bold text-slate-600">✕</span>
                </button>
            </div>

            {{-- Filter Controls --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Dropdown Pilihan Semester --}}
                <div class="w-full sm:w-44">
                    <select x-model="selectedSemester"
                            class="w-full py-2.5 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700 transition-all cursor-pointer">
                        <option value="all">Semua Semester</option>
                        <option value="1">Semester 1 (Ganjil)</option>
                        <option value="2">Semester 2 (Genap)</option>
                    </select>
                </div>

                {{-- Dropdown Status Belajar --}}
                <div class="w-full sm:w-44">
                    <select x-model="selectedStatus"
                            class="w-full py-2.5 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-700 transition-all cursor-pointer">
                        <option value="all">Semua Status Progres</option>
                        <option value="in_progress">Sedang Dipelajari</option>
                        <option value="completed">Tuntas Selesai</option>
                        <option value="not_started">Belum Dimulai</option>
                    </select>
                </div>

                {{-- View Toggle (Grid / List) --}}
                <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
                    <button type="button"
                            @click="explorerView = 'grid'"
                            :class="explorerView === 'grid' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                            title="Tampilan Grid Kartu"
                            class="p-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button type="button"
                            @click="explorerView = 'list'"
                            :class="explorerView === 'list' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                            title="Tampilan Daftar Rinci"
                            class="p-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                {{-- Reset Filter Button --}}
                <button type="button"
                        x-show="isFiltered"
                        x-cloak
                        @click="resetFilters()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition-all cursor-pointer"
                        title="Reset Semua Filter">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ═══ 4. DAFTAR MATA PELAJARAN (LANGSUNG TAMPIL) ═══ --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3 px-1">
            <div class="flex items-center gap-2">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Daftar Mata Pelajaran
                </h2>
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600"
                      x-text="'Menampilkan ' + totalVisible + ' dari ' + items.length + ' mapel'">
                </span>
            </div>

            <div x-show="isFiltered" x-cloak>
                <button type="button"
                        @click="resetFilters()"
                        class="text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                    Tampilkan Semua Mapel
                </button>
            </div>
        </div>

        {{-- ═══ VIEW MODE 1: GRID TILES ═══ --}}
        <div x-show="explorerView === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($subjectsWithSummary as $idx => $subj)
                <div x-show="matches(items[{{ $idx }}])"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="group bg-white rounded-3xl border border-slate-200/90 hover:border-emerald-500 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    
                    {{-- Konten Utama Card Mapel --}}
                    <div class="p-6 sm:p-7 space-y-5">
                        {{-- Top Header: Code Pill, Semester Indicator, and Total Modul Badge --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 font-mono font-black text-sm flex items-center justify-center group-hover:scale-105 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-2xs shrink-0">
                                {{ $subj['code'] ?: 'MAPEL' }}
                            </div>

                            <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                @if($subj['has_s1'])
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200"
                                          title="Tersedia modul Semester 1">
                                        <span>📙</span>
                                        <span>S1 ({{ $subj['s1_modules_count'] }})</span>
                                    </span>
                                @endif
                                @if($subj['has_s2'])
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-cyan-50 text-cyan-800 border border-cyan-200"
                                          title="Tersedia modul Semester 2">
                                        <span>📘</span>
                                        <span>S2 ({{ $subj['s2_modules_count'] }})</span>
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 shrink-0">
                                    {{ $subj['modules_count'] }} Modul
                                </span>
                            </div>
                        </div>

                        {{-- Judul Mata Pelajaran --}}
                        <div class="space-y-1">
                            <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors tracking-tight">
                                {{ $subj['name'] }}
                            </h3>
                            @if(!empty($subj['description']))
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $subj['description'] }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400 italic">
                                    Kurikulum pembelajaran SMK Negeri 3 Yogyakarta
                                </p>
                            @endif
                        </div>

                        {{-- Guru Pengampu Mapel --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                            <span class="text-slate-400 font-medium">Guru Pengampu:</span>
                            <span class="font-bold text-slate-800 truncate max-w-[180px] flex items-center gap-1">
                                <span>👨‍🏫</span>
                                <span>{{ $subj['teacher_display'] }}</span>
                            </span>
                        </div>

                        {{-- Progres Belajar Siswa pada Mapel ini --}}
                        <div class="space-y-2 pt-1">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-slate-500">Progres Pembelajaran:</span>
                                <span class="{{ $subj['avg_progress'] == 100 ? 'text-emerald-600' : ($subj['avg_progress'] > 0 ? 'text-amber-600' : 'text-slate-500') }}">
                                    {{ $subj['avg_progress'] }}%
                                </span>
                            </div>

                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-500 {{ $subj['avg_progress'] == 100 ? 'bg-emerald-500' : ($subj['avg_progress'] > 0 ? 'bg-amber-500' : 'bg-slate-300') }}"
                                     style="width: {{ $subj['avg_progress'] }}%"></div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5">
                                <span>Sedang Berjalan: <strong class="text-amber-600">{{ $subj['in_progress_count'] }}</strong></span>
                                <span>Tuntas: <strong class="text-emerald-600">{{ $subj['completed_count'] }}</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Button Footer Menuju Halaman Modul Mapel --}}
                    <div class="p-6 pt-0">
                        <a href="{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subj['id']]) }}"
                           :href="'{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subj['id']]) }}' + (selectedSemester !== 'all' ? '?semester=' + selectedSemester : '')"
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
                        📚
                    </div>
                    <div class="max-w-md mx-auto">
                        <h3 class="text-base font-bold text-slate-800">Belum Ada Mata Pelajaran</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Mata pelajaran untuk rombel kelas <strong>{{ $class->full_name }}</strong> belum tersedia atau belum ditugaskan modul pembelajaran.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ═══ VIEW MODE 2: DETAILS LIST (TABLE) ═══ --}}
        <div x-show="explorerView === 'list'" class="rounded-2xl bg-white border border-slate-200/90 shadow-sm overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Mata Pelajaran</th>
                        <th class="py-3.5 px-4">Guru Pengampu</th>
                        <th class="py-3.5 px-4 text-center">Semester</th>
                        <th class="py-3.5 px-4 text-center">Total Modul</th>
                        <th class="py-3.5 px-4 text-center">Kemajuan Belajar</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subjectsWithSummary as $idx => $subj)
                        <tr x-show="matches(items[{{ $idx }}])"
                            class="hover:bg-slate-50/80 transition-colors group">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 font-mono font-black text-xs flex items-center justify-center shrink-0 border border-slate-200/80 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                        {{ $subj['code'] ?: 'MP' }}
                                    </span>
                                    <div class="min-w-0">
                                        <a href="{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subj['id']]) }}"
                                           :href="'{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subj['id']]) }}' + (selectedSemester !== 'all' ? '?semester=' + selectedSemester : '')"
                                           class="font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors text-xs sm:text-sm block truncate">
                                            {{ $subj['name'] }}
                                        </a>
                                        @if(!empty($subj['description']))
                                            <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $subj['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 font-medium whitespace-nowrap">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] flex items-center justify-center border border-emerald-200 shrink-0">
                                        👨‍🏫
                                    </span>
                                    <span class="truncate max-w-[160px]">{{ $subj['teacher_display'] }}</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    @if($subj['has_s1'])
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200" title="Tersedia Semester 1">
                                            <span>📙</span><span>S1 ({{ $subj['s1_modules_count'] }})</span>
                                        </span>
                                    @endif
                                    @if($subj['has_s2'])
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-cyan-50 text-cyan-800 border border-cyan-200" title="Tersedia Semester 2">
                                            <span>📘</span><span>S2 ({{ $subj['s2_modules_count'] }})</span>
                                        </span>
                                    @endif
                                    @if(!$subj['has_s1'] && !$subj['has_s2'])
                                        <span class="text-[10px] text-slate-400 italic">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $subj['modules_count'] }} Modul
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="w-24 bg-slate-100 rounded-full h-1.5 overflow-hidden mx-auto">
                                    <div class="h-1.5 rounded-full {{ $subj['avg_progress'] == 100 ? 'bg-emerald-500' : ($subj['avg_progress'] > 0 ? 'bg-amber-500' : 'bg-slate-300') }}"
                                         style="width: {{ $subj['avg_progress'] }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500 mt-1 block">
                                    {{ $subj['completed_count'] }}/{{ $subj['modules_count'] }} ({{ $subj['avg_progress'] }}%)
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subj['id']]) }}"
                                   :href="'{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subj['id']]) }}' + (selectedSemester !== 'all' ? '?semester=' + selectedSemester : '')"
                                   class="px-3.5 py-2 rounded-xl bg-slate-900 group-hover:bg-emerald-600 text-white font-bold text-xs transition inline-flex items-center gap-1.5 shadow-2xs">
                                    <span>Buka Modul</span>
                                    <span>→</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada mata pelajaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Empty Filter State (Ketika pencarian/filter menghasilkan 0) --}}
        <div x-show="totalVisible === 0 && items.length > 0"
             x-cloak
             class="py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center mx-auto text-3xl font-black">
                🔍
            </div>
            <div class="max-w-md mx-auto space-y-1">
                <h3 class="text-base font-extrabold text-slate-800">Tidak Ada Mata Pelajaran yang Cocok</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tidak ditemukan mata pelajaran dengan filter semester atau kata kunci yang Anda pilih.
                </p>
            </div>
            <div>
                <button type="button"
                        @click="resetFilters()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    <span>Reset Filter & Pencarian</span>
                </button>
            </div>
        </div>
    </div>

</div>

@endsection
