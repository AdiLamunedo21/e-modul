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
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-800">
                    @yield('page-title', 'Portal Siswa')
                </span>
                <span class="hidden sm:inline-block text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 font-semibold px-2.5 py-0.5 rounded-full">
                    SMKN 3 Yogyakarta
                </span>
            </div>
        </div>

        {{-- Kanan: Search + Tambah Kelas + Profile + Logout --}}
        <div class="flex items-center gap-2.5 sm:gap-3">

            {{-- Search Bar (Desktop) --}}
            <div class="hidden md:block relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    class="w-52 lg:w-60 rounded-xl border border-gray-200 bg-gray-50 py-1.5 pl-9 pr-3 text-xs text-gray-700 placeholder-gray-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-all"
                    placeholder="Cari modul belajar..."
                >
            </div>

            {{-- Tombol Tambah Kelas Baru di Header --}}
            <button type="button"
                    @click="joinModalOpen = true"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-xs shadow-emerald-600/25 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="hidden sm:inline">Tambah Kelas</span>
            </button>

            {{-- Profile Pill Siswa --}}
            <div class="flex items-center gap-2.5 rounded-2xl sm:rounded-full border border-gray-200 bg-gray-50 p-1.5 pr-3 shadow-2xs shrink-0">
                <div class="h-8 w-8 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center ring-2 ring-emerald-500/20 shrink-0">
                    {{ strtoupper(substr(Auth::guard('student')->user()->name ?? 'S', 0, 2)) }}
                </div>
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-xs font-bold text-gray-800 leading-tight">{{ Auth::guard('student')->user()->name ?? 'Peserta Didik' }}</span>
                    <span class="text-[10px] text-emerald-600 font-semibold">
                        NISN: {{ Auth::guard('student')->user()->identity_number ?? '-' }}
                    </span>
                </div>
            </div>

            {{-- Logout Button --}}
            <form action="{{ route('logout.student') }}" method="POST" class="inline shrink-0">
                @csrf
                <button
                    type="submit"
                    title="Keluar dari Portal Siswa"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>
