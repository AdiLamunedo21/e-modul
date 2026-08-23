@extends('layouts.admin.dashboardadmin')

@section('title', 'Master Data Siswa per Rombel Kelas — Admin E-Modul')
@section('page-title', 'Master Data Siswa')

@section('content')

<div x-data="{
    createModalOpen: false,
    selectedClassId: '{{ $classes->first()?->id ?? '' }}'
}">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Master Data Siswa</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Master Data & Registrasi Siswa</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200">
                    {{ $stats['total_students'] }} Siswa • {{ $stats['total_classes'] }} Kelas
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Pilih rombongan belajar kelas di bawah untuk melihat direktori nama-nama siswa, mendaftarkan siswa baru, atau mengatur plotting mata pelajaran.
            </p>
        </div>

        {{-- Button Tambah Siswa --}}
        <div>
            <button type="button"
                    @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Daftarkan Siswa Baru</span>
            </button>
        </div>
    </div>

    {{-- ══ Flash Alerts ══ --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-1">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.008v.008H12v-.008z"/>
                </svg>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ══ 2. KPI METRICS CARDS ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $stats['total_students'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Siswa</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rombel Kelas</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $stats['total_classes'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Kelas Aktif</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terplot Mapel</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $stats['assigned_students'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $stats['total_students'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Konsentrasi Keahlian</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $stats['total_majors'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Jurusan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ 3. FILTERS & SEARCH BAR ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
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
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            {{-- Filter Tingkat & Jurusan --}}
            <div class="flex items-center gap-3 flex-wrap">
                <select name="grade"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Tingkat Kelas --</option>
                    <option value="X" {{ $grade === 'X' ? 'selected' : '' }}>Tingkat X (Sepuluh)</option>
                    <option value="XI" {{ $grade === 'XI' ? 'selected' : '' }}>Tingkat XI (Sebelas)</option>
                    <option value="XII" {{ $grade === 'XII' ? 'selected' : '' }}>Tingkat XII (Dua Belas)</option>
                    <option value="XIII" {{ $grade === 'XIII' ? 'selected' : '' }}>Tingkat XIII (4 Tahun)</option>
                </select>

                <select name="major_id"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Jurusan --</option>
                    @foreach($majors as $m)
                        <option value="{{ $m->id }}" {{ (string)($majorId ?? '') === (string)$m->id ? 'selected' : '' }}>
                            {{ $m->code }} - {{ $m->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                    Filter
                </button>

                @if($search || ($grade && $grade !== 'all') || ($majorId && $majorId !== 'all'))
                    <a href="{{ route('admin.students.index') }}"
                       class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 4. GRID DIREKTORI KELAS ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($classesList as $cls)
            <div class="group relative rounded-3xl bg-white border border-slate-200/80 hover:border-indigo-300 transition-all duration-300 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md">
                
                {{-- Top Accent Line --}}
                <div class="h-1.5 w-full bg-gradient-to-r from-indigo-500 via-sky-500 to-indigo-600"></div>

                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    {{-- Badges: Grade & Section --}}
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wider">
                            Tingkat {{ $cls->grade }}
                        </span>

                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            Rombel {{ $cls->section }}
                        </span>
                    </div>

                    {{-- Class Full Name & Major Description --}}
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                            {{ $cls->full_name }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">
                            {{ $cls->major ? $cls->major->name : $cls->major_name }}
                        </p>
                    </div>

                    {{-- Stats Summary --}}
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Jumlah Siswa Terdaftar:</span>
                            <span class="font-bold text-slate-900 px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100">
                                {{ $cls->students_count }} Siswa
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Modul Pembelajaran:</span>
                            <span class="font-bold text-slate-700">
                                {{ $cls->modules_count }} Modul
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Action Button Footer --}}
                <div class="p-4 pt-0">
                    <a href="{{ route('admin.students.class', $cls->id) }}"
                       class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center gap-2 transition-all shadow-sm shadow-indigo-600/25 group-hover:shadow-md">
                        <span>Buka Data Siswa ({{ $cls->students_count }})</span>
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

    {{-- ══ 5. MODAL: DAFTARKAN SISWA BARU (GLOBAL) ══ --}}
    <div x-cloak 
         x-show="createModalOpen" 
         @keydown.escape.window="createModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

        {{-- Backdrop Blur --}}
        <div x-show="createModalOpen" 
             x-transition.opacity 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"
             @click="createModalOpen = false" 
             aria-hidden="true"></div>

        <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 lg:px-8 text-center">
            <div x-show="createModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 w-full max-w-md mx-auto transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all my-8 border border-slate-100">
                
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h3 class="text-lg font-black text-slate-900">Daftarkan Siswa Baru</h3>
                            <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Muhammad Farhan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- NISN / Identitas --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">NISN / Nomor Induk Siswa <span class="text-red-500">*</span></label>
                                <input type="text" name="identity_number" required placeholder="Contoh: 0076543210" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                            </div>

                            {{-- Rombel Kelas --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Rombongan Belajar (Kelas) <span class="text-red-500">*</span></label>
                                <select name="class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">-- Pilih Rombel Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Password --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Password Akun Siswa <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- Ploting Mata Pelajaran yang Ditempuh (Centang / Checkboxes) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Mata Pelajaran yang Ditempuh</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih mata pelajaran yang wajib / harus ditempuh oleh siswa ini:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-44 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @forelse($subjects as $s)
                                        <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200">
                                            <input type="checkbox" name="subject_ids[]" value="{{ $s->id }}" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $s->name }}</p>
                                                <p class="text-[10px] font-mono text-slate-500">{{ $s->code }}</p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="col-span-2 text-xs text-slate-400 italic p-2">Belum ada data mata pelajaran.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/25 transition-all">
                            Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
