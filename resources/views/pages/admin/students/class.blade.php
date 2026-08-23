@extends('layouts.admin.dashboardadmin')

@section('title', 'Daftar Siswa ' . $class->full_name . ' — Admin E-Modul')
@section('page-title', 'Detail Siswa Kelas')

@section('content')

<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    deleteModalOpen: false,
    activeStudent: { id: null, name: '', identity_number: '', class_id: {{ $class->id }}, subject_ids: [] },
    openEdit(student) {
        this.activeStudent = JSON.parse(JSON.stringify(student));
        this.editModalOpen = true;
    },
    openDelete(student) {
        this.activeStudent = JSON.parse(JSON.stringify(student));
        this.deleteModalOpen = true;
    }
}">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.students.index') }}" class="hover:text-indigo-600 transition-colors">Master Data Siswa</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">{{ $class->full_name }}</span>
            </nav>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.students.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    <span>Daftar Kelas</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span>{{ $class->full_name }}</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200">
                        {{ $classStats['total_students'] }} Siswa
                    </span>
                </h1>
            </div>
            <p class="mt-1 text-sm text-slate-500">
                Jurusan: <strong class="text-slate-700">{{ $class->major ? $class->major->name : $class->major_name }}</strong> • Tingkat {{ $class->grade }} • Rombel {{ $class->section }}
            </p>
        </div>

        {{-- Button Tambah Siswa ke Kelas Ini --}}
        <div>
            <button type="button"
                    @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Daftarkan Siswa ke Kelas Ini</span>
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

    {{-- ══ 2. KPI METRICS KELAS ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa di Kelas Ini</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['total_students'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Siswa</span>
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
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Terplot Mapel</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['assigned_students'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $classStats['total_students'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Pembelajaran</p>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black text-slate-900">{{ $classStats['total_modules'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Modul Terbit</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ 3. FILTERS & SEARCH ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.students.class', $class->id) }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
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
                       placeholder="Cari nama siswa atau NISN di kelas ini..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            {{-- Filter Mata Pelajaran --}}
            <div class="flex items-center gap-3 flex-wrap">
                <select name="subject_id"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Mata Pelajaran --</option>
                    @foreach($subjects as $sbj)
                        <option value="{{ $sbj->id }}" {{ (string)($subjectId ?? '') === (string)$sbj->id ? 'selected' : '' }}>
                            {{ $sbj->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                    Filter
                </button>

                @if($search || ($subjectId && $subjectId !== 'all'))
                    <a href="{{ route('admin.students.class', $class->id) }}"
                       class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 4. TABEL DAFTAR SISWA KELAS ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Identitas & Nama Siswa</th>
                        <th class="py-4 px-4">Mata Pelajaran Ditempuh</th>
                        <th class="py-4 px-4 text-center">Aktivitas Submisi</th>
                        <th class="py-4 px-4">Terdaftar</th>
                        <th class="py-4 px-6 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $st)
                        @php
                            $stSubjectIds = $st->subjects->pluck('id')->toArray();
                            $stDataJson = json_encode([
                                'id' => $st->id,
                                'name' => $st->name,
                                'identity_number' => $st->identity_number,
                                'class_id' => $st->class_id,
                                'subject_ids' => $stSubjectIds,
                            ]);
                            $totalSubmissions = $st->lkpd_submissions_count + $st->job_sheet_submissions_count + $st->student_results_count;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            {{-- Nama & NISN (Tanpa Rounded Profile Inisial) --}}
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-bold text-slate-900 text-xs">{{ $st->name }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">NISN: {{ $st->identity_number }}</p>
                                </div>
                            </td>

                            {{-- Mata Pelajaran yang Ditempuh --}}
                            <td class="py-4 px-4">
                                <div class="flex flex-wrap gap-1 max-w-[260px]">
                                    @forelse($st->subjects as $subj)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $subj->badgeClasses() }}">
                                            {{ $subj->code ?: $subj->name }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md font-medium border border-amber-200">
                                            Belum Diplot
                                        </span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Submisi --}}
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $totalSubmissions }} Submisi
                                    </span>
                                </div>
                            </td>

                            {{-- Terdaftar --}}
                            <td class="py-4 px-4 text-xs text-slate-500">
                                {{ $st->created_at ? $st->created_at->format('d M Y') : '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            @click="openEdit({{ $stDataJson }})"
                                            class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Edit Data Siswa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </button>

                                    <button type="button"
                                            @click="openDelete({{ $stDataJson }})"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus Siswa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                                Belum ada data siswa di kelas {{ $class->full_name }}. Klik tombol <strong>"Daftarkan Siswa ke Kelas Ini"</strong> untuk menambahkan peserta didik baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($students->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    {{-- ══ 5. MODAL: DAFTARKAN SISWA KE KELAS INI ══ --}}
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
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Daftarkan Siswa Baru</h3>
                                <p class="text-xs text-indigo-600 font-bold mt-0.5">{{ $class->full_name }}</p>
                            </div>
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

                            {{-- Rombel Kelas (Pre-selected) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Rombongan Belajar (Kelas) <span class="text-red-500">*</span></label>
                                <select name="class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $class->id === $c->id ? 'selected' : '' }}>
                                            {{ $c->full_name }}
                                        </option>
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

    {{-- ══ 6. MODAL: EDIT DATA SISWA ══ --}}
    <div x-cloak 
         x-show="editModalOpen" 
         @keydown.escape.window="editModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

        {{-- Backdrop Blur --}}
        <div x-show="editModalOpen" 
             x-transition.opacity 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"
             @click="editModalOpen = false" 
             aria-hidden="true"></div>

        <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 lg:px-8 text-center">
            <div x-show="editModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 w-full max-w-md mx-auto transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all my-8 border border-slate-100">
                
                <form :action="'{{ url('/admin/students') }}/' + activeStudent.id" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h3 class="text-lg font-black text-slate-900">Edit Data Siswa</h3>
                            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="activeStudent.name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- NISN / Identitas --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">NISN / Nomor Induk Siswa <span class="text-red-500">*</span></label>
                                <input type="text" name="identity_number" x-model="activeStudent.identity_number" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                            </div>

                            {{-- Rombel Kelas --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Rombongan Belajar (Kelas) <span class="text-red-500">*</span></label>
                                <select name="class_id" x-model="activeStudent.class_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Password (Opsional) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Ganti Password (Opsional)</label>
                                <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- Ploting Mata Pelajaran yang Ditempuh (Centang / Checkboxes) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Mata Pelajaran yang Ditempuh</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih mata pelajaran yang wajib / harus ditempuh oleh siswa ini:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-44 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @forelse($subjects as $s)
                                        <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200">
                                            <input type="checkbox"
                                                   name="subject_ids[]"
                                                   value="{{ $s->id }}"
                                                   :checked="activeStudent.subject_ids && activeStudent.subject_ids.includes({{ $s->id }})"
                                                   class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
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
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/25 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ 7. MODAL: HAPUS AKUN SISWA ══ --}}
    <div x-cloak 
         x-show="deleteModalOpen" 
         @keydown.escape.window="deleteModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

        {{-- Backdrop Blur --}}
        <div x-show="deleteModalOpen" 
             x-transition.opacity 
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"
             @click="deleteModalOpen = false" 
             aria-hidden="true"></div>

        <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 lg:px-8 text-center">
            <div x-show="deleteModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 w-full max-w-sm mx-auto transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all my-8 border border-slate-100">
                
                <form :action="'{{ url('/admin/students') }}/' + activeStudent.id" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="bg-white p-6 sm:p-7 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>

                        <h3 class="text-base font-black text-slate-900 mb-2">Hapus Akun Siswa?</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus akun siswa <strong class="text-slate-800" x-text="activeStudent.name"></strong> (NISN: <span class="font-mono text-slate-700" x-text="activeStudent.identity_number"></span>)?
                        </p>
                        <p class="text-[11px] text-red-500 mt-2 font-medium">
                            Perhatian: Seluruh data hasil belajar, nilai kuis, dan submisi tugas siswa ini akan ikut terhapus.
                        </p>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-center gap-3">
                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batalkan
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-600/25 transition-all">
                            Ya, Hapus Akun Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
