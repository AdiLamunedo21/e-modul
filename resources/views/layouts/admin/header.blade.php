{{--
    HEADER
    ══════
    Hamburger (☰) adalah SATU-SATUNYA pemicu buka/tutup sidebar.
    Berlaku di semua ukuran layar (mobile + desktop).
    Tidak ada mekanisme penutup lain.
--}}
<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">

        {{-- Kiri: Hamburger + Judul Halaman --}}
        <div class="flex items-center gap-3">

            {{-- ☰ Hamburger Toggle — satu-satunya pemicu sidebar, visible di SEMUA layar --}}
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors"
                aria-label="Toggle sidebar"
                :aria-expanded="sidebarOpen"
            >
                {{-- Ikon Hamburger (saat sidebar TERTUTUP) --}}
                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                {{-- Ikon Panah kiri (saat sidebar TERBUKA) — memberi sinyal visual "tutup" --}}
                <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/>
                </svg>
            </button>

            {{-- Judul halaman aktif --}}
            <span class="text-sm font-semibold text-gray-800 hidden sm:block">
                @yield('page-title', 'Dashboard')
            </span>
        </div>

        {{-- Kanan: Search + Profile --}}
        <div class="flex items-center gap-3">

            {{-- Search bar (desktop) --}}
            <div class="hidden md:block relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    class="w-64 rounded-lg border border-gray-200 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-700 placeholder-gray-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors"
                    placeholder="Pencarian cepat..."
                >
            </div>

            {{-- Profile pill --}}
            <div class="flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 p-1 pr-3">
                <img
                    class="h-8 w-8 rounded-full object-cover"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->name ?? 'Admin') }}&background=4f46e5&color=fff&bold=true&size=64"
                    alt="Avatar Admin"
                >
                <span class="hidden sm:inline text-sm font-semibold text-gray-700">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </div>
</header>
