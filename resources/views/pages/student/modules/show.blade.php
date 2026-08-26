@extends('layouts.student.dashboardstudent')

@section('title', $module->title . ' — Portal Belajar Siswa')

@push('styles')
<style>
    /* Prose-like styling for Materi Uraian */
    .materi-prose h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: .75rem; margin-top: 1.5rem; color: #0f172a; }
    .materi-prose h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1.25rem; color: #1e293b; }
    .materi-prose h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1rem; color: #334155; }
    .materi-prose p { margin-bottom: .75rem; line-height: 1.75; }
    .materi-prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: .75rem; }
    .materi-prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: .75rem; }
    .materi-prose li { margin-bottom: .25rem; line-height: 1.65; }
    .materi-prose img { max-width: 100%; height: auto; border-radius: .75rem; margin: 1rem auto; display: block; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .materi-prose table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: .875rem; }
    .materi-prose th, .materi-prose td { border: 1px solid #e2e8f0; padding: .5rem .75rem; text-align: left; }
    .materi-prose th { background: #f8fafc; font-weight: 700; color: #0f172a; }
    .materi-prose blockquote { border-left: 4px solid #14b8a6; background: #f0fdfa; padding: .75rem 1rem; margin: 1rem 0; border-radius: 0 .5rem .5rem 0; font-style: italic; color: #0f766e; }
    .materi-prose hr { border: none; border-top: 2px solid #e2e8f0; margin: 1.5rem 0; }
    .materi-prose a { color: #0d9488; text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{ currentTab: {{ $currentSection }}, searchGlosarium: '' }">

    {{-- ═══ 1. STICKY TOP HEADER & BREADCRUMB ═══ --}}
    <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-7 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            {{-- Title & Badges --}}
            <div class="space-y-2.5">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('student.modules.subject', $module->subject_id) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        <span>←</span>
                        <span>{{ $module->subject->name ?? 'Mata Pelajaran' }}</span>
                    </a>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold {{ $module->subject?->badgeClasses() ?? 'bg-blue-100 text-blue-800' }}">
                        <span>{{ $module->subject->code ?? 'MAPEL' }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">
                        <span>{{ $module->schoolClass->full_name ?? 'Kelas' }}</span>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ $module->title }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium flex items-center gap-2">
                    <span>👨‍🏫 Guru Pengampu: <strong>{{ $module->teacher->name ?? 'Guru' }}</strong></span>
                    <span>•</span>
                    <span>Terakhir diperbarui: {{ $module->updated_at->translatedFormat('d M Y') }}</span>
                </p>
            </div>

            {{-- Progress & Final Score Card --}}
            <div class="flex flex-wrap items-center gap-4 bg-slate-50 border border-slate-200/80 p-4 rounded-2xl shrink-0">
                {{-- Progres Belajar --}}
                <div class="min-w-[160px]">
                    <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                        <span class="text-slate-500 uppercase tracking-wider text-[10px]">Progres Belajar</span>
                        <span class="{{ $progressPercent >= 100 ? 'text-emerald-600' : 'text-slate-800' }}">
                            {{ $completedTasks }}/{{ $totalActive }} ({{ $progressPercent }}%)
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full transition-all duration-500 {{ $progressPercent >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-emerald-500 to-teal-500' }}"
                             style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>

                {{-- Nilai Akhir Sumatif jika sudah ada --}}
                @if($studentResult)
                    <div class="pl-4 border-l border-slate-200 text-center min-w-[90px]">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nilai Akhir</p>
                        <p class="text-2xl font-black {{ $studentResult->summative_score >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $studentResult->summative_score }}
                        </p>
                        <span class="inline-block text-[9px] font-bold px-1.5 py-0.5 rounded-md {{ $studentResult->grading_status === 'graded' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $studentResult->grading_status === 'graded' ? 'Dinilai' : 'Pending' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══ 2. STEPPER NAVIGATOR (5 BAGIAN E-MODUL) ═══ --}}
        <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5">
            {{-- Step 1: Bagian Awal --}}
            <button type="button"
                    @click="currentTab = 1"
                    :class="currentTab === 1 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20 ring-2 ring-indigo-600' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/70'"
                    class="p-3 rounded-2xl text-left transition-all duration-200 flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full mb-1">
                    <span class="text-xs font-black" :class="currentTab === 1 ? 'text-indigo-200' : 'text-indigo-600'">01</span>
                    <span class="text-sm">📘</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold tracking-tight line-clamp-1">Bagian Awal</h3>
                    <p class="text-[10px] opacity-80 truncate">Cover & Petunjuk</p>
                </div>
            </button>

            {{-- Step 2: Pendahuluan --}}
            <button type="button"
                    @click="currentTab = 2"
                    :class="currentTab === 2 ? 'bg-teal-600 text-white shadow-md shadow-teal-600/20 ring-2 ring-teal-600' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/70'"
                    class="p-3 rounded-2xl text-left transition-all duration-200 flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full mb-1">
                    <span class="text-xs font-black" :class="currentTab === 2 ? 'text-teal-200' : 'text-teal-600'">02</span>
                    <span class="text-sm">🚀</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold tracking-tight line-clamp-1">Pendahuluan</h3>
                    <p class="text-[10px] opacity-80 truncate">
                        {{ $module->has_pre_test ? 'Tujuan & Pre-test' : 'Tujuan & Konsep' }}
                    </p>
                </div>
            </button>

            {{-- Step 3: Kegiatan Belajar --}}
            <button type="button"
                    @click="currentTab = 3"
                    :class="currentTab === 3 ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20 ring-2 ring-blue-600' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/70'"
                    class="p-3 rounded-2xl text-left transition-all duration-200 flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full mb-1">
                    <span class="text-xs font-black" :class="currentTab === 3 ? 'text-blue-200' : 'text-blue-600'">03</span>
                    <span class="text-sm">📖</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold tracking-tight line-clamp-1">Kegiatan Belajar</h3>
                    <p class="text-[10px] opacity-80 truncate">Materi & Video</p>
                </div>
            </button>

            {{-- Step 4: Evaluasi & Latihan --}}
            <button type="button"
                    @click="currentTab = 4"
                    :class="currentTab === 4 ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20 ring-2 ring-amber-600' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/70'"
                    class="p-3 rounded-2xl text-left transition-all duration-200 flex flex-col justify-between group">
                <div class="flex items-center justify-between w-full mb-1">
                    <span class="text-xs font-black" :class="currentTab === 4 ? 'text-amber-200' : 'text-amber-700'">04</span>
                    <span class="text-sm">⚡</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold tracking-tight line-clamp-1">Evaluasi Praktik</h3>
                    <p class="text-[10px] opacity-80 truncate">Embed, LKPD, Jobsheet</p>
                </div>
            </button>

            {{-- Step 5: Bagian Akhir --}}
            <button type="button"
                    @click="currentTab = 5"
                    :class="currentTab === 5 ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20 ring-2 ring-rose-600' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200/70'"
                    class="p-3 rounded-2xl text-left transition-all duration-200 flex flex-col justify-between group col-span-2 sm:col-span-1">
                <div class="flex items-center justify-between w-full mb-1">
                    <span class="text-xs font-black" :class="currentTab === 5 ? 'text-rose-200' : 'text-rose-600'">05</span>
                    <span class="text-sm">🎯</span>
                </div>
                <div>
                    <h3 class="text-xs font-bold tracking-tight line-clamp-1">Bagian Akhir</h3>
                    <p class="text-[10px] opacity-80 truncate">Post-test & Rekap</p>
                </div>
            </button>
        </div>
    </div>

    {{-- Alert Notifikasi Session --}}
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm font-bold flex items-center gap-3 shadow-sm">
            <span class="text-xl">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-red-800 text-sm font-bold flex items-center gap-3 shadow-sm">
            <span class="text-xl">⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ SECTION 1: BAGIAN AWAL ═══ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="currentTab === 1" x-cloak class="space-y-6">

        {{-- 1.1 Sampul Modul (Cover) --}}
        @if($module->isInfoComponentActive('cover'))
            <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-10 shadow-xl relative overflow-hidden">
                <div class="absolute right-0 top-0 translate-x-12 -translate-y-12 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                    @if(!empty($informasiUmum['cover']['cover_image_path']))
                        <div class="w-full md:w-48 lg:w-56 shrink-0">
                            <img src="{{ asset('storage/' . $informasiUmum['cover']['cover_image_path']) }}"
                                 alt="Cover {{ $module->title }}"
                                 class="w-full h-auto rounded-2xl shadow-2xl border-2 border-white/20 object-cover">
                        </div>
                    @else
                        <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-3xl bg-gradient-to-tr from-indigo-600 to-teal-400 flex items-center justify-center text-4xl sm:text-5xl shadow-2xl shrink-0 border border-white/20">
                            📚
                        </div>
                    @endif

                    <div class="space-y-3 text-center md:text-left flex-1">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-teal-300 text-xs font-bold border border-white/10">
                            <span>SMK NEGERI 3 YOGYAKARTA</span>
                            <span>•</span>
                            <span>{{ $informasiUmum['cover']['tahun_ajaran'] ?? date('Y') . '/' . (date('Y')+1) }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
                            {{ $informasiUmum['cover']['judul_cover'] ?? $module->title }}
                        </h2>
                        @if(!empty($informasiUmum['cover']['sub_judul']))
                            <p class="text-indigo-200 text-sm sm:text-base font-medium">
                                {{ $informasiUmum['cover']['sub_judul'] }}
                            </p>
                        @endif
                        <div class="pt-4 flex flex-wrap items-center justify-center md:justify-start gap-4 text-xs text-slate-300">
                            <div>Penyusun: <strong class="text-white">{{ $informasiUmum['cover']['penyusun'] ?? $module->teacher->name }}</strong></div>
                            <span>•</span>
                            <div>Mata Pelajaran: <strong class="text-white">{{ $module->subject->name }}</strong></div>
                            <span>•</span>
                            <div>Kelas: <strong class="text-white">{{ $module->schoolClass->full_name }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 1.2 Kata Pengantar --}}
        @if($module->isInfoComponentActive('kata_pengantar'))
            <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-lg font-bold">✏️</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Kata Pengantar</h3>
                        <p class="text-xs text-slate-500">Prakata dan pengantar dari guru pengampu</p>
                    </div>
                </div>
                <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed space-y-3">
                    @if(!empty($informasiUmum['kata_pengantar']['kata_pengantar_text']))
                        {!! nl2br(e($informasiUmum['kata_pengantar']['kata_pengantar_text'])) !!}
                    @else
                        <p class="italic text-slate-400">
                            Puji syukur ke hadirat Tuhan Yang Maha Esa atas tersusunnya E-Modul ini sebagai media pembelajaran interaktif bagi siswa SMKN 3 Yogyakarta. Semoga modul ini dapat memfasilitasi pembelajaran mandiri yang efektif dan menyenangkan.
                        </p>
                    @endif
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-right text-xs text-slate-500">
                    <p>{{ $informasiUmum['kata_pengantar']['tempat_tanggal'] ?? 'Yogyakarta, ' . date('d F Y') }}</p>
                    <p class="font-bold text-slate-800 mt-1">{{ $informasiUmum['kata_pengantar']['nama_penyusun'] ?? $module->teacher->name }}</p>
                </div>
            </div>
        @endif

        {{-- 1.3 Petunjuk Penggunaan Siswa & Guru --}}
        @if($module->isInfoComponentActive('petunjuk_penggunaan'))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Petunjuk Siswa --}}
                <div class="rounded-3xl bg-teal-50/50 border border-teal-200/80 p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-teal-200/60">
                        <span class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">🎓</span>
                        <div>
                            <h4 class="text-base font-bold text-teal-950">Petunjuk untuk Siswa</h4>
                            <p class="text-xs text-teal-700">Langkah-langkah belajar mandiri</p>
                        </div>
                    </div>
                    <ul class="space-y-2.5 text-xs sm:text-sm text-teal-900">
                        @if(!empty($informasiUmum['petunjuk_penggunaan']['petunjuk_siswa']))
                            @foreach((array) $informasiUmum['petunjuk_penggunaan']['petunjuk_siswa'] as $item)
                                <li class="flex items-start gap-2.5">
                                    <span class="text-teal-600 font-bold mt-0.5">✓</span>
                                    <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">1.</span><span>Baca dan pahami tujuan pembelajaran sebelum masuk ke materi inti.</span></li>
                            <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">2.</span><span>Kerjakan soal latihan diagnostik (Pre-test) untuk mengukur pengetahuan awal.</span></li>
                            <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">3.</span><span>Pelajari uraian materi dan tonton multimedia video pembelajaran.</span></li>
                            <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">4.</span><span>Lakukan praktik pada simulator embed dan kumpulkan tugas LKPD serta Job Sheet.</span></li>
                            <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">5.</span><span>Selesaikan evaluasi Post-test di bagian akhir modul.</span></li>
                        @endif
                    </ul>
                </div>

                {{-- Petunjuk Guru --}}
                <div class="rounded-3xl bg-indigo-50/50 border border-indigo-200/80 p-6 sm:p-7 shadow-sm">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-indigo-200/60">
                        <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">👨‍🏫</span>
                        <div>
                            <h4 class="text-base font-bold text-indigo-950">Peran & Bimbingan Guru</h4>
                            <p class="text-xs text-indigo-700">Fasilitasi pembelajaran peserta didik</p>
                        </div>
                    </div>
                    <ul class="space-y-2.5 text-xs sm:text-sm text-indigo-900">
                        @if(!empty($informasiUmum['petunjuk_penggunaan']['petunjuk_guru']))
                            @foreach((array) $informasiUmum['petunjuk_penggunaan']['petunjuk_guru'] as $item)
                                <li class="flex items-start gap-2.5">
                                    <span class="text-indigo-600 font-bold mt-0.5">✓</span>
                                    <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">1.</span><span>Membimbing siswa yang mengalami kendala saat menyelesaikan aktivitas modul.</span></li>
                            <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">2.</span><span>Memantau antrean pengumpulan tugas dan memberikan penilaian serta umpan balik di Grading Center.</span></li>
                            <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">3.</span><span>Mengarahkan siswa pada sesi refleksi dan penguatan kompetensi kejuruan.</span></li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif

        {{-- Next Button --}}
        <div class="flex justify-end pt-4">
            <button type="button" @click="currentTab = 2"
                    class="px-6 py-3 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-teal-600/20 transition flex items-center gap-2">
                <span>Lanjut ke Bagian 2: Pendahuluan</span>
                <span>→</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ SECTION 2: PENDAHULUAN & PRE-TEST ═══ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="currentTab === 2" x-cloak class="space-y-6">

        {{-- 2.1 Tujuan Pembelajaran & Capaian --}}
        @if($module->isInfoComponentActive('tujuan_pembelajaran'))
            <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg font-bold">🎯</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tujuan Pembelajaran & Capaian</h3>
                        <p class="text-xs text-slate-500">Kompetensi yang akan dikuasai setelah menuntaskan modul ini</p>
                    </div>
                </div>

                <div class="space-y-6">
                    @if(!empty($informasiUmum['tujuan_pembelajaran']['capaian_pembelajaran']))
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Capaian Pembelajaran (CP)</h4>
                            <p class="text-sm text-slate-800 leading-relaxed">
                                {{ $informasiUmum['tujuan_pembelajaran']['capaian_pembelajaran'] }}
                            </p>
                        </div>
                    @endif

                    @if(!empty($informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran']))
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Tujuan Khusus Pembelajaran (TP)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach((array) $informasiUmum['tujuan_pembelajaran']['tujuan_pembelajaran'] as $tp)
                                    <div class="p-4 rounded-2xl bg-teal-50/40 border border-teal-100 flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-lg bg-teal-600 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✓</span>
                                        <span class="text-xs sm:text-sm text-teal-950 leading-relaxed">
                                            {{ is_array($tp) ? ($tp['text'] ?? '') : $tp }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- 2.2 Peta Konsep --}}
        @if($module->isInfoComponentActive('peta_konsep') && (!empty($informasiUmum['peta_konsep']['peta_konsep_image_path']) || !empty($informasiUmum['peta_konsep']['peta_konsep_text'])))
            <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg font-bold">🗺️</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Peta Konsep Materi</h3>
                        <p class="text-xs text-slate-500">Struktur alur kompetensi dan keterkaitan materi</p>
                    </div>
                </div>

                @if(!empty($informasiUmum['peta_konsep']['peta_konsep_image_path']))
                    <div class="mb-4 text-center">
                        <img src="{{ asset('storage/' . $informasiUmum['peta_konsep']['peta_konsep_image_path']) }}"
                             alt="Peta Konsep"
                             class="max-h-96 mx-auto rounded-2xl border border-slate-200 shadow-sm object-contain">
                    </div>
                @endif

                @if(!empty($informasiUmum['peta_konsep']['peta_konsep_text']))
                    <div class="p-4 rounded-2xl bg-slate-50 text-xs sm:text-sm text-slate-700 leading-relaxed border border-slate-200/60">
                        {!! nl2br(e($informasiUmum['peta_konsep']['peta_konsep_text'])) !!}
                    </div>
                @endif
            </div>
        @endif

        {{-- 2.3 Glosarium --}}
        @if($module->isInfoComponentActive('glosarium') && !empty($informasiUmum['glosarium']['glosarium']))
            <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg font-bold">📖</span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Glosarium Kata Kunci</h3>
                            <p class="text-xs text-slate-500">Kamus istilah teknis & konsep penting</p>
                        </div>
                    </div>
                    <div class="relative w-full sm:w-64">
                        <input type="text"
                               x-model="searchGlosarium"
                               placeholder="Cari istilah..."
                               class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none">
                        <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    @foreach((array) $informasiUmum['glosarium']['glosarium'] as $item)
                        @php
                            $istilah = is_array($item) ? ($item['istilah'] ?? '') : '';
                            $definisi = is_array($item) ? ($item['definisi'] ?? '') : $item;
                        @endphp
                        <div x-show="!searchGlosarium || '{{ strtolower($istilah . ' ' . $definisi) }}'.includes(searchGlosarium.toLowerCase())"
                             class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 hover:border-teal-300 transition">
                            <h5 class="text-xs font-bold text-teal-800 mb-1 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                <span>{{ $istilah ?: 'Istilah' }}</span>
                            </h5>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $definisi }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 2.4 Soal Latihan Diagnostik (Pre-test) --}}
        @if($module->has_pre_test && $module->preTest)
            <div class="rounded-3xl bg-white border border-teal-200/90 shadow-sm overflow-hidden" id="section-pre-test">
                <div class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <span class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl shrink-0">⚡</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-200">Latihan Diagnostik Awal</span>
                            <h3 class="text-xl font-black leading-tight">{{ $module->preTest->title ?? 'Pre-test Pembuka' }}</h3>
                            <p class="text-xs text-teal-100 mt-0.5">Durasi: {{ $module->preTest->duration_minutes ?? 15 }} Menit • Target KKTP: {{ $module->preTest->kktp ?? 75 }}</p>
                        </div>
                    </div>

                    @if($studentResult && $studentResult->pre_test_score !== null)
                        <div class="bg-white/15 px-4 py-2 rounded-2xl border border-white/20 text-center shrink-0">
                            <span class="text-[10px] font-bold text-teal-200 uppercase block">Skor Pre-test</span>
                            <span class="text-2xl font-black text-white">{{ $studentResult->pre_test_score }}/100</span>
                        </div>
                    @endif
                </div>

                <div class="p-6 sm:p-8">
                    @if($studentResult && $studentResult->pre_test_score !== null)
                        {{-- Tampilan Hasil Pre-test yang Sudah Dikerjakan --}}
                        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-6 text-center space-y-3">
                            <div class="w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center text-2xl mx-auto shadow-md">
                                ✓
                            </div>
                            <h4 class="text-lg font-black text-emerald-950">Pre-test Telah Diselesaikan!</h4>
                            <p class="text-xs sm:text-sm text-emerald-800 max-w-md mx-auto leading-relaxed">
                                Anda telah menyelesaikan tes diagnostik ini dengan perolehan skor <strong>{{ $studentResult->pre_test_score }}</strong>.
                                Silakan lanjutkan mempelajari uraian materi pada bagian berikutnya.
                            </p>
                        </div>
                    @else
                        {{-- Form Pengerjaan Kuis Pre-test --}}
                        <form action="{{ route('student.modules.pre-test.submit', $module) }}" method="POST" class="space-y-8">
                            @csrf
                            <p class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                                💡 <strong>Petunjuk:</strong> Pilihlah salah satu jawaban yang paling tepat (A, B, C, D, atau E) untuk setiap butir soal di bawah ini, kemudian klik tombol <strong>Kirim Jawaban Pre-test</strong>.
                            </p>

                            @foreach($module->preTest->questions as $idx => $q)
                                <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                                    <div class="flex items-start gap-3">
                                        <span class="w-7 h-7 rounded-xl bg-teal-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                            {{ $idx + 1 }}
                                        </span>
                                        <div class="text-sm font-bold text-slate-900 leading-relaxed flex-1">
                                            {{ $q->question_text }}
                                        </div>
                                    </div>

                                    {{-- Options A-E --}}
                                    <div class="grid grid-cols-1 gap-2.5 pl-10">
                                        @foreach(['A', 'B', 'C', 'D', 'E'] as $optKey)
                                            @if(!empty($q->options[$optKey]))
                                                <label class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:border-teal-400 hover:bg-teal-50/20 cursor-pointer transition">
                                                    <input type="radio"
                                                           name="answers[{{ $q->id }}]"
                                                           value="{{ $optKey }}"
                                                           required
                                                           class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-slate-300">
                                                    <span class="text-xs font-bold text-slate-700 w-5">{{ $optKey }}.</span>
                                                    <span class="text-xs sm:text-sm text-slate-800">{{ $q->options[$optKey] }}</span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <div class="pt-4 flex justify-end">
                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin mengirimkan seluruh jawaban Pre-test?');"
                                        class="px-8 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-md shadow-teal-600/30 transition">
                                    Kirim Jawaban Pre-test →
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        {{-- Stepper Buttons --}}
        <div class="flex justify-between pt-4">
            <button type="button" @click="currentTab = 1"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition">
                ← Kembali ke Bagian Awal
            </button>
            <button type="button" @click="currentTab = 3"
                    class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-600/20 transition flex items-center gap-2">
                <span>Lanjut ke Bagian 3: Kegiatan Belajar</span>
                <span>→</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ SECTION 3: KEGIATAN BELAJAR (MATERI & VIDEO) ═══ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="currentTab === 3" x-cloak class="space-y-6">

        {{-- 3.1 Uraian Materi Pembelajaran & PPT --}}
        @if($module->has_materi)
            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-slate-100">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                            ⏱️ Estimasi Belajar: {{ $materiData['estimasi_waktu'] ?? 45 }} Menit
                        </span>
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            📖 Uraian Materi Inti
                        </span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                        {{ $materiData['judul_materi'] ?? $module->title }}
                    </h2>
                </div>

                {{-- Teks Uraian --}}
                <div class="p-6 sm:p-8">
                    <div class="materi-prose text-slate-800 leading-relaxed text-sm sm:text-base">
                        @if(!empty($materiData['uraian_materi']))
                            {!! $materiData['uraian_materi'] !!}
                        @else
                            <p class="text-slate-400 italic">Materi belum diisi oleh guru.</p>
                        @endif
                    </div>

                    {{-- Unduh Berkas PPT / Slide Pembelajaran --}}
                    @if(!empty($materiData['ppt_file_path']))
                        <div class="mt-8 p-5 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow-md">
                                    📊
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">Dokumen Slide Presentasi (PPT/PDF)</h4>
                                    <p class="text-xs text-slate-500">{{ $materiData['ppt_file_name'] ?? 'Slide Materi' }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $materiData['ppt_file_path']) }}"
                               target="_blank"
                               download
                               class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2">
                                <span>Unduh Slide Presentasi</span>
                                <span>📥</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- 3.2 Multimedia Video YouTube & Form Resume --}}
        @if($module->has_video)
            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-video">
                <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-6 sm:p-7 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center text-lg font-bold shadow-sm">▶️</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-300">Multimedia Pembelajaran</span>
                            <h3 class="text-lg font-bold">{{ $videoData['judul_video'] ?? 'Video Pembelajaran YouTube' }}</h3>
                        </div>
                    </div>
                    @if($videoSummary)
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $videoSummary->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                            {{ $videoSummary->manual_score !== null ? 'Nilai: ' . $videoSummary->manual_score : 'Resume Dikirim (Pending)' }}
                        </span>
                    @endif
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    {{-- Iframe Video Player --}}
                    @if(!empty($videoData['youtube_id']))
                        <div class="aspect-video w-full rounded-2xl overflow-hidden shadow-lg border border-slate-200 bg-black">
                            <iframe class="w-full h-full"
                                    src="https://www.youtube-nocookie.com/embed/{{ $videoData['youtube_id'] }}?rel=0"
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    @endif

                    @if(!empty($videoData['deskripsi_video']))
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200/60">
                            {{ $videoData['deskripsi_video'] }}
                        </p>
                    @endif

                    {{-- Form / Tampilan Resume Siswa --}}
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>📝</span>
                                <span>Ringkasan / Resume Video Siswa</span>
                            </h4>
                            @if($videoSummary && $videoSummary->manual_score === null)
                                <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'video']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda ingin membatalkan resume ini untuk mengedit ulang?');"
                                            class="text-xs text-red-600 hover:text-red-700 font-bold underline">
                                        Batalkan / Edit Ulang
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($videoSummary)
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                                <p class="text-xs text-slate-500">Dikirim pada: {{ $videoSummary->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                <div class="text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                                    {{ $videoSummary->summary_text }}
                                </div>
                                @if($videoSummary->manual_score !== null)
                                    <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between text-xs font-bold text-emerald-800">
                                        <span>Nilai Guru:</span>
                                        <span class="text-sm">{{ $videoSummary->manual_score }}/100</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('student.modules.video.submit', $module) }}" method="POST" class="space-y-3"
                                  x-data="{ summaryText: '', get charCount() { return this.summaryText.length; } }">
                                @csrf
                                <textarea name="summary_text"
                                          x-model="summaryText"
                                          rows="5"
                                          required
                                          placeholder="Tuliskan poin-poin penting, intisari materi, dan pemahaman yang Anda dapatkan dari video di atas (minimal 20 karakter)..."
                                          class="w-full p-4 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition outline-none"></textarea>

                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span :class="charCount < 20 ? 'text-amber-600 font-bold' : 'text-emerald-600 font-bold'">
                                        Karakter: <span x-text="charCount"></span> (Min. 20)
                                    </span>
                                    <button type="submit"
                                            :disabled="charCount < 20"
                                            :class="charCount < 20 ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20'"
                                            class="px-6 py-2.5 rounded-xl text-white font-bold text-xs transition">
                                        Simpan & Kirim Resume Video
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Stepper Buttons --}}
        <div class="flex justify-between pt-4">
            <button type="button" @click="currentTab = 2"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition">
                ← Kembali ke Pendahuluan
            </button>
            <button type="button" @click="currentTab = 4"
                    class="px-6 py-3 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-amber-600/20 transition flex items-center gap-2">
                <span>Lanjut ke Bagian 4: Evaluasi & Latihan</span>
                <span>→</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ SECTION 4: EVALUASI & LATIHAN (EMBED, JOB SHEET, LKPD) ═══ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="currentTab === 4" x-cloak class="space-y-6">

        {{-- 4.1 Game Edukasi & Simulator Embed --}}
        @if($module->has_embed)
            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-embed">
                <div class="bg-gradient-to-r from-indigo-900 to-slate-900 text-white p-6 sm:p-7 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg font-bold shadow-sm">⚡</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-300">Praktik Interaktif & Simulator</span>
                            <h3 class="text-lg font-bold">{{ $embedData['judul_embed'] ?? 'Eksplorasi Simulator / Embed Media' }}</h3>
                        </div>
                    </div>
                    @if($embedSubmission)
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $embedSubmission->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                            {{ $embedSubmission->manual_score !== null ? 'Nilai: ' . $embedSubmission->manual_score : 'Screenshot Terunggah' }}
                        </span>
                    @endif
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    @if(!empty($embedData['instruksi_praktik']))
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs sm:text-sm text-slate-700 leading-relaxed">
                            💡 <strong>Instruksi Praktik:</strong> {{ $embedData['instruksi_praktik'] }}
                        </div>
                    @endif

                    {{-- Embed Frame --}}
                    @if(!empty($embedData['embed_code']))
                        <div class="w-full rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 min-h-[420px] shadow-inner">
                            {!! $embedData['embed_code'] !!}
                        </div>
                    @elseif(!empty($embedData['direct_url']))
                        <div class="p-6 rounded-2xl bg-indigo-50 border border-indigo-200 text-center space-y-3">
                            <h4 class="text-sm font-bold text-indigo-950">Tautan Simulator / Praktik Eksternal</h4>
                            <p class="text-xs text-indigo-700 max-w-md mx-auto">Klik tombol di bawah ini untuk membuka lembar simulator pada jendela peramban baru.</p>
                            <a href="{{ $embedData['direct_url'] }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                                <span>Buka Simulator Praktik</span>
                                <span>↗</span>
                            </a>
                        </div>
                    @endif

                    {{-- Form / Bukti Screenshot Praktik --}}
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>📸</span>
                                <span>Bukti Tangkapan Layar (Screenshot) Praktik</span>
                            </h4>
                            @if($embedSubmission && $embedSubmission->manual_score === null)
                                <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'embed']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda ingin membatalkan screenshot ini untuk mengunggah ulang?');"
                                            class="text-xs text-red-600 hover:text-red-700 font-bold underline">
                                        Batalkan / Unggah Ulang
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($embedSubmission)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center gap-4">
                                <img src="{{ asset('storage/' . $embedSubmission->screenshot_path) }}"
                                     alt="Bukti Screenshot"
                                     class="w-full sm:w-48 h-32 object-cover rounded-xl border border-slate-300 shadow-sm">
                                <div class="space-y-1 text-xs text-slate-600 flex-1">
                                    <p class="font-bold text-slate-900 text-sm">Screenshot Berhasil Diunggah</p>
                                    <p>Waktu kirim: {{ $embedSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                    @if($embedSubmission->manual_score !== null)
                                        <p class="text-emerald-700 font-bold">Nilai: {{ $embedSubmission->manual_score }}/100</p>
                                    @else
                                        <p class="text-amber-600 font-medium">Menunggu penilaian guru pengampu</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <form action="{{ route('student.modules.embed.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3"
                                  x-data="{ previewUrl: null }">
                                @csrf
                                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-indigo-400 bg-slate-50/50 transition">
                                    <template x-if="!previewUrl">
                                        <div>
                                            <span class="text-3xl block mb-2">📷</span>
                                            <p class="text-xs sm:text-sm font-bold text-slate-700">Unggah Gambar Screenshot Praktik</p>
                                            <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 5 MB)</p>
                                        </div>
                                    </template>
                                    <template x-if="previewUrl">
                                        <div class="space-y-2">
                                            <img :src="previewUrl" class="max-h-48 mx-auto rounded-xl shadow-md object-contain border">
                                            <p class="text-xs text-emerald-600 font-bold">Gambar siap diunggah</p>
                                        </div>
                                    </template>
                                    <input type="file"
                                           name="screenshot"
                                           accept="image/*"
                                           required
                                           @change="const file = $event.target.files[0]; if (file) { previewUrl = URL.createObjectURL(file); }"
                                           class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                                        Kirim Bukti Screenshot
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- 4.2 Lembar Kerja Praktik (Job Sheet PDF) --}}
        @if($module->has_job_sheet)
            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-jobsheet">
                <div class="bg-gradient-to-r from-rose-900 to-slate-900 text-white p-6 sm:p-7 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center text-lg font-bold shadow-sm">📋</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-300">Lembar Kerja Praktik Siswa</span>
                            <h3 class="text-lg font-bold">{{ $jobSheetData['judul_jobsheet'] ?? 'Job Sheet Praktikum Bengkel/Lab' }}</h3>
                        </div>
                    </div>
                    @if($jobSheetSubmission)
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $jobSheetSubmission->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                            {{ $jobSheetSubmission->manual_score !== null ? 'Nilai: ' . $jobSheetSubmission->manual_score : 'Laporan PDF Terkirim' }}
                        </span>
                    @endif
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    {{-- Instruksi & Download Berkas Panduan Job Sheet --}}
                    <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-rose-950">Panduan & Lembar Instruksi Praktikum</h4>
                            <p class="text-xs text-rose-800 mt-0.5">Unduh berkas PDF panduan praktikum sebelum memulai pekerjaan laboratorium.</p>
                        </div>
                        @if(!empty($jobSheet?->pdf_file_path))
                            <a href="{{ asset('storage/' . $jobSheet->pdf_file_path) }}"
                               target="_blank"
                               download
                               class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2 shrink-0">
                                <span>Unduh Panduan Job Sheet PDF</span>
                                <span>📥</span>
                            </a>
                        @endif
                    </div>

                    {{-- Form / Status Pengumpulan Laporan Job Sheet --}}
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>📑</span>
                                <span>Unggah Laporan Hasil Praktikum (PDF)</span>
                            </h4>
                            @if($jobSheetSubmission && $jobSheetSubmission->manual_score === null)
                                <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'job_sheet']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda ingin membatalkan berkas Job Sheet ini untuk mengunggah ulang?');"
                                            class="text-xs text-red-600 hover:text-red-700 font-bold underline">
                                        Batalkan / Unggah Ulang
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($jobSheetSubmission)
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm">PDF</span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Laporan Job Sheet Terkirim</p>
                                        <p class="text-[11px] text-slate-500">Dikirim: {{ $jobSheetSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($jobSheetSubmission->manual_score !== null)
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                                            Nilai: {{ $jobSheetSubmission->manual_score }}/100
                                        </span>
                                    @endif
                                    <a href="{{ asset('storage/' . $jobSheetSubmission->uploaded_file_path) }}"
                                       target="_blank"
                                       class="px-4 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                                        Lihat Berkas ↗
                                    </a>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('student.modules.job-sheet.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-rose-400 bg-slate-50/50 transition">
                                    <span class="text-3xl block mb-2">📄</span>
                                    <p class="text-xs sm:text-sm font-bold text-slate-700">Pilih Berkas Laporan Praktikum Job Sheet</p>
                                    <p class="text-xs text-slate-400 mt-1">Dokumen harus dalam format PDF (Maksimal 10 MB)</p>
                                    <input type="file"
                                           name="job_sheet_file"
                                           accept=".pdf,application/pdf"
                                           required
                                           class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 cursor-pointer">
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/20 transition">
                                        Kirim Laporan Job Sheet PDF
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- 4.3 Tugas LKPD (Lembar Kerja Peserta Didik) --}}
        @if($module->has_lkpd)
            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-lkpd">
                <div class="bg-gradient-to-r from-amber-900 to-slate-900 text-white p-6 sm:p-7 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center text-lg font-bold shadow-sm">👥</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-300">Lembar Kerja Peserta Didik</span>
                            <h3 class="text-lg font-bold">{{ $lkpdData['judul_lkpd'] ?? 'Tugas Lembar Kerja (LKPD)' }}</h3>
                        </div>
                    </div>
                    @if($lkpdSubmission)
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $lkpdSubmission->manual_score !== null ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-amber-950' }}">
                            {{ $lkpdSubmission->manual_score !== null ? 'Nilai: ' . $lkpdSubmission->manual_score : 'Tugas LKPD Terkirim' }}
                        </span>
                    @endif
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    {{-- Instruksi & Download LKPD --}}
                    <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-amber-950">Berkas Soal & Instruksi LKPD</h4>
                            <p class="text-xs text-amber-800 mt-0.5">Pelajari dan diskusikan soal LKPD bersama kelompok kerja Anda.</p>
                        </div>
                        @if(!empty($lkpd?->pdf_file_path))
                            <a href="{{ asset('storage/' . $lkpd->pdf_file_path) }}"
                               target="_blank"
                               download
                               class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-sm transition flex items-center justify-center gap-2 shrink-0">
                                <span>Unduh Berkas LKPD PDF</span>
                                <span>📥</span>
                            </a>
                        @endif
                    </div>

                    {{-- Form / Status Pengumpulan LKPD --}}
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>📑</span>
                                <span>Unggah Jawaban / Laporan LKPD (PDF)</span>
                            </h4>
                            @if($lkpdSubmission && $lkpdSubmission->manual_score === null)
                                <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'lkpd']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Apakah Anda ingin membatalkan berkas LKPD ini untuk mengunggah ulang?');"
                                            class="text-xs text-red-600 hover:text-red-700 font-bold underline">
                                        Batalkan / Unggah Ulang
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($lkpdSubmission)
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">PDF</span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Tugas LKPD Terkirim</p>
                                        <p class="text-[11px] text-slate-500">Dikirim: {{ $lkpdSubmission->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($lkpdSubmission->manual_score !== null)
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                                            Nilai: {{ $lkpdSubmission->manual_score }}/100
                                        </span>
                                    @endif
                                    <a href="{{ asset('storage/' . $lkpdSubmission->uploaded_file_path) }}"
                                       target="_blank"
                                       class="px-4 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-bold transition">
                                        Lihat Berkas ↗
                                    </a>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('student.modules.lkpd.submit', $module) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-amber-400 bg-slate-50/50 transition">
                                    <span class="text-3xl block mb-2">📄</span>
                                    <p class="text-xs sm:text-sm font-bold text-slate-700">Pilih Berkas Jawaban LKPD</p>
                                    <p class="text-xs text-slate-400 mt-1">Dokumen harus dalam format PDF (Maksimal 10 MB)</p>
                                    <input type="file"
                                           name="lkpd_file"
                                           accept=".pdf,application/pdf"
                                           required
                                           class="mt-3 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 cursor-pointer">
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md shadow-amber-600/20 transition">
                                        Kirim Jawaban LKPD PDF
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Stepper Buttons --}}
        <div class="flex justify-between pt-4">
            <button type="button" @click="currentTab = 3"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition">
                ← Kembali ke Kegiatan Belajar
            </button>
            <button type="button" @click="currentTab = 5"
                    class="px-6 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-rose-600/20 transition flex items-center gap-2">
                <span>Lanjut ke Bagian 5: Bagian Akhir & Post-Test</span>
                <span>→</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- ═══ SECTION 5: BAGIAN AKHIR (POST-TEST, DAFTAR PUSTAKA & REKAP) ═══ --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="currentTab === 5" x-cloak class="space-y-6">

        {{-- 5.1 Post-test (Tes Akhir Modul) --}}
        @if($module->has_post_test && $module->postTest)
            <div class="rounded-3xl bg-white border border-rose-200/90 shadow-sm overflow-hidden" id="section-post-test">
                <div class="bg-gradient-to-r from-rose-600 to-pink-600 text-white p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <span class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-2xl shrink-0">🎯</span>
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-200">Evaluasi Akhir Modul</span>
                            <h3 class="text-xl font-black leading-tight">{{ $module->postTest->title ?? 'Post-test: Evaluasi Pemahaman' }}</h3>
                            <p class="text-xs text-rose-100 mt-0.5">Durasi: {{ $module->postTest->duration_minutes ?? 20 }} Menit • Target KKTP: {{ $module->postTest->kktp ?? 75 }}</p>
                        </div>
                    </div>

                    @if($studentResult && $studentResult->post_test_score !== null)
                        <div class="bg-white/15 px-4 py-2 rounded-2xl border border-white/20 text-center shrink-0">
                            <span class="text-[10px] font-bold text-rose-200 uppercase block">Skor Post-test</span>
                            <span class="text-2xl font-black text-white">{{ $studentResult->post_test_score }}/100</span>
                        </div>
                    @endif
                </div>

                <div class="p-6 sm:p-8">
                    @if($studentResult && $studentResult->post_test_score !== null)
                        {{-- Hasil Post-Test & Perbandingan Nilai --}}
                        <div class="rounded-2xl bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-200 p-6 text-center space-y-4">
                            <div class="w-16 h-16 rounded-full bg-rose-600 text-white flex items-center justify-center text-3xl mx-auto shadow-md">
                                🏆
                            </div>
                            <h4 class="text-xl font-black text-slate-900">Post-test Berhasil Diselesaikan!</h4>
                            <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
                                Skor Post-test Anda adalah <strong class="text-rose-600 text-base">{{ $studentResult->post_test_score }}</strong>.
                                @if($studentResult->pre_test_score !== null)
                                    @php $delta = $studentResult->post_test_score - $studentResult->pre_test_score; @endphp
                                    <br>
                                    <span class="inline-block mt-2 font-bold px-3 py-1 rounded-full text-xs {{ $delta >= 0 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700' }}">
                                        Perkembangan dari Pre-test: {{ $delta >= 0 ? '+' . $delta : $delta }} poin
                                    </span>
                                @endif
                            </p>
                        </div>
                    @else
                        {{-- Form Kuis Post-test --}}
                        <form action="{{ route('student.modules.post-test.submit', $module) }}" method="POST" class="space-y-8">
                            @csrf
                            <p class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                                💡 <strong>Petunjuk Post-test:</strong> Jawablah seluruh soal evaluasi penutup ini secara cermat dan mandiri untuk mengukur ketuntasan belajar Anda.
                            </p>

                            @foreach($module->postTest->questions as $idx => $q)
                                <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                                    <div class="flex items-start gap-3">
                                        <span class="w-7 h-7 rounded-xl bg-rose-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                            {{ $idx + 1 }}
                                        </span>
                                        <div class="text-sm font-bold text-slate-900 leading-relaxed flex-1">
                                            {{ $q->question_text }}
                                        </div>
                                    </div>

                                    {{-- Options A-E --}}
                                    <div class="grid grid-cols-1 gap-2.5 pl-10">
                                        @foreach(['A', 'B', 'C', 'D', 'E'] as $optKey)
                                            @if(!empty($q->options[$optKey]))
                                                <label class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:border-rose-400 hover:bg-rose-50/20 cursor-pointer transition">
                                                    <input type="radio"
                                                           name="answers[{{ $q->id }}]"
                                                           value="{{ $optKey }}"
                                                           required
                                                           class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-slate-300">
                                                    <span class="text-xs font-bold text-slate-700 w-5">{{ $optKey }}.</span>
                                                    <span class="text-xs sm:text-sm text-slate-800">{{ $q->options[$optKey] }}</span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <div class="pt-4 flex justify-end">
                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin mengirimkan jawaban Post-test dan menyelesaikan modul ini?');"
                                        class="px-8 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-md shadow-rose-600/30 transition">
                                    Kirim Jawaban Post-test →
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        {{-- 5.2 Daftar Pustaka --}}
        @if($module->isInfoComponentActive('daftar_pustaka') && !empty($informasiUmum['daftar_pustaka']['daftar_pustaka']))
            <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center text-lg font-bold">📚</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Daftar Pustaka & Rujukan</h3>
                        <p class="text-xs text-slate-500">Sumber referensi buku, modul standar, dan pustaka penyusun</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach((array) $informasiUmum['daftar_pustaka']['daftar_pustaka'] as $idx => $pustaka)
                        @php
                            $judul = is_array($pustaka) ? ($pustaka['judul'] ?? '') : $pustaka;
                            $penulis = is_array($pustaka) ? ($pustaka['penulis'] ?? '') : '';
                            $tahun = is_array($pustaka) ? ($pustaka['tahun'] ?? '') : '';
                            $penerbit = is_array($pustaka) ? ($pustaka['penerbit'] ?? '') : '';
                            $tautan = is_array($pustaka) ? ($pustaka['tautan'] ?? '') : '';
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-start gap-3.5">
                            <span class="w-6 h-6 rounded-lg bg-slate-200 text-slate-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                {{ $idx + 1 }}
                            </span>
                            <div class="text-xs sm:text-sm text-slate-800 leading-relaxed flex-1">
                                @if($penulis)<strong>{{ $penulis }}</strong>. @endif
                                @if($tahun)({{ $tahun }}). @endif
                                <em class="font-bold text-slate-900">{{ $judul }}</em>.
                                @if($penerbit){{ $penerbit }}. @endif
                                @if($tautan)
                                    <a href="{{ $tautan }}" target="_blank" rel="noopener" class="text-teal-600 hover:underline block mt-1 text-xs">
                                        {{ $tautan }} ↗
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 5.3 Transparansi Rekapitulasi Nilai Belajar Siswa --}}
        <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg font-bold">📊</span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Transparansi Rekapitulasi Nilai Belajar</h3>
                        <p class="text-xs text-slate-500">Rincian perolehan skor per instrumen evaluasi</p>
                    </div>
                </div>
                @if($studentResult)
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $studentResult->grading_status === 'graded' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        {{ $studentResult->grading_status === 'graded' ? 'Telah Dinilai Guru' : 'Menunggu Penilaian Manual' }}
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-200/70">
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Instrumen Evaluasi</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center">Skor / Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @if($module->has_pre_test)
                            <tr>
                                <td class="py-3 px-4 font-semibold text-slate-800">1. Kuis Awal (Pre-test)</td>
                                <td class="py-3 px-4 text-center">
                                    @if($studentResult && $studentResult->pre_test_score !== null)
                                        <span class="text-xs font-bold text-emerald-600">✓ Selesai</span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold">
                                    {{ $studentResult?->pre_test_score !== null ? $studentResult->pre_test_score : '-' }}
                                </td>
                            </tr>
                        @endif

                        @if($module->has_video)
                            <tr>
                                <td class="py-3 px-4 font-semibold text-slate-800">2. Ringkasan Video YouTube</td>
                                <td class="py-3 px-4 text-center">
                                    @if($videoSummary)
                                        <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold">
                                    {{ $studentResult?->video_score !== null ? $studentResult->video_score : ($videoSummary ? 'Pending' : '-') }}
                                </td>
                            </tr>
                        @endif

                        @if($module->has_embed)
                            <tr>
                                <td class="py-3 px-4 font-semibold text-slate-800">3. Praktik Simulator / Embed</td>
                                <td class="py-3 px-4 text-center">
                                    @if($embedSubmission)
                                        <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold">
                                    {{ $studentResult?->embed_score !== null ? $studentResult->embed_score : ($embedSubmission ? 'Pending' : '-') }}
                                </td>
                            </tr>
                        @endif

                        @if($module->has_job_sheet)
                            <tr>
                                <td class="py-3 px-4 font-semibold text-slate-800">4. Lembar Kerja Praktikum (Job Sheet)</td>
                                <td class="py-3 px-4 text-center">
                                    @if($jobSheetSubmission)
                                        <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold">
                                    {{ $studentResult?->job_sheet_score !== null ? $studentResult->job_sheet_score : ($jobSheetSubmission ? 'Pending' : '-') }}
                                </td>
                            </tr>
                        @endif

                        @if($module->has_lkpd)
                            <tr>
                                <td class="py-3 px-4 font-semibold text-slate-800">5. Tugas LKPD</td>
                                <td class="py-3 px-4 text-center">
                                    @if($lkpdSubmission)
                                        <span class="text-xs font-bold text-emerald-600">✓ Terkirim</span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold">
                                    {{ $studentResult?->lkpd_score !== null ? $studentResult->lkpd_score : ($lkpdSubmission ? 'Pending' : '-') }}
                                </td>
                            </tr>
                        @endif

                        @if($module->has_post_test)
                            <tr>
                                <td class="py-3 px-4 font-semibold text-slate-800">6. Evaluasi Akhir (Post-test)</td>
                                <td class="py-3 px-4 text-center">
                                    @if($studentResult && $studentResult->post_test_score !== null)
                                        <span class="text-xs font-bold text-emerald-600">✓ Selesai</span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold">
                                    {{ $studentResult?->post_test_score !== null ? $studentResult->post_test_score : '-' }}
                                </td>
                            </tr>
                        @endif

                        <tr class="bg-slate-50/80 font-black text-slate-900">
                            <td class="py-4 px-4 text-sm uppercase">NILAI AKHIR SUMATIF</td>
                            <td class="py-4 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'TUNTAS' : 'BELUM TUNTAS' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center text-lg {{ ($studentResult?->summative_score ?? 0) >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $studentResult?->summative_score ?? 0 }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Stepper Buttons --}}
        <div class="flex justify-between pt-4">
            <button type="button" @click="currentTab = 4"
                    class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-bold transition">
                ← Kembali ke Evaluasi & Latihan
            </button>
            <a href="{{ route('student.modules.subject', $module->subject_id) }}"
               class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                <span>Selesai Belajar & Kembali ke Daftar Modul</span>
                <span>✓</span>
            </a>
        </div>
    </div>

</div>
@endsection
