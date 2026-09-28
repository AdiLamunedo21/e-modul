@extends('layouts.teacher.dashboardteacher')

@section('title', 'Manajer Modul — Teacher Workspace')
@section('page-title', 'Manajer Modul')

@section('content')

<div x-data="moduleManagerApp({
        counts: {
            all: {{ $counts['all'] }},
            published: {{ $counts['published'] }},
            draft: {{ $counts['draft'] }},
            closed: {{ $counts['closed'] }}
        },
        totalModules: {{ $modules->count() }}
    })">

{{-- ══ Header Halaman ══ --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajer E-Modul</h1>
            <button type="button"
                    @click="silentReload()"
                    :disabled="isReloading"
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-white border border-slate-200 text-[11px] font-bold text-slate-600 hover:text-blue-600 hover:border-blue-200 shadow-sm transition-all cursor-pointer"
                    title="Perbarui data modul di latar belakang tanpa reload">
                <svg class="w-3.5 h-3.5 text-blue-500" :class="{ 'animate-spin': isReloading }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span x-text="isReloading ? 'Menyinkronkan...' : 'Auto-Sync Aktif'"></span>
            </button>
        </div>
        <p class="mt-1 text-sm text-slate-500">Seluruh riwayat E-Modul yang pernah Anda buat. Perubahan status modul diperbarui secara langsung tanpa efek refresh.</p>
    </div>
    <a href="{{ route('teacher.modules.create') }}"
       class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition-all shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Buat Modul Baru
    </a>
</div>

{{-- Flash Messages (Pesan status modul tampil mengambang via pop up iklan 5 detik, pesan lain tetap tampil inline) --}}
@php
    $isStatusPopupActive = session('success') && (
        str_contains(session('success'), 'Modul berhasil dipublikasikan') ||
        str_contains(session('success'), 'Modul ditutup dan tidak bisa diakses') ||
        str_contains(session('success'), 'status Draft')
    );
@endphp
@if (session('success') && !$isStatusPopupActive)
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
@endif

{{-- ══ Subject Switcher (Pilihan Mata Pelajaran Tanggung Jawab Guru) ══ --}}
@if($teacherSubjects->isNotEmpty())
    <div class="mb-6 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-2xl p-4 sm:p-5 text-white shadow-md border border-slate-700/50">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                <h2 class="text-xs font-black uppercase tracking-wider text-slate-300">
                    Mata Pelajaran Tanggung Jawab Mengajar ({{ $teacherSubjects->count() }})
                </h2>
            </div>
            <span class="text-[11px] text-slate-400">Pilih mata pelajaran untuk memfilter daftar modul:</span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Tab: Semua Mapel --}}
            <a href="{{ route('teacher.modules.index', array_filter(['status' => request('status'), 'search' => request('search')])) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all
                   {{ !$selectedSubjectId 
                       ? 'bg-blue-600 text-white shadow-md shadow-blue-600/40 ring-2 ring-white/20' 
                       : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700 hover:text-white border border-slate-700' }}">
                <span>📚 Semua Mata Pelajaran</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ !$selectedSubjectId ? 'bg-white/20 text-white' : 'bg-slate-700 text-slate-300' }}">
                    {{ $totalAllSubjects }}
                </span>
            </a>

            {{-- Tabs: Mapel Guru --}}
            @foreach($teacherSubjects as $subject)
                @php
                    $isActive = $selectedSubjectId === $subject->id;
                    $subCount = $subjectCounts[$subject->id] ?? 0;
                @endphp
                <a href="{{ route('teacher.modules.index', array_filter(['subject_id' => $subject->id, 'status' => request('status'), 'search' => request('search')])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all
                       {{ $isActive 
                           ? 'bg-blue-600 text-white shadow-md shadow-blue-600/40 ring-2 ring-white/20' 
                           : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700 hover:text-white border border-slate-700' }}">
                    <span>{{ $subject->icon }} {{ $subject->name }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-700 text-slate-300' }}">
                        {{ $subCount }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endif

{{-- ══ Filter Tabs Status & Semester ══ --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
    {{-- Status Tabs --}}
    <div class="flex items-center gap-1 bg-white border border-slate-200 p-1 rounded-2xl shadow-sm overflow-x-auto self-start">
        @php
            $activeStatus = request('status', '');
            $tabs = [
                ''          => ['label' => 'Semua',   'key' => 'all'],
                'published' => ['label' => 'Terbit',  'key' => 'published'],
                'draft'     => ['label' => 'Draf',    'key' => 'draft'],
                'closed'    => ['label' => 'Ditutup', 'key' => 'closed'],
            ];
        @endphp
        @foreach ($tabs as $value => $t)
            @php
                $params = array_filter([
                    'status'     => $value,
                    'subject_id' => $selectedSubjectId,
                    'semester'   => $selectedSemester,
                    'search'     => request('search'),
                ]);
            @endphp
            <a href="{{ route('teacher.modules.index', $params) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap
                   {{ $activeStatus === $value
                       ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30'
                       : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <span>{{ $t['label'] }}</span>
                <span class="ml-1 opacity-90">(<span x-text="counts.{{ $t['key'] }}">{{ $counts[$t['key']] }}</span>)</span>
            </a>
        @endforeach
    </div>

    {{-- Semester Filter Switcher --}}
    <div class="flex items-center gap-1 bg-white border border-slate-200 p-1 rounded-2xl shadow-sm self-start sm:self-auto">
        <a href="{{ route('teacher.modules.index', array_filter(['status' => request('status'), 'subject_id' => $selectedSubjectId, 'search' => request('search')])) }}"
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ empty($selectedSemester) ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900' }}">
            Semua Semester
        </a>
        <a href="{{ route('teacher.modules.index', array_filter(['status' => request('status'), 'subject_id' => $selectedSubjectId, 'semester' => '1', 'search' => request('search')])) }}"
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $selectedSemester === '1' ? 'bg-amber-600 text-white shadow-sm' : 'text-amber-800 hover:bg-amber-50' }}">
            S1 Ganjil
        </a>
        <a href="{{ route('teacher.modules.index', array_filter(['status' => request('status'), 'subject_id' => $selectedSubjectId, 'semester' => '2', 'search' => request('search')])) }}"
           class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ $selectedSemester === '2' ? 'bg-cyan-600 text-white shadow-sm' : 'text-cyan-800 hover:bg-cyan-50' }}">
            S2 Genap
        </a>
    </div>
</div>

{{-- ══ Form Pencarian E-Modul (Di Bawah Filter Tabs) ══ --}}
<div class="mb-6">
    <form method="GET" action="{{ route('teacher.modules.index') }}" class="flex items-center gap-3">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        @if($selectedSubjectId)
            <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
        @endif
        @if($selectedSemester)
            <input type="hidden" name="semester" value="{{ $selectedSemester }}">
        @endif
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari judul modul, rombel kelas, atau nama mata pelajaran..."
                   class="w-full pl-10 pr-10 py-3 text-xs sm:text-sm bg-white border border-slate-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-800 shadow-sm transition-all placeholder:text-slate-400">
            @if(request('search'))
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <a href="{{ route('teacher.modules.index', array_filter(['status' => request('status'), 'subject_id' => $selectedSubjectId])) }}"
                       class="text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-100 transition"
                       title="Hapus pencarian">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
        <button type="submit"
                class="inline-flex items-center gap-2 px-5 sm:px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-blue-600/20 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <span>Cari Modul</span>
        </button>
    </form>
</div>

{{-- ══ Module List ══ --}}
@if ($modules->isEmpty())
    <div class="rounded-2xl bg-white border border-dashed border-slate-300 p-16 text-center">
        <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-2">
            {{ request('search') ? 'Modul Tidak Ditemukan' : 'Belum Ada Modul' }}
        </h3>
        <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">
            @if(request('search'))
                Tidak ditemukan modul dengan kata kunci "<strong>{{ request('search') }}</strong>". Silakan coba kata kunci lain.
            @elseif($selectedSubjectId || $activeStatus)
                Tidak ada modul yang cocok dengan filter yang dipilih.
            @else
                Anda belum membuat E-Modul apapun. Mulai rakit modul pertama dengan <em>Module Builder</em>.
            @endif
        </p>
        <div class="flex items-center justify-center gap-3">
            @if(request('search') || $selectedSubjectId || $activeStatus)
                <a href="{{ route('teacher.modules.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">
                    Reset Filter & Pencarian
                </a>
            @endif
            <a href="{{ route('teacher.modules.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition-all">
                Buat Modul Pertama
            </a>
        </div>
    </div>
@else
    <div class="space-y-4" id="modules-container">
        @foreach ($modules as $module)
            @php
                $badge    = $module->statusLabel();
                $comps    = $module->activeComponents();
                $class    = $module->schoolClass;
                $subject  = $module->subject;
            @endphp
            <div x-data="moduleItem({
                     id: {{ $module->id }},
                     status: '{{ $module->status }}',
                     isShared: {{ $module->is_shared ? 'true' : 'false' }},
                     cloneCount: {{ (int) $module->clone_count }},
                     title: '{{ addslashes($module->title) }}',
                     statusUrl: '{{ route('teacher.modules.status', $module) }}',
                     shareUrl: '{{ route('teacher.modules.toggle-share', $module) }}',
                     deleteUrl: '{{ route('teacher.modules.destroy', $module) }}'
                 })"
                 x-show="!deleted"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100 max-h-[500px]"
                 x-transition:leave-end="opacity-0 scale-95 max-h-0 py-0 my-0 overflow-hidden"
                 class="rounded-2xl bg-white border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                        {{-- ── Informasi Modul ── --}}
                        <div class="flex-1 space-y-3 min-w-0">
                            {{-- Status, Subject & Kelas --}}
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide border transition-colors duration-200"
                                      :class="badgeColor">
                                    <span class="w-1.5 h-1.5 rounded-full transition-colors duration-200"
                                          :class="dotColor"></span>
                                    <span x-text="badgeLabel"></span>
                                </span>
                                @if($subject)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $subject->badgeClasses() }}">
                                        <span>{{ $subject->icon }}</span>
                                        <span>{{ $subject->name }}</span>
                                    </span>
                                @endif
                                <span x-show="isShared"
                                      x-transition
                                      class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200"
                                      title="Dibagikan di Library Modul">
                                    <span>🌐 Di Library</span>
                                    @if($module->clone_count > 0)
                                        <span class="text-indigo-500">({{ $module->clone_count }}x)</span>
                                    @endif
                                </span>
                                @if($class)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                        Kelas {{ $class->grade }} {{ $class->major_name }}
                                    </span>
                                @endif
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $module->semester_badge['color'] }}">
                                    {{ $module->semester_badge['short'] }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    Dibuat {{ $module->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Judul Modul --}}
                            <a href="{{ route('teacher.modules.show', $module) }}"
                               class="block text-xl font-bold text-slate-900 hover:text-blue-600 transition-colors leading-snug truncate max-w-2xl">
                                {{ $module->title }}
                            </a>

                            {{-- Cloned from note if any --}}
                            @if($module->clonedFrom)
                                <p class="text-[11px] text-slate-400 flex items-center gap-1">
                                    <span>🌱</span>
                                    <span>Disalin dari karya <strong>{{ $module->clonedFrom->teacher->name ?? 'Pendidik' }}</strong></span>
                                </p>
                            @endif

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

                        {{-- ── Action Buttons (Asinkron / Tanpa Refresh Layar) ── --}}
                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            {{-- Share to Library Quick Toggle --}}
                            <button type="button"
                                    @click="toggleShare()"
                                    :disabled="isUpdatingShare"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer disabled:opacity-50"
                                    :class="isShared ? 'text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100' : 'text-slate-600 bg-slate-50 border border-slate-200 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200'"
                                    :title="isShared ? 'Modul aktif di Library sekolah. Klik untuk menarik.' : 'Bagikan ke Library Modul'">
                                <template x-if="isUpdatingShare">
                                    <svg class="w-3.5 h-3.5 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </template>
                                <template x-if="!isUpdatingShare && isShared">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                                </template>
                                <template x-if="!isUpdatingShare && !isShared">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                                </template>
                                <span x-text="isShared ? 'Di Library' : 'Bagi ke Library'"></span>
                            </button>

                            {{-- Lihat Detail --}}
                            <a href="{{ route('teacher.modules.show', $module) }}"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Detail
                            </a>

                            {{-- Publish / Unpublish Toggle (Asinkron / Tanpa Refresh) --}}
                            <button type="button"
                                    @click="changeStatus(nextStatus)"
                                    :disabled="isUpdatingStatus"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer disabled:opacity-50"
                                    :class="{
                                        'bg-emerald-600 hover:bg-emerald-700 text-white shadow': status === 'draft',
                                        'bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 shadow-sm': status === 'published',
                                        'bg-amber-50 border border-amber-200 hover:bg-amber-100 text-amber-700': status === 'closed'
                                    }">
                                <template x-if="isUpdatingStatus">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </template>
                                <template x-if="!isUpdatingStatus && status === 'draft'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                </template>
                                <template x-if="!isUpdatingStatus && status === 'published'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25-2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                </template>
                                <template x-if="!isUpdatingStatus && status === 'closed'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                </template>
                                <span x-text="statusButtonText"></span>
                            </button>

                            {{-- Hapus (Membuka Modal Konfirmasi Asinkron) --}}
                            <button type="button"
                                    @click="openDeleteModal({{ $module->id }}, '{{ addslashes($module->title) }}', '{{ route('teacher.modules.destroy', $module) }}', status)"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 rounded-xl transition-all shadow-sm cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Fallback Empty State jika seluruh modul di halaman ini terhapus secara dinamis --}}
    <div x-show="visibleModuleCount === 0" x-cloak class="rounded-2xl bg-white border border-dashed border-slate-300 p-16 text-center mt-4">
        <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-2">Semua Modul Telah Dihapus</h3>
        <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">Tidak ada modul yang tersisa pada tampilan ini. Anda dapat membuat E-Modul baru.</p>
        <a href="{{ route('teacher.modules.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow transition-all">
            Buat Modul Baru
        </a>
    </div>

    {{-- Pagination --}}
    @if ($modules->hasPages())
        <div class="mt-6">
            {{ $modules->links() }}
        </div>
    @endif
