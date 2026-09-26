@extends('layouts.admin.dashboardadmin')

@section('title', 'Kenaikan Kelas & Kelulusan Siswa — Admin E-Modul')
@section('page-title', 'Kenaikan Kelas & Kelulusan')

@section('content')

<div x-data="{
    activeTab: '{{ $activeTab }}', // 'promotion' or 'graduation'
    sourceClassId: '{{ $selectedSourceClassId ?? '' }}',
    targetClassId: '',
    students: [],
    selectedStudents: [],
    loadingStudents: false,
    searchStudent: '',
    confirmGraduationModal: false,
    graduationClassId: '{{ $selectedGraduationClassId ?? 'all' }}',

    init() {
        if (this.sourceClassId) {
            this.fetchStudents(this.sourceClassId);
        }
    },

    fetchStudents(classId) {
        if (!classId) {
            this.students = [];
            this.selectedStudents = [];
            return;
        }
        this.loadingStudents = true;
        fetch('{{ url('admin/academic/promotions/students') }}/' + classId)
            .then(res => res.json())
            .then(data => {
                this.students = data.students || [];
                // Default: centang semua siswa
                this.selectedStudents = this.students.map(s => s.id);
                this.loadingStudents = false;
            })
            .catch(err => {
                console.error('Error fetching class students:', err);
                this.loadingStudents = false;
            });
    },

    selectAll() {
        this.selectedStudents = this.filteredStudents().map(s => s.id);
    },

    deselectAll() {
        this.selectedStudents = [];
    },

    filteredStudents() {
        if (!this.searchStudent) return this.students;
        const q = this.searchStudent.toLowerCase();
        return this.students.filter(s =>
            (s.name && s.name.toLowerCase().includes(q)) ||
            (s.identity_number && s.identity_number.toLowerCase().includes(q))
        );
    }
}">

    {{-- ══ 1. BREADCRUMB & HEADER ══ --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Tahun Ajaran Baru</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Kenaikan Kelas & Kelulusan Siswa</span>
                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Siklus Akademik
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Pusat manajemen pergantian tahun ajaran: promosi kenaikan kelas berjenjang dan pembersihan data kelulusan kelas XII.
            </p>
        </div>
    </div>

    {{-- ══ Flash Alerts ══ --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm font-medium text-blue-800 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    {{-- ══ 2. ALUR REKOMENDASI TAHUN AJARAN BARU ══ --}}
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-5 sm:p-6 text-white shadow-xl mb-6 border border-slate-800">
        <div class="flex items-center gap-2.5 mb-3 text-indigo-400 font-bold text-xs uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Panduan Urutan Proses Akhir Tahun Ajaran (Top-Down):</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="bg-white/10 rounded-2xl p-3 border border-white/10 backdrop-blur-xs">
                <span class="font-mono text-indigo-300 font-bold">Langkah 1:</span>
                <p class="font-bold text-white mt-1">🎓 Kelulusan Kelas XII</p>
                <p class="text-[11px] text-slate-300 mt-0.5">Download arsip nilai & bersihkan akun lulusan untuk mengosongkan rombel XII.</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-3 border border-white/10 backdrop-blur-xs">
                <span class="font-mono text-indigo-300 font-bold">Langkah 2:</span>
                <p class="font-bold text-white mt-1">🚀 Naikkan Kelas XI &rarr; XII</p>
                <p class="text-[11px] text-slate-300 mt-0.5">Pindahkan siswa XI ke rombel XII yang sudah kosong dan sinkronkan mapel baru.</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-3 border border-white/10 backdrop-blur-xs">
                <span class="font-mono text-indigo-300 font-bold">Langkah 3:</span>
                <p class="font-bold text-white mt-1">🚀 Naikkan Kelas X &rarr; XI</p>
                <p class="text-[11px] text-slate-300 mt-0.5">Pindahkan siswa X ke rombel XI dan sinkronkan mapel kelas XI.</p>
            </div>
            <div class="bg-white/10 rounded-2xl p-3 border border-white/10 backdrop-blur-xs">
                <span class="font-mono text-indigo-300 font-bold">Langkah 4:</span>
                <p class="font-bold text-white mt-1">📥 Siswa Baru Kelas X</p>
                <p class="text-[11px] text-slate-300 mt-0.5">Gunakan fitur <a href="{{ route('admin.students.index') }}" class="text-indigo-300 underline">Import Excel Siswa</a> untuk mengisi rombel X.</p>
            </div>
        </div>
    </div>

    {{-- ══ 3. STATS TINGKAT KELAS ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tingkat X</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_x'] }} Siswa</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $classesX->count() }} Rombel Kelas</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-base border border-blue-100">
                X
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tingkat XI</p>
                <h3 class="text-2xl font-black text-indigo-600">{{ $stats['total_xi'] }} Siswa</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $classesXI->count() }} Rombel Kelas</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-base border border-indigo-100">
                XI
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tingkat XII (Calon Lulusan)</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ $stats['total_xii'] }} Siswa</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ $classesXII->count() }} Rombel Kelas</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-base border border-emerald-100">
                XII
            </div>
        </div>
    </div>

    {{-- ══ 4. TABS NAVIGATION ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="flex border-b border-slate-200 bg-slate-50/70 p-2 gap-2">
            <button type="button"
                    @click="activeTab = 'promotion'"
                    class="flex-1 py-3 px-4 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center justify-center gap-2 cursor-pointer"
                    :class="activeTab === 'promotion' ? 'bg-white text-indigo-600 shadow-sm border border-slate-200/60' : 'text-slate-500 hover:text-slate-800'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.143 2.143L15 7.5" />
                </svg>
                <span>🚀 Kenaikan Kelas (Promosi Rombel Siswa)</span>
            </button>

            <button type="button"
                    @click="activeTab = 'graduation'"
                    class="flex-1 py-3 px-4 rounded-2xl text-xs sm:text-sm font-bold transition-all flex items-center justify-center gap-2 cursor-pointer"
                    :class="activeTab === 'graduation' ? 'bg-white text-emerald-700 shadow-sm border border-slate-200/60' : 'text-slate-500 hover:text-slate-800'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                </svg>
                <span>🎓 Kelulusan Siswa (Tingkat XII)</span>
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 1: KENAIKAN KELAS --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'promotion'" class="p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Pemindahan & Kenaikan Rombel Siswa</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Pilih kelas asal dan kelas tujuan. Siswa yang terpilih akan otomatis dipindahkan rombelnya dan mata pelajarannya langsung diselaraskan dengan kelas tujuan.
                </p>
            </div>

            <form action="{{ route('admin.promotions.promote') }}" method="POST">
                @csrf

                {{-- Baris Pemilihan Rombel Asal & Tujuan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-slate-50 rounded-3xl border border-slate-200/80 mb-6">
                    
                    {{-- 1. Kelas Asal --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            1. Pilih Kelas Asal (Tingkat X atau XI) <span class="text-red-500">*</span>
                        </label>
                        <select name="source_class_id"
                                x-model="sourceClassId"
                                @change="fetchStudents(sourceClassId)"
                                required
                                class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl border border-slate-300 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-semibold">
                            <option value="">-- Pilih Kelas Asal Siswa --</option>
                            <optgroup label="Tingkat X (Naik ke XI)">
                                @foreach($classesX as $cls)
                                    <option value="{{ $cls->id }}">
                                        {{ $cls->full_name }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Tingkat XI (Naik ke XII)">
                                @foreach($classesXI as $cls)
                                    <option value="{{ $cls->id }}">
                                        {{ $cls->full_name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            Memilih kelas akan memuat daftar seluruh siswa di kelas tersebut.
                        </p>
                    </div>

                    {{-- 2. Kelas Tujuan --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            2. Pilih Kelas Tujuan (Tingkat XI atau XII) <span class="text-red-500">*</span>
                        </label>
                        <select name="target_class_id"
                                x-model="targetClassId"
                                required
                                class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl border border-slate-300 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-semibold">
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            <optgroup label="Tingkat XI (Tujuan dari X)">
                                @foreach($classesXI as $cls)
                                    <option value="{{ $cls->id }}">
                                        {{ $cls->full_name }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Tingkat XII (Tujuan dari XI)">
                                @foreach($classesXII as $cls)
                                    <option value="{{ $cls->id }}">
                                        {{ $cls->full_name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            Mata pelajaran kelas tujuan akan otomatis diberikan ke siswa yang naik.
                        </p>
                    </div>
                </div>

                {{-- Area Loading Siswa --}}
                <div x-show="loadingStudents" class="py-12 text-center text-slate-400">
                    <svg class="w-8 h-8 animate-spin mx-auto text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-xs font-bold text-slate-600">Memuat data siswa kelas...</p>
                </div>

                {{-- Daftar Siswa Checklist --}}
                <div x-show="!loadingStudents && students.length > 0" class="border border-slate-200 rounded-3xl overflow-hidden mb-6">
                    
                    {{-- Header Checklist & Search --}}
                    <div class="p-4 bg-slate-50/80 border-b border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-800">
                                Total Siswa: <span x-text="students.length" class="text-indigo-600"></span>
                            </span>
                            <span class="text-slate-300">|</span>
                            <span class="text-xs font-bold text-slate-800">
                                Dipilih: <span x-text="selectedStudents.length" class="text-emerald-600"></span>
                            </span>
                            <div class="flex items-center gap-1.5 ml-2">
                                <button type="button" @click="selectAll()" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 underline cursor-pointer">Pilih Semua</button>
                                <span class="text-slate-300">•</span>
                                <button type="button" @click="deselectAll()" class="text-[11px] font-bold text-slate-500 hover:text-slate-800 underline cursor-pointer">Batalkan Semua</button>
                            </div>
                        </div>

                        <div class="w-full sm:w-64">
                            <input type="text"
                                   x-model="searchStudent"
                                   placeholder="Filter nama / NISN..."
                                   class="w-full px-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-indigo-500">
                        </div>
                    </div>

                    {{-- Hint Tinggal Kelas --}}
                    <div class="px-5 py-2.5 bg-amber-50/80 border-b border-amber-100 flex items-center gap-2 text-[11px] text-amber-800 font-medium">
                        <span>ℹ️</span>
                        <span>Siswa yang <strong>tidak dicentang</strong> akan tetap tinggal di kelas asal (misal siswa yang dinyatakan tinggal kelas).</span>
                    </div>

                    {{-- Grid / List Siswa --}}
                    <div class="max-h-96 overflow-y-auto p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                        <template x-for="st in filteredStudents()" :key="st.id">
                            <label class="flex items-center gap-3 p-3 rounded-2xl border transition-all cursor-pointer"
                                   :class="selectedStudents.includes(st.id) ? 'bg-indigo-50/50 border-indigo-200' : 'bg-white border-slate-200 hover:bg-slate-50'">
                                <input type="checkbox"
                                       name="student_ids[]"
                                       :value="st.id"
                                       x-model="selectedStudents"
                                       class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                <div class="truncate">
                                    <p class="text-xs font-bold text-slate-800 truncate" x-text="st.name"></p>
                                    <p class="text-[10px] text-slate-400 font-mono" x-text="'NISN: ' + st.identity_number"></p>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Pesan Jika Belum Memilih Kelas Asal --}}
                <div x-show="!loadingStudents && students.length === 0" class="py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 rounded-3xl mb-6">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                        👥
                    </div>
                    <p class="font-bold text-slate-700">Belum Ada Kelas yang Dipilih</p>
                    <p class="text-xs mt-1">Silakan pilih kelas asal pada dropdown di atas untuk menampilkan daftar siswa.</p>
                </div>

                {{-- Action Submit --}}
                <div x-show="students.length > 0" class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="submit"
                            :disabled="selectedStudents.length === 0 || !targetClassId"
                            onclick="return confirm('Konfirmasi: Naikkan siswa terpilih ke kelas tujuan? Mata pelajaran siswa akan otomatis disesuaikan dengan kelas baru.')"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-xs sm:text-sm font-bold shadow-md shadow-indigo-600/25 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Proses Kenaikan Kelas (<span x-text="selectedStudents.length"></span> Siswa)</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════════════════════ --}}
        {{-- TAB 2: KELULUSAN KELAS XII --}}
        {{-- ══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'graduation'" class="p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-900">Manajemen Kelulusan Siswa Tingkat XII</h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Kelola pelepasan siswa kelas XII yang telah lulus. Anda dapat mengunduh rekap nilai sebelum menghapus akun secara massal untuk mengosongkan rombel XII.
                </p>
            </div>

            {{-- Kartu Info Perlindungan --}}
            <div class="p-5 rounded-3xl bg-amber-50 border border-amber-200 text-amber-900 mb-6 flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-amber-200/60 text-amber-800 flex items-center justify-center text-xl shrink-0">
                    💡
                </div>
                <div class="text-xs leading-relaxed">
                    <p class="font-bold text-sm text-amber-950 mb-1">Rekomendasi Sebelum Melakukan Penghapusan:</p>
                    <p>
                        Klik tombol <strong>"Unduh Rekap Nilai Kelulusan (.xlsx)"</strong> terlebih dahulu untuk menyimpan arsip nilai akademik siswa (nilai kuis, tugas LKPD, job sheet). Setelah itu, klik tombol <strong>"Proses Kelulusan & Hapus Akun"</strong> agar rombel kelas XII kembali bersih dan kapasitas server Anda kembali lega.
                    </p>
                </div>
            </div>

            {{-- Form Selector Kelas XII --}}
            <div class="max-w-2xl bg-slate-50 p-6 rounded-3xl border border-slate-200 mb-6">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Pilih Rombel Kelas XII yang Akan Diproses <span class="text-red-500">*</span>
                </label>
                <select x-model="graduationClassId"
                        class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl border border-slate-300 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all font-semibold mb-4">
                    <option value="all">🌟 Semua Rombel Kelas XII ({{ $stats['total_xii'] }} Siswa)</option>
                    @foreach($classesXII as $cls)
                        <option value="{{ $cls->id }}">
                            {{ $cls->full_name }} ({{ $cls->students_count }} Siswa)
                        </option>
                    @endforeach
                </select>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Tombol Unduh Excel --}}
                    <a :href="'{{ route('admin.promotions.graduation.export') }}?class_id=' + graduationClassId"
                       class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Unduh Rekap Nilai (.xlsx)</span>
                    </a>

                    {{-- Tombol Buka Modal Hapus Lulusan --}}
                    <button type="button"
                            @click="confirmGraduationModal = true"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md shadow-red-600/20 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                        <span>Proses Kelulusan & Hapus Akun</span>
                    </button>
                </div>
            </div>

            {{-- Ringkasan Tabel Kelas XII --}}
            <div class="border border-slate-200 rounded-3xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Rombel Kelas XII</th>
                            <th class="py-3.5 px-4">Konsentrasi Keahlian</th>
                            <th class="py-3.5 px-4 text-center">Jumlah Siswa</th>
                            <th class="py-3.5 px-6 text-right">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($classesXII as $cls)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-6 font-bold text-slate-800 text-xs">
                                    {{ $cls->full_name }}
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-500">
                                    {{ $cls->major ? $cls->major->name : ($cls->major_name ?? '-') }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $cls->students_count > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-400' }}">
                                        {{ $cls->students_count }} Siswa
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($cls->students_count > 0)
                                            <a :href="'{{ route('admin.promotions.graduation.export') }}?class_id={{ $cls->id }}'"
                                               class="px-2.5 py-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-200">
                                                Excel
                                            </a>
                                            <button type="button"
                                                    @click="graduationClassId = '{{ $cls->id }}'; confirmGraduationModal = true"
                                                    class="px-2.5 py-1 text-[11px] font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors border border-red-200">
                                                Luluskan
                                            </button>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">Rombel Kosong</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada rombel kelas XII yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══ 5. MODAL KONFIRMASI KELULUSAN & HAPUS AKUN ══ --}}
    <div x-show="confirmGraduationModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in"
         @keydown.escape.window="confirmGraduationModal = false">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-md overflow-hidden p-6 text-center"
             @click.outside="confirmGraduationModal = false">
            
            <div class="w-14 h-14 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 text-2xl">
                ⚠️
            </div>
            
            <h3 class="text-base font-bold text-slate-900 mb-1">Konfirmasi Kelulusan & Pembersihan Akun</h3>
            <p class="text-xs text-slate-500 mb-4">
                Tindakan ini akan memproses kelulusan dan <strong>menghapus permanen</strong> akun siswa pada rombel yang dipilih.
            </p>

            <div class="p-4 bg-slate-50 rounded-2xl text-left border border-slate-200 text-slate-600 text-xs space-y-1.5 mb-4">
                <p class="font-bold text-slate-800">Operasi yang akan dijalankan:</p>
                <p class="flex items-center gap-1.5">
                    <span class="text-emerald-500">✓</span> Menghapus akun login siswa lulusan
                </p>
                <p class="flex items-center gap-1.5">
                    <span class="text-emerald-500">✓</span> Menghapus riwayat nilai dan jawaban kuis
                </p>
                <p class="flex items-center gap-1.5">
                    <span class="text-emerald-500">✓</span> Menghapus file fisik tugas (LKPD, Job Sheet) di storage server
                </p>
                <p class="flex items-center gap-1.5">
                    <span class="text-emerald-500">✓</span> Mengosongkan rombel kelas XII untuk angkatan baru
                </p>
            </div>

            <form action="{{ route('admin.promotions.graduation.process') }}" method="POST">
                @csrf
                <input type="hidden" name="class_id" :value="graduationClassId">

                <div class="flex items-center justify-center gap-2">
                    <button type="button" @click="confirmGraduationModal = false" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                        Batalkan
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition-all cursor-pointer">
                        Ya, Luluskan & Hapus Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
