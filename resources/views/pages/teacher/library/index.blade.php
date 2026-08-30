@extends('layouts.teacher.dashboardteacher')

@section('title', 'Library Modul — Repositori Pembelajaran Kolaboratif')
@section('page-title', 'Library Modul')

@section('content')

{{-- ══ Flash Alerts ══ --}}
@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">
        <div class="font-bold flex items-center gap-2 mb-1">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Terdapat kendala:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ══ 1. HEADER BANNER ══ --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 mb-8 border border-blue-700/40">
    {{-- Glow Elements --}}
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-3 max-w-3xl">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-blue-200">
                    <span>📚</span>
                    <span>Repositori Pembelajaran Terpadu</span>
                </span>
                <span class="text-white/40">•</span>
                <span class="text-slate-300 font-semibold">SMK Negeri 3 Yogyakarta</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                Library Modul & Kolaborasi Guru
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                Wadah kurikulum bersama untuk saling berbagi instrumen pembelajaran digital. Jelajahi modul karya guru lain, lakukan pratinjau komponen, dan salin modul ke <em>workspace</em> Anda untuk disesuaikan secara mandiri tanpa mengubah modul asli.
            </p>
        </div>

        {{-- Action Shortcut --}}
        <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-stretch sm:items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-xs sm:text-sm backdrop-blur-sm transition-all shadow-sm">
                <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <span>Manajer Modul Saya</span>
            </a>
            <a href="{{ route('teacher.modules.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-blue-600/30 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Modul Baru</span>
            </a>
        </div>
    </div>
</div>

