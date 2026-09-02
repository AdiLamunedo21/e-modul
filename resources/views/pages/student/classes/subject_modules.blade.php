@extends('layouts.student.dashboardstudent')

@section('title', 'Modul ' . $subject->name . ' — ' . $class->full_name . ' — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Modul ' . $subject->name)

@section('content')

<div class="space-y-6 pb-12"
     x-data="{
        searchKeyword: '',
        activeFilter: '{{ $filterStatus }}',
        selectedSemester: '{{ $selectedSemester ?? 'all' }}',
        explorerView: 'grid',
        matchesFilter(module) {
            if (this.selectedSemester !== 'all' && String(module.semester) !== String(this.selectedSemester)) return false;
            if (this.activeFilter === 'in_progress' && module.progress_status !== 'in_progress') return false;
            if (this.activeFilter === 'completed' && module.progress_status !== 'completed') return false;
            if (this.activeFilter === 'not_started' && module.progress_status !== 'not_started') return false;
            
            if (this.searchKeyword.trim() !== '') {
                const kw = this.searchKeyword.toLowerCase();
                const titleMatch = (module.title || '').toLowerCase().includes(kw);
                const descMatch = (module.description || '').toLowerCase().includes(kw);
                const teacherMatch = (module.teacher_name || '').toLowerCase().includes(kw);
                return titleMatch || descMatch || teacherMatch;
            }
            return true;
        }
     }">

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ COMMAND BAR & BREADCRUMB ═══════════════════════════════════════════ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs p-3 sm:p-4 space-y-3">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            {{-- Breadcrumb Bersih Tanpa Ikon --}}
            <div class="flex items-center gap-2 text-xs font-semibold bg-slate-100/90 border border-slate-200/90 rounded-xl px-3 py-2 text-slate-600 flex-1 overflow-x-auto">
                <a href="{{ route('student.dashboard') }}" class="hover:text-emerald-700 font-bold shrink-0">
                    Home
                </a>
                <span class="text-slate-400 font-mono">›</span>
                <a href="{{ route('student.classes.show', $class->id) }}" class="hover:text-emerald-700 font-bold shrink-0">
                    {{ $class->full_name }}
                </a>
                <span class="text-slate-400 font-mono">›</span>
                <span class="text-slate-900 font-extrabold shrink-0">
                    {{ $subject->name }}
                </span>
            </div>

            {{-- Toolbar: Live Search & View Mode Switcher --}}
            <div class="flex items-center gap-2.5 shrink-0">
                {{-- Search Box --}}
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text"
                           x-model="searchKeyword"
                           placeholder="Cari modul..."
                           class="w-full pl-9 pr-8 py-2 text-xs bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition font-medium text-slate-800 placeholder-slate-400">
                    
                    {{-- Clear button --}}
                    <button type="button"
                            x-show="searchKeyword && searchKeyword.length > 0"
                            x-cloak
                            @click="searchKeyword = ''"
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <span class="w-4 h-4 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[9px] font-bold text-slate-600">✕</span>
                    </button>
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

                {{-- Back to Class Subjects --}}
                <a href="{{ route('student.classes.show', $class->id) }}"
                   title="Kembali ke Direktori Mata Pelajaran"
                   class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5 shrink-0">
                    <span>←</span>
                    <span class="hidden sm:inline">Kembali ke Mapel</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ══ Header Info Mapel ══ --}}
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-7 text-white shadow-lg relative overflow-hidden border border-slate-800">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap text-xs">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-400/30">
                        {{ $class->full_name }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-white font-mono font-bold">
                        KODE: {{ $class->code }}
                    </span>
                    <template x-if="selectedSemester === '1'">
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 font-bold">
                            Semester 1 (Ganjil)
                        </span>
                    </template>
                    <template x-if="selectedSemester === '2'">
                        <span class="px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-400/30 font-bold">
                            Semester 2 (Genap)
                        </span>
                    </template>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Modul Pembelajaran: {{ $subject->name }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    Guru Pengampu: <strong class="text-white">{{ $teacherDisplay }}</strong>
                </p>
            </div>

            <div class="flex items-center gap-4 bg-slate-950/60 border border-white/15 p-4 rounded-2xl backdrop-blur-md shrink-0">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Capaian Belajar</p>
                    <p class="text-base font-black text-white">
                        {{ $stats['completed_modules'] }}/{{ $stats['total_modules'] }} Modul Tuntas
                    </p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-24 bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="h-1.5 rounded-full bg-emerald-500" style="width: {{ $stats['avg_progress'] }}%"></div>
                        </div>
                        <span class="text-[11px] font-bold text-emerald-400">{{ $stats['avg_progress'] }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ 1. BAGIAN PALING ATAS: MODUL YANG BARU DIBUKA (RECENT) ═════════ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    @if($recentOpenedModules->isNotEmpty())
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    <h2 class="text-xs sm:text-sm font-black text-slate-900 tracking-tight uppercase">
                        Terbaru / Baru Dibuka
                    </h2>
                </div>
                <span class="text-xs text-slate-400 font-medium">
                    {{ $recentOpenedModules->count() }} modul aktif
                </span>
            </div>

            {{-- Recent Items Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($recentOpenedModules as $recMod)
                    <div class="group rounded-2xl bg-white border border-slate-200/90 hover:border-indigo-500 hover:shadow-md p-4 transition-all flex flex-col justify-between space-y-3">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 truncate">
                                    {{ $recMod['subject_name'] }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 shrink-0">
                                    {{ $recMod['last_accessed_at'] ? \Carbon\Carbon::parse($recMod['last_accessed_at'])->diffForHumans() : 'Baru diakses' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                                    {{ $recMod['title'] }}
                                </h3>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                    Guru: {{ $recMod['teacher_name'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Progres Belajar Bar --}}
                        <div class="space-y-1 pt-1">
                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                <span>Kemajuan:</span>
                                <span class="{{ $recMod['progress_status'] === 'completed' ? 'text-emerald-600' : 'text-indigo-600' }}">
                                    {{ $recMod['completed_tasks'] }}/{{ $recMod['total_components'] }} ({{ $recMod['progress_percent'] }}%)
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full {{ $recMod['progress_status'] === 'completed' ? 'bg-emerald-500' : 'bg-indigo-600' }}"
                                     style="width: {{ $recMod['progress_percent'] }}%"></div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold {{ $recMod['progress_status'] === 'completed' ? 'text-emerald-700' : 'text-amber-700' }}">
                                {{ $recMod['progress_status'] === 'completed' ? 'Tuntas' : 'Sedang Berjalan' }}
                            </span>
                            <a href="{{ route('student.modules.show', $recMod['id']) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition">
                                <span>{{ $recMod['progress_status'] === 'completed' ? 'Review Modul' : ($recMod['progress_status'] === 'in_progress' ? 'Lanjutkan Belajar' : 'Mulai Belajar') }}</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ 2. BAGIAN BAWAH: MODUL BARU DITAMBAHKAN / KATALOG MODUL GURU ═════ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="space-y-4 pt-2">
        {{-- Section Title & Filter Tabs --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                <div>
                    <h2 class="text-xs sm:text-sm font-black text-slate-900 tracking-tight uppercase">
                        Modul Baru Ditambahkan & Katalog Kelas
                    </h2>
                    <p class="text-[11px] text-slate-500">
                        Daftar modul yang dibuat oleh guru pengampu untuk kelas <strong>{{ $class->full_name }}</strong>.
                    </p>
                </div>
            </div>

            {{-- Filter Semester & Status Tabs --}}
            <div class="flex items-center gap-2 flex-wrap self-start sm:self-auto">
                {{-- Semester Filter Tabs --}}
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
                    <button type="button"
                            @click="selectedSemester = 'all'"
                            :class="selectedSemester === 'all' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer">
                        Semua Semester
                    </button>
                    <button type="button"
                            @click="selectedSemester = '1'"
                            :class="selectedSemester === '1' ? 'bg-amber-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer">
                        📙 S1 Ganjil
                    </button>
                    <button type="button"
                            @click="selectedSemester = '2'"
                            :class="selectedSemester === '2' ? 'bg-cyan-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer">
                        📘 S2 Genap
                    </button>
                </div>

                {{-- Status Filter Tabs --}}
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0 overflow-x-auto">
                    <button type="button"
                            @click="activeFilter = 'all'"
                            :class="activeFilter === 'all' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer">
                        Semua Status
                    </button>
                    <button type="button"
                            @click="activeFilter = 'in_progress'"
                            :class="activeFilter === 'in_progress' ? 'bg-amber-500 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer">
                        Sedang Dipelajari
                    </button>
                    <button type="button"
                            @click="activeFilter = 'completed'"
                            :class="activeFilter === 'completed' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer">
                        Tuntas
                    </button>
                    <button type="button"
                            @click="activeFilter = 'not_started'"
                            :class="activeFilter === 'not_started' ? 'bg-slate-700 text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-2.5 py-1 rounded-lg text-xs transition cursor-pointer">
                        Belum Mulai
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ VIEW MODE 1: GRID TILES ═══ --}}
        <div x-show="explorerView === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($newlyAddedModules as $idx => $mod)
                <div x-show="matchesFilter({{ json_encode($mod) }})"
                     class="group rounded-3xl bg-white border border-slate-200/90 hover:border-emerald-500 p-5 flex flex-col justify-between transition-all duration-200 shadow-sm hover:shadow-lg hover:-translate-y-1 relative">
                    
                    {{-- Header Card --}}
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1 flex-wrap">
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 uppercase truncate inline-block">
                                        {{ $mod['subject_name'] }}
                                    </span>
                                    @if(!empty($mod['semester_badge']))
                                        <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-md border {{ $mod['semester_badge']['color'] }} inline-flex items-center gap-1">
                                            <span>{{ $mod['semester_badge']['icon'] }}</span>
                                            <span>{{ $mod['semester_badge']['short'] }}</span>
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">
                                    {{ !empty($mod['created_at']) ? \Carbon\Carbon::parse($mod['created_at'])->translatedFormat('d M Y') : 'Terbit' }}
                                </span>
                            </div>

                            {{-- Badge Baru Ditambahkan / Status --}}
                            <div class="shrink-0 flex flex-col items-end gap-1">
                                @if($idx === 0 || !empty($mod['is_new']))
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-black bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wide">
                                        Baru Ditambahkan
                                    </span>
                                @endif

                                @if($mod['progress_status'] === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Tuntas
                                    </span>
                                @elseif($mod['progress_status'] === 'in_progress')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        {{ $mod['progress_percent'] }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                        Belum Mulai
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Title & Desc --}}
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-snug">
                                {{ $mod['title'] }}
                            </h3>
                            @if(!empty($mod['description']))
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $mod['description'] }}
                                </p>
                            @endif
                        </div>

                        {{-- Guru Pengampu --}}
                        <div class="text-xs text-slate-600 flex items-center gap-1.5 pt-1">
                            <span class="text-slate-400">Guru:</span>
                            <strong class="text-slate-800 truncate">{{ $mod['teacher_name'] }}</strong>
                        </div>

                        {{-- Component Tags --}}
                        <div class="flex items-center gap-1 flex-wrap pt-1">
                            @if($mod['has_pre_test'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Pre-test</span>@endif
                            @if($mod['has_materi'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Materi</span>@endif
                            @if($mod['has_video'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Video</span>@endif
                            @if($mod['has_embed'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Embed</span>@endif
                            @if($mod['has_job_sheet'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Job Sheet</span>@endif
                            @if($mod['has_lkpd'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">LKPD</span>@endif
                            @if($mod['has_post_test'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Post-test</span>@endif
                        </div>

                        {{-- Progress Bar --}}
                        <div class="space-y-1 pt-1">
                            <div class="flex items-center justify-between text-[10px] font-semibold text-slate-400">
                                <span>Progress Belajar:</span>
                                <span class="font-bold text-slate-700">{{ $mod['completed_tasks'] }}/{{ $mod['total_components'] }} Komponen ({{ $mod['progress_percent'] }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full {{ $mod['progress_status'] === 'completed' ? 'bg-emerald-500' : ($mod['progress_status'] === 'in_progress' ? 'bg-amber-500' : 'bg-slate-300') }}"
                                     style="width: {{ $mod['progress_percent'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <a href="{{ route('student.modules.show', $mod['id']) }}"
                           class="w-full py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-xs
                           {{ $mod['progress_status'] === 'completed'
                               ? 'bg-emerald-600 hover:bg-emerald-700 text-white'
                               : ($mod['progress_status'] === 'in_progress'
                                   ? 'bg-amber-600 hover:bg-amber-700 text-white'
                                   : 'bg-emerald-600 hover:bg-emerald-700 text-white') }}">
                            <span>{{ $mod['progress_status'] === 'completed' ? 'Review Modul' : ($mod['progress_status'] === 'in_progress' ? 'Lanjutkan Belajar' : 'Mulai Belajar') }}</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200 text-slate-400 space-y-2">
                    <p class="text-xs font-bold text-slate-700">Tidak ada modul yang ditemukan</p>
                </div>
            @endforelse
        </div>

        {{-- ═══ VIEW MODE 2: DETAILS LIST ═══ --}}
        <div x-show="explorerView === 'list'" class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Nama Modul</th>
                        <th class="py-3 px-4">Guru Pembuat</th>
                        <th class="py-3 px-4">Tanggal Ditambahkan</th>
                        <th class="py-3 px-4">Komponen</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Kemajuan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($newlyAddedModules as $idx => $mod)
                        <tr x-show="matchesFilter({{ json_encode($mod) }})"
                            class="hover:bg-slate-50/80 transition-colors group">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                <div>
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <a href="{{ route('student.modules.show', $mod['id']) }}" class="hover:text-emerald-600 font-extrabold">
                                            {{ $mod['title'] }}
                                        </a>
                                        @if(!empty($mod['semester_badge']))
                                            <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded border {{ $mod['semester_badge']['color'] }} inline-flex items-center gap-0.5">
                                                <span>{{ $mod['semester_badge']['icon'] }}</span>
                                                <span>{{ $mod['semester_badge']['short'] }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    @if($idx === 0 || !empty($mod['is_new']))
                                        <span class="text-[9px] font-black text-rose-600 uppercase">Baru Ditambahkan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 font-medium">
                                {{ $mod['teacher_name'] }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                {{ !empty($mod['created_at']) ? \Carbon\Carbon::parse($mod['created_at'])->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $mod['total_components'] }} Komponen
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($mod['progress_status'] === 'completed')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Tuntas
                                    </span>
                                @elseif($mod['progress_status'] === 'in_progress')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        {{ $mod['progress_percent'] }}%
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                        Belum Mulai
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden mx-auto">
                                    <div class="h-1.5 rounded-full {{ $mod['progress_status'] === 'completed' ? 'bg-emerald-500' : 'bg-indigo-600' }}"
                                         style="width: {{ $mod['progress_percent'] }}%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold">{{ $mod['progress_percent'] }}%</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('student.modules.show', $mod['id']) }}"
                                   class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition inline-flex items-center gap-1">
                                    <span>Mulai Belajar</span>
                                    <span>→</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                Tidak ada modul tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
