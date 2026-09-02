@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Materi & PPT — ' . $module->title)
@section('page-title', 'Editor Materi & Slide Presentasi')

@push('head')
<style>
    /* ── Notepad Document Canvas Styling ────────────────── */
    .notepad-container {
        border: 1px solid #cbd5e1;
        border-radius: 1rem;
        background-color: #ffffff;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        transition: all .2s ease;
    }
    .notepad-container:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .notepad-toolbar {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 0.5rem 0.75rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.25rem;
        user-select: none;
    }
    .notepad-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.55rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #475569;
        background: transparent;
        border-radius: 0.5rem;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .notepad-btn:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }
    .notepad-btn.active {
        background-color: #dbeafe;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }
    .toolbar-divider {
        width: 1px;
        height: 1.25rem;
        background-color: #cbd5e1;
        margin: 0 0.25rem;
    }

    /* ── Editable Paper Area ────────────────────────────── */
    #notepad-editor {
        min-height: 420px;
        padding: 1.5rem 2rem;
        outline: none;
        font-size: 0.95rem;
        line-height: 1.8;
        color: #1e293b;
        background-color: #ffffff;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
        overflow-y: auto;
    }
    #notepad-editor[contenteditable="true"]:empty:before {
        content: attr(data-placeholder);
        color: #94a3b8;
        pointer-events: none;
        display: block;
    }

    /* Typography inside editor */
    #notepad-editor h1, .materi-prose h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 1.25rem 0 0.75rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.35rem; }
    #notepad-editor h2, .materi-prose h2 { font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 1.1rem 0 0.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.25rem; }
    #notepad-editor h3, .materi-prose h3 { font-size: 1.15rem; font-weight: 700; color: #334155; margin: 0.9rem 0 0.4rem; }
    #notepad-editor p, .materi-prose p { margin-bottom: 0.85rem; }
    #notepad-editor ul, .materi-prose ul { list-style-type: disc; margin-left: 1.5rem; margin-bottom: 0.85rem; }
    #notepad-editor ol, .materi-prose ol { list-style-type: decimal; margin-left: 1.5rem; margin-bottom: 0.85rem; }
    #notepad-editor li, .materi-prose li { margin-bottom: 0.25rem; }
    #notepad-editor blockquote, .materi-prose blockquote { border-left: 4px solid #3b82f6; padding: 0.5rem 1rem; background-color: #eff6ff; color: #1e3a8a; border-radius: 0 0.5rem 0.5rem 0; margin: 1rem 0; font-style: italic; }

    /* Tables inside editor */
    #notepad-editor table, .materi-prose table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.25rem 0;
        font-size: 0.875rem;
        background-color: #ffffff;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #cbd5e1;
    }
    #notepad-editor table th, .materi-prose table th {
        background-color: #f1f5f9;
        color: #0f172a;
        font-weight: 700;
        text-align: left;
        padding: 0.65rem 0.85rem;
        border: 1px solid #cbd5e1;
    }
    #notepad-editor table td, .materi-prose table td {
        padding: 0.6rem 0.85rem;
        border: 1px solid #e2e8f0;
        color: #334155;
    }
    #notepad-editor table tr:nth-child(even), .materi-prose table tr:nth-child(even) {
        background-color: #f8fafc;
    }

    /* Images inside editor */
    #notepad-editor img, .materi-prose img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        transition: outline .15s ease, transform .15s ease;
    }
    #notepad-editor img.is-selected-img {
        outline: 3px solid #2563eb !important;
        outline-offset: 3px !important;
        box-shadow: 0 8px 25px rgba(37,99,235,0.25) !important;
    }

    /* Floating Image Toolbar */
    #img-floating-toolbar {
        position: fixed !important;
        z-index: 9999999 !important;
        transform: translate(-50%, 0);
        transition: opacity .15s ease;
    }

    /* Code blocks inside editor */
    #notepad-editor pre, .materi-prose pre {
        background: #0f172a;
        color: #f8fafc;
        padding: 1rem;
        border-radius: 0.75rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.85rem;
        overflow-x: auto;
        margin: 1rem 0;
    }

    /* Callout Alert Boxes inside editor */
    .callout-info { background: #eff6ff; border-left: 4px solid #3b82f6; color: #1e40af; padding: 0.85rem 1rem; border-radius: 0.5rem; margin: 1rem 0; }
    .callout-tip { background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 0.85rem 1rem; border-radius: 0.5rem; margin: 1rem 0; }
    .callout-warning { background: #fffbeb; border-left: 4px solid #f59e0b; color: #92400e; padding: 0.85rem 1rem; border-radius: 0.5rem; margin: 1rem 0; }

    /* Fullscreen Mode */
    .notepad-fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        z-index: 9999 !important;
        border-radius: 0 !important;
        height: 100vh !important;
    }
    .notepad-fullscreen #notepad-editor {
        flex: 1 !important;
        max-height: calc(100vh - 120px) !important;
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
    <span class="font-semibold text-slate-800">Komponen Inti: Materi & PPT</span>
</nav>

{{-- Floating Toast Notification --}}
<div id="materi-toast" class="fixed bottom-6 right-6 z-[99999] pointer-events-none transition-all duration-300 transform translate-y-8 opacity-0 hidden">
    <div id="materi-toast-card" class="pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl border backdrop-blur-md text-sm font-semibold max-w-md bg-white border-slate-200 text-slate-800">
        <div id="materi-toast-icon" class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"></div>
        <div class="flex-1">
            <h4 id="materi-toast-title" class="font-bold text-xs uppercase tracking-wider"></h4>
            <p id="materi-toast-msg" class="text-xs text-slate-600 mt-0.5 font-normal leading-relaxed"></p>
        </div>
        <button type="button" onclick="hideMateriToast()" class="p-1 hover:bg-black/5 rounded-lg text-slate-400 hover:text-slate-600 transition-colors ml-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>

{{-- Flash & Dynamic Alert Container --}}
<div id="in-page-alert-container">
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
</div>

{{-- ══ Header Banner ══ --}}
<div class="bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 mb-8 relative overflow-hidden border border-blue-700/40">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-blue-200">
                    <span>📖</span>
                    <span>Komponen Inti — Komponen 2</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-400/20 text-amber-300 border border-amber-400/40 uppercase tracking-wider">
                    Opsional (Toggle)
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">Notepad Editor: Materi & PPT</h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Tulis uraian materi dengan editor visual seperti notepad — sematkan gambar, susun tabel data, dan unggah berkas slide presentasi (PDF/PPT) untuk siswa.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                ← Kembali ke Detail
            </a>
            <a href="{{ route('teacher.modules.materi.preview', $module) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2 border border-emerald-400/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pratinjau Siswa
            </a>
        </div>
    </div>
</div>

<form id="materi-form"
      action="{{ route('teacher.modules.materi.update', $module) }}"
      method="POST"
      enctype="multipart/form-data"
      onsubmit="return handleMateriSubmit(event)">
    @csrf
    @method('PATCH')

    {{-- Hidden input to carry the rich text HTML --}}
    <input type="hidden" name="uraian_materi" id="uraian_materi_input" value="{{ old('uraian_materi', $data['uraian_materi'] ?? '') }}">

    {{-- ══ Main Layout Grid ══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">

        {{-- ── LEFT 3 COLUMNS: Main Form ───────────────────────────────────────── --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- 1. Sakelar Utama & Identitas Materi --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Aktivasi Komponen Materi & PPT</h2>
                            <p class="text-xs text-slate-500">Jika diaktifkan, halaman Materi & Slide PPT akan disajikan pada Komponen Inti bagi siswa.</p>
                        </div>
                    </div>

                    {{-- Switch Toggle --}}
                    <label class="inline-flex items-center cursor-pointer select-none shrink-0 group">
                        <input type="checkbox" name="has_materi" id="has_materi_toggle" value="1"
                               class="sr-only"
                               {{ old('has_materi', $module->has_materi) ? 'checked' : '' }}
                               onchange="toggleMateriStatus(this.checked)">
                        <div id="materi-toggle-track"
                             class="relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out {{ old('has_materi', $module->has_materi) ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                            <div id="materi-toggle-thumb"
                                 class="absolute top-[2px] left-[2px] h-5 w-5 rounded-full bg-white shadow-md border border-slate-300/90 transition-transform duration-300 ease-in-out"
                                 style="transform: translateX({{ old('has_materi', $module->has_materi) ? '20px' : '0px' }});">
                            </div>
                        </div>
                        <span id="toggle-status-badge" class="ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 {{ old('has_materi', $module->has_materi) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-300' }}">
                            {{ old('has_materi', $module->has_materi) ? 'Aktif (ON)' : 'Nonaktif (OFF)' }}
                        </span>
                    </label>
                </div>

                {{-- Fields --}}
                <div class="pt-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul Kegiatan Belajar / Materi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="judul_materi" id="judul_materi_input"
                               value="{{ old('judul_materi', $data['judul_materi'] ?? 'Kegiatan Belajar: ' . $module->title) }}"
                               placeholder="Contoh: Kegiatan Belajar 1: Konsep & Implementasi Basis Data Relasional"
                               class="w-full rounded-xl border @error('judul_materi') border-rose-300 bg-rose-50/50 @else border-slate-300 bg-slate-50 @enderror px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <p id="error-judul_materi" class="text-xs text-rose-600 mt-1 hidden"></p>
                        @error('judul_materi')
                            <p class="text-xs text-rose-600 mt-1 server-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. NOTEPAD RICH TEXT EDITOR DENGAN GAMBAR & TABEL --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                        📝
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Uraian Materi (Notepad Document Canvas)</h2>
                        <p class="text-xs text-slate-500">Tulis teks materi, sisipkan gambar visual, dan buat tabel struktur data secara interaktif.</p>
                    </div>
                </div>

                {{-- The Notepad Container --}}
                <div id="notepad-main-container" class="notepad-container">
                    {{-- Toolbar --}}
                    <div class="notepad-toolbar">

                        {{-- Heading formats --}}
                        <select onchange="execCommandWithArg('formatBlock', this.value); this.value='';"
                                class="text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg px-2.5 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                            <option value="" disabled selected>Format Teks</option>
                            <option value="<p>">Normal Paragraph</option>
                            <option value="<h1>">Heading 1 (Judul Utama)</option>
                            <option value="<h2>">Heading 2 (Sub-Judul)</option>
                            <option value="<h3>">Heading 3 (Sub-Bab)</option>
                        </select>

                        <div class="toolbar-divider"></div>

                        {{-- Text styles --}}
                        <button type="button" class="notepad-btn" onclick="execCmd('bold')" title="Tebal (Ctrl+B)">
                            <strong>B</strong>
                        </button>
                        <button type="button" class="notepad-btn" onclick="execCmd('italic')" title="Miring (Ctrl+I)">
                            <em>I</em>
                        </button>
                        <button type="button" class="notepad-btn" onclick="execCmd('underline')" title="Garis Bawah (Ctrl+U)">
                            <u>U</u>
                        </button>
                        <button type="button" class="notepad-btn" onclick="execCmd('strikeThrough')" title="Coret">
                            <s>S</s>
                        </button>

                        <div class="toolbar-divider"></div>

                        {{-- Colors --}}
                        <div class="flex items-center gap-1">
                            <label class="notepad-btn cursor-pointer" title="Warna Teks">
                                <span class="text-xs font-bold text-red-600">A</span>
                                <input type="color" class="sr-only" onchange="execCommandWithArg('foreColor', this.value)">
                            </label>
                            <label class="notepad-btn cursor-pointer" title="Warna Sorotan (Highlight)">
                                <span class="text-xs bg-yellow-200 px-1 rounded font-bold text-slate-800">H</span>
                                <input type="color" value="#fef08a" class="sr-only" onchange="execCommandWithArg('hiliteColor', this.value)">
                            </label>
                        </div>

                        <div class="toolbar-divider"></div>

                        {{-- Alignment --}}
                        <button type="button" class="notepad-btn" onclick="execCmd('justifyLeft')" title="Rata Kiri">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h10.5m-10.5 5.25h16.5"/></svg>
                        </button>
                        <button type="button" class="notepad-btn" onclick="execCmd('justifyCenter')" title="Rata Tengah">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M6.75 12h10.5m-13.5 5.25h16.5"/></svg>
                        </button>
                        <button type="button" class="notepad-btn" onclick="execCmd('justifyRight')" title="Rata Kanan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9.75 12h10.5m-16.5 5.25h16.5"/></svg>
                        </button>

                        <div class="toolbar-divider"></div>

                        {{-- Lists --}}
                        <button type="button" class="notepad-btn" onclick="execCmd('insertUnorderedList')" title="Bullet List">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </button>
                        <button type="button" class="notepad-btn" onclick="execCmd('insertOrderedList')" title="Numbered List">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M4.5 7.5v-3m0 0l-1.5 1m1.5-1h1.5M3 13.5h3l-3 3h3m-3 4.5h3v3H3"/></svg>
                        </button>

                        <div class="toolbar-divider"></div>

                        {{-- 🖼️ INSERT IMAGE --}}
                        <div class="relative inline-block">
                            <button type="button" class="notepad-btn text-blue-700 bg-blue-50/70 border-blue-200 hover:bg-blue-100 flex items-center gap-1.5"
                                    onclick="document.getElementById('editor_image_upload').click()" title="Sisipkan Gambar dari Komputer">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                <span>+ Gambar</span>
                            </button>
                            <input type="file" id="editor_image_upload" accept="image/*" class="hidden" onchange="uploadEditorImage(this)">
                        </div>

                        {{-- 📊 INSERT TABLE --}}
                        <button type="button" class="notepad-btn text-indigo-700 bg-indigo-50/70 border-indigo-200 hover:bg-indigo-100 flex items-center gap-1.5"
                                onclick="openTableModal()" title="Sisipkan Tabel Data">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6zM3.75 10.5h16.5M3.75 15h16.5M10.5 3.75v16.5M15 3.75v16.5"/></svg>
                            <span>+ Tabel</span>
                        </button>

                        <div class="toolbar-divider"></div>

                        {{-- Callouts & Boxes --}}
                        <button type="button" class="notepad-btn text-emerald-700 hover:bg-emerald-50" onclick="insertCalloutBlock('tip')" title="Kotak Tips">
                            💡 Tip
                        </button>
                        <button type="button" class="notepad-btn text-amber-700 hover:bg-amber-50" onclick="insertCalloutBlock('warning')" title="Kotak Perhatian">
                            ⚠️ Penting
                        </button>
                        <button type="button" class="notepad-btn text-slate-800 hover:bg-slate-100 font-mono text-xs" onclick="insertCodeBlockElement()" title="Blok Kode">
                            &lt;/&gt; Kode
                        </button>

                        <div class="toolbar-divider"></div>

                        {{-- Undo/Redo & Fullscreen --}}
                        <button type="button" class="notepad-btn" onclick="execCmd('undo')" title="Undo (Ctrl+Z)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                        </button>
                        <button type="button" class="notepad-btn" onclick="execCmd('redo')" title="Redo (Ctrl+Y)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l6-6m0 0l-6-6m6 6H9a6 6 0 000 12h3"/></svg>
                        </button>
                        <button type="button" class="notepad-btn ml-auto" onclick="toggleFullscreenNotepad()" title="Mode Layar Penuh">
                            <svg id="fullscreen-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                        </button>
                    </div>

                    {{-- Contenteditable Paper Canvas --}}
                    <div id="notepad-editor"
                         contenteditable="true"
                         data-placeholder="Ketik atau tempel materi pembelajaran di sini... Anda dapat menyisipkan gambar, tabel, daftar poin, dan format teks dengan mudah."
                         oninput="onEditorInput()">
                        {!! old('uraian_materi', $data['uraian_materi'] ?? '') !!}
                    </div>

                    {{-- Bottom Status Bar --}}
                    <div class="px-4 py-2 bg-slate-50 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between rounded-b-2xl">
                        <span id="notepad-word-count">0 Kata &bull; ~0 Menit waktu baca</span>
                        <span class="text-slate-400">Editor Visual Aktif &bull; Format HTML Otomatis</span>
                    </div>
                </div>
                <p id="error-uraian_materi" class="text-xs text-rose-600 mt-2 hidden"></p>
                @error('uraian_materi')
                    <p class="text-xs text-rose-600 mt-2 server-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 3. Upload Berkas Slide Presentasi (PDF / PPT / PPTX) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-lg shrink-0">
                            📊
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Berkas Slide Presentasi (PPT / PDF)</h2>
                            <p class="text-xs text-slate-500">Unggah berkas slide PPT atau dokumen PDF materi untuk diunduh / dibaca oleh siswa.</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider bg-violet-50 text-violet-700 px-3 py-1 rounded-full border border-violet-200">
                        PDF / PPT / PPTX (Maks 15 MB)
                    </span>
                </div>

                {{-- Status Berkas Saat Ini --}}
                <div id="ppt-current-file-container">
                @if(!empty($data['ppt_file_path']))
                    <div id="ppt-current-file-card" class="p-4 rounded-2xl bg-gradient-to-r from-violet-50 to-indigo-50 border border-violet-200/80 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-violet-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-violet-500/20">
                                @if(str_ends_with(strtolower($data['ppt_file_name'] ?? ''), '.pdf'))
                                    PDF
                                @else
                                    PPT
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900 truncate max-w-sm">
                                        {{ $data['ppt_file_name'] ?? 'Berkas Presentasi' }}
                                    </h4>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                        Tersedia
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    @if(!empty($data['ppt_file_size']))
                                        Ukuran: {{ number_format($data['ppt_file_size'] / 1024 / 1024, 2) }} MB &bull;
                                    @endif
                                    Disimpan di penyimpanan aman sistem
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('teacher.modules.materi.download-ppt', $module) }}"
                               class="px-3.5 py-2 text-xs font-bold text-violet-700 bg-white hover:bg-violet-100 border border-violet-300 rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Unduh Berkas
                            </a>
                            <label class="flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-xl cursor-pointer transition-colors border border-rose-200">
                                <input type="checkbox" name="remove_ppt_file" value="1" class="w-3.5 h-3.5 accent-rose-500">
                                Hapus Berkas
                            </label>
                        </div>
                    </div>
                @endif
                </div>

                {{-- Drag & Drop Upload Zone --}}
                <div id="ppt-drop-zone"
                     onclick="document.getElementById('ppt_file_input').click()"
                     class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-violet-400 bg-slate-50 hover:bg-violet-50/30 rounded-2xl p-8 cursor-pointer transition-all text-center">
                    <div class="w-14 h-14 bg-violet-100 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                    </div>
                    <p id="ppt-drop-title" class="text-sm font-bold text-slate-700">
                        {{ !empty($data['ppt_file_path']) ? 'Klik atau seret untuk mengganti berkas PPT/PDF' : 'Klik atau seret berkas PPT/PDF ke sini' }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Mendukung format .PDF, .PPT, .PPTX — Ukuran maksimal 15 MB</p>
                    <p id="ppt-file-selected-name" class="text-xs font-bold text-violet-700 mt-3 hidden bg-violet-100 px-3 py-1 rounded-full"></p>
                </div>

                <p id="error-ppt_file" class="text-xs text-rose-600 mt-2 hidden"></p>
                @error('ppt_file')
                    <p class="text-xs text-rose-600 mt-2 server-error">{{ $message }}</p>
                @enderror

                <input type="file" id="ppt_file_input" name="ppt_file"
                       accept=".pdf,.ppt,.pptx,application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation"
                       class="hidden" onchange="onPptFileSelected(this)">
            </div>

            {{-- 4. Poin-Poin Penting & Ringkasan Materi --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3 pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg shrink-0">
                            📌
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Poin Penting & Rangkuman Materi</h2>
                            <p class="text-xs text-slate-500">Poin-poin kesimpulan kunci yang akan disajikan di akhir halaman materi untuk penguatan pemahaman siswa.</p>
                        </div>
                    </div>
                </div>

                {{-- Dynamic Repeater Poin Penting --}}
                <div id="poin-penting-list" class="space-y-3 mb-4">
                    @php $poinItems = old('poin_penting', $data['poin_penting'] ?? []); @endphp
                    @if(empty($poinItems))
                        <div class="repeater-row flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold shrink-0">1</span>
                            <input type="text" name="poin_penting[0]"
                                   placeholder="Contoh: DBMS bertindak sebagai perantara antara user dan basis data fisik..."
                                   class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <button type="button" onclick="removePoinRow(this)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @else
                        @foreach($poinItems as $pIdx => $pVal)
                            <div class="repeater-row flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold shrink-0">{{ $pIdx + 1 }}</span>
                                <input type="text" name="poin_penting[{{ $pIdx }}]" value="{{ $pVal }}"
                                       placeholder="Poin rangkuman materi..."
                                       class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <button type="button" onclick="removePoinRow(this)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" onclick="addPoinRow()"
                        class="px-4 py-2 text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah Poin Penting
                </button>
            </div>

        </div>

        {{-- ── RIGHT 1 COLUMN: Sticky Navigator & Actions ─────────────────────── --}}
        <div class="xl:col-span-1 space-y-6 sticky top-6">

            {{-- Summary Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4 flex items-center justify-between">
                    <span>Ringkasan Materi</span>
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                </h3>

                {{-- Metric Tiles (2x2 Grid) --}}
                <div class="grid grid-cols-2 gap-2.5 mb-5">
                    {{-- Status Materi --}}
                    <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-slate-500">Status</span>
                        <div class="mt-1.5">
                            <span id="summary-status" class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold {{ old('has_materi', $module->has_materi) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ old('has_materi', $module->has_materi) ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    {{-- Berkas Presentasi --}}
                    <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-slate-500">Slide PPT</span>
                        <div class="mt-1.5">
                            <span id="summary-ppt" class="text-xs font-extrabold {{ !empty($data['ppt_file_path']) ? 'text-violet-700' : 'text-slate-500' }}">
                                {{ !empty($data['ppt_file_path']) ? 'Terlampir' : 'Tidak Ada' }}
                            </span>
                        </div>
                    </div>

                    {{-- Panjang Teks --}}
                    <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-blue-700">Panjang Teks</span>
                        <div class="mt-1.5">
                            <span id="summary-words" class="text-sm font-extrabold text-blue-700">0 Kata</span>
                        </div>
                    </div>

                    {{-- Estimasi Baca --}}
                    <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-slate-500">Estimasi Baca</span>
                        <div class="mt-1.5">
                            <span id="summary-reading-time" class="text-sm font-extrabold text-slate-900">~0 Menit</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 border-t border-slate-100 space-y-2.5">
                    <button type="submit" id="btn-submit-materi"
                            class="w-full py-3 px-4 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2 select-none">
                        <span id="btn-submit-spinner" class="hidden">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span id="btn-submit-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span id="btn-submit-text">Simpan Materi & PPT</span>
                    </button>
                    <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-400 font-medium py-0.5 select-none">
                        <kbd class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono text-[10px] text-slate-600 font-semibold shadow-xs">Ctrl</kbd> + <kbd class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 font-mono text-[10px] text-slate-600 font-semibold shadow-xs">S</kbd>
                        <span>untuk simpan instan</span>
                    </div>
                    <a href="{{ route('teacher.modules.materi.preview', $module) }}" target="_blank"
                       class="w-full py-2.5 px-4 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pratinjau Tampilan
                    </a>
                    <a href="{{ route('teacher.modules.show', $module) }}"
                       class="w-full py-2.5 px-4 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-transparent hover:bg-slate-50 rounded-xl transition-all text-center block">
                        Kembali ke Detail Modul
                    </a>
                </div>
            </div>

            {{-- Notepad Guide Box --}}
            <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 text-xs text-blue-800 leading-relaxed">
                <p class="font-bold mb-1 flex items-center gap-1.5 text-blue-900">
                    💡 Fitur Notepad Visual:
                </p>
                <ul class="list-disc list-inside space-y-1 text-[11px]">
                    <li><strong>+ Gambar:</strong> Pilih file gambar dari komputer untuk langsung disematkan.</li>
                    <li><strong>+ Tabel:</strong> Tentukan jumlah baris & kolom untuk membuat tabel data.</li>
                    <li><strong>Gaya Teks:</strong> Tersedia Heading, Bold, Warna, dan Blok Kode.</li>
                </ul>
            </div>

        </div>

    </div>
</form>

{{-- ══ MODAL INSERT TABLE ══ --}}
<div id="table-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl animate-fadeIn">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span>📊</span> Sisipkan Tabel Baru
            </h3>
            <button type="button" onclick="closeTableModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                ✕
            </button>
        </div>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Baris</label>
                    <input type="number" id="table_rows" min="1" max="20" value="3"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Kolom</label>
                    <input type="number" id="table_cols" min="1" max="10" value="3"
                           class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" id="table_has_header" checked class="w-4 h-4 text-blue-600 rounded">
                    Sertakan Baris Judul Tabel (Header Baris Pertama)
                </label>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
            <button type="button" onclick="closeTableModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                Batal
            </button>
            <button type="button" onclick="insertCustomTable()" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition-all">
                Sisipkan Tabel
            </button>
        </div>
    </div>
</div>



{{-- ══ FLOATING IMAGE RESIZE TOOLBAR ══ --}}
<div id="img-floating-toolbar" class="hidden fixed z-[9999] bg-slate-900/95 backdrop-blur-md text-white rounded-2xl shadow-2xl p-2 flex items-center gap-1.5 border border-slate-700/80 -translate-x-1/2 select-none">
    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-1.5">Ukuran:</span>
    <button type="button" onclick="setImgSize('25%')" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-800 hover:bg-blue-600 hover:text-white transition-all text-slate-200 cursor-pointer" title="Kecil (25%)">25%</button>
    <button type="button" onclick="setImgSize('50%')" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-800 hover:bg-blue-600 hover:text-white transition-all text-slate-200 cursor-pointer" title="Sedang (50%)">50%</button>
    <button type="button" onclick="setImgSize('75%')" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-800 hover:bg-blue-600 hover:text-white transition-all text-slate-200 cursor-pointer" title="Besar (75%)">75%</button>
    <button type="button" onclick="setImgSize('100%')" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-800 hover:bg-blue-600 hover:text-white transition-all text-slate-200 cursor-pointer" title="Penuh (100%)">100%</button>

    {{-- Slider --}}
    <div class="flex items-center gap-1.5 px-2 border-l border-slate-700">
        <input type="range" id="img-width-slider" min="15" max="100" value="100" class="w-20 accent-blue-500 cursor-pointer" oninput="onImgSliderChange(this.value)">
        <span id="img-width-label" class="text-[11px] font-mono font-bold text-blue-400 w-9 text-center">100%</span>
    </div>

    {{-- Alignment --}}
    <div class="flex items-center gap-1 border-l border-slate-700 pl-2">
        <button type="button" onclick="setImgAlign('left')" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-all cursor-pointer" title="Rata Kiri">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h10.5m-10.5 5.25h16.5"/></svg>
        </button>
        <button type="button" onclick="setImgAlign('center')" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-all cursor-pointer" title="Rata Tengah">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M6.75 12h10.5m-13.5 5.25h16.5"/></svg>
        </button>
        <button type="button" onclick="setImgAlign('right')" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-all cursor-pointer" title="Rata Kanan">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M9.75 12h10.5m-16.5 5.25h16.5"/></svg>
        </button>
    </div>

    {{-- Delete Image --}}
    <div class="border-l border-slate-700 pl-1.5">
        <button type="button" onclick="deleteSelectedImg()" class="p-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-600 text-rose-300 hover:text-white transition-all cursor-pointer" title="Hapus Gambar">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const editor = document.getElementById('notepad-editor');
    const inputHidden = document.getElementById('uraian_materi_input');

    // Sync editor HTML to hidden input
    function syncEditorContent() {
        if (editor && inputHidden) {
            inputHidden.value = editor.innerHTML;
        }
    }

    function onEditorInput() {
        syncEditorContent();
        updateWordCount();
    }

    // Standard Rich text command
    function execCmd(command) {
        document.execCommand(command, false, null);
        editor.focus();
        onEditorInput();
    }

    function execCommandWithArg(command, arg) {
        document.execCommand(command, false, arg);
        editor.focus();
        onEditorInput();
    }

    // Toggle switch status
    function toggleMateriStatus(isChecked) {
        const toggleCheckbox = document.getElementById('has_materi_toggle');
        if (toggleCheckbox && toggleCheckbox.checked !== Boolean(isChecked)) {
            toggleCheckbox.checked = Boolean(isChecked);
        }

        const track = document.getElementById('materi-toggle-track');
        const thumb = document.getElementById('materi-toggle-thumb');
        const badge = document.getElementById('toggle-status-badge');
        const summaryStatus = document.getElementById('summary-status');

        if (track && thumb) {
            if (isChecked) {
                track.className = 'relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out bg-emerald-500 border-emerald-600';
                thumb.style.transform = 'translateX(20px)';
            } else {
                track.className = 'relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out bg-slate-200 border-slate-400 hover:border-slate-500';
                thumb.style.transform = 'translateX(0px)';
            }
        }

        if (badge) {
            badge.textContent = isChecked ? 'Aktif (ON)' : 'Nonaktif (OFF)';
            badge.className = isChecked
                ? 'ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 bg-emerald-100 text-emerald-800 border-emerald-300'
                : 'ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 bg-slate-100 text-slate-600 border-slate-300';
        }

        if (summaryStatus) {
            summaryStatus.textContent = isChecked ? 'Aktif' : 'Nonaktif';
            summaryStatus.className = isChecked
                ? 'inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-100 text-emerald-800'
                : 'inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-200 text-slate-600';
        }
    }

    // PPT File selected
    function onPptFileSelected(input) {
        const file = input.files[0];
        const label = document.getElementById('ppt-file-selected-name');
        const summaryPpt = document.getElementById('summary-ppt');

        if (file) {
            const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
            label.textContent = `📁 File dipilih: ${file.name} (${sizeMb} MB)`;
            label.classList.remove('hidden');
            summaryPpt.textContent = 'File Baru Dipilih';
            summaryPpt.className = 'font-bold text-emerald-600';
        }
    }

    // ── UPLOAD IMAGE INTO NOTEPAD ─────────────────────────
    function uploadEditorImage(input) {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Temporary placeholder
        const placeholderId = 'uploading-img-' + Date.now();
        document.execCommand('insertHTML', false, `<span id="${placeholderId}" class="text-xs text-blue-600 font-semibold italic">⏳ Sedang mengunggah gambar (${file.name})...</span>`);

        fetch("{{ route('teacher.modules.materi.upload-image', $module) }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            const placeholder = document.getElementById(placeholderId);
            if (data.success && data.url) {
                const imgHtml = `<img src="${data.url}" alt="${file.name}" style="width: 75%; max-width: 100%; height: auto; display: block; margin: 1rem auto;" class="rounded-xl shadow-md my-4"><p><br></p>`;
                if (placeholder) {
                    placeholder.outerHTML = imgHtml;
                } else {
                    document.execCommand('insertHTML', false, imgHtml);
                }
            } else {
                if (placeholder) placeholder.outerHTML = `<span class="text-xs text-rose-600">❌ Gagal mengunggah gambar.</span>`;
            }
            onEditorInput();
        })
        .catch(err => {
            console.error(err);
            const placeholder = document.getElementById(placeholderId);
            if (placeholder) placeholder.outerHTML = `<span class="text-xs text-rose-600">❌ Gagal mengunggah gambar.</span>`;
        });

        input.value = '';
    }

    // ── IMAGE RESIZING & SELECTION LOGIC ──────────────────
    let selectedImage = null;
    const imgToolbar = document.getElementById('img-floating-toolbar');
    const widthSlider = document.getElementById('img-width-slider');
    const widthLabel = document.getElementById('img-width-label');

    // Handle clicks inside editor for image selection
    editor.addEventListener('click', function(e) {
        if (e.target && e.target.tagName === 'IMG') {
            e.stopPropagation();
            selectImage(e.target);
        } else {
            deselectImage();
        }
    });

    if (imgToolbar) {
        imgToolbar.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Deselect if clicking outside
    document.addEventListener('click', function(e) {
        if (!editor.contains(e.target) && (!imgToolbar || !imgToolbar.contains(e.target))) {
            deselectImage();
        }
    });

    function selectImage(img) {
        if (selectedImage) {
            selectedImage.classList.remove('is-selected-img');
        }
        selectedImage = img;
        selectedImage.classList.add('is-selected-img');

        // Calculate current width percentage
        let currentWidth = selectedImage.style.width || '100%';
        let numWidth = parseInt(currentWidth, 10);
        if (isNaN(numWidth)) numWidth = 100;
        if (currentWidth.includes('px')) {
            const editorWidth = editor.clientWidth || 600;
            numWidth = Math.min(100, Math.round((numWidth / editorWidth) * 100));
        }

        if (widthSlider) widthSlider.value = numWidth;
        if (widthLabel) widthLabel.textContent = numWidth + '%';

        positionImgToolbar(selectedImage);
    }

    function deselectImage() {
        if (selectedImage) {
            selectedImage.classList.remove('is-selected-img');
            selectedImage = null;
        }
        if (imgToolbar) {
            imgToolbar.classList.add('hidden');
        }
    }

    function positionImgToolbar(img) {
        if (!imgToolbar || !img) return;

        const rect = img.getBoundingClientRect();
        
        // Hide if image is scrolled out of view
        if (rect.bottom < 0 || rect.top > window.innerHeight) {
            imgToolbar.classList.add('hidden');
            return;
        }

        let top = rect.top - 54;
        if (top < 15) {
            top = rect.bottom + 12;
        }
        const left = rect.left + (rect.width / 2);

        imgToolbar.style.top = `${top}px`;
        imgToolbar.style.left = `${left}px`;
        imgToolbar.classList.remove('hidden');
    }

    function setImgSize(size) {
        if (!selectedImage) return;
        selectedImage.style.width = size;
        selectedImage.style.maxWidth = '100%';
        selectedImage.style.height = 'auto';
        
        let num = parseInt(size, 10);
        if (widthSlider) widthSlider.value = num;
        if (widthLabel) widthLabel.textContent = num + '%';

        setTimeout(() => positionImgToolbar(selectedImage), 50);
        onEditorInput();
    }

    function onImgSliderChange(val) {
        if (!selectedImage) return;
        selectedImage.style.width = val + '%';
        selectedImage.style.maxWidth = '100%';
        selectedImage.style.height = 'auto';
        if (widthLabel) widthLabel.textContent = val + '%';
        positionImgToolbar(selectedImage);
        onEditorInput();
    }

    function setImgAlign(align) {
        if (!selectedImage) return;
        selectedImage.style.display = 'block';
        if (align === 'left') {
            selectedImage.style.marginLeft = '0';
            selectedImage.style.marginRight = 'auto';
        } else if (align === 'right') {
            selectedImage.style.marginLeft = 'auto';
            selectedImage.style.marginRight = '0';
        } else {
            // center
            selectedImage.style.marginLeft = 'auto';
            selectedImage.style.marginRight = 'auto';
        }
        setTimeout(() => positionImgToolbar(selectedImage), 50);
        onEditorInput();
    }

    function deleteSelectedImg() {
        if (!selectedImage) return;
        if (confirm('Hapus gambar ini dari materi?')) {
            const imgToRemove = selectedImage;
            deselectImage();
            imgToRemove.remove();
            onEditorInput();
        }
    }

    // Reposition floating toolbar on window resize or scroll
    window.addEventListener('resize', () => {
        if (selectedImage) positionImgToolbar(selectedImage);
    });
    window.addEventListener('scroll', () => {
        if (selectedImage) positionImgToolbar(selectedImage);
    }, true);
    if (editor) {
        editor.addEventListener('scroll', () => {
            if (selectedImage) positionImgToolbar(selectedImage);
        }, { passive: true });
    }

    // ── INSERT CUSTOM TABLE ──────────────────────────────
    function openTableModal() {
        document.getElementById('table-modal').classList.remove('hidden');
    }

    function closeTableModal() {
        document.getElementById('table-modal').classList.add('hidden');
    }

    function insertCustomTable() {
        const rows = parseInt(document.getElementById('table_rows').value, 10) || 3;
        const cols = parseInt(document.getElementById('table_cols').value, 10) || 3;
        const hasHeader = document.getElementById('table_has_header').checked;

        let tableHtml = '<table class="w-full border-collapse border border-slate-300 my-4 text-sm">';

        if (hasHeader) {
            tableHtml += '<thead class="bg-slate-100"><tr>';
            for (let c = 1; c <= cols; c++) {
                tableHtml += `<th class="border border-slate-300 p-2 font-bold text-left">Kolom ${c}</th>`;
            }
            tableHtml += '</tr></thead>';
        }

        tableHtml += '<tbody>';
        const bodyRows = hasHeader ? rows - 1 : rows;
        for (let r = 1; r <= Math.max(1, bodyRows); r++) {
            tableHtml += '<tr>';
            for (let c = 1; c <= cols; c++) {
                tableHtml += `<td class="border border-slate-300 p-2 text-slate-700">Data ${r}, ${c}</td>`;
            }
            tableHtml += '</tr>';
        }
        tableHtml += '</tbody></table><p><br></p>';

        closeTableModal();
        editor.focus();
        document.execCommand('insertHTML', false, tableHtml);
        onEditorInput();
    }

    // ── INSERT CALLOUT BOXES & CODE BLOCKS ────────────────
    function insertCalloutBlock(type) {
        let html = '';
        if (type === 'tip') {
            html = '<div class="callout-tip"><p><strong>💡 Tips Pembelajaran:</strong> Tuliskan tips atau praktik terbaik di sini.</p></div><p><br></p>';
        } else if (type === 'warning') {
            html = '<div class="callout-warning"><p><strong>⚠️ Catatan Penting:</strong> Tuliskan poin perhatian atau peringatan penting di sini.</p></div><p><br></p>';
        } else {
            html = '<div class="callout-info"><p><strong>ℹ️ Informasi:</strong> Tuliskan penjelasan tambahan di sini.</p></div><p><br></p>';
        }
        editor.focus();
        document.execCommand('insertHTML', false, html);
        onEditorInput();
    }

    function insertCodeBlockElement() {
        const codeHtml = '<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-xs overflow-x-auto my-3"><code>-- Tulis kode SQL atau skrip di sini\nSELECT id, nama, kelas FROM siswa WHERE status = "aktif";</code></pre><p><br></p>';
        editor.focus();
        document.execCommand('insertHTML', false, codeHtml);
        onEditorInput();
    }

    // ── FULLSCREEN NOTEPAD TOGGLE ─────────────────────────
    function toggleFullscreenNotepad() {
        const container = document.getElementById('notepad-main-container');
        container.classList.toggle('notepad-fullscreen');
        if (selectedImage) {
            setTimeout(() => positionImgToolbar(selectedImage), 60);
        }
    }

    // ── WORD COUNT & READING TIME ─────────────────────────
    function updateWordCount() {
        if (!editor) return;
        const text = editor.innerText.trim();
        const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
        const minutes = Math.max(1, Math.ceil(words / 150));

        document.getElementById('notepad-word-count').textContent = `${words} Kata • ~${minutes} Menit waktu baca`;
        document.getElementById('summary-words').textContent = `${words} Kata`;
        document.getElementById('summary-reading-time').textContent = `~${minutes} Menit`;
    }



    // ── POIN PENTING REPEATER ─────────────────────────────
    let poinIdx = document.querySelectorAll('#poin-penting-list .repeater-row').length;

    function addPoinRow() {
        const list = document.getElementById('poin-penting-list');
        const count = list.querySelectorAll('.repeater-row').length;
        const row = document.createElement('div');
        row.className = 'repeater-row flex items-center gap-3';
        row.innerHTML = `
            <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold shrink-0">${count + 1}</span>
            <input type="text" name="poin_penting[${poinIdx}]"
                   placeholder="Poin rangkuman materi..."
                   class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
            <button type="button" onclick="removePoinRow(this)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;
        list.appendChild(row);
        poinIdx++;
        row.querySelector('input').focus();
    }

    function removePoinRow(btn) {
        btn.closest('.repeater-row').remove();
        document.querySelectorAll('#poin-penting-list .repeater-row').forEach((row, i) => {
            const badge = row.querySelector('span');
            if (badge) badge.textContent = i + 1;
        });
    }

    // ── AJAX SUBMIT & AUTO-UPDATE LOGIC ──────────────────
    let isSubmitting = false;
    let toastTimeout = null;

    function showMateriToast(type, title, message) {
        const toast = document.getElementById('materi-toast');
        const card = document.getElementById('materi-toast-card');
        const icon = document.getElementById('materi-toast-icon');
        const titleEl = document.getElementById('materi-toast-title');
        const msgEl = document.getElementById('materi-toast-msg');

        if (!toast) return;

        if (toastTimeout) clearTimeout(toastTimeout);

        if (type === 'success') {
            card.className = 'pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl border backdrop-blur-md text-sm font-semibold max-w-md bg-emerald-900/95 text-white border-emerald-500/50 shadow-emerald-950/40';
            icon.className = 'w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0';
            icon.innerHTML = '<svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
            titleEl.className = 'font-bold text-xs uppercase tracking-wider text-emerald-300';
            msgEl.className = 'text-xs text-emerald-100/90 mt-0.5 font-normal leading-relaxed';
        } else {
            card.className = 'pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl border backdrop-blur-md text-sm font-semibold max-w-md bg-rose-900/95 text-white border-rose-500/50 shadow-rose-950/40';
            icon.className = 'w-9 h-9 rounded-xl bg-rose-500/20 text-rose-300 flex items-center justify-center shrink-0';
            icon.innerHTML = '<svg class="w-5 h-5 text-rose-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>';
            titleEl.className = 'font-bold text-xs uppercase tracking-wider text-rose-300';
            msgEl.className = 'text-xs text-rose-100/90 mt-0.5 font-normal leading-relaxed';
        }

        titleEl.textContent = title;
        msgEl.textContent = message;

        toast.classList.remove('hidden');
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-8', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        toastTimeout = setTimeout(() => {
            hideMateriToast();
        }, 4000);
    }

    function hideMateriToast() {
        const toast = document.getElementById('materi-toast');
        if (!toast) return;
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-8', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 300);
    }

    function clearErrors() {
        document.querySelectorAll('.server-error').forEach(el => el.remove());
        document.querySelectorAll('[id^="error-"]').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        const alertBox = document.getElementById('dynamic-alert-box');
        if (alertBox) alertBox.remove();

        const judulInput = document.getElementById('judul_materi_input');
        if (judulInput) {
            judulInput.classList.remove('border-rose-300', 'bg-rose-50/50');
            judulInput.classList.add('border-slate-300', 'bg-slate-50');
        }

        const notepadBox = document.getElementById('notepad-main-container');
        if (notepadBox) {
            notepadBox.classList.remove('border-rose-400', 'ring-2', 'ring-rose-200');
        }
    }

    function showErrors(errors, mainMessage) {
        clearErrors();

        const alertContainer = document.getElementById('in-page-alert-container');
        if (alertContainer) {
            let errorItemsHtml = '';
            for (const key in errors) {
                if (Array.isArray(errors[key])) {
                    errors[key].forEach(msg => {
                        errorItemsHtml += `<li>${msg}</li>`;
                    });
                }
            }

            const alertEl = document.createElement('div');
            alertEl.id = 'dynamic-alert-box';
            alertEl.className = 'mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800 shadow-sm transition-all duration-300 animate-fadeIn';
            alertEl.innerHTML = `
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="font-bold mb-1">${mainMessage || 'Terdapat kesalahan input:'}</p>
                        <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
                            ${errorItemsHtml}
                        </ul>
                    </div>
                </div>
            `;
            alertContainer.prepend(alertEl);
            alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        if (errors.judul_materi) {
            const errEl = document.getElementById('error-judul_materi');
            const inputEl = document.getElementById('judul_materi_input');
            if (errEl) {
                errEl.textContent = errors.judul_materi[0];
                errEl.classList.remove('hidden');
            }
            if (inputEl) {
                inputEl.classList.remove('border-slate-300', 'bg-slate-50');
                inputEl.classList.add('border-rose-300', 'bg-rose-50/50');
            }
        }

        if (errors.uraian_materi) {
            const errEl = document.getElementById('error-uraian_materi');
            const notepadBox = document.getElementById('notepad-main-container');
            if (errEl) {
                errEl.textContent = errors.uraian_materi[0];
                errEl.classList.remove('hidden');
            }
            if (notepadBox) {
                notepadBox.classList.add('border-rose-400', 'ring-2', 'ring-rose-200');
            }
        }

        if (errors.ppt_file) {
            const errEl = document.getElementById('error-ppt_file');
            if (errEl) {
                errEl.textContent = errors.ppt_file[0];
                errEl.classList.remove('hidden');
            }
        }

        showMateriToast('error', 'Gagal Menyimpan', mainMessage || 'Periksa kembali data yang Anda isi.');
    }

    async function handleMateriSubmit(e) {
        if (e) e.preventDefault();
        if (isSubmitting) return false;

        syncEditorContent();

        const form = document.getElementById('materi-form');
        const submitBtn = document.getElementById('btn-submit-materi');
        const submitText = document.getElementById('btn-submit-text');
        const submitSpinner = document.getElementById('btn-submit-spinner');
        const submitIcon = document.getElementById('btn-submit-icon');

        isSubmitting = true;
        clearErrors();

        // Loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'bg-emerald-600', 'hover:bg-emerald-700');
            submitBtn.classList.add('bg-blue-700', 'cursor-not-allowed', 'opacity-90');
            if (submitSpinner) submitSpinner.classList.remove('hidden');
            if (submitIcon) submitIcon.classList.add('hidden');
            if (submitText) submitText.textContent = 'Menyimpan Materi...';
        }

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                const resData = await response.json();

                // 1. Toast Notification
                showMateriToast('success', 'Berhasil Disimpan! ✅', resData.message || 'Materi & Berkas Presentasi berhasil disimpan.');

                // 2. In-Page Success Alert
                const alertContainer = document.getElementById('in-page-alert-container');
                if (alertContainer) {
                    const existingSuccess = document.getElementById('dynamic-success-box');
                    if (existingSuccess) existingSuccess.remove();

                    const successEl = document.createElement('div');
                    successEl.id = 'dynamic-success-box';
                    successEl.className = 'mb-6 flex items-center justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm transition-all duration-300 animate-fadeIn';
                    successEl.innerHTML = `
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>${resData.message}</span>
                        </div>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full shrink-0">Auto-Saved</span>
                    `;
                    alertContainer.prepend(successEl);
                }

                // 3. Update PPT Component Card in DOM
                const pptContainer = document.getElementById('ppt-current-file-container');
                const pptDropTitle = document.getElementById('ppt-drop-title');
                const pptSelectedName = document.getElementById('ppt-file-selected-name');
                const summaryPpt = document.getElementById('summary-ppt');

                if (resData.data && resData.data.ppt_file_path) {
                    if (pptContainer) {
                        pptContainer.innerHTML = `
                            <div id="ppt-current-file-card" class="p-4 rounded-2xl bg-gradient-to-r from-violet-50 to-indigo-50 border border-violet-200/80 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300 animate-fadeIn">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-violet-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md shadow-violet-500/20">
                                        ${resData.data.ppt_file_is_pdf ? 'PDF' : 'PPT'}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-slate-900 truncate max-w-sm">
                                                ${resData.data.ppt_file_name || 'Berkas Presentasi'}
                                            </h4>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                                Tersedia
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            ${resData.data.ppt_file_size_formatted ? 'Ukuran: ' + resData.data.ppt_file_size_formatted + ' &bull; ' : ''}
                                            Disimpan di penyimpanan aman sistem
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="${resData.data.ppt_download_url}"
                                       class="px-3.5 py-2 text-xs font-bold text-violet-700 bg-white hover:bg-violet-100 border border-violet-300 rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                        Unduh Berkas
                                    </a>
                                    <label class="flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-xl cursor-pointer transition-colors border border-rose-200">
                                        <input type="checkbox" name="remove_ppt_file" value="1" class="w-3.5 h-3.5 accent-rose-500">
                                        Hapus Berkas
                                    </label>
                                </div>
                            </div>
                        `;
                    }
                    if (pptDropTitle) pptDropTitle.textContent = 'Klik atau seret untuk mengganti berkas PPT/PDF';
                    if (summaryPpt) {
                        summaryPpt.textContent = 'Terlampir';
                        summaryPpt.className = 'text-xs font-extrabold text-violet-700';
                    }
                } else {
                    if (pptContainer) pptContainer.innerHTML = '';
                    if (pptDropTitle) pptDropTitle.textContent = 'Klik atau seret berkas PPT/PDF ke sini';
                    if (summaryPpt) {
                        summaryPpt.textContent = 'Tidak Ada';
                        summaryPpt.className = 'text-xs font-extrabold text-slate-500';
                    }
                }

                // Reset file input & label
                const pptInput = document.getElementById('ppt_file_input');
                if (pptInput) pptInput.value = '';
                if (pptSelectedName) {
                    pptSelectedName.textContent = '';
                    pptSelectedName.classList.add('hidden');
                }

                // 4. Update Toggle Status & Badges
                toggleMateriStatus(resData.data.has_materi);

                // 5. Update Metrics
                updateWordCount();

                // 6. Success Button Animation
                if (submitBtn) {
                    submitBtn.classList.remove('bg-blue-700', 'opacity-90');
                    submitBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                    if (submitSpinner) submitSpinner.classList.add('hidden');
                    if (submitIcon) {
                        submitIcon.innerHTML = '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
                        submitIcon.classList.remove('hidden');
                    }
                    if (submitText) submitText.textContent = 'Tersimpan! ✅';

                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('cursor-not-allowed', 'bg-emerald-600', 'hover:bg-emerald-700');
                        submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                        if (submitIcon) {
                            submitIcon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                        }
                        if (submitText) submitText.textContent = 'Simpan Materi & PPT';
                        isSubmitting = false;
                    }, 2200);
                } else {
                    isSubmitting = false;
                }

            } else if (response.status === 422) {
                // Validation error
                const errData = await response.json();
                showErrors(errData.errors || {}, errData.message || 'Terdapat kesalahan input:');
                resetSubmitButton();
            } else {
                showMateriToast('error', 'Gagal Menyimpan', 'Terjadi kesalahan sistem (' + response.status + ').');
                resetSubmitButton();
            }
        } catch (err) {
            console.error(err);
            showMateriToast('error', 'Gagal Terhubung', 'Gagal mengirim data ke server. Pastikan koneksi server aktif.');
            resetSubmitButton();
        }

        return false;
    }

    function resetSubmitButton() {
        const submitBtn = document.getElementById('btn-submit-materi');
        const submitText = document.getElementById('btn-submit-text');
        const submitSpinner = document.getElementById('btn-submit-spinner');
        const submitIcon = document.getElementById('btn-submit-icon');

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('cursor-not-allowed', 'bg-blue-700', 'opacity-90', 'bg-emerald-600', 'hover:bg-emerald-700');
            submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            if (submitSpinner) submitSpinner.classList.add('hidden');
            if (submitIcon) {
                submitIcon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                submitIcon.classList.remove('hidden');
            }
            if (submitText) submitText.textContent = 'Simpan Materi & PPT';
        }
        isSubmitting = false;
    }

    // ── KEYBOARD SHORTCUT (Ctrl+S / Cmd+S) ─────────────────
    window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
            e.preventDefault();
            handleMateriSubmit();
        }
    });

    // Init on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateWordCount();
    });
</script>
@endpush