{{-- ══ 2. STATISTIC METRIC CARDS ══ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
    {{-- Card 1: Total Modul di Library --}}
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-indigo-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul di Library</span>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-black border border-indigo-100 group-hover:scale-110 transition-transform">
                📚
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_shared'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Modul</span>
            </div>
            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Tersedia untuk disalin</p>
        </div>
    </div>

    {{-- Card 2: Guru Kontributor --}}
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-emerald-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Guru Kontributor</span>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_contributors'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Pendidik</span>
            </div>
            <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Berbagi instrumen aktif</p>
        </div>
    </div>

    {{-- Card 3: Total Adaptasi / Salinan --}}
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-blue-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Disalin</span>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-black border border-blue-100 group-hover:scale-110 transition-transform">
                🔄
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['total_cloned'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Kali</span>
            </div>
            <p class="text-[11px] text-blue-600 font-medium mt-0.5">Kloning ke workspace</p>
        </div>
    </div>

    {{-- Card 4: Modul Anda yang Dibagikan --}}
    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm relative overflow-hidden flex flex-col justify-between group hover:border-amber-300 transition-all">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Anda</span>
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-black border border-amber-100 group-hover:scale-110 transition-transform">
                🌟
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['my_shared_count'] }}</span>
                <span class="text-xs font-semibold text-slate-500">/ {{ $stats['my_total_modules'] }} Modul</span>
            </div>
            <p class="text-[11px] text-amber-600 font-medium mt-0.5">Dibagikan ke library</p>
        </div>
    </div>
</div>

{{-- ══ 3. TABS & FILTER BAR ══ --}}
<div class="space-y-4 mb-8">
    {{-- Quick Tabs --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('teacher.library.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all
                  {{ $tab === 'all' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>🌐 Semua Modul Library</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $stats['total_shared'] }}</span>
        </a>

        <a href="{{ route('teacher.library.index', array_merge(request()->except('tab', 'page'), ['tab' => 'others'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all
                  {{ $tab === 'others' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>👥 Karya Guru Lain</span>
        </a>

        <a href="{{ route('teacher.library.index', array_merge(request()->except('tab', 'page'), ['tab' => 'my_shared'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all
                  {{ $tab === 'my_shared' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>⭐ Modul Saya yang Dibagikan</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'my_shared' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $stats['my_shared_count'] }}</span>
        </a>
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm">
        <form action="{{ route('teacher.library.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            <input type="hidden" name="tab" value="{{ $tab }}">

            {{-- Search Bar --}}
            <div class="relative flex-1 min-w-[240px] flex items-center">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul modul atau nama guru penyusun..."
                       class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-slate-400">
            </div>

            {{-- Filter Controls --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Filter Tingkat --}}
                <div class="w-full sm:w-32">
                    <select name="grade" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 transition-all cursor-pointer">
                        <option value="">Semua Tingkat</option>
                        @foreach($availableGrades as $grade)
                            <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>Kelas {{ $grade }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jurusan --}}
                <div class="w-full sm:w-44">
                    <select name="major" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 transition-all cursor-pointer">
                        <option value="">Semua Jurusan</option>
                        @foreach($availableMajors as $major)
                            <option value="{{ $major }}" {{ request('major') == $major ? 'selected' : '' }}>{{ $major }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Guru --}}
                @if($contributors->count() > 1)
                <div class="w-full sm:w-44">
                    <select name="teacher_id" onchange="this.form.submit()"
                            class="w-full py-2.5 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-700 transition-all cursor-pointer">
                        <option value="">Semua Guru</option>
                        @foreach($contributors as $con)
                            <option value="{{ $con->id }}" {{ request('teacher_id') == $con->id ? 'selected' : '' }}>{{ $con->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Reset Button --}}
                @if(request()->hasAny(['search', 'grade', 'major', 'teacher_id']) || ($sort ?? '') !== 'latest')
                    <a href="{{ route('teacher.library.index', ['tab' => $tab]) }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 hover:text-slate-700 rounded-xl transition-all"
                       title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        {{-- Quick Filter Chips --}}
        <div class="flex items-center gap-1.5 flex-wrap pt-3 mt-3 border-t border-slate-100 text-xs">
            <span class="text-slate-400 font-bold text-[11px] mr-1">Filter Cepat:</span>
            
            <a href="{{ route('teacher.library.index', array_merge(request()->except('sort', 'page'), ['sort' => 'latest'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($sort ?? 'latest') === 'latest' ? 'bg-indigo-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                ✨ Terbaru Dibagikan
            </a>

            <a href="{{ route('teacher.library.index', array_merge(request()->except('sort', 'page'), ['sort' => 'popular'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($sort ?? '') === 'popular' ? 'bg-amber-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🔥 Paling Banyak Dikloning
            </a>

            <a href="{{ route('teacher.library.index', array_merge(request()->except('sort', 'page'), ['sort' => 'title_asc'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-all {{ ($sort ?? '') === 'title_asc' ? 'bg-purple-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                🔤 Judul (A - Z)
            </a>
        </div>
    </div>
</div>

{{-- ══ 4. GRID DAFTAR MODUL LIBRARY ══ --}}
@if($modules->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @foreach($modules as $mod)
            @php
                $isMine = $mod->teacher_id === auth()->guard('teacher')->id();
                $sections = $mod->moduleSectionsSummary();
                $activeCount = collect($sections)->sum('active_count');
                $totalCount = collect($sections)->sum('total_count');
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden group">
                
                {{-- Card Header --}}
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 leading-tight">
                                {{ $mod->teacher->name ?? 'Guru Pendidik' }}
                                @if($isMine)
                                    <span class="ml-1 text-[10px] px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-800 font-extrabold">Anda</span>
                                @endif
                            </h4>
                            <p class="text-[10px] text-slate-400">
                                Dibagikan {{ $mod->shared_at ? $mod->shared_at->diffForHumans() : $mod->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Target Grade, Major & Subject Badge --}}
                        <div class="flex items-center gap-1.5 flex-wrap justify-end">
                            @if($mod->subject)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold {{ $mod->subject->badgeClasses() }}">
                                    <span>{{ $mod->subject->icon }}</span>
                                    <span>{{ $mod->subject->name }}</span>
                                </span>
                            @endif
                            @if($mod->schoolClass)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 shrink-0">
                                    {{ $mod->schoolClass->grade }} {{ $mod->schoolClass->major_name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-base font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug line-clamp-2 mt-2">
                        <a href="{{ route('teacher.library.show', $mod) }}">
                            {{ $mod->title }}
                        </a>
                    </h3>

                    {{-- Cloned Origin Attribution if this was previously cloned --}}
                    @if($mod->clonedFrom)
                        <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1 truncate">
                            <span>🌱</span>
                            <span>Diadaptasi dari karya <strong>{{ $mod->clonedFrom->teacher->name ?? 'Pendidik' }}</strong></span>
                        </p>
                    @endif

                    {{-- Component Pills --}}
                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @if($mod->has_materi)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">📖 Materi</span>
                        @endif
                        @if($mod->has_video)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">🎬 Video</span>
                        @endif
                        @if($mod->has_pre_test)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">📝 Pre-test</span>
                        @endif
                        @if($mod->has_post_test)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">🎯 Post-test</span>
                        @endif
                        @if($mod->has_embed)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">🎮 Embed</span>
                        @endif
                        @if($mod->has_job_sheet)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">📋 Job Sheet</span>
                        @endif
                        @if($mod->has_lkpd)
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-cyan-50 text-cyan-700 border border-cyan-200">📑 LKPD</span>
                        @endif
                    </div>
                </div>

                {{-- Card Footer & Actions --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                    {{-- Clone count badge --}}
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 font-semibold" title="Jumlah guru yang telah menyalin modul ini">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                        </svg>
                        <span>{{ $mod->clone_count }}x Disalin</span>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('teacher.library.show', $mod) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl transition-all">
                            <span>Pratinjau</span>
                        </a>

                        {{-- Trigger Clone Modal --}}
                        <button type="button"
                                onclick="openCloneModal({{ $mod->id }}, '{{ addslashes($mod->title) }}', '{{ $mod->teacher->name ?? 'Guru' }}')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm shadow-indigo-600/25 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Salin Modul</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $modules->links() }}
    </div>

@else
    {{-- Empty State --}}
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm max-w-2xl mx-auto my-6">
        <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 border border-indigo-100 flex items-center justify-center text-3xl mx-auto mb-4">
            📚
        </div>
        <h3 class="text-lg font-black text-slate-900 mb-1">
            @if(request()->hasAny(['search', 'grade', 'major', 'component', 'teacher_id']))
                Tidak Ada Modul yang Cocok dengan Filter
            @elseif($tab === 'my_shared')
                Anda Belum Membagikan Modul ke Library
            @else
                Belum Ada Modul yang Dibagikan di Library
            @endif
        </h3>
        <p class="text-xs sm:text-sm text-slate-500 mb-6 max-w-md mx-auto">
            @if(request()->hasAny(['search', 'grade', 'major', 'component', 'teacher_id']))
                Coba sesuaikan kata kunci pencarian atau bersihkan filter kriteria untuk menampilkan modul pembelajaran lainnya.
            @elseif($tab === 'my_shared')
                Buka salah satu modul pembelajaran Anda di <strong>Manajer Modul</strong> dan aktifkan sakelar <em>"Bagikan ke Library"</em> untuk mulai berkontribusi bagi rekan guru lain.
            @else
                Jadilah pelopor dengan membagikan modul pembelajaran yang telah Anda susun agar dapat diadaptasi oleh rekan guru di SMK Negeri 3 Yogyakarta.
            @endif
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3">
            @if(request()->hasAny(['search', 'grade', 'major', 'component', 'teacher_id']))
                <a href="{{ route('teacher.library.index', ['tab' => $tab]) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
                    <span>Bersihkan Filter</span>
                </a>
            @else
                <a href="{{ route('teacher.modules.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all">
                    <span>Ke Manajer Modul Saya</span>
                </a>
            @endif
        </div>
    </div>
@endif

{{-- ══ MODAL SALIN MODUL KE WORKSPACE ══ --}}
<div id="cloneModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative transform transition-all">
        
        {{-- Close Button --}}
        <button type="button" onclick="closeCloneModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                📥
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Salin Modul ke Workspace</h3>
                <p class="text-xs text-slate-500">Duplikasi instrumen pembelajaran ke akun Anda</p>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-900 mb-5 leading-relaxed">
            <p class="font-bold mb-1">Informasi Kloning Modul:</p>
            <p class="text-indigo-800/80">
                Seluruh materi, catatan notepad, video YouTube, kuis Pre-test, Post-test, simulator embed, job sheet, dan LKPD akan disalin sebagai draf baru yang siap Anda modifikasi tanpa memengaruhi modul asli.
            </p>
        </div>

        <form id="cloneForm" method="POST" action="" class="space-y-4">
            @csrf
            
            {{-- Original Module Info --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Modul Sumber
                </label>
                <div id="modalSourceModuleTitle" class="text-xs sm:text-sm font-extrabold text-slate-800 bg-slate-100 px-3.5 py-2.5 rounded-xl border border-slate-200 truncate">
                    -
                </div>
            </div>

            {{-- Custom Title --}}
            <div>
                <label for="clone_title" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Judul Modul di Workspace Anda <span class="text-slate-400 font-normal">(Bisa disesuaikan)</span>
                </label>
                <input type="text"
                       name="title"
                       id="clone_title"
                       value=""
                       required
                       class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold text-slate-800 transition-all">
            </div>

            {{-- Target Class Selection --}}
            <div>
                <label for="clone_class_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Target Kelas Binaan Anda <span class="text-red-500">*</span>
                </label>
                <select name="class_id"
                        id="clone_class_id"
                        required
                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold text-slate-800 transition-all cursor-pointer">
                    <option value="" disabled selected>-- Pilih Kelas Binaan Target --</option>
                    @foreach($allClasses as $cls)
                        <option value="{{ $cls->id }}">Kelas {{ $cls->grade }} - {{ $cls->major_name }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Kelas yang akan menjadi target distribusi modul Anda.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                <button type="button" onclick="closeCloneModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Konfirmasi & Salin Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCloneModal(moduleId, moduleTitle, authorName) {
        const form = document.getElementById('cloneForm');
        form.action = "{{ url('/teacher/library') }}/" + moduleId + "/clone";

        document.getElementById('modalSourceModuleTitle').textContent = moduleTitle + ' (Oleh: ' + authorName + ')';
        document.getElementById('clone_title').value = moduleTitle;

        document.getElementById('cloneModal').classList.remove('hidden');
    }

    function closeCloneModal() {
        document.getElementById('cloneModal').classList.add('hidden');
    }
</script>

@endsection
