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

                    {{-- Identitas Akun Siswa & Tombol Tambah Kelas --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
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

                        {{-- Tombol Tambah / Gabung Kelas Baru --}}
                        <button @click="joinModalOpen = true"
                                type="button"
                                title="Tambah atau Bergabung ke Rombel Kelas Baru dengan Kode"
                                class="px-4 py-3.5 sm:py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold transition-all shadow-lg shadow-emerald-950/30 flex items-center justify-center gap-2 border border-emerald-400/30 group shrink-0">
                            <svg class="w-4 h-4 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Tambah Kelas</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- ══ 2. RINGKASAN KPI BELAJAR SISWA (STATS CARDS) ══ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
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

        {{-- ══════════════════════════════════════════════════════════════════════════ --}}
        {{-- ══ 3. INTERACTIVE SEARCH & FILTER HUB UNTUK ROMBEL KELAS (ALPINE.JS) ══ --}}
        {{-- ══════════════════════════════════════════════════════════════════════════ --}}
        <div x-data="{
            searchQuery: '',
            selectedGrade: 'all',
            selectedStatus: 'all',
            items: {{ Js::from($classesWithModules) }},
            matches(item) {
                // Pencarian Teks Bebas (Nama Kelas, Kode Kelas, Jurusan, Guru)
                const query = this.searchQuery.toLowerCase().trim();
                const matchesQuery = !query || 
                    (item.full_name && item.full_name.toLowerCase().includes(query)) ||
                    (item.code && item.code.toLowerCase().includes(query)) ||
                    (item.major_name && item.major_name.toLowerCase().includes(query)) ||
                    (item.teacher_display && item.teacher_display.toLowerCase().includes(query));

                // Filter Tingkat Kelas (X, XI, XII)
                const matchesGrade = (this.selectedGrade === 'all') || (item.grade === this.selectedGrade);

                // Filter Status Kelulusan / Progres Kelas
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
            get totalVisible() {
                return this.items.filter(item => this.matches(item)).length;
            },
            get hasActiveFilters() {
                return this.searchQuery !== '' || this.selectedGrade !== 'all' || this.selectedStatus !== 'all';
            },
            resetFilters() {
                this.searchQuery = '';
                this.selectedGrade = 'all';
                this.selectedStatus = 'all';
            }
        }" class="space-y-6">

            {{-- ══ TOOLBAR PENCARIAN & FILTER CEPAT (EFISIEN, TANPA RELOAD) ══ --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-sm space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    
                    {{-- Input Live Search Bar --}}
                    <div class="relative flex-1 max-w-lg">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input type="text"
                               x-model.debounce.150ms="searchQuery"
                               placeholder="Cari kelas, jurusan, kode, atau nama guru..."
                               class="w-full pl-10 pr-9 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/15 focus:border-emerald-500 transition-all shadow-inner">
                        
                        {{-- Tombol Clear Input --}}
                        <button type="button"
                                x-show="searchQuery.length > 0"
                                x-cloak
                                @click="searchQuery = ''"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <span class="w-5 h-5 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[10px] font-bold text-slate-600">✕</span>
                        </button>
                    </div>

                    {{-- Actions: Quick Filter Chips & Tambah Kelas Button --}}
                    <div class="flex items-center gap-2.5 flex-wrap justify-between lg:justify-end">
                        
                        {{-- Chip Status Progres Dropdown / Select --}}
                        <div class="relative inline-flex items-center">
                            <select x-model="selectedStatus"
                                    class="text-xs font-bold py-2 px-3 pr-8 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                                <option value="all">📊 Semua Status</option>
                                <option value="in_progress">⏳ Sedang Berjalan</option>
                                <option value="completed">✓ Tuntas Selesai</option>
                            </select>
                        </div>

                        {{-- Tombol Quick Tambah Kelas Baru --}}
                        <button type="button"
                                @click="joinModalOpen = true"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/25 transition-all group">
                            <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>+ Masuk Kelas Baru</span>
                        </button>

                    </div>
                </div>

                {{-- Filter Chips Bar (Semua Kelas & Tingkat Jenjang) --}}
                <div class="flex items-center gap-2 pt-2 border-t border-slate-100 overflow-x-auto no-scrollbar py-0.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 shrink-0 mr-1">Tingkat:</span>
                    
                    {{-- Filter Semua Tingkat --}}
                    <button type="button"
                            @click="selectedGrade = 'all'"
                            :class="selectedGrade === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5">
                        <span>Semua Tingkat</span>
                        <span :class="selectedGrade === 'all' ? 'bg-slate-800 text-emerald-400' : 'bg-white text-slate-500'"
                              class="px-1.5 py-0.2 rounded-full text-[10px]"
                              x-text="items.length">
                        </span>
                    </button>

                    {{-- Dynamic Grade Filter Chips --}}
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

            {{-- ══ STATUS COUNTER & RESET FILTER ══ --}}
            <div class="flex items-center justify-between gap-3 px-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>🏫</span>
                        <span>Kelas yang Anda Ikuti</span>
                    </h2>
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600"
                          x-text="'Menampilkan ' + totalVisible + ' dari ' + items.length + ' kelas'">
                    </span>
                </div>

                {{-- Reset Filter Shortcut --}}
                <div x-show="hasActiveFilters" x-cloak>
                    <button type="button"
                            @click="resetFilters()"
                            class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline">
                        <span>✕ Reset Pencarian</span>
                    </button>
                </div>
            </div>

            {{-- ══ GRID CARD KELAS SISWA (DILENGKAPI LIVE FILTERING) ══ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @foreach($classesWithModules as $classIndex => $classItem)
                    <div x-show="matches(items[{{ $classIndex }}])"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="group bg-white rounded-3xl border border-slate-200/90 hover:border-emerald-400 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                        
                        {{-- Konten Utama Card Kelas --}}
                        <div class="p-6 sm:p-7 space-y-5">
                            {{-- Top Header: Icon, Code Pill, Active Status & Keluar Kelas Button --}}
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

                            {{-- Judul Kelas & Konsentrasi Keahlian --}}
                            <div class="space-y-1">
                                <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors tracking-tight">
                                    {{ $classItem['full_name'] }}
                                </h3>
                                <p class="text-xs font-semibold text-emerald-600">
                                    {{ $classItem['major_name'] }}
                                </p>
                            </div>

                            {{-- Guru Pengampu di Kelas Ini --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guru Pengampu</p>
                                <p class="text-xs font-bold text-slate-800 truncate" title="{{ $classItem['teacher_display'] }}">
                                    {{ $classItem['teacher_display'] }}
                                </p>
                            </div>

                            {{-- Progres & Ringkasan Modul Belajar --}}
                            <div class="space-y-2 pt-1">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-500">Progres Belajar Kelas:</span>
                                    <span class="text-slate-900">{{ $classItem['completed_count'] }}/{{ $classItem['modules_count'] }} Modul ({{ $classItem['avg_progress'] }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $classItem['avg_progress'] }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5 font-medium">
                                    <span>Total Modul Terbit: <strong class="text-slate-700">{{ $classItem['modules_count'] }}</strong></span>
                                    <span>Tuntas Selesai: <strong class="text-emerald-600">{{ $classItem['completed_count'] }}</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Button Footer Menuju Halaman Mapel Kelas --}}
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

                {{-- ══ EMPTY SEARCH STATE (JIKA HASIL PENCARIAN KOSONG) ══ --}}
                <div x-show="totalVisible === 0"
                     x-cloak
                     class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center mx-auto text-3xl font-black">
                        🔍
                    </div>
                    <div class="max-w-md mx-auto space-y-1">
                        <h3 class="text-base font-extrabold text-slate-800">Tidak Ada Rombel Kelas yang Cocok</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Tidak ditemukan kelas dengan filter atau kata kunci yang Anda masukkan.
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

    @endif

</div>

@endsection
