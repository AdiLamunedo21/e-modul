@extends('layouts.teacher.dashboardteacher')

@section('title', 'Kuis Live (Mode Pantau) — Teacher Workspace')
@section('page-title', 'Kuis Live')

@section('content')
<div x-data="{ deleteModalOpen: false, deleteUrl: '', sessionTitle: '' }" class="space-y-6">

    {{-- ══ Header Halaman ══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kuis Live (Mode Pantau)</h1>
                    <p class="text-sm text-slate-500">Pandu kuis interaktif di proyektor kelas ala Kahoot/Quizizz dengan kendali slide guru & papan skor langsung.</p>
                </div>
            </div>
        </div>
        <a href="{{ route('teacher.live-quiz.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-700/25 hover:from-emerald-700 hover:to-teal-800 transition-all shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Mulai Kuis Live Baru
        </a>
    </div>

    {{-- Flash Notifications --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm font-medium text-rose-800 shadow-sm">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ══ Penjelasan 2 Mode Evaluasi ══ --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Mengenal 2 Mode Evaluasi E-Modul</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4 flex gap-3.5">
                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold text-sm">
                    1
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Mode Mandiri (Self-Paced)</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Siswa mengerjakan Pre-Test dan Post-Test secara individu melalui antarmuka modul masing-masing tanpa perlu dipandu langsung. Nilai otomatis tersimpan ke Pusat Penilaian.
                    </p>
                </div>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4 flex gap-3.5">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-sm">
                    2
                </div>
                <div>
                    <h3 class="text-sm font-bold text-emerald-900 flex items-center gap-2">
                        <span>Mode Pantau / Kuis Live</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-black uppercase bg-emerald-700 text-white tracking-wide">Aktif</span>
                    </h3>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Guru membuka layar proyektor di kelas. Siswa memasukkan <strong>PIN 6 Digit</strong> via HP/Laptop. Guru menggeser slide soal demi soal, siswa berlomba menjawab dengan cepat untuk memperoleh poin maksimal hingga podium juara!
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Ringkasan Statistik ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Sesi Dibuat</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $sessions->total() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                </svg>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Sesi Aktif / Lobby</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">{{ $activeSessionsCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Sesi Selesai</p>
                <p class="text-2xl font-black text-teal-700 mt-1">{{ $completedSessionsCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ══ Daftar Sesi Kuis Live ══ --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-base">Riwayat & Sesi Kuis Live</h2>
            <span class="text-xs text-slate-500">Menampilkan {{ $sessions->count() }} dari {{ $sessions->total() }} sesi</span>
        </div>

        @if($sessions->isEmpty())
            <div class="py-16 text-center">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum Ada Sesi Kuis Live</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto mt-1">
                    Buat sesi kuis live sekarang untuk memulai interaksi langsung dengan siswa Anda di kelas.
                </p>
                <div class="mt-5">
                    <a href="{{ route('teacher.live-quiz.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold shadow-md hover:bg-emerald-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Mulai Sesi Pertama
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-4">PIN Game</th>
                            <th class="px-5 py-4">Modul & Mata Pelajaran</th>
                            <th class="px-5 py-4">Tipe Tes</th>
                            <th class="px-5 py-4">Target Kelas</th>
                            <th class="px-5 py-4">Status Sesi</th>
                            <th class="px-5 py-4 text-center">Peserta</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($sessions as $session)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-4 font-mono font-black text-base text-slate-900">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 text-emerald-400 font-mono tracking-widest text-sm shadow-sm border border-emerald-500/20">
                                        <span>{{ $session->pin }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-bold text-slate-900 line-clamp-1">{{ $session->module->title ?? 'Modul Dihapus' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $session->module->subject->name ?? 'Mata Pelajaran' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    @if($session->test_type === 'pre_test')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                            Pre-Test
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            Post-Test
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $session->schoolClass->name ?? 'Semua Kelas' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @if($session->status === 'lobby')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                            Menunggu Siswa (Lobby)
                                        </span>
                                    @elseif(in_array($session->status, ['question', 'reveal', 'leaderboard', 'active']))
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Kuis Berlangsung
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-slate-700">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                        {{ $session->participants->count() }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($session->status !== 'finished')
                                            <a href="{{ route('teacher.live-quiz.host', $session) }}"
                                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                                Buka Layar Pantau
                                            </a>
                                        @else
                                            <a href="{{ route('teacher.live-quiz.host', $session) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-bold transition-colors">
                                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0A5.25 5.25 0 0018 9.75v-1.5a5.25 5.25 0 00-10.5 0v1.5a5.25 5.25 0 004.125 4.5"/></svg>
                                                Lihat Podium
                                            </a>
                                        @endif

                                        <button type="button"
                                                @click="deleteModalOpen = true; deleteUrl = '{{ route('teacher.live-quiz.destroy', $session) }}'; sessionTitle = 'PIN: {{ $session->pin }} ({{ $session->module->title ?? '' }})'"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                                title="Hapus Sesi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Konfirmasi Hapus Sesi --}}
    <div x-show="deleteModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-2xl space-y-4"
             @click.outside="deleteModalOpen = false">
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Hapus Sesi Kuis Live?</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Anda yakin ingin menghapus sesi <span class="font-bold text-slate-800" x-text="sessionTitle"></span>? Data partisipasi kuis ini akan dibersihkan.
                </p>
            </div>
            <form :action="deleteUrl" method="POST" class="flex items-center justify-end gap-3 pt-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModalOpen = false"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-bold shadow-md hover:bg-rose-700">
                    Ya, Hapus Sesi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
