{{-- 
    ══════════════════════════════════════════════════════════════════════
    NAVIGASI DIGITAL & ACTION DOCK KHUSUS MOBILE (ROLE SISWA)
    ══════════════════════════════════════════════════════════════════════
    - Khusus layar smartphone / tablet (lg:hidden)
    - Ergonomis (mudah dijangkau jempol satu tangan)
    - Integrasi penuh dengan sistem Tab Dashboard & Modal Gabung Kelas
--}}

@php
    $isDashboard = request()->routeIs('student.dashboard') || request()->routeIs('dashboard.student');
    $inProgBadge = isset($stats['in_progress']) ? $stats['in_progress'] : ($sidebarStats['in_progress'] ?? null);
    $defaultNavTab = (!empty($inProgBadge) && $inProgBadge > 0) ? 'in_progress' : 'classes';
    $currentStatus = request()->query('status', $defaultNavTab);
    $completedBadge = isset($stats['completed_modules']) ? $stats['completed_modules'] : ($sidebarStats['completed'] ?? null);
    $allModulesBadge = isset($stats['total_modules']) ? $stats['total_modules'] : ($sidebarStats['total_modules'] ?? null);
    $classesBadge = isset($classesWithModules) ? count($classesWithModules) : (Auth::guard('student')->user()?->classes()->count() ?? 0);
@endphp

