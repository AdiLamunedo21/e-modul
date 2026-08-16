@extends('layouts.student.dashboardstudent')

@section('title', 'Student Dashboard - Portal Belajar')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Tugas Aktif (To-Do)</h2>
        <p class="text-slate-500 text-sm mt-1">Selesaikan modul yang ditugaskan oleh guru Anda.</p>
    </div>
    <form action="{{ route('logout.student') }}" method="POST">
        @csrf
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
            Logout
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card Contoh Modul -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col transition hover:shadow-md">
        <div class="h-32 bg-emerald-500 flex items-center justify-center text-white relative">
            <span class="absolute top-3 right-3 bg-white/20 px-2 py-1 rounded text-xs font-semibold backdrop-blur-sm">Wajib</span>
            <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <div class="p-5 flex-1 flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Sistem Basis Data</h3>
            <p class="text-sm text-slate-500 mb-4">Guru: Pak Budi Santoso</p>
            <div class="mt-auto">
                <div class="flex justify-between text-xs text-slate-500 mb-1">
                    <span>Progress</span>
                    <span class="font-semibold text-emerald-600">0%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mb-4">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: 0%"></div>
                </div>
                <button class="w-full bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border border-emerald-100 font-semibold py-2 rounded-lg transition">
                    Mulai Belajar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
