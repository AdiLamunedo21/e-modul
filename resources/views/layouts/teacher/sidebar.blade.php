{{--
    SIDEBAR GURU (TEACHER WORKSPACE)
    ═════════════════════════════════
    DESKTOP (lg+): Push/pull layout (w-72 vs w-0)
    MOBILE (< lg): Fixed overlay starting from top-16
--}}
<aside
    class="
        {{-- MOBILE: fixed overlay mulai dari bawah header (top-16) --}}
        fixed top-16 left-0 bottom-0 z-40
        {{-- DESKTOP: kembali ke flex flow, tinggi penuh parent container --}}
        lg:static lg:h-full lg:z-auto lg:inset-auto

        flex flex-col shrink-0
        bg-slate-900
        overflow-hidden
        transition-all duration-300 ease-in-out
    "
    :class="{
        {{-- ── DESKTOP ─────────────────────────────────────────────── --}}
        'lg:w-72': sidebarOpen,
        'lg:w-0': !sidebarOpen,

        {{-- ── MOBILE ──────────────────────────────────────────────── --}}
        'w-72 translate-x-0': sidebarOpen,
        'w-72 -translate-x-full lg:translate-x-0': !sidebarOpen,
    }"
>
    {{-- Inner container fixed width --}}
    <div class="flex flex-col w-72 h-full">

        {{-- ══ Logo & Identitas Sekolah ══ --}}
        <div class="flex flex-col items-center px-6 pt-8 pb-6 border-b border-slate-700/50 shrink-0">
            <img src="{{ asset('LGskagata.png') }}" alt="Logo SMKN 3 Yogyakarta" class="h-14 w-auto mb-3 drop-shadow-md">
            <h1 class="text-base font-extrabold text-white tracking-wide text-center leading-tight">
                SMKN 3 YOGYAKARTA
            </h1>
            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-blue-500/15 border border-blue-500/25 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-blue-400">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                Teacher Workspace
            </span>
        </div>

        {{-- ══ Navigasi Menu Guru (Hidden Scrollbar for Clean Desktop View) ══ --}}
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">

            {{-- Workspace Utama --}}
            <a href="{{ route('teacher.dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ request()->routeIs('teacher.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Dashboard Workspace
            </a>

            {{-- Grup: E-Modul & Pembelajaran --}}
            <p class="pt-6 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Modul Pembelajaran</p>

            {{-- Manajer Modul --}}
            <a href="{{ route('teacher.modules.index') }}" class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition-colors group
                {{ request()->routeIs('teacher.modules.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('teacher.modules.*') ? 'text-blue-400' : 'group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span>Manajer Modul</span>
                </div>
            </a>

            {{-- E-Module Builder (Buat Modul Baru) --}}
            <a href="{{ route('teacher.modules.create') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-colors group
                {{ request()->routeIs('teacher.modules.create') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('teacher.modules.create') ? 'text-blue-400' : 'group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                <span>Buat Modul (Builder)</span>
            </a>

            {{-- Grup: Evaluasi & Penilaian --}}
            <p class="pt-6 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Evaluasi & Penilaian</p>

            {{-- Grading Center --}}
            <a href="{{ route('teacher.grading.index') }}" class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition-colors group
                {{ request()->routeIs('teacher.grading.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('teacher.grading.*') ? 'text-blue-400' : 'group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    <span>Grading Center</span>
                </div>
            </a>

            {{-- Rekap Laporan Nilai PDF --}}
            <a href="{{ route('teacher.reports.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-colors group
                {{ request()->routeIs('teacher.reports.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('teacher.reports.*') ? 'text-blue-400' : 'group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Laporan Nilai (PDF)</span>
            </a>

            {{-- Grup: Data Akademik --}}
            <p class="pt-6 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Kelas & Siswa</p>

            <a href="{{ route('teacher.classes.index') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-colors group
                {{ request()->routeIs('teacher.classes.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('teacher.classes.*') ? 'text-blue-400' : 'group-hover:text-blue-400' }} transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <span>Daftar Kelas Binaan</span>
            </a>
        </nav>

        {{-- ══ Logout Button ══ --}}
        <div class="p-4 border-t border-slate-700/50 shrink-0">
            <form action="{{ route('logout.teacher') }}" method="POST">
                @csrf
                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium text-red-400 bg-red-500/10 hover:bg-red-500 hover:text-white transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Keluar Sesi
                </button>
            </form>
        </div>
    </div>
</aside>
