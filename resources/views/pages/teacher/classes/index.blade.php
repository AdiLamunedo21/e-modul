@extends('layouts.teacher.dashboardteacher')
@section('title', 'Kelas Binaan — Teacher Workspace')
@section('page-title', 'Daftar Kelas Binaan')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-slate-900">Daftar Kelas Binaan</h1>
    <p class="mt-1 text-sm text-slate-500">Daftar kelas dan siswa yang berkaitan dengan modul yang Anda buat.</p>
</div>

<div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-10 sm:p-16 text-center">
    <div class="w-20 h-20 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-5">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
        </svg>
    </div>
    <h2 class="text-xl font-bold text-slate-800 mb-2">Daftar Siswa Segera Hadir</h2>
    <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
        Halaman ini akan menampilkan daftar kelas beserta siswa yang menerima modul Anda, lengkap dengan status progres tiap siswa.
    </p>
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all">
        ← Ke Manajer Modul
    </a>
</div>
@endsection