@endif

{{-- Include Delete Confirmation Modal --}}
@include('pages.teacher.modules.partials.delete-modal')

</div>
@endsection

@push('scripts')
<script>
function moduleManagerApp(config) {
    return {
        deleteModalOpen: false,
        deleteId: null,
        deleteTitle: '',
        deleteUrl: '',
        deleteStatus: '',
        isDeleting: false,
        isReloading: false,
        counts: config.counts || { all: 0, published: 0, draft: 0, closed: 0 },
        visibleModuleCount: config.totalModules || 0,

        init() {
            window.addEventListener('module-status-updated', (e) => {
                const { oldStatus, newStatus } = e.detail;
                if (this.counts[oldStatus] > 0) {
                    this.counts[oldStatus]--;
                }
                if (typeof this.counts[newStatus] !== 'undefined') {
                    this.counts[newStatus]++;
                }
            });
        },

        openDeleteModal(id, title, url, status) {
            this.deleteId = id;
            this.deleteTitle = title;
            this.deleteUrl = url;
            this.deleteStatus = status;
            this.deleteModalOpen = true;
        },

        async confirmDelete() {
            if (this.isDeleting || !this.deleteUrl) return;
            this.isDeleting = true;

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(this.deleteUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });

                const data = await res.json();
                if (data.success) {
                    const deletedId = this.deleteId;
                    const deletedStatus = this.deleteStatus;
                    this.deleteModalOpen = false;

                    // Perbarui hitungan
                    if (this.counts.all > 0) this.counts.all--;
                    if (this.counts[deletedStatus] > 0) this.counts[deletedStatus]--;
                    this.visibleModuleCount--;

                    // Trigger event untuk menghapus card dengan animasi
                    window.dispatchEvent(new CustomEvent('module-deleted', {
                        detail: { id: deletedId, status: deletedStatus }
                    }));

                    // Pop up iklan mengambang 5 detik
                    window.dispatchEvent(new CustomEvent('show-status-popup', {
                        detail: { message: data.message || 'Modul berhasil dihapus.', icon: '🗑️' }
                    }));
                } else {
                    alert(data.message || 'Gagal menghapus modul.');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan saat menghapus modul.');
            } finally {
                this.isDeleting = false;
            }
        },

        async silentReload() {
            if (this.isReloading) return;
            this.isReloading = true;
            try {
                const res = await fetch(window.location.href, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newContainer = doc.querySelector('#modules-container');
                const curContainer = document.querySelector('#modules-container');
                if (newContainer && curContainer) {
                    curContainer.innerHTML = newContainer.innerHTML;
                    if (window.Alpine) {
                        window.Alpine.initTree(curContainer);
                    }
                }
                window.dispatchEvent(new CustomEvent('show-status-popup', {
                    detail: { message: 'Data modul berhasil disinkronkan secara real-time!', icon: '🔄' }
                }));
            } catch (e) {
                console.error(e);
            } finally {
                this.isReloading = false;
            }
        }
    };
}

