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
        {{-- DESKTOP: kembali ke relative dalam flex-row, tinggi penuh parent container --}}
        lg:static lg:h-full lg:z-auto lg:inset-auto

        flex flex-col shrink-0
        bg-slate-900
        overflow-hidden
        transition-all duration-300 ease-in-out
    "
    :class="{
        {{-- ── DESKTOP ─────────────────────────────────────────────── --}}
        'lg:w-72':  sidebarOpen,
        'lg:w-0':  !sidebarOpen,

        {{-- ── MOBILE ──────────────────────────────────────────────── --}}
        'w-72 translate-x-0':  sidebarOpen,
        'w-72 -translate-x-full lg:translate-x-0':  !sidebarOpen,
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
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

            {{-- Dashboard --}}
            @php
                $isDashboardActive = request()->routeIs('admin.dashboard') || request()->routeIs('dashboard.admin');
                $isTeachersActive  = request()->routeIs('admin.teachers.*');
                $isStudentsActive  = request()->routeIs('admin.students.*');
                $isSubjectsActive  = request()->routeIs('admin.subjects.*');
                $isMajorsActive    = request()->routeIs('admin.majors.*');
                $isClassesActive   = request()->routeIs('admin.classes.*');
                $isLibraryActive   = request()->routeIs('admin.library.*');
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isDashboardActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                <span>Dashboard Supervisi</span>
            </a>

            {{-- Grup: Master Data Pengguna --}}
            <p class="pt-5 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Master Pengguna</p>

            {{-- Master Guru --}}
            <a href="{{ route('admin.teachers.index') }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isTeachersActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3 truncate">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span>Master Data Guru</span>
                </div>
            </a>

            {{-- Master Siswa --}}
            <a href="{{ route('admin.students.index') }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isStudentsActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3 truncate">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                    </svg>
                    <span>Master Data Siswa</span>
                </div>
            </a>

            {{-- Grup: Kurikulum & Akademik --}}
            <p class="pt-5 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Kurikulum & Akademik</p>

            {{-- Master Mata Pelajaran --}}
            <a href="{{ route('admin.subjects.index') }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isSubjectsActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3 truncate">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span>Mata Pelajaran</span>
                </div>
            </a>

            {{-- Master Jurusan --}}
            <a href="{{ route('admin.majors.index') }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isMajorsActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3 truncate">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/>
                    </svg>
                    <span>Jurusan & Keahlian</span>
                </div>
            </a>

            {{-- Master Kelas & Rombel --}}
            <a href="{{ route('admin.classes.index') }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isClassesActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3 truncate">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5"/>
                    </svg>
                    <span>Build Kelas</span>
                </div>
            </a>

            {{-- Supervisi Library Modul --}}
            <a href="{{ route('admin.library.index') }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isLibraryActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3 truncate">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                    <span>Library Modul</span>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                    Publik
                </span>
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
