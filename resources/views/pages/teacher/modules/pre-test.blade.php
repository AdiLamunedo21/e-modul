@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Pre-test — ' . $module->title)
@section('page-title', 'Editor Pre-test (Komponen Inti)')

@push('head')
<style>
    .question-card {
        scroll-margin-top: 90px;
        transition: all .2s ease-in-out;
    }
    .question-card:hover {
        border-color: #cbd5e1;
    }
    .option-row {
        transition: background-color .15s ease;
    }
    .option-row.is-correct {
        background-color: #f0fdf4;
        border-color: #86efac;
    }
    .option-row.is-correct input[type="text"] {
        background-color: #ffffff;
    }
    .fade-in-item {
        animation: fadeInSlide .25s ease-out;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .sticky-nav a.active {
        border-left: 3px solid #2563eb;
        background-color: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
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
    <span class="font-semibold text-slate-800">Komponen Inti: Pre-test</span>
</nav>

{{-- Flash Message --}}
@if(session('success'))
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>{{ session('success') }}</span>
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
<div class="bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 mb-8 relative overflow-hidden border border-blue-700/40">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3">
            <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                <span class="flex items-center gap-1.5 text-blue-200">
                    <span>⚡</span>
                    <span>Pendahuluan — Pre-test Diagnostik</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-400/20 text-amber-300 border border-amber-400/40 uppercase tracking-wider">
                    Opsional (Toggle)
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">Quiz Builder: Pre-test</h1>
            <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                Rancang kuis pembuka interaktif untuk mendiagnosis kemampuan awal siswa sebelum masuk ke materi inti. Penilaian pilihan ganda dihitung secara otomatis oleh sistem.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('teacher.modules.show', $module) }}"
               class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                ← Kembali ke Detail
            </a>
            <a href="{{ route('teacher.modules.pre-test.preview', $module) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-lg shadow-emerald-950/40 flex items-center gap-2 border border-emerald-400/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pratinjau Siswa
            </a>
        </div>
    </div>
</div>

