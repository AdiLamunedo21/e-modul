@extends('layouts.student.dashboardstudent')

@section('title', 'Modul ' . $subject->name . ' — ' . $class->full_name . ' — E-Modul SMKN 3 Yogyakarta')
@section('page-title', 'Modul ' . $subject->name)

@section('content')

<div class="space-y-8 pb-12">

    {{-- ══ Tombol Kembali & Breadcrumb Navigasi ══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 flex-wrap">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-bold transition-colors">
                <span>Dashboard Siswa</span>
            </a>
            <span>/</span>
            <a href="{{ route('student.classes.show', $class->id) }}" class="text-slate-600 hover:text-emerald-600 font-bold transition-colors">
                {{ $class->full_name }}
            </a>
            <span>/</span>
            <span class="text-slate-800 font-extrabold">{{ $subject->name }}</span>
        </div>

        <a href="{{ route('student.classes.show', $class->id) }}"
           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs transition-all self-start sm:self-auto">
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Mata Pelajaran</span>
        </a>
    </div>

    {{-- ══ Hero Header Modul Mata Pelajaran ══ --}}
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        {{-- Decorative Blur Effects --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                {{-- Badges --}}
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
                    <span class="flex items-center gap-1.5 text-emerald-300">
                        <span>🏫</span>
                        <span>{{ $class->full_name }}</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="font-mono text-emerald-300 font-black uppercase tracking-wider">
                        KODE: {{ $class->code }}
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="text-slate-200">{{ $subject->name }}</span>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    E-Modul: {{ $subject->name }}
                </h1>

                {{-- Guru Pengampu & Deskripsi --}}
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-2xl font-normal">
                    Guru Pengampu: <strong class="text-white font-bold">{{ $teacherDisplay }}</strong>. Selesaikan materi, kuis, praktik embed, LKPD & Job Sheet untuk mencapai kompetensi maksimal.
                </p>
            </div>

            {{-- Ringkasan Capaian Mapel --}}
            <div class="flex items-center gap-4 bg-slate-950/50 border border-white/15 p-4 sm:p-5 rounded-2xl backdrop-blur-md shadow-sm shrink-0">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center text-2xl font-black shrink-0">
                    📊
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Capaian Mapel</p>
                    <p class="text-lg sm:text-xl font-black text-white">
                        {{ $stats['completed_modules'] }}/{{ $stats['total_modules'] }} Modul Selesai
                    </p>
                    <div class="flex items-center gap-2">
                        <div class="w-28 bg-slate-800 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $stats['avg_progress'] }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-emerald-400">{{ $stats['avg_progress'] }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Bar Filter & Tab Status ══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <span>📚</span>
                <span>Modul Pembelajaran Guru</span>
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Daftar modul yang dibuat oleh guru pengampu untuk kelas <strong>{{ $class->full_name }}</strong>.
            </p>
        </div>

        {{-- Filter Status Belajar --}}
        <div class="flex items-center gap-1.5 bg-white p-1 rounded-2xl border border-slate-200 shadow-2xs shrink-0 self-start sm:self-center">
            <a href="{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subject->id, 'status' => 'all']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $filterStatus === 'all' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua Status
            </a>
            <a href="{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subject->id, 'status' => 'in_progress']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $filterStatus === 'in_progress' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Sedang Dipelajari
            </a>
            <a href="{{ route('student.classes.subject', ['class' => $class->id, 'subject' => $subject->id, 'status' => 'completed']) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $filterStatus === 'completed' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Tuntas
            </a>
        </div>
    </div>

    {{-- ══ Grid Modul-Modul Pembelajaran Buatan Guru ══ --}}
    @if($filteredModules->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($filteredModules as $module)
                <div class="group relative rounded-3xl bg-white border border-slate-200/90 hover:border-emerald-400 p-6 flex flex-col justify-between transition-all duration-300 shadow-sm hover:shadow-xl">
                    
                    {{-- Konten Atas Modul --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200 truncate">
                                {{ $module['subject_name'] }}
                            </span>

                            {{-- Status Badge --}}
                            @if($module['progress_status'] === 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0">
                                    <span>✓</span>
                                    <span>Tuntas</span>
                                </span>
                            @elseif($module['progress_status'] === 'in_progress')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 shrink-0">
                                    <span>⚡</span>
                                    <span>{{ $module['progress_percent'] }}%</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-200 text-slate-600 shrink-0">
                                    <span>Belum Mulai</span>
                                </span>
                            @endif
                        </div>

                        {{-- Judul Modul --}}
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-snug">
                                {{ $module['title'] }}
                            </h3>
                            @if($module['description'])
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $module['description'] }}
                                </p>
                            @endif
                        </div>

                        {{-- Guru Pengampu --}}
                        <div class="flex items-center gap-2 text-xs text-slate-600 pt-1">
                            <span class="text-slate-400 font-medium">Guru:</span>
                            <span class="font-bold text-slate-800 truncate">{{ $module['teacher_name'] }}</span>
                        </div>

                        {{-- 5 Komponen Pedagogis Badges --}}
                        <div class="flex items-center gap-1.5 flex-wrap pt-1">
                            @if($module['has_pre_test'])
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">📝 Pre-test</span>
                            @endif
                            @if($module['has_materi'])
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">📖 Materi</span>
                            @endif
                            @if($module['has_video'])
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">🎬 Video</span>
                            @endif
                            @if($module['has_embed'])
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">⚡ Praktik</span>
                            @endif
                            @if($module['has_job_sheet'])
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">📋 Job Sheet</span>
                            @endif
                            @if($module['has_lkpd'])
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">📑 LKPD</span>
                            @endif
                            @if($module['has_post_test'])
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">🎯 Post-test</span>
                            @endif
                        </div>

                        {{-- Progres Belajar Bar --}}
                        <div class="space-y-1.5 pt-2">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500">
                                <span>Kemajuan Belajar:</span>
                                <span class="font-bold text-slate-800">{{ $module['completed_tasks'] }}/{{ $module['total_components'] }} Instrumen ({{ $module['progress_percent'] }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full {{ $module['progress_status'] === 'completed' ? 'bg-emerald-500' : ($module['progress_status'] === 'in_progress' ? 'bg-amber-500' : 'bg-slate-300') }} transition-all duration-300"
                                     style="width: {{ $module['progress_percent'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Buka Modul Interaktif --}}
                    <div class="pt-5 mt-5 border-t border-slate-100">
                        <a href="{{ route('student.modules.show', $module['id']) }}"
                           class="w-full py-3 px-4 rounded-2xl text-xs font-extrabold flex items-center justify-center gap-2 transition-all shadow-sm
                           {{ $module['progress_status'] === 'completed'
                               ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/20'
                               : ($module['progress_status'] === 'in_progress'
                                   ? 'bg-amber-600 hover:bg-amber-500 text-white shadow-amber-600/20'
                                   : 'bg-slate-900 hover:bg-emerald-600 text-white') }}">
                            <span>{{ $module['progress_status'] === 'completed' ? 'Review / Ulangi Modul' : ($module['progress_status'] === 'in_progress' ? 'Lanjutkan Belajar' : 'Mulai Belajar Modul') }}</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-16 text-center bg-white rounded-3xl border border-slate-200 space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-3xl font-bold">
                📭
            </div>
            <div class="max-w-md mx-auto">
                <h3 class="text-base font-bold text-slate-800">Belum Ada Modul Terbit</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Guru pengampu belum mempublikasikan modul pembelajaran untuk mata pelajaran <strong>{{ $subject->name }}</strong> pada kelas <strong>{{ $class->full_name }}</strong>.
                </p>
            </div>
        </div>
    @endif

</div>

@endsection
