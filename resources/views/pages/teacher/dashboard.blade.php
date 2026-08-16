@extends('layouts.teacher.dashboardteacher')

@section('title', 'Teacher Dashboard - Workspace')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Teacher Workspace</h2>
        <p class="text-slate-500 text-sm mt-1">Kelola E-Modul dan evaluasi tugas siswa.</p>
    </div>
    <form action="{{ route('logout.teacher') }}" method="POST">
        @csrf
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
            Logout
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 text-center mt-12">
    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
    </div>
    <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Modul</h3>
    <p class="text-slate-500 mb-6">Anda belum membuat E-Modul apapun. Mulai rakit E-Modul pertama Anda dengan sistem Builder yang dinamis.</p>
    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition shadow-md">
        + Buat Modul Baru
    </button>
</div>
@endsection
