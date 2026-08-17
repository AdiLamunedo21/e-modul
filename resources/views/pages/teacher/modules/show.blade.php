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

        <div class="space-y-2 flex-1">
            {{-- 1. Pre-test --}}
            <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-200/70 p-2.5 transition-colors hover:bg-slate-100/60">
                <span class="text-xs font-semibold text-slate-800">1. Pre-test</span>
                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('teacher.modules.pre-test.edit', $module) }}"
                       class="text-[11px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-200/60 px-2.5 py-1 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('teacher.modules.pre-test.toggle', $module) }}" method="POST" class="inline-flex items-center">
                        @csrf
                        <button type="button"
                                onclick="animateToggleAndSubmit(event, this)"
                                aria-label="Toggle Pre-test"
                                title="Pre-test: {{ $module->has_pre_test ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                class="relative inline-flex items-center h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_pre_test ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                            <span class="pointer-events-none absolute top-[2px] left-[2px] h-5 w-5 rounded-full bg-white shadow-md border border-slate-300/90 transition-transform duration-300 ease-in-out"
                                  style="transform: translateX({{ $module->has_pre_test ? '20px' : '0px' }});"></span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. Materi & PPT --}}
            <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-200/70 p-2.5 transition-colors hover:bg-slate-100/60">
                <span class="text-xs font-semibold text-slate-800">2. Materi & PPT</span>
                <div class="flex items-center gap-2.5 shrink-0">
                    <a href="{{ route('teacher.modules.materi.edit', $module) }}"
                       class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/60 px-2.5 py-1 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('teacher.modules.materi.toggle', $module) }}" method="POST" class="inline-flex items-center">
                        @csrf
                        <button type="button"
                                onclick="animateToggleAndSubmit(event, this)"
                                aria-label="Toggle Materi"
                                title="Materi & PPT: {{ $module->has_materi ? 'Aktif (Klik untuk Matikan)' : 'Nonaktif (Klik untuk Nyalakan)' }}"
                                class="relative inline-flex items-center h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500/40 {{ $module->has_materi ? 'bg-emerald-500 border-emerald-600' : 'bg-slate-200 border-slate-400 hover:border-slate-500' }}">
                            <span class="pointer-events-none absolute top-[2px] left-[2px] h-5 w-5 rounded-full bg-white shadow-md border border-slate-300/90 transition-transform duration-300 ease-in-out"
                                  style="transform: translateX({{ $module->has_materi ? '20px' : '0px' }});"></span>
                        </button>
                    </form>
                </div>
            </div>

            @foreach([
                ['has_video',     '3. Video YouTube'],
                ['has_embed',     '4. Praktik Embed'],
                ['has_job_sheet', '5. Job Sheet PDF'],
                ['has_lkpd',      '6. LKPD Kelompok'],
                ['has_post_test', '7. Post-test'],
            ] as [$field, $label])
                <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-200/70 px-3 py-2">
                    <span class="text-xs font-medium text-slate-700">{{ $label }}</span>
                    <span class="text-[11px] font-bold {{ $module->$field ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $module->$field ? '✓ ON' : '○ OFF' }}
                    </span>
                </div>
            @endforeach
        </div>
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

@push('scripts')
<script>
    function animateToggleAndSubmit(event, button) {
        event.preventDefault();
        const form = button.closest('form');
        const thumb = button.querySelector('span');
        const isCurrentlyActive = button.classList.contains('bg-emerald-500');

        if (isCurrentlyActive) {
            button.classList.remove('bg-emerald-500', 'border-emerald-600');
            button.classList.add('bg-slate-200', 'border-slate-400');
            if (thumb) thumb.style.transform = 'translateX(0px)';
        } else {
            button.classList.remove('bg-slate-200', 'border-slate-400');
            button.classList.add('bg-emerald-500', 'border-emerald-600');
            if (thumb) thumb.style.transform = 'translateX(20px)';
        }

        setTimeout(() => {
            form.submit();
        }, 220);
    }
</script>
@endpush
