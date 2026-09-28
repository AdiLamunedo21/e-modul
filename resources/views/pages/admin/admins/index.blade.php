@extends('layouts.admin.dashboardadmin')

@section('title', 'Manajemen Akun Administrator — Admin E-Modul')
@section('page-title', 'Akun Administrator')

@section('content')

<div x-data="{
    createModalOpen: false,
    editModalOpen: false,
    deleteModalOpen: false,
    createMode: 'teacher', // 'teacher' or 'manual'
    activeAdmin: { id: null, name: '', email: '', identity_number: '' },
    openEdit(admin) {
        this.activeAdmin = JSON.parse(JSON.stringify(admin));
        this.editModalOpen = true;
    },
    openDelete(admin) {
        this.activeAdmin = JSON.parse(JSON.stringify(admin));
        this.deleteModalOpen = true;
    }
}">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Akun Administrator</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Manajemen Administrator</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ $stats['total'] }} Akun
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola akun administrator sistem dan integrasi peran ganda (Guru & Admin dengan NIP yang sama).
            </p>
        </div>

        {{-- Button Tambah Admin --}}
        <div>
            <button type="button"
                    @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Administrator Baru</span>
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

    @if(session('info'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm font-medium text-blue-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
            <span>{{ session('error') }}</span>
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

    {{-- ══ 2. STATS CARDS ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Administrator</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</h3>
                <p class="text-[11px] text-slate-400">Akun dengan akses supervisi sistem</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Merangkap Guru</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ $stats['dual_role'] }}</h3>
                <p class="text-[11px] text-slate-400">Memiliki akses ganda Admin + Guru</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Administrator Murni</p>
                <h3 class="text-2xl font-black text-slate-700">{{ $stats['pure_admin'] }}</h3>
                <p class="text-[11px] text-slate-400">Hanya bertugas sebagai admin</p>
            </div>
        </div>
    </div>

    {{-- ══ 3. PENCARIAN ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-4 sm:p-5 mb-6">
        <form method="GET" action="{{ route('admin.admins.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama atau NIP administrator..."
                       class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all outline-none">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-all">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.admins.index') }}"
                       class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══ 4. TABEL ADMINISTRATOR ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Identitas Administrator</th>
                        <th class="py-4 px-4">Status Integrasi Peran</th>
                        <th class="py-4 px-4">Terdaftar Sejak</th>
                        <th class="py-4 px-6 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($admins as $a)
                        @php
                            $isCurrentAdmin = ($a->id === Auth::guard('admin')->id());
                            $isAlsoTeacher = in_array($a->identity_number, $existingTeacherNips);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $isCurrentAdmin ? 'bg-indigo-50/20' : '' }}">
                            {{-- Nama & NIP --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-sm">
                                        {{ strtoupper(substr($a->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 text-xs sm:text-sm">
                                                {{ $a->name }}
                                            </span>
                                            @if($isCurrentAdmin)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                    Anda (Aktif)
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                            <span class="text-xs text-indigo-600 font-medium font-mono">{{ $a->email }}</span>
                                            <span class="text-slate-300">•</span>
                                            <span class="text-xs text-slate-400 font-mono">NIP: {{ $a->identity_number }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Status Integrasi Guru --}}
                            <td class="py-4 px-4">
                                @if($isAlsoTeacher)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Merangkap Guru Pendidik</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">Dapat login via portal Admin maupun Guru</p>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Administrator Murni</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Tanggal Registrasi --}}
                            <td class="py-4 px-4 text-xs text-slate-500">
                                {{ $a->created_at ? $a->created_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Tombol Jadikan Guru jika belum terdaftar --}}
                                    @if(!$isAlsoTeacher)
                                        <form action="{{ route('admin.admins.make-teacher', $a) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Daftarkan administrator {{ $a->name }} (NIP: {{ $a->identity_number }}) sebagai Guru pendidik? Akun guru akan dibuat dengan kata sandi yang sama.')">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded-xl transition-all border border-emerald-200"
                                                    title="Daftarkan juga sebagai Guru Pendidik">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>
                                                <span>Jadikan Guru</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Edit --}}
                                    <button type="button"
                                            @click="openEdit({{ json_encode(['id' => $a->id, 'name' => $a->name, 'identity_number' => $a->identity_number]) }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all cursor-pointer"
                                            title="Edit Data Administrator">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                        </svg>
                                        <span>Edit</span>
                                    </button>

                                    {{-- Tombol Hapus (Dilindungi jika akun sendiri atau admin terakhir) --}}
                                    @if(!$isCurrentAdmin && $stats['total'] > 1)
                                        <button type="button"
                                                @click="openDelete({{ json_encode(['id' => $a->id, 'name' => $a->name, 'identity_number' => $a->identity_number]) }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-all cursor-pointer border border-red-100"
                                                title="Hapus Administrator Cadangan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                    🛡️
                                </div>
                                <p class="font-bold text-slate-700">Tidak ada administrator yang cocok</p>
                                <p class="text-xs mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($admins->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $admins->links() }}
            </div>
        @endif
    </div>

    {{-- ══ 5. MODAL TAMBAH ADMINISTRATOR ══ --}}
    <div x-show="createModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in"
         @keydown.escape.window="createModalOpen = false">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-lg overflow-hidden transform transition-all"
             @click.outside="createModalOpen = false">
            
            {{-- Modal Header --}}
            <div class="bg-slate-900 px-6 py-5 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-lg font-bold">
                        🛡️
                    </div>
                    <div>
                        <h3 class="text-base font-bold">Tambah Administrator Baru</h3>
                        <p class="text-xs text-slate-400">Pilih dari guru aktif atau buat akun mandiri</p>
                    </div>
                </div>
                <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-white text-lg font-bold">✕</button>
            </div>

            {{-- Mode Switcher Tabs --}}
            <div class="p-2 bg-slate-100 flex gap-1 border-b border-slate-200">
                <button type="button"
                        @click="createMode = 'teacher'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all"
                        :class="createMode === 'teacher' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                    🎓 Pilih Dari Guru Terdaftar
                </button>
                <button type="button"
                        @click="createMode = 'manual'"
                        class="flex-1 py-2 text-xs font-bold rounded-xl transition-all"
                        :class="createMode === 'manual' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                    ✍️ Input Manual Baru
                </button>
            </div>

            {{-- Form Mode A: Dari Guru --}}
            <form x-show="createMode === 'teacher'" action="{{ route('admin.admins.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="source_type" value="teacher">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Pilih Guru Pendidik <span class="text-red-500">*</span>
                    </label>
                    @if($availableTeachers->isNotEmpty())
                        <select name="teacher_id" required class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Guru yang akan dijadikan Admin --</option>
                            @foreach($availableTeachers as $t)
                                <option value="{{ $t->id }}">
                                    {{ $t->name }} (NIP: {{ $t->identity_number }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            Akun admin akan otomatis disinkronkan dengan Nama & NIP guru tersebut.
                        </p>
                    @else
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-xs">
                            Seluruh guru yang terdaftar saat ini sudah memiliki hak akses Administrator. Anda dapat menggunakan opsi <strong>Input Manual</strong>.
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi Admin (Opsional)
                    </label>
                    <input type="password"
                           name="password"
                           placeholder="Kosongkan jika ingin sama dengan kata sandi guru"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">
                        Bila dikosongkan, kata sandi login admin akan sama persis dengan kata sandi guru saat ini.
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Batal
                    </button>
                    <button type="submit"
                            @if($availableTeachers->isEmpty()) disabled @endif
                            class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 rounded-xl shadow-sm transition-all cursor-pointer">
                        Jadikan Administrator
                    </button>
                </div>
            </form>

            {{-- Form Mode B: Input Manual --}}
            <form x-show="createMode === 'manual'" action="{{ route('admin.admins.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="source_type" value="manual">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap Administrator <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           required
                           placeholder="Contoh: Drs. Bambang Sutrisno, M.Kom"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email Administrator (Login) <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           required
                           placeholder="namaSingkat@gmail.com"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                    <p class="text-[11px] text-slate-500 mt-1">
                        Format: <strong>namaSingkat@gmail.com</strong> (digunakan untuk login ke panel admin).
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NIP / Nomor Identitas Pegawai <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="identity_number"
                           required
                           placeholder="Contoh: 198005122005011002"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                    <p class="text-[11px] text-slate-500 mt-1">
                        Bisa menggunakan NIP guru yang sudah ada. Jika sama, akun ini akan otomatis saling terhubung.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               name="password"
                               required
                               minlength="6"
                               placeholder="Minimal 6 karakter"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Konfirmasi Sandi <span class="text-red-500">*</span>
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               required
                               minlength="6"
                               placeholder="Ulangi kata sandi"
                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-all cursor-pointer">
                        Simpan Administrator
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ 6. MODAL EDIT ADMINISTRATOR ══ --}}
    <div x-show="editModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in"
         @keydown.escape.window="editModalOpen = false">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-md overflow-hidden"
             @click.outside="editModalOpen = false">
            
            <div class="bg-slate-900 px-6 py-5 text-white flex items-center justify-between">
                <h3 class="text-base font-bold">Edit Akun Administrator</h3>
                <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-white text-lg font-bold">✕</button>
            </div>

            <form :action="'{{ url('admin/admins') }}/' + activeAdmin.id" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap Administrator <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           x-model="activeAdmin.name"
                           required
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email Administrator (Login) <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           x-model="activeAdmin.email"
                           required
                           placeholder="namaSingkat@gmail.com"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                    <p class="text-[11px] text-slate-500 mt-1">
                        Format: <strong>namaSingkat@gmail.com</strong>
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NIP / Nomor Identitas Pegawai <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="identity_number"
                           x-model="activeAdmin.identity_number"
                           required
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi Baru (Opsional)
                    </label>
                    <input type="password"
                           name="password"
                           minlength="6"
                           placeholder="Kosongkan jika tidak ingin mengubah sandi"
                           class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Isi minimal 6 karakter hanya jika ingin mengganti sandi.</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-all cursor-pointer">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ 7. MODAL KONFIRMASI HAPUS ══ --}}
    <div x-show="deleteModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in"
         @keydown.escape.window="deleteModalOpen = false">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-sm overflow-hidden p-6 text-center"
             @click.outside="deleteModalOpen = false">
            
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                ⚠️
            </div>
            
            <h3 class="text-base font-bold text-slate-900 mb-1">Hapus Administrator Cadangan?</h3>
            <p class="text-xs text-slate-500 mb-4">
                Anda akan menghapus akun admin <strong x-text="activeAdmin.name" class="text-slate-800"></strong> (NIP: <span x-text="activeAdmin.identity_number" class="font-mono"></span>).
            </p>

            <div class="p-3 bg-amber-50 rounded-xl text-left border border-amber-200 text-amber-800 text-[11px] mb-4">
                ℹ️ <strong>Catatan:</strong> Jika NIP ini terdaftar sebagai Guru, akun guru dan materi ajarnya <u>TIDAK AKAN</u> terhapus. Hanya hak akses administrator yang dicabut.
            </div>

            <form :action="'{{ url('admin/admins') }}/' + activeAdmin.id" method="POST" class="flex items-center justify-center gap-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                    Batalkan
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition-all cursor-pointer">
                    Ya, Hapus Admin
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
