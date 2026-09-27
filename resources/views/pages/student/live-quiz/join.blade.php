@extends('layouts.student.dashboardstudent')

@section('title', 'Gabung Kuis Live — Student Portal')
@section('page-title', 'Gabung Kuis Live')

@section('content')
<div class="max-w-md mx-auto py-8 sm:py-12 space-y-6">

    {{-- Main Join Card (Tema Hijau ke Hitaman) --}}
    <div class="bg-gradient-to-b from-[#0a141b] via-slate-950 to-[#060a0f] rounded-3xl p-8 text-white shadow-2xl border border-emerald-500/30 relative overflow-hidden text-center">
        <div class="absolute -right-12 -top-12 w-40 h-40 bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-40 h-40 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">
            {{-- Icon --}}
            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-600 flex items-center justify-center text-slate-950 shadow-lg shadow-emerald-500/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>

            @if(isset($activeClassSession) && $activeClassSession)
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-black uppercase tracking-wider mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Kuis Live Aktif
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-white">{{ $activeClassSession->module->title ?? 'Kuis Live Interaktif' }}</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1.5">
                        Kuis live sedang dipandu oleh guru di kelas. Klik tombol di bawah untuk langsung masuk.
                    </p>
                </div>

                {{-- Detail Sesi --}}
                <div class="bg-slate-900/80 rounded-2xl p-4 border border-slate-800 text-left space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Tipe Evaluasi:</span>
                        <span class="font-bold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase text-[10px]">
                            {{ $activeClassSession->test_type === 'pre_test' ? 'Pre-Test' : 'Post-Test' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Guru Pengampu:</span>
                        <span class="font-bold text-slate-200">{{ $activeClassSession->teacher->name ?? 'Pengampu' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Kelas Target:</span>
                        <span class="font-bold text-slate-200">{{ $activeClassSession->schoolClass->full_name ?? 'Seluruh Siswa' }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-slate-400">Nama Siswa:</span>
                        <span class="font-black text-emerald-400">{{ auth('student')->user()->name }}</span>
                    </div>
                </div>

                {{-- Error alerts --}}
                @if ($errors->any())
                    <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 p-3.5 text-xs text-rose-300 text-left">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                    </div>
                @endif

                {{-- Direct Join Form --}}
                <form action="{{ route('student.live-quiz.submit-join') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $activeClassSession->id }}">
                    <button type="submit"
                            class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black text-base shadow-xl shadow-emerald-500/30 transition-all transform hover:scale-[1.02] active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                        <span>Gabung Kuis Sekarang</span>
                    </button>
                </form>

            @else
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-white">Belum Ada Kuis Live yang Aktif</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-2 leading-relaxed">
                        Guru pengampu belum mengaktifkan sesi kuis live untuk kelas Anda. Sesi kuis akan otomatis muncul di dashboard saat guru memulai kuis di kelas.
                    </p>
                </div>

                {{-- Error alerts --}}
                @if ($errors->any())
                    <div class="rounded-xl border border-rose-500/30 bg-rose-500/10 p-3.5 text-xs text-rose-300 text-left">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                <span>{{ $error }}</span>
                            </p>
                        @endforeach
                    </div>
                @endif

                <div class="pt-2">
                    <a href="{{ route('student.dashboard') }}"
                       class="w-full inline-flex items-center justify-center py-3.5 px-6 rounded-2xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm transition-all">
                        Kembali ke Dashboard Siswa
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Back to dashboard link --}}
    <div class="text-center">
        <a href="{{ route('student.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700 transition-colors">
            ← Kembali ke Dashboard Siswa
        </a>
    </div>

</div>
@endsection
