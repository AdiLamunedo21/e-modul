<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau LKPD — {{ $data['lkpd_title'] ?? $module->title }}</title>
    <link rel="icon" href="{{ asset('lgsmk.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp .4s ease-out; }
    </style>
</head>
<body class="bg-slate-100 antialiased text-slate-900 min-h-screen">

    {{-- ═══ TOP BAR ═══ --}}
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-black shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.199l-.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan LKPD Siswa</h1>
                    <p class="text-[11px] text-slate-400">
                        Navigasi Restriktif: Siswa wajib mengunggah salinan laporan PDF mandiri untuk membuka tahap berikutnya.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.lkpd.edit', $module) }}"
                   class="px-4 py-2 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition-all flex items-center gap-1.5">
                    <span>←</span> Kembali ke Editor
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ KONTEN PRATINJAU UTAMA ═══ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20">
        <div class="w-full animate-fadeInUp space-y-6">

            {{-- Breadcrumb Navigasi Siswa --}}
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700">E-Modul: {{ $module->title }}</span>
                    <span>/</span>
                    <span class="text-indigo-700">Komponen Inti (Kegiatan Belajar)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-800 border border-indigo-200">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        Tahap: Tugas LKPD (Studi Kasus)
                    </span>
                </div>
            </div>

            {{-- Grid Konten Belajar Siswa --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- ── Left Side: Case Study & Guidelines (7 cols) ── --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Card Skenario Kasus Utama --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                        <div class="p-6 sm:p-7 space-y-5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-800 text-[11px] font-extrabold uppercase tracking-wider border border-indigo-200">
                                            Komponen 6
                                        </span>
                                        @if(($data['work_mode'] ?? 'group') === 'group')
                                            <span class="px-2.5 py-1 rounded-lg bg-purple-100 text-purple-800 text-[11px] font-bold border border-purple-200 flex items-center gap-1">
                                                👥 Kelompok ({{ $data['group_size'] ?? '3 - 4 Siswa' }})
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[11px] font-bold border border-slate-200 flex items-center gap-1">
                                                👤 Individu (Mandiri)
                                            </span>
                                        @endif
                                    </div>
                                    <h2 class="text-xl font-extrabold text-slate-900 leading-snug">
                                        {{ $data['lkpd_title'] ?? 'Lembar Kerja Peserta Didik: ' . $module->title }}
                                    </h2>
                                </div>

                                <div class="px-3.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold shrink-0 text-center">
                                    ⏱️ {{ $data['estimated_duration'] ?? 90 }} Menit
                                </div>
                            </div>

                            {{-- Narasi Skenario Masalah --}}
                            <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-50 to-indigo-50/30 border border-indigo-100 space-y-2">
                                <h3 class="text-xs font-extrabold text-indigo-900 uppercase tracking-wider flex items-center gap-2">
                                    <span>📌</span> Skenario Masalah / Problem Statement
                                </h3>
                                <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                    {{ $data['case_study'] ?? 'Lakukan analisis dan pemecahan masalah berdasarkan materi pembelajaran.' }}
                                </p>
                            </div>

                            {{-- Petunjuk Langkah Kerja --}}
                            <div class="space-y-2.5">
                                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                                    <span>📝</span> Petunjuk & Tahapan Pengerjaan
                                </h3>
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                    {{ $data['instructions'] ?? "1. Pelajari skenario studi kasus.\n2. Lakukan analisis dan perancangan solusi.\n3. Susun laporan ke dalam file PDF dan unggah secara individu." }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Berkas Panduan LKPD Guru (Jika ada) --}}
                    @if(!empty($data['pdf_file_path']))
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>📑</span> Berkas Panduan / Format LKPD Resmi
                            </h3>
                            <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                Tersedia
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-xl bg-rose-500 text-white flex items-center justify-center font-black text-xs shrink-0 shadow-md">
                                    PDF
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 truncate max-w-xs">
                                        {{ $data['pdf_file_name'] ?? 'Panduan-LKPD.pdf' }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        {{ !empty($data['pdf_file_size']) ? round($data['pdf_file_size'] / 1024, 1) . ' KB' : 'Dokumen PDF' }} • Lembar instruksi guru
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('teacher.modules.lkpd.download', $module) }}"
                               class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Unduh Panduan LKPD
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Card Rubrik & Kriteria Penilaian --}}
                    @if(!empty($data['assessment_rubric']) && count($data['assessment_rubric']) > 0)
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>🎯</span> Transparansi Kriteria Penilaian LKPD
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($data['assessment_rubric'] as $idx => $rubric)
                                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                                    <span class="w-5 h-5 rounded-lg bg-indigo-100 text-indigo-700 text-[11px] font-black flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $loop->iteration }}
                                    </span>
                                    <span class="text-xs text-slate-700 font-medium leading-relaxed">{{ $rubric }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- ── Right Side: Submission Area & Interactive Simulation (5 cols) ── --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- Card Unggah Berkas Jawaban LKPD Siswa --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 space-y-5">
                        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>📤</span> Unggah Laporan LKPD Siswa
                            </h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full border border-rose-200">
                                Wajib Diunggah
                            </span>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-indigo-50/80 border border-indigo-100 text-xs text-indigo-900 leading-relaxed flex items-start gap-2.5">
                            <span class="text-base shrink-0">💡</span>
                            <span><strong>Pemberitahuan Siswa:</strong> Meskipun dikerjakan dalam kelompok, setiap siswa wajib mengunggah salinan file PDF secara individu ke akun masing-masing agar nilai tercatat personal.</span>
                        </div>

                        {{-- Area Dropzone Simulasi --}}
                        <div class="border-2 border-dashed border-indigo-200 hover:border-indigo-400 bg-indigo-50/20 hover:bg-indigo-50/40 rounded-3xl p-6 text-center transition-all cursor-pointer">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/></svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-bold text-slate-900">Seret & Lepas Berkas PDF di sini</p>
                                    <p class="text-xs text-slate-500">atau klik untuk memilih dokumen laporan Anda</p>
                                </div>
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-600 shadow-sm">
                                    Format: <strong class="text-indigo-600">.PDF</strong> (Maksimal 5 MB)
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Simulasi Simpan Jawaban Siswa --}}
                        <button type="button" disabled
                                class="w-full py-3 px-4 rounded-2xl bg-indigo-600 text-white text-xs font-bold shadow-md opacity-90 cursor-not-allowed flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Kirim Berkas Laporan LKPD (Simulasi Siswa)
                        </button>
                    </div>

                    {{-- Card Navigasi Restriktif Siswa --}}
                    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-3xl p-6 shadow-xl space-y-4 border border-indigo-800/40">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Navigasi Alur Belajar Siswa</h4>
                            <span class="text-[10px] font-extrabold bg-rose-500/20 text-rose-300 border border-rose-500/40 px-2 py-0.5 rounded-full">
                                Restriktif
                            </span>
                        </div>

                        <p class="text-xs text-slate-300 leading-relaxed">
                            Di layar siswa sesungguhnya, tombol di bawah akan <strong class="text-rose-400">terkunci (disabled)</strong> sampai siswa selesai mengunggah file laporan PDF.
                        </p>

                        <div class="pt-2 flex items-center justify-between gap-3">
                            <button type="button" class="px-4 py-2.5 rounded-xl bg-white/10 text-white text-xs font-bold border border-white/15">
                                ← 5. Job Sheet PDF
                            </button>
                            <button type="button" class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold flex items-center gap-1.5 shadow-md shadow-emerald-950/40">
                                <span>7. Post-test</span> →
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</body>
</html>
