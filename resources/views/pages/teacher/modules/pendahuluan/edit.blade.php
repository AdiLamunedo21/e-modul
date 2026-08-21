@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Pendahuluan — ' . $module->title)
@section('page-title', 'Editor Pendahuluan Modul')

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
    .bloom-chip {
        transition: all 0.15s ease-in-out;
    }
    .bloom-chip:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')

{{-- ══ Breadcrumb ══ --}}
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('teacher.modules.index') }}" class="hover:text-teal-600 font-medium transition-colors">Manajer Modul</a>
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <a href="{{ route('teacher.modules.show', $module) }}" class="hover:text-teal-600 transition-colors truncate max-w-xs font-medium">{{ $module->title }}</a>
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800">Pendahuluan (3 Komponen)</span>
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

{{-- ══ Header Banner ══ --}}
<div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/20 mb-8 relative overflow-hidden border border-emerald-700/40">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-emerald-200">
                    <span class="w-5 h-5 rounded-md bg-emerald-600 text-white flex items-center justify-center font-black text-[10px]">2</span>
                    <span>Pendahuluan</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 uppercase tracking-wider">
                    3 Komponen Capaian & Kerangka Konsep
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                Editor Pendahuluan Modul
            </h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Kelola 3 komponen orientasi dan kerangka konsep belajar: Tujuan Pembelajaran (Capaian Pembelajaran & Taksonomi Bloom), Peta Konsep alur materi, dan Glosarium istilah penting.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                ← Kembali ke Detail
            </a>
            <button form="pendahuluan-form" type="submit"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2 border border-emerald-400/30 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

