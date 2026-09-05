{{-- ── TAB CONTENT 1: DIREKTORI SISWA ─────────────────────────────── --}}
<div x-show="activeTab === 'students'" class="p-6 space-y-6">
    {{-- Search Bar Siswa --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form action="{{ route('teacher.classes.show', $class) }}" method="GET" class="relative flex-1 max-w-md">
            <input type="hidden" name="tab" value="students">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" name="search_student" value="{{ request('search_student') }}"
                   placeholder="Cari nama atau NISN siswa..."
                   class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400">
        </form>

        <p class="text-xs text-slate-500">
            @if($students->hasPages())
                Menampilkan <strong class="text-slate-800">{{ $students->firstItem() }}–{{ $students->lastItem() }}</strong> dari <strong class="text-slate-800">{{ $students->total() }}</strong> Siswa
            @else
                Menampilkan <strong class="text-slate-800">{{ $students->total() }}</strong> Siswa
            @endif
        </p>
    </div>

    {{-- Tabel Siswa --}}
    @if($students->isEmpty())
        <div class="rounded-2xl bg-slate-50 p-8 text-center border border-slate-200">
            <p class="text-sm font-bold text-slate-700">Tidak ada data siswa ditemukan.</p>
            <p class="text-xs text-slate-400 mt-1">Belum ada siswa terdaftar pada kelas ini atau hasil pencarian nihil.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-900 text-white font-bold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Siswa</th>
                        <th class="py-3.5 px-4">NISN</th>
                        <th class="py-3.5 px-4 text-center">Modul Diikuti</th>
                        <th class="py-3.5 px-4 text-center">Rata-rata Nilai</th>
                        <th class="py-3.5 px-4 text-center">Status Nilai</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($students as $index => $student)
                        @php
                            $acad = $student->academic_summary;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $index % 2 === 1 ? 'bg-slate-50/30' : '' }}">
                            {{-- No --}}
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                {{ $students->firstItem() + $index }}
                            </td>

                            {{-- Nama Siswa & Avatar --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-tight">
                                            {{ $student->name }}
                                        </p>
                                        <p class="text-[11px] text-slate-400">
                                            {{ $class->full_name }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- NISN --}}
                            <td class="py-3.5 px-4 font-mono font-medium text-slate-600">
                                {{ $student->identity_number }}
                            </td>

                            {{-- Modul Diikuti --}}
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg text-xs">
                                    {{ $acad['submitted_count'] }} <span class="font-normal text-slate-400">/ {{ $acad['total_modules'] }} Modul</span>
                                </span>
                            </td>

                            {{-- Rata-rata Nilai --}}
                            <td class="py-3.5 px-4 text-center font-black">
                                @if($acad['avg_score'] !== null)
                                    <span class="text-sm {{ $acad['avg_score'] >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $acad['avg_score'] }} Poin
                                    </span>
                                @else
                                    <span class="text-slate-300 font-normal">-</span>
                                @endif
                            </td>

                            {{-- Status Ketuntasan Nilai --}}
                            <td class="py-3.5 px-4 text-center">
                                @if($acad['kktp_status'] === 'Tuntas')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tuntas
                                    </span>
                                @elseif($acad['kktp_status'] === 'Belum Tuntas (Remedial)')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Remedial
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                        Belum Ada Nilai
                                    </span>
                                @endif
                            </td>

                            {{-- Tombol Aksi Detail --}}
                            <td class="py-3.5 px-4 text-center">
                                <button type="button"
                                        @click="fetchStudentSummary({{ $student->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-bold text-xs transition-all border border-blue-200 hover:border-transparent cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Rincian Nilai</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination Direktori Siswa --}}
            @if($students->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
