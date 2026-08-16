{{--
    SIDEBAR
    ═══════
    DESKTOP (lg+):
      - Ikut dalam flex-row flow → mendorong konten (push/pull)
      - Saat open  : w-72, posisi normal dalam flex
      - Saat closed: w-0 + overflow-hidden → konten otomatis melebar
      - TANPA fixed/overlay → tidak ada backdrop di desktop

    MOBILE (< lg):
      - Keluar dari flex-flow → fixed overlay di atas konten
      - Saat open  : translate-x-0  (slide in)
      - Saat closed: -translate-x-full (off-screen ke kiri)
--}}
<aside
    {{--
        Base: relative (desktop, ikut flex flow) | fixed (mobile, overlay di atas konten)
        Transisi width & transform berjalan bersamaan.
        TIDAK ada tombol tutup (✕) di dalam sidebar — sesuai spesifikasi.
    --}}
    class="
        {{-- MOBILE: fixed overlay, mulai dari BAWAH header (top-16 = 64px = h-16) --}}
        fixed top-16 left-0 bottom-0 z-40
        {{-- DESKTOP: kembali ke relative dalam flex-row, z-index normal --}}
        lg:static lg:z-auto lg:inset-auto

        flex flex-col shrink-0
        bg-slate-900
        overflow-hidden
        transition-all duration-300 ease-in-out

        {{-- DESKTOP width toggle: push/pull --}}
        lg:w-72

        {{-- MOBILE transform toggle: slide in/out --}}
        translate-x-0
    "
    :class="{
        {{-- ── DESKTOP ─────────────────────────────────────────────── --}}
        'lg:w-72':  sidebarOpen,
        'lg:w-0':  !sidebarOpen,

        {{-- ── MOBILE ──────────────────────────────────────────────── --}}
        'w-72 translate-x-0':   sidebarOpen,
        '-translate-x-full':   !sidebarOpen,
    }"
>
    {{-- Inner container dengan min-width agar konten tidak squash saat animasi --}}
    <div class="flex flex-col w-72 h-full">

        {{-- ══ Logo & Identitas Sekolah ══ --}}
        <div class="flex flex-col items-center px-6 pt-8 pb-6 border-b border-slate-700/50 shrink-0">
            <img src="{{ asset('LGskagata.png') }}" alt="Logo SMKN 3 Yogyakarta" class="h-14 w-auto mb-3">
            <h1 class="text-base font-extrabold text-white tracking-wide text-center leading-tight">
                SMKN 3 YOGYAKARTA
            </h1>
            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-indigo-500/15 border border-indigo-500/20 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-400">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                Admin Panel
            </span>
        </div>

        {{-- ══ Navigasi ══ --}}
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard.admin') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold bg-indigo-600 text-white shadow-lg">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard Utama
            </a>

            {{-- Grup: Administrasi --}}
            <p class="pt-6 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Administrasi</p>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                Master Data Pengguna
            </a>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/>
                </svg>
                Kelas & Jurusan
            </a>

            {{-- Grup: Kurikulum & Mutu --}}
            <p class="pt-6 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Kurikulum & Mutu</p>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
                Monitoring Guru
            </a>

            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Quality Control Modul
            </a>
        </nav>

        {{-- ══ Logout ══ --}}
        <div class="p-4 border-t border-slate-700/50 shrink-0">
            <form action="{{ route('logout.admin') }}" method="POST">
                @csrf
                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-red-400 bg-red-500/10 hover:bg-red-500 hover:text-white transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</aside>
