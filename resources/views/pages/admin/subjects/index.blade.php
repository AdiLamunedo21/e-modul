@extends('layouts.admin.dashboardadmin')

@section('title', 'Master Mata Pelajaran — Admin E-Modul')
@section('page-title', 'Master Mata Pelajaran')

@section('content')

<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    deleteModalOpen: false,
    activeSubject: { id: null, name: '', code: '', icon: '📚', color: 'blue', description: '' },
    openEdit(subject) {
        this.activeSubject = JSON.parse(JSON.stringify(subject));
        this.editModalOpen = true;
    },
    openDelete(subject) {
        this.activeSubject = JSON.parse(JSON.stringify(subject));
        this.deleteModalOpen = true;
    }
}">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Master Mata Pelajaran</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Master Mata Pelajaran</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                    {{ $stats['total'] }} Mapel
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola katalog kurikulum mata pelajaran yang diajarkan pada seluruh kompetensi keahlian.
            </p>
        </div>

        {{-- Button Tambah Mapel --}}
        <div>
            <button type="button"
                    @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Mata Pelajaran</span>
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

    {{-- ══ 2. SEARCH BAR ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.subjects.index') }}" class="flex items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama atau kode mata pelajaran..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.subjects.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 3. TABEL MATA PELAJARAN ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Mata Pelajaran & Kode</th>
                        <th class="py-4 px-4">Deskripsi</th>
                        <th class="py-4 px-4 text-center">Guru Pengampu</th>
                        <th class="py-4 px-4 text-center">Total Modul</th>
                        <th class="py-4 px-6 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subjects as $subj)
                        @php
                            $subjJson = json_encode([
                                'id' => $subj->id,
                                'name' => $subj->name,
                                'code' => $subj->code,
                                'icon' => $subj->icon,
                                'color' => $subj->color,
                                'description' => $subj->description,
                            ]);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            {{-- Nama & Kode --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-extrabold border {{ $subj->badgeClasses() }}">
                                        {{ $subj->code }}
                                    </span>
                                    <div>
                                        <p class="font-bold text-slate-900 text-xs">{{ $subj->name }}</p>
                                        <p class="text-[10px] text-slate-400">Warna tema: <span class="capitalize font-semibold text-slate-600">{{ $subj->color }}</span></p>
                                    </div>
                                </div>
                            </td>

                            {{-- Deskripsi --}}
                            <td class="py-4 px-4 text-xs text-slate-500 max-w-xs">
                                <p class="line-clamp-2 leading-relaxed">{{ $subj->description ?: '-' }}</p>
                            </td>

                            {{-- Guru Pengampu --}}
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $subj->teachers_count }} Guru
                                </span>
                            </td>

                            {{-- Total Modul --}}
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $subj->modules_count }} Modul
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            @click="openEdit({{ $subjJson }})"
                                            class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Edit Mapel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </button>

                                    <button type="button"
                                            @click="openDelete({{ $subjJson }})"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus Mapel">
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
                                Tidak ada data mata pelajaran yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subjects->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>

    {{-- ══ 4. MODAL: TAMBAH MATA PELAJARAN ══ --}}
    <div x-cloak 
         x-show="createModalOpen" 
         @keydown.escape.window="createModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

        {{-- Backdrop Blur (Strictly covers only workspace area right of the sidebar) --}}
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
                
                <form action="{{ route('admin.subjects.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h3 class="text-lg font-black text-slate-900">Tambah Mata Pelajaran Baru</h3>
                            <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="Contoh: Pemrograman Berorientasi Objek" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Kode Singkatan <span class="text-red-500">*</span></label>
                                    <input type="text" name="code" required placeholder="Contoh: PBO" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 uppercase font-mono">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Warna Badge <span class="text-red-500">*</span></label>
                                    <select name="color" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="blue">Biru (Blue)</option>
                                        <option value="indigo">Ungu / Indigo</option>
                                        <option value="emerald">Hijau (Emerald)</option>
                                        <option value="amber">Kuning / Emas (Amber)</option>
                                        <option value="rose">Merah (Rose)</option>
                                        <option value="cyan">Cyan / Teal</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                                <textarea name="description" rows="3" placeholder="Uraian kompetensi atau materi dasar mapel..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/25 transition-all">
                            Simpan Mata Pelajaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ 5. MODAL: EDIT MATA PELAJARAN ══ --}}
    <div x-cloak 
         x-show="editModalOpen" 
         @keydown.escape.window="editModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

        {{-- Backdrop Blur (Strictly covers only workspace area right of the sidebar) --}}
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
                
                <form :action="'{{ url('/admin/subjects') }}/' + activeSubject.id" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h3 class="text-lg font-black text-slate-900">Edit Mata Pelajaran</h3>
                            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="activeSubject.name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Kode Singkatan <span class="text-red-500">*</span></label>
                                    <input type="text" name="code" x-model="activeSubject.code" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 uppercase font-mono">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Warna Badge <span class="text-red-500">*</span></label>
                                    <select name="color" x-model="activeSubject.color" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="blue">Biru (Blue)</option>
                                        <option value="indigo">Ungu / Indigo</option>
                                        <option value="emerald">Hijau (Emerald)</option>
                                        <option value="amber">Kuning / Emas (Amber)</option>
                                        <option value="rose">Merah (Rose)</option>
                                        <option value="cyan">Cyan / Teal</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                                <textarea name="description" x-model="activeSubject.description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
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

    {{-- ══ 6. MODAL: HAPUS MATA PELAJARAN ══ --}}
    <div x-cloak 
         x-show="deleteModalOpen" 
         @keydown.escape.window="deleteModalOpen = false" 
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">

        {{-- Backdrop Blur (Strictly covers only workspace area right of the sidebar) --}}
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
                
                <form :action="'{{ url('/admin/subjects') }}/' + activeSubject.id" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="bg-white p-6 sm:p-7 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>

                        <h3 class="text-base font-black text-slate-900 mb-2">Hapus Mata Pelajaran?</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus mata pelajaran <strong class="text-slate-800" x-text="activeSubject.name"></strong> (<span class="font-mono text-slate-700" x-text="activeSubject.code"></span>)?
                        </p>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-center gap-3">
                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batalkan
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-600/25 transition-all">
                            Ya, Hapus Mapel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
