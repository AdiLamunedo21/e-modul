@extends('layouts.student.dashboardstudent')

@section('title', 'Student Portal — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Dashboard Siswa')

@section('content')

<div class="space-y-8 pb-12">

    {{-- Flash Success Alert --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Flash Error Alert --}}
    @if($errors->has('class_code'))
        <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
            <span>{{ $errors->first('class_code') }}</span>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- KONDISI 1: DASHBOARD KOSONG (SISWA BELUM BERGABUNG KE KELAS MANAPUN)        --}}
    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    @if($joinedClasses->isEmpty())

        {{-- Hero Card Sambutan Siswa Baru & Onboarding Gabung Kelas --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 p-6 sm:p-10 text-white shadow-xl shadow-emerald-950/20 border border-emerald-700/40">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-56 h-56 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                    <span class="flex items-center gap-1.5 text-emerald-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Akun Siswa Aktif</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="text-emerald-100 text-xs font-medium">NISN: {{ $student->identity_number }}</span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight drop-shadow-sm">
                    Selamat Datang di E-Modul, {{ $student->name }}! 👋
                </h1>
                <p class="text-slate-200 text-sm sm:text-base leading-relaxed font-normal">
                    Akun Anda telah berhasil terdaftar. Namun saat ini dashboard Anda masih kosong karena belum terhubung ke rombel kelas manapun. Silakan masukkan <strong>Kode Kelas</strong> yang telah dibagikan oleh guru pengampu Anda untuk langsung mulai belajar.
                </p>
            </div>
        </div>

        {{-- Card Form Input Kode Kelas --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
            <div class="max-w-xl mx-auto text-center space-y-6">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-3xl border border-emerald-100 shadow-inner">
                    🔑
                </div>

                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Tambah / Gabung ke Kelas Pembelajaran
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Masukkan 6 karakter kode kelas yang Anda peroleh dari guru mata pelajaran.
                    </p>
                </div>

                <form action="{{ route('student.join-class') }}" method="POST" class="space-y-4 text-left">
                    @csrf
                    <div>
                        <label for="class_code_input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5 text-center">
                            Kode Kelas Guru
                        </label>
                        <div class="relative max-w-sm mx-auto">
                            <input type="text"
                                   id="class_code_input"
                                   name="class_code"
                                   value="{{ old('class_code') }}"
                                   required
                                   autofocus
                                   placeholder="Contoh: KLS-7X89"
                                   class="w-full text-center px-4 py-3.5 text-lg font-mono font-black uppercase tracking-widest bg-slate-50 border @error('class_code') border-red-400 bg-red-50/30 @else border-slate-300 @enderror rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all outline-none">
                        </div>
                        @error('class_code')
                            <p class="text-red-500 text-xs mt-1.5 text-center font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="max-w-sm mx-auto pt-2">
                        <button type="submit"
                                class="w-full py-3.5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-sm transition-all duration-200 shadow-lg shadow-emerald-600/30 hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Gabung ke Kelas Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Panduan / Help Box --}}
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left text-xs text-slate-600 space-y-1.5">
                    <p class="font-bold text-slate-800 flex items-center gap-1.5">
                        <span>💡</span>
                        <span>Belum memiliki Kode Kelas?</span>
                    </p>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Mintalah kode kelas kepada guru mata pelajaran atau wali kelas Anda. Guru dapat melihat kode kelas pada menu <strong>Build Kelas</strong> di dashboard guru masing-masing.
                    </p>
                </div>
            </div>
        </div>

    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    {{-- KONDISI 2: DASHBOARD AKTIF (SISWA MENGIKUTI 1 ATAU LEBIH ROMBEL KELAS)      --}}
    {{-- ══════════════════════════════════════════════════════════════════════════ --}}
    @else

        {{-- ══ 1. Hero / Header Greeting Banner (Hanya Aktif 10 Menit Pertama Sejak Siswa Terdaftar) ══ --}}
        @if($isNewlyRegistered)
            <div x-data="{ showBanner: true }"
                 x-show="showBanner"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/20 border border-emerald-700/40">
                {{-- Decorative Background Blur Effects --}}
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute right-1/3 -top-10 w-48 h-48 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

                {{-- Close / Dismiss Button --}}
                <button @click="showBanner = false"
                        type="button"
                        class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-slate-950/40 hover:bg-slate-950/70 border border-white/10 text-white/70 hover:text-white flex items-center justify-center text-base transition-all"
                        title="Tutup Banner">
                    &times;
                </button>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 pr-6">
                    <div class="space-y-3">
                        {{-- Top Badge Pill --}}
                        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
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
                            Akses seluruh modul pembelajaran interaktif dari rombel kelas yang Anda ikuti, pelajari materi & video, serta selesaikan praktikum mandiri.
                        </p>
                    </div>

                    {{-- Identitas Akun Siswa --}}
                    <div class="flex items-center shrink-0">
                        <div class="bg-slate-950/50 border border-white/20 p-4 rounded-2xl backdrop-blur-md shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 text-2xl shrink-0 font-black">
                                    🎓
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-white uppercase tracking-wider">{{ $student->name }}</p>
                                    <p class="text-xs text-emerald-200/90 mt-0.5 font-medium">NISN: <span class="font-bold text-white">{{ $student->identity_number }}</span></p>
                                    <span class="inline-flex items-center gap-1.5 mt-1 text-[11px] font-bold text-emerald-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Status: Akun Siswa Aktif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════════════ --}}
        {{-- ══ KONTEN UTAMA PORTAL SISWA: TABS & KPI HUB (ALPINE.JS)                 ══ --}}
        {{-- ══════════════════════════════════════════════════════════════════════════ --}}
        <div x-data="{
            activeTab: '{{ in_array($filterStatus, ['classes', 'completed', 'all_modules', 'in_progress']) ? $filterStatus : ($defaultTab ?? 'in_progress') }}',
            defaultTab: '{{ $defaultTab ?? 'in_progress' }}',
            searchQuery: '',
            selectedGrade: 'all',
            selectedClassId: 'all',
            selectedSubjectId: 'all',
            selectedStatus: 'all',
            classItems: {{ Js::from($classesWithModules) }},
            moduleItems: {{ Js::from($processedModules) }},

            switchTab(tab) {
                this.activeTab = tab;
                this.searchQuery = '';
                this.selectedClassId = 'all';
                this.selectedSubjectId = 'all';
                this.selectedStatus = 'all';
                const url = new URL(window.location);
                if (tab === this.defaultTab) {
                    url.searchParams.delete('status');
                } else {
                    url.searchParams.set('status', tab);
                }
                window.history.replaceState({}, '', url);
                window.dispatchEvent(new CustomEvent('student-tab-changed', { detail: tab }));
            },

            matchesClass(item) {
                const query = this.searchQuery.toLowerCase().trim();
                const matchesQuery = !query || 
                    (item.full_name && item.full_name.toLowerCase().includes(query)) ||
                    (item.code && item.code.toLowerCase().includes(query)) ||
                    (item.major_name && item.major_name.toLowerCase().includes(query)) ||
                    (item.teacher_display && item.teacher_display.toLowerCase().includes(query));

                const matchesGrade = (this.selectedGrade === 'all') || (item.grade === this.selectedGrade);

                let matchesStatus = true;
                if (this.selectedStatus === 'in_progress') {
                    matchesStatus = item.modules_count > 0 && item.completed_count < item.modules_count;
                } else if (this.selectedStatus === 'completed') {
                    matchesStatus = item.modules_count > 0 && item.completed_count === item.modules_count;
                } else if (this.selectedStatus === 'not_started') {
                    matchesStatus = item.modules_count > 0 && item.completed_count === 0 && item.in_progress_count === 0;
                }

                return matchesQuery && matchesGrade && matchesStatus;
            },

            get totalVisibleClasses() {
                return this.classItems.filter(item => this.matchesClass(item)).length;
            },

            matchesModule(item, requiredStatus = null) {
                if (!item) return false;

                if (requiredStatus === 'in_progress') {
                    if (!item.is_active_in_class && item.progress_status !== 'in_progress') {
                        return false;
                    }
                } else if (requiredStatus && item.progress_status !== requiredStatus) {
                    return false;
                }

                if (!requiredStatus && this.selectedStatus !== 'all') {
                    if (this.selectedStatus === 'in_progress') {
                        if (!item.is_active_in_class && item.progress_status !== 'in_progress') {
                            return false;
                        }
                    } else if (item.progress_status !== this.selectedStatus) {
                        return false;
                    }
                }

                if (this.selectedClassId !== 'all' && item.class_id != this.selectedClassId) {
                    return false;
                }

                if (this.selectedSubjectId !== 'all' && item.subject_id != this.selectedSubjectId) {
                    return false;
                }

                const query = this.searchQuery.toLowerCase().trim();
                if (!query) return true;

                return (item.title && item.title.toLowerCase().includes(query)) ||
                       (item.class_name && item.class_name.toLowerCase().includes(query)) ||
                       (item.subject_name && item.subject_name.toLowerCase().includes(query)) ||
                       (item.teacher_name && item.teacher_name.toLowerCase().includes(query)) ||
                       (item.class_code && item.class_code.toLowerCase().includes(query));
            },

            countVisibleModules(status = null) {
                return this.moduleItems.filter(m => this.matchesModule(m, status)).length;
            },

            get hasActiveFilters() {
                return this.searchQuery !== '' || this.selectedGrade !== 'all' || this.selectedClassId !== 'all' || this.selectedSubjectId !== 'all' || this.selectedStatus !== 'all';
            },

            resetFilters() {
                this.searchQuery = '';
                this.selectedGrade = 'all';
                this.selectedClassId = 'all';
                this.selectedSubjectId = 'all';
                this.selectedStatus = 'all';
            }
        }"
        x-on:switch-student-tab.window="switchTab($event.detail)"
        class="space-y-8">

            {{-- ══ 2. RINGKASAN KPI BELAJAR SISWA (STATS CARDS) — DISEMBUNYIKAN PADA MENU SEDANG DIKERJAKAN & RIWAYAT SELESAI ══ --}}
            <div x-show="activeTab !== 'in_progress' && activeTab !== 'completed'"
                 x-cloak
                 @if(in_array($filterStatus, ['in_progress', 'completed'])) style="display: none;" @endif
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- Card 1: Total Kelas --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-2xl shrink-0 font-bold">
                        🏫
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rombel Diikuti</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_joined_classes'] }} <span class="text-xs font-semibold text-slate-500">Kelas</span></p>
                        <p class="text-[11px] text-emerald-600 font-medium truncate">Terhubung Aktif</p>
                    </div>
                </div>

                {{-- Card 2: Total Modul Pembelajaran --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-2xl shrink-0 font-bold">
                        📚
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total E-Modul</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['total_modules'] }} <span class="text-xs font-semibold text-slate-500">Modul</span></p>
                        <p class="text-[11px] text-blue-600 font-medium truncate">Dari {{ $stats['total_subjects'] }} Mata Pelajaran</p>
                    </div>
                </div>

                {{-- Card 3: Rata-Rata Progres Belajar --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center text-2xl shrink-0 font-bold">
                        📈
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-Rata Progres</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['avg_progress'] }}%</p>
                            <span class="text-[11px] text-slate-500 font-medium">{{ $stats['completed_modules'] }}/{{ $stats['total_modules'] }} Tuntas</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1.5 overflow-hidden">
                            <div class="bg-teal-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $stats['avg_progress'] }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Tugas & Praktikum --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                    <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-2xl shrink-0 font-bold">
                        📝
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tugas & Evaluasi</p>
                        <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $stats['pending_tasks_count'] }} <span class="text-xs font-semibold text-slate-500">Tugas</span></p>
                        @if($stats['pending_tasks_count'] === 0)
                            <p class="text-[11px] text-emerald-600 font-bold truncate">Semua Tugas Tuntas 🎉</p>
                        @else
                            <p class="text-[11px] text-amber-600 font-medium truncate">Menunggu Dikerjakan</p>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ══ 3. INTERACTIVE NAVIGATION TABS & FILTER HUB ══ --}}
            <div class="space-y-6">

                {{-- ═══ TAB SELECTOR NAVIGATION BAR (KHUSUS DESKTOP, PADA MOBILE DIGANTIKAN BOTTOM NAV) ═══ --}}
                <div class="hidden lg:flex items-center justify-between gap-3 border-b border-slate-200/80 pb-3">
                <div class="inline-flex p-1.5 rounded-2xl bg-slate-200/70 border border-slate-300/60 backdrop-blur-sm gap-1 overflow-x-auto max-w-full no-scrollbar">
                    
                    {{-- Tab 1: Kelas Saya --}}
                    <button type="button"
                            @click="switchTab('classes')"
                            :class="activeTab === 'classes' ? 'bg-white text-slate-900 font-extrabold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-2 rounded-xl text-xs flex items-center gap-2 transition-all shrink-0">
                        <span>🏫 Kelas Saya</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]"
                              :class="activeTab === 'classes' ? 'bg-slate-100 text-slate-700' : 'bg-slate-300/60 text-slate-600'">
                            {{ count($classesWithModules) }}
                        </span>
                    </button>

                    {{-- Tab 2: Sedang Dikerjakan (Modul) --}}
                    <button type="button"
                            @click="switchTab('in_progress')"
                            :class="activeTab === 'in_progress' ? 'bg-amber-500 text-white font-extrabold shadow-md shadow-amber-500/25' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-2 rounded-xl text-xs flex items-center gap-2 transition-all shrink-0">
                        <span>⏳ Sedang Dikerjakan</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]"
                              :class="activeTab === 'in_progress' ? 'bg-amber-600 text-white' : 'bg-slate-300/60 text-slate-600'">
                            {{ $stats['in_progress'] }}
                        </span>
                    </button>

                    {{-- Tab 3: Riwayat Selesai (Modul) --}}
                    <button type="button"
                            @click="switchTab('completed')"
                            :class="activeTab === 'completed' ? 'bg-emerald-600 text-white font-extrabold shadow-md shadow-emerald-600/25' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-2 rounded-xl text-xs flex items-center gap-2 transition-all shrink-0">
                        <span>✓ Riwayat Selesai</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]"
                              :class="activeTab === 'completed' ? 'bg-emerald-700 text-white' : 'bg-slate-300/60 text-slate-600'">
                            {{ $stats['completed_modules'] }}
                        </span>
                    </button>

                    {{-- Tab 4: Semua Modul --}}
                    <button type="button"
                            @click="switchTab('all_modules')"
                            :class="activeTab === 'all_modules' ? 'bg-blue-600 text-white font-extrabold shadow-md shadow-blue-600/25' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-4 py-2 rounded-xl text-xs flex items-center gap-2 transition-all shrink-0">
                        <span>📚 Semua Modul</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]"
                              :class="activeTab === 'all_modules' ? 'bg-blue-700 text-white' : 'bg-slate-300/60 text-slate-600'">
                            {{ $stats['total_modules'] }}
                        </span>
                    </button>
                </div>

                {{-- Action: Tambah Kelas Baru --}}
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button"
                            @click="joinModalOpen = true"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/25 transition-all group">
                        <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>+ Masuk Kelas Baru</span>
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            {{-- ═══ VIEW 1: TAB KELAS SAYA (ROMBEL)                                ═══ --}}
            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            <div x-show="activeTab === 'classes'" x-cloak class="space-y-6">

                {{-- Toolbar Pencarian & Filter Cepat Kelas --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-sm space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        
                        {{-- Input Live Search Bar Kelas --}}
                        <div class="relative flex-1 max-w-lg">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input type="text"
                                   x-model.debounce.150ms="searchQuery"
                                   placeholder="Cari kelas, jurusan, atau kode kelas..."
                                   class="w-full pl-10 pr-9 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-emerald-500 transition-all shadow-inner">
                            
                            <button type="button"
                                    x-show="searchQuery.length > 0"
                                    x-cloak
                                    @click="searchQuery = ''"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                <span class="w-5 h-5 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[10px] font-bold text-slate-600">✕</span>
                            </button>
                        </div>

                        {{-- Filter Status Progres Kelas --}}
                        <div class="flex items-center gap-2.5 flex-wrap justify-between lg:justify-end">
                            <div class="relative inline-flex items-center">
                                <select x-model="selectedStatus"
                                        class="text-xs font-bold py-2 px-3 pr-8 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                                    <option value="all">📊 Semua Status Kelas</option>
                                    <option value="in_progress">⏳ Sedang Berjalan</option>
                                    <option value="completed">✓ Tuntas Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Tingkat Jenjang --}}
                    <div class="flex items-center gap-2 pt-2 border-t border-slate-100 overflow-x-auto no-scrollbar py-0.5">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 shrink-0 mr-1">Tingkat:</span>
                        
                        <button type="button"
                                @click="selectedGrade = 'all'"
                                :class="selectedGrade === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5">
                            <span>Semua Tingkat</span>
                            <span :class="selectedGrade === 'all' ? 'bg-slate-800 text-emerald-400' : 'bg-white text-slate-500'"
                                  class="px-1.5 py-0.2 rounded-full text-[10px]"
                                  x-text="classItems.length">
                            </span>
                        </button>

                        @foreach($availableGrades as $gradeItem)
                            @php
                                $countGrade = collect($classesWithModules)->where('grade', $gradeItem)->count();
                            @endphp
                            <button type="button"
                                    @click="selectedGrade = '{{ $gradeItem }}'"
                                    :class="selectedGrade === '{{ $gradeItem }}' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5">
                                <span>Kelas {{ $gradeItem }}</span>
                                <span :class="selectedGrade === '{{ $gradeItem }}' ? 'bg-emerald-700 text-white' : 'bg-white text-slate-500 border border-slate-200'"
                                      class="px-1.5 py-0.2 rounded-full text-[10px]">
                                    {{ $countGrade }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Status Counter & Reset Filter Kelas --}}
                <div class="flex items-center justify-between gap-3 px-1">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <span>🏫</span>
                            <span>Kelas yang Anda Ikuti</span>
                        </h2>
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600"
                              x-text="'Menampilkan ' + totalVisibleClasses + ' dari ' + classItems.length + ' kelas'">
                        </span>
                    </div>

                    <div x-show="hasActiveFilters" x-cloak>
                        <button type="button"
                                @click="resetFilters()"
                                class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline">
                            <span>✕ Reset Filter</span>
                        </button>
                    </div>
                </div>

                {{-- Grid Card Kelas Siswa --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classesWithModules as $classIndex => $classItem)
                        <div x-show="matchesClass(classItems[{{ $classIndex }}])"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="group bg-white rounded-3xl border border-slate-200/90 hover:border-emerald-400 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                            
                            <div class="p-6 sm:p-7 space-y-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center font-black text-2xl group-hover:scale-105 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-2xs">
                                        🏫
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-black bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                            KODE: {{ $classItem['code'] }}
                                        </span>
                                        <button type="button"
                                                @click="leaveClassTarget = { id: {{ $classItem['id'] }}, name: '{{ addslashes($classItem['full_name']) }}' }; leaveClassModalOpen = true"
                                                title="Keluar dari Rombel Kelas"
                                                class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-transparent hover:border-rose-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors tracking-tight">
                                        {{ $classItem['full_name'] }}
                                    </h3>
                                    <p class="text-xs font-semibold text-emerald-600">
                                        {{ $classItem['major_name'] }}
                                    </p>
                                </div>

                                <div class="space-y-2 pt-1">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-500">Progres Belajar Kelas:</span>
                                        <span class="text-slate-900">{{ $classItem['completed_count'] }}/{{ $classItem['modules_count'] }} Modul ({{ $classItem['avg_progress'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $classItem['avg_progress'] }}%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5 font-medium">
                                        <span>Total Modul: <strong class="text-slate-700">{{ $classItem['modules_count'] }}</strong></span>
                                        <span>Tuntas: <strong class="text-emerald-600">{{ $classItem['completed_count'] }}</strong></span>
                                    </div>
                                    @php
                                        $activeInThisClass = $classItem['modules']->where('is_active_in_class', true)->count();
                                    @endphp
                                    @if($activeInThisClass > 0)
                                        <div class="pt-1">
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200 shadow-2xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                <span>{{ $activeInThisClass }} Modul Sedang Dibahas di Kelas</span>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <a href="{{ route('student.classes.show', $classItem['id']) }}"
                                   class="w-full py-3 px-4 rounded-2xl bg-slate-900 group-hover:bg-emerald-600 text-white font-extrabold text-xs transition-all shadow-md group-hover:shadow-lg group-hover:shadow-emerald-600/20 flex items-center justify-center gap-2">
                                    <span>Buka Kelas & Pelajari Modul</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach

                    {{-- Empty State Kelas --}}
                    <div x-show="totalVisibleClasses === 0"
                         x-cloak
                         class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center mx-auto text-3xl font-black">
                            🔍
                        </div>
                        <div class="max-w-md mx-auto space-y-1">
                            <h3 class="text-base font-extrabold text-slate-800">Tidak Ada Rombel Kelas yang Cocok</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Tidak ditemukan kelas dengan kata kunci pencarian Anda.
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

            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            {{-- ═══ VIEW 2: TAB SEDANG DIKERJAKAN (MODUL PEMBELAJARAN)             ═══ --}}
            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            <div x-show="activeTab === 'in_progress'" x-cloak class="space-y-6">

                {{-- Status Counter Bar --}}
                <div class="flex items-center justify-between gap-3 px-1">
                    <div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <span>⏳</span>
                            <span>Modul Sedang Dikerjakan</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar modul pembelajaran yang sedang aktif Anda pelajari secara mandiri.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-extrabold"
                              x-text="countVisibleModules('in_progress') + ' Modul'">
                        </span>
                        <div x-show="hasActiveFilters" x-cloak>
                            <button type="button"
                                    @click="resetFilters()"
                                    class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline ml-2">
                                ✕ Reset
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Grid Card Modul Sedang Dikerjakan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($inProgressModules as $mod)
                        <div x-show="matchesModule(moduleItems.find(m => m.id === {{ $mod['id'] }}), 'in_progress')"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="group bg-white rounded-3xl border {{ !empty($mod['is_active_in_class']) ? 'border-emerald-300 ring-2 ring-emerald-500/20 shadow-md shadow-emerald-500/10' : 'border-slate-200/90 hover:border-amber-400' }} shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                            
                            {{-- Top Banner jika modul sedang aktif dibahas di kelas oleh guru --}}
                            @if(!empty($mod['is_active_in_class']))
                                <div class="bg-emerald-50 border-b border-emerald-200/90 px-4 py-2.5 text-xs font-bold flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-600"></span>
                                        </span>
                                        <span class="uppercase tracking-wider text-[11px] font-black text-emerald-900">Sedang Dibahas di Kelas</span>
                                    </div>
                                    <span class="text-[10px] font-black bg-emerald-700 text-white px-2.5 py-0.5 rounded-full shadow-2xs">Materi Aktif</span>
                                </div>
                            @endif

                            <div class="p-6 space-y-4">
                                {{-- Top Header: Info Kelas, Info Mapel, Semester, & Status Badge --}}
                                <div class="flex flex-col gap-2.5">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        {{-- Info Kelas Badge --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                            <span>🏫</span>
                                            <span class="truncate max-w-[170px]">{{ $mod['class_name'] }}</span>
                                        </span>

                                        {{-- Semester Badge --}}
                                        @if(!empty($mod['semester_badge']))
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold border {{ $mod['semester_badge']['color'] }}">
                                                <span>{{ $mod['semester_badge']['icon'] }}</span>
                                                <span>{{ $mod['semester_badge']['short'] }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        {{-- Info Mapel Badge --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/70 truncate max-w-[200px]">
                                            <span>📚</span>
                                            <span class="truncate">{{ $mod['subject_name'] }}</span>
                                        </span>

                                        {{-- Status Progress Badge --}}
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $mod['progress_percent'] >= 100 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }} shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $mod['progress_percent'] >= 100 ? 'bg-emerald-500' : 'bg-amber-500 animate-pulse' }}"></span>
                                            <span>{{ $mod['progress_percent'] }}%</span>
                                        </span>
                                    </div>
                                </div>

                                {{-- Judul Modul & Deskripsi --}}
                                <div>
                                    <a href="{{ route('student.modules.show', $mod['id']) }}"
                                       class="text-base sm:text-lg font-black text-slate-900 group-hover:text-amber-700 transition-colors line-clamp-2 leading-snug">
                                        {{ $mod['title'] }}
                                    </a>
                                    @if(!empty($mod['description']))
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mt-1.5">
                                            {{ $mod['description'] }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Identitas Guru Pengampu --}}
                                <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <span class="text-sm">👨‍🏫</span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Guru Pengampu</p>
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $mod['teacher_name'] }}</p>
                                    </div>
                                </div>

                                {{-- Tag Komponen Pembelajaran --}}
                                <div class="space-y-1 pt-1">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Komponen Modul:</p>
                                    <div class="flex items-center gap-1 flex-wrap">
                                        @if($mod['has_pre_test'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Pre-test</span>@endif
                                        @if($mod['has_materi'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Materi</span>@endif
                                        @if($mod['has_video'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Video</span>@endif
                                        @if($mod['has_embed'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Praktik Embed</span>@endif
                                        @if($mod['has_job_sheet'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Job Sheet</span>@endif
                                        @if($mod['has_lkpd'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">LKPD</span>@endif
                                        @if($mod['has_post_test'])<span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">Post-test</span>@endif
                                    </div>
                                </div>

                                {{-- Progress Belajar Siswa --}}
                                <div class="space-y-1.5 pt-1">
                                    <div class="flex items-center justify-between text-[11px] font-bold">
                                        <span class="text-slate-500">Kemajuan Belajar:</span>
                                        <span class="{{ $mod['progress_percent'] >= 100 ? 'text-emerald-700 font-black' : 'text-amber-800' }}">{{ $mod['completed_tasks'] }}/{{ $mod['total_components'] }} Komponen ({{ $mod['progress_percent'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-amber-500 to-emerald-500 transition-all duration-500" style="width: {{ $mod['progress_percent'] }}%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Footer Action --}}
                            <div class="p-6 pt-0">
                                <a href="{{ route('student.modules.show', $mod['id']) }}"
                                   class="w-full py-3 px-4 rounded-2xl text-white font-extrabold text-xs transition-all shadow-md flex items-center justify-center gap-2 group-hover:scale-[1.01]
                                   {{ !empty($mod['is_active_in_class']) ? 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-emerald-600/25' : 'bg-amber-600 hover:bg-amber-500 shadow-amber-600/20' }}">
                                    <span>{{ !empty($mod['is_active_in_class']) && $mod['progress_percent'] == 0 ? 'Mulai Belajar (Sedang Dibahas di Kelas)' : ($mod['progress_percent'] >= 100 ? 'Buka & Pelajari Ulang Modul' : 'Lanjutkan Belajar Modul') }}</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        {{-- Fallback jika memang tidak ada modul in_progress di database --}}
                        <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center mx-auto text-3xl font-black">
                                ⏳
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Modul yang Sedang Dikerjakan</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Guru Anda belum mengaktifkan modul untuk dibahas di kelas atau Anda belum memulai aktivitas belajar.
                                </p>
                            </div>
                            <div>
                                <button type="button"
                                        @click="switchTab('classes')"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold transition-all shadow-sm">
                                    <span>Lihat Rombel Kelas Saya →</span>
                                </button>
                            </div>
                        </div>
                    @endforelse

                    {{-- Empty State jika hasil filter/pencarian in_progress nihil --}}
                    @if($inProgressModules->isNotEmpty())
                        <div x-show="countVisibleModules('in_progress') === 0"
                             x-cloak
                             class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center mx-auto text-3xl font-black">
                                🔍
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-extrabold text-slate-800">Tidak Ditemukan Modul Berjalan yang Cocok</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Tidak ada modul yang sedang dikerjakan yang cocok dengan kata kunci atau filter kelas/mapel yang dipilih.
                                </p>
                            </div>
                            <div>
                                <button type="button"
                                        @click="resetFilters()"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                                    <span>Reset Filter Pencarian</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            {{-- ═══ VIEW 3: TAB RIWAYAT SELESAI (MODUL PEMBELAJARAN)               ═══ --}}
            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            <div x-show="activeTab === 'completed'" x-cloak class="space-y-6">

                {{-- Toolbar Pencarian & Filter Khusus Modul Riwayat Selesai --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-sm space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        
                        {{-- Live Search Input --}}
                        <div class="relative flex-1 max-w-lg">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input type="text"
                                   x-model.debounce.150ms="searchQuery"
                                   placeholder="Cari judul modul selesai, mata pelajaran, kelas, atau nama guru..."
                                   class="w-full pl-10 pr-9 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-emerald-500 transition-all shadow-inner">
                            
                            <button type="button"
                                    x-show="searchQuery.length > 0"
                                    x-cloak
                                    @click="searchQuery = ''"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                <span class="w-5 h-5 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[10px] font-bold text-slate-600">✕</span>
                            </button>
                        </div>

                        {{-- Dropdown Filter Kelas & Mapel --}}
                        <div class="flex items-center gap-2.5 flex-wrap justify-between lg:justify-end">
                            <select x-model="selectedClassId"
                                    class="text-xs font-bold py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                                <option value="all">🏫 Semua Rombel Kelas</option>
                                @foreach($joinedClasses as $clsOption)
                                    <option value="{{ $clsOption->id }}">{{ $clsOption->full_name }}</option>
                                @endforeach
                            </select>

                            <select x-model="selectedSubjectId"
                                    class="text-xs font-bold py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                                <option value="all">📚 Semua Mata Pelajaran</option>
                                @foreach($subjects as $subjOption)
                                    <option value="{{ $subjOption['id'] }}">{{ $subjOption['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Status Counter Bar --}}
                <div class="flex items-center justify-between gap-3 px-1">
                    <div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <span>✓</span>
                            <span>Riwayat Modul Selesai</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar e-modul pembelajaran yang telah tuntas Anda selesaikan 100%.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-extrabold"
                              x-text="countVisibleModules('completed') + ' Modul Selesai'">
                        </span>
                        <div x-show="hasActiveFilters" x-cloak>
                            <button type="button"
                                    @click="resetFilters()"
                                    class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline ml-2">
                                ✕ Reset
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Grid Card Modul Riwayat Selesai --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($processedModules->where('progress_status', 'completed') as $mod)
                        <div x-show="matchesModule(moduleItems.find(m => m.id === {{ $mod['id'] }}), 'completed')"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="group bg-white rounded-3xl border {{ !empty($mod['is_active_in_class']) ? 'border-emerald-300 ring-2 ring-emerald-500/20 shadow-md shadow-emerald-500/10' : 'border-slate-200/90 hover:border-emerald-400' }} shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                            
                            @if(!empty($mod['is_active_in_class']))
                                <div class="bg-emerald-50 border-b border-emerald-200/90 px-4 py-2.5 text-xs font-bold flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-600"></span>
                                        </span>
                                        <span class="uppercase tracking-wider text-[11px] font-black text-emerald-900">Sedang Dibahas di Kelas</span>
                                    </div>
                                    <span class="text-[10px] font-black bg-emerald-700 text-white px-2.5 py-0.5 rounded-full shadow-2xs">Materi Aktif</span>
                                </div>
                            @endif

                            <div class="p-6 space-y-4">
                                {{-- Top Header: Info Kelas, Info Mapel, Semester, & Status Tuntas --}}
                                <div class="flex flex-col gap-2.5">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        {{-- Info Kelas Badge --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                            <span>🏫</span>
                                            <span class="truncate max-w-[170px]">{{ $mod['class_name'] }}</span>
                                        </span>

                                        {{-- Semester Badge --}}
                                        @if(!empty($mod['semester_badge']))
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold border {{ $mod['semester_badge']['color'] }}">
                                                <span>{{ $mod['semester_badge']['icon'] }}</span>
                                                <span>{{ $mod['semester_badge']['short'] }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        {{-- Info Mapel Badge --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/70 truncate max-w-[200px]">
                                            <span>📚</span>
                                            <span class="truncate">{{ $mod['subject_name'] }}</span>
                                        </span>

                                        {{-- Status Tuntas Badge --}}
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0">
                                            <span class="text-xs">✓</span>
                                            <span>Tuntas 100%</span>
                                        </span>
                                    </div>
                                </div>

                                {{-- Judul Modul & Deskripsi --}}
                                <div>
                                    <a href="{{ route('student.modules.show', $mod['id']) }}"
                                       class="text-base sm:text-lg font-black text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-snug">
                                        {{ $mod['title'] }}
                                    </a>
                                    @if(!empty($mod['description']))
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mt-1.5">
                                            {{ $mod['description'] }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Identitas Guru Pengampu --}}
                                <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <span class="text-sm">👨‍🏫</span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Guru Pengampu</p>
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $mod['teacher_name'] }}</p>
                                    </div>
                                </div>

                                {{-- Rekap Nilai Sumatif (Jika Dinilai) --}}
                                @if(!is_null($mod['summative_score']))
                                    <div class="flex items-center justify-between p-3 rounded-2xl bg-emerald-50 border border-emerald-200">
                                        <div class="flex items-center gap-2">
                                            <span class="text-base">🏅</span>
                                            <span class="text-xs font-extrabold text-emerald-900">Nilai Akhir Modul:</span>
                                        </div>
                                        <span class="text-base font-black text-emerald-700 bg-white px-2.5 py-0.5 rounded-xl border border-emerald-300 shadow-2xs">
                                            {{ $mod['summative_score'] }} / 100
                                        </span>
                                    </div>
                                @elseif($mod['grading_status'] === 'pending')
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 font-semibold">
                                        <span>⏳ Evaluasi Guru:</span>
                                        <span class="font-bold">Menunggu Penilaian</span>
                                    </div>
                                @endif

                                {{-- Progress Full 100% --}}
                                <div class="space-y-1.5 pt-1">
                                    <div class="flex items-center justify-between text-[11px] font-bold">
                                        <span class="text-slate-500">Seluruh Komponen Selesai:</span>
                                        <span class="text-emerald-700 font-extrabold">{{ $mod['completed_tasks'] }}/{{ $mod['total_components'] }} Komponen (100%)</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full bg-emerald-500" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Footer Action --}}
                            <div class="p-6 pt-0">
                                <a href="{{ route('student.modules.show', $mod['id']) }}"
                                   class="w-full py-3 px-4 rounded-2xl bg-slate-900 group-hover:bg-emerald-600 text-white font-extrabold text-xs transition-all shadow-md flex items-center justify-center gap-2">
                                    <span>Buka & Pelajari Ulang Modul</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        {{-- Fallback jika belum ada modul completed di database --}}
                        <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto text-3xl font-black">
                                🎓
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Riwayat Modul Selesai</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Selesaikan seluruh instrumen belajar pada modul yang Anda ikuti untuk melihat riwayat kelulusan dan nilai di sini.
                                </p>
                            </div>
                            <div>
                                <button type="button"
                                        @click="switchTab('in_progress')"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold transition-all shadow-sm">
                                    <span>Lihat Modul Sedang Dikerjakan →</span>
                                </button>
                            </div>
                        </div>
                    @endforelse

                    {{-- Empty State jika hasil filter/pencarian completed nihil --}}
                    @if($processedModules->where('progress_status', 'completed')->isNotEmpty())
                        <div x-show="countVisibleModules('completed') === 0"
                             x-cloak
                             class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-500 border border-emerald-200 flex items-center justify-center mx-auto text-3xl font-black">
                                🔍
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-extrabold text-slate-800">Tidak Ditemukan Modul Selesai yang Cocok</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Tidak ada riwayat modul selesai yang cocok dengan kata kunci atau filter yang Anda pilih.
                                </p>
                            </div>
                            <div>
                                <button type="button"
                                        @click="resetFilters()"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                                    <span>Reset Filter Pencarian</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            {{-- ═══ VIEW 4: TAB SEMUA MODUL                                        ═══ --}}
            {{-- ══════════════════════════════════════════════════════════════════════ --}}
            <div x-show="activeTab === 'all_modules'" x-cloak class="space-y-6">

                {{-- Toolbar Pencarian & Filter Khusus Semua Modul --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-sm space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        
                        <div class="relative flex-1 max-w-lg">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input type="text"
                                   x-model.debounce.150ms="searchQuery"
                                   placeholder="Cari judul modul, mata pelajaran, kelas, atau nama guru..."
                                   class="w-full pl-10 pr-9 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/15 focus:border-blue-500 transition-all shadow-inner">
                            
                            <button type="button"
                                    x-show="searchQuery.length > 0"
                                    x-cloak
                                    @click="searchQuery = ''"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                <span class="w-5 h-5 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[10px] font-bold text-slate-600">✕</span>
                            </button>
                        </div>

                        <div class="flex items-center gap-2.5 flex-wrap justify-between lg:justify-end">
                            {{-- Filter Status --}}
                            <select x-model="selectedStatus"
                                    class="text-xs font-bold py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                                <option value="all">📊 Semua Status Belajar</option>
                                <option value="in_progress">⏳ Sedang Dikerjakan</option>
                                <option value="completed">✓ Tuntas Selesai</option>
                                <option value="not_started">⚪ Belum Mulai</option>
                            </select>

                            {{-- Filter Kelas --}}
                            <select x-model="selectedClassId"
                                    class="text-xs font-bold py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                                <option value="all">🏫 Semua Rombel Kelas</option>
                                @foreach($joinedClasses as $clsOption)
                                    <option value="{{ $clsOption->id }}">{{ $clsOption->full_name }}</option>
                                @endforeach
                            </select>

                            {{-- Filter Mapel --}}
                            <select x-model="selectedSubjectId"
                                    class="text-xs font-bold py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                                <option value="all">📚 Semua Mata Pelajaran</option>
                                @foreach($subjects as $subjOption)
                                    <option value="{{ $subjOption['id'] }}">{{ $subjOption['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Status Counter Bar --}}
                <div class="flex items-center justify-between gap-3 px-1">
                    <div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <span>📚</span>
                            <span>Seluruh Modul Pembelajaran</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Katalog lengkap seluruh e-modul terbit dari kelas yang Anda ikuti.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-xs font-extrabold"
                              x-text="countVisibleModules() + ' dari ' + moduleItems.length + ' Modul'">
                        </span>
                        <div x-show="hasActiveFilters" x-cloak>
                            <button type="button"
                                    @click="resetFilters()"
                                    class="text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline ml-2">
                                ✕ Reset
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Grid Card Semua Modul --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($processedModules as $mod)
                        <div x-show="matchesModule(moduleItems.find(m => m.id === {{ $mod['id'] }}))"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="group bg-white rounded-3xl border {{ !empty($mod['is_active_in_class']) ? 'border-emerald-300 ring-2 ring-emerald-500/20 shadow-md shadow-emerald-500/10' : 'border-slate-200/90 hover:border-blue-400' }} shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                            
                            @if(!empty($mod['is_active_in_class']))
                                <div class="bg-emerald-50 border-b border-emerald-200/90 px-4 py-2.5 text-xs font-bold flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-600"></span>
                                        </span>
                                        <span class="uppercase tracking-wider text-[11px] font-black text-emerald-900">Sedang Dibahas di Kelas</span>
                                    </div>
                                    <span class="text-[10px] font-black bg-emerald-700 text-white px-2.5 py-0.5 rounded-full shadow-2xs">Materi Aktif</span>
                                </div>
                            @endif

                            <div class="p-6 space-y-4">
                                <div class="flex flex-col gap-2.5">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                                            <span>🏫</span>
                                            <span class="truncate max-w-[170px]">{{ $mod['class_name'] }}</span>
                                        </span>

                                        @if(!empty($mod['semester_badge']))
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold border {{ $mod['semester_badge']['color'] }}">
                                                <span>{{ $mod['semester_badge']['icon'] }}</span>
                                                <span>{{ $mod['semester_badge']['short'] }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/70 truncate max-w-[200px]">
                                            <span>📚</span>
                                            <span class="truncate">{{ $mod['subject_name'] }}</span>
                                        </span>

                                        @if($mod['progress_status'] === 'completed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0">
                                                <span>✓ Tuntas</span>
                                            </span>
                                        @elseif($mod['progress_status'] === 'in_progress')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200 shrink-0">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                <span>{{ $mod['progress_percent'] }}%</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500 border border-slate-200 shrink-0">
                                                <span>Belum Mulai</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <a href="{{ route('student.modules.show', $mod['id']) }}"
                                       class="text-base sm:text-lg font-black text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-2 leading-snug">
                                        {{ $mod['title'] }}
                                    </a>
                                    @if(!empty($mod['description']))
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mt-1.5">
                                            {{ $mod['description'] }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <span class="text-sm">👨‍🏫</span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Guru Pengampu</p>
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $mod['teacher_name'] }}</p>
                                    </div>
                                </div>

                                <div class="space-y-1.5 pt-1">
                                    <div class="flex items-center justify-between text-[11px] font-bold">
                                        <span class="text-slate-500">Kemajuan Belajar:</span>
                                        <span class="text-slate-800">{{ $mod['completed_tasks'] }}/{{ $mod['total_components'] }} Komponen ({{ $mod['progress_percent'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $mod['progress_status'] === 'completed' ? 'bg-emerald-500' : ($mod['progress_status'] === 'in_progress' ? 'bg-amber-500' : 'bg-slate-300') }}"
                                             style="width: {{ $mod['progress_percent'] }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 pt-0">
                                <a href="{{ route('student.modules.show', $mod['id']) }}"
                                   class="w-full py-3 px-4 rounded-2xl text-white font-extrabold text-xs transition-all shadow-md flex items-center justify-center gap-2
                                   {{ $mod['progress_status'] === 'completed' ? 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/20' : ($mod['progress_status'] === 'in_progress' ? 'bg-amber-600 hover:bg-amber-500 shadow-amber-600/20' : 'bg-slate-900 hover:bg-blue-600') }}">
                                    <span>{{ $mod['progress_status'] === 'completed' ? 'Buka & Pelajari Ulang' : ($mod['progress_status'] === 'in_progress' ? 'Lanjutkan Belajar' : 'Mulai Belajar Modul') }}</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center mx-auto text-3xl font-black">
                                📚
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Modul yang Ditugaskan</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Guru Anda belum menerbitkan modul untuk kelas yang Anda ikuti.
                                </p>
                            </div>
                        </div>
                    @endforelse

                    @if($processedModules->isNotEmpty())
                        <div x-show="countVisibleModules() === 0"
                             x-cloak
                             class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 border border-blue-200 flex items-center justify-center mx-auto text-3xl font-black">
                                🔍
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-extrabold text-slate-800">Tidak Ada Modul yang Cocok</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Tidak ditemukan modul pembelajaran yang sesuai dengan kata kunci atau kriteria filter Anda.
                                </p>
                            </div>
                            <div>
                                <button type="button"
                                        @click="resetFilters()"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                                    <span>Reset Filter Pencarian</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    @endif

</div>

@endsection
