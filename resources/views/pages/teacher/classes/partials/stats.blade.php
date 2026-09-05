{{-- ══ 2. RINGKASAN METRIK KELAS KHUSUS GURU ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
    {{-- Total Siswa Terdaftar --}}
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa Terdaftar</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-slate-900">{{ $classStats['total_students'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Siswa</span>
            </div>
            <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Dalam rombel kelas</p>
        </div>
    </div>

    {{-- Modul Guru Terbit --}}
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Modul Guru</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-slate-900">{{ $classStats['published_modules'] }}</span>
                <span class="text-xs font-semibold text-slate-500">/ {{ $classStats['total_modules'] }} Modul</span>
            </div>
            <p class="text-[11px] text-blue-600 font-medium mt-0.5">Diberikan ke kelas ini</p>
        </div>
    </div>

    {{-- Total Pengumpulan Tugas --}}
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengumpulan Tugas</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black text-slate-900">{{ $classStats['total_submissions'] }}</span>
                <span class="text-xs font-semibold text-slate-500">Tugas</span>
            </div>
            <p class="text-[11px] text-emerald-600 font-medium mt-0.5">{{ $classStats['graded_count'] }} sudah dinilai</p>
        </div>
    </div>

    {{-- Rata-rata Nilai Kelas --}}
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.003 0H9.497m5.003 0a3.375 3.375 0 00-6.003 0" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai Kelas</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-black {{ $classStats['avg_score'] >= 75 ? 'text-emerald-600' : ($classStats['avg_score'] > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                    {{ $classStats['avg_score'] > 0 ? $classStats['avg_score'] : '-' }}
                </span>
                <span class="text-xs font-semibold text-slate-500">/ 100 Skala</span>
            </div>
            <p class="text-[11px] text-amber-600 font-medium mt-0.5">Standar ketuntasan &ge; 75</p>
        </div>
    </div>
</div>
