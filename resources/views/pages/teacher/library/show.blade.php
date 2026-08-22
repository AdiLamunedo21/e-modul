@extends('layouts.teacher.dashboardteacher')

@section('title', $module->title . ' — Pratinjau Library Modul')
@section('page-title', 'Pratinjau Modul')

@section('content')

{{-- ══ Breadcrumb ══ --}}
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('teacher.library.index') }}" class="hover:text-indigo-600 font-medium transition-colors">Library Modul</a>
    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
    <span class="font-semibold text-slate-800 truncate max-w-md">{{ $module->title }}</span>
</nav>

{{-- ══ Flash Alerts ══ --}}
@if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ══ Header Modul & Action Bar ══ --}}
@php
    $sections = $module->moduleSectionsSummary();
    $totalComponents = collect($sections)->sum('total_count');
    $totalActive = collect($sections)->sum('active_count');
    $activePercent = $totalComponents > 0 ? round(($totalActive / $totalComponents) * 100) : 0;
    $isMine = $module->teacher_id === auth()->guard('teacher')->id();
@endphp

<div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm p-6 sm:p-8 mb-8 relative overflow-hidden">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="space-y-3 flex-1">
            <div class="flex flex-wrap items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    Library Modul Bersama
                </span>
                @if($module->schoolClass)
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        Kelas {{ $module->schoolClass->grade }} {{ $module->schoolClass->major_name }}
                    </span>
                @endif
                <span class="text-xs text-slate-400">
                    Dibagikan oleh <strong>{{ $module->teacher->name ?? 'Guru Pendidik' }}</strong> &bull; {{ $module->shared_at ? $module->shared_at->format('d M Y') : $module->created_at->format('d M Y') }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-snug tracking-tight">
                {{ $module->title }}
            </h1>

            @if($module->clonedFrom)
                <p class="text-xs text-slate-500 flex items-center gap-1.5">
                    <span>🌱</span>
                    <span>Modul ini diadaptasi dari karya asli <strong>{{ $module->clonedFrom->teacher->name ?? 'Pendidik' }}</strong></span>
                </p>
            @endif

            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Pratinjau struktur kurikulum 5 bagian E-Modul. Anda dapat menyalin instrumen ini ke akun Anda untuk digunakan dalam proses belajar mengajar.
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            @if($isMine)
                <a href="{{ route('teacher.modules.show', $module) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-2xl transition-all">
                    <span>Buka di Manajer Modul Saya</span>
                </a>
            @endif

            <button type="button"
                    onclick="openCloneModal({{ $module->id }}, '{{ addslashes($module->title) }}', '{{ $module->teacher->name ?? 'Guru' }}')"
                    class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl shadow-lg shadow-indigo-600/30 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Salin Modul ke Workspace Saya</span>
            </button>
        </div>
    </div>

    {{-- Progress bar overview --}}
    <div class="mt-6 pt-6 border-t border-slate-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700">Kelengkapan Instrumen Modul:</span>
                <span class="text-xs font-black text-indigo-600">{{ $totalActive }} dari {{ $totalComponents }} Komponen Aktif ({{ $activePercent }}%)</span>
            </div>
            <span class="text-xs text-slate-400 font-medium">Telah disalin {{ $module->clone_count }} kali oleh guru sekolah</span>
        </div>
        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
            <div class="h-full bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full transition-all duration-500" style="width: {{ $activePercent }}%"></div>
        </div>
    </div>
</div>

{{-- ══ 2. 5 BAGIAN STRUKTUR KURIKULUM ══ --}}
<div class="space-y-6 mb-8">
    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
        <span>📑</span>
        <span>Struktur 5 Bagian Kurikulum E-Modul</span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- 1. Bagian Awal --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 font-black text-xs flex items-center justify-center border border-blue-100">1</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Bagian Awal</h3>
                        <p class="text-[11px] text-slate-400">Komponen Pembuka Modul</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $sections['bagian_awal']['active_count'] }}/{{ $sections['bagian_awal']['total_count'] }} Aktif</span>
            </div>
            <ul class="space-y-2 text-xs">
                @foreach($sections['bagian_awal']['components'] as $key => $c)
                    <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-blue-50/50 text-blue-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                        <span>{{ $c['name'] }}</span>
                        <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- 2. Pendahuluan --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 font-black text-xs flex items-center justify-center border border-indigo-100">2</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Pendahuluan</h3>
                        <p class="text-[11px] text-slate-400">Orientasi & Asesmen Diagnostik</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $sections['pendahuluan']['active_count'] }}/{{ $sections['pendahuluan']['total_count'] }} Aktif</span>
            </div>
            <ul class="space-y-2 text-xs">
                @foreach($sections['pendahuluan']['components'] as $key => $c)
                    <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-indigo-50/50 text-indigo-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                        <span>{{ $c['name'] }}</span>
                        <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- 3. Kegiatan Belajar --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 font-black text-xs flex items-center justify-center border border-amber-100">3</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Kegiatan Belajar (Materi)</h3>
                        <p class="text-[11px] text-slate-400">Konten Teks, PPT, & Multimedia Video</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $sections['kegiatan_belajar']['active_count'] }}/{{ $sections['kegiatan_belajar']['total_count'] }} Aktif</span>
            </div>
            <ul class="space-y-2 text-xs">
                @foreach($sections['kegiatan_belajar']['components'] as $key => $c)
                    <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-amber-50/50 text-amber-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                        <span>{{ $c['name'] }}</span>
                        <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- 4. Evaluasi & Praktik --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 font-black text-xs flex items-center justify-center border border-purple-100">4</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Evaluasi & Praktik</h3>
                        <p class="text-[11px] text-slate-400">Embed Praktik, Job Sheet, & LKPD</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $sections['evaluasi_latihan']['active_count'] }}/{{ $sections['evaluasi_latihan']['total_count'] }} Aktif</span>
            </div>
            <ul class="space-y-2 text-xs">
                @foreach($sections['evaluasi_latihan']['components'] as $key => $c)
                    <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-purple-50/50 text-purple-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                        <span>{{ $c['name'] }}</span>
                        <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- 5. Bagian Akhir --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm md:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 font-black text-xs flex items-center justify-center border border-teal-100">5</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Bagian Akhir</h3>
                        <p class="text-[11px] text-slate-400">Post-test Sumatif & Daftar Pustaka</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $sections['bagian_akhir']['active_count'] }}/{{ $sections['bagian_akhir']['total_count'] }} Aktif</span>
            </div>
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                @foreach($sections['bagian_akhir']['components'] as $key => $c)
                    <li class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $c['is_active'] ? 'bg-teal-50/50 text-teal-900 font-semibold' : 'bg-slate-50 text-slate-400' }}">
                        <span>{{ $c['name'] }}</span>
                        <span class="text-[10px] font-bold">{{ $c['is_active'] ? '✓ Aktif' : 'Nonaktif' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- ══ 3. DETAIL PREVIEW ACCORDION ══ --}}
