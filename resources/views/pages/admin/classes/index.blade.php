@extends('layouts.admin.dashboardadmin')

@section('title', 'Build Kelas & Manajemen Rombel — Admin E-Modul')
@section('page-title', 'Build Kelas')

@section('content')

<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    deleteModalOpen: false,
    activeClass: { id: null, grade: 'X', major_id: '', section: '1', name: '', code: '', teacher_ids: [] },
    openEdit(cls) {
        this.activeClass = JSON.parse(JSON.stringify(cls));
        if (!this.activeClass.teacher_ids) this.activeClass.teacher_ids = [];
        this.editModalOpen = true;
    },
    openDelete(cls) {
        this.activeClass = JSON.parse(JSON.stringify(cls));
        this.deleteModalOpen = true;
    }
}">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Build Kelas</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Pusat Build Kelas & Rombongan Belajar</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ $stats['total'] }} Rombel
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Bangun rombel kelas baru dengan generator kode unik kelas otomatis untuk pendaftaran siswa dan ploting guru pendidik.
            </p>
        </div>

        {{-- Button Build Kelas --}}
        <div>
            <button type="button"
                    @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Build Kelas Baru</span>
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

    {{-- ══ 2. FILTERS & SEARCH ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.classes.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
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
                       placeholder="Cari kode kelas, rombel, jurusan, atau guru..."
                       class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            {{-- Filter Tingkat & Jurusan --}}
            <div class="flex items-center gap-3 flex-wrap">
                <select name="grade"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Tingkat --</option>
                    <option value="X" {{ $grade === 'X' ? 'selected' : '' }}>Tingkat X</option>
                    <option value="XI" {{ $grade === 'XI' ? 'selected' : '' }}>Tingkat XI</option>
                    <option value="XII" {{ $grade === 'XII' ? 'selected' : '' }}>Tingkat XII</option>
                    <option value="XIII" {{ $grade === 'XIII' ? 'selected' : '' }}>Tingkat XIII</option>
                </select>

                <select name="major_id"
                        onchange="this.form.submit()"
                        class="text-xs rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    <option value="all">-- Semua Jurusan --</option>
                    @foreach($majors as $m)
                        <option value="{{ $m->id }}" {{ (string)$majorId === (string)$m->id ? 'selected' : '' }}>
                            {{ $m->code }} - {{ $m->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                    Filter
                </button>

                @if($search || ($grade && $grade !== 'all') || ($majorId && $majorId !== 'all'))
                    <a href="{{ route('admin.classes.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 3. TABEL BUILD KELAS ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Identitas Rombel</th>
                        <th class="py-4 px-4">Kode Kelas (Auto)</th>
                        <th class="py-4 px-4">Jurusan & Tingkat</th>
                        <th class="py-4 px-4">Guru Pengampu</th>
                        <th class="py-4 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-4 px-4 text-center">Modul Ajar</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classes as $c)
                        @php
                            $cTeacherIds = $c->teachers->pluck('id')->toArray();
                            $cJson = json_encode([
                                'id' => $c->id,
                                'grade' => $c->grade,
                                'major_id' => $c->major_id,
                                'section' => $c->section ?: '1',
                                'name' => $c->full_name,
                                'code' => $c->code,
                                'teacher_ids' => $cTeacherIds,
                            ]);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            {{-- Nama Rombel --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 font-black text-xs flex items-center justify-center border border-indigo-100 shrink-0">
                                        {{ $c->grade }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-xs">{{ $c->full_name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">Rombel {{ $c->section ?: '1' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kode Kelas (Auto-Generated) with Copy & Regenerate --}}
                            <td class="py-4 px-4">
                                <div class="inline-flex items-center gap-1.5 p-1.5 rounded-xl bg-slate-50 border border-slate-200" x-data="{ copied: false }">
                                    <span class="font-mono font-black text-xs text-slate-800 px-2 tracking-wider select-all">{{ $c->code }}</span>
                                    
                                    {{-- Tombol Salin Kode --}}
                                    <button type="button"
                                            @click="navigator.clipboard.writeText('{{ $c->code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                            :class="copied ? 'bg-emerald-600 text-white' : 'bg-white hover:bg-indigo-600 hover:text-white text-slate-600 border border-slate-200 shadow-2xs'"
                                            class="p-1.5 rounded-lg text-xs transition-all"
                                            title="Salin Kode Kelas">
                                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                        <svg x-show="copied" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </button>

                                    {{-- Tombol Regenerate Kode --}}
                                    <form action="{{ route('admin.classes.regenerate-code', $c) }}" method="POST" class="inline" onsubmit="return confirm('Acak ulang kode kelas untuk {{ $c->full_name }}?')">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-white transition-all" title="Acak Ulang Kode Kelas">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            {{-- Jurusan & Tingkat --}}
                            <td class="py-4 px-4">
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100 font-mono">
                                        {{ $c->major?->code ?? $c->major_name }}
                                    </span>
                                    <span class="text-xs text-slate-600 ml-1 font-medium">{{ $c->major?->name ?? '' }}</span>
                                </div>
                            </td>

                            {{-- Guru Pengampu --}}
                            <td class="py-4 px-4">
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @forelse($c->teachers as $tch)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $tch->name }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-amber-600 italic">Belum diplot</span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Jumlah Siswa --}}
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.students.index', ['class_id' => $c->id]) }}"
                                   class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 transition-colors">
                                    {{ $c->students_count }} Siswa &rarr;
                                </a>
                            </td>

                            {{-- Modul --}}
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $c->modules_count }} Modul
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            @click="openEdit({{ $cJson }})"
                                            class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Edit Rombel & Ploting Guru">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </button>

                                    <button type="button"
                                            @click="openDelete({{ $cJson }})"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus Kelas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                                Tidak ada data rombel kelas yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($classes->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $classes->links() }}
            </div>
        @endif
    </div>

    {{-- ══ 4. MODAL: BUILD KELAS BARU ══ --}}
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
                
                <form action="{{ route('admin.classes.store') }}" method="POST">
                    @csrf
                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Build Rombel Kelas Baru</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Kode unik kelas akan dibuat secara otomatis oleh sistem.</p>
                            </div>
                            <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tingkat Kelas <span class="text-red-500">*</span></label>
                                    <select name="grade" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="X">Tingkat X (Sepuluh)</option>
                                        <option value="XI">Tingkat XI (Sebelas)</option>
                                        <option value="XII">Tingkat XII (Dua Belas)</option>
                                        <option value="XIII">Tingkat XIII (Program 4 Tahun)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Nomor Rombel / Pararel <span class="text-red-500">*</span></label>
                                    <input type="text" name="section" required value="1" placeholder="Contoh: 1, 2, A, B" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Jurusan / Konsentrasi Keahlian <span class="text-red-500">*</span></label>
                                <select name="major_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach($majors as $m)
                                        <option value="{{ $m->id }}">{{ $m->code }} - {{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Ploting Guru Pengampu --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Guru Pengampu / Tanggung Jawab Kelas (Opsional)</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Pilih guru yang akan memiliki akses ke kelas ini:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-36 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @forelse($teachers as $t)
                                        <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200">
                                            <input type="checkbox" name="teacher_ids[]" value="{{ $t->id }}" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $t->name }}</p>
                                                <p class="text-[10px] font-mono text-slate-500">NIP: {{ $t->identity_number }}</p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="col-span-2 text-xs text-slate-400 italic p-2">Belum ada data guru.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Notice Kode Kelas --}}
                            <div class="p-3 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 flex items-center gap-2.5 text-indigo-900">
                                <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <span class="text-[11px] leading-tight">
                                    Kode kelas (6 digit kapital unik) akan dibuat otomatis saat tombol disimpan ditekan.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/25 transition-all">
                            Simpan & Generate Kode Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ 5. MODAL: EDIT KELAS ROMBEL ══ --}}
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
                
                <form :action="'{{ url('/admin/classes') }}/' + activeClass.id" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white p-6 sm:p-7">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">Edit Rombel & Guru Pengampu</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Kode Kelas: <strong class="font-mono text-indigo-600 font-bold" x-text="activeClass.code"></strong></p>
                            </div>
                            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                        </div>

                        <div class="space-y-4 text-xs">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Tingkat Kelas <span class="text-red-500">*</span></label>
                                    <select name="grade" x-model="activeClass.grade" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="X">Tingkat X (Sepuluh)</option>
                                        <option value="XI">Tingkat XI (Sebelas)</option>
                                        <option value="XII">Tingkat XII (Dua Belas)</option>
                                        <option value="XIII">Tingkat XIII (Program 4 Tahun)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Nomor Rombel / Pararel <span class="text-red-500">*</span></label>
                                    <input type="text" name="section" x-model="activeClass.section" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Jurusan / Konsentrasi Keahlian <span class="text-red-500">*</span></label>
                                <select name="major_id" x-model="activeClass.major_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    @foreach($majors as $m)
                                        <option value="{{ $m->id }}">{{ $m->code }} - {{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Ploting Guru Pengampu --}}
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Guru Pengampu / Tanggung Jawab Kelas</label>
                                <p class="text-[11px] text-slate-500 mb-1.5">Centang guru yang diplot untuk mengajar di kelas ini:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-36 overflow-y-auto p-2.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    @forelse($teachers as $t)
                                        <label class="flex items-center gap-2.5 text-slate-700 cursor-pointer p-2 rounded-xl hover:bg-white transition-all border border-transparent hover:border-slate-200">
                                            <input type="checkbox"
                                                   name="teacher_ids[]"
                                                   value="{{ $t->id }}"
                                                   :checked="activeClass.teacher_ids && activeClass.teacher_ids.includes({{ $t->id }})"
                                                   class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $t->name }}</p>
                                                <p class="text-[10px] font-mono text-slate-500">NIP: {{ $t->identity_number }}</p>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="col-span-2 text-xs text-slate-400 italic p-2">Belum ada data guru.</p>
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

    {{-- ══ 6. MODAL: HAPUS KELAS ROMBEL ══ --}}
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
                
                <form :action="'{{ url('/admin/classes') }}/' + activeClass.id" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="bg-white p-6 sm:p-7 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z"/>
                            </svg>
                        </div>

                        <h3 class="text-base font-black text-slate-900 mb-2">Hapus Rombel Kelas?</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus rombel <strong class="text-slate-800" x-text="activeClass.name"></strong>?
                        </p>
                        <p class="text-[11px] text-red-500 mt-2 font-medium">
                            Perhatian: Siswa dan modul ajar yang terdaftar di kelas ini akan terpengaruh.
                        </p>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-center gap-3">
                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                            Batalkan
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-600/25 transition-all">
                            Ya, Hapus Rombel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
