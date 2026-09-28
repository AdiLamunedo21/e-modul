<!DOCTYPE html>
<html lang="id" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher Workspace — E-Modul SMKN 3 Yogyakarta')</title>
    <link rel="icon" href="{{ asset('lgsmk.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        
        /* Kunci html dan body agar tidak memicu scrollbar ganda di peramban */
        html, body {
            height: 100% !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }
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

        /* Scrollbar tunggal yang rapi dan halus untuk konten utama */
        main {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        main::-webkit-scrollbar {
            width: 6px;
        }
        main::-webkit-scrollbar-track {
            background: transparent;
        }
        main::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        main::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
    @stack('head')
</head>

{{--
    ATURAN STATE WORKSPACE GURU:
    - sidebarOpen: window.innerWidth >= 1024 (terbuka di desktop, tertutup di mobile)
    - Desktop: Push/Pull Flex layout
    - Mobile: Fixed Overlay di bawah sticky header (top-16)
--}}
<body
    class="h-full overflow-hidden bg-slate-100 antialiased text-slate-900"
    x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
>
    {{--
        BACKDROP — hanya di mobile (lg:hidden), mulai dari top-16 agar header tetap bisa diakses.
        Tidak ada @click handler agar hanya hamburger yang dapat menutup sidebar.
    --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity.duration.300ms
        class="fixed top-16 inset-x-0 bottom-0 z-30 bg-black/50 lg:hidden"
    ></div>

    {{-- ─── WRAPPER UTAMA: flex row setinggi layar (App Shell) ────────── --}}
    <div class="flex h-full w-full overflow-hidden">

        {{-- ─── SIDEBAR GURU ───────────────────────────────────────────── --}}
        @include('layouts.teacher.sidebar')

        {{-- ─── AREA KONTEN UTAMA ────────────────────────────────────────── --}}
        <div class="flex flex-col flex-1 min-w-0 h-full overflow-hidden transition-all duration-300 ease-in-out">

            {{-- Header --}}
            @include('layouts.teacher.header')

            {{-- Konten halaman (scrollable independen) --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="w-full max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- ══ FLOATING POP-UP IKLAN KHUSUS STATUS MODUL (MENGAMBANG 5 DETIK) ══ --}}
    @php
        $statusPopupMessage = null;
        $statusPopupIcon = '📢';
        $statusPopupBorder = 'border-emerald-500/80';
        $statusPopupShadow = 'shadow-[0_20px_50px_rgba(16,185,129,0.35)]';
        $statusPopupIconBg = 'from-emerald-500 to-teal-600 shadow-emerald-500/30';
        $statusPopupBar = 'from-emerald-400 via-teal-300 to-cyan-400';

        $flashSuccess = session('success');
        $flashInfo = session('info');
        $activeFlash = $flashSuccess ?? $flashInfo;

        if ($activeFlash) {
            if (str_contains($activeFlash, 'Modul berhasil dipublikasikan') || str_contains($activeFlash, 'dapat diakses siswa')) {
                $statusPopupMessage = $activeFlash;
                $statusPopupIcon = '📢';
                $statusPopupBorder = 'border-emerald-500/80';
                $statusPopupShadow = 'shadow-[0_20px_50px_rgba(16,185,129,0.35)]';
                $statusPopupIconBg = 'from-emerald-500 to-teal-600 shadow-emerald-500/30';
                $statusPopupBar = 'from-emerald-400 via-teal-300 to-cyan-400';
            } elseif (str_contains($activeFlash, 'Modul ditutup') || str_contains($activeFlash, 'tidak bisa diakses siswa')) {
                $statusPopupMessage = $activeFlash;
                $statusPopupIcon = '🔒';
                $statusPopupBorder = 'border-rose-500/80';
                $statusPopupShadow = 'shadow-[0_20px_50px_rgba(244,63,94,0.35)]';
                $statusPopupIconBg = 'from-rose-500 to-red-600 shadow-rose-500/30';
                $statusPopupBar = 'from-rose-400 via-red-300 to-amber-300';
            } elseif (str_contains($activeFlash, 'status Draft') || str_contains($activeFlash, 'dikembalikan ke status Draft')) {
                $statusPopupMessage = $activeFlash;
                $statusPopupIcon = '📝';
                $statusPopupBorder = 'border-amber-500/80';
                $statusPopupShadow = 'shadow-[0_20px_50px_rgba(245,158,11,0.35)]';
                $statusPopupIconBg = 'from-amber-500 to-amber-600 shadow-amber-500/30';
                $statusPopupBar = 'from-amber-400 via-amber-300 to-yellow-300';
            } elseif (str_contains($activeFlash, 'DIAKTIFKAN untuk pembelajaran')) {
                $statusPopupMessage = $activeFlash;
                $statusPopupIcon = '⚡';
                $statusPopupBorder = 'border-indigo-500/80';
                $statusPopupShadow = 'shadow-[0_20px_50px_rgba(99,102,241,0.35)]';
                $statusPopupIconBg = 'from-indigo-500 to-blue-600 shadow-indigo-500/30';
                $statusPopupBar = 'from-indigo-400 via-blue-300 to-teal-300';
            } elseif (str_contains($activeFlash, 'dinonaktifkan dari pembelajaran aktif')) {
                $statusPopupMessage = $activeFlash;
                $statusPopupIcon = '💤';
                $statusPopupBorder = 'border-slate-500/80';
                $statusPopupShadow = 'shadow-[0_20px_50px_rgba(100,116,139,0.35)]';
                $statusPopupIconBg = 'from-slate-600 to-slate-700 shadow-slate-500/30';
                $statusPopupBar = 'from-slate-400 via-slate-300 to-slate-200';
            }
        }
    @endphp

    <div x-data="{
            show: {{ $statusPopupMessage ? 'true' : 'false' }},
            message: '{{ addslashes($statusPopupMessage ?? '') }}',
            icon: '{{ $statusPopupIcon }}',
            border: '{{ $statusPopupBorder }}',
            shadow: '{{ $statusPopupShadow }}',
            iconBg: '{{ $statusPopupIconBg }}',
            bar: '{{ $statusPopupBar }}',
            progressWidth: 100,
            timer: null,
            init() {
                window.addEventListener('show-status-popup', (e) => {
                    this.trigger(e.detail);
                });
                if (this.show) {
                    this.startCountdown();
                }
            },
            trigger(detail) {
                if (!detail) return;
                const msg = typeof detail === 'string' ? detail : (detail.message || '');
                if (!msg) return;
                this.message = msg;
                if (msg.includes('dipublikasikan') || msg.includes('dapat diakses')) {
                    this.icon = '📢';
                    this.border = 'border-emerald-500/80';
                    this.shadow = 'shadow-[0_20px_50px_rgba(16,185,129,0.35)]';
                    this.iconBg = 'from-emerald-500 to-teal-600 shadow-emerald-500/30';
                    this.bar = 'from-emerald-400 via-teal-300 to-cyan-400';
                } else if (msg.includes('ditutup') || msg.includes('tidak bisa diakses')) {
                    this.icon = '🔒';
                    this.border = 'border-rose-500/80';
                    this.shadow = 'shadow-[0_20px_50px_rgba(244,63,94,0.35)]';
                    this.iconBg = 'from-rose-500 to-red-600 shadow-rose-500/30';
                    this.bar = 'from-rose-400 via-red-300 to-amber-300';
                } else if (msg.includes('Draft') || msg.includes('draft')) {
                    this.icon = '📝';
                    this.border = 'border-amber-500/80';
                    this.shadow = 'shadow-[0_20px_50px_rgba(245,158,11,0.35)]';
                    this.iconBg = 'from-amber-500 to-amber-600 shadow-amber-500/30';
                    this.bar = 'from-amber-400 via-amber-300 to-yellow-300';
                } else if (msg.includes('Library') || msg.includes('library')) {
                    this.icon = '🌐';
                    this.border = 'border-indigo-500/80';
                    this.shadow = 'shadow-[0_20px_50px_rgba(99,102,241,0.35)]';
                    this.iconBg = 'from-indigo-500 to-purple-600 shadow-indigo-500/30';
                    this.bar = 'from-indigo-400 via-purple-300 to-pink-300';
                } else if (msg.includes('dihapus')) {
                    this.icon = '🗑️';
                    this.border = 'border-rose-500/80';
                    this.shadow = 'shadow-[0_20px_50px_rgba(244,63,94,0.35)]';
                    this.iconBg = 'from-rose-500 to-red-600 shadow-rose-500/30';
                    this.bar = 'from-rose-400 via-red-300 to-amber-300';
                } else {
                    this.icon = detail.icon || '✨';
                    this.border = 'border-blue-500/80';
                    this.shadow = 'shadow-[0_20px_50px_rgba(59,130,246,0.35)]';
                    this.iconBg = 'from-blue-500 to-indigo-600 shadow-blue-500/30';
                    this.bar = 'from-blue-400 via-indigo-300 to-cyan-300';
                }
                this.show = true;
                this.startCountdown();
            },
            startCountdown() {
                if (this.timer) clearTimeout(this.timer);
                this.progressWidth = 100;
                this.$nextTick(() => {
                    setTimeout(() => { this.progressWidth = 0; }, 50);
                    this.timer = setTimeout(() => { this.show = false; }, 5000);
                });
            }
         }"
         x-on:show-status-popup.window="trigger($event.detail)"
         x-show="show"
         x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500"
         x-transition:enter-start="opacity-0 -translate-y-8 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-400"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-6 scale-95"
         x-cloak
         class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] max-w-lg w-[92%] sm:w-auto rounded-3xl bg-slate-900/95 text-white backdrop-blur-xl border-2 p-4 sm:p-5 overflow-hidden select-none"
         :class="[border, shadow]"
         role="alert">
        
        <div class="flex items-center gap-3.5">
            {{-- Icon Roket / Megaphone / Status Beranimasi --}}
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br text-white flex items-center justify-center text-2xl font-black shadow-lg shrink-0"
                 :class="iconBg"
                 x-text="icon">
            </div>

            {{-- Konten Pop Up (Hanya Memakai Judul Tebal Ringkas) --}}
            <div class="flex-1 pr-2 min-w-0">
                <h4 class="text-sm font-black text-white leading-snug" x-text="message">
                </h4>
            </div>

            {{-- Tombol Tutup Manual --}}
            <button type="button"
                    @click="show = false"
                    class="w-7 h-7 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center text-xs font-bold transition-all cursor-pointer shrink-0"
                    title="Tutup">
                ✕
            </button>
        </div>

        {{-- Progress Bar Countdown 5 Detik --}}
        <div class="w-full bg-slate-800/80 rounded-full h-1 overflow-hidden mt-3.5">
            <div class="bg-gradient-to-r h-full rounded-full transition-all duration-[5000ms] ease-linear"
                 :class="bar"
                 :style="'width: ' + progressWidth + '%'"></div>
        </div>
    </div>

    <script>
        window.showStatusPopup = function(detail) {
            window.dispatchEvent(new CustomEvent('show-status-popup', {
                detail: typeof detail === 'string' ? { message: detail } : detail
            }));
        };
    </script>

    @stack('scripts')
</body>
</html>
