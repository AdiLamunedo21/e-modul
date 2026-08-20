@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor LKPD (Lembar Kerja Peserta Didik) — ' . $module->title)
@section('page-title', 'Editor LKPD / Studi Kasus (Komponen Inti)')

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
    <span class="font-semibold text-slate-800">Komponen Inti: Tugas LKPD</span>
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
<div class="bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20 mb-8 relative overflow-hidden border border-indigo-700/40">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-indigo-200">
                    <span>👥</span>
                    <span>Komponen Inti — Komponen 6</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-indigo-400/20 text-indigo-300 border border-indigo-400/40 uppercase tracking-wider">
                    Opsional (Toggle)
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                Lembar Kerja Peserta Didik (LKPD)
            </h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Rancang penugasan studi kasus pemecahan masalah kejuruan. Mode pengerjaan dapat diatur <strong class="text-indigo-200 font-semibold">Kelompok</strong> atau <strong class="text-indigo-200 font-semibold">Individu</strong>, dengan kewajiban setiap siswa mengunggah salinan berkas laporan PDF secara personal.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                ← Kembali ke Detail
            </a>
            <a href="{{ route('teacher.modules.lkpd.preview', $module) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2 border border-emerald-400/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pratinjau Siswa
            </a>
        </div>
    </div>
</div>