<nav x-data="{
        currentTab: '{{ $isDashboard ? (in_array($currentStatus, ['classes', 'completed', 'all_modules', 'in_progress']) ? $currentStatus : $defaultNavTab) : '' }}',
        goToTab(tab, fallbackUrl) {
            const isDash = window.location.pathname.endsWith('/student/dashboard') || window.location.pathname.endsWith('/student/portal');
            if (isDash) {
                this.currentTab = tab;
                window.dispatchEvent(new CustomEvent('switch-student-tab', { detail: tab }));
            } else {
                window.location.href = fallbackUrl;
            }
        }
     }"
     x-on:student-tab-changed.window="currentTab = $event.detail"
     class="fixed bottom-0 inset-x-0 z-40 lg:hidden bg-white border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(15,23,42,0.08)] px-2 pt-2 transition-all select-none"
     style="padding-bottom: max(0.6rem, env(safe-area-inset-bottom));"
     aria-label="Navigasi Mobile Siswa">
    
    <div class="max-w-md mx-auto flex items-end justify-around relative">

        {{-- ═══ 1. TOMBOL BELAJAR / SEDANG DIKERJAKAN ═══ --}}
        <button type="button"
                @click="goToTab('in_progress', '{{ route('student.dashboard', ['status' => 'in_progress']) }}')"
                class="flex-1 flex flex-col items-center justify-center py-1 px-1 transition-all group relative cursor-pointer"
                :class="currentTab === 'in_progress' ? 'text-amber-600' : 'text-slate-400 hover:text-slate-600'">
            
            <div class="relative p-1.5 rounded-xl transition-all"
                 :class="currentTab === 'in_progress' ? 'bg-amber-50 text-amber-600 ring-1 ring-amber-300/80 scale-105 shadow-2xs' : 'group-hover:bg-slate-100/80'">
                
                {{-- Icon Jam Belajar --}}
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                {{-- Badge Counter In-Progress --}}
                @if(!empty($inProgBadge) && $inProgBadge > 0)
                    <span class="absolute -top-1 -right-1.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-amber-500 text-[9px] font-black text-white shadow-xs">
                        {{ $inProgBadge > 99 ? '99+' : $inProgBadge }}
                    </span>
                @endif
            </div>

            <span class="text-[10px] font-bold tracking-tight mt-1 leading-none transition-colors"
                  :class="currentTab === 'in_progress' ? 'text-amber-700 font-black' : 'text-slate-500 group-hover:text-slate-700'">
                Belajar
            </span>

            {{-- Active Indicator Dot --}}
            <span x-show="currentTab === 'in_progress'"
                  class="w-1 h-1 rounded-full bg-amber-500 mt-1 shadow-xs shadow-amber-500/50"></span>
        </button>

        {{-- ═══ 2. TOMBOL KELAS SAYA ═══ --}}
        <button type="button"
                @click="goToTab('classes', '{{ route('student.dashboard', ['status' => 'classes']) }}')"
                class="flex-1 flex flex-col items-center justify-center py-1 px-1 transition-all group relative cursor-pointer"
                :class="currentTab === 'classes' ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600'">
            
            <div class="relative p-1.5 rounded-xl transition-all"
                 :class="currentTab === 'classes' ? 'bg-blue-50 text-blue-600 ring-1 ring-blue-300/80 scale-105 shadow-2xs' : 'group-hover:bg-slate-100/80'">
                
                {{-- Icon Gedung Sekolah / Kelas --}}
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-5.25 6.557c0 1.036.84 1.875 1.875 1.875h6.75A1.875 1.875 0 0 0 17.25 15v-3.675c-1.637.332-3.325.568-5.25.708" />
                </svg>

                {{-- Badge Counter Kelas --}}
                @if(!empty($classesBadge) && $classesBadge > 0)
                    <span class="absolute -top-1 -right-1.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-blue-600 text-[9px] font-black text-white shadow-xs">
                        {{ $classesBadge }}
                    </span>
                @endif
            </div>

            <span class="text-[10px] font-bold tracking-tight mt-1 leading-none transition-colors"
                  :class="currentTab === 'classes' ? 'text-blue-700 font-black' : 'text-slate-500 group-hover:text-slate-700'">
                Kelas
            </span>

            {{-- Active Indicator Dot --}}
            <span x-show="currentTab === 'classes'"
                  class="w-1 h-1 rounded-full bg-blue-600 mt-1 shadow-xs shadow-blue-600/50"></span>
        </button>

        {{-- ═══ 3. TOMBOL HERO UTAMA: + GABUNG KELAS ═══ --}}
        <div class="flex-1 flex flex-col items-center justify-center relative -mt-3.5">
            <button type="button"
                    @click="joinModalOpen = true"
                    class="w-12 h-12 rounded-2xl bg-white text-emerald-600 border-2 border-emerald-600 shadow-md shadow-emerald-600/20 flex items-center justify-center hover:bg-emerald-50 active:scale-95 transition-all cursor-pointer group"
                    style="background-color: #ffffff; border-color: #059669; color: #059669;"
                    title="Tambah & Gabung Kelas Baru via Kode Kelas">
                
                <svg class="w-6 h-6 transition-transform group-hover:rotate-90 duration-300 stroke-[2.8]" fill="none" stroke="#059669" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>

            <span class="text-[10px] font-black text-emerald-700 tracking-tight mt-1 leading-none">
                Gabung
            </span>
        </div>

        {{-- ═══ 4. TOMBOL RIWAYAT SELESAI ═══ --}}
        <button type="button"
                @click="goToTab('completed', '{{ route('student.dashboard', ['status' => 'completed']) }}')"
                class="flex-1 flex flex-col items-center justify-center py-1 px-1 transition-all group relative cursor-pointer"
                :class="currentTab === 'completed' ? 'text-emerald-600' : 'text-slate-400 hover:text-slate-600'">
            
            <div class="relative p-1.5 rounded-xl transition-all"
                 :class="currentTab === 'completed' ? 'bg-emerald-50 text-emerald-600 ring-1 ring-emerald-300/80 scale-105 shadow-2xs' : 'group-hover:bg-slate-100/80'">
                
                {{-- Icon Centang / Piala Selesai --}}
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                {{-- Badge Counter Selesai --}}
                @if(!empty($completedBadge) && $completedBadge > 0)
                    <span class="absolute -top-1 -right-1.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-emerald-600 text-[9px] font-black text-white shadow-xs">
                        {{ $completedBadge }}
                    </span>
                @endif
            </div>

            <span class="text-[10px] font-bold tracking-tight mt-1 leading-none transition-colors"
                  :class="currentTab === 'completed' ? 'text-emerald-700 font-black' : 'text-slate-500 group-hover:text-slate-700'">
                Selesai
            </span>

            {{-- Active Indicator Dot --}}
            <span x-show="currentTab === 'completed'"
                  class="w-1 h-1 rounded-full bg-emerald-600 mt-1 shadow-xs shadow-emerald-600/50"></span>
        </button>

        {{-- ═══ 5. TOMBOL SEMUA MODUL ═══ --}}
        <button type="button"
                @click="goToTab('all_modules', '{{ route('student.dashboard', ['status' => 'all_modules']) }}')"
                class="flex-1 flex flex-col items-center justify-center py-1 px-0.5 transition-all group relative cursor-pointer"
                :class="currentTab === 'all_modules' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600'"
                title="Lihat Semua Modul Pembelajaran">
            
            <div class="relative p-1.5 rounded-xl transition-all"
                 :class="currentTab === 'all_modules' ? 'bg-indigo-50 text-indigo-600 ring-1 ring-indigo-300/80 scale-105 shadow-2xs' : 'group-hover:bg-slate-100/80'">
                
                {{-- Icon Buku / Semua Modul --}}
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>

                {{-- Badge Counter Semua Modul --}}
                @if(!empty($allModulesBadge) && $allModulesBadge > 0)
                    <span class="absolute -top-1 -right-1.5 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-indigo-600 text-[9px] font-black text-white shadow-xs">
                        {{ $allModulesBadge > 99 ? '99+' : $allModulesBadge }}
                    </span>
                @endif
            </div>

            <span class="text-[9.5px] font-bold tracking-tight mt-1 leading-none transition-colors truncate max-w-full text-center"
                  :class="currentTab === 'all_modules' ? 'text-indigo-700 font-black' : 'text-slate-500 group-hover:text-slate-700'">
                Semua Modul
            </span>

            {{-- Active Indicator Dot --}}
            <span x-show="currentTab === 'all_modules'"
                  class="w-1 h-1 rounded-full bg-indigo-600 mt-1 shadow-xs shadow-indigo-600/50"></span>
        </button>

    </div>
</nav>
