@extends('layouts.admin.dashboardadmin')

@section('title', 'Master Data Guru — Admin E-Modul')
@section('page-title', 'Master Data Guru')

@section('content')

<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    deleteModalOpen: false,
    activeTeacher: { id: null, name: '', identity_number: '', subjects: [], class_ids: [], subject_ids: [] },
    openEdit(teacher) {
        this.activeTeacher = JSON.parse(JSON.stringify(teacher));
        if (!this.activeTeacher.class_ids) this.activeTeacher.class_ids = [];
        if (!this.activeTeacher.subject_ids) this.activeTeacher.subject_ids = [];
        this.editModalOpen = true;
    },
    openDelete(teacher) {
        this.activeTeacher = JSON.parse(JSON.stringify(teacher));
        this.deleteModalOpen = true;
    }
}">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Master Data Guru</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Master Data & Registrasi Guru</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ $stats['total'] }} Guru
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola akun guru pendidik, ploting mata pelajaran pengampu, serta penugasan kelas didik.
            </p>
        </div>

        {{-- Button Tambah Guru --}}
        <div>
            <button type="button"
                    @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Daftarkan Guru Baru</span>
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
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

    {{-- ══ 2. STATS PILLS & FILTERS ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.teachers.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
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
                       placeholder="Cari nama atau NIP guru..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            {{-- Filter Mapel --}}
            <div class="flex items-center gap-3 flex-wrap">
                <select name="subject_id"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Mata Pelajaran --</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ (string)$subjectId === (string)$subj->id ? 'selected' : '' }}>
                            {{ $subj->name }} ({{ $subj->code }})
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                    Filter
                </button>

                @if($search || ($subjectId && $subjectId !== 'all'))
                    <a href="{{ route('admin.teachers.index') }}"
                       class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 3. TABEL MASTER DATA GURU ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Identitas & Nama Guru</th>
                        <th class="py-4 px-4">Mata Pelajaran Diampu</th>
                        <th class="py-4 px-4">Kelas Didik (Tanggung Jawab)</th>
                        <th class="py-4 px-4 text-center">Modul Ajar</th>
                        <th class="py-4 px-6 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($teachers as $t)
                        @php
                            $tSubjectsIds = $t->subjects->pluck('id')->toArray();
                            $tClassIds = $t->classes->pluck('id')->toArray();
                            $tDataJson = json_encode([
                                'id' => $t->id,
                                'name' => $t->name,
                                'identity_number' => $t->identity_number,
                                'subject_ids' => $tSubjectsIds,
                                'class_ids' => $tClassIds,
                            ]);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            {{-- Nama & NIP --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-black text-xs flex items-center justify-center border border-indigo-100 shrink-0">
                                        {{ strtoupper(substr($t->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.teachers.show', $t) }}" class="font-bold text-slate-900 hover:text-indigo-600 text-xs transition-colors">
                                            {{ $t->name }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 font-mono">NIP: {{ $t->identity_number }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Mata Pelajaran --}}
                            <td class="py-4 px-4">
                                <div class="flex flex-wrap gap-1 max-w-[220px]">
                                    @forelse($t->subjects as $subj)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $subj->name }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 italic">Belum diplot</span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Kelas Didik --}}
                            <td class="py-4 px-4">
                                <div class="flex flex-wrap gap-1 max-w-[240px]">
                                    @forelse($t->classes as $cls)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $cls->short_name }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-amber-600 italic font-medium">Belum dipilihkan</span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Modul Ajar --}}
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.teachers.show', $t) }}" class="inline-flex items-center gap-1.5 hover:scale-105 transition-transform" title="Klik untuk lihat daftar modul">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $t->published_modules_count }} Terbit
                                    </span>
                                    @if($t->modules_count > $t->published_modules_count)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                            {{ $t->modules_count - $t->published_modules_count }} Draf
                                        </span>
                                    @endif
                                </a>
                            </td>

                            {{-- Aksi Manajemen --}}
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Tombol Detail Guru (Buka Halaman Baru) --}}
                                    <a href="{{ route('admin.teachers.show', $t) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-indigo-600 hover:text-white rounded-xl transition-all shadow-2xs"
                                       title="Lihat Halaman Detail Profil & Daftar Modul Guru">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    {{-- Tombol Edit Guru --}}
                                    <button type="button"
                                            @click="openEdit({{ $tDataJson }})"
                                            class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Edit Data & Kelas Didik">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </button>

                                    {{-- Tombol Hapus Guru --}}
                                    <button type="button"
                                            @click="openDelete({{ $tDataJson }})"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus Guru">
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
                                Tidak ada data guru yang cocok dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($teachers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>

    {{-- ══ 4. MODAL: DAFTARKAN GURU BARU ══ --}}
    <div x-cloak 
         x-show="createModalOpen" 
         @keydown.escape.window="createModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

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
                 class="relative z-10 w-full max-w-lg mx-auto transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all my-8 border border-slate-100">
                
                <form action="{{ route('admin.teachers.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h3 class="text-lg font-black text-slate-900">Daftarkan Guru Baru</h3>
                            <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Budi Santoso, S.Kom." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- NIP / Identitas --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">NIP / NUPTK / No. Identitas <span class="text-red-500">*</span></label>
                                <input type="text" name="identity_number" required placeholder="Contoh: 198501152010011002" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                            </div>

                            {{-- Password --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Password Akun <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- Ploting Mata Pelajaran --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Mata Pelajaran yang Diampu</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih mata pelajaran pengampu guru:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-36 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
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

                            {{-- Penugasan Kelas Didik (Admin Memilihkan Kelas) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kelas Didik (Tanggung Jawab Mengajar)</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih rombel kelas yang akan muncul di dashboard guru ini:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @forelse($classes as $cls)
                                        <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200">
                                            <input type="checkbox" name="class_ids[]" value="{{ $cls->id }}" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $cls->full_name }}</p>
                                                <p class="text-[10px] text-slate-500">{{ $cls->major?->name ?? $cls->major_name }}</p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="col-span-2 text-xs text-slate-400 italic p-2">Belum ada data kelas.</p>
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
                            Simpan Data Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ 5. MODAL: EDIT DATA GURU & KELAS DIDIK ══ --}}
    <div x-cloak 
         x-show="editModalOpen" 
         @keydown.escape.window="editModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

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
                 class="relative z-10 w-full max-w-lg mx-auto transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all my-8 border border-slate-100">
                
                <form :action="'{{ url('/admin/teachers') }}/' + activeTeacher.id" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h3 class="text-lg font-black text-slate-900">Edit Guru & Kelas Didik</h3>
                            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="activeTeacher.name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- NIP / Identitas --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">NIP / NUPTK / No. Identitas <span class="text-red-500">*</span></label>
                                <input type="text" name="identity_number" x-model="activeTeacher.identity_number" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                            </div>

                            {{-- Password (Opsional) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Ganti Password (Opsional)</label>
                                <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            {{-- Ploting Mata Pelajaran --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Mata Pelajaran yang Diampu</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih mata pelajaran pengampu guru:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-36 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @forelse($subjects as $s)
                                        <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200">
                                            <input type="checkbox"
                                                   name="subject_ids[]"
                                                   value="{{ $s->id }}"
                                                   :checked="activeTeacher.subject_ids && activeTeacher.subject_ids.includes({{ $s->id }})"
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

                            {{-- Penugasan Kelas Didik (Admin Memilihkan Kelas) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kelas Didik (Tanggung Jawab Mengajar)</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih rombel kelas yang akan menjadi tanggung jawab guru ini:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @forelse($classes as $cls)
                                        <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200">
                                            <input type="checkbox"
                                                   name="class_ids[]"
                                                   value="{{ $cls->id }}"
                                                   :checked="activeTeacher.class_ids && activeTeacher.class_ids.includes({{ $cls->id }})"
                                                   class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $cls->full_name }}</p>
                                                <p class="text-[10px] text-slate-500">{{ $cls->major?->name ?? $cls->major_name }}</p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="col-span-2 text-xs text-slate-400 italic p-2">Belum ada data kelas.</p>
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

    {{-- ══ 6. MODAL: HAPUS AKUN GURU ══ --}}
    <div x-cloak 
         x-show="deleteModalOpen" 
         @keydown.escape.window="deleteModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

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
                
                <form :action="'{{ url('/admin/teachers') }}/' + activeTeacher.id" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="bg-white p-6 sm:p-7 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z"/>
                            </svg>
                        </div>

                        <h3 class="text-base font-black text-slate-900 mb-2">Hapus Akun Guru?</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus akun guru <strong class="text-slate-800" x-text="activeTeacher.name"></strong> (NIP: <span class="font-mono text-slate-700" x-text="activeTeacher.identity_number"></span>)?
                        </p>
                        <p class="text-[11px] text-red-500 mt-2 font-medium">
                            Perhatian: Modul ajar yang dibuat oleh guru ini akan ikut terhapus dari sistem.
                        </p>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-center gap-3">
                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batalkan
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-600/25 transition-all">
                            Ya, Hapus Akun Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
