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
                @if(Auth::guard('student')->user()?->schoolClass)
                    <span class="hidden md:inline-block text-xs text-slate-600 bg-slate-100 border border-slate-200 font-bold px-2 py-0.5 rounded-md">
                        {{ Auth::guard('student')->user()->schoolClass->full_name }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Kanan: Status Siswa + Profile + Logout --}}
        <div class="flex items-center gap-3">

            {{-- Search Bar (Desktop) --}}
            <div class="hidden md:block relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    class="w-56 rounded-xl border border-gray-200 bg-gray-50 py-1.5 pl-9 pr-3 text-xs text-gray-700 placeholder-gray-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500 transition-all"
                    placeholder="Cari modul belajar..."
                >
            </div>

            {{-- Profile Pill Siswa --}}
            <div class="flex items-center gap-2.5 rounded-full border border-gray-200 bg-gray-50 p-1 pr-3">
                <img
                    class="h-8 w-8 rounded-full object-cover ring-2 ring-emerald-500/20"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('student')->user()->name ?? 'Siswa') }}&background=059669&color=fff&bold=true&size=64"
                    alt="Avatar Siswa"
                >
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-xs font-bold text-gray-800 leading-tight">{{ Auth::guard('student')->user()->name ?? 'Peserta Didik' }}</span>
                    <span class="text-[10px] text-gray-500 font-medium">NISN: {{ Auth::guard('student')->user()->identity_number ?? '-' }}</span>
                </div>
            </div>

            {{-- Logout Button --}}
            <form action="{{ route('logout.student') }}" method="POST" class="inline">
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