@if($module->has_pre_test || $module->has_post_test || $module->has_materi || $module->has_video)
<div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm mb-8 space-y-6">
    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
        <span>🔍</span>
        <span>Pratinjau Konten Utama</span>
    </h3>

    {{-- Pre-test info --}}
    @if($module->has_pre_test && $module->preTest)
        <div class="p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wide">📝 Kuis Pre-test Diagnostik</span>
                <span class="text-xs font-bold text-emerald-700">{{ $module->preTest->questionCount() }} Butir Soal • Durasi {{ $module->preTest->duration_minutes }} Menit • KKTP {{ $module->preTest->kktp }}</span>
            </div>
            <p class="text-xs text-emerald-900 font-medium">{{ $module->preTest->title }}</p>
        </div>
    @endif

    {{-- Post-test info --}}
    @if($module->has_post_test && $module->postTest)
        <div class="p-5 rounded-2xl bg-teal-50/50 border border-teal-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-teal-800 uppercase tracking-wide">🎯 Kuis Post-test Evaluasi</span>
                <span class="text-xs font-bold text-teal-700">{{ $module->postTest->questionCount() }} Butir Soal • Durasi {{ $module->postTest->duration_minutes }} Menit • KKTP {{ $module->postTest->kktp }}</span>
            </div>
            <p class="text-xs text-teal-900 font-medium">{{ $module->postTest->title }}</p>
        </div>
    @endif

    {{-- Video info --}}
    @if($module->has_video && !empty($module->video_data['youtube_url']))
        <div class="p-5 rounded-2xl bg-red-50/50 border border-red-200">
            <span class="text-xs font-bold text-red-800 uppercase tracking-wide block mb-1">🎬 Video YouTube Pembelajaran</span>
            <p class="text-xs text-red-900 font-semibold">{{ $module->video_data['title'] ?? 'Video Pembelajaran' }}</p>
            <p class="text-[11px] text-red-600 truncate mt-0.5">{{ $module->video_data['youtube_url'] }}</p>
        </div>
    @endif
