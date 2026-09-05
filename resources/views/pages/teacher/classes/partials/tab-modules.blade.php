@php
    $teacherModulesJson = $teacherModules->map(function($m) {
        return [
            'id'           => $m->id,
            'title'        => $m->title,
            'subject_name' => $m->subject?->name ?? '',
            'subject_code' => $m->subject?->code ?? '',
            'description'  => $m->description ?? '',
            'semester'     => (string) ($m->semester ?? 1),
            'status'       => $m->status,
            'is_active'    => (bool) $m->is_active,
        ];
    })->values()->toArray();
@endphp

{{-- ── TAB CONTENT 2: PORTOFOLIO MODUL GURU (FILTER, PAGINATION & DUAL VIEW GRID/LIST) ── --}}
<div x-show="activeTab === 'modules'"
     id="modules-section"
     x-data="{
         searchKeyword: '',
         selectedSemester: 'all',
         selectedStatus: 'all',
         explorerView: 'grid',
         currentPage: 1,
         perPage: 9,
         modules: {{ json_encode($teacherModulesJson) }},
         init() {
             this.$watch('searchKeyword', () => { this.currentPage = 1; });
             this.$watch('selectedSemester', () => { this.currentPage = 1; });
             this.$watch('selectedStatus', () => { this.currentPage = 1; });
         },
         matchesModule(mod) {
             // 1. Filter Semester
             if (this.selectedSemester === '1' && String(mod.semester) !== '1') return false;
             if (this.selectedSemester === '2' && String(mod.semester) !== '2') return false;

             // 2. Filter Status Modul
             if (this.selectedStatus === 'active_in_class' && !mod.is_active) return false;
             if (this.selectedStatus === 'published' && mod.status !== 'published') return false;
             if (this.selectedStatus === 'draft' && mod.status !== 'draft') return false;
             if (this.selectedStatus === 'closed' && mod.status !== 'closed' && mod.status !== 'archived') return false;

             // 3. Live Search Keyword
             if (this.searchKeyword.trim() !== '') {
                 const kw = this.searchKeyword.toLowerCase().trim();
                 const titleMatch = (mod.title || '').toLowerCase().includes(kw);
                 const subjMatch = (mod.subject_name || '').toLowerCase().includes(kw) || (mod.subject_code || '').toLowerCase().includes(kw);
                 const descMatch = (mod.description || '').toLowerCase().includes(kw);
                 if (!titleMatch && !subjMatch && !descMatch) return false;
             }

             return true;
         },
         get filteredModules() {
             return this.modules.filter(m => this.matchesModule(m));
         },
         get totalPages() {
             return Math.ceil(this.filteredModules.length / this.perPage) || 1;
         },
         get paginatedModules() {
             const start = (this.currentPage - 1) * this.perPage;
             return this.filteredModules.slice(start, start + this.perPage);
         },
         get visibleIdSet() {
             return new Set(this.paginatedModules.map(m => m.id));
         },
         isModuleVisible(id) {
             return this.visibleIdSet.has(id);
         },
         get totalVisible() {
             return this.filteredModules.length;
         },
         get isFiltered() {
             return (this.selectedSemester !== 'all' || this.selectedStatus !== 'all' || this.searchKeyword.trim() !== '');
         },
         resetFilters() {
             this.selectedSemester = 'all';
             this.selectedStatus = 'all';
             this.searchKeyword = '';
             this.currentPage = 1;
         },
         goToPage(p) {
             if (p >= 1 && p <= this.totalPages) {
                 this.currentPage = p;
                 const el = document.getElementById('modules-toolbar-anchor');
                 if (el) {
                     el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                 }
             }
         },
         prevPage() {
             if (this.currentPage > 1) {
                 this.goToPage(this.currentPage - 1);
             }
         },
         nextPage() {
             if (this.currentPage < this.totalPages) {
                 this.goToPage(this.currentPage + 1);
             }
         },
         get pagesToDisplay() {
             const total = this.totalPages;
             const current = this.currentPage;
             if (total <= 7) {
                 return Array.from({ length: total }, (_, i) => i + 1);
             }
             if (current <= 4) {
                 return [1, 2, 3, 4, 5, '...', total];
             }
             if (current >= total - 3) {
                 return [1, '...', total - 4, total - 3, total - 2, total - 1, total];
             }
             return [1, '...', current - 1, current, current + 1, '...', total];
         }
     }"
     class="p-6 space-y-6">
    
    {{-- Header & Toolbar Modul --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5 flex-wrap">
                <h3 class="text-base font-black text-slate-900">Modul Pembelajaran di Kelas Ini</h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                    {{ $teacherModules->count() }} Modul
                </span>
                @php
                    $activeCountInClass = $teacherModules->where('is_active', true)->count();
                @endphp
                @if($activeCountInClass > 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300 animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        <span>{{ $activeCountInClass }} Sedang Dibahas di Kelas</span>
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500">
                Kelola materi dan aktifkan modul yang sedang diajarkan di kelas agar siswa langsung mengetahuinya di menu <strong>Sedang Dikerjakan</strong>.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button @click="importModalOpen = true"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Import dari Kelas Lain</span>
            </button>
            <a href="{{ route('teacher.modules.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                <span>+ Buat Modul Baru</span>
            </a>
        </div>
    </div>

    @if($teacherModules->isEmpty())
        <div class="rounded-3xl bg-slate-50 p-12 text-center border border-slate-200 space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto text-3xl font-black">
                📚
            </div>
            <div class="max-w-md mx-auto space-y-1">
                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Modul untuk Kelas Ini</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda dapat mengimpor modul yang sudah pernah dibuat di kelas lain atau membuat modul baru dari awal untuk rombel <strong>{{ $class->full_name }}</strong>.
                </p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button @click="importModalOpen = true"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                    <span>📥 Import dari Kelas Lain</span>
                </button>
                <a href="{{ route('teacher.modules.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition-all cursor-pointer">
                    <span>Buat Modul Baru</span>
                </a>
            </div>
        </div>
    @else
        {{-- ═══ FILTER & LIVE SEARCH BAR (DENGAN DEBOUNCE 250ms UNTUK PERFORMA TINGGI) ═══ --}}
        <div id="modules-toolbar-anchor" class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">

                {{-- Live Search Input (Debounced) --}}
                <div class="relative flex-1 min-w-[220px] flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text"
                           x-model.debounce.250ms="searchKeyword"
                           placeholder="Cari judul modul, mapel, atau materi pembelajaran..."
                           class="w-full pl-10 pr-9 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400 font-medium">
                    
                    {{-- Clear Search Button --}}
                    <button type="button"
                            x-show="searchKeyword && searchKeyword.length > 0"
                            x-cloak
                            @click="searchKeyword = ''"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <span class="w-4 h-4 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-[9px] font-bold text-slate-600">✕</span>
                    </button>
                </div>

                {{-- Filter Controls --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    {{-- Dropdown Pilihan Semester --}}
                    <div class="w-full sm:w-44">
                        <select x-model="selectedSemester"
                                class="w-full py-2.5 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-700 transition-all cursor-pointer">
                            <option value="all">Semua Semester</option>
                            <option value="1">Semester 1 (Ganjil)</option>
                            <option value="2">Semester 2 (Genap)</option>
                        </select>
                    </div>

                    {{-- Dropdown Status Modul --}}
                    <div class="w-full sm:w-48">
                        <select x-model="selectedStatus"
                                class="w-full py-2.5 px-3 text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-700 transition-all cursor-pointer">
                            <option value="all">Semua Status Modul</option>
                            <option value="active_in_class">🟢 Sedang Dibahas di Kelas</option>
                            <option value="published">Terbit (Published)</option>
                            <option value="draft">Draf (Draft)</option>
                            <option value="closed">Ditutup (Closed)</option>
                        </select>
                    </div>

                    {{-- View Toggle (Grid / List) --}}
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
                        <button type="button"
                                @click="explorerView = 'grid'"
                                :class="explorerView === 'grid' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                                title="Tampilan Grid Kartu"
                                class="p-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button type="button"
                                @click="explorerView = 'list'"
                                :class="explorerView === 'list' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                                title="Tampilan Daftar Rinci"
                                class="p-1.5 rounded-lg text-xs transition cursor-pointer flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Reset Filter Button --}}
                    <button type="button"
                            x-show="isFiltered"
                            x-cloak
                            @click="resetFilters()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition-all cursor-pointer"
                            title="Reset Semua Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Reset Filter</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Counter & Active Filter Indicators --}}
        <div class="flex items-center justify-between gap-3 px-1">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-bold text-slate-600"
                      x-text="filteredModules.length > perPage ? 'Menampilkan ' + (((currentPage - 1) * perPage) + 1) + '–' + Math.min(currentPage * perPage, filteredModules.length) + ' dari ' + filteredModules.length + ' modul (Halaman ' + currentPage + ' dari ' + totalPages + ')' : 'Menampilkan ' + filteredModules.length + ' dari ' + modules.length + ' modul'">
                </span>
            </div>

            <div x-show="isFiltered" x-cloak>
                <button type="button"
                        @click="resetFilters()"
                        class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline cursor-pointer">
                    Tampilkan Semua Modul
                </button>
            </div>
        </div>

        {{-- ═══ VIEW MODE 1: GRID KARTU ═══ --}}
        <div x-show="explorerView === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($teacherModules as $idx => $mod)
                @php
                    $activeComps = $mod->activeComponents();
                    $stats = $mod->gradingStats();
                @endphp
                <div x-show="isModuleVisible({{ $mod->id }})"
                     x-transition:enter="transition-opacity ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="group relative bg-white rounded-3xl border transition-all duration-300 flex flex-col justify-between overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1
                            {{ $mod->is_active ? 'border-emerald-300 ring-2 ring-emerald-500/20 shadow-md shadow-emerald-500/10' : 'border-slate-200/90 hover:border-slate-300' }}">

                    {{-- Banner Status Aktif Pembelajaran di Kelas --}}
                    @if($mod->is_active)
                        <div class="bg-emerald-50 border-b border-emerald-200/90 px-4 py-2.5 text-xs font-bold flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-600"></span>
                                </span>
                                <span class="uppercase tracking-wider text-[11px] font-black text-emerald-900">Sedang Dibahas di Kelas</span>
                            </div>
                            <span class="text-[10px] font-black bg-emerald-700 text-white px-2.5 py-0.5 rounded-full shadow-2xs">Aktif Siswa</span>
                        </div>
                    @else
                        <div class="bg-slate-50 border-b border-slate-100 px-4 py-1.5 text-[11px] font-medium text-slate-400 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span>Modul Pembelajaran</span>
                            </span>
                            <span class="text-[10px] text-slate-400 font-semibold">Siap Diajarkan</span>
                        </div>
                    @endif

                    {{-- Bagian Isi Kartu Modul --}}
                    <div class="p-5 sm:p-6 space-y-4 flex-1">
                        {{-- Baris Pill Badges: Mapel, Semester, Status Publikasi --}}
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if($mod->subject)
                                    <span class="px-2.5 py-1 rounded-xl text-[11px] font-black {{ $mod->subject->badgeClasses() }} border shadow-2xs">
                                        {{ $mod->subject->name }}
                                    </span>
                                @endif
                                <span class="px-2 py-1 rounded-xl text-[11px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $mod->semester_badge['icon'] }} {{ $mod->semester_badge['label'] }}
                                </span>
                            </div>

                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold
                                {{ $mod->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($mod->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700') }}">
                                {{ ucfirst($mod->status) }}
                            </span>
                        </div>

                        {{-- Judul & Deskripsi --}}
                        <div>
                            <a href="{{ route('teacher.modules.show', $mod) }}"
                               class="text-base sm:text-lg font-black text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-2 leading-snug">
                                {{ $mod->title }}
                            </a>
                            @if(!empty($mod->description))
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mt-1.5">
                                    {{ $mod->description }}
                                </p>
                            @endif
                        </div>

                        {{-- Komponen Penilaian & Aktivitas Aktif --}}
                        <div class="space-y-1.5 pt-1">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Komponen Aktif ({{ count($activeComps) }})</p>
                            <div class="flex flex-wrap gap-1">
                                @forelse($activeComps as $comp)
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/60">
                                        {{ $comp }}
                                    </span>
                                @empty
                                    <span class="text-[11px] text-slate-400 italic">Belum ada komponen aktif</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- Metrik Respon Siswa --}}
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                            <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Submisi</p>
                                <p class="text-base font-black text-slate-800 mt-0.5">{{ $stats['submitted_count'] }}</p>
                            </div>
                            <div class="p-2.5 rounded-2xl bg-emerald-50/50 border border-emerald-100/60">
                                <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider">Dinilai</p>
                                <p class="text-base font-black text-emerald-700 mt-0.5">{{ $stats['graded_count'] }}</p>
                            </div>
                            <div class="p-2.5 rounded-2xl bg-blue-50/50 border border-blue-100/60">
                                <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Rata-rata</p>
                                <p class="text-base font-black text-blue-700 mt-0.5">{{ $stats['avg_score'] > 0 ? $stats['avg_score'] : '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Kartu: Fitur Aktifkan Modul & Action Buttons --}}
                    <div class="p-5 sm:p-6 pt-0 space-y-2.5 border-t border-slate-100 bg-slate-50/50">
                        {{-- ══ TOMBOL UTAMA: FITUR AKTIFKAN / NONAKTIFKAN MODUL DI KELAS ══ --}}
                        @if($mod->is_active)
                            <form action="{{ route('teacher.classes.modules.toggle-active', [$class, $mod]) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2.5 px-4 rounded-2xl bg-emerald-100 hover:bg-rose-100 text-emerald-800 hover:text-rose-800 border border-emerald-300 hover:border-rose-300 font-extrabold text-xs transition-all flex items-center justify-center gap-2 group/btn shadow-xs cursor-pointer">
                                    <svg class="w-4 h-4 text-emerald-600 group-hover/btn:text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="group-hover/btn:hidden">✓ Modul Aktif di Kelas Ini</span>
                                    <span class="hidden group-hover/btn:inline">✕ Klik untuk Nonaktifkan</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('teacher.classes.modules.toggle-active', [$class, $mod]) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2.5 px-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-xs transition-all shadow-md shadow-blue-600/20 hover:shadow-lg flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4 text-blue-200 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                    </svg>
                                    <span>Aktifkan Modul untuk Kelas Ini</span>
                                </button>
                            </form>
                        @endif

                        {{-- Tombol Cepat: Builder & Beri Nilai --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('teacher.modules.show', $mod) }}"
                               class="flex-1 text-center py-2 px-3 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition-all shadow-2xs">
                                Builder Modul
                            </a>
                            <a href="{{ route('teacher.grading.show', $mod) }}"
                               class="flex-1 text-center py-2 px-3 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-2xs">
                                Beri Nilai
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ═══ VIEW MODE 2: DAFTAR RINCI (TABLE LIST) ═══ --}}
        <div x-show="explorerView === 'list'" class="rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Modul & Mata Pelajaran</th>
                        <th class="py-3.5 px-4 text-center">Semester & Status</th>
                        <th class="py-3.5 px-4 text-center">Komponen</th>
                        <th class="py-3.5 px-4 text-center">Submisi & Rata-rata</th>
                        <th class="py-3.5 px-4 text-center">Status di Kelas</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($teacherModules as $idx => $mod)
                        @php
                            $activeComps = $mod->activeComponents();
                            $stats = $mod->gradingStats();
                        @endphp
                        <tr x-show="isModuleVisible({{ $mod->id }})"
                            class="hover:bg-slate-50/80 transition-colors group {{ $mod->is_active ? 'bg-emerald-50/30' : '' }}">
                            <td class="py-3.5 px-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 mt-0.5 {{ $mod->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        @if($mod->is_active)
                                            <span class="animate-pulse">🟢</span>
                                        @else
                                            <span>📖</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ route('teacher.modules.show', $mod) }}"
                                               class="font-black text-slate-900 group-hover:text-blue-700 transition-colors text-xs sm:text-sm">
                                                {{ $mod->title }}
                                            </a>
                                            @if($mod->is_active)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                    <span>Sedang Dibahas</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            @if($mod->subject)
                                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $mod->subject->badgeClasses() }} border">
                                                    {{ $mod->subject->name }}
                                                </span>
                                            @endif
                                            @if(!empty($mod->description))
                                                <span class="text-[11px] text-slate-400 truncate max-w-[280px]">
                                                    {{ $mod->description }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $mod->semester_badge['icon'] }} {{ $mod->semester_badge['label'] }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold
                                        {{ $mod->status === 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($mod->status === 'draft' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700') }}">
                                        {{ ucfirst($mod->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80"
                                      title="{{ implode(', ', $activeComps) }}">
                                    {{ count($activeComps) }} Komponen
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="text-xs">
                                    <div class="font-extrabold text-slate-800">
                                        {{ $stats['submitted_count'] }} <span class="text-[10px] font-medium text-slate-400">submisi</span>
                                    </div>
                                    <div class="text-[11px] font-bold {{ $stats['avg_score'] > 0 ? 'text-blue-600' : 'text-slate-400' }}">
                                        Nilai: {{ $stats['avg_score'] > 0 ? $stats['avg_score'] : '-' }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($mod->is_active)
                                    <form action="{{ route('teacher.classes.modules.toggle-active', [$class, $mod]) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                                title="Modul sedang aktif di kelas. Klik untuk nonaktifkan."
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-100 hover:bg-rose-100 text-emerald-800 hover:text-rose-800 border border-emerald-300 hover:border-rose-300 font-extrabold text-[11px] transition-all group/btn cursor-pointer">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 group-hover/btn:bg-rose-600 animate-pulse"></span>
                                            <span class="group-hover/btn:hidden">Aktif di Kelas</span>
                                            <span class="hidden group-hover/btn:inline">Nonaktifkan</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('teacher.classes.modules.toggle-active', [$class, $mod]) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                                title="Aktifkan modul ini untuk rombel {{ $class->full_name }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-600 text-slate-700 hover:text-white border border-slate-200 font-bold text-[11px] transition-all cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                            </svg>
                                            <span>Aktifkan</span>
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('teacher.modules.show', $mod) }}"
                                       title="Builder Modul"
                                       class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition-all shadow-2xs">
                                        Builder
                                    </a>
                                    <a href="{{ route('teacher.grading.show', $mod) }}"
                                       title="Beri Nilai Siswa"
                                       class="px-2.5 py-1.5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-2xs">
                                        Nilai
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ═══ 4. PAGINATION CONTROLS (GRID & LIST) ═══ --}}
        <div x-show="filteredModules.length > perPage"
             x-cloak
             class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-200">
            
            {{-- Pagination Summary --}}
            <div class="text-xs text-slate-500 font-medium">
                Halaman <span class="font-bold text-slate-800" x-text="currentPage"></span> dari <span class="font-bold text-slate-800" x-text="totalPages"></span> &bull; 
                Menampilkan <span class="font-bold text-slate-800" x-text="((currentPage - 1) * perPage) + 1"></span>
                sampai <span class="font-bold text-slate-800" x-text="Math.min(currentPage * perPage, filteredModules.length)"></span>
                dari <span class="font-bold text-slate-800" x-text="filteredModules.length"></span> modul
            </div>

            {{-- Page Navigation Buttons --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                {{-- Previous Button --}}
                <button type="button"
                        @click="prevPage()"
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed bg-slate-50 text-slate-400' : 'bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 shadow-2xs cursor-pointer'"
                        class="inline-flex items-center gap-1 px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </button>

                {{-- Page Numbers (Dynamic with helper) --}}
                <template x-for="(p, pIdx) in pagesToDisplay" :key="pIdx">
                    <div>
                        <template x-if="p === '...'">
                            <span class="px-2 py-1 text-xs text-slate-400 font-bold">...</span>
                        </template>
                        <template x-if="p !== '...'">
                            <button type="button"
                                    @click="goToPage(p)"
                                    :class="currentPage === p ? 'bg-blue-600 text-white font-black shadow-xs' : 'bg-white hover:bg-slate-100 text-slate-700 font-bold border border-slate-200 shadow-2xs hover:text-slate-900'"
                                    class="w-8 h-8 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer"
                                    x-text="p">
                            </button>
                        </template>
                    </div>
                </template>

                {{-- Next Button --}}
                <button type="button"
                        @click="nextPage()"
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed bg-slate-50 text-slate-400' : 'bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 shadow-2xs cursor-pointer'"
                        class="inline-flex items-center gap-1 px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold transition-all">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Filtered Empty State --}}
        <div x-show="totalVisible === 0"
             x-cloak
             class="rounded-3xl bg-slate-50 p-10 text-center border border-slate-200 space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-white text-slate-400 flex items-center justify-center mx-auto text-xl font-bold shadow-2xs border border-slate-200">
                🔍
            </div>
            <div class="max-w-md mx-auto space-y-1">
                <h3 class="text-sm font-extrabold text-slate-800">Tidak Ada Modul yang Sesuai</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tidak ditemukan modul pembelajaran dengan filter atau kata kunci yang Anda masukkan.
                </p>
            </div>
            <div class="pt-1">
                <button type="button"
                        @click="resetFilters()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all shadow-xs cursor-pointer">
                    <span>Reset Filter & Tampilkan Semua</span>
                </button>
            </div>
        </div>
    @endif
</div>
