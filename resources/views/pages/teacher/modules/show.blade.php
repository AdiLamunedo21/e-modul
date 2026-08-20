@extends('layouts.teacher.dashboardteacher')

@section('title', $module->title . ' — Detail Modul')
@section('page-title', 'Detail Modul')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('teacher.modules.index') }}" class="hover:text-blue-600 font-medium transition-colors">Manajer Modul</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800 truncate max-w-md">{{ $module->title }}</span>
</nav>

{{-- Flash --}}
@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ══ Header Modul ══ --}}
@php
    $badge = $module->statusLabel();
    $comps = $module->activeComponents();
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
                Sistem E-Modul Modular & Terbagi Halaman — Konfigurasi 3 Babak pembelajaran Anda di bawah ini.
            </p>
        </div>

        {{-- Status Action Buttons --}}
        <div class="flex items-center gap-3 shrink-0">
            <form action="{{ route('teacher.modules.status', $module) }}" method="POST">
                @csrf @method('PATCH')
                @if($module->status === 'draft')
                    <input type="hidden" name="status" value="published">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-3 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-2xl shadow-lg shadow-emerald-600/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        Publish Modul
                    </button>
                @elseif($module->status === 'published')
                    <input type="hidden" name="status" value="closed">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-3 text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-2xl transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25-2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Tutup Modul
                    </button>
                @else
                    <input type="hidden" name="status" value="draft">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-3 text-sm font-bold text-amber-800 bg-amber-50 border border-amber-300 hover:bg-amber-100 rounded-2xl transition-all shadow-sm">
                        Buka Kembali Draf
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- ══ Grid 3 Babak Modul (Layout 3 Kolom: Informasi Umum (Kiri) | Bagian Inti (Tengah) | Bagian Akhir (Kanan)) ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">

    {{-- ── 1. INFORMASI UMUM (Kiri — Mandatori) ── --}}
    <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-black text-sm shrink-0">
                        1
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 leading-tight">Informasi Umum</h2>
                        <p class="text-[11px] text-slate-400">Pendahuluan</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-700 px-2.5 py-0.5 rounded-full border border-rose-200">
                    Mandatori
                </span>
            </div>

            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                7 elemen pembuka pembelajaran yang wajib dilengkapi guru:
            </p>

            <ul class="space-y-2.5 mb-6">
                @foreach([
                    'Halaman Cover (Gambar)',
                    'Kata Pengantar',
                    'Daftar Isi (Hyperlink)',
                    'Peta Konsep',
                    'Glosarium (Istilah Teknis)',
                    'Petunjuk Penggunaan',
                    'Tujuan Pembelajaran',
                ] as $item)
                    @php $filled = !empty($module->bagian_awal_data); @endphp
                    <li class="flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                        <svg class="w-4 h-4 shrink-0 {{ $filled ? 'text-emerald-500' : 'text-slate-300' }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $filled ? 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' }}"/>
                        </svg>
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <a href="{{ route('teacher.modules.informasi-umum.edit', $module) }}"
               class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                Edit Informasi Umum
            </a>
        </div>
    </div>

    {{-- ── 2. BAGIAN INTI (Tengah — 7 Toggle Opsional) ── --}}
    <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-blue-600/20">
                        2
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 leading-tight">Bagian Inti</h2>
                        <p class="text-[11px] text-slate-400">Kegiatan Belajar</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full border border-blue-200">
                    {{ count($comps) }}/7 Aktif
                </span>
            </div>

            <p class="text-xs text-slate-500 mb-3 leading-relaxed">
                7 komponen opsional yang aktif di sisi alur belajar siswa:
            </p>

            {{-- 7 Interactive Component Rows --}}
            <div class="space-y-2 mb-4">

                {{-- 1. Pre-test --}}
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs shrink-0 font-bold">⚡</span>
                        <span class="text-xs font-bold text-slate-800 truncate">1. Pre-test</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.pre-test.edit', $module) }}"
                           class="text-[11px] font-bold text-blue-700 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('teacher.modules.pre-test.toggle', $module) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <button type="button"
                                    onclick="animateToggleAndSubmit(event, this)"
                                    aria-label="Toggle Pre-test"
                                    title="Pre-test: {{ $module->has_pre_test ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_pre_test ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                      style="transform: translateX({{ $module->has_pre_test ? '20px' : '0px' }});"></span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 2. Materi & PPT --}}
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs shrink-0 font-bold">📖</span>
                        <span class="text-xs font-bold text-slate-800 truncate">2. Materi & PPT</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.materi.edit', $module) }}"
                           class="text-[11px] font-bold text-indigo-700 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('teacher.modules.materi.toggle', $module) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <button type="button"
                                    onclick="animateToggleAndSubmit(event, this)"
                                    aria-label="Toggle Materi"
                                    title="Materi: {{ $module->has_materi ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_materi ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                      style="transform: translateX({{ $module->has_materi ? '20px' : '0px' }});"></span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 3. Video YouTube --}}
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-red-100 text-red-700 flex items-center justify-center text-xs shrink-0 font-bold">▶️</span>
                        <span class="text-xs font-bold text-slate-800 truncate">3. Video YouTube</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.video.edit', $module) }}"
                           class="text-[11px] font-bold text-red-700 hover:text-red-900 bg-red-50 hover:bg-red-100 border border-red-200/80 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('teacher.modules.video.toggle', $module) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <button type="button"
                                    onclick="animateToggleAndSubmit(event, this)"
                                    aria-label="Toggle Video"
                                    title="Video: {{ $module->has_video ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_video ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                      style="transform: translateX({{ $module->has_video ? '20px' : '0px' }});"></span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 4. Praktik Embed --}}
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs shrink-0 font-bold">💻</span>
                        <span class="text-xs font-bold text-slate-800 truncate">4. Praktik Embed</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.embed.edit', $module) }}"
                           class="text-[11px] font-bold text-cyan-700 hover:text-cyan-900 bg-cyan-50 hover:bg-cyan-100 border border-cyan-200/80 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('teacher.modules.embed.toggle', $module) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <button type="button"
                                    onclick="animateToggleAndSubmit(event, this)"
                                    aria-label="Toggle Praktik Embed"
                                    title="Praktik Embed: {{ $module->has_embed ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_embed ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                      style="transform: translateX({{ $module->has_embed ? '20px' : '0px' }});"></span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 5. Job Sheet PDF --}}
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs shrink-0 font-bold">📋</span>
                        <span class="text-xs font-bold text-slate-800 truncate">5. Job Sheet PDF</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.job-sheet.edit', $module) }}"
                           class="text-[11px] font-bold text-amber-800 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('teacher.modules.job-sheet.toggle', $module) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <button type="button"
                                    onclick="animateToggleAndSubmit(event, this)"
                                    aria-label="Toggle Job Sheet"
                                    title="Job Sheet: {{ $module->has_job_sheet ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_job_sheet ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                      style="transform: translateX({{ $module->has_job_sheet ? '20px' : '0px' }});"></span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 6. Tugas LKPD --}}
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-xs shrink-0 font-bold">👥</span>
                        <span class="text-xs font-bold text-slate-800 truncate">6. Tugas LKPD</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.lkpd.edit', $module) }}"
                           class="text-[11px] font-bold text-purple-700 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 border border-purple-200/80 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('teacher.modules.lkpd.toggle', $module) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <button type="button"
                                    onclick="animateToggleAndSubmit(event, this)"
                                    aria-label="Toggle LKPD"
                                    title="Tugas LKPD: {{ $module->has_lkpd ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_lkpd ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                      style="transform: translateX({{ $module->has_lkpd ? '20px' : '0px' }});"></span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 7. Post-test --}}
                <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/70 hover:bg-slate-100/60 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center text-xs shrink-0 font-bold">🎯</span>
                        <span class="text-xs font-bold text-slate-800 truncate">7. Post-test</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.post-test.edit', $module) }}"
                           class="text-[11px] font-bold text-teal-700 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 border border-teal-200/80 px-2.5 py-1 rounded-lg transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('teacher.modules.post-test.toggle', $module) }}" method="POST" class="inline-flex items-center">
                            @csrf
                            <button type="button"
                                    onclick="animateToggleAndSubmit(event, this)"
                                    aria-label="Toggle Post-test"
                                    title="Post-test: {{ $module->has_post_test ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                    class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_post_test ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                                <span class="pointer-events-none absolute top-[1px] left-[1px] h-4 w-4 rounded-full bg-white shadow-sm border border-slate-300 transition-transform duration-300 ease-in-out"
                                      style="transform: translateX({{ $module->has_post_test ? '20px' : '0px' }});"></span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="pt-2">
            <span class="text-[11px] text-slate-400 text-center block">
                Gunakan tombol sakelar untuk menyalakan/mematikan komponen secara instan.
            </span>
        </div>
    </div>

    {{-- ── 3. BAGIAN AKHIR (Kanan — Mandatori) ── --}}
    <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-7 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-sm shrink-0">
                        3
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 leading-tight">Bagian Akhir</h2>
                        <p class="text-[11px] text-slate-400">Evaluasi & Penutup</p>
                    </div>
                </div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-700 px-2.5 py-0.5 rounded-full border border-rose-200">
                    Mandatori
                </span>
            </div>

            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Instrumen tes sumatif dan kriteria kelulusan KKTP:
            </p>

            <ul class="space-y-2.5 mb-6">
                @foreach([
                    'Soal Evaluasi (Tes Sumatif)',
                    'Kunci Jawaban & Pedoman KKTP',
                    'Logika Rekomendasi Pengulangan',
                    'Daftar Pustaka & Referensi',
                ] as $item)
                    @php $filled = !empty($module->bagian_akhir_data); @endphp
                    <li class="flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                        <svg class="w-4 h-4 shrink-0 {{ $filled ? 'text-emerald-500' : 'text-slate-300' }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $filled ? 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' }}"/>
                        </svg>
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="pt-4 border-t border-slate-100">
            <a href="#"
               class="inline-flex items-center justify-center gap-2 w-full py-2.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                Edit Bagian Akhir
            </a>
        </div>
    </div>

</div>

{{-- ══ Footer Actions ══ --}}
<div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-200/80">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-2xl transition-all shadow-sm">
        ← Kembali ke Daftar Modul
    </a>
    <a href="{{ route('teacher.grading.index') }}"
       class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-2xl shadow-lg shadow-blue-600/25 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Grading Center Modul Ini
    </a>
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
