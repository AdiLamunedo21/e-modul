{{--
    HEADER SISWA (STUDENT PORTAL)
    ═════════════════════════════
    Hamburger (☰) toggle di semua layar
    Sticky top-0 z-50 agar selalu di atas overlay sidebar
--}}
<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">

        {{-- Kiri: Hamburger + Judul Halaman --}}
        <div class="flex items-center gap-3">

            {{-- ☰ Hamburger Toggle — pemicu sidebar untuk semua ukuran layar --}}
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500 transition-colors"
                aria-label="Toggle sidebar"
                :aria-expanded="sidebarOpen"
            >
                {{-- Ikon Hamburger (saat sidebar TERTUTUP) --}}
                <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                {{-- Ikon Panah kiri (saat sidebar TERBUKA) --}}
                <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/>
                </svg>
            </button>

            {{-- Breadcrumb / Judul Halaman Aktif --}}
            <div class="flex flex-col text-left min-w-0 justify-center">
                <span class="text-[13px] sm:text-sm font-bold text-gray-800 leading-tight truncate">
                    @yield('page-title', 'Portal Siswa')
                </span>
                <span class="text-[10px] sm:text-[11px] text-emerald-600 font-semibold leading-tight truncate">
                    SMKN 3 Yogyakarta
                </span>
            </div>
        </div>

        {{-- Kanan: Profile --}}
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            {{-- Profile Pill Siswa --}}
            <div class="flex items-center gap-2 sm:gap-2.5 rounded-full border border-gray-200 bg-gray-50 p-1 sm:p-1.5 pr-2.5 sm:pr-3 shadow-2xs shrink-0 max-w-[165px] sm:max-w-none">
                <div class="h-7 w-7 sm:h-8 sm:w-8 rounded-full bg-emerald-600 text-white font-bold text-[11px] sm:text-xs flex items-center justify-center ring-2 ring-emerald-500/20 shrink-0">
                    {{ strtoupper(substr(Auth::guard('student')->user()->name ?? 'S', 0, 2)) }}
                </div>
                <div class="flex flex-col text-left min-w-0">
                    <span class="text-[11px] sm:text-xs font-bold text-gray-800 leading-tight truncate">{{ Auth::guard('student')->user()->name ?? 'Peserta Didik' }}</span>
                    <span class="text-[9px] sm:text-[10px] text-emerald-600 font-semibold truncate">{{ Auth::guard('student')->user()->identity_number ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