</div>
@endif

{{-- ══ STICKY BOTTOM ACTION BAR ══ --}}
<div class="sticky bottom-6 z-30 bg-slate-900/90 backdrop-blur-md rounded-3xl p-4 sm:p-5 text-white shadow-2xl border border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-lg font-black shrink-0">
            📚
        </div>
        <div>
            <h4 class="text-xs sm:text-sm font-bold truncate max-w-md">{{ $module->title }}</h4>
            <p class="text-[11px] text-slate-400">Oleh {{ $module->teacher->name ?? 'Guru Pendidik' }}</p>
        </div>
    </div>

    <button type="button"
            onclick="openCloneModal({{ $module->id }}, '{{ addslashes($module->title) }}', '{{ $module->teacher->name ?? 'Guru' }}')"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-indigo-600/30 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        <span>Salin ke Workspace Saya</span>
    </button>
</div>

{{-- ══ MODAL SALIN MODUL KE WORKSPACE ══ --}}
<div id="cloneModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative transform transition-all">
        
        {{-- Close Button --}}
        <button type="button" onclick="closeCloneModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                📥
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Salin Modul ke Workspace</h3>
                <p class="text-xs text-slate-500">Duplikasi instrumen pembelajaran ke akun Anda</p>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-900 mb-5 leading-relaxed">
            <p class="font-bold mb-1">Informasi Kloning Modul:</p>
            <p class="text-indigo-800/80">
                Seluruh materi, catatan notepad, video YouTube, kuis Pre-test, Post-test, simulator embed, job sheet, dan LKPD akan disalin sebagai draf baru yang siap Anda modifikasi tanpa memengaruhi modul asli.
            </p>
        </div>

        <form id="cloneForm" method="POST" action="{{ route('teacher.library.clone', $module) }}" class="space-y-4">
            @csrf
            
            {{-- Original Module Info --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Modul Sumber
                </label>
                <div id="modalSourceModuleTitle" class="text-xs sm:text-sm font-extrabold text-slate-800 bg-slate-100 px-3.5 py-2.5 rounded-xl border border-slate-200 truncate">
                    {{ $module->title }} (Oleh: {{ $module->teacher->name ?? 'Guru' }})
                </div>
            </div>

            {{-- Custom Title --}}
            <div>
                <label for="clone_title" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Judul Modul di Workspace Anda <span class="text-slate-400 font-normal">(Bisa disesuaikan)</span>
                </label>
                <input type="text"
                       name="title"
                       id="clone_title"
                       value="{{ $module->title }} (Salinan)"
                       required
                       class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold text-slate-800 transition-all">
            </div>

            {{-- Target Class Selection --}}
            <div>
                <label for="clone_class_id" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                    Target Kelas Binaan Anda <span class="text-red-500">*</span>
                </label>
                <select name="class_id"
                        id="clone_class_id"
                        required
                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 font-semibold text-slate-800 transition-all cursor-pointer">
                    <option value="" disabled selected>-- Pilih Kelas Binaan Target --</option>
                    @foreach($allClasses as $cls)
                        <option value="{{ $cls->id }}">Kelas {{ $cls->grade }} - {{ $cls->major_name }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Kelas yang akan menjadi target distribusi modul Anda.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                <button type="button" onclick="closeCloneModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span>Konfirmasi & Salin Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCloneModal(moduleId, moduleTitle, authorName) {
        document.getElementById('cloneModal').classList.remove('hidden');
    }

    function closeCloneModal() {
        document.getElementById('cloneModal').classList.add('hidden');
    }
</script>

@endsection
