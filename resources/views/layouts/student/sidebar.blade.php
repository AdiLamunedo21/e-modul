{{--
    SIDEBAR SISWA (STUDENT PORTAL)
    ══════════════════════════════
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
            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/25 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-emerald-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Student Portal
            </span>
        </div>

        {{-- ══ Navigasi Menu Siswa ══ --}}
        @php
            $currentStatus = request()->query('status');
            $inProgressBadge = isset($stats['in_progress']) ? $stats['in_progress'] : ($sidebarStats['in_progress'] ?? null);
            $completedBadge = isset($stats['completed_modules']) ? $stats['completed_modules'] : ($sidebarStats['completed'] ?? null);
            $defaultSidebarStatus = (!empty($inProgressBadge) && $inProgressBadge > 0) ? 'in_progress' : 'classes';
            $effectiveStatus = $currentStatus ?: $defaultSidebarStatus;

            $isDashboardActive = (request()->routeIs('student.dashboard') || request()->routeIs('dashboard.student')) 
                && !request()->routeIs('student.modules.*') 
                && ($effectiveStatus === 'classes' || $effectiveStatus === 'all');
            $isInProgressActive = (request()->routeIs('student.dashboard') || request()->routeIs('dashboard.student')) 
                && $effectiveStatus === 'in_progress';
            $isCompletedActive = (request()->routeIs('student.dashboard') || request()->routeIs('dashboard.student')) 
                && $effectiveStatus === 'completed';

            $isModulesRoute = request()->routeIs('student.modules.*');
            $currentSubjectParam = request()->route('subject');
            $activeSubjectId = null;
            if ($currentSubjectParam instanceof \App\Models\Subject) {
                $activeSubjectId = $currentSubjectParam->id;
            } elseif (is_numeric($currentSubjectParam)) {
                $activeSubjectId = (int) $currentSubjectParam;
            } elseif (isset($subject) && $subject instanceof \App\Models\Subject) {
                $activeSubjectId = $subject->id;
            }
        @endphp

        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
             x-data="{ modulMenuOpen: true }">

            {{-- Portal Utama: Dashboard Siswa --}}
            <a href="{{ route('student.dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all
                   {{ $isDashboardActive ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span>Dashboard Siswa</span>
            </a>

            {{-- Tombol Tambah / Gabung Kelas Baru --}}
            <button type="button"
                    @click="joinModalOpen = true"
                    class="w-full flex items-center justify-between rounded-xl px-3.5 py-2 text-xs font-bold text-emerald-400 bg-emerald-950/40 hover:bg-emerald-900/60 border border-emerald-500/30 transition-all group shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-5 h-5 rounded-md bg-emerald-500/20 flex items-center justify-center text-emerald-300 font-black text-xs group-hover:scale-110 transition-transform">
                        +
                    </span>
                    <span>Tambah Kelas Baru</span>
                </div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded-md border border-emerald-400/30">
                    Kode
                </span>
            </button>

            {{-- Grup: Pembelajaran --}}
            <p class="pt-6 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Modul & Pembelajaran</p>

            {{-- Menu Utama: Modul Belajar (Parent Accordion with Subject Sub-menus) --}}
            <div class="space-y-1">
                <button type="button"
                        @click="modulMenuOpen = !modulMenuOpen"
                        class="w-full flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-colors
                     {{ $isModulesRoute ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                        </svg>
                        <span>Modul Belajar</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                         :class="modulMenuOpen ? 'rotate-180 text-emerald-400' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                {{-- Sub-menu Mata Pelajaran (Tanpa icon) --}}
                <div x-show="modulMenuOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-700/60 ml-5 my-1">

                    {{-- Dynamic Sub-menus per Subject (Tanpa icon) --}}
                    @if(isset($studentSidebarSubjects) && $studentSidebarSubjects->isNotEmpty())
                        @foreach($studentSidebarSubjects as $sSubj)
                            @php
                                $isSubjActive = ($activeSubjectId === $sSubj->id);
                            @endphp
                            <a href="{{ route('student.modules.subject', $sSubj->id) }}"
                               class="flex items-center justify-between rounded-lg px-2.5 py-1.5 text-xs font-medium transition-all group
                                   {{ $isSubjActive ? 'bg-emerald-600 text-white font-bold shadow-md shadow-emerald-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                                <span class="truncate">{{ $sSubj->name }}</span>
                                @if(isset($sSubj->modules_count) && $sSubj->modules_count > 0)
                                    <span class="ml-1.5 px-1.5 py-0.2 rounded text-[10px] font-bold shrink-0
                                        {{ $isSubjActive ? 'bg-emerald-700/60 text-white' : 'bg-slate-800 text-slate-400 group-hover:text-emerald-400' }}">
                                        {{ $sSubj->modules_count }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Shortcut Filter: Sedang Dikerjakan --}}
            <a href="{{ route('student.dashboard', ['status' => 'in_progress']) }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all group
                   {{ $isInProgressActive ? 'bg-amber-500/20 text-amber-300 font-bold border border-amber-500/40 shadow-md shadow-amber-500/10' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 {{ $isInProgressActive ? 'text-amber-300' : 'text-amber-400 group-hover:text-amber-300' }} transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Sedang Dikerjakan</span>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isInProgressActive ? 'bg-amber-400 text-slate-900 shadow-sm' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                    {{ is_null($inProgressBadge) ? 'Proses' : $inProgressBadge . ' Modul' }}
                </span>
            </a>

            {{-- Shortcut Filter: Riwayat Selesai --}}
            <a href="{{ route('student.dashboard', ['status' => 'completed']) }}"
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all group
                   {{ $isCompletedActive ? 'bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/40 shadow-md shadow-emerald-600/10' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 {{ $isCompletedActive ? 'text-emerald-300' : 'text-emerald-400 group-hover:text-emerald-300' }} transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Riwayat Selesai</span>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isCompletedActive ? 'bg-emerald-400 text-slate-900 shadow-sm' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                    {{ is_null($completedBadge) ? 'Lulus' : $completedBadge . ' Modul' }}
                </span>
            </a>

            {{-- Grup: Bantuan Belajar --}}
            <p class="pt-6 pb-1 px-3 text-[11px] font-bold uppercase tracking-widest text-slate-500">Pedoman Belajar</p>

            <div class="p-3.5 rounded-2xl bg-slate-800/70 border border-slate-700/60 text-slate-300">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-sm">💡</span>
                    <h4 class="text-xs font-bold text-white">Panduan 5 Bagian</h4>
                </div>
                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Selesaikan materi, kuis, praktik embed, LKPD & Job Sheet untuk mencapai kompetensi maksimal.
                </p>
            </div>
        </nav>

        {{-- ══ Profil Bawah & Logout Siswa ══ --}}
        <div class="p-4 border-t border-slate-800 shrink-0">
            <div class="flex items-center justify-between gap-3 bg-slate-800/80 p-2.5 rounded-xl border border-slate-700/50">
                <div class="flex items-center gap-2.5 min-w-0">
                    <img class="h-9 w-9 rounded-full object-cover ring-2 ring-emerald-500/30 shrink-0"
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('student')->user()->name ?? 'Siswa') }}&background=059669&color=fff&bold=true&size=64"
                         alt="Avatar">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::guard('student')->user()->name ?? 'Peserta Didik' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ Auth::guard('student')->user()?->schoolClass?->full_name ?? 'Siswa SMKN 3' }}</p>
                    </div>
                </div>
                <form action="{{ route('logout.student') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-700/50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
