@extends('layouts.teacher.dashboardteacher')

@section('title', 'Edit Informasi Umum — ' . $module->title)
@section('page-title', 'Editor Informasi Umum')

@push('head')
<style>
    /* ── Prose editor area ──────────────────────────────── */
    .prose-editor {
        min-height: 140px;
        line-height: 1.7;
        resize: vertical;
    }
    /* ── Cover preview ──────────────────────────────────── */
    #cover-preview-wrap { transition: opacity .25s ease; }
    /* ── Glosarium / Daftar Isi rows ─────────────────────── */
    .repeater-row { animation: fadeSlideIn .2s ease; }
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    /* ── Sticky sidebar steps ───────────────────────────── */
    .step-nav a.active span.step-dot {
        background: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.25);
    }
    .step-nav a.active span.step-label {
        color: #1e3a8a;
        font-weight: 700;
    }
    /* ── Section divider ────────────────────────────────── */
    .section-card {
        scroll-margin-top: 80px;
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
    <span class="font-semibold text-slate-800">Informasi Umum</span>
</nav>

{{-- Flash --}}
@if(session('success'))
<div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
    <p class="font-bold mb-1.5 flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
        Terdapat {{ $errors->count() }} kesalahan validasi:
    </p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ══ Layout: Sticky Nav + Form ══ --}}
