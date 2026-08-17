<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Workspace — E-Modul SMKN 3 Yogyakarta')</title>
    <link rel="icon" href="{{ asset('lgsmk.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, sans-serif; }
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
    class="bg-slate-100 antialiased text-slate-900"
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
    <div class="flex h-screen overflow-hidden">

        {{-- ─── SIDEBAR GURU ───────────────────────────────────────────── --}}
        @include('layouts.teacher.sidebar')

        {{-- ─── AREA KONTEN UTAMA ────────────────────────────────────────── --}}
        <div class="flex flex-col flex-1 min-w-0 h-full overflow-hidden transition-all duration-300 ease-in-out">

            {{-- Header --}}
            @include('layouts.teacher.header')

            {{-- Konten halaman (scrollable independen) --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
