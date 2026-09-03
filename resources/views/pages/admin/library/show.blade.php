@extends('layouts.admin.dashboardadmin')

@section('title', 'Pratinjau Modul: ' . $module->title . ' — Admin E-Modul')
@section('page-title', 'Pratinjau & Supervisi Modul')

@push('styles')
<style>
    /* Prose styling for Uraian Materi */
    .materi-prose h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: .75rem; margin-top: 1.5rem; color: #0f172a; }
    .materi-prose h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1.25rem; color: #1e293b; }
    .materi-prose h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1rem; color: #334155; }
    .materi-prose p { margin-bottom: .75rem; line-height: 1.75; }
    .materi-prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: .75rem; }
    .materi-prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: .75rem; }
    .materi-prose li { margin-bottom: .25rem; line-height: 1.65; }
    .materi-prose img { max-width: 100%; height: auto; border-radius: .75rem; margin: 1rem auto; display: block; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .materi-prose table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: .875rem; }
    .materi-prose th, .materi-prose td { border: 1px solid #e2e8f0; padding: .5rem .75rem; text-align: left; }
    .materi-prose th { background: #f8fafc; font-weight: 700; color: #0f172a; }
    .materi-prose blockquote { border-left: 4px solid #4f46e5; background: #eef2ff; padding: .75rem 1rem; margin: 1rem 0; border-radius: 0 .5rem .5rem 0; font-style: italic; color: #3730a3; }
    .materi-prose hr { border: none; border-top: 2px solid #e2e8f0; margin: 1.5rem 0; }
    .materi-prose a { color: #4f46e5; text-decoration: underline; }
</style>
@endpush

@section('content')

@php
    $totalComponents = collect($sections)->sum('total_count');
    $totalActive = collect($sections)->sum('active_count');
    $activePercent = $totalComponents > 0 ? round(($totalActive / $totalComponents) * 100) : 0;

    // Definisikan seluruh halaman aktivitas yang aktif secara berurutan
    $pagesList = [];

    // Bagian 1: Bagian Awal
    if ($module->isInfoComponentActive('kata_pengantar')) {
        $pagesList[] = ['id' => 'kata_pengantar', 'sec' => 1, 'sec_name' => '1. Bagian Awal', 'title' => 'Kata Pengantar', 'icon' => '✏️', 'badge' => 'Pengantar', 'desc' => 'Prakata dan sambutan motivasi guru pengampu'];
    }
    if ($module->isInfoComponentActive('petunjuk_penggunaan')) {
        $pagesList[] = ['id' => 'petunjuk_penggunaan', 'sec' => 1, 'sec_name' => '1. Bagian Awal', 'title' => 'Petunjuk Penggunaan', 'icon' => '💡', 'badge' => 'Panduan', 'desc' => 'Panduan langkah belajar mandiri peserta didik'];
    }

    // Bagian 2: Pendahuluan
    if ($module->isInfoComponentActive('tujuan_pembelajaran')) {
        $pagesList[] = ['id' => 'tujuan_pembelajaran', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Tujuan & Capaian', 'icon' => '🎯', 'badge' => 'Capaian', 'desc' => 'Target kompetensi pembelajaran (CP & TP)'];
    }
    $hasPetaKonsep = !empty($informasiUmum['peta_konsep_text'])
        || !empty($informasiUmum['peta_konsep']['peta_konsep_text'])
        || !empty($informasiUmum['peta_konsep']['peta_konsep_image_path'])
        || (!empty($informasiUmum['peta_konsep']) && is_string($informasiUmum['peta_konsep']));
    if ($module->isInfoComponentActive('peta_konsep') && $hasPetaKonsep) {
        $pagesList[] = ['id' => 'peta_konsep', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Peta Konsep Materi', 'icon' => '🗺️', 'badge' => 'Alur Materi', 'desc' => 'Diagram hierarki konsep materi kejuruan'];
    }
    $hasGlosarium = !empty($informasiUmum['glosarium']) && (
        (is_array($informasiUmum['glosarium']) && count($informasiUmum['glosarium']) > 0)
        || (is_string($informasiUmum['glosarium']) && trim($informasiUmum['glosarium']) !== '')
    );
    if ($module->isInfoComponentActive('glosarium') && $hasGlosarium) {
        $pagesList[] = ['id' => 'glosarium', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Glosarium Istilah', 'icon' => '📖', 'badge' => 'Kamus', 'desc' => 'Kamus istilah teknis & konsep penting'];
    }
    if ($module->has_pre_test && $module->preTest) {
        $pagesList[] = ['id' => 'pre_test', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Pre-test (Diagnostik)', 'icon' => '⚡', 'badge' => 'Kuis Awal', 'desc' => 'Tes diagnostik awal kemampuan siswa'];
    }

    // Bagian 3: Kegiatan Belajar
    if ($module->has_materi) {
        $pagesList[] = ['id' => 'materi', 'sec' => 3, 'sec_name' => '3. Kegiatan Belajar', 'title' => 'Uraian Materi & PPT', 'icon' => '📖', 'badge' => 'Materi Inti', 'desc' => 'Uraian teori mendalam & slide presentasi'];
    }
    if ($module->has_video) {
        $pagesList[] = ['id' => 'video', 'sec' => 3, 'sec_name' => '3. Kegiatan Belajar', 'title' => 'Video & Resume YouTube', 'icon' => '▶️', 'badge' => 'Multimedia', 'desc' => 'Video interaktif & penulisan resume intisari'];
    }

    // Bagian 4: Evaluasi & Praktik
    if ($module->has_embed) {
        $pagesList[] = ['id' => 'embed', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Simulator Embed Interaktif', 'icon' => '🎮', 'badge' => 'Praktik', 'desc' => 'Eksplorasi simulator interaktif'];
    }
    if ($module->has_job_sheet) {
        $pagesList[] = ['id' => 'job_sheet', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Job Sheet Praktikum', 'icon' => '📑', 'badge' => 'Laboratorium', 'desc' => 'Panduan instruksi kerja praktikum'];
    }
    if ($module->has_lkpd) {
        $pagesList[] = ['id' => 'lkpd', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Tugas LKPD Siswa', 'icon' => '📋', 'badge' => 'Penugasan', 'desc' => 'Lembar kerja peserta didik'];
    }

    // Bagian 5: Bagian Akhir
    if ($module->has_post_test && $module->postTest) {
        $pagesList[] = ['id' => 'post_test', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Post-test (Evaluasi Akhir)', 'icon' => '🏆', 'badge' => 'Uji Akhir', 'desc' => 'Evaluasi ketuntasan belajar akhir modul'];
    }
    if ($module->isInfoComponentActive('daftar_pustaka') && !empty($informasiUmum['daftar_pustaka']['daftar_pustaka'])) {
        $pagesList[] = ['id' => 'daftar_pustaka', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Daftar Pustaka', 'icon' => '📚', 'badge' => 'Rujukan', 'desc' => 'Daftar referensi buku dan sumber materi'];
    }

    // Tambahan Tab Supervisi Admin: Riwayat Kloning
    $pagesList[] = ['id' => 'clones_history', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Riwayat Kloning Guru (' . $module->clone_count . ')', 'icon' => '👥', 'badge' => 'Admin Info', 'desc' => 'Daftar guru yang mengadopsi modul'];

    $initialPage = $pagesList[0]['id'] ?? 'kata_pengantar';
@endphp

<div x-data="{
    activePage: '{{ $initialPage }}',
    openSections: { 1: true, 2: true, 3: true, 4: true, 5: true },
    mobileDrawerOpen: false,
    searchGlosarium: '',
    pages: {{ json_encode($pagesList) }},
    get currentIndex() {
        return this.pages.findIndex(p => p.id === this.activePage);
    },
    get currentPage() {
        return this.pages[this.currentIndex] || this.pages[0];
    },
    get prevPage() {
        return this.currentIndex > 0 ? this.pages[this.currentIndex - 1] : null;
    },
    get nextPage() {
        return this.currentIndex < this.pages.length - 1 ? this.pages[this.currentIndex + 1] : null;
    },
    goToPage(pageId) {
        this.activePage = pageId;
        const target = this.pages.find(p => p.id === pageId);
        if (target) {
            this.openSections[target.sec] = true;
        }
        this.mobileDrawerOpen = false;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    toggleSection(secNum) {
        this.openSections[secNum] = !this.openSections[secNum];
    }
}" class="w-full space-y-6">

    {{-- ══ 1. BREADCRUMB & ADMIN HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.library.index') }}" class="hover:text-indigo-600 transition-colors">Library Modul</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold truncate max-w-xs sm:max-w-md">{{ $module->title }}</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Pratinjau E-Modul Pembelajaran</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200">
                    Mode Supervisi Admin
                </span>
            </h1>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.library.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold transition-all shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Library</span>
            </a>

            {{-- Moderasi Toggle Share --}}
            <form action="{{ route('admin.library.toggle-share', $module) }}" method="POST" onsubmit="return confirm('{{ $module->is_shared ? 'Tarik modul ini dari Library Publik?' : 'Publikasikan modul ini ke Library Publik?' }}');">
                @csrf
                @if($module->is_shared)
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span>Tarik dari Library Publik</span>
                    </button>
                @else
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/25 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Publikasikan ke Library</span>
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- ══ Flash Alerts ══ --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ══ 2. TOP HERO INFORMATION CARD (ADMIN OVERVIEW) ══ --}}
    <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-7 text-white shadow-xl shadow-indigo-950/20 border border-indigo-800/40 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div class="space-y-3 flex-1">
                {{-- Badges row --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if($module->is_shared)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                            🌐 Library Modul Bersama
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-slate-800 text-slate-300 border border-slate-700">
                            🔒 Nonaktif / Privat
                        </span>
                    @endif

                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-white border border-white/10">
                        {{ $module->subject?->name ?? 'Mata Pelajaran' }} ({{ $module->subject?->code ?? '-' }})
                    </span>

                    @if($module->schoolClass)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-slate-200 border border-white/10">
                            Kelas {{ $module->schoolClass->grade }} {{ $module->schoolClass->major_name }}
                        </span>
                    @endif

                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $module->semester_badge['color'] }}">
                        {{ $module->semester_badge['label'] }}
                    </span>

                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30">
                        🔥 {{ $module->clone_count }}x Dikloning
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white leading-snug">
                    {{ $module->title }}
                </h1>

                {{-- Author & Meta --}}
                <div class="flex items-center gap-3 pt-1 text-xs text-slate-300 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-indigo-600 text-white font-black text-[10px] flex items-center justify-center">
                            {{ strtoupper(substr($module->teacher->name ?? 'G', 0, 1)) }}
                        </div>
                        <span>Penyusun: <strong class="text-white">{{ $module->teacher->name ?? 'Guru' }}</strong> (NIP: {{ $module->teacher->identity_number ?? '-' }})</span>
                    </div>
                    <span>•</span>
                    <span>Rilis: {{ $module->shared_at ? $module->shared_at->format('d M Y') : $module->created_at->format('d M Y') }}</span>
                    @if($module->clonedFrom)
                        <span>•</span>
                        <span class="text-amber-300">🌱 Adaptasi dari: <strong>{{ $module->clonedFrom->teacher->name ?? 'Pendidik' }}</strong></span>
                    @endif
                </div>
            </div>

            {{-- Metric Box --}}
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 flex items-center gap-4 shrink-0">
                <div class="text-center px-2">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Kelengkapan</p>
                    <p class="text-2xl font-black text-white mt-0.5">{{ $totalActive }}/{{ $totalComponents }}</p>
                    <p class="text-[10px] text-indigo-300 font-semibold">{{ $activePercent }}% Aktif</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-center px-2">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Langkah</p>
                    <p class="text-2xl font-black text-amber-300 mt-0.5">{{ count($pagesList) }}</p>
                    <p class="text-[10px] text-slate-300 font-semibold">Siap Diinspeksi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ 3. INTERACTIVE LEARNING PLAYER LAYOUT (SILABUS + WORKSPACE) ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full">

        {{-- ── LEFT COLUMN: ACCORDION SILABUS MODUL (4 Cols on Desktop) ── --}}
        <div class="lg:col-span-4 w-full sticky top-6 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 overflow-hidden">
                <div class="flex items-center justify-between pb-3.5 mb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-wide">Silabus Pembelajaran</h2>
                    </div>
                    <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                        {{ count($pagesList) }} Langkah
                    </span>
                </div>

                {{-- Accordion List 5 Bagian --}}
                <div class="space-y-3 text-xs">
                    
                    {{-- 1. Bagian Awal --}}
                    @php $sec1Items = collect($pagesList)->where('sec', 1); @endphp
                    @if($sec1Items->isNotEmpty())
                        <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                            <button type="button" @click="toggleSection(1)" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 font-bold text-slate-800 flex items-center justify-between transition cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-black">1</span>
                                    <span>Bagian Awal</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="openSections[1] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openSections[1]" class="p-2 space-y-1.5 bg-white">
                                @foreach($sec1Items as $item)
                                    <button type="button"
                                            @click="goToPage('{{ $item['id'] }}')"
                                            :class="activePage === '{{ $item['id'] }}'
                                                ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/25 border-transparent'
                                                : 'bg-white hover:bg-slate-50 text-slate-700 font-medium border-slate-200/70'"
                                            class="w-full px-3 py-2 rounded-xl border flex items-center justify-between text-left transition-all cursor-pointer">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="text-sm shrink-0">{{ $item['icon'] }}</span>
                                            <span class="truncate" :class="activePage === '{{ $item['id'] }}' ? 'text-white font-bold' : 'text-slate-800'">{{ $item['title'] }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md shrink-0 transition-colors"
                                              :class="activePage === '{{ $item['id'] }}' ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-700'">
                                            {{ $item['badge'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 2. Pendahuluan --}}
                    @php $sec2Items = collect($pagesList)->where('sec', 2); @endphp
                    @if($sec2Items->isNotEmpty())
                        <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                            <button type="button" @click="toggleSection(2)" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 font-bold text-slate-800 flex items-center justify-between transition cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">2</span>
                                    <span>Pendahuluan</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="openSections[2] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openSections[2]" class="p-2 space-y-1.5 bg-white">
                                @foreach($sec2Items as $item)
                                    <button type="button"
                                            @click="goToPage('{{ $item['id'] }}')"
                                            :class="activePage === '{{ $item['id'] }}'
                                                ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/25 border-transparent'
                                                : 'bg-white hover:bg-slate-50 text-slate-700 font-medium border-slate-200/70'"
                                            class="w-full px-3 py-2 rounded-xl border flex items-center justify-between text-left transition-all cursor-pointer">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="text-sm shrink-0">{{ $item['icon'] }}</span>
                                            <span class="truncate" :class="activePage === '{{ $item['id'] }}' ? 'text-white font-bold' : 'text-slate-800'">{{ $item['title'] }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md shrink-0 transition-colors"
                                              :class="activePage === '{{ $item['id'] }}' ? 'bg-white/20 text-white' : 'bg-indigo-50 text-indigo-700'">
                                            {{ $item['badge'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Kegiatan Belajar --}}
                    @php $sec3Items = collect($pagesList)->where('sec', 3); @endphp
                    @if($sec3Items->isNotEmpty())
                        <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                            <button type="button" @click="toggleSection(3)" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 font-bold text-slate-800 flex items-center justify-between transition cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-[10px] font-black">3</span>
                                    <span>Kegiatan Belajar</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="openSections[3] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openSections[3]" class="p-2 space-y-1.5 bg-white">
                                @foreach($sec3Items as $item)
                                    <button type="button"
                                            @click="goToPage('{{ $item['id'] }}')"
                                            :class="activePage === '{{ $item['id'] }}'
                                                ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/25 border-transparent'
                                                : 'bg-white hover:bg-slate-50 text-slate-700 font-medium border-slate-200/70'"
                                            class="w-full px-3 py-2 rounded-xl border flex items-center justify-between text-left transition-all cursor-pointer">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="text-sm shrink-0">{{ $item['icon'] }}</span>
                                            <span class="truncate" :class="activePage === '{{ $item['id'] }}' ? 'text-white font-bold' : 'text-slate-800'">{{ $item['title'] }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md shrink-0 transition-colors"
                                              :class="activePage === '{{ $item['id'] }}' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-700'">
                                            {{ $item['badge'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 4. Evaluasi & Praktik --}}
                    @php $sec4Items = collect($pagesList)->where('sec', 4); @endphp
                    @if($sec4Items->isNotEmpty())
                        <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                            <button type="button" @click="toggleSection(4)" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 font-bold text-slate-800 flex items-center justify-between transition cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-[10px] font-black">4</span>
                                    <span>Evaluasi & Praktik</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="openSections[4] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openSections[4]" class="p-2 space-y-1.5 bg-white">
                                @foreach($sec4Items as $item)
                                    <button type="button"
                                            @click="goToPage('{{ $item['id'] }}')"
                                            :class="activePage === '{{ $item['id'] }}'
                                                ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/25 border-transparent'
                                                : 'bg-white hover:bg-slate-50 text-slate-700 font-medium border-slate-200/70'"
                                            class="w-full px-3 py-2 rounded-xl border flex items-center justify-between text-left transition-all cursor-pointer">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="text-sm shrink-0">{{ $item['icon'] }}</span>
                                            <span class="truncate" :class="activePage === '{{ $item['id'] }}' ? 'text-white font-bold' : 'text-slate-800'">{{ $item['title'] }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md shrink-0 transition-colors"
                                              :class="activePage === '{{ $item['id'] }}' ? 'bg-white/20 text-white' : 'bg-purple-50 text-purple-700'">
                                            {{ $item['badge'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 5. Bagian Akhir --}}
                    @php $sec5Items = collect($pagesList)->where('sec', 5); @endphp
                    @if($sec5Items->isNotEmpty())
                        <div class="rounded-2xl border border-slate-200/70 overflow-hidden">
                            <button type="button" @click="toggleSection(5)" class="w-full px-3.5 py-2.5 bg-slate-50 hover:bg-slate-100 font-bold text-slate-800 flex items-center justify-between transition cursor-pointer">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center text-[10px] font-black">5</span>
                                    <span>Bagian Akhir</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="openSections[5] ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openSections[5]" class="p-2 space-y-1.5 bg-white">
                                @foreach($sec5Items as $item)
                                    <button type="button"
                                            @click="goToPage('{{ $item['id'] }}')"
                                            :class="activePage === '{{ $item['id'] }}'
                                                ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/25 border-transparent'
                                                : 'bg-white hover:bg-slate-50 text-slate-700 font-medium border-slate-200/70'"
                                            class="w-full px-3 py-2 rounded-xl border flex items-center justify-between text-left transition-all cursor-pointer">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="text-sm shrink-0">{{ $item['icon'] }}</span>
                                            <span class="truncate" :class="activePage === '{{ $item['id'] }}' ? 'text-white font-bold' : 'text-slate-800'">{{ $item['title'] }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md shrink-0 transition-colors"
                                              :class="activePage === '{{ $item['id'] }}' ? 'bg-white/20 text-white' : 'bg-teal-50 text-teal-700'">
                                            {{ $item['badge'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Quick Hint Box --}}
            <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4 text-xs text-indigo-900">
                <p class="font-bold flex items-center gap-1.5 mb-1">
                    <span>💡</span>
                    <span>Mode Supervisi Administrator</span>
                </p>
                <p class="text-indigo-800/80 text-[11px] leading-relaxed">
                    Anda dapat bebas berpindah ke seluruh langkah kurikulum tanpa kunci sekuensial. Kunci jawaban dan berkas instrumen ditampilkan dalam mode inspeksi baca.
                </p>
            </div>
        </div>

        {{-- ── RIGHT COLUMN: WORKSPACE AKTIVITAS PEMBELAJARAN (8 Cols on Desktop) ── --}}
        <div class="lg:col-span-8 space-y-6 w-full">

            {{-- 1. Kata Pengantar --}}
            <div x-show="activePage === 'kata_pengantar'" x-cloak class="w-full space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">✏️</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian 1 • Pengantar</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kata Pengantar</h2>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-bold">
                            Instrumen Aktif
                        </span>
                    </div>

                    <div class="prose prose-slate max-w-none text-sm sm:text-base text-slate-700 leading-relaxed space-y-3">
                        @if(!empty($informasiUmum['kata_pengantar']['kata_pengantar_text']))
                            {!! nl2br(e($informasiUmum['kata_pengantar']['kata_pengantar_text'])) !!}
                        @elseif(!empty($informasiUmum['kata_pengantar']) && is_string($informasiUmum['kata_pengantar']))
                            {!! nl2br(e($informasiUmum['kata_pengantar'])) !!}
                        @else
                            <p class="italic text-slate-400">
                                Puji syukur ke hadirat Tuhan Yang Maha Esa atas tersusunnya E-Modul ini sebagai media pembelajaran interaktif bagi siswa SMKN 3 Yogyakarta. Semoga modul ini dapat memfasilitasi pembelajaran mandiri yang efektif dan menyenangkan.
                            </p>
                        @endif
                    </div>

                    <div class="mt-8 pt-4 border-t border-slate-100 text-right text-xs text-slate-500">
                        <p>{{ $informasiUmum['kata_pengantar']['tempat_tanggal'] ?? 'Yogyakarta, ' . date('d F Y') }}</p>
                        <p class="font-bold text-slate-900 mt-1 text-sm">{{ $informasiUmum['kata_pengantar']['nama_penyusun'] ?? $module->teacher->name }}</p>
                        <p class="text-[11px] text-slate-400">Guru Pengampu Mata Pelajaran</p>
                    </div>
                </div>
            </div>

            {{-- 2. Petunjuk Penggunaan --}}
            <div x-show="activePage === 'petunjuk_penggunaan'" x-cloak class="w-full space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">💡</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian 1 • Panduan</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Petunjuk Penggunaan E-Modul</h2>
                            </div>
                        </div>
                    </div>

                    {{-- Petunjuk Umum --}}
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Petunjuk Belajar Mandiri:</h3>
                        <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed bg-slate-50 p-5 rounded-2xl border border-slate-200/60">
                            @if(!empty($informasiUmum['petunjuk_penggunaan']['petunjuk_umum']))
                                {!! nl2br(e($informasiUmum['petunjuk_penggunaan']['petunjuk_umum'])) !!}
                            @else
                                <ul class="list-disc pl-5 space-y-1.5 text-xs text-slate-600">
                                    <li>Berdoalah sebelum memulai kegiatan belajar.</li>
                                    <li>Pelajari materi secara bertahap mulai dari pendahuluan, materi inti, hingga evaluasi.</li>
                                    <li>Kerjakan asesmen diagnostik dan tugas praktik sesuai instruksi guru.</li>
                                    <li>Konsultasikan materi yang belum dipahami kepada guru pengampu.</li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Tujuan Pembelajaran (CP & TP) --}}
            <div x-show="activePage === 'tujuan_pembelajaran'" x-cloak class="w-full space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">🎯</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian 2 • Target Kompetensi</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Capaian & Tujuan Pembelajaran</h2>
                            </div>
                        </div>
                    </div>

                    {{-- Capaian Pembelajaran --}}
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Capaian Pembelajaran (CP):</h3>
                        <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 text-sm text-slate-800 leading-relaxed">
                            {{ $informasiUmum['tujuan_pembelajaran']['capaian_pembelajaran'] ?? 'Peserta didik mampu menguasai kompetensi dasar kejuruan sesuai konsentrasi keahlian.' }}
                        </div>
                    </div>

                    {{-- Tujuan Pembelajaran --}}
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Tujuan Pembelajaran (TP):</h3>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm text-slate-800 leading-relaxed">
                            @if(!empty($informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran']))
                                {!! nl2br(e($informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran'])) !!}
                            @else
                                <p class="italic text-slate-400">Tujuan pembelajaran telah dirumuskan dalam modul ajar kurikulum merdeka.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Peta Konsep --}}
            <div x-show="activePage === 'peta_konsep'" x-cloak class="w-full space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">🗺️</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian 2 • Struktur Materi</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Peta Konsep Materi</h2>
                            </div>
                        </div>
                    </div>

                    @php
                        $adminPetaText = $informasiUmum['peta_konsep_text']
                            ?? ($informasiUmum['peta_konsep']['peta_konsep_text']
                            ?? (is_string($informasiUmum['peta_konsep'] ?? null) ? $informasiUmum['peta_konsep'] : ''));
                        $adminPetaImage = $informasiUmum['peta_konsep']['peta_konsep_image_path'] ?? ($informasiUmum['peta_konsep_image_path'] ?? null);
                    @endphp

                    @if(!empty($adminPetaImage))
                        <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 p-4 text-center">
                            <img src="{{ Storage::url($adminPetaImage) }}" alt="Peta Konsep" class="max-w-full h-auto mx-auto rounded-xl shadow-xs">
                        </div>
                    @endif

                    @if(!empty($adminPetaText))
                        <div class="prose prose-slate text-sm text-slate-700 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                            {!! nl2br(e($adminPetaText)) !!}
                        </div>
                    @endif
                </div>
            </div>

            {{-- 5. Glosarium --}}
            <div x-show="activePage === 'glosarium'" x-cloak class="w-full space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">📖</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian 2 • Glosarium Istilah</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kamus Istilah Teknis</h2>
                            </div>
                        </div>
                    </div>

                    @php
                        $adminGlosarium = [];
                        if (isset($informasiUmum['glosarium'])) {
                            $adminGlosarium = is_array($informasiUmum['glosarium']) && isset($informasiUmum['glosarium']['glosarium'])
                                ? $informasiUmum['glosarium']['glosarium']
                                : (array) $informasiUmum['glosarium'];
                        }
                    @endphp

                    @if(!empty($adminGlosarium) && is_array($adminGlosarium))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                            @foreach($adminGlosarium as $gItem)
                                @php
                                    $gIstilah = is_array($gItem) ? ($gItem['istilah'] ?? '') : '';
                                    $gDefinisi = is_array($gItem) ? ($gItem['definisi'] ?? '') : $gItem;
                                @endphp
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <h5 class="text-xs font-bold text-teal-900 mb-1 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                        <span>{{ $gIstilah ?: 'Istilah' }}</span>
                                    </h5>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $gDefinisi }}</p>
                                </div>
                            @endforeach
                        </div>
                    @elseif(!empty($adminGlosarium) && is_string($adminGlosarium))
                        <div class="prose prose-slate text-sm text-slate-700 bg-slate-50 p-5 rounded-2xl border border-slate-200 leading-relaxed">
                            {!! nl2br(e($adminGlosarium)) !!}
                        </div>
                    @else
                        <p class="italic text-slate-400">Belum ada glosarium yang ditambahkan.</p>
                    @endif
                </div>
            </div>

            {{-- 6. Pre-test (Diagnostik) --}}
            @if($module->has_pre_test && $module->preTest)
                <div x-show="activePage === 'pre_test'" x-cloak class="w-full space-y-6">
                    <div class="rounded-3xl bg-white border border-teal-200/90 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3.5">
                                <span class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-2xl font-bold shrink-0">⚡</span>
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-600">Bagian 2 • Latihan Diagnostik</span>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ $module->preTest->title ?? 'Pre-test Pembuka' }}</h2>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Target KKTP: {{ $module->preTest->kktp ?? 75 }} • Total: {{ $module->preTest->questions->count() }} Soal</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-200 text-xs font-bold self-start sm:self-center">
                                Mode Inspeksi Soal & Kunci Jawaban
                            </span>
                        </div>

                        <div class="p-4 bg-teal-50/60 rounded-2xl border border-teal-100 text-xs text-teal-900 flex items-center gap-2">
                            <span>🔍</span>
                            <span><strong>Keterangan Admin:</strong> Kunci jawaban resmi guru ditandai dengan label hijau bercentang pada opsi jawaban.</span>
                        </div>

                        {{-- Question list --}}
                        <div class="space-y-6">
                            @foreach($module->preTest->questions as $idx => $q)
                                <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                                    <div class="flex items-start gap-3">
                                        <span class="w-7 h-7 rounded-xl bg-teal-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                            {{ $idx + 1 }}
                                        </span>
                                        <div class="text-sm font-bold text-slate-900 leading-relaxed flex-1">
                                            {{ $q->question_text }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-2.5 pl-10">
                                        @foreach(['A', 'B', 'C', 'D', 'E'] as $optKey)
                                            @if(!empty($q->options[$optKey]))
                                                @php $isCorrect = strtoupper($q->correct_answer ?? '') === $optKey; @endphp
                                                <div class="flex items-center justify-between p-3 rounded-xl border transition {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-white border-slate-200 text-slate-700' }}">
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-xs font-bold {{ $isCorrect ? 'text-emerald-700' : 'text-slate-500' }} w-5">{{ $optKey }}.</span>
                                                        <span class="text-xs sm:text-sm">{{ $q->options[$optKey] }}</span>
                                                    </div>
                                                    @if($isCorrect)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-600 text-white shadow-xs">
                                                            ✓ Kunci Jawaban
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- 7. Uraian Materi & PPT --}}
            @if($module->has_materi)
                <div x-show="activePage === 'materi'" x-cloak class="w-full space-y-6">
                    <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">📖</span>
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian 3 • Materi Teori</span>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Uraian Materi Pembelajaran</h2>
                                </div>
                            </div>
                        </div>

                        {{-- Rich Text Content --}}
                        <div class="materi-prose text-slate-800 text-sm sm:text-base leading-relaxed">
                            @if(!empty($materiData['ringkasan']))
                                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 mb-6 text-sm text-indigo-950 font-medium">
                                    <strong>Ringkasan Inti:</strong> {{ $materiData['ringkasan'] }}
                                </div>
                            @endif

                            @if(!empty($materiData['content']))
                                {!! $materiData['content'] !!}
                            @elseif(!empty($module->content))
                                {!! $module->content !!}
                            @else
                                <p class="italic text-slate-400">Konten materi pembelajaran sedang disusun oleh guru pengampu.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- 8. Video Multimedia YouTube --}}
            @if($module->has_video)
                <div x-show="activePage === 'video'" x-cloak class="w-full space-y-6">
                    <div class="rounded-3xl bg-white border border-rose-200/90 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg font-bold">▶️</span>
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-600">Bagian 3 • Multimedia</span>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">Video Pembelajaran YouTube</h2>
                                </div>
                            </div>
                        </div>

                        @if(!empty($videosList) && count($videosList) > 0)
                            <div class="space-y-6">
                                @foreach($videosList as $vIndex => $vid)
                                    <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-950">
                                        <div class="p-3 bg-slate-900 text-white text-xs font-bold flex items-center justify-between border-b border-slate-800">
                                            <span>Video #{{ $vIndex + 1 }}: {{ $vid['title'] ?? 'Video Pembelajaran' }}</span>
                                        </div>
                                        @if(!empty($vid['youtube_id']))
                                            <div class="aspect-video w-full">
                                                <iframe src="https://www.youtube.com/embed/{{ $vid['youtube_id'] }}" class="w-full h-full border-0" allowfullscreen></iframe>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif(!empty($videoData['youtube_url']) || !empty($module->youtube_id))
                            @php
                                $ytId = $videoData['youtube_id'] ?? ($module->youtube_id ?? null);
                            @endphp
                            @if($ytId)
                                <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-950 aspect-video w-full">
                                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}" class="w-full h-full border-0" allowfullscreen></iframe>
                                </div>
                            @endif
                        @else
                            <p class="italic text-slate-400">Belum ada video YouTube yang ditautkan pada modul ini.</p>
                        @endif

                        {{-- Petunjuk Resume --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1">
                            <p class="font-bold text-slate-900">Petunjuk Tugas Siswa:</p>
                            <p>{{ $videoData['instructions'] ?? 'Siswa diarahkan untuk menyimak materi video di atas dan menuliskan rangkuman intisari pada kolom resume interaktif.' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 9. Simulator Embed Interaktif --}}
            @if($module->has_embed)
                <div x-show="activePage === 'embed'" x-cloak class="w-full space-y-6">
                    <div class="rounded-3xl bg-white border border-sky-200/90 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg font-bold">🎮</span>
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600">Bagian 4 • Praktik Interaktif</span>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ $embedData['title'] ?? 'Simulator Embed Interaktif' }}</h2>
                                </div>
                            </div>
                        </div>

                        @if(!empty($embedData['embed_url']) || !empty($module->embed_url) || !empty($embedData['embed_code']))
                            <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-900 aspect-video w-full shadow-inner">
                                @if(!empty($embedData['embed_url']))
                                    <iframe src="{{ $embedData['embed_url'] }}" class="w-full h-full border-0" allowfullscreen></iframe>
                                @elseif(!empty($module->embed_url))
                                    <iframe src="{{ $module->embed_url }}" class="w-full h-full border-0" allowfullscreen></iframe>
                                @else
                                    <div class="w-full h-full p-4 overflow-auto text-white text-xs">{!! $embedData['embed_code'] !!}</div>
                                @endif
                            </div>
                        @else
                            <p class="italic text-slate-400">Belum ada tautan simulator embed yang aktif.</p>
                        @endif

                        <div class="p-4 rounded-2xl bg-sky-50/60 border border-sky-100 text-xs text-sky-900">
                            <p class="font-bold mb-1">Petunjuk Praktik Simulator:</p>
                            <p class="text-sky-800/80">{{ $embedData['instructions'] ?? 'Siswa diarahkan untuk menjalankan simulator di atas dan mengunggah tangkapan layar (screenshot) hasil pengujian.' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 10. Job Sheet Praktikum --}}
            @if($module->has_job_sheet)
                <div x-show="activePage === 'job_sheet'" x-cloak class="w-full space-y-6">
                    <div class="rounded-3xl bg-white border border-amber-200/90 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg font-bold">📑</span>
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-600">Bagian 4 • Laboratorium</span>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ $jobSheet?->title ?? ($jobSheetData['title'] ?? 'Job Sheet Praktikum') }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-2">
                            <p class="font-bold text-slate-900">Instruksi Praktikum:</p>
                            <p>{{ $jobSheet?->instructions ?? ($jobSheetData['instructions'] ?? 'Silakan unduh dokumen panduan kerja praktikum laboratorium di bawah ini.') }}</p>
                        </div>

                        @if($jobSheet && $jobSheet->file_path)
                            <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold">📄</span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">{{ $jobSheet->file_name ?? 'Dokumen_Job_Sheet.pdf' }}</p>
                                        <p class="text-[11px] text-slate-500">Berkas Panduan Praktikum Guru</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($jobSheet->file_path) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-xs transition">
                                    <span>Unduh Dokumen PDF</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 11. Tugas LKPD --}}
            @if($module->has_lkpd)
                <div x-show="activePage === 'lkpd'" x-cloak class="w-full space-y-6">
                    <div class="rounded-3xl bg-white border border-cyan-200/90 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-2xl bg-cyan-100 text-cyan-700 flex items-center justify-center text-lg font-bold">📋</span>
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-cyan-600">Bagian 4 • Lembar Kerja</span>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ $lkpd?->title ?? ($lkpdData['title'] ?? 'Lembar Kerja Peserta Didik (LKPD)') }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-2">
                            <p class="font-bold text-slate-900">Petunjuk Tugas Proyek / Diskusi:</p>
                            <p>{{ $lkpd?->instructions ?? ($lkpdData['instructions'] ?? 'Kerjakan lembar kerja berikut sesuai arahan guru mata pelajaran.') }}</p>
                        </div>

                        @if($lkpd && $lkpd->file_path)
                            <div class="p-5 rounded-2xl bg-cyan-50/50 border border-cyan-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-xl bg-cyan-600 text-white flex items-center justify-center text-lg font-bold">📑</span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">{{ $lkpd->file_name ?? 'Dokumen_LKPD.pdf' }}</p>
                                        <p class="text-[11px] text-slate-500">Lembar Kerja Penugasan Siswa</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($lkpd->file_path) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold shadow-xs transition">
                                    <span>Unduh Dokumen PDF</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 12. Post-test (Evaluasi Akhir) --}}
            @if($module->has_post_test && $module->postTest)
                <div x-show="activePage === 'post_test'" x-cloak class="w-full space-y-6">
                    <div class="rounded-3xl bg-white border border-purple-200/90 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3.5">
                                <span class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl font-bold shrink-0">🏆</span>
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-purple-600">Bagian 5 • Asesmen Sumatif</span>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900">{{ $module->postTest->title ?? 'Post-test Evaluasi Akhir' }}</h2>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Target KKTP: {{ $module->postTest->kktp ?? 75 }} • Total: {{ $module->postTest->questions->count() }} Soal</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200 text-xs font-bold self-start sm:self-center">
                                Mode Inspeksi Soal & Kunci Jawaban
                            </span>
                        </div>

                        <div class="p-4 bg-purple-50/60 rounded-2xl border border-purple-100 text-xs text-purple-900 flex items-center gap-2">
                            <span>🔍</span>
                            <span><strong>Keterangan Admin:</strong> Kunci jawaban resmi guru ditandai dengan label hijau bercentang pada opsi jawaban.</span>
                        </div>

                        {{-- Question list --}}
                        <div class="space-y-6">
                            @foreach($module->postTest->questions as $idx => $q)
                                <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                                    <div class="flex items-start gap-3">
                                        <span class="w-7 h-7 rounded-xl bg-purple-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                            {{ $idx + 1 }}
                                        </span>
                                        <div class="text-sm font-bold text-slate-900 leading-relaxed flex-1">
                                            {{ $q->question_text }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-2.5 pl-10">
                                        @foreach(['A', 'B', 'C', 'D', 'E'] as $optKey)
                                            @if(!empty($q->options[$optKey]))
                                                @php $isCorrect = strtoupper($q->correct_answer ?? '') === $optKey; @endphp
                                                <div class="flex items-center justify-between p-3 rounded-xl border transition {{ $isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-950 font-bold' : 'bg-white border-slate-200 text-slate-700' }}">
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-xs font-bold {{ $isCorrect ? 'text-emerald-700' : 'text-slate-500' }} w-5">{{ $optKey }}.</span>
                                                        <span class="text-xs sm:text-sm">{{ $q->options[$optKey] }}</span>
                                                    </div>
                                                    @if($isCorrect)
                                                        <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-600 text-white shadow-xs">
                                                            ✓ Kunci Jawaban
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- 13. Daftar Pustaka --}}
            <div x-show="activePage === 'daftar_pustaka'" x-cloak class="w-full space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">📚</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian 5 • Rujukan</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Daftar Pustaka & Literatur</h2>
                            </div>
                        </div>
                    </div>

                    <div class="prose prose-slate text-sm text-slate-700 bg-slate-50 p-5 rounded-2xl border border-slate-200 leading-relaxed">
                        @php
                            $pustakaRaw = $informasiUmum['daftar_pustaka']['daftar_pustaka'] ?? ($informasiUmum['daftar_pustaka'] ?? []);
                        @endphp
                        @if(is_array($pustakaRaw) && !empty($pustakaRaw))
                            <ol class="list-decimal pl-5 space-y-2">
                                @foreach($pustakaRaw as $pItem)
                                    @php
                                        $pj = is_array($pItem) ? ($pItem['judul'] ?? '') : $pItem;
                                        $pp = is_array($pItem) ? ($pItem['penulis'] ?? '') : '';
                                        $pt = is_array($pItem) ? ($pItem['tahun'] ?? '') : '';
                                        $pu = is_array($pItem) ? ($pItem['tautan'] ?? '') : '';
                                    @endphp
                                    <li>
                                        @if($pp)<strong>{{ $pp }}</strong>. @endif
                                        @if($pt)({{ $pt }}). @endif
                                        <em>{{ $pj }}</em>.
                                        @if($pu)
                                            <a href="{{ $pu }}" target="_blank" rel="noopener" class="text-indigo-600 underline text-xs break-all block mt-0.5">{{ $pu }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        @elseif(is_string($pustakaRaw) && !empty(trim($pustakaRaw)))
                            {!! nl2br(e($pustakaRaw)) !!}
                        @else
                            <p class="italic text-slate-400">Daftar pustaka belum ditambahkan.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 14. Tab Khusus Admin: Riwayat Kloning Guru --}}
            <div x-show="activePage === 'clones_history'" x-cloak class="w-full space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">👥</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Supervisi Admin • Adopsi Modul</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Riwayat Guru yang Mengkloning ({{ $module->clone_count }})</h2>
                            </div>
                        </div>
                    </div>

                    @if($module->clones && $module->clones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                        <th class="py-3 px-4">Guru Pendidik</th>
                                        <th class="py-3 px-4">Rombel Kelas Tujuan</th>
                                        <th class="py-3 px-4">Status Modul Klon</th>
                                        <th class="py-3 px-4">Waktu Kloning</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($module->clones as $clone)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="py-3.5 px-4">
                                                <div class="font-bold text-slate-800">{{ $clone->teacher->name ?? 'Guru' }}</div>
                                                <div class="text-[11px] text-slate-400">NIP: {{ $clone->teacher->identity_number ?? '-' }}</div>
                                            </td>
                                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                                {{ $clone->schoolClass?->grade }} {{ $clone->schoolClass?->major_name }}
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $clone->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                    {{ ucfirst($clone->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-slate-400 font-mono text-[11px]">
                                                {{ $clone->created_at->format('d M Y, H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 bg-slate-50 rounded-2xl border border-slate-200 text-center text-slate-500 text-xs">
                            <p class="font-bold text-slate-700 mb-1">Belum Ada Guru yang Mengkloning Modul Ini</p>
                            <p>Modul ini siap diadopsi oleh rekan guru lain di sekolah melalui Library Modul Bersama.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── BOTTOM SEQUENTIAL NAVIGATION BAR ── --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-4 sm:p-5 flex items-center justify-between gap-4">
                {{-- Prev Button --}}
                <div>
                    <template x-if="prevPage">
                        <button type="button"
                                @click="goToPage(prevPage.id)"
                                class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            <span class="hidden sm:inline">Sebelumnya:</span>
                            <span class="truncate max-w-[120px] sm:max-w-[180px]" x-text="prevPage.title"></span>
                        </button>
                    </template>
                </div>

                {{-- Current Position --}}
                <div class="text-center text-xs text-slate-400 font-bold">
                    <span x-text="(currentIndex + 1)"></span> dari <span x-text="pages.length"></span> Langkah
                </div>

                {{-- Next Button --}}
                <div>
                    <template x-if="nextPage">
                        <button type="button"
                                @click="goToPage(nextPage.id)"
                                class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition cursor-pointer">
                            <span class="hidden sm:inline">Selanjutnya:</span>
                            <span class="truncate max-w-[120px] sm:max-w-[180px]" x-text="nextPage.title"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </template>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
