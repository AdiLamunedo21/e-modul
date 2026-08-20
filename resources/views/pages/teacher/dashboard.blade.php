@extends('layouts.teacher.dashboardteacher')

@section('title', 'Teacher Workspace — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Dashboard Workspace Guru')

@section('content')

{{-- ══ Header Workspace & Action ══ --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Teacher Workspace</h1>
        <p class="mt-1 text-sm text-slate-500 max-w-2xl">
            Kelola modul pembelajaran modular, pantau rasio pengumpulan tugas siswa secara <em>real-time</em>, dan lakukan penilaian adaptif di Grading Center.
        </p>
    </div>
    <div class="flex items-center gap-3">
        <a href="#" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 hover:shadow-blue-600/35 transition-all">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>+ Buat Modul Baru</span>
        </a>
    </div>
</div>

{{-- ══ Stat Cards (4 Cards Grid) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Card 1: Total Modul Saya --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total E-Modul Saya</span>
            <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-slate-900">6</span>
            <span class="text-xs font-semibold text-slate-500">Modul Ajar</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 4 Published
            </span>
            <span>•</span>
            <span class="text-amber-600 font-semibold">2 Draft</span>
        </div>
    </div>

    {{-- Card 2: Siswa Binaan --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Siswa Terhubung</span>
            <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-slate-900">72</span>
            <span class="text-xs font-semibold text-slate-500">Siswa Aktif</span>
        </div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-indigo-600 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21"/></svg>
            <span>Kelas XI RPL 1 & XI RPL 2</span>
        </div>
    </div>

    {{-- Card 3: Antrean Grading Center --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Perlu Dinilai (Grading)</span>
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-amber-600">18</span>
            <span class="text-xs font-semibold text-slate-500">Tugas Menunggu</span>
        </div>
        <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-amber-600">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
            <span>LKPD, Job Sheet & Screenshot</span>
        </div>
    </div>

    {{-- Card 4: Rata-rata Progres Pengumpulan --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rata-rata Kelulusan</span>
            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.25 2.25L15 6" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-slate-900">84.2%</span>
            <span class="text-xs font-semibold text-emerald-600">Di atas KKTP</span>
        </div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-slate-500">
            <span>Evaluasi Sumatif Aktif</span>
        </div>
    </div>
</div>

{{-- ══ Section: Manajer Modul Saya (Module Manager) ══ --}}
<div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm overflow-hidden mb-8">
    {{-- Header & Tabs --}}
    <div class="border-b border-slate-100 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span>Manajer E-Modul Saya</span>
                <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full">Portfolio Guru</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">Daftar modul yang telah dirakit dan didistribusikan ke kelas target.</p>
        </div>

        {{-- Filter Status Tabs --}}
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-semibold">
            <button class="px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-sm">Semua (6)</button>
            <button class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900">Terbit (4)</button>
            <button class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900">Draf (2)</button>
        </div>
    </div>

    {{-- Module Cards List --}}
    <div class="p-5 sm:p-6 space-y-4">

        {{-- Modul Item 1: Sistem Basis Data --}}
        <div class="p-5 rounded-2xl border border-slate-200/70 hover:border-blue-300 hover:bg-blue-50/20 transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- Modul Info --}}
                <div class="space-y-2 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-emerald-100 text-emerald-800 border border-emerald-200">
                            ● Published
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            Kelas XI RPL 1
                        </span>
                        <span class="text-xs text-slate-400">Diperbarui: 16 Agu 2026</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 hover:text-blue-600 transition-colors">
                        Sistem Basis Data: Konsep Relasi & Query SQL (DDL / DML)
                    </h3>
                    
                    {{-- 7 Toggle Bagian Inti yang Aktif (sesuai PRD) --}}
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        <span class="text-[11px] font-bold text-slate-500 mr-1">7 Komponen Inti Aktif:</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">1. Pre-test</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">2. Materi & PPT</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">3. Video YouTube</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">4. Praktik Embed</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">6. LKPD Kelompok</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">7. Post-test</span>
                    </div>
                </div>

                {{-- Progress Bar & Stats --}}
                <div class="w-full lg:w-72 bg-slate-50 p-4 rounded-xl border border-slate-200/60 shrink-0">
                    <div class="flex justify-between items-center text-xs font-semibold mb-2">
                        <span class="text-slate-600">Pengumpulan Siswa</span>
                        <span class="text-blue-600 font-bold">32 / 36 Siswa (89%)</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: 89%"></div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Pending Nilai: <strong class="text-amber-600">8 Siswa</strong></span>
                        <span class="text-emerald-600 font-bold">Selesai: 24</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 lg:flex-col lg:items-end shrink-0">
                    <a href="#" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Grading Center (8)</span>
                    </a>
                    <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        <span>Laporan PDF</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Modul Item 2: Pemrograman Web --}}
        <div class="p-5 rounded-2xl border border-slate-200/70 hover:border-blue-300 hover:bg-blue-50/20 transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- Modul Info --}}
                <div class="space-y-2 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-emerald-100 text-emerald-800 border border-emerald-200">
                            ● Published
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            Kelas XI RPL 2
                        </span>
                        <span class="text-xs text-slate-400">Diperbarui: 14 Agu 2026</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 hover:text-blue-600 transition-colors">
                        Pemrograman Web: RESTful API dengan Arsitektur Laravel
                    </h3>
                    
                    {{-- 7 Toggle Bagian Inti yang Aktif --}}
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        <span class="text-[11px] font-bold text-slate-500 mr-1">7 Komponen Inti Aktif:</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">2. Materi & PPT</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">3. Video YouTube</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">5. Job Sheet PDF</span>
                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md border border-indigo-100">7. Post-test</span>
                    </div>
                </div>

                {{-- Progress Bar & Stats --}}
                <div class="w-full lg:w-72 bg-slate-50 p-4 rounded-xl border border-slate-200/60 shrink-0">
                    <div class="flex justify-between items-center text-xs font-semibold mb-2">
                        <span class="text-slate-600">Pengumpulan Siswa</span>
                        <span class="text-blue-600 font-bold">24 / 36 Siswa (67%)</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: 67%"></div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Pending Nilai: <strong class="text-amber-600">10 Siswa</strong></span>
                        <span class="text-emerald-600 font-bold">Selesai: 14</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 lg:flex-col lg:items-end shrink-0">
                    <a href="#" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Grading Center (10)</span>
                    </a>
                    <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        <span>Laporan PDF</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Modul Item 3: Draf Modul --}}
        <div class="p-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50/50 transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-amber-100 text-amber-800 border border-amber-200">
                            ● Draft (Dalam Penyusunan)
                        </span>
                        <span class="text-xs text-slate-400">Target: Kelas XI RPL 1</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-700">
                        Object-Oriented Programming (OOP) Lanjutan dengan PHP 8.2
                    </h3>
                    <p class="text-xs text-slate-500">Tahap pengerjaan: Form Informasi Umum (Kata Pengantar, Glosarium) telah terisi.</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="#" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-blue-600 bg-white border border-blue-200 hover:bg-blue-50 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                        <span>Lanjutkan di Builder</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ Bottom Section: Grading Center Queue & E-Module Builder Guide ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Antrean Grading Center (2 Kolom) --}}
    <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200/80 shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>Antrean Penilaian Adaptif (Grading Center)</span>
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Tugas yang memerlukan penilaian manual guru (LKPD, Job Sheet, Screenshot Praktik, Ringkasan Video).</p>
            </div>
            <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700">Lihat Semua (18) →</a>
        </div>

        <div class="divide-y divide-slate-100">
            {{-- Submission 1: LKPD Siswa Bagas --}}
            <div class="py-3.5 flex items-center justify-between gap-3 group">
                <div class="flex items-center gap-3">
                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20" src="https://ui-avatars.com/api/?name=Bagas+Pratama&background=10b981&color=fff&bold=true" alt="Avatar Siswa">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-slate-800">Bagas Pratama</p>
                            <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded">XI RPL 1</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Modul: <span class="font-medium text-slate-700">Sistem Basis Data</span> • Tugas: <span class="font-semibold text-indigo-600">LKPD Diskusi Kelompok</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg">
                        Salinan PDF (2.4 MB)
                    </span>
                    <button class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all">
                        Beri Nilai
                    </button>
                </div>
            </div>

            {{-- Submission 2: Screenshot Praktik --}}
            <div class="py-3.5 flex items-center justify-between gap-3 group">
                <div class="flex items-center gap-3">
                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20" src="https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=6366f1&color=fff&bold=true" alt="Avatar Siswa">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-slate-800">Ahmad Fauzi</p>
                            <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded">XI RPL 2</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Modul: <span class="font-medium text-slate-700">Sistem Basis Data</span> • Tugas: <span class="font-semibold text-emerald-600">Screenshot Praktik Embed</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">
                        PNG (1.1 MB)
                    </span>
                    <button class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all">
                        Beri Nilai
                    </button>
                </div>
            </div>

            {{-- Submission 3: Job Sheet PDF --}}
            <div class="py-3.5 flex items-center justify-between gap-3 group">
                <div class="flex items-center gap-3">
                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20" src="https://ui-avatars.com/api/?name=Siti+Rahma&background=ec4899&color=fff&bold=true" alt="Avatar Siswa">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-slate-800">Siti Rahmawati</p>
                            <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded">XI RPL 1</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Modul: <span class="font-medium text-slate-700">Pemrograman Web</span> • Tugas: <span class="font-semibold text-rose-600">Lembar Job Sheet Mandiri</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg">
                        PDF (3.8 MB)
                    </span>
                    <button class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all">
                        Beri Nilai
                    </button>
                </div>
            </div>

            {{-- Submission 4: Ringkasan Video YouTube --}}
            <div class="py-3.5 flex items-center justify-between gap-3 group">
                <div class="flex items-center gap-3">
                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20" src="https://ui-avatars.com/api/?name=Dinda+Kirana&background=f59e0b&color=fff&bold=true" alt="Avatar Siswa">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-slate-800">Dinda Kirana</p>
                            <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded">XI RPL 1</span>
                        </div>
                        <p class="text-xs text-slate-500">
                            Modul: <span class="font-medium text-slate-700">Sistem Basis Data</span> • Tugas: <span class="font-semibold text-cyan-600">Ringkasan Video YouTube</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-lg">
                        342 Kata Teks
                    </span>
                    <button class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all">
                        Beri Nilai
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Banner Arsitektur E-Module Builder (1 Kolom) --}}
    <div class="rounded-2xl bg-gradient-to-br from-blue-700 to-indigo-800 p-6 text-white flex flex-col justify-between shadow-lg relative overflow-hidden">
        {{-- Background decorative circles --}}
        <div class="absolute -top-10 -right-10 w-44 h-44 rounded-full bg-white/10 blur-xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-blue-400/10 blur-lg"></div>

        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-[11px] font-bold tracking-wider uppercase backdrop-blur-md mb-4 border border-white/20">
                ⚡ Dynamic Builder
            </span>
            <h3 class="text-xl font-black leading-snug">
                Struktur E-Modul 3 Babak & 7 Sakelar Opsional
            </h3>
            <p class="mt-3 text-xs text-blue-100 leading-relaxed">
                Rakit modul pembelajaran modular dengan membagi materi ke dalam 3 bagian sistematis:
            </p>

            <ul class="mt-4 space-y-2 text-xs text-blue-50">
                <li class="flex items-start gap-2">
                    <span class="font-bold text-white bg-blue-500/40 rounded px-1.5 py-0.5">1</span>
                    <span><strong>Informasi Umum:</strong> Cover, Kata Pengantar, Glosarium & Capaian.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-bold text-white bg-blue-500/40 rounded px-1.5 py-0.5">2</span>
                    <span><strong>Bagian Inti:</strong> 7 Toggle bebas dihidupkan (Pre-test, Video, LKPD, dll).</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-bold text-white bg-blue-500/40 rounded px-1.5 py-0.5">3</span>
                    <span><strong>Bagian Akhir:</strong> Evaluasi Sumatif, Kunci Jawaban KKTP & PDF Report.</span>
                </li>
            </ul>
        </div>

        <div class="mt-6 pt-4 border-t border-white/15 relative z-10">
            <a href="#" class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-blue-700 shadow hover:bg-blue-50 transition-colors">
                <span>Rakit Modul dengan Builder</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>
</div>

@endsection
