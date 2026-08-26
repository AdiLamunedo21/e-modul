<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard — E-Modul SMKN 3 Yogyakarta')</title>
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
</head>

{{--
    ATURAN STATE:
    - sidebarOpen: true  → sidebar tampil (desktop: push layout | mobile: overlay)
    - sidebarOpen: false → sidebar tersembunyi (desktop: konten full-width | mobile: off-screen)
    - Default: true (desktop menampilkan sidebar sejak awal)
--}}
<body
    class="bg-gray-100 antialiased"
    x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
>
    {{--
        BACKDROP — hanya tampil di mobile (lg:hidden),
        hanya saat sidebar terbuka.
        PENTING: Tidak ada @click handler → klik backdrop tidak melakukan apa-apa.
        Sidebar HANYA bisa ditutup via tombol hamburger di header.
    --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity.duration.300ms
        class="fixed top-16 inset-x-0 bottom-0 z-30 bg-black/50 lg:hidden"
        {{-- Sengaja tidak ada @click — sesuai spesifikasi --}}
    ></div>

    {{-- ─── WRAPPER UTAMA: flex row setinggi layar (App Shell) ────────── --}}
    <div class="flex h-screen overflow-hidden">

        {{-- ─── SIDEBAR ─────────────────────────────────────────────────── --}}
        @include('layouts.admin.sidebar')

        {{--
            ─── AREA KONTEN UTAMA ──────────────────────────────────────────
            Di DESKTOP: flex-1 otomatis menyesuaikan lebar karena sidebar
            ikut dalam flow flex. Saat sidebar tutup, konten melebar sendiri.
            Di MOBILE: sidebar adalah fixed overlay, jadi area ini selalu full-width.
        --}}
        <div class="flex flex-col flex-1 min-w-0 h-full overflow-hidden transition-all duration-300 ease-in-out">

            {{-- Header --}}
            @include('layouts.admin.header')

            {{-- Konten halaman (scrollable independen) --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
