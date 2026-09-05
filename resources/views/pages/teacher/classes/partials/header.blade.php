{{-- ══ 1. HEADER & BREADCRUMB ══ --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 border border-blue-700/40 mb-8">
    {{-- Glow Elements --}}
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 space-y-4">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs font-semibold text-blue-200/90">
            <a href="{{ route('teacher.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
            <span class="text-blue-300/40">/</span>
            <a href="{{ route('teacher.classes.index') }}" class="hover:text-white transition-colors">Build Kelas</a>
            <span class="text-blue-300/40">/</span>
            <span class="text-white font-bold">{{ $class->full_name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
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

                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    {{ $class->full_name }}
                </h1>
                <p class="text-slate-200 text-sm max-w-2xl leading-relaxed font-normal">
                    {{ $class->major ? $class->major->name : $class->major_name }} &bull; Kelola data siswa, portofolio modul, impor modul dari kelas lain, dan rekapitulasi nilai akademik.
                </p>

                {{-- ══ KODE KELAS & SHARE SISWA ══ --}}
                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <div class="inline-flex items-center gap-3 bg-slate-950/60 border border-white/20 px-4 py-2 rounded-2xl backdrop-blur-md shadow-sm" x-data="{ copied: false }">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-blue-200">KODE KELAS:</span>
                            <span class="font-mono text-base font-black text-yellow-300 tracking-widest select-all">{{ $class->code }}</span>
                        </div>
                        <div class="h-4 w-px bg-white/20"></div>
                        <button type="button"
                                @click="navigator.clipboard.writeText('{{ $class->code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="inline-flex items-center gap-1.5 text-xs font-extrabold text-white hover:text-blue-300 transition-colors"
                                title="Salin Kode Kelas">
                            <svg x-show="!copied" class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                            <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span x-text="copied ? 'Tersalin!' : 'Salin Kode'"></span>
                        </button>
                    </div>

                    <button type="button"
                            x-data="{ shared: false }"
                            @click="navigator.clipboard.writeText('Silakan bergabung ke kelas {{ addslashes($class->full_name) }} di E-Modul SMKN 3 Yogyakarta menggunakan Kode Kelas: {{ $class->code }} (Link Pendaftaran: {{ url('/register/student') }}?code={{ $class->code }})'); shared = true; setTimeout(() => shared = false, 2000)"
                            class="px-3.5 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold transition-all flex items-center gap-1.5 backdrop-blur-md">
                        <svg x-show="!shared" class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                        <svg x-show="shared" x-cloak class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span x-text="shared ? 'Teks Undangan Disalin!' : 'Bagikan ke Siswa'"></span>
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('teacher.classes.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                    ← Kembali ke Build Kelas
                </a>

                {{-- Tombol Import Modul --}}
                <button @click="importModalOpen = true"
                        type="button"
                        class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-lg shadow-indigo-950/40 flex items-center gap-2 border border-indigo-400/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Import Modul Lain</span>
                </button>

                {{-- Tombol Hapus Kelas (Purge) --}}
                <button @click="deleteModalOpen = true"
                        type="button"
                        class="px-4 py-2.5 rounded-xl bg-rose-600/80 hover:bg-rose-600 text-white text-xs font-bold transition-all shadow-lg shadow-rose-950/40 flex items-center gap-2 border border-rose-400/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>Hapus Kelas</span>
                </button>
            </div>
        </div>
    </div>
</div>
