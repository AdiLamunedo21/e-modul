@extends('layouts.teacher.dashboardteacher')
@section('title', 'Laporan Nilai PDF — Teacher Workspace')
@section('page-title', 'Laporan Nilai PDF')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-slate-900">Laporan Nilai (PDF Generator)</h1>
    <p class="mt-1 text-sm text-slate-500">Unduh rekap nilai kelas dalam format PDF. Kolom otomatis menyesuaikan komponen modul yang diaktifkan.</p>
</div>

<div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-10 sm:p-16 text-center">
    <div class="w-20 h-20 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-5">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
        </svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800 mb-2">PDF Report Generator Segera Hadir</h2>
    <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
        Fitur ini akan mengagregasi nilai dari seluruh komponen aktif modul (Pre-test, Video, Praktik, Job Sheet, LKPD, Post-test, Sumatif) ke dalam satu laporan PDF siap cetak per kelas.
    </p>
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all">
        ← Ke Manajer Modul
    </a>
</div>
@endsection
