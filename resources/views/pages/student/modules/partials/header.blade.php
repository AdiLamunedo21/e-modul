{{-- ═══ 1. TOP HEADER & NAVIGATION HUB (DESKTOP & MOBILE RESPONSIVE) ═══ --}}
<div class="rounded-3xl bg-white border border-slate-200/90 p-4 sm:p-6 lg:p-7 shadow-sm space-y-4">
    
    {{-- Top Row: Breadcrumb, Class Badges & Auto-Sync --}}
    <div class="flex flex-wrap items-center justify-between gap-2.5 pb-3 border-b border-slate-100">
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
            <a href="{{ route('student.dashboard') }}"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] sm:text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition shadow-2xs">
                <span>←</span>
                <span>Dashboard</span>
            </a>
            <span class="text-slate-300 text-xs">/</span>
            <a href="{{ $classSubjectModulesUrl }}"
               title="Kembali ke Daftar Modul Kelas {{ $classNameText }}"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] sm:text-xs font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200/60 transition shadow-2xs">
                <span>🏫</span>
                <span class="truncate max-w-[140px] sm:max-w-none">{{ $classNameText }}</span>
            </a>
            @if(!empty($module->subject?->name))
                <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">
                    <span>📚</span>
                    <span>{{ $module->subject->name }}</span>
                </span>
            @endif
            @if(!empty($module->semester_badge))
                <span class="hidden md:inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-extrabold border {{ $module->semester_badge['color'] }}">
                    <span>{{ $module->semester_badge['icon'] }}</span>
                    <span>{{ $module->semester_badge['short'] }}</span>
                </span>
            @endif
        </div>

        {{-- Auto-Sync Button --}}
        <button type="button"
                @click="silentReload()"
                :disabled="isReloading"
                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] sm:text-xs font-bold bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 hover:text-indigo-600 shadow-2xs transition cursor-pointer"
                title="Sinkronkan pembaruan modul dari guru tanpa reload halaman">
            <svg class="w-3.5 h-3.5 text-indigo-500" :class="{ 'animate-spin': isReloading }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            <span class="hidden xs:inline" x-text="isReloading ? 'Menyinkronkan...' : 'Auto-Sync'"></span>
        </button>
    </div>

    {{-- Main Row: Title & Action Block --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        {{-- Title & Module Info --}}
        <div class="space-y-1.5 flex-1 min-w-0">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-snug">
                {{ $module->title }}
            </h1>
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 font-medium">
                @if(!empty($module->teacher?->name))
                    <span>Guru: <strong class="text-slate-700 font-semibold">{{ $module->teacher->name }}</strong></span>
                    <span>•</span>
                @endif
                <span>Diperbarui: {{ $module->updated_at->translatedFormat('d M Y') }}</span>
                @if($module->is_active)
                    <span>•</span>
                    <span class="inline-flex items-center gap-1 text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 text-[10px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Materi Aktif di Kelas</span>
                    </span>
                @endif
            </div>
        </div>

        {{-- Progress Bar & Mode Switcher Dock --}}
        <div class="w-full lg:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3 bg-slate-50 border border-slate-200/80 p-3 sm:p-4 rounded-2xl shrink-0">
            
            {{-- Progres Belajar Siswa --}}
            <div class="min-w-0 sm:min-w-[170px] space-y-1.5">
                <div class="flex justify-between items-center text-xs font-bold">
                    <span class="text-slate-500 uppercase tracking-wider text-[10px]">Kemajuan Belajar</span>
                    <span :class="computedProgressPercent >= 100 ? 'text-emerald-600' : 'text-indigo-600'"
                          class="{{ $progressPercent >= 100 ? 'text-emerald-600' : 'text-indigo-600' }} font-extrabold text-[11px]"
                          x-text="computedCompletedTasks + '/' + totalActiveComps + ' (' + computedProgressPercent + '%)'">
                        {{ $completedTasks }}/{{ $totalActive }} ({{ $progressPercent }}%)
                    </span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-indigo-500 to-teal-500' }}"
                         :class="computedProgressPercent >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-indigo-500 to-teal-500'"
                         :style="'width: ' + computedProgressPercent + '%'"
                         style="width: {{ $progressPercent }}%"></div>
                </div>
            </div>

            {{-- Segmented Mode Toggle (Detail Modul vs Mode Belajar) --}}
            <div class="grid grid-cols-2 sm:flex sm:items-center gap-1.5 p-1 rounded-xl bg-slate-200/70 border border-slate-300/60">
                <button type="button"
                        @click="viewMode = 'overview'"
                        :class="viewMode === 'overview' ? 'bg-white text-indigo-900 shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                        class="px-3 py-2 rounded-lg text-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>📋</span>
                    <span>Detail Modul</span>
                </button>
                <button type="button"
                        @click="startLearning()"
                        :class="viewMode === 'learn' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25 font-extrabold' : 'text-slate-600 hover:text-indigo-600 font-semibold'"
                        class="px-3.5 py-2 rounded-lg text-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>🚀</span>
                    <span>Mode Belajar</span>
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
