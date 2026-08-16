@extends('layouts.admin.dashboardadmin')

@section('title', 'Dashboard Supervisi — Admin E-Modul')
@section('page-title', 'Dashboard Utama')

@section('content')

{{-- Page heading --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Dashboard Supervisi</h1>
    <p class="mt-1 text-sm text-gray-500">Pantau statistik utama, produktivitas pendidik, dan aktivitas sistem E-Modul.</p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">

    {{-- Total Guru --}}
    <div class="overflow-hidden rounded-xl bg-white border border-gray-200 shadow-sm">
        <div class="p-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg bg-indigo-50 p-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">Total Guru Aktif</dt>
                        <dd class="text-3xl font-extrabold text-gray-900">24</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3 sm:px-6">
            <div class="text-sm">
                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Lihat semua →</a>
            </div>
        </div>
    </div>

    {{-- Total Siswa --}}
    <div class="overflow-hidden rounded-xl bg-white border border-gray-200 shadow-sm">
        <div class="p-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg bg-sky-50 p-3">
                    <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">Total Siswa</dt>
                        <dd class="text-3xl font-extrabold text-gray-900">850</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3 sm:px-6">
            <div class="text-sm">
                <a href="#" class="font-medium text-sky-600 hover:text-sky-500">Tersebar di 24 Kelas →</a>
            </div>
        </div>
    </div>

    {{-- Total Modul --}}
    <div class="overflow-hidden rounded-xl bg-white border border-gray-200 shadow-sm">
        <div class="p-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg bg-emerald-50 p-3">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">E-Modul Dipublikasi</dt>
                        <dd class="text-3xl font-extrabold text-gray-900">128</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3 sm:px-6">
            <div class="text-sm">
                <a href="#" class="font-medium text-emerald-600 hover:text-emerald-500">+12 bulan ini →</a>
            </div>
        </div>
    </div>
</div>

{{-- Bottom section: Quick actions + Welcome --}}
<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

    {{-- Quick actions --}}
    <div class="rounded-xl bg-white border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-900">Akses Cepat Administrasi</h3>
        </div>
        <div class="divide-y divide-gray-100">
            <a href="#" class="flex items-center gap-4 px-5 py-4 sm:px-6 hover:bg-gray-50 transition-colors group">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600">Tambah Guru / Siswa Baru</p>
                    <p class="text-xs text-gray-500 mt-0.5">Registrasi akun pengguna ke Master Data</p>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-4 sm:px-6 hover:bg-gray-50 transition-colors group">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-emerald-600">Tinjau Modul Draft</p>
                    <p class="text-xs text-gray-500 mt-0.5">Quality Control kelayakan materi ajar</p>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
            <a href="#" class="flex items-center gap-4 px-5 py-4 sm:px-6 hover:bg-gray-50 transition-colors group">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-amber-600">Unduh Laporan Nilai (PDF)</p>
                    <p class="text-xs text-gray-500 mt-0.5">Rekap nilai kelas otomatis & adaptif</p>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Welcome card --}}
    <div class="rounded-xl bg-gradient-to-br from-indigo-600 to-indigo-700 shadow-sm p-6 sm:p-8 text-white flex flex-col justify-between relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-10 -left-10 w-28 h-28 rounded-full bg-white/5"></div>

        <div class="relative">
            <h3 class="text-xl font-bold">Selamat Datang, Admin 👋</h3>
            <p class="mt-3 text-indigo-100 text-sm leading-relaxed max-w-md">
                Sistem E-Modul SMKN 3 Yogyakarta memungkinkan Anda mengelola seluruh ekosistem pembelajaran digital secara terpusat, transparan, dan interaktif.
            </p>
        </div>

        <div class="mt-6 relative">
            <a href="#" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-indigo-600 shadow-sm hover:bg-indigo-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                Lihat Panduan Sistem
            </a>
        </div>
    </div>
</div>

@endsection
