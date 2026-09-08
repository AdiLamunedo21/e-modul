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

            <div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Kuis Live Interaktif</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1.5">
                    Masukkan 6 digit PIN yang ditampilkan di proyektor guru di depan kelas.
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

            {{-- Join Form --}}
            <form action="{{ route('student.live-quiz.submit-join') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="pin" class="block text-[11px] font-black uppercase tracking-widest text-emerald-400/90 mb-2">
                        Game PIN
                    </label>
                    <input type="text"
                           name="pin"
                           id="pin"
                           maxlength="6"
                           pattern="[0-9]{6}"
                           inputmode="numeric"
                           required
                           autofocus
                           placeholder="000000"
                           class="w-full text-center font-mono text-3xl sm:text-4xl font-black tracking-[0.3em] rounded-2xl border-2 border-emerald-500/30 bg-slate-950/80 text-emerald-300 placeholder-slate-600 focus:border-emerald-400 focus:bg-slate-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 py-4 transition-all uppercase shadow-inner">
                </div>

                {{-- Student Identity Info --}}
                <div class="bg-slate-900/80 rounded-xl p-3 border border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-400">Masuk sebagai:</span>
                    <span class="font-bold text-slate-200">{{ auth('student')->user()->name }}</span>
                </div>

                <button type="submit"
                        class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black text-base shadow-xl shadow-emerald-500/30 transition-all transform hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                    Masuk ke Kuis
                </button>
            </form>
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
