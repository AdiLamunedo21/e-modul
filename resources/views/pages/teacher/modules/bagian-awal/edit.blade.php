@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Bagian Awal — ' . $module->title)
@section('page-title', 'Editor Bagian Awal Modul')

@push('head')
<style>
    .prose-editor {
        min-height: 140px;
        line-height: 1.7;
        resize: vertical;
    }
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
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <a href="{{ route('teacher.modules.show', $module) }}" class="hover:text-blue-600 transition-colors truncate max-w-xs font-medium">{{ $module->title }}</a>
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800">Bagian Awal (4 Komponen)</span>
</nav>

{{-- ══ Flash Messages ══ --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm fade-in-item">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800 shadow-sm fade-in-item">
    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
    <span>{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800 shadow-sm fade-in-item">
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

{{-- ══ Header Card ══ --}}
<div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200/80 text-xs font-bold tracking-wide text-indigo-900 shadow-xs">
                <span class="flex items-center gap-1.5 text-indigo-700">
                    <span class="w-5 h-5 rounded-md bg-indigo-600 text-white flex items-center justify-center font-black text-[10px]">1</span>
                    <span>Bagian Awal</span>
                </span>
                <span class="text-indigo-300">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-indigo-100/80 text-indigo-800 border border-indigo-200 uppercase tracking-wider">
                    4 Komponen Identitas & Pengantar
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                Editor Bagian Awal Modul
            </h1>
            <p class="text-slate-500 text-sm leading-relaxed max-w-2xl font-normal">
                Kelola 4 komponen identitas pembuka modul pembelajaran: Halaman Sampul (Cover), Kata Pengantar, Daftar Isi navigasi, dan Petunjuk Penggunaan bagi siswa & guru.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 hover:border-slate-400 text-xs font-bold transition-all flex items-center gap-2 shadow-xs">
                ← Kembali ke Detail
            </a>
            <button form="bagian-awal-form" type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

{{-- ══ Form 4 Komponen Bagian Awal ══ --}}
<form id="bagian-awal-form"
      action="{{ route('teacher.modules.bagian-awal.update', $module) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    {{-- ══ Main Layout Grid (Standard 12 Columns) ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- ── Left Side: 4 Component Form Cards (8 Cols) ── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- ── 1. HALAMAN SAMPUL (COVER IMAGE) ── --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">
                                📷
                            </span>
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Halaman Sampul (Cover)</h2>
                                <p class="text-xs text-slate-500">Identitas visual modul pembelajaran yang tampil pada halaman pertama siswa.</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 shrink-0">
                            Komponen 1 / 4
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                        {{-- Upload Box --}}
                        <div class="space-y-3">
                            <label class="block text-xs font-extrabold uppercase tracking-wide text-slate-700">
                                Berkas Gambar Cover
                            </label>
                            <div class="relative border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl p-6 text-center transition-colors bg-slate-50/50 hover:bg-indigo-50/20 group cursor-pointer"
                                 onclick="document.getElementById('cover-file-input').click()">
                                <input type="file"
                                       id="cover-file-input"
                                       name="cover_image"
                                       accept="image/jpeg,image/png,image/webp"
                                       class="hidden"
                                       onchange="previewCoverImage(event)">
                                
                                <div class="space-y-2">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">
                                        Klik untuk memilih gambar cover
                                    </p>
                                    <p class="text-[11px] text-slate-400">
                                        Format JPG, PNG, WebP (Maks. 3MB)
                                    </p>
                                </div>
                            </div>

                            @error('cover_image')
                                <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror

                            @if(!empty($data['cover_image_path']))
                                <div class="pt-1">
                                    <label class="flex items-center gap-2 text-xs font-medium text-rose-600 cursor-pointer hover:text-rose-800 transition-colors">
                                        <input type="checkbox"
                                               name="remove_cover"
                                               value="1"
                                               onchange="toggleRemoveCover(this)"
                                               class="rounded text-rose-600 focus:ring-rose-500">
                                        <span>Hapus cover saat ini & gunakan cover standar</span>
                                    </label>
                                </div>
                            @endif
                        </div>

                        {{-- Preview Box --}}
                        <div class="space-y-3">
                            <label class="block text-xs font-extrabold uppercase tracking-wide text-slate-700">
                                Pratinjau Sampul Modul
                            </label>
                            <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50 flex items-center justify-center min-h-[190px]">
                                <div id="cover-preview-placeholder" class="text-center space-y-1.5 text-slate-400 py-4 {{ !empty($data['cover_image_path']) ? 'hidden' : '' }}">
                                    <span class="text-3xl block">🖼️</span>
                                    <p class="text-xs font-semibold text-slate-500">Belum ada cover khusus.</p>
                                    <p class="text-[11px] text-slate-400">Modul menggunakan cover standar sistem.</p>
                                </div>
                                <img id="cover-preview-img"
                                     src="{{ !empty($data['cover_image_path']) ? asset('storage/' . $data['cover_image_path']) : '#' }}"
                                     alt="Cover Preview"
                                     class="{{ empty($data['cover_image_path']) ? 'hidden' : '' }} max-h-48 max-w-full rounded-xl object-contain shadow-sm border border-slate-200 transition-opacity duration-200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 2. KATA PENGANTAR ── --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">
                                ✏️
                            </span>
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Kata Pengantar</h2>
                                <p class="text-xs text-slate-500">Prakata guru pengampu yang menyapa dan memperkenalkan materi kepada siswa.</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 shrink-0">
                            Komponen 2 / 4
                        </span>
                    </div>

                    <div class="space-y-2">
                        <label for="kata_pengantar" class="block text-xs font-extrabold uppercase tracking-wide text-slate-700">
                            Teks Kata Pengantar
                        </label>
                        <textarea id="kata_pengantar"
                                  name="kata_pengantar"
                                  rows="6"
                                  class="prose-editor w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-4 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all leading-relaxed"
                                  placeholder="Puji syukur kami panjatkan ke hadirat Tuhan Yang Maha Esa, e-modul ini disusun untuk memandu peserta didik dalam memahami konsep...">{{ old('kata_pengantar', $data['kata_pengantar']) }}</textarea>
                        @error('kata_pengantar')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400">
                            Tuliskan sambutan pembuka, pengantar topik materi, dan motivasi belajar untuk peserta didik.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── 3. DAFTAR ISI ── --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">
                                📋
                            </span>
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Daftar Isi (Navigasi Modul)</h2>
                                <p class="text-xs text-slate-500">Struktur bab, topik materi, dan navigasi halaman modul pembelajaran.</p>
                            </div>
                        </div>
                        <button type="button"
                                onclick="addDaftarIsiRow()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors shrink-0 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Tambah Baris
                        </button>
                    </div>

                    <div id="daftar-isi-list" class="space-y-3 mb-4">
                        @php 
                            $daftarIsiItems = old('daftar_isi', $data['daftar_isi'] ?? []); 
                            if (empty($daftarIsiItems)) {
                                $daftarIsiItems = [
                                    ['judul' => 'Bagian Awal Modul', 'anchor' => '#sec-bagian-awal'],
                                    ['judul' => 'Pendahuluan & Capaian', 'anchor' => '#sec-pendahuluan'],
                                    ['judul' => 'Kegiatan Belajar (Materi & Praktik)', 'anchor' => '#sec-kegiatan-belajar'],
                                    ['judul' => 'Evaluasi & Latihan', 'anchor' => '#sec-evaluasi-latihan'],
                                    ['judul' => 'Bagian Akhir & Post-Test', 'anchor' => '#sec-bagian-akhir'],
                                ]; 
                            }
                        @endphp

                        @foreach($daftarIsiItems as $index => $item)
                            @php
                                $itemJudul = is_array($item) ? ($item['judul'] ?? '') : (is_string($item) ? $item : '');
                                $itemAnchor = is_array($item) ? ($item['anchor'] ?? '') : '';
                            @endphp
                            <div class="repeater-row flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80">
                                <span class="row-number w-7 h-7 rounded-xl bg-white border border-slate-200 text-slate-500 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input type="text"
                                           name="daftar_isi[{{ $index }}][judul]"
                                           value="{{ $itemJudul }}"
                                           placeholder="Judul Bab / Sub-bab"
                                           class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all font-medium">
                                    <input type="text"
                                           name="daftar_isi[{{ $index }}][anchor]"
                                           value="{{ $itemAnchor }}"
                                           placeholder="Target Link / Anchor (opsional, contoh: #materi-1)"
                                           class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all font-mono">
                                </div>
                                <button type="button"
                                        onclick="removeRepeaterRow(this)"
                                        class="w-8 h-8 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                                        title="Hapus Baris">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button"
                            onclick="addDaftarIsiRow()"
                            class="w-full py-3 rounded-2xl border-2 border-dashed border-slate-200 hover:border-indigo-400 text-xs font-bold text-slate-500 hover:text-indigo-600 hover:bg-indigo-50/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Butir Daftar Isi Baru
                    </button>
                </div>
            </div>

            {{-- ── 4. PETUNJUK PENGGUNAAN ── --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">
                                💡
                            </span>
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Petunjuk Penggunaan</h2>
                                <p class="text-xs text-slate-500">Panduan belajar dan petunjuk teknis mempelajari e-modul bagi peserta didik maupun guru.</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 shrink-0">
                            Komponen 4 / 4
                        </span>
                    </div>

                    <div class="space-y-2">
                        <label for="petunjuk_penggunaan" class="block text-xs font-extrabold uppercase tracking-wide text-slate-700">
                            Teks Petunjuk Penggunaan Modul
                        </label>
                        <textarea id="petunjuk_penggunaan"
                                  name="petunjuk_penggunaan"
                                  rows="6"
                                  class="prose-editor w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-4 text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all leading-relaxed"
                                  placeholder="Petunjuk Bagi Siswa:&#10;1. Bacalah capaian pembelajaran dengan seksama.&#10;2. Kerjakan soal pre-test terlebih dahulu sebelum membaca materi.&#10;3. Simak video pendukung dan pelajari job sheet praktikum.&#10;4. Kerjakan post-test untuk mengukur pemahaman.">{{ old('petunjuk_penggunaan', $data['petunjuk_penggunaan']) }}</textarea>
                        @error('petunjuk_penggunaan')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400">
                            Berikan langkah-langkah belajar terstruktur agar siswa dapat belajar mandiri secara efektif.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ══ Bottom Action Bar ══ --}}
            <div class="flex items-center justify-between gap-4 pt-4">
                <a href="{{ route('teacher.modules.show', $module) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-2xl transition-all shadow-sm">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                    Batal & Kembali
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3 text-xs sm:text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl shadow-lg shadow-indigo-600/25 transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Simpan Bagian Awal (4 Komponen)
                </button>
            </div>

        </div>

        {{-- ── Right Side: Info & Guidelines Column (4 Cols) ── --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Card 1: Ringkasan Status 4 Komponen --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-900">Kelengkapan Bagian Awal</h3>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wide">
                        4 Komponen
                    </span>
                </div>

                <div class="space-y-3">
                    {{-- 1. Cover --}}
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                        <span class="font-medium text-slate-700 flex items-center gap-2">
                            <span>📷</span> Halaman Cover
                        </span>
                        <span class="font-bold {{ !empty($data['cover_image_path']) ? 'text-emerald-600' : 'text-slate-500' }}">
                            {{ !empty($data['cover_image_path']) ? 'Kustom' : 'Standar' }}
                        </span>
                    </div>

                    {{-- 2. Kata Pengantar --}}
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                        <span class="font-medium text-slate-700 flex items-center gap-2">
                            <span>✏️</span> Kata Pengantar
                        </span>
                        <span class="font-bold {{ !empty($data['kata_pengantar']) ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ !empty($data['kata_pengantar']) ? 'Terisi' : 'Belum Diisi' }}
                        </span>
                    </div>

                    {{-- 3. Daftar Isi --}}
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                        <span class="font-medium text-slate-700 flex items-center gap-2">
                            <span>📋</span> Daftar Isi
                        </span>
                        <span class="font-bold text-indigo-600">
                            {{ count($daftarIsiItems ?? []) }} Bab/Topik
                        </span>
                    </div>

                    {{-- 4. Petunjuk Penggunaan --}}
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                        <span class="font-medium text-slate-700 flex items-center gap-2">
                            <span>💡</span> Petunjuk Penggunaan
                        </span>
                        <span class="font-bold {{ !empty($data['petunjuk_penggunaan']) ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ !empty($data['petunjuk_penggunaan']) ? 'Terisi' : 'Belum Diisi' }}
                        </span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Simpan Semua Komponen
                    </button>
                </div>
            </div>

            {{-- Card 2: Panduan Pedagogis Standar Modul --}}
            <div class="rounded-3xl bg-indigo-50/60 border border-indigo-100 p-6 space-y-3">
                <div class="flex items-center gap-2 text-indigo-900 font-bold text-xs">
                    <span class="text-base">💡</span>
                    <span>Panduan Standar Bagian Awal</span>
                </div>
                <p class="text-[11px] text-indigo-900/80 leading-relaxed">
                    Bagian Awal berfungsi memberikan identitas resmi dan orientasi belajar pertama bagi peserta didik.
                </p>
                <ul class="text-[11px] text-indigo-900/80 space-y-1.5 list-disc list-inside">
                    <li>Gunakan gambar sampul berkualitas tinggi & relevan.</li>
                    <li>Sampaikan kata sambutan guru dengan ramah & motivatif.</li>
                    <li>Susun daftar isi bab sesuai alur capaian pembelajaran.</li>
                    <li>Sertakan petunjuk belajar mandiri yang praktis dan jelas.</li>
                </ul>
            </div>

        </div>

    </div>

</form>

@endsection

@push('scripts')
<script>
    function previewCoverImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('cover-preview-img');
            const placeholder = document.getElementById('cover-preview-placeholder');
            if (img) {
                img.src = e.target.result;
                img.classList.remove('hidden');
                img.style.opacity = '1';
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    function toggleRemoveCover(checkbox) {
        const img = document.getElementById('cover-preview-img');
        if (img) {
            img.style.opacity = checkbox.checked ? '0.3' : '1';
        }
    }

    function addDaftarIsiRow() {
        const container = document.getElementById('daftar-isi-list');
        const count = container.querySelectorAll('.repeater-row').length;
        const newIndex = Date.now() + Math.floor(Math.random() * 1000);

        const row = document.createElement('div');
        row.className = 'repeater-row flex items-center gap-3 p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80';
        row.innerHTML = `
            <span class="row-number w-7 h-7 rounded-xl bg-white border border-slate-200 text-slate-500 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                ${count + 1}
            </span>
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input type="text"
                       name="daftar_isi[${newIndex}][judul]"
                       placeholder="Judul Bab / Sub-bab"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all font-medium">
                <input type="text"
                       name="daftar_isi[${newIndex}][anchor]"
                       placeholder="Target Link / Anchor (opsional, contoh: #materi-1)"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-600 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all font-mono">
            </div>
            <button type="button"
                    onclick="removeRepeaterRow(this)"
                    class="w-8 h-8 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                    title="Hapus Baris">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;
        container.appendChild(row);
        updateRowNumbers();
    }

    function removeRepeaterRow(button) {
        const row = button.closest('.repeater-row');
        if (row) {
            row.remove();
            updateRowNumbers();
        }
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#daftar-isi-list .repeater-row');
        rows.forEach((row, i) => {
            const num = row.querySelector('.row-number');
            if (num) num.textContent = i + 1;
        });
    }
</script>
@endpush