<form id="pre-test-form" action="{{ route('teacher.modules.pre-test.update', $module) }}" method="POST">
    @csrf
    @method('PATCH')

    {{-- ══ Main Grid ══ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">

        {{-- ── LEFT 3 COLUMNS: Settings & Questions ───────────────────────────── --}}
        <div class="xl:col-span-3 space-y-6">

            {{-- 1. Sakelar Utama & Pengaturan Umum --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg shrink-0">
                            ⚙️
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Aktivasi & Pengaturan Pre-test</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Jika diaktifkan, halaman Pre-test akan muncul sebagai langkah pertama Komponen Inti bagi siswa.</p>
                        </div>
                    </div>

                    {{-- Switch Toggle --}}
                    <label class="inline-flex items-center cursor-pointer select-none shrink-0 group">
                        <input type="checkbox" name="has_pre_test" id="has_pre_test_toggle" value="1"
                               class="sr-only"
                               {{ old('has_pre_test', $module->has_pre_test) ? 'checked' : '' }}
                               onchange="togglePreTestStatus(this.checked)">
                        <div id="pre-test-toggle-track"
                             class="relative w-12 h-7 rounded-full border-2 transition-colors duration-300 ease-in-out {{ old('has_pre_test', $module->has_pre_test) ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                            <div id="pre-test-toggle-thumb"
                                 class="absolute top-[2px] left-[2px] h-5 w-5 rounded-full bg-white shadow-md border border-slate-300/90 transition-transform duration-300 ease-in-out"
                                 style="transform: translateX({{ old('has_pre_test', $module->has_pre_test) ? '20px' : '0px' }});">
                            </div>
                        </div>
                        <span id="toggle-status-badge" class="ml-3 text-xs font-extrabold uppercase px-2.5 py-1 rounded-full border transition-all duration-300 shrink-0 {{ old('has_pre_test', $module->has_pre_test) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-300' }}">
                            {{ old('has_pre_test', $module->has_pre_test) ? 'Aktif (ON)' : 'Nonaktif (OFF)' }}
                        </span>
                    </label>
                </div>

                {{-- Fields --}}
                <div class="space-y-5 pt-6">
                    {{-- Row 1: Judul Pre-test --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Pre-test</label>
                        <input type="text" name="title" value="{{ old('title', $preTest->title ?? 'Pre-test Pembuka') }}"
                               placeholder="Contoh: Pre-test: Pemahaman Awal Konsep Basis Data"
                               class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </div>

                    {{-- Row 2: Batas Nilai & Info Batas Waktu per Soal --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Batas Nilai Kelulusan (KKTP)</label>
                            <div class="flex rounded-xl border border-slate-300 bg-slate-50 overflow-hidden focus-within:border-blue-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20 transition-all shadow-sm">
                                <input type="number" name="kktp" min="0" max="100"
                                       value="{{ old('kktp', $preTest->kktp ?? 75) }}"
                                       class="w-full bg-transparent px-4 py-2.5 text-sm text-slate-900 focus:outline-none">
                                <span class="inline-flex items-center px-3.5 bg-slate-100 text-xs font-bold text-slate-500 border-l border-slate-200 select-none shrink-0">
                                    Poin
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Standar nilai minimum pemahaman awal.</p>
                        </div>

                        <div class="rounded-2xl border border-blue-200/80 bg-blue-50/70 p-4 flex items-start gap-3 shadow-2xs">
                            <span class="text-xl shrink-0">⏱️</span>
                            <div class="text-xs text-blue-900 leading-relaxed">
                                <span class="font-bold block text-blue-950 mb-0.5">Pengaturan Waktu Mandiri per Butir Soal</span>
                                Durasi total kuis ditiadakan. Anda dapat menentukan batas waktu yang berbeda-beda untuk tiap butir soal secara leluasa langsung pada kartu soal di bawah (misalnya: Soal 1 = 20 detik, Soal 3 = 15 detik).
                            </div>
                        </div>
                    </div>

                    {{-- Row 3: Acak Urutan Soal --}}
                    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="randomize_questions" value="1"
                                   class="w-4 h-4 mt-0.5 text-blue-600 rounded border-slate-300 focus:ring-blue-500"
                                   {{ old('randomize_questions', $preTest->randomize_questions ?? false) ? 'checked' : '' }}>
                            <div>
                                <span class="text-xs font-bold text-slate-800">Acak Urutan Butir Soal</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Jika dicentang, urutan nomor soal akan diacak otomatis untuk setiap siswa saat mengerjakan.</p>
                            </div>
                        </label>
                    </div>

                    {{-- Row 4: Petunjuk Pengerjaan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Petunjuk Pengerjaan</label>
                        <textarea name="instructions" rows="2"
                                  placeholder="Tuliskan panduan untuk siswa sebelum memulai kuis..."
                                  class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all resize-none">{{ old('instructions', $preTest->instructions ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. Question Builder Section --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shrink-0">
                            📝
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Daftar Soal Pilihan Ganda (Tersimpan di Database)</h2>
                            <p class="text-xs text-slate-500">Buat pertanyaan, tentukan pilihan A–E, dan tandai satu kunci jawaban yang benar.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="addNewQuestion()"
                                class="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow shadow-blue-600/20 transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Tambah Soal Baru
                        </button>
                    </div>
                </div>

                {{-- Container Soal --}}
                <div id="questions-container" class="space-y-6">
                    @php
                        $questions = old('questions', $preTest->questions ?? []);
                    @endphp

                    @if(empty($questions) || (is_countable($questions) && count($questions) === 0))
                        {{-- Placeholder jika belum ada soal --}}
                        <div id="empty-state" class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">Belum ada soal tersimpan di database</h3>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Klik tombol <strong>"Tambah Soal Baru"</strong> untuk menyusun butir soal.</p>
                            <button type="button" onclick="addNewQuestion()" class="mt-4 px-4 py-2 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all">
                                + Tambah Soal Pertama
                            </button>
                        </div>
                    @else
                        @foreach($questions as $qIndex => $question)
                            @include('pages.teacher.modules.partials.question-card', ['qIndex' => $qIndex, 'question' => $question, 'accent' => 'blue'])
                        @endforeach
                    @endif
                </div>

                {{-- Tombol Bawah Tambah Soal --}}
                <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-center">
                    <button type="button" onclick="addNewQuestion()"
                            class="px-5 py-2.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Soal Pilihan Ganda Berikutnya
                    </button>
                </div>
            </div>

        </div>

        {{-- ── RIGHT 1 COLUMN: Sticky Navigator & Summary ────────────────────── --}}
        <div class="xl:col-span-1 space-y-6 sticky top-6">

            {{-- Summary Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4 flex items-center justify-between">
                    <span>Ringkasan Kuis</span>
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                </h3>

                {{-- Metric Tiles (2x2 Grid) --}}
                <div class="grid grid-cols-2 gap-2.5 mb-5">
                    {{-- Status Pre-test --}}
                    <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-slate-500">Status</span>
                        <div class="mt-1.5">
                            <span id="summary-status" class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold {{ old('has_pre_test', $module->has_pre_test) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                {{ old('has_pre_test', $module->has_pre_test) ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    {{-- Jumlah Soal --}}
                    <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-slate-500">Jumlah Soal</span>
                        <div class="mt-1.5">
                            <span id="summary-total-questions" class="text-sm font-extrabold text-slate-900">0 Soal</span>
                        </div>
                    </div>

                    {{-- Total Bobot --}}
                    <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-blue-700">Total Bobot</span>
                        <div class="mt-1.5">
                            <span id="summary-total-points" class="text-sm font-extrabold text-blue-700">0 Poin</span>
                        </div>
                    </div>

                    {{-- Nilai Maksimal --}}
                    <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between">
                        <span class="text-[11px] font-semibold text-slate-500">Batas Nilai</span>
                        <div class="mt-1.5">
                            <span id="summary-kktp" class="text-sm font-extrabold text-slate-900">{{ old('kktp', $preTest->kktp ?? 75) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Navigator Lompat Soal --}}
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2.5">Navigasi Butir Soal</p>
                    <div id="quick-jump-container" class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-1">
                        {{-- Diisi secara dinamis via JS --}}
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 pt-5 border-t border-slate-100 space-y-2">
                    <button type="submit"
                            class="w-full py-3 px-4 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Simpan Konfigurasi & Soal
                    </button>
                    <a href="{{ route('teacher.modules.show', $module) }}"
                       class="w-full py-2.5 px-4 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all text-center block">
                        Batal
                    </a>
                </div>
            </div>

            {{-- Info Pedoman --}}
            <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 text-xs text-blue-800 leading-relaxed">
                <p class="font-bold mb-1.5 flex items-center gap-1.5 text-blue-900">
                    💡 Tips Penilaian Pre-test:
                </p>
                <p class="text-[11px]">
                    Siswa langsung mendapatkan nilai saat menuntaskan seluruh butir soal. Nilai pre-test akan dicatat pada rekapitulasi penilaian dan disajikan di Laporan Excel (.xlsx) jika diaktifkan.
                </p>
            </div>

        </div>

    </div>
</form>


@endsection

@push('scripts')
<script>
    let currentQuestionCount = 0;

    // Template soal baru
    function getQuestionTemplate(index, data = {}) {
        const pertanyaan = data.question_text || data.pertanyaan || '';
        const pilihan = data.options || data.pilihan || { A: '', B: '', C: '', D: '', E: '' };
        const kunci = data.correct_answer || data.kunci_jawaban || 'A';
        const bobot = data.score_weight || data.bobot || 10;
        const waktu = data.time_limit_seconds || data.waktu || '';
        const pembahasan = data.explanation || data.pembahasan || '';

        return `
        <div class="question-card fade-in-item bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative" id="question-card-${index}">
            <div class="flex items-center justify-between gap-4 mb-4 pb-3 border-b border-slate-100 flex-wrap sm:flex-nowrap">
                <div class="flex items-center gap-2.5">
                    <span class="q-number-badge w-7 h-7 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs font-black shadow-sm shadow-blue-500/30">
                        ${index + 1}
                    </span>
                    <span class="text-xs font-bold text-slate-800">Pertanyaan Soal #${index + 1}</span>
                </div>
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1 hover:border-slate-300 transition-colors" title="Batas waktu pengerjaan butir soal ini dalam detik (contoh: 20 untuk 20 detik, 15 untuk 15 detik)">
                        <label class="text-[11px] font-bold text-slate-600">⏱️ Waktu:</label>
                        <input type="number" name="questions[${index}][time_limit_seconds]" value="${waktu}" min="0" max="3600"
                               placeholder="Detik"
                               class="w-14 text-center text-xs font-bold text-slate-800 bg-transparent focus:outline-none">
                        <span class="text-[10px] text-slate-400 font-bold select-none">dtk</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1">
                        <label class="text-[11px] font-semibold text-slate-500">Bobot Poin:</label>
                        <input type="number" name="questions[${index}][score_weight]" value="${bobot}" min="1" max="100"
                               oninput="updateSummary()"
                               class="w-12 text-center text-xs font-bold text-blue-600 bg-transparent focus:outline-none">
                    </div>
                    <button type="button" onclick="duplicateQuestion(${index})" title="Duplikat Soal"
                            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                    </button>
                    <button type="button" onclick="deleteQuestion(${index})" title="Hapus Soal"
                            class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                </div>
            </div>

            {{-- Teks Pertanyaan --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Teks Soal / Pertanyaan</label>
                <textarea name="questions[${index}][question_text]" rows="3" required
                          placeholder="Tuliskan butir soal di sini..."
                          class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">${pertanyaan}</textarea>
            </div>

            {{-- Pilihan Ganda (A, B, C, D, E) --}}
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700">Pilihan Jawaban & Kunci Jawaban</label>
                    <span class="text-[11px] text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 font-semibold">
                        Pilih radio button pada jawaban yang benar
                    </span>
                </div>

                <div class="space-y-2.5">
                    ${['A', 'B', 'C', 'D', 'E'].map(opt => `
                        <div class="option-row flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 ${kunci === opt ? 'is-correct' : ''}">
                            <label class="flex items-center gap-2 cursor-pointer shrink-0">
                                <input type="radio" name="questions[${index}][correct_answer]" value="${opt}"
                                       ${kunci === opt ? 'checked' : ''}
                                       onchange="onCorrectAnswerChange(this, ${index})"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                <span class="w-6 h-6 rounded-lg ${kunci === opt ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700'} flex items-center justify-center text-xs font-bold option-label">
                                    ${opt}
                                </span>
                            </label>
                            <input type="text" name="questions[${index}][options][${opt}]"
                                   value="${pilihan[opt] || ''}"
                                   ${opt === 'A' || opt === 'B' ? 'required' : ''}
                                   placeholder="Pilihan ${opt} ${opt === 'A' || opt === 'B' ? '(Wajib)' : '(Opsional)'}..."
                                   class="flex-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                        </div>
                    `).join('')}
                </div>
            </div>

            {{-- Pembahasan Jawaban (Opsional) --}}
            <div class="pt-3 border-t border-slate-100">
                <details class="group" ${pembahasan ? 'open' : ''}>
                    <summary class="cursor-pointer text-xs font-bold text-slate-600 hover:text-blue-600 flex items-center gap-1.5 select-none">
                        <svg class="w-3.5 h-3.5 transform group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        <span>Tambahkan Pembahasan / Keterangan Jawaban (Opsional)</span>
                    </summary>
                    <div class="mt-2.5 pl-5">
                        <textarea name="questions[${index}][explanation]" rows="2"
                                  placeholder="Tuliskan alasan mengapa jawaban tersebut benar untuk referensi..."
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs text-slate-800 focus:border-blue-500 focus:bg-white focus:outline-none transition-all resize-none">${pembahasan}</textarea>
                    </div>
                </details>
            </div>
        </div>
        `;
    }

    // Ubah highlight kunci jawaban saat radio dipilih
    function onCorrectAnswerChange(radio, questionIndex) {
        const card = document.getElementById(`question-card-${questionIndex}`);
        if (!card) return;
        const rows = card.querySelectorAll('.option-row');
        rows.forEach(row => {
            row.classList.remove('is-correct');
            const label = row.querySelector('.option-label');
            if (label) {
                label.className = 'w-6 h-6 rounded-lg bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold option-label';
            }
        });

        const activeRow = radio.closest('.option-row');
        if (activeRow) {
            activeRow.classList.add('is-correct');
            const activeLabel = activeRow.querySelector('.option-label');
            if (activeLabel) {
                activeLabel.className = 'w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold option-label';
            }
        }
    }

    // Tambah Soal Baru
    function addNewQuestion(data = {}) {
        const container = document.getElementById('questions-container');
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.remove();

        const count = container.querySelectorAll('.question-card').length;
        const html = getQuestionTemplate(count, data);
        container.insertAdjacentHTML('beforeend', html);

        renumberQuestions();
        updateSummary();

        // Scroll to the newly added question
        const newCard = document.getElementById(`question-card-${count}`);
        if (newCard) {
            newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Duplikasi Soal
    function duplicateQuestion(index) {
        const card = document.getElementById(`question-card-${index}`);
        if (!card) return;

        const pertanyaan = card.querySelector(`textarea[name="questions[${index}][question_text]"]`)?.value
            || card.querySelector(`textarea[name="questions[${index}][pertanyaan]"]`)?.value || '';
        const kunci = card.querySelector(`input[name="questions[${index}][correct_answer]"]:checked`)?.value
            || card.querySelector(`input[name="questions[${index}][kunci_jawaban]"]:checked`)?.value || 'A';
        const bobot = card.querySelector(`input[name="questions[${index}][score_weight]"]`)?.value
            || card.querySelector(`input[name="questions[${index}][bobot]"]`)?.value || 10;
        const waktu = card.querySelector(`input[name="questions[${index}][time_limit_seconds]"]`)?.value || '';
        const pembahasan = card.querySelector(`textarea[name="questions[${index}][explanation]"]`)?.value
            || card.querySelector(`textarea[name="questions[${index}][pembahasan]"]`)?.value || '';

        const pilihan = {};
        ['A', 'B', 'C', 'D', 'E'].forEach(opt => {
            pilihan[opt] = card.querySelector(`input[name="questions[${index}][options][${opt}]"]`)?.value
                || card.querySelector(`input[name="questions[${index}][pilihan][${opt}]"]`)?.value || '';
        });

        addNewQuestion({
            question_text: `${pertanyaan} (Salinan)`,
            options: pilihan,
            correct_answer: kunci,
            score_weight: bobot,
            time_limit_seconds: waktu,
            explanation: pembahasan
        });
    }

    // Hapus Soal
    function deleteQuestion(index) {
        if (!confirm('Apakah Anda yakin ingin menghapus butir soal ini?')) return;
        const card = document.getElementById(`question-card-${index}`);
        if (card) {
            card.remove();
            renumberQuestions();
            updateSummary();
        }
    }

    // Renumbering questions
    function renumberQuestions() {
        const container = document.getElementById('questions-container');
        const cards = container.querySelectorAll('.question-card');

        cards.forEach((card, idx) => {
            card.id = `question-card-${idx}`;
            const badge = card.querySelector('.q-number-badge');
            if (badge) badge.textContent = idx + 1;

            const title = card.querySelector('.text-slate-800');
            if (title) title.textContent = `Pertanyaan Soal #${idx + 1}`;

            // Update input names
            const textareaQ = card.querySelector('textarea[name^="questions"][name*="question_text"], textarea[name^="questions"][name*="pertanyaan"]');
            if (textareaQ) textareaQ.name = `questions[${idx}][question_text]`;

            const bobotInput = card.querySelector('input[name^="questions"][name*="score_weight"], input[name^="questions"][name*="bobot"]');
            if (bobotInput) bobotInput.name = `questions[${idx}][score_weight]`;

            const waktuInput = card.querySelector('input[name^="questions"][name*="time_limit_seconds"]');
            if (waktuInput) waktuInput.name = `questions[${idx}][time_limit_seconds]`;

            const pembahasanTA = card.querySelector('textarea[name^="questions"][name*="explanation"], textarea[name^="questions"][name*="pembahasan"]');
            if (pembahasanTA) pembahasanTA.name = `questions[${idx}][explanation]`;

            const radioInputs = card.querySelectorAll('input[type="radio"][name^="questions"]');
            radioInputs.forEach(radio => {
                radio.name = `questions[${idx}][correct_answer]`;
                radio.setAttribute('onchange', `onCorrectAnswerChange(this, ${idx})`);
            });

            ['A', 'B', 'C', 'D', 'E'].forEach(opt => {
                const optInput = card.querySelector(`input[name^="questions"][name*="[options][${opt}]"], input[name^="questions"][name*="[pilihan][${opt}]"]`);
                if (optInput) optInput.name = `questions[${idx}][options][${opt}]`;
            });
        });
    }

    // Update Quick Jump and Summary
    function updateSummary() {
        const container = document.getElementById('questions-container');
        const cards = container.querySelectorAll('.question-card');
        const totalQ = cards.length;

        document.getElementById('summary-total-questions').textContent = `${totalQ} Soal`;

        let totalPoints = 0;
        cards.forEach(card => {
            const bInput = card.querySelector('input[name*="score_weight"], input[name*="bobot"]');
            const val = parseInt(bInput?.value || 0, 10);
            totalPoints += isNaN(val) ? 0 : val;
        });
        document.getElementById('summary-total-points').textContent = `${totalPoints} Poin`;

        // Update Quick Jump buttons
        const jumpContainer = document.getElementById('quick-jump-container');
        if (jumpContainer) {
            jumpContainer.innerHTML = '';
            cards.forEach((_, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'w-8 h-8 rounded-lg bg-slate-100 hover:bg-blue-600 hover:text-white text-xs font-bold text-slate-700 transition-all flex items-center justify-center';
                btn.textContent = idx + 1;
                btn.onclick = () => {
                    document.getElementById(`question-card-${idx}`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                };
                jumpContainer.appendChild(btn);
            });
        }
    }



    // Toggle switch status handler
    function togglePreTestStatus(isChecked) {
        const track = document.getElementById('pre-test-toggle-track');
        const thumb = document.getElementById('pre-test-toggle-thumb');
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

    // Init on page load
    document.addEventListener('DOMContentLoaded', function() {
        renumberQuestions();
        updateSummary();
    });
</script>
@endpush