<form id="lkpd-form" action="{{ route('teacher.modules.lkpd.update', $module) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    {{-- ══ Main Layout Grid (Standard 4 Columns) ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- ── Left Side: Form Controls (8 Cols) ── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Card 1: Toggle Sakelar Komponen LKPD --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm overflow-hidden transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <span class="w-3 h-3 rounded-full {{ $module->has_lkpd ? 'bg-emerald-500 ring-4 ring-emerald-100' : 'bg-slate-300' }}"></span>
                                <h2 class="text-base font-bold text-slate-900">Aktivasi Komponen LKPD</h2>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed max-w-xl">
                                Jika diaktifkan, halaman LKPD akan muncul pada urutan ke-6 Komponen Inti di alur belajar siswa. Siswa wajib mengunggah file laporan PDF untuk dapat melanjutkan ke tahap berikutnya.
                            </p>
                        </div>

                        {{-- Toggle Switch --}}
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="has_lkpd" value="1" id="toggle-has-lkpd"
                                   class="sr-only peer" {{ old('has_lkpd', $module->has_lkpd) ? 'checked' : '' }}
                                   onchange="updateToggleState(this.checked)">
                            <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                        </label>
                    </div>

                    <div id="toggle-alert" class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold {{ old('has_lkpd', $module->has_lkpd) ? 'text-emerald-700' : 'text-slate-500' }}">
                        <span id="toggle-status-text">
                            {{ old('has_lkpd', $module->has_lkpd) ? '✓ Komponen LKPD Sedang AKTIF dalam Modul' : '○ Komponen LKPD Sedang NON-AKTIF' }}
                        </span>
                        <span class="text-[11px] text-slate-400 font-normal">Perubahan disimpan saat klik tombol simpan</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Konfigurasi Mode Pengerjaan & Identitas Tugas --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-black">1</span>
                        Identitas & Mode Pengerjaan Tugas
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Pilih apakah tugas LKPD dikerjakan secara berkelompok atau individu, serta atur durasi pengerjaan.
                    </p>
                </div>

                {{-- Judul LKPD --}}
                <div class="space-y-1.5">
                    <label for="lkpd_title" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Judul Lembar Kerja Peserta Didik (LKPD) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="lkpd_title" id="lkpd_title"
                           value="{{ old('lkpd_title', $data['lkpd_title']) }}"
                           placeholder="Contoh: Studi Kasus Perancangan Basis Data Normalisasi"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                    <p class="text-[11px] text-slate-500">Judul yang akan menjadi tajuk utama pada antarmuka belajar siswa.</p>
                </div>

                {{-- Mode Pengerjaan (Kelompok vs Individu) --}}
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Mode Kolaborasi / Pengerjaan <span class="text-rose-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Mode Kelompok --}}
                        <label class="relative flex items-start gap-3.5 p-4 rounded-2xl border-2 cursor-pointer transition-all {{ old('work_mode', $data['work_mode']) === 'group' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white' }}" id="label-mode-group">
                            <input type="radio" name="work_mode" value="group" class="sr-only"
                                   {{ old('work_mode', $data['work_mode']) === 'group' ? 'checked' : '' }}
                                   onchange="handleWorkModeChange('group')">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-lg shrink-0">
                                👥
                            </div>
                            <div class="space-y-1">
                                <span class="block text-sm font-bold text-slate-900">Kerjasama Kelompok</span>
                                <span class="block text-xs text-slate-500 leading-relaxed">Siswa berdiskusi dalam tim kecil, namun tetap mengunggah salinan laporan individu.</span>
                            </div>
                        </label>

                        {{-- Mode Individu --}}
                        <label class="relative flex items-start gap-3.5 p-4 rounded-2xl border-2 cursor-pointer transition-all {{ old('work_mode', $data['work_mode']) === 'individual' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white' }}" id="label-mode-individual">
                            <input type="radio" name="work_mode" value="individual" class="sr-only"
                                   {{ old('work_mode', $data['work_mode']) === 'individual' ? 'checked' : '' }}
                                   onchange="handleWorkModeChange('individual')">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-lg shrink-0">
                                👤
                            </div>
                            <div class="space-y-1">
                                <span class="block text-sm font-bold text-slate-900">Pengerjaan Individu</span>
                                <span class="block text-xs text-slate-500 leading-relaxed">Siswa menganalisis dan menyelesaikan penugasan studi kasus secara mandiri penuh.</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Row: Ukuran Kelompok & Estimasi Waktu --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    {{-- Input Ukuran Kelompok (Dinamis disesuaikan dengan mode) --}}
                    <div id="group-size-container" class="space-y-1.5 {{ old('work_mode', $data['work_mode']) === 'individual' ? 'opacity-50 pointer-events-none' : '' }}">
                        <label for="group_size" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Ukuran Kelompok
                        </label>
                        <div class="relative">
                            <input type="text" name="group_size" id="group_size"
                                   value="{{ old('group_size', $data['group_size']) }}"
                                   placeholder="Contoh: 3 - 4 Siswa"
                                   class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                        </div>
                        <p class="text-[11px] text-slate-500">Rekomendasi jumlah peserta per kelompok diskusi.</p>
                    </div>

                    {{-- Estimasi Waktu --}}
                    <div class="space-y-1.5">
                        <label for="estimated_duration" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Estimasi Alokasi Waktu
                        </label>
                        <div class="relative">
                            <input type="number" name="estimated_duration" id="estimated_duration" min="5" max="300"
                                   value="{{ old('estimated_duration', $data['estimated_duration']) }}"
                                   class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Menit</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Panduan perkiraan waktu penyelesaian tugas.</p>
                    </div>
                </div>
            </div>

            {{-- Card 3: Skenario Kasus & Petunjuk Kerja --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-black">2</span>
                        Skenario Studi Kasus & Petunjuk Kerja
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Tuliskan narasi masalah kejuruan yang harus dianalisis serta tahapan yang wajib dilalui siswa.
                    </p>
                </div>

                {{-- Deskripsi Skenario Studi Kasus --}}
                <div class="space-y-1.5">
                    <label for="case_study" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Deskripsi Masalah / Skenario Kasus Kejuruan
                    </label>
                    <textarea name="case_study" id="case_study" rows="4"
                              placeholder="Deskripsikan latar belakang masalah teknis yang memerlukan solusi perancangan..."
                              class="w-full rounded-2xl border border-slate-300 p-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all leading-relaxed">{{ old('case_study', $data['case_study']) }}</textarea>
                    <p class="text-[11px] text-slate-500">Skenario nyata yang memicu kemampuan berpikir kritis dan pemecahan masalah (Problem-Based Learning).</p>
                </div>

                {{-- Petunjuk Langkah Kerja --}}
                <div class="space-y-1.5">
                    <label for="instructions" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Petunjuk & Tahapan Pengerjaan Tugas
                    </label>
                    <textarea name="instructions" id="instructions" rows="4"
                              placeholder="Tuliskan urutan langkah kerja mulai dari analisis, perancangan, hingga pembuatan laporan..."
                              class="w-full rounded-2xl border border-slate-300 p-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all leading-relaxed">{{ old('instructions', $data['instructions']) }}</textarea>
                    <p class="text-[11px] text-slate-500">Gunakan penomoran (1, 2, 3...) agar tahapan terbaca terstruktur oleh siswa.</p>
                </div>
            </div>

            {{-- Card 4: Rubrik & Kriteria Penilaian --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-black">3</span>
                            Rubrik / Kriteria Penilaian LKPD
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Kriteria ini akan menjadi acuan guru saat menilai laporan LKPD di Grading Center dan ditampilkan transparan kepada siswa.
                        </p>
                    </div>
                    <button type="button" onclick="addRubricItem()"
                            class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Kriteria
                    </button>
                </div>

                <div id="rubric-container" class="space-y-3">
                    @forelse(old('assessment_rubric', $data['assessment_rubric']) as $index => $item)
                        <div class="flex items-center gap-3 fade-in-item rubric-row">
                            <span class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0 row-number">
                                {{ $loop->iteration }}
                            </span>
                            <input type="text" name="assessment_rubric[]" value="{{ $item }}"
                                   placeholder="Contoh: Identifikasi & Analisis Masalah"
                                   class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <button type="button" onclick="removeRow(this)"
                                    class="p-2.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors" title="Hapus Kriteria">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    @empty
                        <div class="flex items-center gap-3 fade-in-item rubric-row">
                            <span class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0 row-number">1</span>
                            <input type="text" name="assessment_rubric[]" value="Pemahaman Masalah & Kedalaman Analisis Kasus"
                                   class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <button type="button" onclick="removeRow(this)"
                                    class="p-2.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors" title="Hapus Kriteria">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Card 5: Unggah Berkas Panduan PDF LKPD Guru --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-7 space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-xs font-black">4</span>
                        Lampiran Berkas Panduan LKPD Guru (PDF)
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Unggah berkas lembar kerja resmi atau formulir template PDF yang dapat diunduh oleh siswa saat mengerjakan tugas.
                    </p>
                </div>

                {{-- Status Berkas Saat Ini --}}
                @if(!empty($data['pdf_file_path']))
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200/80">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-rose-500 text-white flex items-center justify-center font-black text-xs shrink-0 shadow-md">
                            PDF
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-xs font-bold text-slate-900 block truncate max-w-sm">
                                {{ $data['pdf_file_name'] ?? 'Berkas-LKPD.pdf' }}
                            </span>
                            <span class="text-[11px] text-slate-500 block">
                                {{ !empty($data['pdf_file_size']) ? round($data['pdf_file_size'] / 1024, 1) . ' KB' : 'Tersimpan di Storage' }} • Siap Diunduh Siswa
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('teacher.modules.lkpd.download', $module) }}"
                           class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Unduh File
                        </a>
                        <label class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-800 cursor-pointer ml-2">
                            <input type="checkbox" name="remove_pdf_file" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span>Hapus File</span>
                        </label>
                    </div>
                </div>
                @endif

                {{-- Area Unggah Berkas Baru --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        {{ !empty($data['pdf_file_path']) ? 'Ganti / Perbarui Berkas PDF LKPD' : 'Pilih Berkas PDF Panduan LKPD' }}
                    </label>

                    <div class="relative border-2 border-dashed border-slate-300 hover:border-indigo-400 rounded-3xl p-6 text-center transition-all bg-slate-50/50 hover:bg-indigo-50/30">
                        <input type="file" name="pdf_file" id="pdf_file" accept=".pdf,application/pdf"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="handlePdfSelect(this)">

                        <div class="flex flex-col items-center justify-center space-y-2">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-sm font-bold text-slate-800" id="pdf-filename-display">
                                    Klik atau seret berkas PDF panduan LKPD ke sini
                                </p>
                                <p class="text-xs text-slate-500">
                                    Format resmi <strong class="text-slate-700">.PDF</strong> (Maksimal ukuran: 15 MB)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Right Side: Sticky Information & Actions (4 Cols) ── --}}
        <div class="lg:col-span-4 space-y-6 sticky top-6">

            {{-- Card Ringkasan Konfigurasi --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>📋</span> Ringkasan Komponen
                    </h3>
                    <span class="text-[11px] font-extrabold px-2.5 py-0.5 rounded-full {{ $module->has_lkpd ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                        {{ $module->has_lkpd ? 'Status: ON' : 'Status: OFF' }}
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Mode Pengerjaan</span>
                        <span class="font-bold text-indigo-700" id="summary-work-mode">
                            {{ old('work_mode', $data['work_mode']) === 'group' ? '👥 Kerjasama Kelompok' : '👤 Pengerjaan Individu' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Ukuran Kelompok</span>
                        <span class="font-bold text-slate-800" id="summary-group-size">
                            {{ old('work_mode', $data['work_mode']) === 'group' ? old('group_size', $data['group_size']) : '1 Siswa (Mandiri)' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Alokasi Waktu</span>
                        <span class="font-bold text-slate-800">{{ $data['estimated_duration'] ?? 90 }} Menit</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Panduan PDF Guru</span>
                        <span class="font-bold {{ !empty($data['pdf_file_path']) ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ !empty($data['pdf_file_path']) ? '✓ Dilampirkan' : '○ Tidak ada' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="text-slate-500">Batas Berkas Siswa</span>
                        <span class="font-bold text-rose-600">Maks. 5 MB (PDF)</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-3 border-t border-slate-100 space-y-2.5">
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-lg shadow-indigo-600/25 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Simpan Pengaturan LKPD
                    </button>

                    <a href="{{ route('teacher.modules.lkpd.preview', $module) }}" target="_blank"
                       class="w-full py-3 px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center justify-center gap-2 border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Simulasi Pratinjau Siswa
                    </a>
                </div>
            </div>

            {{-- Card Edukasi Aturan PRD E-Modul --}}
            <div class="rounded-3xl bg-gradient-to-br from-indigo-900 to-slate-900 text-white p-6 shadow-xl space-y-4 border border-indigo-700/40">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/30 border border-indigo-400/40 flex items-center justify-center text-indigo-300 font-bold text-sm">
                        💡
                    </div>
                    <h4 class="text-xs font-bold text-indigo-200 uppercase tracking-wider">Aturan Sistem (PRD)</h4>
                </div>

                <ul class="space-y-2.5 text-xs text-slate-300 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <span class="text-indigo-400 font-bold">•</span>
                        <span><strong>Pengunggahan Mandiri:</strong> Meskipun dikerjakan dalam kelompok, setiap siswa wajib mengunggah salinan file PDF secara individu ke akun masing-masing.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-indigo-400 font-bold">•</span>
                        <span><strong>Navigasi Restriktif:</strong> Siswa tidak dapat berpindah ke halaman Post-test sebelum mengunggah file laporan LKPD.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-indigo-400 font-bold">•</span>
                        <span><strong>Kebijakan Re-submission:</strong> Siswa hanya dapat mengganti file selama guru belum memberikan nilai di <em>Grading Center</em>.</span>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    // Penanganan perubahan mode pengerjaan (Kelompok vs Individu)
    function handleWorkModeChange(mode) {
        const groupLabel = document.getElementById('label-mode-group');
        const individualLabel = document.getElementById('label-mode-individual');
        const groupSizeContainer = document.getElementById('group-size-container');
        const summaryWorkMode = document.getElementById('summary-work-mode');
        const summaryGroupSize = document.getElementById('summary-group-size');
        const groupSizeInput = document.getElementById('group_size');

        if (mode === 'group') {
            groupLabel.classList.add('border-indigo-600', 'bg-indigo-50/50', 'shadow-sm');
            groupLabel.classList.remove('border-slate-200', 'bg-white');
            individualLabel.classList.remove('border-indigo-600', 'bg-indigo-50/50', 'shadow-sm');
            individualLabel.classList.add('border-slate-200', 'bg-white');

            groupSizeContainer.classList.remove('opacity-50', 'pointer-events-none');
            summaryWorkMode.innerText = '👥 Kerjasama Kelompok';
            summaryGroupSize.innerText = groupSizeInput.value || '3 - 4 Siswa';
        } else {
            individualLabel.classList.add('border-indigo-600', 'bg-indigo-50/50', 'shadow-sm');
            individualLabel.classList.remove('border-slate-200', 'bg-white');
            groupLabel.classList.remove('border-indigo-600', 'bg-indigo-50/50', 'shadow-sm');
            groupLabel.classList.add('border-slate-200', 'bg-white');

            groupSizeContainer.classList.add('opacity-50', 'pointer-events-none');
            summaryWorkMode.innerText = '👤 Pengerjaan Individu';
            summaryGroupSize.innerText = '1 Siswa (Mandiri)';
        }
    }

    // Penanganan tampilan toggle status
    function updateToggleState(isChecked) {
        const statusText = document.getElementById('toggle-status-text');
        const alertBox = document.getElementById('toggle-alert');

        if (isChecked) {
            statusText.innerText = '✓ Komponen LKPD Sedang AKTIF dalam Modul';
            alertBox.className = 'mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-emerald-700';
        } else {
            statusText.innerText = '○ Komponen LKPD Sedang NON-AKTIF';
            alertBox.className = 'mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500';
        }
    }

    // Tambah baris rubrik penilaian
    function addRubricItem() {
        const container = document.getElementById('rubric-container');
        const count = container.querySelectorAll('.rubric-row').length + 1;

        const row = document.createElement('div');
        row.className = 'flex items-center gap-3 fade-in-item rubric-row';
        row.innerHTML = `
            <span class="w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0 row-number">
                ${count}
            </span>
            <input type="text" name="assessment_rubric[]" placeholder="Kriteria penilaian..."
                   class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            <button type="button" onclick="removeRow(this)"
                    class="p-2.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-colors" title="Hapus Kriteria">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
            </button>
        `;

        container.appendChild(row);
        updateRowNumbers();
    }

    // Hapus baris
    function removeRow(btn) {
        const row = btn.closest('.rubric-row');
        row.remove();
        updateRowNumbers();
    }

    // Update penomoran baris
    function updateRowNumbers() {
        document.querySelectorAll('.rubric-row').forEach((row, idx) => {
            const num = row.querySelector('.row-number');
            if (num) num.innerText = idx + 1;
        });
    }

    // Tampilkan nama file saat dipilih
    function handlePdfSelect(input) {
        const display = document.getElementById('pdf-filename-display');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            display.innerText = `📄 Terpilih: ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
            display.classList.add('text-indigo-700');
        }
    }
</script>
@endpush
