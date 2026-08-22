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
            <a href="{{ route('teacher.grading.index') }}" class="hover:text-blue-600 transition-colors">Evaluasi & Penilaian</a>
            <span>/</span>
            <span class="text-emerald-600">Laporan Spreadsheet</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            <span>Pusat Laporan Nilai (Excel .XLSX)</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                ● Live Generator
            </span>
        </h1>
        <p class="mt-1.5 text-sm text-slate-500 max-w-3xl leading-relaxed">
            Ekspor rekapitulasi nilai hasil belajar siswa per modul ke dalam berkas spreadsheet <strong>Microsoft Excel (.xlsx)</strong>. Kolom penilaian beradaptasi secara otomatis hanya menampilkan komponen belajar yang Anda aktifkan pada modul.
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
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

    {{-- Card 4: Rata-rata Skor --}}
    <div class="rounded-2xl bg-white p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rata-Rata Nilai</span>
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-black text-slate-900">{{ $stats['average_score'] }}</span>
            <span class="text-xs font-semibold text-slate-500">/ 100 Poin</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
            <span class="text-slate-600">Rata-rata akumulasi nilai kelas</span>
        </div>
    </div>

</div>

{{-- ══ Filter & Search Bar ══ --}}
<div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm mb-6">
    <form action="{{ route('teacher.reports.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 sm:gap-4">
        {{-- Search Input --}}
        <div class="relative flex-1 min-w-[240px] flex items-center">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari judul modul pembelajaran..."
                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm bg-slate-50/70 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
        </div>

        {{-- Dropdown Filters & Actions --}}
        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
            {{-- Filter Kelas --}}
            <div class="w-full sm:w-auto min-w-[180px]">
                <select name="class_id"
                        onchange="this.form.submit()"
                        class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-700 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                    <option value="">Semua Target Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status --}}
            <div class="w-full sm:w-auto min-w-[150px]">
                <select name="status"
                        onchange="this.form.submit()"
                        class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-700 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Aktif)</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed (Selesai)</option>
                </select>
            </div>

            {{-- Submit & Reset Buttons --}}
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="px-4 py-2.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-sm shrink-0 flex-1 sm:flex-initial text-center justify-center">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'class_id', 'status']))
                    <a href="{{ route('teacher.reports.index') }}"
                       class="px-3.5 py-2.5 text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition-all shrink-0 flex-1 sm:flex-initial text-center justify-center">
                        ✕ Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- ══ Daftar Modul & Rekapitulasi Laporan ══ --}}
<div class="space-y-4">
    @forelse($modules as $module)
        @php
            $statsModule = $module->gradingStats();
            $activeComponents = $module->activeGradedComponents();
            $status = $module->statusLabel();
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                
                {{-- Modul Info & Komponen Aktif --}}
                <div class="space-y-2.5 flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase border {{ $status['color'] }}">
                            {{ $status['label'] }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            {{ $module->schoolClass ? $module->schoolClass->full_name : 'Semua Kelas' }}
                        </span>
                        <span class="text-xs text-slate-400">
                            Batas Nilai: <strong class="text-slate-600">{{ $module->postTestKktp() }} Poin</strong>
                        </span>
                    </div>

                    <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        {{ $module->title }}
                    </h3>

                    {{-- Dynamic Active Evaluation Components Pills --}}
                    <div class="flex flex-wrap items-center gap-1.5 pt-1">
                        <span class="text-[11px] font-bold text-slate-400 mr-1">Kolom Nilai Adaptif:</span>
                        @forelse($activeComponents as $comp)
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-md border {{ $comp['badge'] }}">
                                <span>{{ $comp['icon'] }}</span>
                                <span>{{ $comp['name'] }}</span>
                            </span>
                        @empty
                            <span class="text-[11px] text-slate-400 italic">Tidak ada komponen evaluasi berbobot aktif</span>
                        @endforelse
                    </div>
                </div>

                {{-- Progress Bar & Penilaian Summary --}}
                <div class="w-full lg:w-72 bg-slate-50 p-4 rounded-xl border border-slate-200/60 shrink-0">
                    <div class="flex justify-between items-center text-xs font-semibold mb-2">
                        <span class="text-slate-600">Progres Penilaian</span>
                        <span class="text-emerald-700 font-bold">
                            {{ $statsModule['graded_count'] }} / {{ $statsModule['total_students'] }} Siswa ({{ $statsModule['progress_pct'] }}%)
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-emerald-600 h-2 rounded-full transition-all" style="width: {{ $statsModule['progress_pct'] }}%"></div>
                    </div>
                    <div class="mt-2.5 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Menunggu: <strong class="text-amber-600">{{ $statsModule['pending_count'] }}</strong></span>
                        <span>Rata-rata: <strong class="text-emerald-600">{{ $statsModule['avg_score'] }} Poin</strong></span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-row lg:flex-col items-center lg:items-end gap-2 shrink-0">
                    {{-- Download Excel Button --}}
                    <a href="{{ route('teacher.reports.export.module', $module) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-xl shadow-sm shadow-emerald-600/20 transition-all w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-8.625 1.125V5.625m17.25 13.875c.621 0 1.125-.504 1.125-1.125M20.625 19.5h-7.5c-.621 0-1.125-.504-1.125-1.125m8.625 1.125V5.625m-17.25 0c0-.621.504-1.125 1.125-1.125h15c.621 0 1.125.504 1.125 1.125m-17.25 0v12.75c0 .621.504 1.125 1.125 1.125h15c.621 0 1.125-.504 1.125-1.125V5.625m-17.25 0h17.25M9 4.5v15M15 4.5v15M3.75 9.75h16.5M3.75 14.25h16.5" />
                        </svg>
                        <span>Unduh Rekap Excel (.xlsx)</span>
                    </a>

                    {{-- View Grading Matrix --}}
                    <a href="{{ route('teacher.grading.show', $module) }}"
                       class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:text-blue-600 rounded-xl transition-all w-full sm:w-auto">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Lihat Matriks Nilai</span>
                    </a>
                </div>

            </div>
        </div>
    @empty
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Tidak Ada Modul Ditemukan</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-5">
                Tidak ada modul pembelajaran yang sesuai dengan kriteria filter atau pencarian Anda.
            </p>
            <a href="{{ route('teacher.reports.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                Reset Filter Pencarian
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="pt-4">
        {{ $modules->links() }}
    </div>
</div>


@endsection
