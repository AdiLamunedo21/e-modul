@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Praktik Interaktif (Embed) — ' . $module->title)
@section('page-title', 'Editor Praktik Interaktif (Komponen Inti)')

@push('head')
<style>
    .fade-in-item {
        animation: fadeInSlide .25s ease-out;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .sandbox-frame-wrapper {
        transition: max-width 0.3s ease;
    }
</style>
@endpush

@section('content')

{{-- ══ Breadcrumb ══ --}}
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('teacher.modules.index') }}" class="hover:text-blue-600 font-medium transition-colors">Manajer Modul</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <a href="{{ route('teacher.modules.show', $module) }}" class="hover:text-blue-600 transition-colors truncate max-w-[12rem]">{{ $module->title }}</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800">Komponen Inti: Praktik Embed</span>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800 shadow-sm">
    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800 shadow-sm">
    <p class="font-bold mb-2 flex items-center gap-2">
        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
        Terdapat {{ $errors->count() }} kesalahan input:
    </p>
    <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ══ Header Banner ══ --}}
<div class="bg-gradient-to-r from-cyan-800 via-teal-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-cyan-950/20 mb-8 relative overflow-hidden border border-cyan-700/40">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-cyan-200">
                    <span>💻</span>
                    <span>Komponen Inti — Komponen 4</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-400/20 text-amber-300 border border-amber-400/40 uppercase tracking-wider">
                    Opsional (Toggle)
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                Praktik Interaktif (Embed Code & Simulator)
            </h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Sematkan media interaktif atau simulator praktik langsung (HTML/CSS/JS, PhET, CodePen, W3Schools, Tinkercad). Siswa mengeksekusi simulasi dan mengunggah tangkapan layar (screenshot) sebagai bukti pengerjaan.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                ← Kembali ke Detail
            </a>
            <a href="{{ route('teacher.modules.embed.preview', $module) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2 border border-emerald-400/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pratinjau Siswa
            </a>
        </div>
    </div>
</div>