{{-- ══ Form 3 Komponen Pendahuluan ══ --}}
<form id="pendahuluan-form"
      action="{{ route('teacher.modules.pendahuluan.update', $module) }}"
      method="POST">
    @csrf
    @method('PATCH')

    {{-- ══ Main Layout Grid (Standard 12 Columns) ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- ── Left Side: 3 Component Form Cards (8 Cols) ── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- ── 1. TUJUAN PEMBELAJARAN (CAPAIAN) ── --}}
            <div id="sec-tujuan" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">
                                🎯
                            </span>
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Tujuan Pembelajaran & Capaian</h2>
                                <p class="text-xs text-slate-500">Rumusan capaian, kompetensi, dan target belajar yang harus dicapai peserta didik.</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100 shrink-0">
                            Komponen 1 / 3
                        </span>
                    </div>

                    {{-- Bloom Taxonomy Helper Box --}}
                    <div class="mb-5 rounded-2xl bg-teal-50/60 border border-teal-200/70 p-4">
                        <div class="flex items-center justify-between gap-2 mb-2.5">
                            <p class="text-xs font-bold text-teal-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                                Kata Kerja Operasional Bloom (Klik untuk menyisipkan ke teks):
                            </p>
                            <span class="text-[10px] text-teal-700 font-medium hidden sm:inline">Panduan Pedagogis</span>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach([
                                'Mengidentifikasi', 'Menjelaskan', 'Menerapkan', 'Menganalisis', 'Mengevaluasi',
                                'Merancang', 'Membuat', 'Mendemonstrasikan', 'Membandingkan', 'Menyimpulkan'
                            ] as $kw)
                                <button type="button"
                                        onclick="insertKataKerja('{{ $kw }}')"
                                        class="bloom-chip text-[11px] font-bold bg-white text-teal-800 border border-teal-200/90 hover:bg-teal-600 hover:text-white hover:border-teal-600 px-3 py-1 rounded-xl shadow-xs cursor-pointer">
                                    + {{ $kw }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="tujuan_pembelajaran" class="block text-xs font-extrabold uppercase tracking-wide text-slate-700">
                            Teks Rumusan Tujuan Pembelajaran & Capaian <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="tujuan_pembelajaran"
                                  name="tujuan_pembelajaran"
                                  rows="7"
                                  required
                                  class="prose-editor w-full rounded-2xl border @error('tujuan_pembelajaran') border-rose-400 bg-rose-50/40 @else border-slate-200 bg-slate-50/50 @enderror p-4 text-sm text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all leading-relaxed font-normal"
                                  placeholder="Setelah menyelesaikan modul pembelajaran ini, peserta didik diharapkan mampu:&#10;1. Mengidentifikasi konsep dasar dan fungsi komponen utama.&#10;2. Menerapkan prosedur pemecahan masalah secara tepat.&#10;3. Merancang hasil karya atau solusi berbasis praktik nyata.">{{ old('tujuan_pembelajaran', $data['tujuan_pembelajaran']) }}</textarea>
                        @error('tujuan_pembelajaran')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400">
                            Tuliskan butir-butir capaian kompetensi secara terukur agar siswa mengetahui sasaran hasil belajarnya.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── 2. PETA KONSEP ── --}}
            <div id="sec-peta" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">
                                🗺️
                            </span>
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Peta Konsep (Alur & Hierarki Materi)</h2>
                                <p class="text-xs text-slate-500">Gambaran diagram alur atau struktur keterkaitan konsep materi pembelajaran.</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100 shrink-0">
                            Komponen 2 / 3
                        </span>
                    </div>

                    <div class="mb-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 p-4 flex items-start gap-3">
                        <span class="text-lg shrink-0">💡</span>
                        <p class="text-xs text-amber-900 leading-relaxed">
                            <strong>Tips Penyusunan:</strong> Tuliskan alur hierarki konsep menggunakan format panah alur (contoh: <em>Topik Inti → Sub-Topik 1 → Praktik Langsung → Evaluasi Mandiri</em>) atau jelaskan skema pemetaan topik secara terstruktur.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label for="peta_konsep_text" class="block text-xs font-extrabold uppercase tracking-wide text-slate-700">
                            Deskripsi / Struktur Peta Konsep
                        </label>
                        <textarea id="peta_konsep_text"
                                  name="peta_konsep_text"
                                  rows="6"
                                  class="prose-editor w-full rounded-2xl border border-slate-200 bg-slate-50/50 p-4 text-sm text-slate-800 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all leading-relaxed"
                                  placeholder="Alur Peta Konsep Modul:&#10;1. Fondasi & Pengenalan Konsep&#10;   └── Teori Dasar → Studi Kasus Pengantar&#10;2. Pendalaman Materi & Prosedur Praktis&#10;   └── Langkah Kerja → Pengujian / Simulasi&#10;3. Evaluasi Ketercapaian Kompetensi&#10;   └── Penugasan LKPD → Uji Akhir Post-test">{{ old('peta_konsep_text', $data['peta_konsep_text']) }}</textarea>
                        @error('peta_konsep_text')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400">
                            Membantu siswa memahami urutan bab dan hubungan logis antartopik dalam modul ini.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── 3. GLOSARIUM ── --}}
            <div id="sec-glosarium" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100 mb-6">
                        <div class="flex items-center gap-3.5">
                            <span class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-lg font-bold">
                                📖
                            </span>
                            <div>
                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Glosarium (Istilah Teknis Penting)</h2>
                                <p class="text-xs text-slate-500">Daftar kata kunci, istilah teknis, dan definisi singkat untuk mempermudah pemahaman siswa.</p>
                            </div>
                        </div>
                        <button type="button"
                                onclick="addGlosariumRow()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 transition-colors shrink-0 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Tambah Istilah
                        </button>
                    </div>

                    <div id="glosarium-list" class="space-y-3 mb-4">
                        @php 
                            $glosariumItems = old('glosarium', $data['glosarium'] ?? []); 
                            if (empty($glosariumItems)) {
                                $glosariumItems = [
                                    ['istilah' => 'Kompetensi', 'definisi' => 'Kemampuan kerja setiap individu yang mencakup aspek pengetahuan, keterampilan, dan sikap kerja.'],
                                    ['istilah' => 'Modul Ajar', 'definisi' => 'Sejumlah alat atau sarana media, petunjuk, dan pedoman yang dirancang secara sistematis dan menarik.'],
                                ]; 
                            }
                        @endphp

                        @foreach($glosariumItems as $index => $item)
                            @php
                                $istilah = is_array($item) ? ($item['istilah'] ?? '') : '';
                                $definisi = is_array($item) ? ($item['definisi'] ?? '') : '';
                            @endphp
                            <div class="repeater-row flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80">
                                <span class="row-number w-7 h-7 rounded-xl bg-white border border-slate-200 text-slate-500 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs mt-1">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-12 gap-3">
                                    <div class="sm:col-span-4">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Istilah / Kata Kunci</label>
                                        <input type="text"
                                               name="glosarium[{{ $index }}][istilah]"
                                               value="{{ $istilah }}"
                                               placeholder="Contoh: Algoritma"
                                               class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-semibold">
                                    </div>
                                    <div class="sm:col-span-8">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Definisi / Arti Kata</label>
                                        <textarea name="glosarium[{{ $index }}][definisi]"
                                                  rows="2"
                                                  placeholder="Definisi ringkas dan mudah dipahami oleh siswa..."
                                                  class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all resize-none leading-relaxed">{{ $definisi }}</textarea>
                                    </div>
                                </div>
                                <button type="button"
                                        onclick="removeRepeaterRow(this)"
                                        class="w-8 h-8 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors shrink-0 cursor-pointer mt-1"
                                        title="Hapus Istilah">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button"
                            onclick="addGlosariumRow()"
                            class="w-full py-3 rounded-2xl border-2 border-dashed border-slate-200 hover:border-teal-400 text-xs font-bold text-slate-500 hover:text-teal-700 hover:bg-teal-50/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Entri Glosarium Baru
                    </button>
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
                        class="inline-flex items-center gap-2 px-8 py-3 text-xs sm:text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-2xl shadow-lg shadow-teal-600/25 transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Simpan Pendahuluan (3 Komponen)
                </button>
            </div>

        </div>

        {{-- ── Right Side: Info & Guidelines Column (4 Cols) ── --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Card 1: Ringkasan Status 3 Komponen --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-900">Kelengkapan Pendahuluan</h3>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-teal-50 text-teal-700 border border-teal-100 uppercase tracking-wide">
                        3 Komponen
                    </span>
                </div>

                <div class="space-y-3">
                    {{-- 1. Tujuan Pembelajaran --}}
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                        <span class="font-medium text-slate-700 flex items-center gap-2">
                            <span>🎯</span> Tujuan Pembelajaran
                        </span>
                        <span class="font-bold {{ !empty($data['tujuan_pembelajaran']) ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ !empty($data['tujuan_pembelajaran']) ? 'Terisi' : 'Belum Diisi' }}
                        </span>
                    </div>

                    {{-- 2. Peta Konsep --}}
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                        <span class="font-medium text-slate-700 flex items-center gap-2">
                            <span>🗺️</span> Peta Konsep
                        </span>
                        <span class="font-bold {{ !empty($data['peta_konsep_text']) ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ !empty($data['peta_konsep_text']) ? 'Terisi' : 'Belum Diisi' }}
                        </span>
                    </div>

                    {{-- 3. Glosarium --}}
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                        <span class="font-medium text-slate-700 flex items-center gap-2">
                            <span>📖</span> Glosarium
                        </span>
                        <span class="font-bold text-teal-700">
                            {{ count($glosariumItems ?? []) }} Istilah
                        </span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition-all shadow-md shadow-teal-600/20 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Simpan Semua Komponen
                    </button>
                </div>
            </div>

            {{-- Card 2: Panduan Pedagogis Standar Modul --}}
            <div class="rounded-3xl bg-teal-50/60 border border-teal-100 p-6 space-y-3">
                <div class="flex items-center gap-2 text-teal-900 font-bold text-xs">
                    <span class="text-base">💡</span>
                    <span>Panduan Standar Pendahuluan</span>
                </div>
                <p class="text-[11px] text-teal-900/80 leading-relaxed">
                    Bagian Pendahuluan mengarahkan ekspektasi belajar siswa dengan menentukan sasaran hasil, pemetaan alur materi, dan kejelasan istilah kunci.
                </p>
                <ul class="text-[11px] text-teal-900/80 space-y-1.5 list-disc list-inside">
                    <li>Gunakan kata kerja operasional (Taksonomi Bloom) pada rumusan tujuan.</li>
                    <li>Sajikan keterkaitan antar konsep secara bertahap dan logis.</li>
                    <li>Definisikan kata kunci teknis secara lugas agar mudah dicerna peserta didik.</li>
                </ul>
            </div>

        </div>

    </div>

</form>

@push('scripts')
<script>
    // Menyisipkan Kata Kerja Operasional Taksonomi Bloom ke dalam textarea Tujuan Pembelajaran
    function insertKataKerja(kata) {
        const textarea = document.getElementById('tujuan_pembelajaran');
        if (!textarea) return;

        const cursorPos = textarea.selectionStart;
        const textBefore = textarea.value.substring(0, cursorPos);
        const textAfter = textarea.value.substring(textarea.selectionEnd, textarea.value.length);

        textarea.value = textBefore + kata + ' ' + textAfter;
        textarea.focus();
        const newPos = cursorPos + kata.length + 1;
        textarea.setSelectionRange(newPos, newPos);
    }

    // Menambah Baris Baru pada Glosarium
    function addGlosariumRow() {
        const list = document.getElementById('glosarium-list');
        const count = list.querySelectorAll('.repeater-row').length;

        const row = document.createElement('div');
        row.className = 'repeater-row flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/80 border border-slate-200/80 fade-in-item';
        row.innerHTML = `
            <span class="row-number w-7 h-7 rounded-xl bg-white border border-slate-200 text-slate-500 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs mt-1">
                ${count + 1}
            </span>
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-4">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Istilah / Kata Kunci</label>
                    <input type="text"
                           name="glosarium[${count}][istilah]"
                           placeholder="Istilah baru..."
                           class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all font-semibold">
                </div>
                <div class="sm:col-span-8">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Definisi / Arti Kata</label>
                    <textarea name="glosarium[${count}][definisi]"
                              rows="2"
                              placeholder="Definisi ringkas istilah..."
                              class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all resize-none leading-relaxed"></textarea>
                </div>
            </div>
            <button type="button"
                    onclick="removeRepeaterRow(this)"
                    class="w-8 h-8 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors shrink-0 cursor-pointer mt-1"
                    title="Hapus Istilah">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;

        list.appendChild(row);
        updateRowNumbers();
    }

    // Menghapus baris dari repeater
    function removeRepeaterRow(btn) {
        const row = btn.closest('.repeater-row');
        const list = row.parentElement;
        if (list.querySelectorAll('.repeater-row').length <= 1) {
            alert('Minimal harus ada 1 baris glosarium.');
            return;
        }
        row.remove();
        updateRowNumbers();
    }

    // Memperbarui nomor baris repeater
    function updateRowNumbers() {
        const rows = document.querySelectorAll('#glosarium-list .repeater-row');
        rows.forEach((r, idx) => {
            const numSpan = r.querySelector('.row-number');
            if (numSpan) numSpan.textContent = idx + 1;
            
            const istilahInput = r.querySelector('input[name*="[istilah]"]');
            if (istilahInput) istilahInput.name = `glosarium[${idx}][istilah]`;

            const definisiTextarea = r.querySelector('textarea[name*="[definisi]"]');
            if (definisiTextarea) definisiTextarea.name = `glosarium[${idx}][definisi]`;
        });
    }
</script>
@endpush

@endsection
