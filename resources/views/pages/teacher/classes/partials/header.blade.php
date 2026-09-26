{{-- ══ 1. HEADER & BREADCRUMB ══ --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 p-4 sm:p-5 text-white shadow-xl shadow-blue-950/20 border border-blue-700/40 mb-8">
    {{-- Glow Elements --}}
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 sm:gap-4 flex-wrap">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-semibold text-blue-200/90">
                <a href="{{ route('teacher.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                <span class="text-blue-300/40">/</span>
                <a href="{{ route('teacher.classes.index') }}" class="hover:text-white transition-colors">Build Kelas</a>
                <span class="text-blue-300/40">/</span>
                <span class="text-white font-bold">{{ $class->full_name }}</span>
            </nav>

            <span class="text-white/30 hidden sm:inline">•</span>

            {{-- Badges Kelas --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold text-white shadow-sm flex-wrap">
                <span class="flex items-center gap-1.5 text-blue-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>Kelas {{ $class->grade }} &bull; Rombel {{ $class->section }}</span>
                </span>
                <span class="text-white/30">•</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/40 uppercase tracking-wider">
                    {{ $class->major ? $class->major->code : $class->major_name }}
                </span>
                <span class="text-white/30">•</span>
                <span class="text-[11px] text-slate-300 font-medium">SMK Negeri 3 Yogyakarta</span>
            </div>
        </div>

        {{-- Tombol Kembali ke Build Kelas --}}
        <div>
            <a href="{{ route('teacher.classes.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900/60 hover:bg-slate-900/90 text-white border border-white/20 hover:border-white/40 text-xs font-bold transition-all backdrop-blur-sm shadow-sm shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Build Kelas</span>
            </a>
        </div>
    </div>
</div>
