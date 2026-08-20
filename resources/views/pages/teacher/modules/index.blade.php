@extends('layouts.teacher.dashboardteacher')

@section('title', 'Manajer Modul — Teacher Workspace')
@section('page-title', 'Manajer Modul')

@section('content')

{{-- ══ Header Halaman ══ --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajer E-Modul</h1>
        <p class="mt-1 text-sm text-slate-500">Seluruh riwayat E-Modul yang pernah Anda buat. Pantau status dan progres pengumpulan siswa secara <em>real-time</em>.</p>
    </div>
    <a href="{{ route('teacher.modules.create') }}"
       class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition-all shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Buat Modul Baru
    </a>
</div>

{{-- Flash Messages --}}
@if (session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
@endif

{{-- ══ Filter Tabs ══ --}}
<div class="flex items-center gap-1 bg-white border border-slate-200 p-1 rounded-2xl shadow-sm mb-6 self-start overflow-x-auto">
    @php
        $activeStatus = request('status', '');
        $tabs = [
            ''          => "Semua ({$counts['all']})",
            'published' => "Terbit ({$counts['published']})",
            'draft'     => "Draf ({$counts['draft']})",
            'closed'    => "Ditutup ({$counts['closed']})",
        ];
    @endphp
    @foreach ($tabs as $value => $label)
        <a href="{{ route('teacher.modules.index', $value ? ['status' => $value] : []) }}"
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap
               {{ $activeStatus === $value
                   ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30'
                   : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- ══ Module List ══ --}}
@if ($modules->isEmpty())
    <div class="rounded-2xl bg-white border border-dashed border-slate-300 p-16 text-center">
        <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Modul</h3>
        <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">
            @if($activeStatus)
                Tidak ada modul dengan status <strong>{{ $tabs[$activeStatus] ?? $activeStatus }}</strong>.
            @else
                Anda belum membuat E-Modul apapun. Mulai rakit modul pertama dengan <em>Module Builder</em>.
            @endif
        </p>
        <a href="{{ route('teacher.modules.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition-all">
            + Buat Modul Pertama
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach ($modules as $module)
            @php
                $badge    = $module->statusLabel();
                $comps    = $module->activeComponents();
                $class    = $module->schoolClass;
            @endphp
            <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                        {{-- ── Informasi Modul ── --}}
                        <div class="flex-1 space-y-3 min-w-0">
                            {{-- Status & Kelas --}}
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide border {{ $badge['color'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        {{ $module->status === 'published' ? 'bg-emerald-500' : ($module->status === 'closed' ? 'bg-slate-400' : 'bg-amber-500') }}"></span>
                                    {{ $badge['label'] }}
                                </span>
                                @if($class)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        Kelas {{ $class->grade }} {{ $class->major_name }}
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400">
                                    Dibuat {{ $module->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Judul Modul --}}
                            <a href="{{ route('teacher.modules.show', $module) }}"
                               class="block text-xl font-bold text-slate-900 hover:text-blue-600 transition-colors leading-snug truncate max-w-2xl">
                                {{ $module->title }}
                            </a>

                            {{-- 7 Komponen Inti --}}
                            @if(count($comps) > 0)
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="text-[11px] font-bold text-slate-500 mr-1">{{ count($comps) }} Komponen Aktif:</span>
                                    @foreach($comps as $comp)
                                        <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100/80 px-2 py-0.5 rounded-md">
                                            {{ $comp }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-400 italic">Belum ada Komponen Inti yang diaktifkan.</p>
                            @endif
                        </div>

                        {{-- ── Progress Bar (Published only) ── --}}
                        @if($module->status === 'published')
                            <div class="w-full lg:w-64 bg-slate-50 border border-slate-200/70 rounded-2xl p-4 shrink-0">
                                @php
                                    $totalSiswa = 0; // TODO: ambil dari DB
                                    $selesai = 0;
                                    $pct = $totalSiswa > 0 ? round(($selesai / $totalSiswa) * 100) : 0;
                                @endphp
                                <div class="flex justify-between text-xs font-semibold mb-2">
                                    <span class="text-slate-600">Pengumpulan Siswa</span>
                                    <span class="text-blue-600">{{ $selesai }}/{{ $totalSiswa }} ({{ $pct }}%)</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="mt-2 text-[11px] text-slate-500">Fitur Progress Bar aktif setelah siswa mulai mengerjakan.</p>
                            </div>
                        @endif

                        {{-- ── Action Buttons ── --}}
                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            {{-- Lihat Detail --}}
                            <a href="{{ route('teacher.modules.show', $module) }}"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Detail
                            </a>

                            {{-- Publish / Unpublish Toggle --}}
                            <form action="{{ route('teacher.modules.status', $module) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                @if($module->status === 'draft')
                                    <input type="hidden" name="status" value="published">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                        Publish
                                    </button>
                                @elseif($module->status === 'published')
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        Tutup Modul
                                    </button>
                                @else
                                    <input type="hidden" name="status" value="draft">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 rounded-xl transition-all">
                                        Buka Kembali
                                    </button>
                                @endif
                            </form>

                            {{-- Hapus --}}
                            <form action="{{ route('teacher.modules.destroy', $module) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus modul \'{{ addslashes($module->title) }}\'? Semua data terkait akan ikut terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if ($modules->hasPages())
        <div class="mt-6">
            {{ $modules->links() }}
        </div>
    @endif
@endif

@endsection
