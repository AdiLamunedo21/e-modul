@extends('layouts.student.dashboardstudent')

@section('title', $module->title . ' — Portal Belajar Siswa')

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
    .materi-prose blockquote { border-left: 4px solid #0284c7; background: #f0f9ff; padding: .75rem 1rem; margin: 1rem 0; border-radius: 0 .5rem .5rem 0; font-style: italic; color: #0369a1; }
    .materi-prose hr { border: none; border-top: 2px solid #e2e8f0; margin: 1.5rem 0; }
    .materi-prose a { color: #0284c7; text-decoration: underline; }

    /* Active styling in learning syllabus */
    .sidebar-item-active {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px -2px rgba(79, 70, 229, 0.35);
    }
    .sidebar-item-active * {
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')

@php
    // Definisikan seluruh halaman aktivitas yang aktif secara dinamis
    $pagesList = [];

    // Bagian 1: Bagian Awal
    if ($module->isInfoComponentActive('kata_pengantar')) {
        $pagesList[] = ['id' => 'kata_pengantar', 'sec' => 1, 'sec_name' => '1. Bagian Awal', 'title' => 'Kata Pengantar', 'icon' => '✏️', 'badge' => 'Pengantar', 'desc' => 'Prakata dan sambutan guru pengampu', 'status' => 'done'];
    }
    if ($module->isInfoComponentActive('petunjuk_penggunaan')) {
        $pagesList[] = ['id' => 'petunjuk_penggunaan', 'sec' => 1, 'sec_name' => '1. Bagian Awal', 'title' => 'Petunjuk Penggunaan', 'icon' => '💡', 'badge' => 'Panduan', 'desc' => 'Panduan belajar siswa & arahan guru', 'status' => 'done'];
    }

    // Bagian 2: Pendahuluan
    if ($module->isInfoComponentActive('tujuan_pembelajaran')) {
        $pagesList[] = ['id' => 'tujuan_pembelajaran', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Tujuan & Capaian', 'icon' => '🎯', 'badge' => 'Capaian', 'desc' => 'Target kompetensi pembelajaran (CP & TP)', 'status' => 'done'];
    }
    if ($module->isInfoComponentActive('peta_konsep') && (!empty($informasiUmum['peta_konsep']['peta_konsep_image_path']) || !empty($informasiUmum['peta_konsep']['peta_konsep_text']))) {
        $pagesList[] = ['id' => 'peta_konsep', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Peta Konsep Materi', 'icon' => '🗺️', 'badge' => 'Alur Materi', 'desc' => 'Diagram hierarki konsep materi kejuruan', 'status' => 'done'];
    }
    if ($module->isInfoComponentActive('glosarium') && !empty($informasiUmum['glosarium']['glosarium'])) {
        $pagesList[] = ['id' => 'glosarium', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Glosarium Istilah', 'icon' => '📖', 'badge' => 'Kamus', 'desc' => 'Kamus istilah teknis & konsep penting', 'status' => 'done'];
    }
    if ($module->has_pre_test && $module->preTest) {
        $preStatus = ($studentResult && $studentResult->pre_test_score !== null) ? 'completed' : 'todo';
        $pagesList[] = ['id' => 'pre_test', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Pre-test (Diagnostik)', 'icon' => '⚡', 'badge' => 'Kuis Awal', 'desc' => 'Tes diagnostik awal kemampuan siswa', 'status' => $preStatus];
    }

    // Bagian 3: Kegiatan Belajar
    if ($module->has_materi) {
        $pagesList[] = ['id' => 'materi', 'sec' => 3, 'sec_name' => '3. Kegiatan Belajar', 'title' => 'Uraian Materi & PPT', 'icon' => '📖', 'badge' => 'Materi Inti', 'desc' => 'Uraian teori mendalam & slide presentasi', 'status' => 'done'];
    }
    if ($module->has_video) {
        $vidStatus = $videoSummary ? ($videoSummary->manual_score !== null ? 'completed' : 'pending') : 'todo';
        $pagesList[] = ['id' => 'video', 'sec' => 3, 'sec_name' => '3. Kegiatan Belajar', 'title' => 'Video & Resume YouTube', 'icon' => '▶️', 'badge' => 'Multimedia', 'desc' => 'Video interaktif & penulisan resume intisari', 'status' => $vidStatus];
    }

    // Bagian 4: Evaluasi & Praktik
    if ($module->has_embed) {
        $embStatus = $embedSubmission ? ($embedSubmission->manual_score !== null ? 'completed' : 'pending') : 'todo';
        $pagesList[] = ['id' => 'embed', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Simulator Embed Interaktif', 'icon' => '🎮', 'badge' => 'Praktik', 'desc' => 'Eksplorasi simulator & upload screenshot', 'status' => $embStatus];
    }
    if ($module->has_job_sheet) {
        $jsStatus = $jobSheetSubmission ? ($jobSheetSubmission->manual_score !== null ? 'completed' : 'pending') : 'todo';
        $pagesList[] = ['id' => 'job_sheet', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Job Sheet Praktikum', 'icon' => '📑', 'badge' => 'Laboratorium', 'desc' => 'Panduan instruksi kerja & laporan PDF', 'status' => $jsStatus];
    }
    if ($module->has_lkpd) {
        $lkpdStatus = $lkpdSubmission ? ($lkpdSubmission->manual_score !== null ? 'completed' : 'pending') : 'todo';
        $pagesList[] = ['id' => 'lkpd', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Tugas LKPD Siswa', 'icon' => '📋', 'badge' => 'Penugasan', 'desc' => 'Lembar kerja peserta didik berbasis proyek', 'status' => $lkpdStatus];
    }

    // Bagian 5: Bagian Akhir
    if ($module->has_post_test && $module->postTest) {
        $postStatus = ($studentResult && $studentResult->post_test_score !== null) ? 'completed' : 'todo';
        $pagesList[] = ['id' => 'post_test', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Post-test (Evaluasi Akhir)', 'icon' => '🏆', 'badge' => 'Uji Akhir', 'desc' => 'Evaluasi ketuntasan belajar akhir modul', 'status' => $postStatus];
    }
    if ($module->isInfoComponentActive('daftar_pustaka') && !empty($informasiUmum['daftar_pustaka']['daftar_pustaka'])) {
        $pagesList[] = ['id' => 'daftar_pustaka', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Daftar Pustaka', 'icon' => '📚', 'badge' => 'Rujukan', 'desc' => 'Daftar referensi buku dan sumber materi', 'status' => 'done'];
    }
    $pagesList[] = ['id' => 'rekap_nilai', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Rekapitulasi Nilai', 'icon' => '📊', 'badge' => 'Transparansi', 'desc' => 'Matriks transparansi skor evaluasi siswa', 'status' => 'done'];

    // Menentukan halaman awal & mode tampilan awal
    $hasPageParam = request()->has('page') || session('success') || session('error');
    $initialViewMode = $hasPageParam ? 'learn' : 'overview';
    $initialPage = request()->query('page', $pagesList[0]['id'] ?? 'kata_pengantar');
    
    // Cari tugas pertama yang belum selesai untuk tombol "Lanjutkan Belajar"
    $firstPendingPage = $pagesList[0]['id'] ?? 'kata_pengantar';
    foreach ($pagesList as $p) {
        if ($p['status'] === 'todo') {
            $firstPendingPage = $p['id'];
            break;
        }
    }
@endphp

<div class="max-w-7xl mx-auto space-y-6"
     x-data="{
        viewMode: '{{ $initialViewMode }}',
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
        startLearning(targetPageId) {
            this.activePage = targetPageId || '{{ $firstPendingPage }}';
            const target = this.pages.find(p => p.id === this.activePage);
            if (target) {
                this.openSections[target.sec] = true;
            }
            this.viewMode = 'learn';
            this.mobileDrawerOpen = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goToPage(pageId) {
            this.activePage = pageId;
            const target = this.pages.find(p => p.id === pageId);
            if (target) {
                this.openSections[target.sec] = true;
            }
            this.viewMode = 'learn';
            this.mobileDrawerOpen = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        toggleSection(secNum) {
            this.openSections[secNum] = !this.openSections[secNum];
        }
     }">

    {{-- ═══ 1. STICKY TOP HEADER & BREADCRUMB ═══ --}}
    <div class="rounded-3xl bg-white border border-slate-200/90 p-5 sm:p-7 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            {{-- Title & Badges --}}
            <div class="space-y-2 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('student.modules.subject', $module->subject_id) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        <span>←</span>
                        <span>{{ $module->subject->name ?? 'Mata Pelajaran' }}</span>
                    </a>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold {{ $module->subject?->badgeClasses() ?? 'bg-blue-100 text-blue-800' }}">
                        <span>{{ $module->subject->code ?? 'MAPEL' }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">
                        <span>{{ $module->schoolClass->full_name ?? 'Kelas' }}</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-xl text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                        ✓ Terbit & Aktif
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ $module->title }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium flex flex-wrap items-center gap-2">
                    <span>👨‍🏫 Guru Pengampu: <strong>{{ $module->teacher->name ?? 'Guru' }}</strong></span>
                    <span>•</span>
                    <span>Terakhir diperbarui: {{ $module->updated_at->translatedFormat('d M Y') }}</span>
                </p>
            </div>

            {{-- Progress & Mode Toggle Actions --}}
            <div class="flex flex-wrap items-center gap-3 bg-slate-50 border border-slate-200/80 p-3 sm:p-4 rounded-2xl shrink-0">
                {{-- Progres Belajar --}}
                <div class="min-w-[140px]">
                    <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                        <span class="text-slate-500 uppercase tracking-wider text-[10px]">Aktivitas Selesai</span>
                        <span class="{{ $progressPercent >= 100 ? 'text-emerald-600' : 'text-indigo-600' }}">
                            {{ $completedTasks }}/{{ $totalActive }} ({{ $progressPercent }}%)
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-indigo-500 to-teal-500' }}"
                             style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>

                {{-- Nilai Akhir Sumatif --}}
                @if($studentResult)
                    <div class="pl-3.5 border-l border-slate-200 text-center min-w-[80px]">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nilai Akhir</p>
                        <p class="text-xl font-black {{ $studentResult->summative_score >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $studentResult->summative_score }}
                        </p>
                    </div>
                @endif

                {{-- Switch View Mode Buttons --}}
                <div class="flex items-center gap-1.5 pl-2 border-l border-slate-200">
                    <button type="button"
                            @click="viewMode = 'overview'"
                            :class="viewMode === 'overview' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200 font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                            class="px-3 py-2 rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                        <span>📋</span>
                        <span>Detail Modul</span>
                    </button>
                    <button type="button"
                            @click="startLearning('{{ $firstPendingPage }}')"
                            :class="viewMode === 'learn' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20 font-bold' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold'"
                            class="px-3.5 py-2 rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                        <span>🚀</span>
                        <span>{{ $progressPercent > 0 ? 'Lanjut Belajar' : 'Mulai Belajar' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-800 flex items-center gap-3">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-sm font-semibold text-rose-800 flex items-center gap-3">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ VIEW 1: TAMPILAN AWAL DETAIL MODUL SISWA (OVERVIEW 5 BAGIAN) ═══ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="viewMode === 'overview'" x-cloak class="space-y-8">

        {{-- ── CTA Big Hero Banner ── --}}
        <div class="rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 text-white p-6 sm:p-8 shadow-xl relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <span class="px-3 py-1 rounded-full bg-white/15 text-indigo-200 text-xs font-extrabold uppercase tracking-wider backdrop-blur-xs">
                        Panduan Belajar Siswa
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        {{ $progressPercent >= 100 ? 'Selamat! Anda Telah Menuntaskan Modul Ini' : ($progressPercent > 0 ? 'Lanjutkan Pembelajaran Anda' : 'Siap Memulai Pembelajaran Interaktif?') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-indigo-100 leading-relaxed font-normal">
                        Pelajari materi secara bertahap mulai dari <strong>Bagian Awal</strong>, kerjakan <strong>Pre-test</strong>, pelajari <strong>Materi & Video</strong>, tuntaskan <strong>Praktik Laboratorium</strong>, dan selesaikan <strong>Post-test</strong> untuk mengukur capaian kompetensi Anda.
                    </p>
                </div>

                <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button type="button"
                            @click="startLearning('{{ $firstPendingPage }}')"
                            class="px-8 py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                        <span>{{ $progressPercent >= 100 ? 'Buka Kembali Seluruh Materi' : ($progressPercent > 0 ? 'Lanjutkan Belajar Sekarang' : 'Mulai Belajar Sekarang') }}</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             BARIS 1: BAGIAN 1 & BAGIAN 2 SEJAJAR (INFORMASI UMUM & ORIENTASI)
             1. Bagian Awal  <─── SEJAJAR ───>  2. Pendahuluan
             ══════════════════════════════════════════════════════════════════════ --}}
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider bg-indigo-50 text-indigo-900 border border-indigo-200/80 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    Tahap 1: Bagian Awal & Pendahuluan (Orientasi Belajar)
                </span>
                <div class="h-px bg-slate-200 flex-1"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

                {{-- ── KARTU 1: BAGIAN AWAL ── --}}
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-indigo-600/20">
                                    1
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Bagian Awal</h3>
                                    <p class="text-xs text-slate-500">Kata Pengantar & Petunjuk Pembelajaran</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                Pengantar
                            </span>
                        </div>

                        <div class="space-y-3 mb-6">
                            {{-- Item: Kata Pengantar --}}
                            @if($module->isInfoComponentActive('kata_pengantar'))
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-bold">✏️</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Kata Pengantar</h4>
                                            <p class="text-[11px] text-slate-500">Sambutan dan motivasi dari guru pengampu</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="goToPage('kata_pengantar')"
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                        Baca →
                                    </button>
                                </div>
                            @endif

                            {{-- Item: Petunjuk Penggunaan --}}
                            @if($module->isInfoComponentActive('petunjuk_penggunaan'))
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-bold">💡</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Petunjuk Penggunaan</h4>
                                            <p class="text-[11px] text-slate-500">Panduan langkah belajar mandiri peserta didik</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="goToPage('petunjuk_penggunaan')"
                                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                        Lihat →
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="button"
                                @click="goToPage('kata_pengantar')"
                                class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2">
                            <span>Mulai dari Bagian Awal</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>

                {{-- ── KARTU 2: PENDAHULUAN ── --}}
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-teal-600/20">
                                    2
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Pendahuluan</h3>
                                    <p class="text-xs text-slate-500">Capaian, Konsep & Pre-test Diagnostik</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
                                Orientasi
                            </span>
                        </div>

                        <div class="space-y-3 mb-6">
                            {{-- Item: Tujuan Pembelajaran --}}
                            @if($module->isInfoComponentActive('tujuan_pembelajaran'))
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-bold">🎯</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Tujuan & Capaian</h4>
                                            <p class="text-[11px] text-slate-500">Rumusan kompetensi CP & TP modul</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="goToPage('tujuan_pembelajaran')"
                                            class="px-3 py-1.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-bold transition">
                                        Lihat →
                                    </button>
                                </div>
                            @endif

                            {{-- Item: Peta Konsep --}}
                            @if($module->isInfoComponentActive('peta_konsep') && (!empty($informasiUmum['peta_konsep']['peta_konsep_image_path']) || !empty($informasiUmum['peta_konsep']['peta_konsep_text'])))
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-bold">🗺️</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Peta Konsep Materi</h4>
                                            <p class="text-[11px] text-slate-500">Alur keterkaitan materi kejuruan</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="goToPage('peta_konsep')"
                                            class="px-3 py-1.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-bold transition">
                                        Buka →
                                    </button>
                                </div>
                            @endif

                            {{-- Item: Glosarium --}}
                            @if($module->isInfoComponentActive('glosarium') && !empty($informasiUmum['glosarium']['glosarium']))
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-bold">📖</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Glosarium Istilah</h4>
                                            <p class="text-[11px] text-slate-500">Kamus istilah teknis & konsep penting</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="goToPage('glosarium')"
                                            class="px-3 py-1.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-bold transition">
                                        Buka →
                                    </button>
                                </div>
                            @endif

                            {{-- Item: Pre-test --}}
                            @if($module->has_pre_test && $module->preTest)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-teal-50/60 border border-teal-200">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center text-sm font-bold">⚡</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-teal-950">Pre-test (Diagnostik)</h4>
                                            <p class="text-[11px] text-teal-700">Kuis diagnostik sebelum membaca materi</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($studentResult && $studentResult->pre_test_score !== null)
                                            <span class="text-xs font-extrabold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-lg">
                                                Skor: {{ $studentResult->pre_test_score }}
                                            </span>
                                        @else
                                            <button type="button"
                                                    @click="goToPage('pre_test')"
                                                    class="px-3.5 py-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition">
                                                Kerjakan Kuis
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="button"
                                @click="goToPage('tujuan_pembelajaran')"
                                class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2">
                            <span>Pelajari Pendahuluan</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             BARIS 2: BAGIAN 3 & BAGIAN 4 SEJAJAR (MATERI, VIDEO, EMBED & TUGAS)
             3. Kegiatan Belajar  <─── SEJAJAR ───>  4. Evaluasi & Praktik
             ══════════════════════════════════════════════════════════════════════ --}}
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider bg-blue-50 text-blue-900 border border-blue-200/80 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Tahap 2: Kegiatan Belajar & Praktik Evaluasi
                </span>
                <div class="h-px bg-slate-200 flex-1"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

                {{-- ── KARTU 3: KEGIATAN BELAJAR ── --}}
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-blue-600/20">
                                    3
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Kegiatan Belajar</h3>
                                    <p class="text-xs text-slate-500">Materi Inti & Multi-Video YouTube</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                Isi Materi
                            </span>
                        </div>

                        <div class="space-y-3 mb-6">
                            {{-- Item: Uraian Materi --}}
                            @if($module->has_materi)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold">📖</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Uraian Materi & PPT</h4>
                                            <p class="text-[11px] text-slate-500">Materi teori komprehensif & slide presentasi</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="goToPage('materi')"
                                            class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                                        Pelajari →
                                    </button>
                                </div>
                            @endif

                            {{-- Item: Multi-Video YouTube --}}
                            @if($module->has_video)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center text-sm font-bold">▶️</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Video & Resume YouTube</h4>
                                            <p class="text-[11px] text-slate-500">Tonton video pembelajaran & kirim resume</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($videoSummary)
                                            <span class="text-[11px] font-bold px-2 py-1 rounded-lg {{ $videoSummary->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                {{ $videoSummary->manual_score !== null ? 'Nilai: ' . $videoSummary->manual_score : 'Terkirim' }}
                                            </span>
                                        @else
                                            <button type="button"
                                                    @click="goToPage('video')"
                                                    class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold transition">
                                                Tonton →
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="button"
                                @click="goToPage('materi')"
                                class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2">
                            <span>Buka Kegiatan Belajar</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>

                {{-- ── KARTU 4: EVALUASI & PRAKTIK ── --}}
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-violet-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-violet-600/20">
                                    4
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Evaluasi & Praktik</h3>
                                    <p class="text-xs text-slate-500">Simulator Interaktif, Job Sheet & LKPD</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 border border-violet-100">
                                Praktik & Tugas
                            </span>
                        </div>

                        <div class="space-y-3 mb-6">
                            {{-- Item: Simulator Embed --}}
                            @if($module->has_embed)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-sm font-bold">🎮</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Simulator Embed</h4>
                                            <p class="text-[11px] text-slate-500">Praktik langsung & unggah bukti screenshot</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($embedSubmission)
                                            <span class="text-[11px] font-bold px-2 py-1 rounded-lg {{ $embedSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                {{ $embedSubmission->manual_score !== null ? 'Nilai: ' . $embedSubmission->manual_score : 'Terkirim' }}
                                            </span>
                                        @else
                                            <button type="button"
                                                    @click="goToPage('embed')"
                                                    class="px-3 py-1.5 rounded-xl bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-bold transition">
                                                Praktik →
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Item: Job Sheet --}}
                            @if($module->has_job_sheet)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-sm font-bold">📑</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Job Sheet Praktikum</h4>
                                            <p class="text-[11px] text-slate-500">Unduh panduan & unggah laporan PDF</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($jobSheetSubmission)
                                            <span class="text-[11px] font-bold px-2 py-1 rounded-lg {{ $jobSheetSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                {{ $jobSheetSubmission->manual_score !== null ? 'Nilai: ' . $jobSheetSubmission->manual_score : 'Terkirim' }}
                                            </span>
                                        @else
                                            <button type="button"
                                                    @click="goToPage('job_sheet')"
                                                    class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition">
                                                Tugas →
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Item: LKPD --}}
                            @if($module->has_lkpd)
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-sm font-bold">📋</span>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">Tugas LKPD</h4>
                                            <p class="text-[11px] text-slate-500">Lembar kerja peserta didik & umpan balik</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($lkpdSubmission)
                                            <span class="text-[11px] font-bold px-2 py-1 rounded-lg {{ $lkpdSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                {{ $lkpdSubmission->manual_score !== null ? 'Nilai: ' . $lkpdSubmission->manual_score : 'Terkirim' }}
                                            </span>
                                        @else
                                            <button type="button"
                                                    @click="goToPage('lkpd')"
                                                    class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold transition">
                                                Tugas →
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="button"
                                @click="goToPage('{{ $module->has_embed ? 'embed' : ($module->has_job_sheet ? 'job_sheet' : 'lkpd') }}')"
                                class="w-full py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2">
                            <span>Buka Evaluasi & Praktik</span>
                            <span>→</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════════
             BARIS 3: BAGIAN 5 (POST-TEST, DAFTAR PUSTAKA & REKAP NILAI)
             ══════════════════════════════════════════════════════════════════════ --}}
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider bg-rose-50 text-rose-900 border border-rose-200/80 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                    Tahap 3: Bagian Akhir & Rekapitulasi Penilaian
                </span>
                <div class="h-px bg-slate-200 flex-1"></div>
            </div>

            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-6 sm:p-8 hover:shadow-md transition">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black text-sm shadow-md shadow-rose-600/20">
                            5
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Bagian Akhir & Evaluasi Sumatif</h3>
                            <p class="text-xs text-slate-500">Post-test Penutup, Daftar Rujukan, dan Rekap Nilai Siswa</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100">
                        Evaluasi Akhir
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    {{-- Post-test --}}
                    @if($module->has_post_test && $module->postTest)
                        <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200 flex flex-col justify-between">
                            <div class="space-y-1.5 mb-3">
                                <span class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center text-sm font-bold">🏆</span>
                                <h4 class="text-xs font-bold text-rose-950">Post-test Evaluasi Akhir</h4>
                                <p class="text-[11px] text-rose-800">Uji pemahaman komprehensif setelah menuntaskan seluruh materi.</p>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-rose-200/60">
                                @if($studentResult && $studentResult->post_test_score !== null)
                                    <span class="text-xs font-black text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md">
                                        Skor: {{ $studentResult->post_test_score }}
                                    </span>
                                @else
                                    <button type="button"
                                            @click="goToPage('post_test')"
                                            class="w-full py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition">
                                        Kerjakan Post-test
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Daftar Pustaka --}}
                    @if($module->isInfoComponentActive('daftar_pustaka'))
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                            <div class="space-y-1.5 mb-3">
                                <span class="w-8 h-8 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center text-sm font-bold">📚</span>
                                <h4 class="text-xs font-bold text-slate-900">Daftar Pustaka</h4>
                                <p class="text-[11px] text-slate-500">Rujukan buku referensi, standar kejuruan, dan modul pembelajaran.</p>
                            </div>
                            <button type="button"
                                    @click="goToPage('daftar_pustaka')"
                                    class="w-full py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                Lihat Rujukan →
                            </button>
                        </div>
                    @endif

                    {{-- Rekap Nilai --}}
                    <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 flex flex-col justify-between">
                        <div class="space-y-1.5 mb-3">
                            <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">📊</span>
                            <h4 class="text-xs font-bold text-emerald-950">Rekapitulasi Nilai</h4>
                            <p class="text-[11px] text-emerald-800">Transparansi skor perolehan tugas mandiri & kuis evaluasi.</p>
                        </div>
                        <button type="button"
                                @click="goToPage('rekap_nilai')"
                                class="w-full py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                            Lihat Rekap Nilai →
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ VIEW 2: MODE BELAJAR INTERAKTIF (ACCORDION SILABUS + WORKSPACE) ═══ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="viewMode === 'learn'" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ─────────────────────────────────────────────────────────────────── --}}
        {{-- ── LEFT COLUMN: ACCORDION SILABUS MODUL (4 Cols on Desktop) ─────── --}}
        {{-- ─────────────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-4 space-y-3"
             :class="mobileDrawerOpen ? 'block' : 'hidden lg:block'">

            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-4 sm:p-5 sticky top-6 space-y-4">
                {{-- Header Silabus --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold">📑</span>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Alur Belajar Modul</h3>
                            <p class="text-[11px] text-slate-400">Pilih materi untuk mulai belajar</p>
                        </div>
                    </div>
                    <button type="button"
                            @click="viewMode = 'overview'"
                            class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2 py-1 rounded-lg transition">
                        Detail Modul 📋
                    </button>
                </div>

                {{-- Accordion Group 1 to 5 --}}
                <div class="space-y-3">

                    {{-- ── ACCORDION 1: BAGIAN AWAL ── --}}
                    @php $sec1Pages = array_filter($pagesList, fn($p) => $p['sec'] === 1); @endphp
                    @if(!empty($sec1Pages))
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                            <button type="button"
                                    @click="toggleSection(1)"
                                    class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-indigo-100 text-indigo-700 text-[10px] font-black flex items-center justify-center">1</span>
                                    <span>Bagian Awal</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="openSections[1] ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="openSections[1]" x-collapse class="p-2 pt-0 space-y-1.5">
                                @foreach($sec1Pages as $page)
                                    <button type="button"
                                            @click="goToPage('{{ $page['id'] }}')"
                                            :class="activePage === '{{ $page['id'] }}' ? 'sidebar-item-active' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'"
                                            class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2 cursor-pointer">
                                        <span class="flex items-center gap-2 truncate">
                                            <span>{{ $page['icon'] }}</span>
                                            <span class="truncate">{{ $page['title'] }}</span>
                                        </span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold opacity-80"
                                              :class="activePage === '{{ $page['id'] }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                                            {{ $page['badge'] }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── ACCORDION 2: PENDAHULUAN ── --}}
                    @php $sec2Pages = array_filter($pagesList, fn($p) => $p['sec'] === 2); @endphp
                    @if(!empty($sec2Pages))
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                            <button type="button"
                                    @click="toggleSection(2)"
                                    class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-teal-100 text-teal-700 text-[10px] font-black flex items-center justify-center">2</span>
                                    <span>Pendahuluan</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="openSections[2] ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="openSections[2]" x-collapse class="p-2 pt-0 space-y-1.5">
                                @foreach($sec2Pages as $page)
                                    <button type="button"
                                            @click="goToPage('{{ $page['id'] }}')"
                                            :class="activePage === '{{ $page['id'] }}' ? 'sidebar-item-active' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'"
                                            class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2 cursor-pointer">
                                        <span class="flex items-center gap-2 truncate">
                                            <span>{{ $page['icon'] }}</span>
                                            <span class="truncate">{{ $page['title'] }}</span>
                                        </span>
                                        @if($page['id'] === 'pre_test')
                                            @if($studentResult && $studentResult->pre_test_score !== null)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    {{ $studentResult->pre_test_score }}
                                                </span>
                                            @else
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                                    Kuis
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold opacity-80"
                                                  :class="activePage === '{{ $page['id'] }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                                                {{ $page['badge'] }}
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── ACCORDION 3: KEGIATAN BELAJAR ── --}}
                    @php $sec3Pages = array_filter($pagesList, fn($p) => $p['sec'] === 3); @endphp
                    @if(!empty($sec3Pages))
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                            <button type="button"
                                    @click="toggleSection(3)"
                                    class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-black flex items-center justify-center">3</span>
                                    <span>Kegiatan Belajar</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="openSections[3] ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="openSections[3]" x-collapse class="p-2 pt-0 space-y-1.5">
                                @foreach($sec3Pages as $page)
                                    <button type="button"
                                            @click="goToPage('{{ $page['id'] }}')"
                                            :class="activePage === '{{ $page['id'] }}' ? 'sidebar-item-active' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'"
                                            class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2 cursor-pointer">
                                        <span class="flex items-center gap-2 truncate">
                                            <span>{{ $page['icon'] }}</span>
                                            <span class="truncate">{{ $page['title'] }}</span>
                                        </span>
                                        @if($page['id'] === 'video')
                                            @if($videoSummary)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $videoSummary->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                    {{ $videoSummary->manual_score !== null ? 'Nilai ' . $videoSummary->manual_score : 'Terkirim' }}
                                                </span>
                                            @else
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold bg-slate-100 text-slate-600">
                                                    Video
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold opacity-80"
                                                  :class="activePage === '{{ $page['id'] }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                                                {{ $page['badge'] }}
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── ACCORDION 4: EVALUASI & PRAKTIK ── --}}
                    @php $sec4Pages = array_filter($pagesList, fn($p) => $p['sec'] === 4); @endphp
                    @if(!empty($sec4Pages))
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                            <button type="button"
                                    @click="toggleSection(4)"
                                    class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-violet-100 text-violet-700 text-[10px] font-black flex items-center justify-center">4</span>
                                    <span>Evaluasi & Praktik</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="openSections[4] ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="openSections[4]" x-collapse class="p-2 pt-0 space-y-1.5">
                                @foreach($sec4Pages as $page)
                                    <button type="button"
                                            @click="goToPage('{{ $page['id'] }}')"
                                            :class="activePage === '{{ $page['id'] }}' ? 'sidebar-item-active' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'"
                                            class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2 cursor-pointer">
                                        <span class="flex items-center gap-2 truncate">
                                            <span>{{ $page['icon'] }}</span>
                                            <span class="truncate">{{ $page['title'] }}</span>
                                        </span>

                                        @if($page['id'] === 'embed')
                                            @if($embedSubmission)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $embedSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                    {{ $embedSubmission->manual_score !== null ? 'Nilai ' . $embedSubmission->manual_score : 'Terkirim' }}
                                                </span>
                                            @else
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold bg-slate-100 text-slate-600">Praktik</span>
                                            @endif
                                        @elseif($page['id'] === 'job_sheet')
                                            @if($jobSheetSubmission)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $jobSheetSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                    {{ $jobSheetSubmission->manual_score !== null ? 'Nilai ' . $jobSheetSubmission->manual_score : 'Terkirim' }}
                                                </span>
                                            @else
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold bg-slate-100 text-slate-600">PDF</span>
                                            @endif
                                        @elseif($page['id'] === 'lkpd')
                                            @if($lkpdSubmission)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $lkpdSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                                                    {{ $lkpdSubmission->manual_score !== null ? 'Nilai ' . $lkpdSubmission->manual_score : 'Terkirim' }}
                                                </span>
                                            @else
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold bg-slate-100 text-slate-600">LKPD</span>
                                            @endif
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── ACCORDION 5: BAGIAN AKHIR ── --}}
                    @php $sec5Pages = array_filter($pagesList, fn($p) => $p['sec'] === 5); @endphp
                    @if(!empty($sec5Pages))
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                            <button type="button"
                                    @click="toggleSection(5)"
                                    class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition">
                                <span class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-rose-100 text-rose-700 text-[10px] font-black flex items-center justify-center">5</span>
                                    <span>Bagian Akhir</span>
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="openSections[5] ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="openSections[5]" x-collapse class="p-2 pt-0 space-y-1.5">
                                @foreach($sec5Pages as $page)
                                    <button type="button"
                                            @click="goToPage('{{ $page['id'] }}')"
                                            :class="activePage === '{{ $page['id'] }}' ? 'sidebar-item-active' : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60'"
                                            class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2 cursor-pointer">
                                        <span class="flex items-center gap-2 truncate">
                                            <span>{{ $page['icon'] }}</span>
                                            <span class="truncate">{{ $page['title'] }}</span>
                                        </span>

                                        @if($page['id'] === 'post_test')
                                            @if($studentResult && $studentResult->post_test_score !== null)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    {{ $studentResult->post_test_score }}
                                                </span>
                                            @else
                                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold bg-rose-100 text-rose-900 border border-rose-200">
                                                    Uji Akhir
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-[10px] px-2 py-0.5 rounded-md font-semibold opacity-80"
                                                  :class="activePage === '{{ $page['id'] }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                                                {{ $page['badge'] }}
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- ─────────────────────────────────────────────────────────────────── --}}
        {{-- ── RIGHT COLUMN: WORKSPACE AKTIVITAS PEMBELAJARAN (8 Cols) ──────── --}}
        {{-- ─────────────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 1. KATA PENGANTAR ═════════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div x-show="activePage === 'kata_pengantar'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">✏️</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600">Bagian Awal • Langkah 1</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Kata Pengantar</h2>
                            </div>
                        </div>
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

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 2. PETUNJUK PENGGUNAAN ════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div x-show="activePage === 'petunjuk_penggunaan'" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Petunjuk Siswa --}}
                    <div class="rounded-3xl bg-white border border-teal-200/80 p-6 sm:p-7 shadow-sm space-y-4">
                        <div class="flex items-center gap-3 pb-3 border-b border-teal-100">
                            <span class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">🎓</span>
                            <div>
                                <h3 class="text-base font-bold text-teal-950">Petunjuk untuk Siswa</h3>
                                <p class="text-xs text-teal-700">Langkah-langkah belajar mandiri</p>
                            </div>
                        </div>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700">
                            @if(!empty($informasiUmum['petunjuk_penggunaan']['petunjuk_siswa']))
                                @foreach((array) $informasiUmum['petunjuk_penggunaan']['petunjuk_siswa'] as $item)
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-teal-600 font-bold mt-0.5">✓</span>
                                        <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                                    </li>
                                @endforeach
                            @elseif(!empty($informasiUmum['petunjuk_penggunaan']) && is_string($informasiUmum['petunjuk_penggunaan']))
                                <div class="whitespace-pre-line leading-relaxed">{!! nl2br(e($informasiUmum['petunjuk_penggunaan'])) !!}</div>
                            @else
                                <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">1.</span><span>Baca dan pahami tujuan pembelajaran sebelum masuk ke materi inti.</span></li>
                                <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">2.</span><span>Kerjakan soal latihan diagnostik (Pre-test) untuk mengukur pengetahuan awal.</span></li>
                                <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">3.</span><span>Pelajari uraian materi dan tonton multimedia video pembelajaran.</span></li>
                                <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">4.</span><span>Lakukan praktik pada simulator embed dan kumpulkan tugas LKPD serta Job Sheet.</span></li>
                                <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">5.</span><span>Selesaikan evaluasi Post-test di bagian akhir modul.</span></li>
                            @endif
                        </ul>
                    </div>

                    {{-- Petunjuk Guru --}}
                    <div class="rounded-3xl bg-white border border-indigo-200/80 p-6 sm:p-7 shadow-sm space-y-4">
                        <div class="flex items-center gap-3 pb-3 border-b border-indigo-100">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">👨‍🏫</span>
                            <div>
                                <h3 class="text-base font-bold text-indigo-950">Peran & Bimbingan Guru</h3>
                                <p class="text-xs text-indigo-700">Fasilitasi pembelajaran peserta didik</p>
                            </div>
                        </div>
                        <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700">
                            @if(!empty($informasiUmum['petunjuk_penggunaan']['petunjuk_guru']))
                                @foreach((array) $informasiUmum['petunjuk_penggunaan']['petunjuk_guru'] as $item)
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-indigo-600 font-bold mt-0.5">✓</span>
                                        <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                                    </li>
                                @endforeach
                            @else
                                <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">1.</span><span>Membimbing siswa yang mengalami kendala saat menyelesaikan aktivitas modul.</span></li>
                                <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">2.</span><span>Memantau antrean pengumpulan tugas dan memberikan penilaian serta umpan balik di Grading Center.</span></li>
                                <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">3.</span><span>Mengarahkan siswa pada sesi refleksi dan penguatan kompetensi kejuruan.</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 3. TUJUAN PEMBELAJARAN ════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div x-show="activePage === 'tujuan_pembelajaran'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">🎯</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Bagian 2: Pendahuluan</span>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Tujuan Pembelajaran & Capaian</h2>
                        </div>
                    </div>

                    @if(!empty($informasiUmum['tujuan_pembelajaran']['capaian_pembelajaran']))
                        <div class="p-5 rounded-2xl bg-teal-50/50 border border-teal-200/70">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-teal-800 mb-2">Capaian Pembelajaran (CP)</h4>
                            <p class="text-sm text-slate-800 leading-relaxed font-medium">
                                {{ $informasiUmum['tujuan_pembelajaran']['capaian_pembelajaran'] }}
                            </p>
                        </div>
                    @endif

                    @if(!empty($informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran']))
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Tujuan Khusus Pembelajaran (TP)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach((array) $informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran'] as $tp)
                                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                                        <span class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">
                                            {{ is_array($tp) ? ($tp['text'] ?? '') : $tp }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif(!empty($informasiUmum['tujuan_pembelajaran']) && is_string($informasiUmum['tujuan_pembelajaran']))
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium">
                            {!! nl2br(e($informasiUmum['tujuan_pembelajaran'])) !!}
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 4. PETA KONSEP ════════════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div x-show="activePage === 'peta_konsep'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">🗺️</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Bagian 2: Pendahuluan</span>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Peta Konsep Materi</h2>
                        </div>
                    </div>

                    @if(!empty($informasiUmum['peta_konsep']['peta_konsep_image_path']))
                        <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-200/70">
                            <img src="{{ asset('storage/' . $informasiUmum['peta_konsep']['peta_konsep_image_path']) }}"
                                 alt="Peta Konsep"
                                 class="max-h-96 mx-auto rounded-xl border border-slate-200 shadow-sm object-contain">
                        </div>
                    @endif

                    @if(!empty($informasiUmum['peta_konsep']['peta_konsep_text']))
                        <div class="p-5 rounded-2xl bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed border border-slate-200/70 font-mono">
                            {!! nl2br(e($informasiUmum['peta_konsep']['peta_konsep_text'])) !!}
                        </div>
                    @elseif(!empty($informasiUmum['peta_konsep']) && is_string($informasiUmum['peta_konsep']))
                        <div class="p-5 rounded-2xl bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed border border-slate-200/70">
                            {!! nl2br(e($informasiUmum['peta_konsep'])) !!}
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 5. GLOSARIUM ══════════════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div x-show="activePage === 'glosarium'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">📖</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Bagian 2: Pendahuluan</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Glosarium Kata Kunci</h2>
                            </div>
                        </div>
                        <div class="relative w-full sm:w-64">
                            <input type="text"
                                   x-model="searchGlosarium"
                                   placeholder="Cari istilah teknis..."
                                   class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:bg-white outline-none transition">
                            <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    @php
                        $glosariumItems = [];
                        if (isset($informasiUmum['glosarium'])) {
                            $glosariumItems = is_array($informasiUmum['glosarium']) && isset($informasiUmum['glosarium']['glosarium'])
                                ? $informasiUmum['glosarium']['glosarium']
                                : (array) $informasiUmum['glosarium'];
                        }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        @foreach($glosariumItems as $item)
                            @php
                                $istilah = is_array($item) ? ($item['istilah'] ?? '') : '';
                                $definisi = is_array($item) ? ($item['definisi'] ?? '') : $item;
                            @endphp
                            <div x-show="!searchGlosarium || '{{ strtolower($istilah . ' ' . $definisi) }}'.includes(searchGlosarium.toLowerCase())"
                                 class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 hover:border-teal-300 transition">
                                <h5 class="text-xs font-bold text-teal-900 mb-1 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                    <span>{{ $istilah ?: 'Istilah' }}</span>
                                </h5>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    {{ $definisi }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 6. PRE-TEST (SOAL LATIHAN DIAGNOSTIK) ═════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            @if($module->has_pre_test && $module->preTest)
            <div x-show="activePage === 'pre_test'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-teal-200/90 shadow-sm overflow-hidden" id="section-pre-test">
                    <div class="bg-gradient-to-r from-teal-700 to-emerald-700 text-white p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl shrink-0">⚡</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-200">Bagian 2 • Latihan Diagnostik</span>
                                <h2 class="text-xl sm:text-2xl font-black leading-tight">{{ $module->preTest->title ?? 'Pre-test Pembuka' }}</h2>
                                <p class="text-xs text-teal-100 mt-0.5">Durasi: {{ $module->preTest->duration_minutes ?? 15 }} Menit • Target KKTP: {{ $module->preTest->kktp ?? 75 }}</p>
                            </div>
                        </div>

                        @if($studentResult && $studentResult->pre_test_score !== null)
                            <div class="bg-white/15 px-4 py-2 rounded-2xl border border-white/20 text-center shrink-0">
                                <span class="text-[10px] font-bold text-teal-200 uppercase block">Skor Pre-test</span>
                                <span class="text-2xl font-black text-white">{{ $studentResult->pre_test_score }}/100</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8">
                        @if($studentResult && $studentResult->pre_test_score !== null)
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-6 text-center space-y-3">
                                <div class="w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center text-2xl mx-auto shadow-md">
                                    ✓
                                </div>
                                <h3 class="text-lg font-black text-emerald-950">Pre-test Telah Diselesaikan!</h3>
                                <p class="text-xs sm:text-sm text-emerald-800 max-w-md mx-auto leading-relaxed">
                                    Anda telah menyelesaikan tes diagnostik ini dengan perolehan skor <strong>{{ $studentResult->pre_test_score }}</strong>.
                                    Silakan lanjutkan mempelajari materi pembelajaran pada langkah berikutnya.
                                </p>
                            </div>
                        @else
                            <form action="{{ route('student.modules.pre-test.submit', $module) }}" method="POST" class="space-y-8">
                                @csrf
                                <p class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                                    💡 <strong>Petunjuk:</strong> Pilihlah salah satu jawaban yang paling tepat (A, B, C, D, atau E) untuk setiap butir soal, kemudian klik tombol <strong>Kirim Jawaban Pre-test</strong>.
                                </p>

                                @foreach($module->preTest->questions as $idx => $q)
                                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
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
                                                    <label class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:border-teal-400 hover:bg-teal-50/20 cursor-pointer transition">
                                                        <input type="radio"
                                                               name="answers[{{ $q->id }}]"
                                                               value="{{ $optKey }}"
                                                               required
                                                               class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-slate-300">
                                                        <span class="text-xs font-bold text-slate-700 w-5">{{ $optKey }}.</span>
                                                        <span class="text-xs sm:text-sm text-slate-800">{{ $q->options[$optKey] }}</span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="pt-4 flex justify-end">
                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin mengirimkan seluruh jawaban Pre-test?');"
                                            class="px-8 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-md shadow-teal-600/30 transition cursor-pointer">
                                        Kirim Jawaban Pre-test →
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 7. URAIAN MATERI PEMBELAJARAN & PPT ═══════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            @if($module->has_materi)
            <div x-show="activePage === 'materi'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-blue-50/70 to-slate-50">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold border border-blue-200">
                                ⏱️ Estimasi Belajar: {{ $materiData['estimasi_waktu'] ?? 45 }} Menit
                            </span>
                            <span class="px-3 py-1 rounded-full bg-slate-200/80 text-slate-800 text-xs font-bold">
                                📖 Bagian 3: Kegiatan Belajar
                            </span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                            {{ $materiData['judul_materi'] ?? $module->title }}
                        </h2>
                    </div>

                    {{-- Teks Uraian --}}
                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="materi-prose text-slate-800 leading-relaxed text-sm sm:text-base">
                            @if(!empty($materiData['uraian_materi']))
                                {!! $materiData['uraian_materi'] !!}
                            @else
                                <p class="text-slate-400 italic">Materi pembelajaran belum diunggah oleh guru pengampu.</p>
                            @endif
                        </div>

                        {{-- Unduh Berkas PPT / Slide Pembelajaran --}}
                        @if(!empty($materiData['ppt_file_path']))
                            <div class="mt-8 p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow-md">
                                        📊
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900">Dokumen Slide Presentasi (PPT / PDF)</h4>
                                        <p class="text-xs text-slate-500">{{ $materiData['ppt_file_name'] ?? 'Slide Materi' }}</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $materiData['ppt_file_path']) }}"
                                   target="_blank"
                                   download
                                   class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2">
                                    <span>Unduh Slide Presentasi</span>
                                    <span>📥</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 8. VIDEO YOUTUBE & RESUME ══════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            @if($module->has_video)
            @php
                $vList = !empty($videosList) ? $videosList : $module->videosList();
                $minCharsRequired = (int)($videoData['min_summary_chars'] ?? 20);
            @endphp
            <div x-show="activePage === 'video'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-video">
                    {{-- Header Multimedia --}}
                    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-red-600 text-white flex items-center justify-center text-lg font-bold shadow-sm">▶️</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-300">Multimedia Pembelajaran ({{ count($vList) }} Video)</span>
                                <h2 class="text-lg sm:text-xl font-bold">{{ $videoData['video_title'] ?? ($videoData['judul_video'] ?? 'Video Pembelajaran YouTube') }}</h2>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            @if($videoSummary)
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $videoSummary->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                                    {{ $videoSummary->manual_score !== null ? 'Nilai: ' . $videoSummary->manual_score : 'Resume Terkirim (Pending)' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        {{-- Multi-Video Player & Playlist Switcher --}}
                        @if(!empty($vList))
                            <div x-data="{ activeVid: 0, vids: {{ json_encode($vList) }} }" class="space-y-4">
                                {{-- Playlist Tabs jika terdapat lebih dari 1 video --}}
                                <template x-if="vids.length > 1">
                                    <div class="bg-slate-900 rounded-2xl p-2.5 flex items-center gap-2 overflow-x-auto border border-slate-800 shadow-inner">
                                        <span class="text-xs font-bold text-red-400 px-2 shrink-0">Daftar Video:</span>
                                        <template x-for="(v, vIdx) in vids" :key="vIdx">
                                            <button type="button"
                                                    @click="activeVid = vIdx"
                                                    :class="activeVid === vIdx ? 'bg-red-600 text-white shadow-md' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 cursor-pointer">
                                                <span>▶</span>
                                                <span x-text="'Video ' + (vIdx + 1)"></span>
                                            </button>
                                        </template>
                                    </div>
                                </template>

                                {{-- Active Video Player --}}
                                <div class="aspect-video w-full rounded-2xl overflow-hidden shadow-lg border border-slate-200 bg-black relative">
                                    <template x-for="(v, vIdx) in vids" :key="vIdx">
                                        <iframe x-show="activeVid === vIdx"
                                                class="w-full h-full absolute inset-0"
                                                :src="v.embed_url"
                                                :title="v.title"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                    </template>
                                </div>

                                {{-- Active Video Info & Keterangan --}}
                                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-red-600"
                                              x-text="'Sedang Diputar: Video ' + (activeVid + 1) + ' dari ' + vids.length"></span>
                                        <h4 class="text-sm sm:text-base font-bold text-slate-900" x-text="vids[activeVid]?.title || 'Video Pembelajaran'"></h4>
                                    </div>
                                    <template x-if="vids[activeVid]?.description && vids[activeVid]?.description.trim().length > 0">
                                        <div class="text-xs text-slate-600 leading-relaxed bg-white p-3.5 rounded-xl border border-slate-200/70 whitespace-pre-line shadow-2xs">
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan Video:</div>
                                            <p x-text="vids[activeVid]?.description"></p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        @endif

                        {{-- Petunjuk Belajar & Poin Panduan (Jika ada) --}}
                        @if(!empty($videoData['instructions']))
                            <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 text-xs text-amber-950 space-y-1">
                                <h5 class="font-bold flex items-center gap-1.5 text-amber-900">
                                    <span>📌</span>
                                    <span>Petunjuk Belajar:</span>
                                </h5>
                                <p class="leading-relaxed whitespace-pre-line text-amber-900/90">{{ $videoData['instructions'] }}</p>
                            </div>
                        @endif

                        {{-- Form / Tampilan Satu Ringkasan Terpadu Siswa --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                        <span>📝</span>
                                        <span>Ringkasan / Resume Video Siswa</span>
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Tuliskan 1 (satu) resume intisari pemahaman yang merangkum seluruh video di atas.</p>
                                </div>

                                @if($videoSummary && $videoSummary->manual_score === null)
                                    <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'video']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Apakah Anda ingin membatalkan resume ini untuk mengedit ulang?');"
                                                class="text-xs text-red-600 hover:text-red-700 font-bold underline cursor-pointer">
                                            Batalkan / Edit Ulang
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($videoSummary)
                                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                                    <p class="text-xs text-slate-500">Dikirim pada: {{ $videoSummary->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                    <div class="text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium">
                                        {{ $videoSummary->summary_text }}
                                    </div>
                                    @if($videoSummary->manual_score !== null)
                                        <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between text-xs font-bold text-emerald-800">
                                            <span>Nilai Guru:</span>
                                            <span class="text-sm">{{ $videoSummary->manual_score }}/100</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <form action="{{ route('student.modules.video.submit', $module) }}" method="POST" class="space-y-3"
                                      x-data="{ summaryText: '', get charCount() { return this.summaryText.length; }, minChars: {{ $minCharsRequired }} }">
                                    @csrf
                                    <textarea name="summary_text"
                                              x-model="summaryText"
                                              rows="5"
                                              required
                                              placeholder="Tuliskan poin-poin penting, intisari materi, dan pemahaman yang Anda peroleh setelah menyimak seluruh video di atas (minimal {{ $minCharsRequired }} karakter)..."
                                              class="w-full p-4 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition outline-none"></textarea>

                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span :class="charCount < minChars ? 'text-amber-600 font-bold' : 'text-emerald-600 font-bold'">
                                            Karakter: <span x-text="charCount"></span> (Min. <span x-text="minChars"></span>)
                                        </span>
                                        <button type="submit"
                                                :disabled="charCount < minChars"
                                                :class="charCount < minChars ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 cursor-pointer'"
                                                class="px-6 py-2.5 rounded-xl text-white font-bold text-xs transition">
                                            Simpan & Kirim Resume Video
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 9. SIMULATOR EMBED / MEDIA INTERAKTIF ═════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            @if($module->has_embed)
            <div x-show="activePage === 'embed'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-embed">
                    <div class="bg-gradient-to-r from-violet-950 via-slate-900 to-indigo-950 text-white p-6 sm:p-7 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-lg font-bold shadow-sm">⚡</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-300">Bagian 4 • Praktik Interaktif</span>
                                <h2 class="text-lg sm:text-xl font-bold">{{ $embedData['judul_embed'] ?? 'Eksplorasi Simulator / Embed Media' }}</h2>
                            </div>
                        </div>
                        @if($embedSubmission)
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $embedSubmission->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                                {{ $embedSubmission->manual_score !== null ? 'Nilai: ' . $embedSubmission->manual_score : 'Screenshot Terunggah' }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        @if(!empty($embedData['instruksi_praktik']))
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                                💡 <strong>Instruksi Praktik:</strong> {{ $embedData['instruksi_praktik'] }}
                            </div>
                        @endif

                        {{-- Embed Frame --}}
                        @if(!empty($embedData['embed_code']))
                            <div class="w-full rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 min-h-[420px] shadow-inner">
                                {!! $embedData['embed_code'] !!}
                            </div>
                        @elseif(!empty($embedData['direct_url']))
                            <div class="p-6 rounded-2xl bg-indigo-50 border border-indigo-200 text-center space-y-3">
                                <h4 class="text-sm font-bold text-indigo-950">Tautan Simulator / Praktik Eksternal</h4>
                                <p class="text-xs text-indigo-700 max-w-md mx-auto">Klik tombol di bawah ini untuk membuka lembar simulator pada jendela peramban baru.</p>
                                <a href="{{ $embedData['direct_url'] }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                                    <span>Buka Simulator Praktik</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        @endif

                        {{-- Form / Bukti Screenshot Praktik --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    <span>📸</span>
                                    <span>Bukti Tangkapan Layar (Screenshot) Praktik</span>
                                </h4>
                                @if($embedSubmission && $embedSubmission->manual_score === null)
                                    <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'embed']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Apakah Anda ingin membatalkan screenshot ini untuk mengunggah ulang?');"
                                                class="text-xs text-red-600 hover:text-red-700 font-bold underline cursor-pointer">
                                            Batalkan / Unggah Ulang
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($embedSubmission)
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center gap-4">
                                    <img src="{{ asset('storage/' . $embedSubmission->screenshot_path) }}"
                                         alt="Bukti Screenshot"
                                         class="w-full sm:w-48 h-32 object-cover rounded-xl border border-slate-300 shadow-sm">
                                    <div class="space-y-1 text-xs text-slate-600 flex-1">
                                        <p class="font-bold text-slate-900 text-sm">Screenshot Berhasil Diunggah</p>
                                        <p>Waktu kirim: {{ $embedSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                        @if($embedSubmission->manual_score !== null)
                                            <p class="text-emerald-700 font-bold">Nilai: {{ $embedSubmission->manual_score }}/100</p>
                                        @else
                                            <p class="text-amber-600 font-medium">Menunggu penilaian guru pengampu</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('student.modules.embed.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3"
                                      x-data="{ previewUrl: null }">
                                    @csrf
                                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-indigo-400 bg-slate-50/50 transition">
                                        <template x-if="!previewUrl">
                                            <div>
                                                <span class="text-3xl block mb-2">📷</span>
                                                <p class="text-xs sm:text-sm font-bold text-slate-700">Unggah Gambar Screenshot Praktik</p>
                                                <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5 MB)</p>
                                            </div>
                                        </template>
                                        <template x-if="previewUrl">
                                            <div class="space-y-2">
                                                <img :src="previewUrl" class="max-h-48 mx-auto rounded-xl shadow-md object-contain border">
                                                <p class="text-xs text-emerald-600 font-bold">Gambar siap diunggah</p>
                                            </div>
                                        </template>
                                        <input type="file"
                                               name="screenshot"
                                               accept="image/*"
                                               required
                                               @change="const file = $event.target.files[0]; if (file) { previewUrl = URL.createObjectURL(file); }"
                                               class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer">
                                            Kirim Bukti Screenshot
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 10. LEMBAR KERJA PRAKTIK (JOB SHEET PDF) ══════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            @if($module->has_job_sheet)
            <div x-show="activePage === 'job_sheet'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-jobsheet">
                    <div class="bg-gradient-to-r from-rose-950 to-slate-900 text-white p-6 sm:p-7 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-lg font-bold shadow-sm">📋</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-300">Bagian 4 • Lembar Kerja Praktik</span>
                                <h2 class="text-lg sm:text-xl font-bold">{{ $jobSheetData['judul_jobsheet'] ?? 'Job Sheet Praktikum Bengkel/Lab' }}</h2>
                            </div>
                        </div>
                        @if($jobSheetSubmission)
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $jobSheetSubmission->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                                {{ $jobSheetSubmission->manual_score !== null ? 'Nilai: ' . $jobSheetSubmission->manual_score : 'Laporan PDF Terkirim' }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        {{-- Instruksi & Download Berkas Panduan Job Sheet --}}
                        <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-rose-950">Panduan & Lembar Instruksi Praktikum</h4>
                                <p class="text-xs text-rose-800 mt-0.5">Unduh berkas PDF panduan praktikum sebelum memulai pekerjaan laboratorium.</p>
                            </div>
                            @if(!empty($jobSheet?->pdf_file_path))
                                <a href="{{ asset('storage/' . $jobSheet->pdf_file_path) }}"
                                   target="_blank"
                                   download
                                   class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2 shrink-0">
                                    <span>Unduh Panduan Job Sheet PDF</span>
                                    <span>📥</span>
                                </a>
                            @endif
                        </div>

                        {{-- Form / Status Pengumpulan Laporan Job Sheet --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    <span>📑</span>
                                    <span>Unggah Laporan Hasil Praktikum (PDF)</span>
                                </h4>
                                @if($jobSheetSubmission && $jobSheetSubmission->manual_score === null)
                                    <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'job_sheet']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Apakah Anda ingin membatalkan berkas Job Sheet ini untuk mengunggah ulang?');"
                                                class="text-xs text-red-600 hover:text-red-700 font-bold underline cursor-pointer">
                                            Batalkan / Unggah Ulang
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($jobSheetSubmission)
                                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm">PDF</span>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900">Laporan Job Sheet Terkirim</p>
                                            <p class="text-[11px] text-slate-500">Dikirim: {{ $jobSheetSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($jobSheetSubmission->manual_score !== null)
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                                                Nilai: {{ $jobSheetSubmission->manual_score }}/100
                                            </span>
                                        @endif
                                        <a href="{{ asset('storage/' . $jobSheetSubmission->uploaded_file_path) }}"
                                           target="_blank"
                                           class="px-4 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                                            Lihat Berkas ↗
                                        </a>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('student.modules.job-sheet.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-rose-400 bg-slate-50/50 transition">
                                        <span class="text-3xl block mb-2">📄</span>
                                        <p class="text-xs sm:text-sm font-bold text-slate-700">Pilih Berkas Laporan Praktikum Job Sheet</p>
                                        <p class="text-xs text-slate-400 mt-1">Dokumen harus dalam format PDF (Maksimal 10 MB)</p>
                                        <input type="file"
                                               name="job_sheet_file"
                                               accept=".pdf,application/pdf"
                                               required
                                               class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition cursor-pointer">
                                            Kirim Laporan Job Sheet PDF
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 11. TUGAS LKPD ════════════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            @if($module->has_lkpd)
            <div x-show="activePage === 'lkpd'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-lkpd">
                    <div class="bg-gradient-to-r from-amber-950 via-slate-900 to-amber-950 text-white p-6 sm:p-7 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center text-lg font-bold shadow-sm">👥</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-300">Bagian 4 • Lembar Kerja Peserta Didik</span>
                                <h2 class="text-lg sm:text-xl font-bold">{{ $lkpdData['judul_lkpd'] ?? 'Tugas Lembar Kerja (LKPD)' }}</h2>
                            </div>
                        </div>
                        @if($lkpdSubmission)
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $lkpdSubmission->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                                {{ $lkpdSubmission->manual_score !== null ? 'Nilai: ' . $lkpdSubmission->manual_score : 'Tugas LKPD Terkirim' }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        {{-- Instruksi & Download LKPD --}}
                        <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-amber-950">Berkas Soal & Instruksi LKPD</h4>
                                <p class="text-xs text-amber-800 mt-0.5">Pelajari dan diskusikan soal LKPD bersama kelompok kerja Anda.</p>
                            </div>
                            @if(!empty($lkpd?->pdf_file_path))
                                <a href="{{ asset('storage/' . $lkpd->pdf_file_path) }}"
                                   target="_blank"
                                   download
                                   class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2 shrink-0">
                                    <span>Unduh Berkas LKPD PDF</span>
                                    <span>📥</span>
                                </a>
                            @endif
                        </div>

                        {{-- Form / Status Pengumpulan LKPD --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    <span>📑</span>
                                    <span>Unggah Jawaban / Laporan LKPD (PDF)</span>
                                </h4>
                                @if($lkpdSubmission && $lkpdSubmission->manual_score === null)
                                    <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'lkpd']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Apakah Anda ingin membatalkan berkas LKPD ini untuk mengunggah ulang?');"
                                                class="text-xs text-red-600 hover:text-red-700 font-bold underline cursor-pointer">
                                            Batalkan / Unggah Ulang
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($lkpdSubmission)
                                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">PDF</span>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900">Tugas LKPD Terkirim</p>
                                            <p class="text-[11px] text-slate-500">Dikirim: {{ $lkpdSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($lkpdSubmission->manual_score !== null)
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                                                Nilai: {{ $lkpdSubmission->manual_score }}/100
                                            </span>
                                        @endif
                                        <a href="{{ asset('storage/' . $lkpdSubmission->uploaded_file_path) }}"
                                           target="_blank"
                                           class="px-4 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                                            Lihat Berkas ↗
                                        </a>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('student.modules.lkpd.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-amber-400 bg-slate-50/50 transition">
                                        <span class="text-3xl block mb-2">📄</span>
                                        <p class="text-xs sm:text-sm font-bold text-slate-700">Pilih Berkas Jawaban LKPD</p>
                                        <p class="text-xs text-slate-400 mt-1">Dokumen harus dalam format PDF (Maksimal 10 MB)</p>
                                        <input type="file"
                                               name="lkpd_file"
                                               accept=".pdf,application/pdf"
                                               required
                                               class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 cursor-pointer">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md shadow-amber-600/20 transition cursor-pointer">
                                            Kirim Jawaban LKPD PDF
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 12. POST-TEST (EVALUASI AKHIR MODUL) ══════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            @if($module->has_post_test && $module->postTest)
            <div x-show="activePage === 'post_test'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-rose-200/90 shadow-sm overflow-hidden" id="section-post-test">
                    <div class="bg-gradient-to-r from-rose-700 to-pink-700 text-white p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <span class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl shrink-0">🏆</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-200">Bagian 5 • Evaluasi Akhir</span>
                                <h2 class="text-xl sm:text-2xl font-black leading-tight">{{ $module->postTest->title ?? 'Post-test: Evaluasi Pemahaman' }}</h2>
                                <p class="text-xs text-rose-100 mt-0.5">Durasi: {{ $module->postTest->duration_minutes ?? 20 }} Menit • Target KKTP: {{ $module->postTest->kktp ?? 75 }}</p>
                            </div>
                        </div>

                        @if($studentResult && $studentResult->post_test_score !== null)
                            <div class="bg-white/15 px-4 py-2 rounded-2xl border border-white/20 text-center shrink-0">
                                <span class="text-[10px] font-bold text-rose-200 uppercase block">Skor Post-test</span>
                                <span class="text-2xl font-black text-white">{{ $studentResult->post_test_score }}/100</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 sm:p-8">
                        @if($studentResult && $studentResult->post_test_score !== null)
                            <div class="rounded-2xl bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-200 p-6 text-center space-y-4">
                                <div class="w-16 h-16 rounded-full bg-rose-600 text-white flex items-center justify-center text-3xl mx-auto shadow-md">
                                    🏆
                                </div>
                                <h3 class="text-xl font-black text-slate-900">Post-test Berhasil Diselesaikan!</h3>
                                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
                                    Skor Post-test Anda adalah <strong class="text-rose-600 text-base">{{ $studentResult->post_test_score }}</strong>.
                                    @if($studentResult->pre_test_score !== null)
                                        @php $delta = $studentResult->post_test_score - $studentResult->pre_test_score; @endphp
                                        <br>
                                        <span class="inline-block mt-2 font-bold px-3 py-1 rounded-full text-xs {{ $delta >= 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700' }}">
                                            Perkembangan dari Pre-test: {{ $delta >= 0 ? '+' . $delta : $delta }} poin
                                        </span>
                                    @endif
                                </p>
                            </div>
                        @else
                            <form action="{{ route('student.modules.post-test.submit', $module) }}" method="POST" class="space-y-8">
                                @csrf
                                <p class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                                    💡 <strong>Petunjuk Post-test:</strong> Jawablah seluruh soal evaluasi penutup ini secara cermat dan mandiri untuk mengukur ketuntasan belajar Anda.
                                </p>

                                @foreach($module->postTest->questions as $idx => $q)
                                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                                        <div class="flex items-start gap-3">
                                            <span class="w-7 h-7 rounded-xl bg-rose-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                                {{ $idx + 1 }}
                                            </span>
                                            <div class="text-sm font-bold text-slate-900 leading-relaxed flex-1">
                                                {{ $q->question_text }}
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-2.5 pl-10">
                                            @foreach(['A', 'B', 'C', 'D', 'E'] as $optKey)
                                                @if(!empty($q->options[$optKey]))
                                                    <label class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:border-rose-400 hover:bg-rose-50/20 cursor-pointer transition">
                                                        <input type="radio"
                                                               name="answers[{{ $q->id }}]"
                                                               value="{{ $optKey }}"
                                                               required
                                                               class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-slate-300">
                                                        <span class="text-xs font-bold text-slate-700 w-5">{{ $optKey }}.</span>
                                                        <span class="text-xs sm:text-sm text-slate-800">{{ $q->options[$optKey] }}</span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <div class="pt-4 flex justify-end">
                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin mengirimkan jawaban Post-test dan menyelesaikan modul ini?');"
                                            class="px-8 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-md shadow-rose-600/30 transition cursor-pointer">
                                        Kirim Jawaban Post-test →
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 13. DAFTAR PUSTAKA ════════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div x-show="activePage === 'daftar_pustaka'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <span class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg font-bold">📚</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-600">Bagian 5 • Bagian Akhir</span>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Daftar Pustaka & Rujukan</h2>
                        </div>
                    </div>

                    @php
                        $daftarPustakaList = [];
                        if (isset($informasiUmum['daftar_pustaka'])) {
                            $daftarPustakaList = is_array($informasiUmum['daftar_pustaka']) && isset($informasiUmum['daftar_pustaka']['daftar_pustaka'])
                                ? $informasiUmum['daftar_pustaka']['daftar_pustaka']
                                : (array) $informasiUmum['daftar_pustaka'];
                        }
                    @endphp

                    <div class="space-y-3">
                        @if(!empty($daftarPustakaList))
                            @foreach($daftarPustakaList as $idx => $pustaka)
                                @php
                                    $judul = is_array($pustaka) ? ($pustaka['judul'] ?? '') : $pustaka;
                                    $penulis = is_array($pustaka) ? ($pustaka['penulis'] ?? '') : '';
                                    $tahun = is_array($pustaka) ? ($pustaka['tahun'] ?? '') : '';
                                    $tautan = is_array($pustaka) ? ($pustaka['tautan'] ?? '') : '';
                                @endphp
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-start gap-3.5">
                                    <span class="w-6 h-6 rounded-lg bg-slate-200 text-slate-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $idx + 1 }}
                                    </span>
                                    <div class="text-xs sm:text-sm text-slate-800 leading-relaxed flex-1">
                                        @if($penulis)<strong>{{ $penulis }}</strong>. @endif
                                        @if($tahun)({{ $tahun }}). @endif
                                        <em class="font-bold text-slate-900">{{ $judul }}</em>.
                                        @if($tautan)
                                            <a href="{{ $tautan }}" target="_blank" rel="noopener" class="text-teal-600 hover:underline block mt-1 text-xs">
                                                {{ $tautan }} ↗
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-slate-400 italic text-sm">Tidak ada daftar pustaka yang dicantumkan.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- 14. REKAPITULASI NILAI BELAJAR SISWA ══════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div x-show="activePage === 'rekap_nilai'" x-cloak class="space-y-6">
                <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold">📊</span>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-600">Bagian 5 • Ringkasan Akhir</span>
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900">Transparansi Rekapitulasi Nilai</h2>
                            </div>
                        </div>
                        @if($studentResult)
                            <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $studentResult->grading_status === 'graded' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                {{ $studentResult->grading_status === 'graded' ? 'Telah Dinilai Guru' : 'Menunggu Penilaian Manual' }}
                            </span>
                        @endif
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
                        <table class="w-full text-xs sm:text-sm text-left">
                            <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                                <tr>
                                    <th class="py-3.5 px-4">Instrumen Aktivitas Evaluasi</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                    <th class="py-3.5 px-4 text-center">Skor / Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @if($module->has_pre_test)
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">1. Kuis Awal (Pre-test)</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($studentResult && $studentResult->pre_test_score !== null)
                                                <span class="text-xs font-bold text-emerald-600">✓ Selesai</span>
                                            @else
                                                <span class="text-xs text-slate-400">Belum</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            {{ $studentResult?->pre_test_score !== null ? $studentResult->pre_test_score : '-' }}
                                        </td>
                                    </tr>
                                @endif

                                @if($module->has_video)
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">2. Ringkasan Video YouTube</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($videoSummary)
                                                <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                            @else
                                                <span class="text-xs text-slate-400">Belum</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            {{ $studentResult?->video_score !== null ? $studentResult->video_score : ($videoSummary ? 'Pending' : '-') }}
                                        </td>
                                    </tr>
                                @endif

                                @if($module->has_embed)
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">3. Praktik Simulator / Embed</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($embedSubmission)
                                                <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                            @else
                                                <span class="text-xs text-slate-400">Belum</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            {{ $studentResult?->embed_score !== null ? $studentResult->embed_score : ($embedSubmission ? 'Pending' : '-') }}
                                        </td>
                                    </tr>
                                @endif

                                @if($module->has_job_sheet)
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">4. Lembar Kerja Praktikum (Job Sheet)</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($jobSheetSubmission)
                                                <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                            @else
                                                <span class="text-xs text-slate-400">Belum</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            {{ $studentResult?->job_sheet_score !== null ? $studentResult->job_sheet_score : ($jobSheetSubmission ? 'Pending' : '-') }}
                                        </td>
                                    </tr>
                                @endif

                                @if($module->has_lkpd)
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">5. Tugas LKPD</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($lkpdSubmission)
                                                <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                            @else
                                                <span class="text-xs text-slate-400">Belum</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            {{ $studentResult?->lkpd_score !== null ? $studentResult->lkpd_score : ($lkpdSubmission ? 'Pending' : '-') }}
                                        </td>
                                    </tr>
                                @endif

                                @if($module->has_post_test)
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">6. Evaluasi Akhir (Post-test)</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($studentResult && $studentResult->post_test_score !== null)
                                                <span class="text-xs font-bold text-emerald-600">✓ Selesai</span>
                                            @else
                                                <span class="text-xs text-slate-400">Belum</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold">
                                            {{ $studentResult?->post_test_score !== null ? $studentResult->post_test_score : '-' }}
                                        </td>
                                    </tr>
                                @endif

                                <tr class="bg-slate-50/80 font-black text-slate-900">
                                    <td class="py-4 px-4 text-sm uppercase">NILAI AKHIR SUMATIF</td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'TUNTAS' : 'BELUM TUNTAS' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-center text-lg {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $studentResult?->summative_score ?? 0 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════════ --}}
            {{-- ═══ 3. BOTTOM SEQUENTIAL NAVIGATION BAR (NEXT / PREV) ════════ --}}
            {{-- ═══════════════════════════════════════════════════════════════ --}}
            <div class="rounded-3xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Tombol Sebelumnya --}}
                <div>
                    <template x-if="prevPage">
                        <button type="button"
                                @click="goToPage(prevPage.id)"
                                class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                            <span>←</span>
                            <span>Sebelumnya: <strong x-text="prevPage.title"></strong></span>
                        </button>
                    </template>
                    <template x-if="!prevPage">
                        <button type="button"
                                @click="viewMode = 'overview'"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer">
                            <span>← Kembali ke Detail Modul</span>
                        </button>
                    </template>
                </div>

                {{-- Tombol Selanjutnya --}}
                <div>
                    <template x-if="nextPage">
                        <button type="button"
                                @click="goToPage(nextPage.id)"
                                class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-indigo-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
                            <span>Lanjut: <strong x-text="nextPage.title"></strong></span>
                            <span>→</span>
                        </button>
                    </template>
                    <template x-if="!nextPage">
                        <button type="button"
                                @click="viewMode = 'overview'"
                                class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
                            <span>Selesai & Lihat Detail Nilai</span>
                            <span>✓</span>
                        </button>
                    </template>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
