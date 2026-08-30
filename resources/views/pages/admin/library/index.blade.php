@extends('layouts.admin.dashboardadmin')

@section('title', 'Supervisi Perpustakaan Modul (Library) — Admin E-Modul')
@section('page-title', 'Library Modul Overview')

@section('content')

@php
    $modulesToSerialize = isset($allShared) && $allShared->isNotEmpty() ? $allShared : $modules->getCollection();
    $modulesJson = $modulesToSerialize->map(function($m) {
        return [
            'id' => $m->id,
            'title' => $m->title,
            'teacher_id' => (string) $m->teacher_id,
            'teacher_name' => $m->teacher->name ?? 'Guru Pendidik',
            'teacher_nip' => $m->teacher->identity_number ?? '',
            'subject_id' => (string) $m->subject_id,
            'subject_name' => $m->subject?->name ?? 'Mapel',
            'subject_code' => $m->subject?->code ?? '-',
            'class_name' => $m->schoolClass?->short_name ?? ($m->schoolClass ? ($m->schoolClass->grade . ' ' . $m->schoolClass->major_name) : 'Semua Kelas'),
            'clone_count' => (int) $m->clone_count,
            'shared_at' => $m->shared_at ? $m->shared_at->format('d M Y') : $m->created_at->format('d M Y'),
            'shared_timestamp' => $m->shared_at ? $m->shared_at->timestamp : $m->created_at->timestamp,
            'has_materi' => (bool) $m->has_materi,
            'has_video' => (bool) $m->has_video,
            'has_pre_test' => (bool) $m->has_pre_test,
            'has_post_test' => (bool) $m->has_post_test,
            'has_lkpd' => (bool) $m->has_lkpd,
            'has_job_sheet' => (bool) $m->has_job_sheet,
            'has_embed' => (bool) $m->has_embed,
            'show_url' => route('admin.library.show', $m),
            'toggle_url' => route('admin.library.toggle-share', $m),
        ];
    })->values()->toArray();
@endphp

