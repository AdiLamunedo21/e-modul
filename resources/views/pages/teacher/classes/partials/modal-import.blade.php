{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL 1: IMPORT MODUL DARI KELAS LAIN KE KELAS INI                           --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div x-show="importModalOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="importModalOpen = false"></div>

    {{-- Dialog Wrapper --}}
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div @click.stop
             class="relative w-full max-w-xl transform rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-200 overflow-hidden my-8">
            
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 sm:px-8 pt-6 sm:pt-7 pb-4 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-lg border border-indigo-100 shrink-0">
                        📥
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 leading-tight">Import Modul ke {{ $class->full_name }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Salin instrumen e-modul dari kelas lain ke kelas ini secara mandiri</p>
                    </div>
                </div>
                <button @click="importModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 flex items-center justify-center text-base transition-colors shrink-0 cursor-pointer">&times;</button>
            </div>

            {{-- Form Body --}}
            <form action="{{ route('teacher.classes.import-modules', $class) }}" method="POST">
                @csrf
                <div class="p-6 sm:p-8 space-y-4">
                    @if($otherClassModules->isEmpty())
                        <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Tidak ada modul dari kelas lain.</p>
                            <p class="text-[11px] text-slate-400 mt-1">Buat modul baru di kelas lain terlebih dahulu untuk dapat diimpor.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Pilih Modul yang Ingin Diimpor:
                                </label>
                                <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-200">
                                    {{ $otherClassModules->count() }} Modul Tersedia
                                </span>
                            </div>

                            {{-- Scrollable box strictly limited to ~5 items (280px) --}}
                            <div style="max-height: 280px; overflow-y: auto;" class="space-y-2 pr-1.5 [scrollbar-width:thin] [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-slate-300 [&::-webkit-scrollbar-track]:bg-slate-100">
                                @foreach($otherClassModules as $mod)
                                    <label class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200/90 hover:border-indigo-400 hover:bg-indigo-50/50 transition-all cursor-pointer group">
                                        <input type="checkbox" name="module_ids[]" value="{{ $mod->id }}"
                                               class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-xs text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $mod->title }}</span>
                                                @if($mod->subject)
                                                    <span class="px-2 py-0.2 rounded-md text-[10px] font-extrabold {{ $mod->subject->badgeClasses() }}">
                                                        {{ $mod->subject->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                                <span class="font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md text-[10px]">
                                                    {{ $mod->schoolClass ? $mod->schoolClass->full_name : 'Modul Umum' }}
                                                </span>
                                                <span>&bull;</span>
                                                <span class="text-[10px]">Dibuat {{ $mod->created_at ? $mod->created_at->format('d M Y') : '-' }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-3">
                    <button @click="importModalOpen = false" type="button" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors cursor-pointer">
                        Batal
                    </button>
                    @if($otherClassModules->isNotEmpty())
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/25 transition-all cursor-pointer">
                            Import & Salin ke Kelas Ini
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
