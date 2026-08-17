@extends('layouts.teacher.dashboardteacher')
@section('title', 'Grading Center — Teacher Workspace')
@section('page-title', 'Grading Center')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Grading Center</h1>
        <p class="mt-1 text-sm text-slate-500">Pusat penilaian adaptif. Kolom penilaian menyesuaikan komponen yang Anda aktifkan pada setiap modul.</p>
    </div>
</div>

<div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-10 sm:p-16 text-center">
    <div class="w-20 h-20 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-5">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
        </svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800 mb-2">Grading Center Segera Hadir</h2>
    <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
        Panel ini akan menampilkan antrean penilaian manual dari seluruh tugas siswa (LKPD, Job Sheet PDF, Screenshot Praktik, dan Ringkasan Video) yang menyesuaikan 7 komponen aktif pada setiap modul.
    </p>
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all">
        ← Ke Manajer Modul
    </a>
</div>
@endsection
