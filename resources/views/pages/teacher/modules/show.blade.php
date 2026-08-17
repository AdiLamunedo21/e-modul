@extends('layouts.teacher.dashboardteacher')

@section('title', $module->title . ' — Detail Modul')
@section('page-title', 'Detail Modul')

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('teacher.modules.index') }}" class="hover:text-blue-600 font-medium transition-colors">Manajer Modul</a>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800 truncate max-w-xs">{{ $module->title }}</span>
</nav>

{{-- Flash --}}
@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
@endif

{{-- ══ Header Modul ══ --}}
@php $badge = $module->statusLabel(); @endphp
<div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-6 sm:p-8 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide border {{ $badge['color'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $module->status === 'published' ? 'bg-emerald-500' : ($module->status === 'closed' ? 'bg-slate-400' : 'bg-amber-500') }}"></span>
                    {{ $badge['label'] }}
                </span>
                @if($module->schoolClass)
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        Kelas {{ $module->schoolClass->grade }} {{ $module->schoolClass->major_name }}
                    </span>
                @endif
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 leading-snug">{{ $module->title }}</h1>
            <p class="text-sm text-slate-500">
                Dibuat {{ $module->created_at->format('d M Y') }} &bull; Terakhir diperbarui {{ $module->updated_at->diffForHumans() }}
            </p>
        </div>

        {{-- Status Toggle --}}
        <div class="flex items-center gap-2 shrink-0">
            <form action="{{ route('teacher.modules.status', $module) }}" method="POST">
                @csrf @method('PATCH')
                @if($module->status === 'draft')
                    <input type="hidden" name="status" value="published">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                        Publish Modul
                    </button>
                @elseif($module->status === 'published')
                    <input type="hidden" name="status" value="closed">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25-2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Tutup Modul
                    </button>
                @else
                    <input type="hidden" name="status" value="draft">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-amber-700 bg-amber-50 border border-amber-200 hover:bg-amber-100 rounded-xl transition-all">
                        Buka Kembali
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- ══ Grid Tiga Bagian Modul ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- BAGIAN AWAL --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                    <span class="text-xs font-black">1</span>
                </div>
                <h2 class="text-sm font-bold text-slate-900">Bagian Awal</h2>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full border border-rose-200">Mandatori</span>
        </div>

        <ul class="space-y-1.5 flex-1 mb-4">
            @foreach([
                'Halaman Cover (Gambar)',
                'Kata Pengantar',
                'Daftar Isi (Hyperlink)',
                'Peta Konsep',
                'Glosarium',
                'Petunjuk Penggunaan',
                'Tujuan Pembelajaran',
            ] as $item)
                @php $filled = !empty($module->bagian_awal_data); @endphp
                <li class="flex items-center gap-2 text-xs text-slate-600">
                    <svg class="w-4 h-4 shrink-0 {{ $filled ? 'text-emerald-500' : 'text-slate-300' }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $filled ? 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' }}"/>
                    </svg>
                    {{ $item }}
                </li>
            @endforeach
        </ul>

        <a href="{{ route('teacher.modules.bagian-awal.edit', $module) }}" class="inline-flex items-center justify-center gap-1.5 w-full py-2.5 text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
            Edit Bagian Awal
        </a>
    </div>

    {{-- BAGIAN INTI —7 Toggle --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <span class="text-xs font-black">2</span>
                </div>
                <h2 class="text-sm font-bold text-slate-900">Bagian Inti</h2>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full border border-blue-200">7 Toggle Opsional</span>
        </div>

        <div class="space-y-2 flex-1 mb-4">
            @foreach([
                ['has_pre_test',  '1. Pre-test'],
                ['has_materi',    '2. Materi & PPT'],
                ['has_video',     '3. Video YouTube'],
                ['has_embed',     '4. Praktik Embed'],
                ['has_job_sheet', '5. Job Sheet PDF'],
                ['has_lkpd',      '6. LKPD Kelompok'],
                ['has_post_test', '7. Post-test'],
            ] as [$field, $label])
                <form action="{{ route('teacher.modules.status', $module) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-200/70 px-3 py-2">
                        <span class="text-xs font-medium text-slate-700">{{ $label }}</span>
                        <span class="text-[11px] font-bold {{ $module->$field ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $module->$field ? '✓ ON' : '○ OFF' }}
                        </span>
                    </div>
                </form>
            @endforeach
        </div>

        <a href="#" class="inline-flex items-center justify-center gap-1.5 w-full py-2.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Kelola 7 Sakelar Komponen
        </a>
    </div>

    {{-- BAGIAN AKHIR --}}
    <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="text-xs font-black">3</span>
                </div>
                <h2 class="text-sm font-bold text-slate-900">Bagian Akhir</h2>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full border border-rose-200">Mandatori</span>
        </div>

        <ul class="space-y-1.5 flex-1 mb-4">
            @foreach([
                'Soal Evaluasi (Tes Sumatif)',
                'Kunci Jawaban & Pedoman KKTP',
                'Logika Rekomendasi Pengulangan',
                'Daftar Pustaka',
            ] as $item)
                @php $filled = !empty($module->bagian_akhir_data); @endphp
                <li class="flex items-center gap-2 text-xs text-slate-600">
                    <svg class="w-4 h-4 shrink-0 {{ $filled ? 'text-emerald-500' : 'text-slate-300' }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $filled ? 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' }}"/>
                    </svg>
                    {{ $item }}
                </li>
            @endforeach
        </ul>

        <a href="#" class="inline-flex items-center justify-center gap-1.5 w-full py-2.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
            Edit Bagian Akhir
        </a>
    </div>
</div>

{{-- ══ Footer Actions ══ --}}
<div class="flex items-center gap-3">
    <a href="{{ route('teacher.modules.index') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all">
        ← Kembali ke Daftar
    </a>
    <a href="{{ route('teacher.grading.index') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow shadow-blue-600/20 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Grading Center Modul Ini
    </a>
</div>

@endsection
