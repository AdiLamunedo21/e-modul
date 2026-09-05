{{-- ─────────────────────────────────────────────────────────────────── --}}
{{-- ── LEFT COLUMN: ACCORDION SILABUS MODUL (4 Cols on Desktop) ─────── --}}
{{-- ─────────────────────────────────────────────────────────────────── --}}
<div class="lg:col-span-4 space-y-3"
     :class="mobileDrawerOpen ? 'block' : 'hidden lg:block'">

    <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm p-4 sm:p-5 sticky top-6 space-y-4">
        {{-- Header Silabus --}}
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold">📑</span>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Alur Belajar Modul</h3>
                    <p class="text-[11px] text-slate-400">Ikuti materi secara berurutan</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <a href="{{ $classSubjectModulesUrl }}"
                   title="Kembali ke Daftar Modul Kelas {{ $classNameText }}"
                   class="text-[11px] font-bold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-lg transition inline-flex items-center gap-1 cursor-pointer">
                    <span>←</span>
                    <span>Daftar Modul</span>
                </a>
                <button type="button"
                        @click="viewMode = 'overview'"
                        title="Lihat Tampilan Detail Modul"
                        class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition cursor-pointer">
                    Detail 📋
                </button>
            </div>
        </div>

        {{-- Accordion Group 1 to 5 --}}
        <div class="space-y-3">

            {{-- ── ACCORDION 1: BAGIAN AWAL ── --}}
            @php $sec1Pages = array_filter($pagesList, fn($p) => $p['sec'] === 1); @endphp
            @if(!empty($sec1Pages))
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                    <button type="button"
                            @click="toggleSection(1)"
                            class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-lg bg-indigo-100 text-indigo-700 text-[10px] font-black flex items-center justify-center">{{ $secMap[1] ?? 1 }}</span>
                            <span>Bagian Awal</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                             :class="openSections[1] ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openSections[1]" x-collapse class="p-2 pt-0 space-y-1.5">
                        @foreach($sec1Pages as $page)
                            <button type="button"
                                    @click="goToPage('{{ $page['id'] }}')"
                                    :disabled="!isUnlocked('{{ $page['id'] }}')"
                                    :class="{
                                        'sidebar-item-active': activePage === '{{ $page['id'] }}',
                                        'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60 cursor-pointer': activePage !== '{{ $page['id'] }}' && isUnlocked('{{ $page['id'] }}'),
                                        'bg-slate-100/70 text-slate-400 border border-slate-200/40 opacity-60 cursor-not-allowed': !isUnlocked('{{ $page['id'] }}')
                                    }"
                                    class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2">
                                <span class="flex items-center gap-2 truncate">
                                    <span>{{ $page['icon'] }}</span>
                                    <span class="truncate">{{ $page['title'] }}</span>
                                </span>
                                <template x-if="isCompleted('{{ $page['id'] }}')">
                                    <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-black shrink-0">✓</span>
                                </template>
                                <template x-if="!isCompleted('{{ $page['id'] }}') && !isUnlocked('{{ $page['id'] }}')">
                                    <span class="text-[10px] opacity-70">🔒</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── ACCORDION 2: PENDAHULUAN ── --}}
            @php $sec2Pages = array_filter($pagesList, fn($p) => $p['sec'] === 2); @endphp
            @if(!empty($sec2Pages))
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                    <button type="button"
                            @click="toggleSection(2)"
                            class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-lg bg-teal-100 text-teal-700 text-[10px] font-black flex items-center justify-center">{{ $secMap[2] ?? 2 }}</span>
                            <span>Pendahuluan</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                             :class="openSections[2] ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openSections[2]" x-collapse class="p-2 pt-0 space-y-1.5">
                        @foreach($sec2Pages as $page)
                            <button type="button"
                                    @click="goToPage('{{ $page['id'] }}')"
                                    :disabled="!isUnlocked('{{ $page['id'] }}')"
                                    :class="{
                                        'sidebar-item-active': activePage === '{{ $page['id'] }}',
                                        'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60 cursor-pointer': activePage !== '{{ $page['id'] }}' && isUnlocked('{{ $page['id'] }}'),
                                        'bg-slate-100/70 text-slate-400 border border-slate-200/40 opacity-60 cursor-not-allowed': !isUnlocked('{{ $page['id'] }}')
                                    }"
                                    class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2">
                                <span class="flex items-center gap-2 truncate">
                                    <span>{{ $page['icon'] }}</span>
                                    <span class="truncate">{{ $page['title'] }}</span>
                                </span>
                                <template x-if="isCompleted('{{ $page['id'] }}')">
                                    <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-black shrink-0">✓</span>
                                </template>
                                <template x-if="!isCompleted('{{ $page['id'] }}') && !isUnlocked('{{ $page['id'] }}')">
                                    <span class="text-[10px] opacity-70">🔒</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── ACCORDION 3: KEGIATAN BELAJAR ── --}}
            @php $sec3Pages = array_filter($pagesList, fn($p) => $p['sec'] === 3); @endphp
            @if(!empty($sec3Pages))
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                    <button type="button"
                            @click="toggleSection(3)"
                            class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-black flex items-center justify-center">{{ $secMap[3] ?? 3 }}</span>
                            <span>Kegiatan Belajar</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                             :class="openSections[3] ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openSections[3]" x-collapse class="p-2 pt-0 space-y-1.5">
                        @foreach($sec3Pages as $page)
                            <button type="button"
                                    @click="goToPage('{{ $page['id'] }}')"
                                    :disabled="!isUnlocked('{{ $page['id'] }}')"
                                    :class="{
                                        'sidebar-item-active': activePage === '{{ $page['id'] }}',
                                        'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60 cursor-pointer': activePage !== '{{ $page['id'] }}' && isUnlocked('{{ $page['id'] }}'),
                                        'bg-slate-100/70 text-slate-400 border border-slate-200/40 opacity-60 cursor-not-allowed': !isUnlocked('{{ $page['id'] }}')
                                    }"
                                    class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2">
                                <span class="flex items-center gap-2 truncate">
                                    <span>{{ $page['icon'] }}</span>
                                    <span class="truncate">{{ $page['title'] }}</span>
                                </span>
                                <template x-if="isCompleted('{{ $page['id'] }}')">
                                    <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-black shrink-0">✓</span>
                                </template>
                                <template x-if="!isCompleted('{{ $page['id'] }}') && !isUnlocked('{{ $page['id'] }}')">
                                    <span class="text-[10px] opacity-70">🔒</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── ACCORDION 4: EVALUASI & PRAKTIK ── --}}
            @php $sec4Pages = array_filter($pagesList, fn($p) => $p['sec'] === 4); @endphp
            @if(!empty($sec4Pages))
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                    <button type="button"
                            @click="toggleSection(4)"
                            class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-lg bg-violet-100 text-violet-700 text-[10px] font-black flex items-center justify-center">{{ $secMap[4] ?? 4 }}</span>
                            <span>Evaluasi & Praktik</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                             :class="openSections[4] ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openSections[4]" x-collapse class="p-2 pt-0 space-y-1.5">
                        @foreach($sec4Pages as $page)
                            <button type="button"
                                    @click="goToPage('{{ $page['id'] }}')"
                                    :disabled="!isUnlocked('{{ $page['id'] }}')"
                                    :class="{
                                        'sidebar-item-active': activePage === '{{ $page['id'] }}',
                                        'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60 cursor-pointer': activePage !== '{{ $page['id'] }}' && isUnlocked('{{ $page['id'] }}'),
                                        'bg-slate-100/70 text-slate-400 border border-slate-200/40 opacity-60 cursor-not-allowed': !isUnlocked('{{ $page['id'] }}')
                                    }"
                                    class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2">
                                <span class="flex items-center gap-2 truncate">
                                    <span>{{ $page['icon'] }}</span>
                                    <span class="truncate">{{ $page['title'] }}</span>
                                </span>
                                <template x-if="isCompleted('{{ $page['id'] }}')">
                                    <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-black shrink-0">✓</span>
                                </template>
                                <template x-if="!isCompleted('{{ $page['id'] }}') && !isUnlocked('{{ $page['id'] }}')">
                                    <span class="text-[10px] opacity-70">🔒</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── ACCORDION 5: BAGIAN AKHIR ── --}}
            @php $sec5Pages = array_filter($pagesList, fn($p) => $p['sec'] === 5); @endphp
            @if(!empty($sec5Pages))
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/50 overflow-hidden">
                    <button type="button"
                            @click="toggleSection(5)"
                            class="w-full px-3.5 py-3 text-left flex items-center justify-between text-xs font-extrabold text-slate-800 hover:bg-slate-100/60 transition cursor-pointer">
                        <span class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-lg bg-rose-100 text-rose-700 text-[10px] font-black flex items-center justify-center">{{ $secMap[5] ?? 5 }}</span>
                            <span>Bagian Akhir</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                             :class="openSections[5] ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="openSections[5]" x-collapse class="p-2 pt-0 space-y-1.5">
                        @foreach($sec5Pages as $page)
                            <button type="button"
                                    @click="goToPage('{{ $page['id'] }}')"
                                    :disabled="!isUnlocked('{{ $page['id'] }}')"
                                    :class="{
                                        'sidebar-item-active': activePage === '{{ $page['id'] }}',
                                        'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/60 cursor-pointer': activePage !== '{{ $page['id'] }}' && isUnlocked('{{ $page['id'] }}'),
                                        'bg-slate-100/70 text-slate-400 border border-slate-200/40 opacity-60 cursor-not-allowed': !isUnlocked('{{ $page['id'] }}')
                                    }"
                                    class="w-full px-3 py-2.5 rounded-xl text-left text-xs font-bold transition flex items-center justify-between gap-2">
                                <span class="flex items-center gap-2 truncate">
                                    <span>{{ $page['icon'] }}</span>
                                    <span class="truncate">{{ $page['title'] }}</span>
                                </span>
                                <template x-if="isCompleted('{{ $page['id'] }}')">
                                    <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-black shrink-0">✓</span>
                                </template>
                                <template x-if="!isCompleted('{{ $page['id'] }}') && !isUnlocked('{{ $page['id'] }}')">
                                    <span class="text-[10px] opacity-70">🔒</span>
                                </template>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