<div x-data="{
    searchKeyword: '{{ addslashes($search ?? '') }}',
    selectedSubject: '{{ addslashes($subjectId ?? 'all') }}',
    selectedTeacher: '{{ addslashes($teacherId ?? 'all') }}',
    activeTab: 'all',
    explorerView: 'grid',
    perPage: 9,
    currentPage: 1,
    items: {{ json_encode($modulesJson) }},
    get filteredList() {
        let list = [...this.items];
        
        // 1. Filter Mata Pelajaran (Instan Tanpa Refresh)
        if (this.selectedSubject !== 'all') {
            list = list.filter(m => String(m.subject_id) === String(this.selectedSubject));
        }

        // 2. Filter Guru Kontributor (Instan Tanpa Refresh)
        if (this.selectedTeacher !== 'all') {
            list = list.filter(m => String(m.teacher_id) === String(this.selectedTeacher));
        }

        // 3. Keyword Search (Instan Tanpa Refresh)
        if (this.searchKeyword.trim() !== '') {
            const kw = this.searchKeyword.toLowerCase();
            list = list.filter(m => {
                const matchTitle = (m.title || '').toLowerCase().includes(kw);
                const matchTeacher = (m.teacher_name || '').toLowerCase().includes(kw);
                const matchSubject = (m.subject_name || '').toLowerCase().includes(kw);
                const matchClass = (m.class_name || '').toLowerCase().includes(kw);
                return matchTitle || matchTeacher || matchSubject || matchClass;
            });
        }

        // 4. Status Filter & Sorting Tabs (Instan Tanpa Refresh)
        if (this.activeTab === 'cloned') {
            list = list.filter(m => m.clone_count > 0);
            list.sort((a, b) => b.clone_count - a.clone_count);
        } else if (this.activeTab === 'new') {
            list = list.filter(m => m.clone_count === 0);
        } else if (this.activeTab === 'latest') {
            list.sort((a, b) => b.shared_timestamp - a.shared_timestamp);
        } else if (this.activeTab === 'title_asc') {
            list.sort((a, b) => a.title.localeCompare(b.title));
        }

        return list;
    },
    get totalPages() {
        return Math.max(1, Math.ceil(this.filteredList.length / this.perPage));
    },
    get displayItems() {
        if (this.currentPage > this.totalPages) {
            this.currentPage = 1;
        }
        const start = (this.currentPage - 1) * this.perPage;
        return this.filteredList.slice(start, start + this.perPage);
    },
    goToPage(pageNum) {
        if (pageNum >= 1 && pageNum <= this.totalPages) {
            this.currentPage = pageNum;
            const targetEl = document.getElementById('module-explorer-section');
            if (targetEl) {
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    },
    resetFilters() {
        this.searchKeyword = '';
        this.selectedSubject = 'all';
        this.selectedTeacher = 'all';
        this.activeTab = 'all';
        this.currentPage = 1;
    },
    get isFiltered() {
        return this.searchKeyword.trim() !== '' || this.selectedSubject !== 'all' || this.selectedTeacher !== 'all' || this.activeTab !== 'all';
    }
}"
x-init="$watch('searchKeyword', () => currentPage = 1); $watch('selectedSubject', () => currentPage = 1); $watch('selectedTeacher', () => currentPage = 1); $watch('activeTab', () => currentPage = 1)">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Library Modul Sekolah</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Supervisi Perpustakaan Modul</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200" x-text="items.length + ' Modul Publik'">
                    {{ $stats['total_shared'] }} Modul Publik
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Katalog repositori modul bersama antar-guru. Pantau modul yang paling sering dijadikan referensi & dikloning oleh guru lain.
            </p>
        </div>
    </div>

    {{-- ══ Flash Alerts ══ --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ══ 2. KPI STATS CARDS ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Card 1: Total Modul Bersama --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul di Library</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ $stats['total_shared'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Tersedia untuk seluruh guru</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Adopsi / Kloning Guru --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Total Adopsi / Kloning</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-amber-600 mt-1">{{ $stats['total_clones'] }} <span class="text-sm font-semibold text-slate-400">kali</span></h3>
                    <p class="text-[11px] text-slate-500 mt-1">Frekuensi replikasi modul</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 3: Guru Kontributor --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Guru Kontributor</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">{{ $stats['total_contributors'] }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Pendidik aktif berbagi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.999-3.199a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 4: Rekor Kloning Tertinggi --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Adopsi Tertinggi</p>
                    <h3 class="text-2xl sm:text-3xl font-black text-indigo-600 mt-1">{{ $stats['most_popular_count'] }} <span class="text-sm font-semibold text-slate-400">klon</span></h3>
                    <p class="text-[11px] text-slate-500 mt-1">Pada 1 modul terfavorit</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0H9.496m5.004 0a3 3 0 002.996-3V7.5a3 3 0 00-3-3H9.5a3 3 0 00-3 3v4.875a3 3 0 002.996 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ 3. TOOLBAR: LIVE SEARCH, FILTER DROPDOWNS & VIEW MODE SWITCHER ══ --}}
    <div id="module-explorer-section" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-6 space-y-4">
        
        {{-- Row 1: Live Search + Filters + View Switcher (Sejajar di Versi Desktop) --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            
            {{-- Form Wrapper (Bisa Live Filter Tanpa Reload Halaman) --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 flex-1 min-w-0">
                
                {{-- Live Search Box (Flex-1) --}}
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text"
                           x-model="searchKeyword"
                           placeholder="Cari judul modul, guru, mapel, kelas..."
                           class="w-full pl-10 pr-9 py-2.5 text-xs bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition font-medium text-slate-800 placeholder-slate-400">
                    
                    {{-- Clear Search Button --}}
                    <button type="button"
                            x-show="searchKeyword && searchKeyword.length > 0"
                            x-cloak
                            @click="searchKeyword = ''"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <span class="w-4 h-4 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[9px] font-bold text-slate-600">✕</span>
                    </button>
                </div>

                {{-- Filter Mapel (Instan Tanpa Refresh) --}}
                <div class="w-full sm:w-auto sm:min-w-[160px]">
                    <select x-model="selectedSubject"
                            class="w-full text-xs rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all font-medium cursor-pointer">
                        <option value="all">-- Semua Mapel --</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->name }} ({{ $s->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Guru (Instan Tanpa Refresh) --}}
                <div class="w-full sm:w-auto sm:min-w-[160px]">
                    <select x-model="selectedTeacher"
                            class="w-full text-xs rounded-2xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all font-medium cursor-pointer">
                        <option value="all">-- Semua Guru --</option>
                        @foreach($contributors as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset Filter Button (Instan Tanpa Refresh) --}}
                <button type="button"
                        x-show="isFiltered"
                        x-cloak
                        @click="resetFilters()"
                        class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-bold transition-colors whitespace-nowrap text-center cursor-pointer">
                    Reset Filter
                </button>
            </div>

            {{-- ══ VIEW MODE SWITCHER (GRID vs LIST) ══ --}}
            <div class="flex items-center bg-slate-100 p-1 rounded-2xl border border-slate-200 shrink-0 self-start sm:self-center">
                <button type="button"
                        @click="explorerView = 'grid'"
                        :class="explorerView === 'grid' ? 'bg-white text-indigo-700 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        title="Tampilan Grid Kartu"
                        class="px-3 py-2 rounded-xl text-xs transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="text-[11px]">Grid</span>
                </button>

                <button type="button"
                        @click="explorerView = 'list'"
                        :class="explorerView === 'list' ? 'bg-white text-indigo-700 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        title="Tampilan Tabel / Daftar Rinci"
                        class="px-3 py-2 rounded-xl text-xs transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span class="text-[11px]">Tabel</span>
                </button>
            </div>

        </div>

        {{-- Row 2: Status Filter Tabs (Filter Cepat) --}}
        <div class="flex items-center gap-1.5 flex-wrap pt-3 border-t border-slate-100 text-xs">
            <span class="text-slate-400 font-bold text-[11px] mr-1">Filter Cepat:</span>
            
            <button type="button"
                    @click="activeTab = 'all'"
                    :class="activeTab === 'all' ? 'bg-indigo-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-3 py-1.5 rounded-xl text-xs transition cursor-pointer">
                Semua Modul (<span x-text="items.length"></span>)
            </button>

            <button type="button"
                    @click="activeTab = 'cloned'"
                    :class="activeTab === 'cloned' ? 'bg-amber-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-3 py-1.5 rounded-xl text-xs transition cursor-pointer">
                🔥 Pernah Dikloning (<span x-text="items.filter(m => m.clone_count > 0).length"></span>)
            </button>

            <button type="button"
                    @click="activeTab = 'new'"
                    :class="activeTab === 'new' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-3 py-1.5 rounded-xl text-xs transition cursor-pointer">
                ✨ Modul Baru (0 Klon)
            </button>

            <button type="button"
                    @click="activeTab = 'latest'"
                    :class="activeTab === 'latest' ? 'bg-blue-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-3 py-1.5 rounded-xl text-xs transition cursor-pointer">
                ✨ Terbaru Dibagikan
            </button>

            <button type="button"
                    @click="activeTab = 'title_asc'"
                    :class="activeTab === 'title_asc' ? 'bg-purple-600 text-white shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold'"
                    class="px-3 py-1.5 rounded-xl text-xs transition cursor-pointer">
                🔤 Judul (A - Z)
            </button>
        </div>

    </div>

    {{-- ══ 5. KATALOG MODUL BERSAMA ══ --}}
    @if(count($modulesJson) > 0)
        
        {{-- ═══ VIEW MODE 1: GRID CARDS ═══ --}}
        <div x-show="explorerView === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            <template x-for="m in displayItems" :key="'card-' + m.id">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 hover:border-indigo-300 hover:shadow-md transition-all flex flex-col justify-between group">
                    
                    <div>
                        {{-- Top Header Card --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            {{-- Kloning Badge --}}
                            <template x-if="m.clone_count > 0">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                    <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                                    </svg>
                                    <span x-text="m.clone_count + 'x Dikloning'"></span>
                                </span>
                            </template>
                            <template x-if="m.clone_count === 0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                    Modul Baru (0 Klon)
                                </span>
                            </template>

                            <span class="text-[10px] text-slate-400 font-mono" x-text="m.shared_at"></span>
                        </div>

                        {{-- Judul Modul --}}
                        <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2 leading-snug">
                            <a :href="m.show_url" x-text="m.title"></a>
                        </h3>

                        {{-- Guru Penyusun --}}
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-700 font-black text-[10px] flex items-center justify-center shrink-0 border border-indigo-100">
                                <span x-text="(m.teacher_name || 'G').substring(0, 1).toUpperCase()"></span>
                            </div>
                            <span class="text-xs font-bold text-slate-700 truncate" x-text="m.teacher_name"></span>
                        </div>

                        {{-- Meta: Mapel & Rombel Kelas --}}
                        <div class="flex items-center gap-2 text-xs text-slate-500 mb-4 flex-wrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100" x-text="m.subject_name"></span>
                            <span class="text-slate-300">•</span>
                            <span class="font-bold text-slate-700" x-text="m.class_name"></span>
                        </div>

                        {{-- Active Pedagogical Components Checklist --}}
                        <div class="pt-3 border-t border-slate-100 mb-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Komponen Pembelajaran:</p>
                            <div class="flex flex-wrap gap-1">
                                <template x-if="m.has_materi"><span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">📄 Materi</span></template>
                                <template x-if="m.has_video"><span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">🎬 Video</span></template>
                                <template x-if="m.has_pre_test"><span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-100">📝 Pre-Test</span></template>
                                <template x-if="m.has_post_test"><span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">🎯 Post-Test</span></template>
                                <template x-if="m.has_lkpd"><span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">📋 LKPD</span></template>
                                <template x-if="m.has_job_sheet"><span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">🛠️ Job Sheet</span></template>
                                <template x-if="m.has_embed"><span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-sky-50 text-sky-700 border border-sky-100">💻 Simulator</span></template>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Card: Actions --}}
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <form :action="m.toggle_url" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menarik modul ini dari Library Sekolah?');">
                            @csrf
                            <button type="submit"
                                    class="px-2.5 py-1.5 text-[11px] font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors border border-rose-200 cursor-pointer"
                                    title="Moderasi: Tarik modul dari perpustakaan publik">
                                Tarik Akses
                            </button>
                        </form>

                        <a :href="m.show_url"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs shadow-indigo-600/20 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pratinjau Modul</span>
                        </a>
                    </div>

                </div>
            </template>
        </div>

        {{-- ═══ VIEW MODE 2: DETAILS TABLE / LIST ═══ --}}
        <div x-show="explorerView === 'list'" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Modul & Guru Penyusun</th>
                            <th class="py-4 px-4">Mapel & Kelas</th>
                            <th class="py-4 px-4">Komponen Aktif</th>
                            <th class="py-4 px-4 text-center">Frekuensi Kloning</th>
                            <th class="py-4 px-4">Waktu Rilis</th>
                            <th class="py-4 px-6 text-right">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="m in displayItems" :key="'row-' + m.id">
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                {{-- Modul & Guru --}}
                                <td class="py-4 px-6">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-black text-xs flex items-center justify-center border border-indigo-100 shrink-0 mt-0.5">
                                            <span x-text="(m.teacher_name || 'G').substring(0, 2).toUpperCase()"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <a :href="m.show_url" class="font-bold text-slate-900 hover:text-indigo-600 text-xs transition-colors line-clamp-1" x-text="m.title"></a>
                                            <p class="text-[11px] text-slate-500 font-medium">Oleh: <span class="text-slate-800 font-bold" x-text="m.teacher_name"></span></p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Mapel & Kelas --}}
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 block w-fit mb-1" x-text="m.subject_name"></span>
                                    <span class="text-[11px] font-bold text-slate-700" x-text="m.class_name"></span>
                                </td>

                                {{-- Komponen --}}
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        <template x-if="m.has_materi"><span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700">Materi</span></template>
                                        <template x-if="m.has_video"><span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700">Video</span></template>
                                        <template x-if="m.has_pre_test"><span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-50 text-purple-700">Pre-test</span></template>
                                        <template x-if="m.has_post_test"><span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700">Post-test</span></template>
                                        <template x-if="m.has_lkpd"><span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-cyan-50 text-cyan-700">LKPD</span></template>
                                        <template x-if="m.has_job_sheet"><span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700">Job Sheet</span></template>
                                        <template x-if="m.has_embed"><span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-sky-50 text-sky-700">Simulator</span></template>
                                    </div>
                                </td>

                                {{-- Frekuensi Kloning --}}
                                <td class="py-4 px-4 text-center">
                                    <template x-if="m.clone_count > 0">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-700 border border-amber-200">
                                            <span>🔥</span>
                                            <span x-text="m.clone_count + 'x'"></span>
                                        </span>
                                    </template>
                                    <template x-if="m.clone_count === 0">
                                        <span class="text-slate-400 font-semibold text-[11px]">0 klon</span>
                                    </template>
                                </td>

                                {{-- Waktu Rilis --}}
                                <td class="py-4 px-4 text-slate-400 font-mono text-[11px]" x-text="m.shared_at"></td>

                                {{-- Aksi --}}
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form :action="m.toggle_url" method="POST" onsubmit="return confirm('Tarik modul ini dari Library Sekolah?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-[11px] font-bold transition-all cursor-pointer">
                                                Tarik
                                            </button>
                                        </form>

                                        <a :href="m.show_url" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs shadow-indigo-600/20 transition-all">
                                            <span>Pratinjau</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ══ 5. INTERACTIVE PAGINATION CONTROLS (1, 2, 3, 4 dst.) ══ --}}
        <div x-show="filteredList.length > 0" class="mt-8 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm">
            {{-- Info Range --}}
            <div class="text-xs font-semibold text-slate-500 text-center sm:text-left">
                Menampilkan <span class="font-black text-slate-800" x-text="filteredList.length === 0 ? 0 : ((currentPage - 1) * perPage) + 1"></span> sampai <span class="font-black text-slate-800" x-text="Math.min(currentPage * perPage, filteredList.length)"></span> dari <span class="font-black text-indigo-600" x-text="filteredList.length"></span> modul
            </div>

            {{-- Numeric Page Buttons & Navigation --}}
            <div class="flex items-center gap-1.5 flex-wrap justify-center" x-show="totalPages > 1">
                {{-- Prev Button --}}
                <button type="button"
                        @click="goToPage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed text-slate-400 bg-slate-50' : 'hover:bg-slate-100 text-slate-700 bg-white cursor-pointer'"
                        class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold rounded-xl border border-slate-200 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </button>

                {{-- Numbered Page Buttons (1, 2, 3, 4 dst.) --}}
                <template x-for="pageNum in totalPages" :key="'page-btn-' + pageNum">
                    <button type="button"
                            @click="goToPage(pageNum)"
                            :class="currentPage === pageNum ? 'bg-indigo-600 text-white font-black shadow-sm shadow-indigo-600/25 border-transparent' : 'bg-white hover:bg-slate-100 text-slate-700 font-bold border-slate-200'"
                            class="w-9 h-9 flex items-center justify-center text-xs rounded-xl border transition-all cursor-pointer"
                            x-text="pageNum">
                    </button>
                </template>

                {{-- Next Button --}}
                <button type="button"
                        @click="goToPage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed text-slate-400 bg-slate-50' : 'hover:bg-slate-100 text-slate-700 bg-white cursor-pointer'"
                        class="inline-flex items-center gap-1 px-3 py-2 text-xs font-bold rounded-xl border border-slate-200 transition-all">
                    <span class="hidden sm:inline">Selanjutnya</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </button>
            </div>
        </div>

        {{-- No match in Alpine --}}
        <div x-show="displayItems.length === 0" class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm mb-8">
            <p class="text-sm font-bold text-slate-800">Tidak ada modul yang cocok dengan filter atau kata kunci ini.</p>
            <button type="button" @click="resetFilters()" class="mt-3 px-4 py-2 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-xl hover:bg-indigo-100 transition-colors cursor-pointer">
                Reset Pencarian & Filter
            </button>
        </div>

    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm">
            <div class="w-16 h-16 rounded-3xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
            <h3 class="text-base font-black text-slate-900 mb-1">Belum Ada Modul di Library</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                Belum ada guru yang membagikan modul ke Library Sekolah atau kriteria pencarian tidak menemukan hasil.
            </p>
        </div>
    @endif

</div>

@endsection
