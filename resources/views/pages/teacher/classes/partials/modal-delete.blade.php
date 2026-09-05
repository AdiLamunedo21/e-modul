{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL 2: HAPUS KELAS & PURGE ALUMNI/MODUL                                   --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div x-show="deleteModalOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity" @click="deleteModalOpen = false"></div>

    {{-- Dialog Wrapper --}}
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
        <div @click.stop
             class="relative w-full max-w-md transform rounded-3xl bg-white text-left shadow-2xl transition-all border border-red-100 overflow-hidden my-8">
            
            {{-- Header --}}
            <div class="p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-black text-xl border border-red-100 shrink-0">
                        ⚠️
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 leading-tight">Hapus {{ $class->full_name }}?</h3>
                        <p class="text-xs text-red-600 font-semibold mt-0.5">Modul akan dihapus dan siswa dilepaskan</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-red-50/70 border border-red-200 text-xs text-red-900 space-y-2">
                    <p>
                        Anda akan menghapus rombel <strong>{{ $class->full_name }}</strong>.
                    </p>
                    <p class="text-[11px] text-red-700 font-medium">
                        Ketentuan penghapusan kelas ini:
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-[11px] text-red-800 font-medium pl-1">
                        <li>Seluruh modul pembelajaran pada kelas ini ({{ $teacherModules->count() }} modul) akan dihapus.</li>
                        <li>Siswa terdaftar ({{ $students->count() }} siswa) akan <strong>dilepaskan status kelasnya</strong>.</li>
                        <li><strong>Akun siswa TIDAK DIHAPUS</strong> (NISN, nama, dan akses login siswa tetap aman untuk bergabung ke kelas lain).</li>
                    </ul>
                </div>
            </div>

            {{-- Footer --}}
            <form action="{{ route('teacher.classes.destroy', $class) }}" method="POST" class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button @click="deleteModalOpen = false" type="button" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-200/70 rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md shadow-red-600/25 transition-all cursor-pointer">
                    Ya, Hapus Kelas
                </button>
            </form>
        </div>
    </div>
</div>
