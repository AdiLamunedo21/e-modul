@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Lembar Praktikum (Job Sheet) — ' . $module->title)
@section('page-title', 'Editor Lembar Praktikum / Job Sheet (Bagian Inti)')

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
    <span class="font-semibold text-slate-800">Bagian Inti: Job Sheet</span>
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
<div class="bg-gradient-to-r from-amber-800 via-yellow-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-amber-950/20 mb-8 relative overflow-hidden border border-amber-700/40">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-yellow-500/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-amber-200">
                    <span>📋</span>
                    <span>Bagian Inti — Komponen 5</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-400/20 text-amber-300 border border-amber-400/40 uppercase tracking-wider">
                    Opsional (Toggle)
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                Lembar Praktikum (Job Sheet PDF)
            </h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Unggah berkas lembar kerja teknis mandiri (Job Sheet) berformat PDF. Siswa mempelajari instruksi, mematuhi standar K3 bengkel, melaksanakan praktikum, dan mengunggah laporan hasil pengerjaan.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                ← Kembali ke Detail
            </a>
            <a href="{{ route('teacher.modules.job-sheet.preview', $module) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2 border border-emerald-400/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pratinjau Siswa
            </a>
        </div>
    </div>
</div>

<form id="job-sheet-form" action="{{ route('teacher.modules.job-sheet.update', $module) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    {{-- ══ Main Layout Grid (Standard 4 Columns) ══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">

        {{-- ── LEFT 3 COLUMNS: Main Form ───────────────────────────────────────── --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- 1. Sakelar Utama & Identitas Job Sheet --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Aktivasi Komponen Lembar Praktikum (Job Sheet)</h2>
                            <p class="text-xs text-slate-500">Jika diaktifkan, halaman unduh lembar kerja PDF dan form pengumpulan laporan praktik wajib akan disajikan bagi siswa.</p>
                        </div>
                    </div>

                    {{-- Switch Toggle --}}
                    <label class="inline-flex items-center cursor-pointer select-none shrink-0 group">
                        <input type="checkbox" name="has_job_sheet" id="has_job_sheet_toggle" value="1"
                               class="sr-only"
                               {{ old('has_job_sheet', $module->has_job_sheet) ? 'checked' : '' }}
                               onchange="toggleJobSheetStatus(this.checked)">
                        <div id="job-sheet-toggle-track"
                             class="relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out {{ old('has_job_sheet', $module->has_job_sheet) ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                            <div id="job-sheet-toggle-thumb"
                                 class="absolute top-[2px] left-[2px] h-5 w-5 rounded-full bg-white shadow-md border border-slate-300/90 transition-transform duration-300 ease-in-out"
                                 style="transform: translateX({{ old('has_job_sheet', $module->has_job_sheet) ? '20px' : '0px' }});">
                            </div>
                        </div>
                        <span id="toggle-status-badge" class="ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 {{ old('has_job_sheet', $module->has_job_sheet) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-300' }}">
                            {{ old('has_job_sheet', $module->has_job_sheet) ? 'Aktif (ON)' : 'Nonaktif (OFF)' }}
                        </span>
                    </label>
                </div>

                {{-- Fields Identitas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-6">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Judul Lembar Praktikum (Job Sheet) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="job_sheet_title"
                               id="job_sheet_title"
                               value="{{ old('job_sheet_title', $data['job_sheet_title'] ?? 'Lembar Praktikum: ' . $module->title) }}"
                               placeholder="Contoh: Job Sheet 01: Perakitan & Pengujian Kabel Jaringan UTP"
                               class="w-full rounded-xl border @error('job_sheet_title') border-rose-300 bg-rose-50/50 @else border-slate-300 bg-slate-50 @enderror px-4 py-2.5 text-sm text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all">
                        @error('job_sheet_title')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Estimasi Alokasi Waktu
                        </label>
                        <div class="flex rounded-xl border border-slate-300 bg-slate-50 overflow-hidden focus-within:border-amber-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-amber-500/20 transition-all shadow-sm">
                            <input type="number"
                                   name="estimated_duration"
                                   id="estimated_duration"
                                   min="5"
                                   max="300"
                                   value="{{ old('estimated_duration', $data['estimated_duration'] ?? 60) }}"
                                   class="w-full bg-transparent px-4 py-2.5 text-sm text-slate-900 focus:outline-none">
                            <span class="inline-flex items-center px-3.5 bg-slate-100 text-xs font-bold text-slate-500 border-l border-slate-200 select-none shrink-0">
                                Menit
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Estimasi pengerjaan di lab/bengkel.</p>
                    </div>
                </div>
            </div>

            {{-- 2. Unggah & Kelola Berkas PDF Job Sheet Guru --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg shrink-0">
                        📄
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Berkas Dokumen Job Sheet (Format PDF)</h2>
                        <p class="text-xs text-slate-500">Unggah berkas instruksi teknis resmi yang akan diunduh dan dipedomani oleh siswa.</p>
                    </div>
                </div>

                {{-- Status Berkas Tersimpan --}}
                @if(!empty($data['pdf_file_path']))
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-amber-50/70 border border-amber-200 text-slate-800">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                PDF
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900 truncate max-w-sm sm:max-w-md">
                                    {{ $data['pdf_file_name'] ?? 'Dokumen-Job-Sheet.pdf' }}
                                </p>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    Ukuran: {{ !empty($data['pdf_file_size']) ? round($data['pdf_file_size'] / 1024, 1) . ' KB' : 'Tersimpan' }} &bull; Format resmi Job Sheet
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('teacher.modules.job-sheet.download', $module) }}"
                               class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-amber-300 text-amber-800 text-xs font-bold transition-all shadow-2xs flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Unduh File
                            </a>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-600 cursor-pointer px-2 py-1 hover:bg-rose-50 rounded-lg transition-colors">
                                <input type="checkbox" name="remove_pdf_file" value="1" class="rounded text-rose-600 focus:ring-rose-500 border-rose-300">
                                <span>Hapus Berkas</span>
                            </label>
                        </div>
                    </div>
                @endif

                {{-- Area Unggah Berkas Baru --}}
                <div class="space-y-2">
                    <label for="pdf_file" class="block text-xs font-bold text-slate-700">
                        {{ !empty($data['pdf_file_path']) ? 'Ganti Berkas PDF Job Sheet (Opsional)' : 'Unggah Berkas PDF Job Sheet' }}
                    </label>
                    <div class="relative flex items-center">
                        <input type="file"
                               name="pdf_file"
                               id="pdf_file"
                               accept=".pdf"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 border border-slate-300 rounded-xl bg-slate-50 p-1.5 focus:outline-none focus:border-amber-500 transition-all cursor-pointer">
                    </div>
                    <p class="text-[11px] text-slate-400">Format: Dokumen PDF resmi (Maksimal 15 MB).</p>
                    @error('pdf_file')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 3. Instruksi Kerja & Standar Keselamatan Kerja (K3) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0">
                        🛡️
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Petunjuk Pengerjaan & Standar Keselamatan Kerja (K3)</h2>
                        <p class="text-xs text-slate-500">Arahan alur praktikum teknis dan SOP keselamatan kerja di bengkel vokasi SMKN 3 Yogyakarta.</p>
                    </div>
                </div>

                {{-- Instruksi Kerja --}}
                <div class="space-y-1.5">
                    <label for="instructions" class="block text-xs font-bold text-slate-700">
                        Petunjuk Pengerjaan Praktikum bagi Siswa
                    </label>
                    <textarea name="instructions"
                              id="instructions"
                              rows="3"
                              placeholder="Tuliskan arahan pelaksanaan tahapan praktikum..."
                              class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all leading-relaxed">{{ old('instructions', $data['instructions'] ?? '') }}</textarea>
                </div>

                {{-- Keselamatan & Kesehatan Kerja (K3) --}}
                <div class="space-y-1.5">
                    <label for="safety_guidelines" class="block text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span>Standar Keselamatan & Kesehatan Kerja (K3)</span>
                        <span class="text-[11px] font-normal text-amber-700">Wajib Dipatuhi Siswa</span>
                    </label>
                    <textarea name="safety_guidelines"
                              id="safety_guidelines"
                              rows="3"
                              placeholder="Tuliskan SOP keselamatan, penggunaan APD, dan pencegahan risiko..."
                              class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all leading-relaxed font-sans">{{ old('safety_guidelines', $data['safety_guidelines'] ?? '') }}</textarea>
                </div>
            </div>

            {{-- 4. Daftar Alat & Bahan Praktik (Dynamic Builder) --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg shrink-0">
                            🛠️
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Daftar Alat & Bahan Praktik</h3>
                            <p class="text-xs text-slate-500">Perlengkapan teknis yang harus disiapkan siswa atau disediakan di laboratorium.</p>
                        </div>
                    </div>

                    <button type="button"
                            id="btn-add-tool"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200/60 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Alat/Bahan
                    </button>
                </div>

                <div id="tools-items-list" class="space-y-3 pt-1">
                    @php
                        $tools = old('tools_and_materials', $data['tools_and_materials'] ?? []);
                        if (empty($tools)) {
                            $tools = [
                                '1 unit Komputer / PC Client terinstal aplikasi praktikum',
                                'Koneksi jaringan intranet / internet laboratorium',
                                'Buku laporan dan alat tulis praktikum',
                            ];
                        }
                    @endphp

                    @foreach($tools as $index => $t)
                        <div class="tool-row flex items-center gap-2 group fade-in-item">
                            <span class="row-number w-7 h-7 rounded-xl bg-amber-100 text-amber-800 text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <input type="text"
                                   name="tools_and_materials[]"
                                   value="{{ $t }}"
                                   placeholder="Nama alat, bahan, atau spesifikasi perangkat..."
                                   class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all">
                            <button type="button"
                                    onclick="removeToolRow(this)"
                                    title="Hapus baris alat/bahan"
                                    class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 5. Rubrik Penilaian di Grading Center & Alur Navigasi (Sesuai PRD) --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-amber-950 rounded-2xl p-6 text-white shadow-md border border-slate-800 space-y-4">
                <div class="flex items-center gap-2.5 text-amber-400 text-xs font-bold uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Pedoman Penilaian & Alur Navigasi Siswa (PRD Section 2.4, 2.5, 4.2)
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1 text-xs">
                    <div class="bg-slate-800/80 rounded-xl p-4 border border-slate-700/60 space-y-1.5">
                        <p class="font-bold text-emerald-400 flex items-center gap-1.5">
                            <span>🔒</span> Pengumpulan Laporan PDF Mengikat
                        </p>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            Tombol <b>"Halaman Selanjutnya"</b> terkunci otomatis sampai siswa mengunggah berkas laporan hasil praktikum (PDF, Maksimal 5 MB).
                        </p>
                    </div>
                    <div class="bg-slate-800/80 rounded-xl p-4 border border-slate-700/60 space-y-1.5">
                        <p class="font-bold text-amber-400 flex items-center gap-1.5">
                            <span>⭐</span> Penilaian di Grading Center & Kebijakan Revisi
                        </p>
                        <p class="text-slate-300 leading-relaxed text-[11px]">
                            Hasil unggahan tersimpan di <code class="text-amber-300">job_sheet_submissions</code>. Siswa dapat mengunggah ulang selama status masih <code class="text-amber-300">pending</code>. Guru memberikan skor manual (0-100).
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
                        <span id="side-status-indicator" class="font-bold {{ $module->has_job_sheet ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $module->has_job_sheet ? '✓ Aktif' : '○ Non-Aktif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Berkas PDF:</span>
                        <span class="font-bold {{ !empty($data['pdf_file_path']) ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ !empty($data['pdf_file_path']) ? '✓ Terpasang' : '○ Belum Ada' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Alat & Bahan:</span>
                        <span id="side-tools-count" class="font-bold text-slate-900">
                            {{ count($tools) }} Item
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card Tombol Aksi & Template --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-amber-700 to-yellow-700 hover:from-amber-800 hover:to-yellow-800 shadow-lg shadow-amber-700/25 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Simpan Pengaturan Job Sheet</span>
                </button>

                <a href="{{ route('teacher.modules.job-sheet.preview', $module) }}"
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
    function toggleJobSheetStatus(isChecked) {
        const track = document.getElementById('job-sheet-toggle-track');
        const thumb = document.getElementById('job-sheet-toggle-thumb');
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

    // Dynamic Tools and Materials rows
    document.getElementById('btn-add-tool')?.addEventListener('click', function() {
        const list = document.getElementById('tools-items-list');
        const count = list.querySelectorAll('.tool-row').length + 1;

        const row = document.createElement('div');
        row.className = 'tool-row flex items-center gap-2 group fade-in-item';
        row.innerHTML = `
            <span class="row-number w-7 h-7 rounded-xl bg-amber-100 text-amber-800 text-xs font-bold flex items-center justify-center shrink-0">
                ${count}
            </span>
            <input type="text"
                   name="tools_and_materials[]"
                   placeholder="Nama alat, bahan, atau spesifikasi perangkat..."
                   class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-xs font-medium text-slate-900 placeholder-slate-400 focus:border-amber-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all">
            <button type="button"
                    onclick="removeToolRow(this)"
                    title="Hapus baris alat/bahan"
                    class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
            </button>
        `;

        list.appendChild(row);
        updateToolNumbers();
    });

    function removeToolRow(btn) {
        const list = document.getElementById('tools-items-list');
        if (list.querySelectorAll('.tool-row').length <= 1) {
            alert('Minimal harus ada 1 item alat/bahan.');
            return;
        }
        btn.closest('.tool-row').remove();
        updateToolNumbers();
    }

    function updateToolNumbers() {
        const rows = document.querySelectorAll('#tools-items-list .tool-row');
        rows.forEach((row, i) => {
            row.querySelector('.row-number').innerText = i + 1;
        });
        const sideCount = document.getElementById('side-tools-count');
        if (sideCount) sideCount.innerText = rows.length + ' Item';
    }
</script>
@endpush
