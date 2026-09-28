{{--
    HEADER GURU (TEACHER WORKSPACE)
    ═══════════════════════════════
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
                class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors"
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
                    @yield('page-title', 'Workspace Guru')
                </span>
                <span class="hidden sm:inline-block text-xs text-blue-600 bg-blue-50 border border-blue-200/60 font-semibold px-2.5 py-0.5 rounded-full">
                    SMKN 3 Yogyakarta
                </span>
            </div>
        </div>

        {{-- Kanan: Profile Pill & Role Switcher --}}
        <div class="flex items-center gap-3">

            {{-- Switch to Admin Panel (jika NIP guru terdaftar sebagai admin) --}}
            @php
                $currentTeacher = Auth::guard('teacher')->user();
                $isAlsoAdmin = $currentTeacher ? \App\Models\Admin::where('identity_number', $currentTeacher->identity_number)->when(!empty($currentTeacher->email), fn($q) => $q->orWhere('email', $currentTeacher->email))->exists() : false;
            @endphp
            @if($isAlsoAdmin)
                <form action="{{ route('teacher.switch-to-admin') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition-all shadow-2xs cursor-pointer"
                            title="Beralih peran langsung ke Admin Panel Supervisi">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        <span class="hidden sm:inline">Admin Panel</span>
                        <span class="sm:hidden">Admin</span>
                    </button>
                </form>
            @endif

            {{-- Profile Pill Guru --}}
            <div class="flex items-center gap-2.5 rounded-2xl sm:rounded-full border border-gray-200 bg-gray-50 p-1.5 pr-3 shadow-2xs">
                <div class="h-8 w-8 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center ring-2 ring-blue-500/20 shrink-0">
                    {{ strtoupper(substr(Auth::guard('teacher')->user()->name ?? 'G', 0, 2)) }}
                </div>
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-xs font-bold text-gray-800 leading-tight">{{ Auth::guard('teacher')->user()->name ?? 'Guru Pengajar' }}</span>
                    <span class="text-[10px] text-gray-500 font-medium">{{ Auth::guard('teacher')->user()->email ?? Auth::guard('teacher')->user()->identity_number }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