<form id="embed-form" action="{{ route('teacher.modules.embed.update', $module) }}" method="POST">
    @csrf
    @method('PATCH')

    {{-- ══ Main Layout Grid (Standard 4 Columns) ══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">

        {{-- ── LEFT 3 COLUMNS: Main Form ───────────────────────────────────────── --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- 1. Sakelar Utama & Identitas Praktik --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center text-lg shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Aktivasi Komponen Praktik Interaktif (Embed)</h2>
                            <p class="text-xs text-slate-500">Jika diaktifkan, halaman simulasi interaktif dan form unggah bukti screenshot akan disajikan pada Komponen Inti bagi siswa.</p>
                        </div>
                    </div>

                    {{-- Switch Toggle --}}
                    <label class="inline-flex items-center cursor-pointer select-none shrink-0 group">
                        <input type="checkbox" name="has_embed" id="has_embed_toggle" value="1"
                               class="sr-only"
                               {{ old('has_embed', $module->has_embed) ? 'checked' : '' }}
                               onchange="toggleEmbedStatus(this.checked)">
                        <div id="embed-toggle-track"
                             class="relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out {{ old('has_embed', $module->has_embed) ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                            <div id="embed-toggle-thumb"
                                 class="absolute top-[2px] left-[2px] h-5 w-5 rounded-full bg-white shadow-md border border-slate-300/90 transition-transform duration-300 ease-in-out"
                                 style="transform: translateX({{ old('has_embed', $module->has_embed) ? '20px' : '0px' }});">
                            </div>
                        </div>
                        <span id="toggle-status-badge" class="ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 {{ old('has_embed', $module->has_embed) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-300' }}">
                            {{ old('has_embed', $module->has_embed) ? 'Aktif (ON)' : 'Nonaktif (OFF)' }}
                        </span>
                    </label>
                </div>

                {{-- Fields Identitas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-6">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul Kegiatan Praktik Interaktif <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="embed_title"
                               id="embed_title"
                               value="{{ old('embed_title', $data['embed_title'] ?? 'Praktik Interaktif: ' . $module->title) }}"
                               placeholder="Contoh: Praktik Interaktif: Simulasi Rangkaian Gerbang Logika Digital"
                               class="w-full rounded-xl border @error('embed_title') border-rose-300 bg-rose-50/50 @else border-slate-300 bg-slate-50 @enderror px-4 py-2.5 text-sm text-slate-900 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all">
                        @error('embed_title')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Estimasi Waktu Praktik
                        </label>
                        <div class="flex rounded-xl border border-slate-300 bg-slate-50 overflow-hidden focus-within:border-cyan-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-cyan-500/20 transition-all shadow-sm">
                            <input type="number"
                                   name="estimated_duration"
                                   id="estimated_duration"
                                   min="1"
                                   max="300"
                                   value="{{ old('estimated_duration', $data['estimated_duration'] ?? 20) }}"
                                   class="w-full bg-transparent px-4 py-2.5 text-sm text-slate-900 focus:outline-none">
                            <span class="inline-flex items-center px-3.5 bg-slate-100 text-xs font-bold text-slate-500 border-l border-slate-200 select-none shrink-0">
                                Menit
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Perkiraan durasi simulasi & eksekusi.</p>
                    </div>
                </div>
            </div>

            {{-- 2. Editor Media Embed & Live Interactive Sandbox --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center text-lg shrink-0">
                            💻
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Media Embed & Kode Simulator Interaktif</h2>
                            <p class="text-xs text-slate-500">Pilih mode penyematan kode (HTML/CSS/Iframe) atau tautan URL simulator langsung.</p>
                        </div>
                    </div>

                    {{-- Mode Selector --}}
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/80 shrink-0">
                        <label class="cursor-pointer">
                            <input type="radio" name="embed_type" value="code" class="sr-only"
                                   {{ old('embed_type', $data['embed_type'] ?? 'code') === 'code' ? 'checked' : '' }}
                                   onchange="switchEmbedMode('code')">
                            <span id="tab-mode-code" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ old('embed_type', $data['embed_type'] ?? 'code') === 'code' ? 'bg-white text-cyan-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                <span>&lt;/&gt;</span> Kode Embed / HTML
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="embed_type" value="url" class="sr-only"
                                   {{ old('embed_type', $data['embed_type'] ?? 'code') === 'url' ? 'checked' : '' }}
                                   onchange="switchEmbedMode('url')">
                            <span id="tab-mode-url" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ old('embed_type', $data['embed_type'] ?? 'code') === 'url' ? 'bg-white text-cyan-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                <span>🔗</span> Tautan URL Web
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Template / Presets Gallery --}}
                <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <span>✨</span> Template & Contoh Simulator Siap Pakai (1-Click Insert)
                        </span>
                        <span class="text-[11px] text-slate-400">Klik untuk menyalin template ke editor</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <button type="button" onclick="loadTemplate('html_css')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-cyan-50 border border-slate-200 text-slate-700 hover:text-cyan-800 text-xs font-medium transition-all shadow-2xs flex items-center gap-1.5">
                            <span>🎨</span> HTML/CSS Sandbox
                        </button>
                        <button type="button" onclick="loadTemplate('sql_console')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-cyan-50 border border-slate-200 text-slate-700 hover:text-cyan-800 text-xs font-medium transition-all shadow-2xs flex items-center gap-1.5">
                            <span>🗄️</span> SQL Query Simulator
                        </button>
                        <button type="button" onclick="loadTemplate('phet_sim')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-cyan-50 border border-slate-200 text-slate-700 hover:text-cyan-800 text-xs font-medium transition-all shadow-2xs flex items-center gap-1.5">
                            <span>🔬</span> PhET Science Lab
                        </button>
                        <button type="button" onclick="loadTemplate('codepen')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-cyan-50 border border-slate-200 text-slate-700 hover:text-cyan-800 text-xs font-medium transition-all shadow-2xs flex items-center gap-1.5">
                            <span>✏️</span> CodePen Embed
                        </button>
                        <button type="button" onclick="loadTemplate('tinkercad')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-cyan-50 border border-slate-200 text-slate-700 hover:text-cyan-800 text-xs font-medium transition-all shadow-2xs flex items-center gap-1.5">
                            <span>⚡</span> Tinkercad / Circuit
                        </button>
                    </div>
                </div>

                {{-- Mode A: Code Textarea --}}
                <div id="section-embed-code" class="space-y-2 {{ old('embed_type', $data['embed_type'] ?? 'code') === 'url' ? 'hidden' : '' }}">
                    <div class="flex items-center justify-between">
                        <label for="embed_code" class="block text-xs font-bold text-slate-700">
                            Kode HTML, CSS, JavaScript, atau Tag &lt;iframe&gt; <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-slate-400">Mendukung Iframe responsif dan skrip interaktif mandiri</span>
                    </div>
                    <div class="rounded-2xl overflow-hidden border @error('embed_code') border-rose-400 @else border-slate-800 @enderror bg-slate-950 shadow-md">
                        <div class="bg-slate-900 px-4 py-2 flex items-center justify-between border-b border-slate-800 text-xs text-slate-400 font-mono">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                                <span class="ml-1.5 font-bold text-slate-300">Editor Kode HTML / Iframe</span>
                            </div>
                            <span class="text-[11px] text-emerald-400 font-mono font-semibold">&lt;/&gt; Code Editor</span>
                        </div>
                        <textarea name="embed_code"
                                  id="embed_code"
                                  rows="9"
                                  placeholder="Masukkan tag <iframe> atau kode HTML/CSS/JS di sini..."
                                  class="w-full font-mono text-xs text-emerald-400 bg-slate-950 p-4 border-0 focus:ring-0 focus:outline-none placeholder-slate-600 transition-all leading-relaxed"
                                  style="color: #34d399 !important; background-color: #020617 !important;"
                                  oninput="triggerSandboxUpdate()">{{ old('embed_code', $data['embed_code'] ?? '') }}</textarea>
                    </div>
                    @error('embed_code')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mode B: Direct URL Input --}}
                <div id="section-embed-url" class="space-y-2 {{ old('embed_type', $data['embed_type'] ?? 'code') === 'url' ? '' : 'hidden' }}">
                    <label for="embed_url" class="block text-xs font-bold text-slate-700">
                        URL / Tautan Web Simulator <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                        </div>
                        <input type="url"
                               name="embed_url"
                               id="embed_url"
                               value="{{ old('embed_url', $data['embed_url'] ?? '') }}"
                               placeholder="https://contoh-simulator.edu/lab/..."
                               class="w-full pl-10 pr-24 py-2.5 rounded-xl border @error('embed_url') border-rose-300 bg-rose-50/50 @else border-slate-300 bg-slate-50 @enderror text-sm text-slate-900 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all font-mono"
                               oninput="triggerSandboxUpdate()">
                        <button type="button"
                                onclick="updateSandboxPreview()"
                                class="absolute right-2 top-2 px-3 py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-lg transition-colors">
                            Muat
                        </button>
                    </div>
                    @error('embed_url')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Live Sandbox Preview Container --}}
                <div class="space-y-3 pt-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-slate-800">Pratinjau Live Sandbox Simulator</span>
                        </div>

                        {{-- Viewport Switcher & Controls --}}
                        <div class="flex items-center gap-2">
                            <div class="flex items-center bg-slate-100 rounded-lg p-0.5 border border-slate-200 text-xs font-medium">
                                <button type="button" onclick="setViewport('100%')" id="vp-btn-full" class="px-2.5 py-1 rounded-md bg-white shadow-2xs font-bold text-slate-800 transition-all">
                                    Desktop
                                </button>
                                <button type="button" onclick="setViewport('768px')" id="vp-btn-tablet" class="px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all">
                                    Tablet
                                </button>
                                <button type="button" onclick="setViewport('420px')" id="vp-btn-mobile" class="px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all">
                                    Mobile
                                </button>
                            </div>
                            <button type="button" onclick="updateSandboxPreview()" class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors" title="Muat Ulang Simulator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Sandbox Box --}}
                    <div class="w-full bg-slate-950 rounded-2xl p-3 sm:p-4 border border-slate-300 shadow-inner flex flex-col items-center justify-center min-h-[380px]">
                        <div id="sandbox-wrapper" class="sandbox-frame-wrapper w-full max-w-full bg-white rounded-xl overflow-hidden shadow-md flex flex-col" style="height: 420px;">
                            {{-- Browser Bar --}}
                            <div class="bg-slate-800 px-3 py-2 flex items-center justify-between border-b border-slate-700 select-none">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                </div>
                                <span id="sandbox-address-bar" class="text-[11px] font-mono text-slate-400 px-3 py-0.5 rounded-md bg-slate-900 border border-slate-700/80 truncate max-w-xs">
                                    embed://sandbox.simulator.local
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Live View</span>
                            </div>

                            {{-- Real Sandbox Iframe --}}
                            <iframe id="live-sandbox-iframe"
                                    sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-modals"
                                    class="w-full flex-1 border-none bg-white"
                                    title="Interactive Simulator Sandbox"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Petunjuk Pengerjaan & Ketentuan Bukti Tangkapan Layar --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg shrink-0">
                        📝
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Petunjuk Praktik & Ketentuan Bukti Tangkapan Layar</h2>
                        <p class="text-xs text-slate-500">Instruksi kerja bagi siswa dan panduan format berkas tangkapan layar (screenshot).</p>
                    </div>
                </div>

                {{-- Instruksi Praktik --}}
                <div class="space-y-1.5">
                    <label for="instructions" class="block text-xs font-bold text-slate-700">
                        Petunjuk Pengerjaan Praktik bagi Siswa
                    </label>
                    <textarea name="instructions"
                              id="instructions"
                              rows="4"
                              placeholder="Tuliskan arahan bagi siswa mengenai apa yang harus mereka lakukan di simulator..."
                              class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all leading-relaxed">{{ old('instructions', $data['instructions'] ?? '') }}</textarea>
                </div>

                {{-- Panduan Screenshot & Batasan Berkas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-1">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label for="screenshot_guide" class="block text-xs font-bold text-slate-700">
                            Panduan Tangkapan Layar (Screenshot)
                        </label>
                        <input type="text"
                               name="screenshot_guide"
                               id="screenshot_guide"
                               value="{{ old('screenshot_guide', $data['screenshot_guide'] ?? 'Unggah tangkapan layar (screenshot) bukti hasil eksekusi simulasi Anda.') }}"
                               placeholder="Contoh: Pastikan layar simulator dan output sukses terlihat jelas."
                               class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Batasan Berkas Bukti (PRD 2.4)
                        </label>
                        <div class="p-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs text-slate-700 space-y-1">
                            <p class="font-bold flex items-center gap-1.5 text-slate-800">
                                <span>📸</span> Format: JPG, PNG, WEBP
                            </p>
                            <p class="text-[11px] text-slate-500">Maksimal <b>2 MB</b> per file.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Target Indikator Keberhasilan Praktik (Checklist Builder) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            🎯
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Target & Indikator Keberhasilan Praktik</h3>
                            <p class="text-xs text-slate-500">Daftar capaian langkah yang harus dipastikan siswa sebelum mengambil tangkapan layar.</p>
                        </div>
                    </div>

                    <button type="button"
                            id="btn-add-checklist"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-cyan-700 bg-cyan-50 hover:bg-cyan-100 border border-cyan-200/60 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Poin Target
                    </button>
                </div>

                <div id="checklist-items-list" class="space-y-3 pt-1">
                    @php
                        $checklistItems = old('checklist_items', $data['checklist_items'] ?? []);
                        if (empty($checklistItems)) {
                            $checklistItems = [
                                'Jalankan simulasi sesuai instruksi kerja pada modul.',
                                'Lakukan pengujian kondisi dan amati output simulator.',
                                'Ambil tangkapan layar (screenshot) layar hasil praktik Anda.',
                                'Unggah bukti tangkapan layar (JPG/PNG maks 2 MB) di halaman ini.',
                            ];
                        }
                    @endphp

                    @foreach($checklistItems as $index => $item)
                        <div class="checklist-row flex items-center gap-2 group fade-in-item">
                            <span class="row-number w-7 h-7 rounded-xl bg-cyan-100 text-cyan-800 text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <input type="text"
                                   name="checklist_items[]"
                                   value="{{ $item }}"
                                   placeholder="Tuliskan target capaian langkah kerja siswa..."
                                   class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-900 placeholder-slate-400 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all">
                            <button type="button"
                                    onclick="removeChecklistRow(this)"
                                    title="Hapus poin target"
                                    class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 5. Pedoman Penilaian & Alur Navigasi Siswa (Sesuai PRD) --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-teal-950 rounded-2xl p-6 text-white shadow-md border border-slate-800 space-y-4">
                <div class="flex items-center gap-2.5 text-amber-400 text-xs font-bold uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Pedoman Penilaian & Alur Navigasi Siswa (PRD Section 2.4, 2.5, 4.2)
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1 text-xs">
                    <div class="bg-slate-800/80 rounded-xl p-4 border border-slate-700/60 space-y-1.5">
                        <p class="font-bold text-emerald-400 flex items-center gap-1.5">
                            <span>🔒</span> Bukti Screenshot Wajib di Sisi Siswa
                        </p>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            Tombol <b>"Halaman Selanjutnya"</b> terkunci otomatis sampai siswa mengunggah berkas screenshot (JPG/PNG maks 2 MB) bukti keberhasilan simulasi.
                        </p>
                    </div>
                    <div class="bg-slate-800/80 rounded-xl p-4 border border-slate-700/60 space-y-1.5">
                        <p class="font-bold text-cyan-400 flex items-center gap-1.5">
                            <span>⭐</span> Penilaian di Grading Center & Kebijakan Re-upload
                        </p>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            Screenshot tersimpan ke tabel <code class="text-amber-300">embed_submissions</code>. Siswa dapat mengunggah ulang selama status masih <code class="text-amber-300">pending</code>. Guru memberikan skor (0-100).
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT 1 COLUMN: Sidebar Status & Actions ────────────────────────── --}}
        <div class="xl:col-span-1 space-y-6 sticky top-6">

            {{-- Card Ringkasan Modul & Status --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Ringkasan Modul</h3>
                
                <div class="space-y-2">
                    <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $module->title }}</h4>
                    @if($module->schoolClass)
                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                            Kelas {{ $module->schoolClass->grade }} {{ $module->schoolClass->major_name }}
                        </span>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-3 space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>Status Modul:</span>
                        @php $badge = $module->statusLabel(); @endphp
                        <span class="font-bold px-2 py-0.5 rounded-full text-[10px] border {{ $badge['color'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Status Komponen:</span>
                        <span id="side-status-indicator" class="font-bold {{ $module->has_embed ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $module->has_embed ? '✓ Aktif' : '○ Non-Aktif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Poin Capaian:</span>
                        <span id="side-points-count" class="font-bold text-slate-900">
                            {{ count($checklistItems) }} Target
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card Tombol Aksi & Template --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-cyan-700 to-teal-700 hover:from-cyan-800 hover:to-teal-800 shadow-lg shadow-cyan-700/25 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Simpan Pengaturan Praktik</span>
                </button>

                <a href="{{ route('teacher.modules.embed.preview', $module) }}"
                   target="_blank"
                   class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pratinjau Siswa</span>
                </a>

                <a href="{{ route('teacher.modules.show', $module) }}"
                   class="w-full py-2 px-4 rounded-xl text-xs font-medium text-slate-500 hover:text-slate-800 text-center block transition-colors">
                    Batal & Kembali
                </a>
            </div>

        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    // Templates Library
    const templates = {
        html_css: `<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f8fafc; color: #0f172a; padding: 24px; text-align: center; }
    .card { background: white; max-width: 420px; margin: 0 auto; padding: 24px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; }
    h2 { color: #0284c7; margin-top: 0; }
    .counter-display { font-size: 42px; font-weight: bold; color: #0f172a; margin: 16px 0; }
    button { background: #0284c7; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px; }
    button:hover { background: #0369a1; }
  </style>
</head>
<body>
  <div class="card">
    <h2>🎮 Simulasi Counter Interaktif</h2>
    <p style="font-size: 13px; color: #64748b;">Klik tombol di bawah ini untuk menguji interaktivitas JavaScript.</p>
    <div id="count" class="counter-display">0</div>
    <button onclick="document.getElementById('count').innerText = parseInt(document.getElementById('count').innerText) + 1">Tambah Nilai (+1)</button>
  </div>
</body>
</html>`,

        sql_console: `<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: 'Segoe UI', monospace; background: #0f172a; color: #f8fafc; padding: 20px; }
    .sql-box { background: #1e293b; border-radius: 12px; padding: 20px; border: 1px solid #334155; }
    .sql-header { color: #38bdf8; font-weight: bold; margin-bottom: 12px; font-size: 15px; }
    pre { background: #020617; padding: 12px; border-radius: 8px; color: #a7f3d0; overflow-x: auto; font-size: 13px; }
    .result-table { width: 100%; border-collapse: collapse; margin-top: 14px; font-size: 12px; }
    .result-table th, .result-table td { border: 1px solid #334155; padding: 8px; text-align: left; }
    .result-table th { background: #334155; color: #38bdf8; }
    button { background: #10b981; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: bold; cursor: pointer; }
  </style>
</head>
<body>
  <div class="sql-box">
    <div class="sql-header">🗄️ SQL Query Sandbox: Tabel 'siswa'</div>
    <pre>SELECT id, nama, kelas, nilai_praktik FROM siswa WHERE status = 'LULUS';</pre>
    <button onclick="document.getElementById('sql-res').style.display = 'table'">▶ Jalankan Query (Execute)</button>
    <table id="sql-res" class="result-table" style="display: none;">
      <tr><th>ID</th><th>Nama</th><th>Kelas</th><th>Nilai Praktik</th></tr>
      <tr><td>101</td><td>Bagas Setiawan</td><td>XII RPL 1</td><td>92</td></tr>
      <tr><td>102</td><td>Annisa Rahma</td><td>XII RPL 1</td><td>95</td></tr>
      <tr><td>103</td><td>Candra Wijaya</td><td>XII RPL 1</td><td>88</td></tr>
    </table>
  </div>
</body>
</html>`,

        phet_sim: `<iframe src="https://phet.colorado.edu/sims/html/circuit-construction-kit-dc/latest/circuit-construction-kit-dc_all.html"
  width="100%"
  height="450"
  style="border:0; border-radius: 12px;"
  allowfullscreen>
</iframe>`,

        codepen: `<iframe height="450" style="width: 100%; border-radius: 12px;" scrolling="no" title="Interactive Flexbox & Grid Sandbox" src="https://codepen.io/enxaneta/embed/adLPwv?default-tab=result" frameborder="no" loading="lazy" allowtransparency="true" allowfullscreen="true">
</iframe>`,

        tinkercad: `<iframe width="100%" height="450" src="https://www.tinkercad.com/embed/fD3yqZ8HnQk" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" style="border-radius: 12px;" allowfullscreen></iframe>`
    };

    function loadTemplate(type) {
        if (!templates[type]) return;
        
        const codeInput = document.getElementById('embed_code');
        const modeCodeRadio = document.querySelector('input[name="embed_type"][value="code"]');
        
        modeCodeRadio.checked = true;
        switchEmbedMode('code');
        
        codeInput.value = templates[type];
        updateSandboxPreview();
    }

    function switchEmbedMode(mode) {
        const sectionCode = document.getElementById('section-embed-code');
        const sectionUrl = document.getElementById('section-embed-url');
        const tabCode = document.getElementById('tab-mode-code');
        const tabUrl = document.getElementById('tab-mode-url');

        if (mode === 'code') {
            sectionCode.classList.remove('hidden');
            sectionUrl.classList.add('hidden');
            tabCode.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-cyan-700 shadow-sm';
            tabUrl.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900';
        } else {
            sectionCode.classList.add('hidden');
            sectionUrl.classList.remove('hidden');
            tabCode.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-900';
            tabUrl.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-white text-cyan-700 shadow-sm';
        }
        updateSandboxPreview();
    }

    function setViewport(width) {
        const wrapper = document.getElementById('sandbox-wrapper');
        wrapper.style.maxWidth = width;

        document.getElementById('vp-btn-full').className = width === '100%' ? 'px-2.5 py-1 rounded-md bg-white shadow-2xs font-bold text-slate-800 transition-all' : 'px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all';
        document.getElementById('vp-btn-tablet').className = width === '768px' ? 'px-2.5 py-1 rounded-md bg-white shadow-2xs font-bold text-slate-800 transition-all' : 'px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all';
        document.getElementById('vp-btn-mobile').className = width === '420px' ? 'px-2.5 py-1 rounded-md bg-white shadow-2xs font-bold text-slate-800 transition-all' : 'px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all';
    }

    let debounceTimer;
    function triggerSandboxUpdate() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updateSandboxPreview, 400);
    }

    function updateSandboxPreview() {
        const mode = document.querySelector('input[name="embed_type"]:checked')?.value || 'code';
        const iframe = document.getElementById('live-sandbox-iframe');
        const addressBar = document.getElementById('sandbox-address-bar');

        if (mode === 'code') {
            const rawCode = document.getElementById('embed_code').value.trim();
            addressBar.innerText = 'embed://sandbox.local (HTML/Iframe Code)';
            
            // Render directly into iframe doc
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            doc.open();
            if (rawCode.toLowerCase().includes('<iframe') || rawCode.toLowerCase().includes('<html')) {
                doc.write(rawCode);
            } else {
                doc.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:system-ui,sans-serif;margin:0;padding:16px;}</style></head><body>${rawCode}</body></html>`);
            }
            doc.close();
        } else {
            const rawUrl = document.getElementById('embed_url').value.trim();
            addressBar.innerText = rawUrl ? rawUrl : 'https://simulator-url.edu/...';
            if (rawUrl) {
                iframe.src = rawUrl;
            } else {
                const doc = iframe.contentDocument || iframe.contentWindow.document;
                doc.open();
                doc.write('<div style="text-align:center;padding:50px;color:#94a3b8;font-family:sans-serif;">Silakan ketikkan URL simulator di atas</div>');
                doc.close();
            }
        }
    }

    function toggleEmbedStatus(isChecked) {
        const track = document.getElementById('embed-toggle-track');
        const thumb = document.getElementById('embed-toggle-thumb');
        const badge = document.getElementById('toggle-status-badge');
        const sideIndicator = document.getElementById('side-status-indicator');

        if (isChecked) {
            track.className = 'relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out bg-emerald-500 border-emerald-600';
            thumb.style.transform = 'translateX(20px)';
            badge.className = 'ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 bg-emerald-100 text-emerald-800 border-emerald-300';
            badge.innerText = 'Aktif (ON)';
            if (sideIndicator) {
                sideIndicator.className = 'font-bold text-emerald-600';
                sideIndicator.innerText = '✓ Aktif';
            }
        } else {
            track.className = 'relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out bg-slate-200 border-slate-400 hover:border-slate-500';
            thumb.style.transform = 'translateX(0px)';
            badge.className = 'ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 bg-slate-100 text-slate-600 border-slate-300';
            badge.innerText = 'Nonaktif (OFF)';
            if (sideIndicator) {
                sideIndicator.className = 'font-bold text-slate-400';
                sideIndicator.innerText = '○ Non-Aktif';
            }
        }
    }

    // Dynamic Checklist rows
    document.getElementById('btn-add-checklist')?.addEventListener('click', function() {
        const list = document.getElementById('checklist-items-list');
        const count = list.querySelectorAll('.checklist-row').length + 1;

        const row = document.createElement('div');
        row.className = 'checklist-row flex items-center gap-2 group fade-in-item';
        row.innerHTML = `
            <span class="row-number w-7 h-7 rounded-xl bg-cyan-100 text-cyan-800 text-xs font-bold flex items-center justify-center shrink-0">
                ${count}
            </span>
            <input type="text"
                   name="checklist_items[]"
                   placeholder="Tuliskan target capaian langkah kerja siswa..."
                   class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-900 placeholder-slate-400 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all">
            <button type="button"
                    onclick="removeChecklistRow(this)"
                    title="Hapus poin target"
                    class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
            </button>
        `;

        list.appendChild(row);
        updateChecklistNumbers();
    });

    function removeChecklistRow(btn) {
        const list = document.getElementById('checklist-items-list');
        if (list.querySelectorAll('.checklist-row').length <= 1) {
            alert('Minimal harus ada 1 poin target praktik.');
            return;
        }
        btn.closest('.checklist-row').remove();
        updateChecklistNumbers();
    }

    function updateChecklistNumbers() {
        const rows = document.querySelectorAll('#checklist-items-list .checklist-row');
        rows.forEach((row, i) => {
            row.querySelector('.row-number').innerText = i + 1;
        });
        const sideCount = document.getElementById('side-points-count');
        if (sideCount) sideCount.innerText = rows.length + ' Target';
    }

    // Initial Sandbox Render on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSandboxPreview();
    });
</script>
@endpush
