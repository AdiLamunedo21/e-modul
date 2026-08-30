@extends('layouts.admin.dashboardadmin')

@section('title', 'Detail Profil & Modul ' . $teacher->name . ' — Admin E-Modul')
@section('page-title', 'Detail Guru & Modul')

@section('content')

<div x-data="{
    editModalOpen: false,
    activeTeacher: {
        id: {{ $teacher->id }},
        name: '{{ addslashes($teacher->name) }}',
        identity_number: '{{ $teacher->identity_number }}',
        subject_ids: {{ json_encode($teacher->subjects->pluck('id')->toArray()) }},
        class_ids: {{ json_encode($teacher->classes->pluck('id')->toArray()) }}
    }
}">

    {{-- ══ 1. BREADCRUMB & BACK BUTTON ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.teachers.index') }}" class="hover:text-indigo-600 transition-colors">Master Data Guru</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold truncate">{{ $teacher->name }}</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Profil Guru & Direktori Modul</span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Supervisi modul ajar yang dirakit oleh guru, kelengkapan komponen pedagogik, dan kelas didik binaan.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.teachers.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Master Guru</span>
            </a>

            <button type="button"
                    @click="editModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                </svg>
                <span>Edit Guru & Kelas Didik</span>
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

    {{-- ══ 2. HERO PROFILE CARD GURU ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7 mb-8 overflow-hidden relative">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            
            {{-- Left: Identitas Guru --}}
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl bg-indigo-600 text-white font-black text-xl sm:text-2xl flex items-center justify-center shadow-lg shadow-indigo-600/30 shrink-0">
                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">
                            {{ $teacher->name }}
                        </h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Guru Pendidik
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-1 flex items-center gap-2">
                        <span>NIP / NUPTK:</span>
                        <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">{{ $teacher->identity_number }}</span>
                    </p>

                    {{-- Meta: Mapel & Kelas Didik --}}
                    <div class="flex flex-wrap gap-4 mt-3 pt-3 border-t border-slate-100 text-xs">
                        {{-- Mapel --}}
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 block mb-1">Mata Pelajaran:</span>
                            <div class="flex flex-wrap gap-1">
                                @forelse($teacher->subjects as $subj)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $subj->name }} ({{ $subj->code }})
                                    </span>
                                @empty
                                    <span class="text-[11px] text-slate-400 italic">Belum diplot</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- Kelas Didik --}}
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 block mb-1">Kelas Didik:</span>
                            <div class="flex flex-wrap gap-1">
                                @forelse($teacher->classes as $cls)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $cls->full_name }}
                                    </span>
                                @empty
                                    <span class="text-[11px] text-amber-600 italic font-medium">Belum dipilihkan</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: KPI Stats Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4 gap-3 shrink-0 border-t lg:border-t-0 lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-6">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Modul</span>
                    <span class="text-xl font-black text-slate-900 mt-1 block">{{ $stats['total_modules'] }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-center">
                    <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Terbit</span>
                    <span class="text-xl font-black text-emerald-700 mt-1 block">{{ $stats['published_modules'] }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-100 text-center">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Draf</span>
                    <span class="text-xl font-black text-amber-700 mt-1 block">{{ $stats['draft_modules'] }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-center">
                    <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider block">Library</span>
                    <span class="text-xl font-black text-indigo-700 mt-1 block">{{ $stats['shared_modules'] }}</span>
                </div>
            </div>

        </div>
    </div>

    {{-- ══ 3. FILTERS & SEARCH FOR MODULES ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.teachers.show', $teacher) }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
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
                       placeholder="Cari judul modul, mapel, atau kelas..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            {{-- Filter Status & Mapel --}}
            <div class="flex items-center gap-3 flex-wrap">
                <select name="status"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Status Modul --</option>
                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>✓ Terbit (Published)</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draf (Draft)</option>
                    <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>Ditutup (Closed)</option>
                </select>

                <select name="subject_id"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Mapel --</option>
                    @foreach($teacherSubjects as $ts)
                        <option value="{{ $ts->id }}" {{ (string)$subjectId === (string)$ts->id ? 'selected' : '' }}>
                            {{ $ts->name }} ({{ $ts->code }})
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                    Filter
                </button>

                @if($search || ($status && $status !== 'all') || ($subjectId && $subjectId !== 'all'))
                    <a href="{{ route('admin.teachers.show', $teacher) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 4. DAFTAR MODUL AJAR (GRID CARDS) ══ --}}
    <div class="mb-8">
        @if($modules->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($modules as $mod)
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 hover:border-indigo-300 hover:shadow-md transition-all flex flex-col justify-between group">
                        
                        <div>
                            {{-- Top Header Card --}}
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    {{-- Status Badge --}}
                                    @if($mod->status === 'published')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✓ Terbit
                                        </span>
                                    @elseif($mod->status === 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                            Draf
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                            Ditutup
                                        </span>
                                    @endif

                                    {{-- Shared to Library Badge --}}
                                    @if($mod->is_shared)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200" title="Dibagikan ke Library Modul Sekolah">
                                            🌐 Library
                                        </span>
                                    @endif
                                </div>

                                <span class="text-[10px] text-slate-400 font-mono">
                                    {{ $mod->updated_at ? $mod->updated_at->format('d M Y') : '-' }}
                                </span>
                            </div>

                            {{-- Judul Modul --}}
                            <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2 leading-snug">
                                {{ $mod->title }}
                            </h3>

                            {{-- Meta: Mapel & Rombel Kelas --}}
                            <div class="flex items-center gap-2 text-xs text-slate-500 mb-4 flex-wrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $mod->subject?->name ?? '-' }}
                                </span>
                                <span class="text-slate-300">•</span>
                                <span class="font-bold text-indigo-600">
                                    {{ $mod->schoolClass?->full_name ?? ($mod->schoolClass ? ($mod->schoolClass->grade . ' ' . $mod->schoolClass->major_name) : 'Semua Kelas') }}
                                </span>
                            </div>

                            {{-- Active Pedagogical Components Checklist --}}
                            <div class="pt-3 border-t border-slate-100 mb-4">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Komponen Aktif Modul:</p>
                                <div class="flex flex-wrap gap-1">
                                    @if($mod->has_materi)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            📄 Materi & PPT
                                        </span>
                                    @endif
                                    @if($mod->has_video)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                            🎬 Multi-Video
                                        </span>
                                    @endif
                                    @if($mod->has_pre_test)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                            📝 Pre-Test
                                        </span>
                                    @endif
                                    @if($mod->has_post_test)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            🎯 Post-Test
                                        </span>
                                    @endif
                                    @if($mod->has_lkpd)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                            📋 LKPD
                                        </span>
                                    @endif
                                    @if($mod->has_job_sheet)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                            🛠️ Job Sheet
                                        </span>
                                    @endif
                                    @if($mod->has_embed)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-sky-50 text-sky-700 border border-sky-100">
                                            💻 Embed
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Footer Card: Info Guru --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                            <span class="text-[11px] font-medium">Oleh: <strong class="text-slate-700">{{ $teacher->name }}</strong></span>
                            <span class="text-[10px] font-mono">ID: #{{ $mod->id }}</span>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($modules->hasPages())
                <div class="mt-6">
                    {{ $modules->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-3xl bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 mb-1">Tidak Ada Modul Ditemukan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    Guru ini belum memiliki modul ajar yang sesuai dengan kriteria pencarian atau filter yang Anda tentukan.
                </p>
                @if($search || ($status && $status !== 'all') || ($subjectId && $subjectId !== 'all'))
                    <a href="{{ route('admin.teachers.show', $teacher) }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                        Reset Filter
                    </a>
                @endif
            </div>
        @endif
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
                                    @foreach($allSubjects as $s)
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
                                    @endforeach
                                </div>
                            </div>

                            {{-- Penugasan Kelas Didik (Admin Memilihkan Kelas) --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Kelas Didik (Tanggung Jawab Mengajar)</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih rombel kelas yang akan menjadi tanggung jawab guru ini:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @foreach($allClasses as $cls)
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
                                    @endforeach
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

</div>

@endsection
