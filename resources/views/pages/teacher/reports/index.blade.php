@extends('layouts.teacher.dashboardteacher')

@section('title', 'Pusat Rekap Laporan Nilai Excel (.xlsx) — Teacher Workspace')
@section('page-title', 'Laporan Nilai Excel (.xlsx)')

@section('content')

{{-- ══ Header Workspace & Title ══ --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">
            <a href="{{ route('teacher.dashboard') }}" class="hover:text-blue-600 transition-colors">Workspace</a>
            <span>/</span>
            <span class="text-emerald-600">Laporan Spreadsheet Excel</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            <span>Pusat Laporan Nilai (Excel .XLSX)</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                ● Live Generator
            </span>
        </h1>
        <p class="mt-1.5 text-sm text-slate-500 max-w-3xl leading-relaxed">
            Pilih rombongan belajar kelas di bawah untuk melihat mata pelajaran yang Anda ampu, membuka modul pembelajaran, serta mengunduh rekapitulasi nilai siswa dalam format <strong>Microsoft Excel (.xlsx)</strong>.
        </p>
    </div>

    <div class="flex items-center gap-3 shrink-0">
        <a href="{{ route('teacher.grading.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 bg-white border border-slate-200/90 rounded-xl hover:bg-slate-50 hover:border-slate-300 shadow-sm transition-all">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Buka Grading Center</span>
        </a>
    </div>
</div>

{{-- ══ Stat Cards (4 Grid) ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Card 1: Total Modul Ajar --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Modul Pembelajaran</span>
            <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-slate-900">{{ $stats['total_modules'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Modul Anda</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
            <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $stats['published_modules'] }} Terpublikasi
            </span>
        </div>
    </div>

    {{-- Card 2: Pengumpulan Tugas --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pengumpulan</span>
            <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-slate-900">{{ $stats['total_submissions'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Rekap Siswa</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
            <span class="text-slate-600 font-semibold">Terekam di sistem penilaian</span>
        </div>
    </div>

    {{-- Card 3: Penilaian Selesai --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Tuntas Dinilai</span>
            <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-emerald-600">{{ $stats['completed_grading'] }}</span>
            <span class="text-xs font-semibold text-slate-500">Siswa Dinilai</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
            <span class="text-emerald-700 font-medium">Siap diolah di Excel</span>
        </div>
    </div>

    {{-- Card 4: Rata-Rata Nilai Akhir --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rata-Rata Kelas</span>
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-amber-600">{{ $stats['average_score'] }}</span>
            <span class="text-xs font-semibold text-slate-500">/ 100 Skala</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
            <span class="text-slate-600 font-semibold">{{ $stats['total_classes'] }} Kelas Binaan</span>
        </div>
    </div>

</div>

{{-- ══ Filter & Search Bar ══ --}}
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-8">
    <form method="GET" action="{{ route('teacher.reports.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        {{-- Search Bar --}}
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </div>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari rombel kelas (misal: X TE 2, RPL, PPLG)..."
                   class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
        </div>

        {{-- Filter Tingkat & Jurusan --}}
        <div class="flex items-center gap-3 flex-wrap">
            <select name="grade"
                    onchange="this.form.submit()"
                    class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                <option value="all">-- Semua Tingkat Kelas --</option>
                <option value="X" {{ $grade === 'X' ? 'selected' : '' }}>Tingkat X (Sepuluh)</option>
                <option value="XI" {{ $grade === 'XI' ? 'selected' : '' }}>Tingkat XI (Sebelas)</option>
                <option value="XII" {{ $grade === 'XII' ? 'selected' : '' }}>Tingkat XII (Dua Belas)</option>
                <option value="XIII" {{ $grade === 'XIII' ? 'selected' : '' }}>Tingkat XIII (4 Tahun)</option>
            </select>

            <select name="major_id"
                    onchange="this.form.submit()"
                    class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-slate-700 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                <option value="all">-- Semua Jurusan --</option>
                @foreach($majors as $m)
                    <option value="{{ $m->id }}" {{ (string)($majorId ?? '') === (string)$m->id ? 'selected' : '' }}>
                        {{ $m->code }} - {{ $m->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                Filter
            </button>

            @if($search || ($grade && $grade !== 'all') || ($majorId && $majorId !== 'all'))
                <a href="{{ route('teacher.reports.index') }}"
                   class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

{{-- ══ Grid Direktori Kelas (Tahap 1) ══ --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @forelse($classesList as $cls)
        <div class="group relative rounded-3xl bg-white border border-slate-200/80 hover:border-blue-300 transition-all duration-300 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md">
            
            {{-- Top Accent Line --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-500"></div>

            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                {{-- Badges: Grade & Section --}}
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wider">
                        Tingkat {{ $cls->grade }}
                    </span>

                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Rombel {{ $cls->section }}
                    </span>
                </div>

                {{-- Class Full Name & Major Description --}}
                <div class="space-y-1">
                    <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
                        {{ $cls->full_name }}
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">
                        {{ $cls->major ? $cls->major->name : $cls->major_name }}
                    </p>
                </div>

                {{-- Stats Summary --}}
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Siswa Terdaftar:</span>
                        <span class="font-bold text-slate-900 px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100">
                            {{ $cls->students_count }} Siswa
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Modul Pembelajaran Anda:</span>
                        <span class="font-bold text-slate-700">
                            {{ $cls->teacher_modules_count }} Modul
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Mata Pelajaran:</span>
                        <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                            {{ $cls->teacher_subjects_count }} Mapel
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Button Footer --}}
            <div class="p-4 pt-0">
                <a href="{{ route('teacher.reports.class', $cls->id) }}"
                   class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center gap-2 transition-all shadow-sm shadow-blue-600/25 group-hover:shadow-md">
                    <span>Pilih Kelas & Lihat Mapel</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-slate-200">
            <p class="text-sm font-bold text-slate-700">Tidak ada rombel kelas yang cocok dengan filter.</p>
            <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau reset filter.</p>
        </div>
    @endforelse
</div>

@endsection
