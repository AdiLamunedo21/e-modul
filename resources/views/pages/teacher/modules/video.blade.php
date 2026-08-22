@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Video & Ringkasan YouTube — ' . $module->title)
@section('page-title', 'Editor Video & Ringkasan YouTube (Komponen Inti)')

@push('head')
<style>
    .fade-in-item {
        animation: fadeInSlide .25s ease-out;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
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
    <span class="font-semibold text-slate-800">Komponen Inti: Video & Ringkasan</span>
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
<div class="bg-gradient-to-r from-red-800 via-rose-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-red-950/20 mb-8 relative overflow-hidden border border-red-700/40">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-red-200">
                    <span>🎬</span>
                    <span>Komponen Inti — Komponen 3</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-400/20 text-amber-300 border border-amber-400/40 uppercase tracking-wider">
                    Opsional (Toggle)
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">Integrasi Video & Ringkasan YouTube</h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Sematkan video pembelajaran interaktif dari YouTube. Siswa diwajibkan menyimak materi dan menuliskan intisari ringkasan sebelum dapat melangkah ke halaman selanjutnya.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                ← Kembali ke Detail
            </a>
            <a href="{{ route('teacher.modules.video.preview', $module) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2 border border-emerald-400/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pratinjau Siswa
            </a>
        </div>
    </div>
</div>

<form id="video-form" action="{{ route('teacher.modules.video.update', $module) }}" method="POST">
    @csrf
    @method('PATCH')

    {{-- ══ Main Layout Grid (Standard 4 Columns) ══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">

        {{-- ── LEFT 3 COLUMNS: Main Form ───────────────────────────────────────── --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- 1. Sakelar Utama & Identitas Video --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-lg shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Aktivasi Komponen Video & Ringkasan YouTube</h2>
                            <p class="text-xs text-slate-500">Jika diaktifkan, halaman pemutar video dan form ringkasan wajib akan disajikan pada Komponen Inti bagi siswa.</p>
                        </div>
                    </div>

                    {{-- Switch Toggle --}}
                    <label class="inline-flex items-center cursor-pointer select-none shrink-0 group">
                        <input type="checkbox" name="has_video" id="has_video_toggle" value="1"
                               class="sr-only"
                               {{ old('has_video', $module->has_video) ? 'checked' : '' }}
                               onchange="toggleVideoStatus(this.checked)">
                        <div id="video-toggle-track"
                             class="relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out {{ old('has_video', $module->has_video) ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                            <div id="video-toggle-thumb"
                                 class="absolute top-[2px] left-[2px] h-5 w-5 rounded-full bg-white shadow-md border border-slate-300/90 transition-transform duration-300 ease-in-out"
                                 style="transform: translateX({{ old('has_video', $module->has_video) ? '20px' : '0px' }});">
                            </div>
                        </div>
                        <span id="toggle-status-badge" class="ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 {{ old('has_video', $module->has_video) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-300' }}">
                            {{ old('has_video', $module->has_video) ? 'Aktif (ON)' : 'Nonaktif (OFF)' }}
                        </span>
                    </label>
                </div>

                {{-- Fields Identitas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-6">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul Video Pembelajaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="video_title"
                               id="video_title"
                               value="{{ old('video_title', $data['video_title'] ?? 'Video Pembelajaran: ' . $module->title) }}"
                               placeholder="Contoh: Video Tutorial: Instalasi & Konfigurasi Basis Data MySQL"
                               class="w-full rounded-xl border @error('video_title') border-rose-300 bg-rose-50/50 @else border-slate-300 bg-slate-50 @enderror px-4 py-2.5 text-sm text-slate-900 focus:border-red-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all">
                        @error('video_title')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Estimasi Durasi Menonton
                        </label>
                        <div class="flex rounded-xl border border-slate-300 bg-slate-50 overflow-hidden focus-within:border-red-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-red-500/20 transition-all shadow-sm">
                            <input type="number"
                                   name="estimated_duration"
                                   id="estimated_duration"
                                   min="1"
                                   max="240"
                                   value="{{ old('estimated_duration', $data['estimated_duration'] ?? 15) }}"
                                   class="w-full bg-transparent px-4 py-2.5 text-sm text-slate-900 focus:outline-none">
                            <span class="inline-flex items-center px-3.5 bg-slate-100 text-xs font-bold text-slate-500 border-l border-slate-200 select-none shrink-0">
                                Menit
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Perkiraan durasi menonton & mencatat.</p>
                    </div>
                </div>
            </div>

            {{-- 2. Tautan & Pemutar Video YouTube (Live Embed Player) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-lg shrink-0">
                            🎬
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Tautan & Media Video YouTube</h2>
                            <p class="text-xs text-slate-500">Mendukung tautan standar, <i>short link</i> (youtu.be), YouTube Shorts, maupun ID video langsung.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span id="youtube-status-badge" class="px-3 py-1 text-xs font-bold rounded-full border bg-slate-100 text-slate-600 border-slate-200">
                            Belum Ada Tautan
                        </span>
                        <a id="btn-open-youtube"
                           href="#"
                           target="_blank"
                           class="hidden px-3 py-1 text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">
                            Buka di YouTube ↗
                        </a>
                    </div>
                </div>

                {{-- Input Tautan YouTube --}}
                <div class="space-y-2">
                    <label for="youtube_url" class="block text-xs font-bold text-slate-700">
                        URL / Tautan Video YouTube <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </div>
                        <input type="text"
                               name="youtube_url"
                               id="youtube_url"
                               value="{{ old('youtube_url', $data['youtube_url'] ?? '') }}"
                               placeholder="Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ atau https://youtu.be/..."
                               class="w-full pl-11 pr-24 py-3 rounded-xl border @error('youtube_url') border-rose-300 bg-rose-50/50 @else border-slate-300 bg-slate-50 @enderror text-sm font-mono text-slate-900 focus:border-red-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all">
                        <button type="button"
                                id="btn-check-url"
                                class="absolute right-2 top-2 px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-lg transition-colors">
                            Muat
                        </button>
                    </div>
                    @error('youtube_url')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Live Preview Embed Player Box --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Pratinjau Pemutar Video (Live Embed 16:9)</span>
                        <span class="text-[11px] font-normal text-slate-400">Tampilan persis seperti yang akan ditonton siswa</span>
                    </label>
                    <div id="player-container" class="relative w-full aspect-video bg-slate-950 rounded-2xl overflow-hidden border border-slate-300 shadow-inner flex flex-col items-center justify-center text-center p-6 text-slate-400">
                        {{-- Iframe placeholder saat kosong/invalid --}}
                        <div id="player-placeholder" class="flex flex-col items-center justify-center space-y-3">
                            <div class="w-16 h-16 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-red-500 shadow-lg">
                                <svg class="w-8 h-8 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-300">Belum ada video yang dimuat</p>
                                <p class="text-xs text-slate-500 max-w-sm mt-0.5">Ketikkan atau tempel URL YouTube di atas untuk melihat tampilan video siswa</p>
                            </div>
                        </div>

                        {{-- Iframe sesungguhnya --}}
                        <iframe id="youtube-iframe"
                                src=""
                                class="w-full h-full absolute inset-0 hidden"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            {{-- 3. Petunjuk Pengerjaan & Batasan Minimal Ringkasan --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg shrink-0">
                        📝
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Petunjuk Pengerjaan & Batasan Ringkasan Siswa</h2>
                        <p class="text-xs text-slate-500">Instruksi penulisan intisari dan syarat minimal panjang teks pengerjaan siswa.</p>
                    </div>
                </div>

                {{-- Instruksi Ringkasan --}}
                <div class="space-y-1.5">
                    <label for="instructions" class="block text-xs font-bold text-slate-700">
                        Petunjuk Pengerjaan Ringkasan bagi Siswa
                    </label>
                    <textarea name="instructions"
                              id="instructions"
                              rows="4"
                              placeholder="Tuliskan arahan bagi siswa terkait apa yang perlu mereka catat dan perhatikan selama menonton..."
                              class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all leading-relaxed">{{ old('instructions', $data['instructions'] ?? '') }}</textarea>
                </div>

                {{-- Batasan Minimal Karakter & Kata --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                    <div>
                        <label for="min_summary_chars" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Batas Minimal Karakter
                        </label>
                        <div class="flex rounded-xl border border-slate-300 bg-slate-50 overflow-hidden focus-within:border-amber-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-amber-500/20 transition-all shadow-sm">
                            <input type="number"
                                   name="min_summary_chars"
                                   id="min_summary_chars"
                                   min="10"
                                   max="2000"
                                   value="{{ old('min_summary_chars', $data['min_summary_chars'] ?? 100) }}"
                                   class="w-full bg-transparent px-4 py-2.5 text-sm text-slate-900 focus:outline-none">
                            <span class="inline-flex items-center px-3.5 bg-slate-100 text-xs font-bold text-slate-500 border-l border-slate-200 select-none shrink-0">
                                Karakter
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Standar: 100 karakter.</p>
                    </div>

                    <div>
                        <label for="min_summary_words" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Batas Minimal Kata
                        </label>
                        <div class="flex rounded-xl border border-slate-300 bg-slate-50 overflow-hidden focus-within:border-amber-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-amber-500/20 transition-all shadow-sm">
                            <input type="number"
                                   name="min_summary_words"
                                   id="min_summary_words"
                                   min="5"
                                   max="500"
                                   value="{{ old('min_summary_words', $data['min_summary_words'] ?? 20) }}"
                                   class="w-full bg-transparent px-4 py-2.5 text-sm text-slate-900 focus:outline-none">
                            <span class="inline-flex items-center px-3.5 bg-slate-100 text-xs font-bold text-slate-500 border-l border-slate-200 select-none shrink-0">
                                Kata
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Standar: 20 kata.</p>
                    </div>
                </div>
            </div>

            {{-- 4. Poin Panduan / Pertanyaan Pemantik Ringkasan (Guiding Questions Builder) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            💡
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Poin Fokus / Panduan Ringkasan</h3>
                            <p class="text-xs text-slate-500">Membantu siswa menyusun intisari materi secara terstruktur saat menonton video.</p>
                        </div>
                    </div>

                    <button type="button"
                            id="btn-add-question"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200/60 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Poin
                    </button>
                </div>

                <div id="guiding-questions-list" class="space-y-3 pt-1">
                    @php
                        $questions = old('guiding_questions', $data['guiding_questions'] ?? []);
                        if (empty($questions)) {
                            $questions = [
                                'Apa konsep atau topik utama yang dijelaskan dalam video ini?',
                                'Sebutkan langkah kerja atau poin krusial yang harus diperhatikan!',
                                'Bagaimana penerapan konsep tersebut dalam praktik kejuruan Anda?',
                            ];
                        }
                    @endphp

                    @foreach($questions as $index => $q)
                        <div class="question-row flex items-center gap-2 group fade-in-item">
                            <span class="row-number w-7 h-7 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <input type="text"
                                   name="guiding_questions[]"
                                   value="{{ $q }}"
                                   placeholder="Tuliskan pertanyaan pemantik atau poin fokus..."
                                   class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                            <button type="button"
                                    onclick="removeQuestionRow(this)"
                                    title="Hapus poin panduan"
                                    class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 5. Rubrik Penilaian di Grading Center & Alur Navigasi (Sesuai PRD) --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 rounded-2xl p-6 text-white shadow-md border border-slate-800 space-y-4">
                <div class="flex items-center gap-2.5 text-amber-400 text-xs font-bold uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Pedoman Penilaian & Alur Navigasi Siswa (PRD Section 2.4, 3.2, 4.2)
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1 text-xs">
                    <div class="bg-slate-800/80 rounded-xl p-4 border border-slate-700/60 space-y-1.5">
                        <p class="font-bold text-emerald-400 flex items-center gap-1.5">
                            <span>🔒</span> Navigasi Mengikat di Sisi Siswa
                        </p>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            Tombol <b>"Halaman Selanjutnya"</b> terkunci otomatis sampai siswa mengetikkan teks ringkasan sesuai batas minimal kata/karakter yang Anda tetapkan.
                        </p>
                    </div>
                    <div class="bg-slate-800/80 rounded-xl p-4 border border-slate-700/60 space-y-1.5">
                        <p class="font-bold text-emerald-400 flex items-center gap-1.5">
                            <span>⭐</span> Penilaian di Grading Center & Rekap Excel
                        </p>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            Hasil ketikan ringkasan tersimpan ke tabel <code class="text-amber-300">video_summaries</code>. Guru memberikan skor manual (0-100) yang otomatis masuk ke rekap nilai akhir.
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
                        <span id="side-status-indicator" class="font-bold {{ $module->has_video ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $module->has_video ? '✓ Aktif' : '○ Non-Aktif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Poin Panduan:</span>
                        <span id="side-points-count" class="font-bold text-slate-900">
                            {{ count($questions) }} Poin
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card Tombol Aksi & Template --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 shadow-lg shadow-red-600/25 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Simpan Pengaturan Video</span>
                </button>

                <a href="{{ route('teacher.modules.video.preview', $module) }}"
                   target="_blank"
                   class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pratinjau Siswa</span>
                </a>

                <div class="pt-2 border-t border-slate-100">
                    <button type="button"
                            onclick="loadSampleVideoContent()"
                            class="w-full py-2 px-3 text-[11px] font-bold text-blue-600 hover:text-blue-700 bg-blue-50/70 hover:bg-blue-50 rounded-xl transition-colors flex items-center justify-center gap-1.5">
                        <span>⚡ Muat Template Video & Panduan</span>
                    </button>
                </div>
            </div>

            {{-- Card Navigasi Langkah Modul --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Daftar Komponen Inti</h3>
                <div class="space-y-1.5 text-xs">
                    <a href="{{ route('teacher.modules.pre-test.edit', $module) }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors text-slate-600">
                        <span>1. Pre-test</span>
                        <span class="text-[10px] font-bold {{ $module->has_pre_test ? 'text-emerald-600' : 'text-slate-400' }}">{{ $module->has_pre_test ? 'ON' : 'OFF' }}</span>
                    </a>
                    <a href="{{ route('teacher.modules.materi.edit', $module) }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors text-slate-600">
                        <span>2. Materi & PPT</span>
                        <span class="text-[10px] font-bold {{ $module->has_materi ? 'text-emerald-600' : 'text-slate-400' }}">{{ $module->has_materi ? 'ON' : 'OFF' }}</span>
                    </a>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-red-50 text-red-700 font-bold border border-red-200/60">
                        <span>3. Video YouTube</span>
                        <span class="text-[10px] uppercase">Sedang Diedit</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    // Toggle Status Controller
    function toggleVideoStatus(isActive) {
        const track = document.getElementById('video-toggle-track');
        const thumb = document.getElementById('video-toggle-thumb');
        const badge = document.getElementById('toggle-status-badge');
        const sideStatus = document.getElementById('side-status-indicator');

        if (isActive) {
            track.classList.remove('bg-slate-200', 'border-slate-400', 'hover:border-slate-500');
            track.classList.add('bg-emerald-500', 'border-emerald-600');
            thumb.style.transform = 'translateX(20px)';

            badge.classList.remove('bg-slate-100', 'text-slate-600', 'border-slate-300');
            badge.classList.add('bg-emerald-100', 'text-emerald-800', 'border-emerald-300');
            badge.textContent = 'Aktif (ON)';

            if (sideStatus) {
                sideStatus.className = 'font-bold text-emerald-600';
                sideStatus.textContent = '✓ Aktif';
            }
        } else {
            track.classList.remove('bg-emerald-500', 'border-emerald-600');
            track.classList.add('bg-slate-200', 'border-slate-400', 'hover:border-slate-500');
            thumb.style.transform = 'translateX(0px)';

            badge.classList.remove('bg-emerald-100', 'text-emerald-800', 'border-emerald-300');
            badge.classList.add('bg-slate-100', 'text-slate-600', 'border-slate-300');
            badge.textContent = 'Nonaktif (OFF)';

            if (sideStatus) {
                sideStatus.className = 'font-bold text-slate-400';
                sideStatus.textContent = '○ Non-Aktif';
            }
        }
    }

    // YouTube Video ID extraction helper
    function extractYouTubeId(url) {
        if (!url) return null;
        url = url.trim();

        // Direct 11 char ID
        if (/^[a-zA-Z0-9_-]{11}$/.test(url)) {
            return url;
        }

        // Try extracting query param 'v'
        try {
            const parsed = new URL(url.startsWith('http') ? url : 'https://' + url);
            const v = parsed.searchParams.get('v');
            if (v && /^[a-zA-Z0-9_-]{11}$/.test(v)) {
                return v;
            }
        } catch (e) {}

        // Match youtu.be, embed, shorts, live, v
        const regExp = /(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|shorts\/|live\/))([a-zA-Z0-9_-]{11})/i;
        const match = url.match(regExp);
        if (match && match[1]) {
            return match[1];
        }

        // General fallback match
        const fallbackMatch = url.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
        if (fallbackMatch && fallbackMatch[1]) {
            return fallbackMatch[1];
        }

        return null;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlInput = document.getElementById('youtube_url');
        const checkBtn = document.getElementById('btn-check-url');
        const iframe = document.getElementById('youtube-iframe');
        const placeholder = document.getElementById('player-placeholder');
        const badge = document.getElementById('youtube-status-badge');
        const openYtBtn = document.getElementById('btn-open-youtube');

        function updatePlayerPreview() {
            const url = urlInput.value;
            const id = extractYouTubeId(url);

            if (id) {
                iframe.src = `https://www.youtube-nocookie.com/embed/${id}?rel=0`;
                iframe.classList.remove('hidden');
                placeholder.classList.add('hidden');

                badge.className = 'px-3 py-1 text-xs font-bold rounded-full border bg-emerald-100 text-emerald-800 border-emerald-300';
                badge.textContent = `✓ ID: ${id} (Valid)`;

                if (openYtBtn) {
                    openYtBtn.href = `https://www.youtube.com/watch?v=${id}`;
                    openYtBtn.classList.remove('hidden');
                }
            } else {
                iframe.src = '';
                iframe.classList.add('hidden');
                placeholder.classList.remove('hidden');

                if (url && url.trim().length > 0) {
                    badge.className = 'px-3 py-1 text-xs font-bold rounded-full border bg-rose-100 text-rose-800 border-rose-300';
                    badge.textContent = '❌ Format URL Tidak Dikenali';
                } else {
                    badge.className = 'px-3 py-1 text-xs font-bold rounded-full border bg-slate-100 text-slate-600 border-slate-200';
                    badge.textContent = 'Belum Ada Tautan';
                }

                if (openYtBtn) {
                    openYtBtn.classList.add('hidden');
                }
            }
        }

        urlInput.addEventListener('input', updatePlayerPreview);
        urlInput.addEventListener('change', updatePlayerPreview);
        if (checkBtn) checkBtn.addEventListener('click', updatePlayerPreview);

        // Initial preview load
        updatePlayerPreview();

        // Guiding Questions List management
        const questionsList = document.getElementById('guiding-questions-list');
        const addBtn = document.getElementById('btn-add-question');
        const sidePointsCount = document.getElementById('side-points-count');

        function reindexQuestions() {
            const rows = questionsList.querySelectorAll('.question-row');
            rows.forEach((row, idx) => {
                const num = row.querySelector('.row-number');
                if (num) num.textContent = idx + 1;
            });
            if (sidePointsCount) {
                sidePointsCount.textContent = `${rows.length} Poin`;
            }
        }

        window.removeQuestionRow = function(btn) {
            const rows = questionsList.querySelectorAll('.question-row');
            if (rows.length <= 1) {
                const input = rows[0].querySelector('input');
                if (input) input.value = '';
                return;
            }
            const row = btn.closest('.question-row');
            if (row) {
                row.remove();
                reindexQuestions();
            }
        };

        if (addBtn) {
            addBtn.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'question-row flex items-center gap-2 group fade-in-item';
                row.innerHTML = `
                    <span class="row-number w-7 h-7 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                        1
                    </span>
                    <input type="text"
                           name="guiding_questions[]"
                           value=""
                           placeholder="Tuliskan pertanyaan pemantik atau poin fokus..."
                           class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <button type="button"
                            onclick="removeQuestionRow(this)"
                            title="Hapus poin panduan"
                            class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                `;
                questionsList.appendChild(row);
                reindexQuestions();
                row.querySelector('input').focus();
            });
        }

        // Fast Template Loader
        window.loadSampleVideoContent = function() {
            const titleInput = document.getElementById('video_title');
            const urlInput = document.getElementById('youtube_url');
            const durInput = document.getElementById('estimated_duration');
            const instInput = document.getElementById('instructions');
            const minCharsInput = document.getElementById('min_summary_chars');
            const minWordsInput = document.getElementById('min_summary_words');
            const toggleInput = document.getElementById('has_video_toggle');

            if (titleInput) titleInput.value = "Video Pembelajaran: Pengenalan Konsep & Perancangan Basis Data";
            if (urlInput) urlInput.value = "https://www.youtube.com/watch?v=HXV3zeQKqGY";
            if (durInput) durInput.value = "20";
            if (instInput) instInput.value = "Simak video tutorial di samping dari awal hingga akhir. Catat konsep utama normalisasi data, pembuatan tabel, dan hubungan antar-entitas (Entity Relationship). Tuliskan ringkasan pemahaman Anda secara terstruktur pada kolom yang disediakan.";
            if (minCharsInput) minCharsInput.value = "100";
            if (minWordsInput) minWordsInput.value = "20";

            if (toggleInput && !toggleInput.checked) {
                toggleInput.checked = true;
                toggleVideoStatus(true);
            }

            // Questions template
            const sampleQuestions = [
                "Jelaskan pengertian basis data relasional menurut penjelasan dalam video!",
                "Sebutkan 3 tahapan utama dalam merancang tabel dan relasi Primary Key - Foreign Key!",
                "Apa keuntungan menerapkan prinsip normalisasi database pada sistem informasi sekolah?",
            ];

            questionsList.innerHTML = '';
            sampleQuestions.forEach((q) => {
                const row = document.createElement('div');
                row.className = 'question-row flex items-center gap-2 group fade-in-item';
                row.innerHTML = `
                    <span class="row-number w-7 h-7 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                        1
                    </span>
                    <input type="text"
                           name="guiding_questions[]"
                           value="${q}"
                           placeholder="Tuliskan pertanyaan pemantik atau poin fokus..."
                           class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <button type="button"
                            onclick="removeQuestionRow(this)"
                            title="Hapus poin panduan"
                            class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                `;
                questionsList.appendChild(row);
            });

            reindexQuestions();
            updatePlayerPreview();
        };
    });
</script>
@endpush
