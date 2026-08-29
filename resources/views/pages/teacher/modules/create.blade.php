@extends('layouts.teacher.dashboardteacher')

@section('title', 'Buat Modul Baru — Teacher Workspace')
@section('page-title', 'Buat Modul Baru')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('teacher.modules.index') }}" class="hover:text-blue-600 transition-colors font-medium">Manajer Modul</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800">Buat Modul Baru</span>
</nav>

<div class="max-w-2xl">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900">Buat E-Modul Baru</h1>
        <p class="mt-1.5 text-sm text-slate-500">
            Langkah awal: tentukan judul dan target kelas. Setelah tersimpan sebagai <strong>Draft</strong>, Anda bisa mengisi konten Informasi Umum, mengaktifkan 7 Komponen Inti, dan Bagian Akhir melalui halaman detail modul.
        </p>
    </div>

    {{-- Form --}}
    <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-6 sm:p-8">
        <form action="{{ route('teacher.modules.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Judul Modul --}}
            <div>
                <label for="title" class="block text-sm font-bold text-slate-700 mb-1.5">
                    Judul E-Modul <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    required
                    maxlength="255"
                    placeholder="Contoh: Sistem Basis Data: Konsep Relasi & Query SQL"
                    class="w-full rounded-xl border @error('title') border-red-400 bg-red-50 @else border-slate-300 bg-slate-50 @enderror px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                >
                @error('title')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Mata Pelajaran --}}
            <div>
                <label for="subject_id" class="block text-sm font-bold text-slate-700 mb-1.5">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <select
                    name="subject_id"
                    id="subject_id"
                    required
                    class="w-full rounded-xl border @error('subject_id') border-red-400 bg-red-50 @else border-slate-300 bg-slate-50 @enderror px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                >
                    <option value="" disabled selected>Pilih Mata Pelajaran...</option>
                    @foreach($teacherSubjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }} ({{ $subject->code ?? 'MAPEL' }})
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Target Kelas --}}
            <div>
                <label for="class_id" class="block text-sm font-bold text-slate-700 mb-1.5">
                    Target Kelas / Jurusan <span class="text-red-500">*</span>
                </label>
                <select
                    name="class_id"
                    id="class_id"
                    required
                    class="w-full rounded-xl border @error('class_id') border-red-400 bg-red-50 @else border-slate-300 bg-slate-50 @enderror px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all"
                >
                    <option value="" disabled selected>Pilih Kelas Target...</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-1.5 text-xs text-slate-500">Modul yang dipublikasikan hanya bisa diakses oleh siswa dari kelas yang dipilih.</p>
            </div>

            {{-- Info Banner 3 Bagian Modul --}}
            <div class="rounded-xl bg-blue-50 border border-blue-100 p-4">
                <p class="text-xs font-bold text-blue-800 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    Cara Kerja E-Module Builder
                </p>
                <div class="space-y-1.5">
                    @foreach([
                        ['1', 'Bagian Awal & Pendahuluan', 'Kata Pengantar, Petunjuk Penggunaan, Peta Konsep, Glosarium, Tujuan Pembelajaran.'],
                        ['2', 'Komponen Inti (7 Toggle Opsional)', 'Pre-test, Materi+PPT, Video YouTube, Praktik Embed, Job Sheet, LKPD, Post-test.'],
                        ['3', 'Bagian Akhir (Mandatori)', 'Evaluasi Sumatif, Kunci Jawaban & Standar Nilai, & Daftar Pustaka.'],
                    ] as [$no, $title, $desc])
                        <div class="flex items-start gap-2.5 text-xs text-blue-800">
                            <span class="font-black bg-blue-200 text-blue-800 rounded px-1.5 py-0.5 shrink-0">{{ $no }}</span>
                            <span><strong>{{ $title }}</strong> — {{ $desc }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow shadow-blue-600/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Buat & Lanjut ke Detail
                </button>
                <a href="{{ route('teacher.modules.index') }}"
                   class="px-5 py-3 text-sm font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
