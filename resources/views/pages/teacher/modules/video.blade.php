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
    <span class="font-semibold text-slate-800">Komponen Inti: Multi-Video & Ringkasan</span>
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
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
                <span class="flex items-center gap-1.5 text-red-200">
                    <span>🎬</span>
                    <span>Komponen Inti — Komponen 3</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-400/20 text-amber-300 border border-amber-400/40 uppercase tracking-wider">
                    Multi-Video YouTube
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">Integrasi Video & Ringkasan YouTube</h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Sematkan satu atau beberapa video pembelajaran interaktif dari YouTube. Siswa dapat menyimak seluruh video dan menuliskan <strong>satu intisari ringkasan terpadu</strong> sebelum beralih ke tahapan berikutnya.
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

    {{-- ══ Main Layout Grid ══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">

        {{-- ── LEFT 3 COLUMNS: Main Form ── --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- 1. Sakelar Utama --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-lg shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Aktivasi Komponen Video & Ringkasan YouTube</h2>
                            <p class="text-xs text-slate-500">Jika diaktifkan, pemutar video dan form ringkasan wajib akan disajikan pada Komponen Inti bagi siswa.</p>
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
            </div>

            {{-- 2. DAFTAR VIDEO YOUTUBE (MULTI-VIDEO BUILDER) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-lg shrink-0">
                            🎬
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Daftar Video Pembelajaran YouTube</h2>
                            <p class="text-xs text-slate-500">Tambahkan satu atau beberapa video YouTube. Anda dapat menambah atau menghapus video sesuai kebutuhan materi.</p>
                        </div>
                    </div>

                    <button type="button"
                            id="btn-add-video"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-bold shadow-md shadow-red-600/25 transition-all self-start sm:self-auto shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span>+ Tambah Video Baru</span>
                    </button>
                </div>

                {{-- Container Kartu Video Dinamis --}}
                <div id="videos-container" class="space-y-6">
                    @php
                        $videosList = old('videos', $data['videos'] ?? []);
                        if (empty($videosList)) {
                            $videosList = [
                                [
                                    'title'       => 'Video Pembelajaran 1: ' . $module->title,
                                    'url'         => $data['youtube_url'] ?? '',
                                    'duration'    => 15,
                                    'id'          => $data['youtube_id'] ?? '',
                                ]
                            ];
                        }
                    @endphp

                    @foreach($videosList as $vIndex => $vItem)
                        @php
                            $vUrl = $vItem['url'] ?? '';
                            $vTitle = $vItem['title'] ?? ('Video Pembelajaran ' . ($vIndex + 1));
                            $vDescription = $vItem['description'] ?? ($vItem['keterangan'] ?? '');
                            $vDuration = $vItem['duration'] ?? 15;
                            $vId = $vItem['id'] ?? \App\Http\Controllers\Teacher\VideoController::extractYoutubeId($vUrl);
                        @endphp

                        <div class="video-card bg-slate-50/70 border border-slate-200/90 rounded-2xl p-5 space-y-4 fade-in-item transition-all hover:border-red-300"
                             data-index="{{ $vIndex }}">
                            
                            {{-- Header Kartu Video: Badge & Tombol Hapus --}}
                            <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-200/80">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="video-number-badge px-3 py-1 text-xs font-black rounded-lg bg-red-600 text-white shadow-xs">
                                        Video #<span class="num-text">{{ $vIndex + 1 }}</span>
                                    </span>
                                    <span class="video-status-badge px-2.5 py-0.5 text-[11px] font-bold rounded-md border {{ !empty($vId) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-200 text-slate-600 border-slate-300' }}">
                                        {{ !empty($vId) ? '✓ Valid (ID: ' . $vId . ')' : 'Belum Ada Tautan' }}
                                    </span>
                                    <a href="{{ !empty($vId) ? 'https://www.youtube.com/watch?v=' . $vId : '#' }}"
                                       target="_blank"
                                       class="video-external-btn {{ empty($vId) ? 'hidden' : '' }} text-[11px] font-bold text-red-600 hover:text-red-700 bg-red-50 border border-red-200 px-2 py-0.5 rounded-md transition-colors">
                                        Buka di YouTube ↗
                                    </a>
                                </div>

                                <button type="button"
                                        onclick="removeVideoCard(this)"
                                        title="Hapus video ini dari daftar"
                                        class="btn-delete-video inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-rose-600 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 transition-colors shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    <span>Hapus Video</span>
                                </button>
                            </div>

                            {{-- Input Field: Judul Video --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Judul Video <span class="text-rose-500">*</span>
                                </label>
                                <input type="text"
                                       name="videos[{{ $vIndex }}][title]"
                                       value="{{ $vTitle }}"
                                       required
                                       placeholder="Contoh: Video 1: Pengenalan Komponen & Teori Dasar"
                                       class="video-title-input w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all">
                            </div>

                            {{-- Input URL YouTube --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    URL / Tautan Video YouTube <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           name="videos[{{ $vIndex }}][url]"
                                           value="{{ $vUrl }}"
                                           placeholder="Contoh: https://www.youtube.com/watch?v=... atau https://youtu.be/..."
                                           oninput="handleVideoUrlChange(this)"
                                           class="video-url-input w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-mono text-slate-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all">
                                </div>
                            </div>

                            {{-- Input Keterangan / Catatan Video --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    Keterangan / Catatan Video <span class="text-slate-400 font-normal">(Opsional)</span>
                                </label>
                                <textarea name="videos[{{ $vIndex }}][description]"
                                          rows="2"
                                          placeholder="Tuliskan keterangan singkat, petunjuk menyimak, atau poin penting khusus untuk video ini..."
                                          class="video-description-input w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs text-slate-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all leading-relaxed">{{ $vDescription }}</textarea>
                            </div>

                            {{-- Live Preview Embed Player --}}
                            <div class="pt-1">
                                <div class="player-box relative w-full aspect-video bg-slate-950 rounded-2xl overflow-hidden border border-slate-300 shadow-inner flex flex-col items-center justify-center text-center p-4 text-slate-400">
                                    <div class="player-placeholder {{ !empty($vId) ? 'hidden' : '' }} flex flex-col items-center justify-center space-y-2">
                                        <div class="w-12 h-12 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-red-500 shadow-md">
                                            <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-300">Belum ada video yang dimuat</p>
                                            <p class="text-[11px] text-slate-500">Tempel URL YouTube di atas untuk melihat tampilan video</p>
                                        </div>
                                    </div>

                                    <iframe src="{{ !empty($vId) ? 'https://www.youtube-nocookie.com/embed/' . $vId . '?rel=0' : '' }}"
                                            class="player-iframe w-full h-full absolute inset-0 {{ empty($vId) ? 'hidden' : '' }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen></iframe>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Tombol Tambah Video Bawah --}}
                <div class="pt-2">
                    <button type="button"
                            onclick="addNewVideoCard()"
                            class="w-full py-3 px-4 rounded-2xl border-2 border-dashed border-red-300 hover:border-red-500 bg-red-50/40 hover:bg-red-50 text-red-700 text-xs font-extrabold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span>+ Tambah Video Pembelajaran YouTube Lainnya</span>
                    </button>
                </div>
            </div>

            {{-- 3. PETUNJUK PENGERJAAN & SATU RINGKASAN TERPADU --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg shrink-0">
                        📝
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Petunjuk Pengerjaan & Form Ringkasan Siswa</h2>
                        <p class="text-xs text-slate-500">Siswa akan menyimak seluruh video di atas, kemudian menyusun <strong>1 (satu) ringkasan intisari terpadu</strong>.</p>
                    </div>
                </div>

                {{-- Banner Edukasi Single Summary --}}
                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-900 text-xs space-y-1">
                    <p class="font-bold flex items-center gap-1.5 text-amber-800">
                        <span>💡</span>
                        <span>Satu Ringkasan untuk Seluruh Video:</span>
                    </p>
                    <p class="text-[11px] text-amber-700 leading-relaxed">
                        Meskipun Anda menambahkan banyak video, siswa hanya perlu mengisi 1 kolom resume yang merangkum keseluruhan poin penting dari semua video pembelajaran yang Anda sematkan.
                    </p>
                </div>

                {{-- Instruksi Ringkasan --}}
                <div class="space-y-1.5">
                    <label for="instructions" class="block text-xs font-bold text-slate-700">
                        Petunjuk Pengerjaan Ringkasan bagi Siswa
                    </label>
                    <textarea name="instructions"
                              id="instructions"
                              rows="4"
                              placeholder="Tuliskan arahan bagi siswa terkait apa yang perlu mereka catat dan perhatikan selama menonton seluruh video..."
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

            {{-- 4. Poin Panduan / Pertanyaan Pemantik Ringkasan --}}
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
                        <span>Tambah Poin</span>
                    </button>
                </div>

                <div id="guiding-questions-list" class="space-y-3 pt-1">
                    @php
                        $questions = old('guiding_questions', $data['guiding_questions'] ?? []);
                        if (empty($questions)) {
                            $questions = [
                                'Apa konsep atau topik utama yang dijelaskan dalam video-video ini?',
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

        </div>

        {{-- ── RIGHT 1 COLUMN: Sidebar Status & Actions ── --}}
        <div class="xl:col-span-1 space-y-6 sticky top-6">

            {{-- Card Ringkasan Modul & Status --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Ringkasan Video</h3>
                
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
                        <span>Status Komponen:</span>
                        <span id="side-status-indicator" class="font-bold {{ $module->has_video ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $module->has_video ? '✓ Aktif' : '○ Non-Aktif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Total Video:</span>
                        <span id="side-videos-count" class="font-bold text-red-600">
                            {{ count($videosList) }} Video
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

            {{-- Card Tombol Aksi --}}
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
                            onclick="loadSampleMultiVideoContent()"
                            class="w-full py-2 px-3 text-[11px] font-bold text-blue-600 hover:text-blue-700 bg-blue-50/70 hover:bg-blue-50 rounded-xl transition-colors flex items-center justify-center gap-1.5">
                        <span>⚡ Muat Template Multi-Video</span>
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
                        <span>3. Multi-Video YouTube</span>
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

    // Handler ketika URL video berubah
    function handleVideoUrlChange(inputElem) {
        const card = inputElem.closest('.video-card');
        if (!card) return;

        const url = inputElem.value.trim();
        const id = extractYouTubeId(url);
        const iframe = card.querySelector('.player-iframe');
        const placeholder = card.querySelector('.player-placeholder');
        const badge = card.querySelector('.video-status-badge');
        const externalBtn = card.querySelector('.video-external-btn');

        if (id) {
            if (iframe) {
                iframe.src = `https://www.youtube-nocookie.com/embed/${id}?rel=0`;
                iframe.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');

            if (badge) {
                badge.className = 'video-status-badge px-2.5 py-0.5 text-[11px] font-bold rounded-md border bg-emerald-100 text-emerald-800 border-emerald-300';
                badge.textContent = `✓ Valid (ID: ${id})`;
            }

            if (externalBtn) {
                externalBtn.href = `https://www.youtube.com/watch?v=${id}`;
                externalBtn.classList.remove('hidden');
            }
        } else {
            if (iframe) {
                iframe.src = '';
                iframe.classList.add('hidden');
            }
            if (placeholder) placeholder.classList.remove('hidden');

            if (badge) {
                if (url.length > 0) {
                    badge.className = 'video-status-badge px-2.5 py-0.5 text-[11px] font-bold rounded-md border bg-rose-100 text-rose-800 border-rose-300';
                    badge.textContent = '❌ Format URL Tidak Valid';
                } else {
                    badge.className = 'video-status-badge px-2.5 py-0.5 text-[11px] font-bold rounded-md border bg-slate-200 text-slate-600 border-slate-300';
                    badge.textContent = 'Belum Ada Tautan';
                }
            }

            if (externalBtn) {
                externalBtn.classList.add('hidden');
            }
        }
    }

    // Reindex video cards (numbers and input array names)
    function reindexVideos() {
        const container = document.getElementById('videos-container');
        const cards = container.querySelectorAll('.video-card');
        const sideCount = document.getElementById('side-videos-count');

        cards.forEach((card, idx) => {
            card.setAttribute('data-index', idx);
            const numText = card.querySelector('.num-text');
            if (numText) numText.textContent = idx + 1;

            // Update input names
            const titleInput = card.querySelector('.video-title-input');
            if (titleInput) titleInput.setAttribute('name', `videos[${idx}][title]`);

            const urlInput = card.querySelector('.video-url-input');
            if (urlInput) urlInput.setAttribute('name', `videos[${idx}][url]`);

            const descInput = card.querySelector('.video-description-input');
            if (descInput) descInput.setAttribute('name', `videos[${idx}][description]`);
        });

        if (sideCount) {
            sideCount.textContent = `${cards.length} Video`;
        }
    }

    // Hapus Kartu Video
    window.removeVideoCard = function(btn) {
        const container = document.getElementById('videos-container');
        const cards = container.querySelectorAll('.video-card');

        if (cards.length <= 1) {
            if (confirm('Ini adalah video satu-satunya. Apakah Anda ingin mengosongkan tautannya?')) {
                const firstCard = cards[0];
                const urlInput = firstCard.querySelector('.video-url-input');
                const titleInput = firstCard.querySelector('.video-title-input');
                const descInput = firstCard.querySelector('.video-description-input');
                if (urlInput) {
                    urlInput.value = '';
                    handleVideoUrlChange(urlInput);
                }
                if (titleInput) titleInput.value = '';
                if (descInput) descInput.value = '';
            }
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus video ini dari daftar?')) {
            const card = btn.closest('.video-card');
            if (card) {
                card.remove();
                reindexVideos();
            }
        }
    };

    // Tambah Kartu Video Baru
    window.addNewVideoCard = function(initialData = null) {
        const container = document.getElementById('videos-container');
        const cards = container.querySelectorAll('.video-card');
        const nextIndex = cards.length;
        const defaultTitle = initialData?.title || `Video Pembelajaran ${nextIndex + 1}`;
        const defaultUrl = initialData?.url || '';
        const defaultDesc = initialData?.description || initialData?.keterangan || '';

        const newCard = document.createElement('div');
        newCard.className = 'video-card bg-slate-50/70 border border-slate-200/90 rounded-2xl p-5 space-y-4 fade-in-item transition-all hover:border-red-300';
        newCard.setAttribute('data-index', nextIndex);

        newCard.innerHTML = `
            <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-200/80">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="video-number-badge px-3 py-1 text-xs font-black rounded-lg bg-red-600 text-white shadow-xs">
                        Video #<span class="num-text">${nextIndex + 1}</span>
                    </span>
                    <span class="video-status-badge px-2.5 py-0.5 text-[11px] font-bold rounded-md border bg-slate-200 text-slate-600 border-slate-300">
                        Belum Ada Tautan
                    </span>
                    <a href="#"
                       target="_blank"
                       class="video-external-btn hidden text-[11px] font-bold text-red-600 hover:text-red-700 bg-red-50 border border-red-200 px-2 py-0.5 rounded-md transition-colors">
                        Buka di YouTube ↗
                    </a>
                </div>

                <button type="button"
                        onclick="removeVideoCard(this)"
                        title="Hapus video ini dari daftar"
                        class="btn-delete-video inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-rose-600 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    <span>Hapus Video</span>
                </button>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Judul Video <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="videos[${nextIndex}][title]"
                       value="${defaultTitle}"
                       required
                       placeholder="Contoh: Video 2: Prosedur Praktik & Penerapan"
                       class="video-title-input w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">
                    URL / Tautan Video YouTube <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="videos[${nextIndex}][url]"
                           value="${defaultUrl}"
                           placeholder="Contoh: https://www.youtube.com/watch?v=... atau https://youtu.be/..."
                           oninput="handleVideoUrlChange(this)"
                           class="video-url-input w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-mono text-slate-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">
                    Keterangan / Catatan Video <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <textarea name="videos[${nextIndex}][description]"
                          rows="2"
                          placeholder="Tuliskan keterangan singkat, petunjuk menyimak, atau poin penting khusus untuk video ini..."
                          class="video-description-input w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs text-slate-900 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all leading-relaxed">${defaultDesc}</textarea>
            </div>

            <div class="pt-1">
                <div class="player-box relative w-full aspect-video bg-slate-950 rounded-2xl overflow-hidden border border-slate-300 shadow-inner flex flex-col items-center justify-center text-center p-4 text-slate-400">
                    <div class="player-placeholder flex flex-col items-center justify-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-red-500 shadow-md">
                            <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-300">Belum ada video yang dimuat</p>
                            <p class="text-[11px] text-slate-500">Tempel URL YouTube di atas untuk melihat tampilan video</p>
                        </div>
                    </div>

                    <iframe src=""
                            class="player-iframe w-full h-full absolute inset-0 hidden"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                </div>
            </div>
        `;

        container.appendChild(newCard);
        reindexVideos();

        if (defaultUrl) {
            const urlInput = newCard.querySelector('.video-url-input');
            if (urlInput) handleVideoUrlChange(urlInput);
        }

        newCard.querySelector('.video-title-input').focus();
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Init add video button in header
        const btnAdd = document.getElementById('btn-add-video');
        if (btnAdd) {
            btnAdd.addEventListener('click', function() {
                addNewVideoCard();
            });
        }

        // Guiding Questions List management
        const questionsList = document.getElementById('guiding-questions-list');
        const addQBtn = document.getElementById('btn-add-question');
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

        if (addQBtn) {
            addQBtn.addEventListener('click', function() {
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

        // Fast Template Loader for Multi-Video
        window.loadSampleMultiVideoContent = function() {
            const toggleInput = document.getElementById('has_video_toggle');
            if (toggleInput && !toggleInput.checked) {
                toggleInput.checked = true;
                toggleVideoStatus(true);
            }

            const container = document.getElementById('videos-container');
            container.innerHTML = '';

            const sampleVideos = [
                {
                    title: "Video 1: Pengenalan Konsep & Teori Dasar",
                    url: "https://www.youtube.com/watch?v=HXV3zeQKqGY",
                    description: "Penjelasan dasar mengenai konsep arsitektur rangkaian dan komponen pendukung."
                },
                {
                    title: "Video 2: Prosedur Praktik & Langkah Kerja Mandiri",
                    url: "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
                    description: "Demonstrasi langkah perakitan, uji coba modul, dan standar operasional keselamatan kerja."
                }
            ];

            sampleVideos.forEach(v => {
                addNewVideoCard(v);
            });

            const instInput = document.getElementById('instructions');
            if (instInput) {
                instInput.value = "Simak seluruh rangkaian video tutorial di atas secara seksama. Catat konsep utama, langkah-langkah kerja, dan keselamatan kerja yang dijelaskan. Tuliskan 1 (satu) ringkasan intisari materi terpadu yang merangkum pemahaman Anda dari kedua video tersebut pada kolom yang disediakan.";
            }

            // Questions template
            const sampleQuestions = [
                "Jelaskan pengertian dan konsep utama yang dipaparkan pada Video 1!",
                "Sebutkan tahapan prosedur kerja dan hal penting yang harus diperhatikan pada Video 2!",
                "Bagaimana sintesis dan penerapan materi dari seluruh video dalam praktik kejuruan Anda?",
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
        };
    });
</script>
@endpush