function moduleItem(config) {
    return {
        id: config.id,
        status: config.status,
        isShared: config.isShared,
        cloneCount: config.cloneCount,
        title: config.title,
        statusUrl: config.statusUrl,
        shareUrl: config.shareUrl,
        deleteUrl: config.deleteUrl,
        isUpdatingStatus: false,
        isUpdatingShare: false,
        deleted: false,

        init() {
            window.addEventListener('module-deleted', (e) => {
                if (e.detail && e.detail.id === this.id) {
                    this.deleted = true;
                }
            });
        },

        get badgeColor() {
            if (this.status === 'published') return 'bg-emerald-100 text-emerald-800 border-emerald-200';
            if (this.status === 'closed') return 'bg-slate-100 text-slate-600 border-slate-200';
            return 'bg-amber-100 text-amber-800 border-amber-200';
        },

        get dotColor() {
            if (this.status === 'published') return 'bg-emerald-500';
            if (this.status === 'closed') return 'bg-slate-400';
            return 'bg-amber-500';
        },

        get badgeLabel() {
            if (this.status === 'published') return 'Published';
            if (this.status === 'closed') return 'Closed';
            return 'Draft';
        },

        get nextStatus() {
            if (this.status === 'draft') return 'published';
            if (this.status === 'published') return 'closed';
            return 'published';
        },

        get statusButtonText() {
            if (this.status === 'draft') return 'Publish';
            if (this.status === 'published') return 'Tutup Modul';
            return 'Buka Kembali';
        },

        async changeStatus(targetStatus) {
            if (this.isUpdatingStatus) return;
            this.isUpdatingStatus = true;
            const oldStatus = this.status;

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(this.statusUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        _method: 'PATCH',
                        status: targetStatus
                    })
                });

                const data = await res.json();
                if (data.success) {
                    this.status = targetStatus;

                    // Update parent counter
                    window.dispatchEvent(new CustomEvent('module-status-updated', {
                        detail: { oldStatus, newStatus: targetStatus }
                    }));

                    // Pop up iklan mengambang 5 detik
                    window.dispatchEvent(new CustomEvent('show-status-popup', {
                        detail: { message: data.message }
                    }));
                } else {
                    alert(data.message || 'Gagal mengubah status modul.');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi.');
            } finally {
                this.isUpdatingStatus = false;
            }
        },

        async toggleShare() {
            if (this.isUpdatingShare) return;
            this.isUpdatingShare = true;

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(this.shareUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await res.json();
                if (data.success) {
                    this.isShared = data.is_shared;

                    // Pop up iklan mengambang 5 detik
                    window.dispatchEvent(new CustomEvent('show-status-popup', {
                        detail: { message: data.message }
                    }));
                } else {
                    alert(data.message || 'Gagal mengubah status library.');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi.');
            } finally {
                this.isUpdatingShare = false;
            }
        }
    };
}
</script>
@endpush
