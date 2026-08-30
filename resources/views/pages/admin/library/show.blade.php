@extends('layouts.admin.dashboardadmin')

@section('title', 'Supervisi Modul: ' . $module->title . ' — Library Modul')
@section('page-title', 'Pratinjau Library Modul')

@section('content')

@php
    $sections = $module->moduleSectionsSummary();
    $totalComponents = collect($sections)->sum('total_count');
    $totalActive = collect($sections)->sum('active_count');
    $activePercent = $totalComponents > 0 ? round(($totalActive / $totalComponents) * 100) : 0;
@endphp

<div>
    {{-- ══ 1. BREADCRUMB & TOP ACTIONS ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.library.index') }}" class="hover:text-indigo-600 transition-colors">Library Modul</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold truncate max-w-xs sm:max-w-md">{{ $module->title }}</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Inspeksi & Pratinjau Modul</span>
            </h1>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.library.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Library</span>
            </a>

            {{-- Moderasi Toggle Share --}}
            <form action="{{ route('admin.library.toggle-share', $module) }}" method="POST" onsubmit="return confirm('{{ $module->is_shared ? 'Tarik modul ini dari Library Publik?' : 'Publikasikan modul ini ke Library Publik?' }}');">
                @csrf
                @if($module->is_shared)
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span>Tarik dari Library Publik</span>
                    </button>
                @else
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Publikasikan ke Library</span>
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- ══ Flash Alerts ══ --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ══ 2. HERO MODULE OVERVIEW CARD ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 mb-8 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            
            <div class="space-y-3 flex-1">
                {{-- Badges row --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if($module->is_shared)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            🌐 Library Modul Bersama
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                            🔒 Nonaktif / Privat
                        </span>
                    @endif

                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $module->subject?->name ?? 'Mata Pelajaran' }} ({{ $module->subject?->code ?? '-' }})
                    </span>

                    @if($module->schoolClass)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            Kelas {{ $module->schoolClass->grade }} {{ $module->schoolClass->major_name }}
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-snug tracking-tight">
                    {{ $module->title }}
                </h1>

                {{-- Guru Penyusun & Asal Kloning --}}
                <div class="flex items-center gap-3 pt-1 text-xs text-slate-600 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center border border-indigo-100">
                            {{ strtoupper(substr($module->teacher->name ?? 'G', 0, 2)) }}
                        </div>
                        <span>Penyusun Asli: <strong class="text-slate-900">{{ $module->teacher->name ?? 'Guru Pendidik' }}</strong></span>
                    </div>

                    @if($module->clonedFrom)
                        <span class="text-slate-300">•</span>
                        <span class="text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 font-medium">
                            🌱 Diadaptasi dari karya: <strong>{{ $module->clonedFrom->teacher->name ?? 'Pendidik' }}</strong>
                        </span>
                    @endif

                    <span class="text-slate-300">•</span>
                    <span class="text-slate-400 font-mono">
                        Dibagikan: {{ $module->shared_at ? $module->shared_at->format('d F Y, H:i') : $module->created_at->format('d F Y') }}
                    </span>
                </div>
            </div>

            {{-- Stat Box Kloning --}}
            <div class="p-5 rounded-3xl bg-amber-50/60 border border-amber-200/80 text-center shrink-0 min-w-[200px]">
                <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Total Adopsi Guru</span>
                <div class="flex items-center justify-center gap-2 mt-1">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                    </svg>
                    <span class="text-3xl font-black text-amber-700">{{ $module->clone_count }}</span>
                    <span class="text-xs font-bold text-amber-700">Kali</span>
                </div>
                <p class="text-[11px] text-amber-700/80 mt-1">Direplikasi ke workspace guru lain</p>
            </div>

        </div>

        {{-- Progress bar kelengkapan instrumen --}}
        <div class="mt-6 pt-6 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                <span class="text-xs font-bold text-slate-700">
                    Kelengkapan 5 Bagian Kurikulum: <strong class="text-indigo-600">{{ $totalActive }} dari {{ $totalComponents }} Komponen Aktif ({{ $activePercent }}%)</strong>
                </span>
            </div>
            <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-indigo-500 via-blue-500 to-emerald-500 rounded-full transition-all duration-500" style="width: {{ $activePercent }}%"></div>
            </div>
        </div>
    </div>

    {{-- ══ 3. 5 BAGIAN STRUKTUR KURIKULUM ══ --}}
    <div class="space-y-6 mb-8">
        <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
            <span>📑</span>
            <span>Rincian 5 Bagian Kurikulum E-Modul</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- 1. Bagian Awal --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 font-black text-xs flex items-center justify-center border border-blue-100">1</span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Bagian Awal</h3>
                            <p class="text-[11px] text-slate-400">Komponen Pembuka Modul</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $sections['bagian_awal']['active_count'] }}/{{ $sections['bagian_awal']['total_count'] }} Aktif</span>
                </div>
                <ul class="space-y-2 text-xs">
                    @foreach($sections['bagian_awal']['components'] as $key => $c)
                        <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-blue-50/50 text-blue-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                            <span>{{ $c['name'] }}</span>
                            <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 2. Pendahuluan --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-black text-xs flex items-center justify-center border border-indigo-100">2</span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Pendahuluan</h3>
                            <p class="text-[11px] text-slate-400">Orientasi & Asesmen Diagnostik</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $sections['pendahuluan']['active_count'] }}/{{ $sections['pendahuluan']['total_count'] }} Aktif</span>
                </div>
                <ul class="space-y-2 text-xs">
                    @foreach($sections['pendahuluan']['components'] as $key => $c)
                        <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-indigo-50/50 text-indigo-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                            <span>{{ $c['name'] }}</span>
                            <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 3. Kegiatan Belajar --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 font-black text-xs flex items-center justify-center border border-amber-100">3</span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Kegiatan Belajar (Materi)</h3>
                            <p class="text-[11px] text-slate-400">Konten Teks, PPT, & Multimedia Video</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $sections['kegiatan_belajar']['active_count'] }}/{{ $sections['kegiatan_belajar']['total_count'] }} Aktif</span>
                </div>
                <ul class="space-y-2 text-xs">
                    @foreach($sections['kegiatan_belajar']['components'] as $key => $c)
                        <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-amber-50/50 text-amber-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                            <span>{{ $c['name'] }}</span>
                            <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 4. Evaluasi & Praktik --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 font-black text-xs flex items-center justify-center border border-purple-100">4</span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Evaluasi & Praktik</h3>
                            <p class="text-[11px] text-slate-400">Embed Praktik, Job Sheet, & LKPD</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $sections['evaluasi_latihan']['active_count'] }}/{{ $sections['evaluasi_latihan']['total_count'] }} Aktif</span>
                </div>
                <ul class="space-y-2 text-xs">
                    @foreach($sections['evaluasi_latihan']['components'] as $key => $c)
                        <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-purple-50/50 text-purple-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                            <span>{{ $c['name'] }}</span>
                            <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- 5. Bagian Akhir --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm md:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 font-black text-xs flex items-center justify-center border border-teal-100">5</span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Bagian Akhir</h3>
                            <p class="text-[11px] text-slate-400">Post-test Sumatif & Daftar Pustaka</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ $sections['bagian_akhir']['active_count'] }}/{{ $sections['bagian_akhir']['total_count'] }} Aktif</span>
                </div>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach($sections['bagian_akhir']['components'] as $key => $c)
                        <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-teal-50/50 text-teal-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                            <span>{{ $c['name'] }}</span>
                            <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- ══ 4. RIWAYAT ADOPSI / KLONING OLEH GURU LAIN ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm mb-8">
        <h3 class="text-base font-black text-slate-900 flex items-center gap-2 mb-4">
            <span>👥</span>
            <span>Daftar Guru Pengadopsi / Kloning Modul Ini ({{ $module->clones->count() }})</span>
        </h3>

        @if($module->clones->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Guru Pengadopsi</th>
                            <th class="py-3 px-4">Judul Modul Kloning</th>
                            <th class="py-3 px-4">Target Rombel Kelas</th>
                            <th class="py-3 px-4 text-right">Waktu Kloning</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($module->clones as $clone)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-[10px] flex items-center justify-center shrink-0 border border-indigo-100">
                                            {{ strtoupper(substr($clone->teacher->name ?? 'G', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800">{{ $clone->teacher->name ?? 'Guru' }}</span>
                                            <p class="text-[10px] text-slate-400 font-mono">{{ $clone->teacher->identity_number ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-700">
                                    {{ $clone->title }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $clone->schoolClass?->short_name ?? ($clone->schoolClass ? ($clone->schoolClass->grade . ' ' . $clone->schoolClass->major_name) : '-') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right text-slate-400 font-mono text-[11px]">
                                    {{ $clone->created_at ? $clone->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 rounded-2xl bg-slate-50 text-center text-slate-400 text-xs">
                Belum ada guru lain yang mengkloning modul ini ke workspace mereka.
            </div>
        @endif
    </div>

</div>

@endsection
