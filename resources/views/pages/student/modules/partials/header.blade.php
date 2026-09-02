{{-- ═══ 1. STICKY TOP HEADER & BREADCRUMB ═══ --}}
<div class="rounded-3xl bg-white border border-slate-200/90 p-5 sm:p-7 shadow-sm">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        {{-- Title & Badges --}}
        <div class="space-y-2 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ $classSubjectModulesUrl }}"
                   title="Kembali ke Daftar Modul Kelas {{ $classNameText }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition shadow-2xs">
                    <span>←</span>
                    <span>Daftar Modul ({{ $classNameText }})</span>
                </a>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold {{ $module->subject?->badgeClasses() ?? 'bg-blue-100 text-blue-800' }}">
                    <span>{{ $module->subject->code ?? 'MAPEL' }}</span>
                </span>
                <span class="px-2.5 py-1 rounded-xl text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                    ✓ Terbit & Aktif
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $module->title }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium flex flex-wrap items-center gap-2">
                <span>👨‍🏫 Guru Pengampu: <strong>{{ $module->teacher->name ?? 'Guru' }}</strong></span>
                <span>•</span>
                <span>Terakhir diperbarui: {{ $module->updated_at->translatedFormat('d M Y') }}</span>
            </p>
        </div>

        {{-- Progress & Mode Toggle Actions --}}
        <div class="flex flex-wrap items-center gap-3 bg-slate-50 border border-slate-200/80 p-3 sm:p-4 rounded-2xl shrink-0">
            {{-- Progres Belajar --}}
            <div class="min-w-[140px]">
                <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                    <span class="text-slate-500 uppercase tracking-wider text-[10px]">Aktivitas Selesai</span>
                    <span :class="computedProgressPercent >= 100 ? 'text-emerald-600' : 'text-indigo-600'"
                          class="{{ $progressPercent >= 100 ? 'text-emerald-600' : 'text-indigo-600' }}"
                          x-text="computedCompletedTasks + '/' + totalActiveComps + ' (' + computedProgressPercent + '%)'">
                        {{ $completedTasks }}/{{ $totalActive }} ({{ $progressPercent }}%)
                    </span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-indigo-500 to-teal-500' }}"
                         :class="computedProgressPercent >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-indigo-500 to-teal-500'"
                         :style="'width: ' + computedProgressPercent + '%'"
                         style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>

            {{-- Nilai Akhir Sumatif --}}
            @if($studentResult)
                <div class="pl-3.5 border-l border-slate-200 text-center min-w-[80px]">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nilai Akhir</p>
                    <p class="text-xl font-black {{ $studentResult->summative_score >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $studentResult->summative_score }}
                    </p>
                </div>
            @endif

            {{-- Switch View Mode Buttons --}}
            <div class="flex items-center gap-1.5 pl-2 border-l border-slate-200">
                <button type="button"
                        @click="viewMode = 'overview'"
                        :class="viewMode === 'overview' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200 font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-2 rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                    <span>📋</span>
                    <span>Detail Modul</span>
                </button>
                <button type="button"
                        @click="startLearning()"
                        :class="viewMode === 'learn' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20 font-bold' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold'"
                        class="px-3.5 py-2 rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer">
                    <span>🚀</span>
                    <span>{{ $progressPercent > 0 ? 'Lanjut Belajar' : 'Mulai Belajar' }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-800 flex items-center gap-3">
        <span>✅</span>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-sm font-semibold text-rose-800 flex items-center gap-3">
        <span>⚠️</span>
        <span>{{ session('error') }}</span>
    </div>
@endif
