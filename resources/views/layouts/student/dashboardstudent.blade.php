<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Portal — E-Modul SMKN 3 Yogyakarta')</title>
    <link rel="icon" href="{{ asset('lgsmk.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, sans-serif; }

        /* Sembunyikan batang scrollbar di sidebar di semua browser */
        aside, aside nav, .no-scrollbar {
            -ms-overflow-style: none !important; /* IE dan Edge */
            scrollbar-width: none !important;    /* Firefox */
        }
        aside::-webkit-scrollbar,
        aside nav::-webkit-scrollbar,
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;             /* Chrome, Safari, Opera */
            width: 0 !important;
            height: 0 !important;
        }
    </style>
    @stack('styles')
    @stack('head')
</head>

{{--
    ATURAN STATE PORTAL SISWA:
    - sidebarOpen: window.innerWidth >= 1024 (terbuka di desktop, tertutup di mobile)
    - Desktop: Push/Pull Flex layout
    - Mobile: Fixed Overlay di bawah sticky header (top-16)
--}}
<body
    class="bg-slate-100 antialiased text-slate-900"
    x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        joinModalOpen: {{ $errors->has('class_code') || request()->filled('join_code') ? 'true' : 'false' }},
        joinCodeInput: '{{ old('class_code', request('join_code', '')) }}',
        leaveClassModalOpen: false,
        leaveClassTarget: { id: null, name: '' }
    }"
>
    {{--
        BACKDROP — hanya di mobile (lg:hidden), mulai dari top-16 agar header tetap bisa diakses.
    --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity.duration.300ms
        @click="sidebarOpen = false"
        class="fixed top-16 inset-x-0 bottom-0 z-30 bg-black/50 lg:hidden cursor-pointer"
    ></div>

    {{-- ─── WRAPPER UTAMA: flex row setinggi layar (App Shell) ────────── --}}
    <div class="flex h-screen overflow-hidden">

        {{-- ─── SIDEBAR SISWA ───────────────────────────────────────────── --}}
        @include('layouts.student.sidebar')

        {{-- ─── AREA KONTEN UTAMA ────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-slate-100">

            {{-- Header --}}
            @include('layouts.student.header')

            {{-- FLASH MESSAGES --}}
            @if (session('success') || session('error'))
                <div class="px-4 sm:px-6 lg:px-8 pt-4 max-w-7xl mx-auto w-full">
                    @if (session('success'))
                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-black text-emerald-600">✓</span>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold text-xs p-1">✕</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-black text-rose-600">⚠️</span>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-800 font-bold text-xs p-1">✕</button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Konten halaman (scrollable independen) --}}
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto w-full">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- ════ Modal Tambah / Gabung Kelas Menggunakan Kode Kelas ════ --}}
    <div x-cloak
         x-show="joinModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             x-show="joinModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="joinModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-slate-200"
                 x-show="joinModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">
                
                {{-- Header Modal --}}
                <div class="px-6 pt-6 pb-4 flex items-center justify-between border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg font-black shrink-0">
                            🏫
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">
                                Tambah & Gabung Kelas
                            </h3>
                            <p class="text-xs text-slate-500">
                                Masukkan kode kelas dari guru Anda
                            </p>
                        </div>
                    </div>
                    <button @click="joinModalOpen = false" type="button" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-colors">
                        ✕
                    </button>
                </div>

                {{-- Form Input Kode Kelas --}}
                <form action="{{ route('student.join-class') }}" method="POST">
                    @csrf
                    <div class="px-6 py-5 space-y-4">
                        {{-- Daftar Kelas yang Sudah Diikuti --}}
                        @php
                            $enrolledClasses = Auth::guard('student')->user()?->classes ?? collect();
                        @endphp
                        @if($enrolledClasses->isNotEmpty())
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    Kelas yang Sudah Diikuti ({{ $enrolledClasses->count() }}):
                                </p>
                                <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto">
                                    @foreach($enrolledClasses as $enrolled)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-xs font-semibold text-slate-700 shadow-2xs">
                                            <span>{{ $enrolled->full_name }}</span>
                                            <span class="font-mono font-bold text-[11px] bg-white px-2 py-0.5 rounded-md border border-slate-200 text-emerald-700 shrink-0">
                                                {{ $enrolled->code }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kode Kelas Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="class_code"
                                   x-model="joinCodeInput"
                                   required
                                   autofocus
                                   placeholder="Contoh: KLS-7X89"
                                   class="w-full text-center px-4 py-3.5 text-lg font-mono font-black uppercase tracking-widest bg-slate-50 border @error('class_code') border-red-400 bg-red-50/30 @else border-slate-300 @enderror rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all outline-none">
                            
                            @error('class_code')
                                <p class="text-red-500 text-xs mt-1.5 font-medium text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-2.5">
                        <button @click="joinModalOpen = false" type="button" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl shadow-md shadow-emerald-600/25 hover:shadow-lg transition-all flex items-center gap-1.5">
                            <span>Gabung Kelas Sekarang</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════ Modal Konfirmasi Keluar dari Kelas ════ --}}
    <div x-cloak
         x-show="leaveClassModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             x-show="leaveClassModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="leaveClassModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-slate-200"
                 x-show="leaveClassModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">
                
                {{-- Header Modal --}}
                <div class="px-6 pt-6 pb-4 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-2xl font-black shrink-0">
                        ⚠️
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-extrabold text-slate-900">
                            Keluar dari Rombel Kelas
                        </h3>
                        <p class="text-xs text-slate-500">
                            Konfirmasi pelepasan status siswa dari kelas ini
                        </p>
                    </div>
                </div>

                {{-- Body Modal --}}
                <div class="px-6 py-2 space-y-3.5">
                    <p class="text-xs text-slate-700 leading-relaxed">
                        Apakah Anda yakin ingin keluar dari rombel <strong class="text-slate-900 font-extrabold" x-text="leaveClassTarget.name"></strong>?
                    </p>

                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-900 space-y-1.5 text-xs">
                        <div class="flex items-center gap-1.5 font-bold text-rose-700">
                            <span>⚠️</span>
                            <span>PERHATIAN RESIKO:</span>
                        </div>
                        <p class="leading-relaxed text-[11px] text-rose-800">
                            Seluruh progres belajar, tugas yang telah dikumpulkan (Pre-test, Video, LKPD, Job Sheet, Post-test), dan nilai Anda pada modul di kelas ini akan <strong>dihapus permanen dari akun Anda</strong>.
                        </p>
                        <p class="text-[10px] text-slate-500 pt-1 border-t border-rose-200/60">
                            ℹ️ Catatan: Rombel kelas dan materi modul buatan guru di database tetap aman dan tidak akan terhapus.
                        </p>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="px-6 py-4 mt-2 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-2.5">
                    <button @click="leaveClassModalOpen = false"
                            type="button"
                            class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors">
                        Batal
                    </button>
                    <form :action="'{{ url('/student/classes') }}/' + leaveClassTarget.id + '/leave'" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-500 rounded-xl shadow-md shadow-rose-600/25 hover:shadow-lg transition-all flex items-center gap-1.5">
                            <span>Ya, Keluar dari Kelas</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
