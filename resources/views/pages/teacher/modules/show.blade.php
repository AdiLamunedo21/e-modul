@extends('layouts.teacher.dashboardteacher')

@section('title', $module->title . ' — Detail Modul')
@section('page-title', 'Detail Modul')

@section('content')

{{-- ══ Breadcrumb ══ --}}
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('teacher.modules.index') }}" class="hover:text-blue-600 font-medium transition-colors">Manajer Modul</a>
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800 truncate max-w-md">{{ $module->title }}</span>
</nav>

{{-- ══ Flash Alert ══ --}}
@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ══ Header Modul & Overview ══ --}}
@php
    $badge = $module->statusLabel();
    $sections = $module->moduleSectionsSummary();
    $totalComponents = collect($sections)->sum('total_count');
    $totalActive = collect($sections)->sum('active_count');
    $activePercent = $totalComponents > 0 ? round(($totalActive / $totalComponents) * 100) : 0;
@endphp
<div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm p-6 sm:p-8 mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="space-y-3 flex-1">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide border {{ $badge['color'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $module->status === 'published' ? 'bg-emerald-500' : ($module->status === 'closed' ? 'bg-slate-400' : 'bg-amber-500') }}"></span>
                    {{ $badge['label'] }}
                </span>
                @if($module->schoolClass)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Kelas {{ $module->schoolClass->grade }} {{ $module->schoolClass->major_name }}
                    </span>
                @endif
                <span class="text-xs text-slate-400">
                    Dibuat {{ $module->created_at->format('d M Y') }} &bull; Terakhir diperbarui {{ $module->updated_at->diffForHumans() }}
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug tracking-tight">{{ $module->title }}</h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Struktur Kurikulum E-Modul Modular — Bagian 1 & 2 sejajar, Bagian 3 & 4 sejajar, serta Bagian 5 & Pusat Penilaian sejajar di bawahnya.
            </p>
        </div>

        {{-- Status Action Buttons --}}
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('teacher.grading.show', $module) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-2xl transition-all shadow-sm">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Grading Center
            </a>

            <form action="{{ route('teacher.modules.status', $module) }}" method="POST">
                @csrf @method('PATCH')
                @if($module->status === 'draft')
                    <input type="hidden" name="status" value="published">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-2xl shadow-lg shadow-emerald-600/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        Publish Modul
                    </button>
                @elseif($module->status === 'published')
                    <input type="hidden" name="status" value="closed">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-2xl transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25-2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Tutup Modul
                    </button>
                @else
                    <input type="hidden" name="status" value="draft">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold text-amber-800 bg-amber-50 border border-amber-300 hover:bg-amber-100 rounded-2xl transition-all shadow-sm">
                        Buka Kembali Draf
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- ══ Summary Progress & Quick Jump ══ --}}
    <div class="mt-6 pt-6 border-t border-slate-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 font-extrabold text-sm shrink-0">
                    📊
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Ringkasan Kesiapan 5 Bagian E-Modul</h3>
                    <p class="text-xs text-slate-500">
                        Total <span class="font-extrabold text-slate-800">{{ $totalActive }} dari {{ $totalComponents }} Komponen</span> Aktif ({{ $activePercent }}%)
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div class="w-48 bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ $activePercent }}%"></div>
                </div>
                <span class="text-xs font-black text-slate-700 w-10 text-right">{{ $activePercent }}%</span>
            </div>
        </div>

        {{-- Quick Jump Pills --}}
        <div class="flex flex-wrap items-center gap-2 pt-2">
            @foreach($sections as $secKey => $sec)
                <a href="#{{ $sec['id'] }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border transition-all hover:scale-102 hover:shadow-xs {{ $sec['badge_color'] }}">
                    <span class="w-2 h-2 rounded-full {{ $sec['active_count'] > 0 ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                    <span>{{ $sec['number'] }}. {{ $sec['title'] }}</span>
                    <span class="opacity-75 text-[10px]">({{ $sec['active_count'] }}/{{ $sec['total_count'] }})</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════════
     BARIS 1: BAGIAN 1 & BAGIAN 2 SEJAJAR (INFORMASI UMUM MODUL)
     1. Bagian Awal  <─── SEJAJAR ───>  2. Pendahuluan
     ══════════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider bg-indigo-50 text-indigo-900 border border-indigo-200/80 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
            Tahap 1: Bagian Awal & Pendahuluan (Informasi Umum)
        </span>
        <div class="h-px bg-slate-200 flex-1"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

        {{-- ── 1. BAGIAN AWAL ── --}}
        @php $sec1 = $sections['bagian_awal']; @endphp
        <div id="{{ $sec1['id'] }}" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-7 flex flex-col justify-between scroll-mt-24 h-full">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-indigo-600/20">
                            1
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 leading-tight">1. Bagian Awal</h2>
                            <p class="text-[11px] text-slate-400">Identitas & Pengantar (4 Komponen)</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $sec1['badge_color'] }} px-2.5 py-0.5 rounded-full border">
                        {{ $sec1['active_count'] }}/{{ $sec1['total_count'] }} Aktif
                    </span>
                </div>

                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    {{ $sec1['subtitle'] }}
                </p>

                {{-- Component List --}}
                <div class="space-y-2.5 mb-4">
                    @foreach($sec1['components'] as $key => $comp)
                        <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50/80 border border-slate-200/70 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl {{ $sec1['icon_bg'] }} flex items-center justify-center text-sm shrink-0 font-bold shadow-xs">
                                    {{ $comp['emoji'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-bold text-slate-900 truncate">{{ $comp['name'] }}</h3>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-100 shrink-0">
                                            {{ $comp['badge'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $comp['desc'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ $comp['edit_url'] }}"
                                   class="text-[11px] font-bold text-indigo-700 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 px-2.5 py-1 rounded-lg transition-colors">
                                    Edit
                                </a>
                                <form action="{{ $comp['toggle_url'] }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <button type="button"
                                            onclick="animateToggleAndSubmit(event, this)"
                                            aria-label="Toggle {{ $comp['name'] }}"
                                            title="{{ $comp['name'] }}: {{ $comp['is_active'] ? 'Aktif (Klik untuk Nonaktifkan)' : 'Nonaktif (Klik untuk Aktifkan)' }}"
                                            class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $comp['is_active'] ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                        <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                              style="transform: translateX({{ $comp['is_active'] ? '20px' : '0px' }});"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100">
                <a href="{{ $sec1['edit_all_url'] }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 rounded-xl transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                    {{ $sec1['edit_all_label'] }}
                </a>
            </div>
        </div>

        {{-- ── 2. PENDAHULUAN ── --}}
        @php $sec2 = $sections['pendahuluan']; @endphp
        <div id="{{ $sec2['id'] }}" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-7 flex flex-col justify-between scroll-mt-24 h-full">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-teal-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-teal-600/20">
                            2
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 leading-tight">2. Pendahuluan</h2>
                            <p class="text-[11px] text-slate-400">Capaian & Kerangka Konsep (3 Komponen)</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $sec2['badge_color'] }} px-2.5 py-0.5 rounded-full border">
                        {{ $sec2['active_count'] }}/{{ $sec2['total_count'] }} Aktif
                    </span>
                </div>

                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    {{ $sec2['subtitle'] }}
                </p>

                {{-- Component List --}}
                <div class="space-y-2.5 mb-4">
                    @foreach($sec2['components'] as $key => $comp)
                        <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50/80 border border-slate-200/70 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl {{ $sec2['icon_bg'] }} flex items-center justify-center text-sm shrink-0 font-bold shadow-xs">
                                    {{ $comp['emoji'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-bold text-slate-900 truncate">{{ $comp['name'] }}</h3>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-teal-50 text-teal-700 border border-teal-100 shrink-0">
                                            {{ $comp['badge'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $comp['desc'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ $comp['edit_url'] }}"
                                   class="text-[11px] font-bold text-teal-700 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 border border-teal-200/80 px-2.5 py-1 rounded-lg transition-colors">
                                    Edit
                                </a>
                                <form action="{{ $comp['toggle_url'] }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <button type="button"
                                            onclick="animateToggleAndSubmit(event, this)"
                                            aria-label="Toggle {{ $comp['name'] }}"
                                            title="{{ $comp['name'] }}: {{ $comp['is_active'] ? 'Aktif (Klik untuk Nonaktifkan)' : 'Nonaktif (Klik untuk Aktifkan)' }}"
                                            class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $comp['is_active'] ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                        <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                              style="transform: translateX({{ $comp['is_active'] ? '20px' : '0px' }});"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100">
                <a href="{{ $sec2['edit_all_url'] }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 rounded-xl transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                    {{ $sec2['edit_all_label'] }}
                </a>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════════
     BARIS 2: BAGIAN 3 & BAGIAN 4 SEJAJAR (PEMBELAJARAN INTI & LATIHAN)
     3. Kegiatan Belajar (Isi Materi)  <─── SEJAJAR ───>  4. Evaluasi & Latihan
     ══════════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider bg-blue-50 text-blue-900 border border-blue-200/80 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
            Tahap 2: Kegiatan Belajar & Evaluasi Latihan (Pembelajaran Inti)
        </span>
        <div class="h-px bg-slate-200 flex-1"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

        {{-- ── 3. KEGIATAN BELAJAR (ISI MATERI) ── --}}
        @php $sec3 = $sections['kegiatan_belajar']; @endphp
        <div id="{{ $sec3['id'] }}" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-7 flex flex-col justify-between scroll-mt-24 h-full">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-blue-600/20">
                            3
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 leading-tight">3. Kegiatan Belajar (Isi Materi)</h2>
                            <p class="text-[11px] text-slate-400">Materi, Slide PPT, Video & Job Sheet (3 Komponen)</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $sec3['badge_color'] }} px-2.5 py-0.5 rounded-full border">
                        {{ $sec3['active_count'] }}/{{ $sec3['total_count'] }} Aktif
                    </span>
                </div>

                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    {{ $sec3['subtitle'] }}
                </p>

                {{-- Component List --}}
                <div class="space-y-2.5 mb-4">
                    @foreach($sec3['components'] as $key => $comp)
                        <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50/80 border border-slate-200/70 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl {{ $sec3['icon_bg'] }} flex items-center justify-center text-sm shrink-0 font-bold shadow-xs">
                                    {{ $comp['emoji'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-bold text-slate-900 truncate">{{ $comp['name'] }}</h3>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 shrink-0">
                                            {{ $comp['badge'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $comp['desc'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if(!empty($comp['preview_url']))
                                    <a href="{{ $comp['preview_url'] }}" target="_blank"
                                       class="text-[11px] font-bold text-slate-600 hover:text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg transition-colors inline-flex items-center gap-1"
                                       title="Lihat Pratinjau">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="hidden sm:inline">Preview</span>
                                    </a>
                                @endif
                                <a href="{{ $comp['edit_url'] }}"
                                   class="text-[11px] font-bold text-blue-700 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 px-2.5 py-1 rounded-lg transition-colors">
                                    Edit
                                </a>
                                <form action="{{ $comp['toggle_url'] }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <button type="button"
                                            onclick="animateToggleAndSubmit(event, this)"
                                            aria-label="Toggle {{ $comp['name'] }}"
                                            title="{{ $comp['name'] }}: {{ $comp['is_active'] ? 'Aktif (Klik untuk Nonaktifkan)' : 'Nonaktif (Klik untuk Aktifkan)' }}"
                                            class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $comp['is_active'] ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                        <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                              style="transform: translateX({{ $comp['is_active'] ? '20px' : '0px' }});"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100">
                <a href="{{ $sec3['edit_all_url'] }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                    {{ $sec3['edit_all_label'] }}
                </a>
            </div>
        </div>

        {{-- ── 4. EVALUASI & LATIHAN ── --}}
        @php $sec4 = $sections['evaluasi_latihan']; @endphp
        <div id="{{ $sec4['id'] }}" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-7 flex flex-col justify-between scroll-mt-24 h-full">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-amber-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-amber-600/20">
                            4
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 leading-tight">4. Evaluasi & Latihan</h2>
                            <p class="text-[11px] text-slate-400">Pre-test, Game Kuis & LKPD (3 Komponen)</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $sec4['badge_color'] }} px-2.5 py-0.5 rounded-full border">
                        {{ $sec4['active_count'] }}/{{ $sec4['total_count'] }} Aktif
                    </span>
                </div>

                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    {{ $sec4['subtitle'] }}
                </p>

                {{-- Component List --}}
                <div class="space-y-2.5 mb-4">
                    @foreach($sec4['components'] as $key => $comp)
                        <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50/80 border border-slate-200/70 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl {{ $sec4['icon_bg'] }} flex items-center justify-center text-sm shrink-0 font-bold shadow-xs">
                                    {{ $comp['emoji'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-bold text-slate-900 truncate">{{ $comp['name'] }}</h3>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200/80 shrink-0">
                                            {{ $comp['badge'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $comp['desc'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if(!empty($comp['preview_url']))
                                    <a href="{{ $comp['preview_url'] }}" target="_blank"
                                       class="text-[11px] font-bold text-slate-600 hover:text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg transition-colors inline-flex items-center gap-1"
                                       title="Lihat Pratinjau">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="hidden sm:inline">Preview</span>
                                    </a>
                                @endif
                                <a href="{{ $comp['edit_url'] }}"
                                   class="text-[11px] font-bold text-amber-800 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 px-2.5 py-1 rounded-lg transition-colors">
                                    Edit
                                </a>
                                <form action="{{ $comp['toggle_url'] }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <button type="button"
                                            onclick="animateToggleAndSubmit(event, this)"
                                            aria-label="Toggle {{ $comp['name'] }}"
                                            title="{{ $comp['name'] }}: {{ $comp['is_active'] ? 'Aktif (Klik untuk Nonaktifkan)' : 'Nonaktif (Klik untuk Aktifkan)' }}"
                                            class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $comp['is_active'] ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                        <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                              style="transform: translateX({{ $comp['is_active'] ? '20px' : '0px' }});"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100">
                <a href="{{ $sec4['edit_all_url'] }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 hover:bg-amber-100 rounded-xl transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    {{ $sec4['edit_all_label'] }}
                </a>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════════
     BARIS 3: BAGIAN 5 & PUSAT PENILAIAN SEJAJAR (PENUTUPAN & GRADING CENTER)
     5. Bagian Akhir  <─── SEJAJAR ───>  Pusat Pintasan & Grading Center Hub
     ══════════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider bg-rose-50 text-rose-900 border border-rose-200/80 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-rose-600"></span>
            Tahap 3: Bagian Akhir & Pusat Penilaian (Evaluasi Akhir & Grading)
        </span>
        <div class="h-px bg-slate-200 flex-1"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

        {{-- ── 5. BAGIAN AKHIR ── --}}
        @php $sec5 = $sections['bagian_akhir']; @endphp
        <div id="{{ $sec5['id'] }}" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 p-6 sm:p-7 flex flex-col justify-between scroll-mt-24 h-full">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-rose-600/20">
                            5
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 leading-tight">5. Bagian Akhir</h2>
                            <p class="text-[11px] text-slate-400">Post-test & Daftar Pustaka (2 Komponen)</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider {{ $sec5['badge_color'] }} px-2.5 py-0.5 rounded-full border">
                        {{ $sec5['active_count'] }}/{{ $sec5['total_count'] }} Aktif
                    </span>
                </div>

                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    {{ $sec5['subtitle'] }}
                </p>

                {{-- Component List --}}
                <div class="space-y-2.5 mb-4">
                    @foreach($sec5['components'] as $key => $comp)
                        <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50/80 border border-slate-200/70 hover:bg-slate-100/70 transition-colors">
                            <div class="flex items-start gap-3 min-w-0">
                                <span class="w-8 h-8 rounded-xl {{ $sec5['icon_bg'] }} flex items-center justify-center text-sm shrink-0 font-bold shadow-xs">
                                    {{ $comp['emoji'] }}
                                </span>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs font-bold text-slate-900 truncate">{{ $comp['name'] }}</h3>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-100 shrink-0">
                                            {{ $comp['badge'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $comp['desc'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if(!empty($comp['preview_url']))
                                    <a href="{{ $comp['preview_url'] }}" target="_blank"
                                       class="text-[11px] font-bold text-slate-600 hover:text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg transition-colors inline-flex items-center gap-1"
                                       title="Lihat Pratinjau">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="hidden sm:inline">Preview</span>
                                    </a>
                                @endif
                                <a href="{{ $comp['edit_url'] }}"
                                   class="text-[11px] font-bold text-rose-700 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 px-2.5 py-1 rounded-lg transition-colors">
                                    Edit
                                </a>
                                <form action="{{ $comp['toggle_url'] }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <button type="button"
                                            onclick="animateToggleAndSubmit(event, this)"
                                            aria-label="Toggle {{ $comp['name'] }}"
                                            title="{{ $comp['name'] }}: {{ $comp['is_active'] ? 'Aktif (Klik untuk Nonaktifkan)' : 'Nonaktif (Klik untuk Aktifkan)' }}"
                                            class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $comp['is_active'] ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                        <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                              style="transform: translateX({{ $comp['is_active'] ? '20px' : '0px' }});"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100">
                <a href="{{ $sec5['edit_all_url'] }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 rounded-xl transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $sec5['edit_all_label'] }}
                </a>
            </div>
        </div>

        {{-- ── 6. PUSAT PINTASAN & MONITORING PENILAIAN ── --}}
        <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white shadow-lg p-6 sm:p-7 flex flex-col justify-between scroll-mt-24 h-full">
            <div>
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-white/10">
                    <div class="w-9 h-9 rounded-2xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center text-blue-300 font-black text-sm shrink-0">
                        ⚡
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white">Grading Center & Aksi</h3>
                        <p class="text-xs text-slate-300">Pusat evaluasi & monitoring nilai siswa</p>
                    </div>
                </div>

                <p class="text-xs text-slate-300 mb-5 leading-relaxed">
                    Grading Center secara adaptif menampilkan kolom penilaian otomatis untuk seluruh komponen evaluasi yang Anda aktifkan pada modul ini.
                </p>

                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-[11px] text-slate-400 block font-medium">Status Modul</span>
                        <span class="text-sm font-extrabold text-white">{{ $badge['label'] }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-[11px] text-slate-400 block font-medium">Komponen Aktif</span>
                        <span class="text-sm font-extrabold text-emerald-400">{{ $totalActive }} / {{ $totalComponents }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-auto pt-4 border-t border-white/10 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('teacher.grading.show', $module) }}"
                   class="inline-flex items-center justify-center gap-2 flex-1 py-3 text-xs font-bold text-slate-900 bg-emerald-400 hover:bg-emerald-300 rounded-xl transition-all shadow-md shadow-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Buka Matriks Nilai
                </a>
                <a href="{{ route('teacher.modules.informasi-umum.edit', $module) }}"
                   class="inline-flex items-center justify-center gap-2 py-3 px-4 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition-all">
                    Form Lengkap
                </a>
            </div>
        </div>

    </div>
</div>

{{-- ══ Footer Actions ══ --}}
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-slate-200/80">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-2xl transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Kembali ke Manajer Modul
    </a>
    <div class="flex items-center gap-3">
        <a href="{{ route('teacher.modules.informasi-umum.edit', $module) }}"
           class="inline-flex items-center gap-2 px-5 py-3 text-xs sm:text-sm font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 rounded-2xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
            Editor Informasi Umum
        </a>
        <a href="{{ route('teacher.grading.show', $module) }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-2xl shadow-lg shadow-blue-600/25 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Grading Center Modul Ini
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function animateToggleAndSubmit(event, button) {
        event.preventDefault();
        const form = button.closest('form');
        const thumb = button.querySelector('span');
        const isCurrentlyActive = button.classList.contains('bg-emerald-500');

        if (isCurrentlyActive) {
            button.classList.remove('bg-emerald-500', 'border-emerald-600');
            button.classList.add('bg-slate-200', 'border-slate-400');
            if (thumb) thumb.style.transform = 'translateX(0px)';
        } else {
            button.classList.remove('bg-slate-200', 'border-slate-400');
            button.classList.add('bg-emerald-500', 'border-emerald-600');
            if (thumb) thumb.style.transform = 'translateX(20px)';
        }

        setTimeout(() => {
            form.submit();
        }, 220);
    }
</script>
@endpush