<div class="flex gap-7 items-start">

    {{-- ── LEFT: Sticky Step Navigator (hidden on mobile) ─────────────────── --}}
    <aside class="hidden xl:block w-52 shrink-0 sticky top-0 step-nav">
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-4">
            <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500 mb-4 px-1">8 Komponen</p>
            <nav class="space-y-1">
                @foreach([
                    ['cover',       'sec-cover',       '📷', 'Halaman Cover'],
                    ['kata',        'sec-kata',        '✏️', 'Kata Pengantar'],
                    ['daftar',      'sec-daftar',      '📋', 'Daftar Isi'],
                    ['peta',        'sec-peta',        '🗺️', 'Peta Konsep'],
                    ['glosarium',   'sec-glosarium',   '📖', 'Glosarium'],
                    ['petunjuk',    'sec-petunjuk',    '💡', 'Petunjuk Penggunaan'],
                    ['tujuan',      'sec-tujuan',      '🎯', 'Tujuan Pembelajaran'],
                    ['pustaka',     'sec-pustaka',     '📚', 'Daftar Pustaka'],
                ] as [$id, $anchor, $emoji, $label])
                    <a href="#{{ $anchor }}" onclick="setActive(this)"
                       class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 hover:bg-blue-50 transition-colors">
                        <span class="step-dot w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[11px] shrink-0">{{ $emoji }}</span>
                        <span class="step-label text-xs font-medium text-slate-600">{{ $label }}</span>
                    </a>
                @endforeach
            </nav>
            {{-- Save shortcut --}}
            <div class="mt-5 pt-4 border-t border-slate-200">
                <button form="informasi-umum-form" type="submit"
                        class="w-full py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow shadow-blue-600/20">
                    Simpan Semua
                </button>
            </div>
        </div>
    </aside>

    {{-- ── RIGHT: The Form ─────────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0">
        <form id="informasi-umum-form"
              action="{{ route('teacher.modules.informasi-umum.update', $module) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 1. COVER --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-cover" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-black shrink-0">1</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Halaman Cover (Sampul Modul)</h2>
                        <p class="text-xs text-slate-500">Upload gambar menarik sebagai identitas visual modul ini. Format: JPG/PNG/WebP, maks. 3 MB.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 px-2.5 py-1 rounded-full shrink-0">Mandatori</span>
                </div>
                <div class="p-6">
                    {{-- Preview Area --}}
                    <div id="cover-preview-wrap" class="mb-5">
                        @if(!empty($data['cover_image_path']))
                            <div class="relative inline-block">
                                <img id="cover-img-preview"
                                     src="{{ Storage::url($data['cover_image_path']) }}"
                                     alt="Cover saat ini"
                                     class="w-full max-w-sm rounded-xl border border-slate-200 shadow-md object-cover aspect-[3/2]">
                                <div class="absolute top-2 right-2 flex gap-1.5">
                                    <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">Cover Aktif</span>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-600 hover:text-red-600 transition-colors">
                                    <input type="checkbox" name="remove_cover" value="1" class="w-4 h-4 rounded accent-red-500">
                                    Hapus cover ini dan simpan tanpa gambar
                                </label>
                            </div>
                        @else
                            <div id="cover-drop-zone"
                                 class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-blue-400 bg-slate-50 hover:bg-blue-50/30 rounded-2xl p-10 cursor-pointer transition-all"
                                 onclick="document.getElementById('cover_image_input').click()">
                                <div id="cover-drop-content" class="text-center pointer-events-none">
                                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Klik atau seret gambar ke sini</p>
                                    <p class="text-xs text-slate-500 mt-1">JPG, PNG, WebP — maks 3 MB — Rekomendasi: 1200×800px (rasio 3:2)</p>
                                </div>
                                {{-- Preview jika ada gambar baru --}}
                                <img id="cover-img-preview" class="hidden w-full max-w-sm rounded-xl object-cover aspect-[3/2] mt-4" alt="Preview baru">
                            </div>
                        @endif
                    </div>

                    <input type="file"
                           id="cover_image_input"
                           name="cover_image"
                           accept="image/jpeg,image/png,image/webp"
                           class="hidden">

                    @if(empty($data['cover_image_path']))
                        <p class="text-xs text-slate-400">Atau pilih file secara manual:</p>
                        <label for="cover_image_input"
                               class="mt-2 inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl cursor-pointer transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            Pilih Gambar Cover
                        </label>
                        <p id="cover-file-name" class="mt-2 text-xs text-slate-500 italic"></p>
                    @else
                        <div class="mt-3">
                            <p class="text-xs font-semibold text-slate-700 mb-1.5">Ganti dengan gambar baru:</p>
                            <label for="cover_image_input"
                                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl cursor-pointer transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                Ganti Cover
                            </label>
                            <p id="cover-file-name" class="mt-2 text-xs text-slate-500 italic"></p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 2. KATA PENGANTAR --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-kata" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-black shrink-0">2</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Kata Pengantar</h2>
                        <p class="text-xs text-slate-500">Sambutan singkat dari guru kepada siswa — konteks, motivasi, cara penggunaan modul ini.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 px-2.5 py-1 rounded-full shrink-0">Wajib</span>
                </div>
                <div class="p-6">
                    <textarea
                        id="kata_pengantar"
                        name="kata_pengantar"
                        rows="7"
                        placeholder="Contoh: Puji syukur kehadirat Tuhan Yang Maha Esa, modul Sistem Basis Data ini disusun untuk membantu siswa kelas XI memahami konsep relasi dan query SQL secara mandiri dan terstruktur..."
                        class="prose-editor w-full rounded-xl border @error('kata_pengantar') border-red-400 bg-red-50 @else border-slate-300 bg-slate-50 @enderror px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                    >{{ old('kata_pengantar', $data['kata_pengantar'] ?? '') }}</textarea>
                    <div class="mt-1.5 flex items-center justify-between">
                        @error('kata_pengantar')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <span></span>
                        @enderror
                        <span id="kata-count" class="text-xs text-slate-400 ml-auto"></span>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 3. DAFTAR ISI --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-daftar" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-black shrink-0">3</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Daftar Isi (Hiperlink Navigasi)</h2>
                        <p class="text-xs text-slate-500">Daftar bab/sub-bab yang akan tampil sebagai tautan navigasi bagi siswa. Tambahkan sesuai struktur materi Anda.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-full shrink-0">Opsional</span>
                </div>
                <div class="p-6">
                    <div id="daftar-isi-list" class="space-y-3 mb-4">
                        @php $daftarIsiItems = old('daftar_isi', $data['daftar_isi'] ?? []); @endphp
                        @if(empty($daftarIsiItems))
                            {{-- Default satu baris kosong --}}
                            <div class="repeater-row flex items-center gap-3">
                                <div class="w-7 h-7 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center text-xs font-bold shrink-0">1</div>
                                <input type="text" name="daftar_isi[0][judul]" placeholder="Contoh: Bab 1 — Pengenalan Database"
                                       class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <button type="button" onclick="removeDaftarIsiRow(this)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @else
                            @foreach($daftarIsiItems as $idx => $item)
                                <div class="repeater-row flex items-center gap-3">
                                    <div class="w-7 h-7 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center text-xs font-bold shrink-0">{{ $idx + 1 }}</div>
                                    <input type="text" name="daftar_isi[{{ $idx }}][judul]"
                                           value="{{ $item['judul'] ?? '' }}"
                                           placeholder="Judul bab atau sub-bab..."
                                           class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <button type="button" onclick="removeDaftarIsiRow(this)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addDaftarIsiRow()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Item Daftar Isi
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 4. PETA KONSEP --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-peta" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center text-xs font-black shrink-0">4</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Peta Konsep</h2>
                        <p class="text-xs text-slate-500">Gambaran hubungan antar konsep yang akan dipelajari. Bisa berupa teks deskripsi, hierarki, atau tautan ke gambar peta konsep.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-full shrink-0">Opsional</span>
                </div>
                <div class="p-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-4 flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        <p class="text-xs text-amber-800">
                            <strong>Tips:</strong> Deskripsikan hubungan antar konsep dalam teks (contoh: "Database → Tabel → Kolom/Baris → Data"). Atau tulis URL gambar peta konsep jika sudah Anda buat di luar sistem.
                        </p>
                    </div>
                    <textarea
                        name="peta_konsep_text"
                        rows="5"
                        placeholder="Contoh: Sistem Basis Data terdiri dari: (1) Database → berisi Tabel-tabel → setiap Tabel memiliki Kolom dan Baris. (2) DBMS → mengatur akses, query, dan integritas data melalui SQL..."
                        class="prose-editor w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                    >{{ old('peta_konsep_text', $data['peta_konsep_text'] ?? '') }}</textarea>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 5. GLOSARIUM --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-glosarium" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-xs font-black shrink-0">5</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Glosarium Istilah Teknis</h2>
                        <p class="text-xs text-slate-500">Daftar istilah-istilah khusus beserta definisinya untuk mempermudah pemahaman siswa.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-full shrink-0">Opsional</span>
                </div>
                <div class="p-6">
                    {{-- Header tabel --}}
                    <div class="hidden sm:grid grid-cols-[1fr_2fr_auto] gap-3 mb-2 px-1">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Istilah / Kata Kunci</span>
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Definisi / Penjelasan</span>
                        <span class="w-8"></span>
                    </div>

                    <div id="glosarium-list" class="space-y-3 mb-4">
                        @php $glosItems = old('glosarium', $data['glosarium'] ?? []); @endphp
                        @if(empty($glosItems))
                            <div class="repeater-row grid grid-cols-1 sm:grid-cols-[1fr_2fr_auto] gap-3 items-start">
                                <input type="text" name="glosarium[0][istilah]" placeholder="Query"
                                       class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <textarea name="glosarium[0][definisi]" rows="2" placeholder="Perintah/pernyataan yang dikirim ke DBMS untuk mengambil atau memanipulasi data dalam database..."
                                          class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"></textarea>
                                <button type="button" onclick="removeGlosRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @else
                            @foreach($glosItems as $gi => $gItem)
                                <div class="repeater-row grid grid-cols-1 sm:grid-cols-[1fr_2fr_auto] gap-3 items-start">
                                    <input type="text" name="glosarium[{{ $gi }}][istilah]"
                                           value="{{ $gItem['istilah'] ?? '' }}"
                                           placeholder="Istilah teknis..."
                                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <textarea name="glosarium[{{ $gi }}][definisi]" rows="2"
                                              placeholder="Definisi singkat dan jelas..."
                                              class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all resize-none">{{ $gItem['definisi'] ?? '' }}</textarea>
                                    <button type="button" onclick="removeGlosRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" onclick="addGlosRow()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Entri Glosarium
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 6. PETUNJUK PENGGUNAAN --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-petunjuk" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center text-xs font-black shrink-0">6</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Petunjuk Penggunaan Modul</h2>
                        <p class="text-xs text-slate-500">Panduan tata cara membaca, mengerjakan, dan menyelesaikan modul ini untuk siswa.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-full shrink-0">Opsional</span>
                </div>
                <div class="p-6">
                    <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach([
                            ['📖', 'Baca setiap halaman secara berurutan dari awal hingga akhir.'],
                            ['✅', 'Selesaikan semua tugas di setiap halaman sebelum melanjutkan.'],
                            ['💾', 'Simpan semua file hasil praktik sebelum mengunggah.'],
                        ] as [$icon, $tip])
                            <div class="flex items-start gap-2.5 bg-blue-50/50 border border-blue-100 rounded-xl p-3.5">
                                <span class="text-lg shrink-0">{{ $icon }}</span>
                                <p class="text-xs text-blue-800 leading-relaxed">{{ $tip }}</p>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-500 mb-2">Tambahkan petunjuk tambahan yang spesifik untuk modul ini (opsional):</p>
                    <textarea
                        name="petunjuk_penggunaan"
                        rows="5"
                        placeholder="Contoh: Modul ini terdiri dari 7 tahap. Pastikan Anda memiliki koneksi internet stabil untuk menonton video di Komponen Inti. Untuk pengerjaan Job Sheet, siapkan aplikasi phpMyAdmin atau MySQL Workbench di komputer Anda sebelum memulai..."
                        class="prose-editor w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                    >{{ old('petunjuk_penggunaan', $data['petunjuk_penggunaan'] ?? '') }}</textarea>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 7. TUJUAN PEMBELAJARAN --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-tujuan" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-black shrink-0">7</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Tujuan Pembelajaran (Capaian)</h2>
                        <p class="text-xs text-slate-500">Kompetensi yang diharapkan siswa kuasai setelah menyelesaikan modul ini. Gunakan kata kerja operasional (Bloom's Taxonomy).</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 px-2.5 py-1 rounded-full shrink-0">Wajib</span>
                </div>
                <div class="p-6">
                    {{-- Bloom Taxonomy Quick Reference --}}
                    <div class="mb-4 bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                        <p class="text-xs font-bold text-emerald-800 mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                            Kata Kerja Operasional Bloom (panduan, klik untuk menyisipkan):
                        </p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach([
                                'Mengidentifikasi','Menjelaskan','Menerapkan','Menganalisis','Mengevaluasi',
                                'Merancang','Membuat','Mendemonstrasikan','Membandingkan','Menyimpulkan'
                            ] as $kw)
                                <button type="button"
                                        onclick="insertKataKerja('{{ $kw }}')"
                                        class="text-[10px] font-semibold bg-white text-emerald-700 border border-emerald-300 hover:bg-emerald-600 hover:text-white px-2.5 py-1 rounded-full transition-all">
                                    {{ $kw }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <textarea
                        id="tujuan_pembelajaran"
                        name="tujuan_pembelajaran"
                        rows="6"
                        placeholder="Contoh: Setelah menyelesaikan modul ini, peserta didik diharapkan mampu:&#10;1. Menjelaskan konsep database relasional dan peran DBMS.&#10;2. Mengidentifikasi jenis-jenis SQL (DDL, DML, DCL).&#10;3. Menerapkan perintah SELECT, INSERT, UPDATE, dan DELETE pada phpMyAdmin.&#10;4. Merancang struktur tabel sederhana dengan normalisasi dasar."
                        class="prose-editor w-full rounded-xl border @error('tujuan_pembelajaran') border-red-400 bg-red-50 @else border-slate-300 bg-slate-50 @enderror px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                    >{{ old('tujuan_pembelajaran', $data['tujuan_pembelajaran'] ?? '') }}</textarea>
                    @error('tujuan_pembelajaran')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- 8. DAFTAR PUSTAKA & REFERENSI --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div id="sec-pustaka" class="section-card bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-black shrink-0">8</div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Daftar Pustaka & Referensi</h2>
                        <p class="text-xs text-slate-500">Daftar sumber pustaka, buku ajar, artikel, atau tautan acuan penyusunan materi modul ini.</p>
                    </div>
                    <span class="ml-auto text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-full shrink-0">Opsional</span>
                </div>
                <div class="p-6">
                    {{-- Header kolom --}}
                    <div class="hidden sm:grid grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 mb-2 px-1">
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Judul Buku / Sumber</span>
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Penulis / Penerbit</span>
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Tahun</span>
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Tautan Web (Opsional)</span>
                        <span class="w-8"></span>
                    </div>

                    <div id="pustaka-list" class="space-y-3 mb-4">
                        @php $pustakaItems = old('daftar_pustaka', $data['daftar_pustaka'] ?? []); @endphp
                        @if(empty($pustakaItems))
                            <div class="repeater-row grid grid-cols-1 sm:grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 items-start">
                                <input type="text" name="daftar_pustaka[0][judul]" placeholder="Basis Data SMK/MAK Kelas XI"
                                       class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <input type="text" name="daftar_pustaka[0][penulis]" placeholder="Kemendikbudristek / Erlangga"
                                       class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <input type="text" name="daftar_pustaka[0][tahun]" placeholder="2023"
                                       class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <input type="url" name="daftar_pustaka[0][tautan]" placeholder="https://..."
                                       class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <button type="button" onclick="removePustakaRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @else
                            @foreach($pustakaItems as $pi => $pItem)
                                <div class="repeater-row grid grid-cols-1 sm:grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 items-start">
                                    <input type="text" name="daftar_pustaka[{{ $pi }}][judul]"
                                           value="{{ is_array($pItem) ? ($pItem['judul'] ?? '') : $pItem }}"
                                           placeholder="Judul Buku / Sumber..."
                                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <input type="text" name="daftar_pustaka[{{ $pi }}][penulis]"
                                           value="{{ is_array($pItem) ? ($pItem['penulis'] ?? '') : '' }}"
                                           placeholder="Penulis / Penerbit..."
                                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <input type="text" name="daftar_pustaka[{{ $pi }}][tahun]"
                                           value="{{ is_array($pItem) ? ($pItem['tahun'] ?? '') : '' }}"
                                           placeholder="Tahun..."
                                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <input type="url" name="daftar_pustaka[{{ $pi }}][tautan]"
                                           value="{{ is_array($pItem) ? ($pItem['tautan'] ?? '') : '' }}"
                                           placeholder="https://..."
                                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <button type="button" onclick="removePustakaRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" onclick="addPustakaRow()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Referensi Pustaka
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- SUBMIT BAR --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div class="sticky bottom-0 z-20 -mx-1">
                <div class="bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-xl shadow-slate-900/10 px-6 py-4 flex flex-col sm:flex-row items-center gap-3">
                    <div class="flex items-center gap-2 text-sm text-slate-600 mr-auto">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                        <span class="text-xs">Semua data tersimpan ke modul <strong class="text-slate-900">{{ $module->title }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2.5 w-full sm:w-auto">
                        <a href="{{ route('teacher.modules.show', $module) }}"
                           class="flex-1 sm:flex-none text-center px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                            Batal
                        </a>
                        <button type="submit"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-7 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 rounded-xl shadow shadow-blue-600/20 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Simpan Informasi Umum
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>{{-- end form column --}}
</div>{{-- end flex layout --}}

@endsection

@push('scripts')
<script>
/* ─── Cover Image Preview ───────────────────────────────────────── */
const coverInput = document.getElementById('cover_image_input');
const coverPreview = document.getElementById('cover-img-preview');
const coverFileName = document.getElementById('cover-file-name');
const coverDropZone = document.getElementById('cover-drop-zone');

if (coverInput) {
    coverInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (coverFileName) coverFileName.textContent = `📁 ${file.name} (${(file.size/1024).toFixed(0)} KB)`;
        if (coverPreview) {
            const reader = new FileReader();
            reader.onload = e => {
                coverPreview.src = e.target.result;
                coverPreview.classList.remove('hidden');
                const dropContent = document.getElementById('cover-drop-content');
                if (dropContent) dropContent.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
}

// Drag & drop support
if (coverDropZone) {
    coverDropZone.addEventListener('dragover', e => {
        e.preventDefault();
        coverDropZone.classList.add('border-blue-500', 'bg-blue-50/60');
    });
    coverDropZone.addEventListener('dragleave', () => {
        coverDropZone.classList.remove('border-blue-500', 'bg-blue-50/60');
    });
    coverDropZone.addEventListener('drop', e => {
        e.preventDefault();
        coverDropZone.classList.remove('border-blue-500', 'bg-blue-50/60');
        if (e.dataTransfer.files.length) {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            coverInput.files = dt.files;
            coverInput.dispatchEvent(new Event('change'));
        }
    });
}

/* ─── Kata Pengantar character count ───────────────────────────── */
const kataTA = document.getElementById('kata_pengantar');
const kataCount = document.getElementById('kata-count');
if (kataTA && kataCount) {
    const updateCount = () => {
        const words = kataTA.value.trim().split(/\s+/).filter(Boolean).length;
        kataCount.textContent = `${words} kata`;
    };
    kataTA.addEventListener('input', updateCount);
    updateCount();
}

/* ─── Daftar Isi Repeater ──────────────────────────────────────── */
let diIndex = document.querySelectorAll('#daftar-isi-list .repeater-row').length;

function addDaftarIsiRow() {
    const list = document.getElementById('daftar-isi-list');
    const count = list.querySelectorAll('.repeater-row').length;
    const row = document.createElement('div');
    row.className = 'repeater-row flex items-center gap-3';
    row.innerHTML = `
        <div class="w-7 h-7 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center text-xs font-bold shrink-0">${count + 1}</div>
        <input type="text" name="daftar_isi[${diIndex}][judul]" placeholder="Judul bab atau sub-bab..."
               class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <button type="button" onclick="removeDaftarIsiRow(this)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors rounded-lg hover:bg-red-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    list.appendChild(row);
    diIndex++;
    row.querySelector('input').focus();
}

function removeDaftarIsiRow(btn) {
    btn.closest('.repeater-row').remove();
    renumberDaftarIsi();
}

function renumberDaftarIsi() {
    document.querySelectorAll('#daftar-isi-list .repeater-row').forEach((row, i) => {
        const badge = row.querySelector('div.w-7');
        if (badge) badge.textContent = i + 1;
    });
}

/* ─── Glosarium Repeater ────────────────────────────────────────── */
let glosIndex = document.querySelectorAll('#glosarium-list .repeater-row').length;

function addGlosRow() {
    const list = document.getElementById('glosarium-list');
    const row = document.createElement('div');
    row.className = 'repeater-row grid grid-cols-1 sm:grid-cols-[1fr_2fr_auto] gap-3 items-start';
    row.innerHTML = `
        <input type="text" name="glosarium[${glosIndex}][istilah]" placeholder="Istilah teknis..."
               class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <textarea name="glosarium[${glosIndex}][definisi]" rows="2" placeholder="Definisi singkat dan jelas..."
                  class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"></textarea>
        <button type="button" onclick="removeGlosRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    list.appendChild(row);
    glosIndex++;
    row.querySelector('input').focus();
}

function removeGlosRow(btn) {
    btn.closest('.repeater-row').remove();
}

/* ─── Daftar Pustaka Repeater ──────────────────────────────────── */
let pustakaIndex = document.querySelectorAll('#pustaka-list .repeater-row').length;

function addPustakaRow() {
    const list = document.getElementById('pustaka-list');
    const row = document.createElement('div');
    row.className = 'repeater-row grid grid-cols-1 sm:grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 items-start';
    row.innerHTML = `
        <input type="text" name="daftar_pustaka[${pustakaIndex}][judul]" placeholder="Judul Buku / Sumber..."
               class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <input type="text" name="daftar_pustaka[${pustakaIndex}][penulis]" placeholder="Penulis / Penerbit..."
               class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <input type="text" name="daftar_pustaka[${pustakaIndex}][tahun]" placeholder="Tahun..."
               class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <input type="url" name="daftar_pustaka[${pustakaIndex}][tautan]" placeholder="https://..."
               class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
        <button type="button" onclick="removePustakaRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    list.appendChild(row);
    pustakaIndex++;
    row.querySelector('input').focus();
}

function removePustakaRow(btn) {
    btn.closest('.repeater-row').remove();
}

/* ─── Bloom Taxonomy: Insert kata kerja ────────────────────────── */
function insertKataKerja(word) {
    const ta = document.getElementById('tujuan_pembelajaran');
    if (!ta) return;
    const pos = ta.selectionStart;
    ta.value = ta.value.slice(0, pos) + word + ' ' + ta.value.slice(pos);
    ta.focus();
    ta.selectionStart = ta.selectionEnd = pos + word.length + 1;
}

/* ─── Sticky Step Navigator Active State ─────────────────────────── */
function setActive(el) {
    document.querySelectorAll('.step-nav a').forEach(a => {
        a.querySelector('.step-dot')?.classList.remove('!bg-blue-600');
        a.querySelector('.step-label')?.classList.remove('!text-blue-800', '!font-bold');
    });
    el.querySelector('.step-dot')?.classList.add('!bg-blue-600');
    el.querySelector('.step-label')?.classList.add('!text-blue-800', '!font-bold');
}

/* IntersectionObserver: auto-highlight step as you scroll */
const sections = document.querySelectorAll('.section-card');
const navLinks  = document.querySelectorAll('.step-nav a');
const mainContainer = document.querySelector('main');
const observer  = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const id = entry.target.id;
            navLinks.forEach(a => {
                const active = a.getAttribute('href') === `#${id}`;
                a.querySelector('.step-dot')?.classList.toggle('!bg-blue-600', active);
                a.querySelector('.step-label')?.classList.toggle('!font-bold', active);
                a.querySelector('.step-label')?.classList.toggle('!text-blue-900', active);
            });
        }
    });
}, { root: mainContainer, threshold: 0.35 });
sections.forEach(s => observer.observe(s));
</script>
@endpush
