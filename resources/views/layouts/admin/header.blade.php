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

        {{-- Kanan: Role Switcher & Profile Pill --}}
        <div class="flex items-center gap-3">

            {{-- Switch to Teacher Portal (jika NIP admin terdaftar sebagai guru) --}}
            @php
                $currentAdmin = Auth::guard('admin')->user();
                $isAlsoTeacher = $currentAdmin ? \App\Models\Teacher::where('identity_number', $currentAdmin->identity_number)->when(!empty($currentAdmin->email), fn($q) => $q->orWhere('email', $currentAdmin->email))->exists() : false;
            @endphp
            @if($isAlsoTeacher)
                <form action="{{ route('admin.switch-to-teacher') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition-all shadow-2xs cursor-pointer"
                            title="Beralih peran langsung ke Workspace Guru">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                        <span class="hidden sm:inline">Workspace Guru</span>
                        <span class="sm:hidden">Guru</span>
                    </button>
                </form>
            @endif

            {{-- Profile pill (Link to Profile) --}}
            <a href="{{ route('admin.profile.edit') }}"
               class="flex items-center gap-2.5 rounded-2xl sm:rounded-full border border-gray-200 bg-gray-50 hover:bg-indigo-50/60 hover:border-indigo-200 p-1.5 pr-3 shadow-2xs transition-all group"
               title="Buka Pengaturan Profil & Keamanan">
                <div class="h-8 w-8 rounded-full bg-indigo-600 group-hover:bg-indigo-700 text-white font-bold text-xs flex items-center justify-center ring-2 ring-indigo-500/20 shrink-0 transition-colors">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-xs font-bold text-gray-800 group-hover:text-indigo-600 leading-tight transition-colors">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</span>
                    <span class="text-[10px] text-gray-500 font-medium">{{ Auth::guard('admin')->user()->email ?? Auth::guard('admin')->user()->identity_number }} (Admin)</span>
                </div>
            </a>

        </div>
    </div>
</header>
