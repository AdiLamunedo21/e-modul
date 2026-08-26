@extends('layouts.teacher.dashboardteacher')

@section('title', $module->title . ' — Detail Modul')
@section('page-title', 'Detail Modul')

@section('content')

<div x-data="{ deleteModalOpen: false, deleteUrl: '', deleteTitle: '', editModalOpen: false }">

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
                @if($module->is_shared)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        🌐 Dibagikan di Library
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200">
                        🔒 Pribadi
                    </span>
                @endif
                @if($module->subject)
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $module->subject->badgeClasses() }}">
                        <span>{{ $module->subject->icon }}</span>
                        <span>{{ $module->subject->name }}</span>
                    </span>
                @endif
                @if($module->schoolClass)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Kelas {{ $module->schoolClass->grade }} {{ $module->schoolClass->major_name }}
                    </span>
                @endif
                <span class="text-xs text-slate-400">
                    Dibuat {{ $module->created_at->format('d M Y') }} &bull; Terakhir diperbarui {{ $module->updated_at->diffForHumans() }}
                </span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug tracking-tight">{{ $module->title }}</h1>
                <button type="button"
                        @click="editModalOpen = true"
                        class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all border border-transparent hover:border-blue-200"
                        title="Ubah Nama & Identitas Modul">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                </button>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Struktur Kurikulum E-Modul Modular — Bagian 1 & 2 sejajar, Bagian 3 & 4 sejajar, serta Bagian 5 & Pusat Penilaian sejajar di bawahnya.
            </p>
        </div>

        {{-- Status Action Buttons --}}
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            {{-- Edit Module Identity Button --}}
            <button type="button"
                    @click="editModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 rounded-2xl transition-all shadow-sm"
                    title="Ubah Nama & Identitas Modul">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                </svg>
                <span>Edit Nama Modul</span>
            </button>
            {{-- Share to Library Toggle --}}
            <form action="{{ route('teacher.modules.toggle-share', $module) }}" method="POST">
                @csrf
                @if($module->is_shared)
                    <button type="submit"
                            onclick="return confirm('Tarik modul ini dari Library Modul publik? (Guru lain tidak akan lagi melihat modul ini di katalog)')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 rounded-2xl transition-all shadow-sm"
                            title="Modul sedang dibagikan di Library sekolah">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                        <span>✓ Di Library ({{ $module->clone_count }}x Disalin)</span>
                    </button>
                @else
                    <button type="submit"
                            onclick="return confirm('Bagikan modul ini ke Library Modul sekolah agar rekan guru lain dapat melihat dan menyalin instrumen ini?')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 rounded-2xl transition-all shadow-sm"
                            title="Bagikan ke Library agar rekan guru bisa menyalin">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                        <span>Bagikan ke Library</span>
                    </button>
                @endif
            </form>
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

            {{-- Hapus Modul (Membuka Modal) --}}
            <button type="button"
                    @click="deleteModalOpen = true; deleteUrl = '{{ route('teacher.modules.destroy', $module) }}'; deleteTitle = '{{ addslashes($module->title) }}'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 rounded-2xl transition-all shadow-sm"
                    title="Hapus E-Modul Ini">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                <span>Hapus</span>
            </button>
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
                            <h2 class="text-base font-bold text-slate-900 leading-tight">Bagian Awal</h2>
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
                            <h2 class="text-base font-bold text-slate-900 leading-tight">Pendahuluan</h2>
                            <p class="text-[11px] text-slate-400">Capaian, Konsep & Pre-test (4 Komponen)</p>
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

            <div class="mt-auto pt-4 border-t border-slate-100 space-y-2">
                <a href="{{ $sec2['edit_all_url'] }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 rounded-xl transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                    {{ $sec2['edit_all_label'] }}
                </a>
                <a href="{{ route('teacher.modules.pre-test.edit', $module) }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z"/></svg>
                    Edit / Kelola Soal Pre-test
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
                            <h2 class="text-base font-bold text-slate-900 leading-tight">Kegiatan Belajar (Isi Materi)</h2>
                            <p class="text-[11px] text-slate-400">Materi PPT & Multimedia Video (2 Komponen)</p>
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
                <div class="space-y-2.5">
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
                            <h2 class="text-base font-bold text-slate-900 leading-tight">Evaluasi & Latihan</h2>
                            <p class="text-[11px] text-slate-400">Game Edukasi, Job Sheet & LKPD (3 Komponen)</p>
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
                <div class="space-y-2.5">
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
                            <h2 class="text-base font-bold text-slate-900 leading-tight">Bagian Akhir</h2>
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
                <div class="space-y-2.5">
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

            <div class="mt-auto pt-4 border-t border-white/10">
                <a href="{{ route('teacher.grading.show', $module) }}"
                   class="inline-flex items-center justify-center gap-2 w-full py-3 text-xs font-bold text-slate-900 bg-emerald-400 hover:bg-emerald-300 rounded-xl transition-all shadow-md shadow-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Buka Matriks Nilai
                </a>
            </div>
        </div>

    </div>
</div>

{{-- ══ Footer Actions ══ --}}
<div class="flex items-center justify-between gap-4 pt-6 border-t border-slate-200/80">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-2xl transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Kembali ke Manajer Modul
    </a>
</div>

{{-- ══ Modal Edit Nama & Identitas Modul ══ --}}
<div x-show="editModalOpen"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
    <div @click.away="editModalOpen = false"
         class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-lg w-full p-6 sm:p-7 relative">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                    ✏️
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Ubah Nama & Identitas Modul</h3>
                    <p class="text-xs text-slate-500">Perbarui judul, mata pelajaran, atau kelas sasaran</p>
                </div>
            </div>
            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('teacher.modules.update', $module) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="module_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Judul E-Modul <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="module_title"
                       value="{{ old('title', $module->title) }}"
                       required
                       class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold text-slate-800 transition-all">
            </div>

            <div>
                <label for="module_subject_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <select name="subject_id"
                        id="module_subject_id"
                        required
                        class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold text-slate-800 transition-all cursor-pointer">
                    @foreach($teacherSubjects ?? [] as $sub)
                        <option value="{{ $sub->id }}" {{ $sub->id == old('subject_id', $module->subject_id) ? 'selected' : '' }}>
                            {{ $sub->name }} ({{ $sub->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="module_class_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Target Kelas / Rombel <span class="text-red-500">*</span>
                </label>
                <select name="class_id"
                        id="module_class_id"
                        required
                        class="w-full px-4 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold text-slate-800 transition-all cursor-pointer">
                    @foreach($classes ?? [] as $cls)
                        <option value="{{ $cls->id }}" {{ $cls->id == old('class_id', $module->class_id) ? 'selected' : '' }}>
                            Kelas {{ $cls->grade }} - {{ $cls->major_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                <button type="button" @click="editModalOpen = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-lg shadow-blue-600/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Include Delete Confirmation Modal --}}
@include('pages.teacher.modules.partials.delete-modal')

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
