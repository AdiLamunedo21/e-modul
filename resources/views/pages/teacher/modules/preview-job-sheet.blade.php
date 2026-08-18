<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Lembar Praktikum — {{ $data['job_sheet_title'] ?? $module->title }}</title>
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
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-slate-900 to-amber-950 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 font-black shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan Lembar Praktikum Siswa</h1>
                    <p class="text-[11px] text-slate-400">Navigasi Restriktif: Siswa wajib mengunduh Job Sheet & mengunggah file laporan PDF.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.job-sheet.edit', $module) }}"
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
                    <span class="text-amber-700">Bagian Inti (Kegiatan Belajar)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Tahap: Lembar Praktikum (Job Sheet PDF)
                    </span>
                </div>
            </div>

            {{-- Grid Konten Belajar Siswa --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- ── Left Side: Job Sheet Document & Guidelines (7 cols) ── --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Card Berkas Dokumen Job Sheet --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                        <div class="p-6 space-y-5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-md">
                                        PDF
                                    </div>
                                    <div>
                                        <h2 class="text-base font-bold text-slate-900 leading-snug">
                                            {{ $data['job_sheet_title'] ?? 'Lembar Praktikum: ' . $module->title }}
                                        </h2>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $data['pdf_file_name'] ?? 'Dokumen-Petunjuk-Job-Sheet.pdf' }} &bull; Estimasi: {{ $data['estimated_duration'] ?? 60 }} Menit
                                        </p>
                                    </div>
                                </div>

                                @if(!empty($data['pdf_file_path']))
                                    <a href="{{ route('teacher.modules.job-sheet.download', $module) }}"
                                       class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition-all shadow-md shadow-amber-900/20 flex items-center gap-2 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                        Unduh Job Sheet PDF
                                    </a>
                                @else
                                    <button type="button" onclick="alert('Simulasi: Berkas PDF resmi akan diunduh oleh siswa.')"
                                            class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition-all shadow-md shadow-amber-900/20 flex items-center gap-2 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                        Unduh Job Sheet PDF
                                    </button>
                                @endif
                            </div>

                            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-xs text-slate-700 leading-relaxed">
                                <p class="font-bold text-amber-900 flex items-center gap-1.5 mb-1">
                                    <span>💡</span> Petunjuk Penggunaan Lembar Kerja
                                </p>
                                <p>
                                    Unduh dan cetak/buka lembar kerja di atas. Lakukan setiap langkah kerja secara mandiri di bengkel atau laboratorium. Setelah selesai, susun laporan hasil praktikum Anda dan simpan dalam format PDF.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Petunjuk Kerja / Instruksi Praktikum --}}
                    @if(!empty($data['instructions']))
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>📖</span> Petunjuk Pengerjaan Praktikum
                        </h3>
                        <div class="text-xs text-slate-700 leading-relaxed space-y-2 prose max-w-none">
                            {!! nl2br(e($data['instructions'])) !!}
                        </div>
                    </div>
                    @endif

                    {{-- Standar Keselamatan & Kesehatan Kerja (K3) --}}
                    @if(!empty($data['safety_guidelines']))
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>🛡️</span> Standar Keselamatan & Kesehatan Kerja (K3)
                            </h3>
                            <span class="text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-full uppercase">
                                Wajib Dipatuhi
                            </span>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-900 text-amber-300 font-mono text-xs leading-relaxed space-y-1 border border-slate-800">
                            {!! nl2br(e($data['safety_guidelines'])) !!}
                        </div>
                    </div>
                    @endif

                    {{-- Daftar Alat & Bahan --}}
                    @php
                        $tools = $data['tools_and_materials'] ?? [];
                    @endphp
                    @if(!empty($tools) && count($tools) > 0)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>🛠️</span> Alat & Bahan yang Diperlukan
                        </h3>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                            @foreach($tools as $tool)
                                <li class="flex items-start gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-1.5 shrink-0"></span>
                                    <span>{{ $tool }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                </div>

                {{-- ── Right Side: Checklist & PDF Submission Dropzone (5 cols) ── --}}
                <div class="lg:col-span-5 space-y-6 sticky top-20">

                    {{-- Card Unggah Laporan Hasil Praktikum (PDF) --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">
                                    📤
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">Unggah Laporan Hasil Praktikum</h3>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full border border-rose-200">Wajib</span>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed">
                            Kompilasikan lembar kerja dan laporan hasil pengujian praktikum Anda ke dalam satu berkas PDF resmi sebelum mengunggah.
                        </p>

                        {{-- Dropzone Container --}}
                        <div id="dropzone-pdf-box"
                             onclick="document.getElementById('file-pdf-input').click()"
                             ondragover="handlePdfDragOver(event)"
                             ondragleave="handlePdfDragLeave(event)"
                             ondrop="handlePdfDrop(event)"
                             class="border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50 hover:bg-amber-50/40 rounded-2xl p-6 text-center cursor-pointer transition-all flex flex-col items-center justify-center space-y-2">
                            <input type="file" id="file-pdf-input" accept=".pdf,application/pdf" class="hidden" onchange="handlePdfSelected(this.files)">
                            
                            <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Klik atau seret berkas PDF ke sini</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Format: Dokumen PDF (Maksimal 5 MB)</p>
                            </div>
                        </div>

                        {{-- Preview Berkas PDF Terunggah --}}
                        <div id="preview-pdf-container" class="hidden p-4 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                        PDF
                                    </div>
                                    <div class="overflow-hidden">
                                        <p id="pdf-filename" class="text-xs font-bold text-slate-900 truncate max-w-[13rem]">Laporan-Praktikum.pdf</p>
                                        <p id="pdf-filesize" class="text-[11px] font-bold text-emerald-600 mt-0.5">✓ 2.4 MB</p>
                                    </div>
                                </div>
                                <button type="button" onclick="cancelPdfUpload()" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus berkas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-amber-200/60">
                                <span>Status: Siap Dikumpulkan</span>
                                <span class="text-amber-800 font-medium">Re-submission: Aktif (Pending)</span>
                            </div>
                        </div>

                        {{-- Tombol Navigasi Siswa Terkunci / Aktif --}}
                        <div class="pt-3 border-t border-slate-100 space-y-2">
                            <button type="button"
                                    id="btn-next-page"
                                    disabled
                                    class="w-full py-3.5 px-4 rounded-2xl text-xs font-extrabold text-white bg-slate-300 cursor-not-allowed transition-all flex items-center justify-center gap-2 shadow-sm select-none">
                                <span id="btn-next-text">🔒 Unggah Berkas Laporan PDF Dahulu</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </button>
                            <p class="text-[11px] text-center text-slate-400">Simulasi: Tombol di atas akan terbuka otomatis setelah file PDF valid terpasang.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        // Dropzone interactions
        function handlePdfDragOver(e) {
            e.preventDefault();
            document.getElementById('dropzone-pdf-box').classList.add('border-amber-500', 'bg-amber-50');
        }

        function handlePdfDragLeave(e) {
            e.preventDefault();
            document.getElementById('dropzone-pdf-box').classList.remove('border-amber-500', 'bg-amber-50');
        }

        function handlePdfDrop(e) {
            e.preventDefault();
            handlePdfDragLeave(e);
            if (e.dataTransfer.files.length > 0) {
                handlePdfSelected(e.dataTransfer.files);
            }
        }

        function handlePdfSelected(files) {
            if (!files || files.length === 0) return;
            const file = files[0];

            // Validasi format PDF
            if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                alert('Format file tidak valid! Harap unggah dokumen berformat PDF.');
                return;
            }

            // Validasi ukuran (5 MB = 5 * 1024 * 1024 bytes)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)! Maksimal ukuran file laporan adalah 5 MB sesuai PRD Section 2.4.');
                return;
            }

            document.getElementById('pdf-filename').innerText = file.name;
            document.getElementById('pdf-filesize').innerText = '✓ ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB (PDF Valid)';

            document.getElementById('dropzone-pdf-box').classList.add('hidden');
            document.getElementById('preview-pdf-container').classList.remove('hidden');

            // Unlock restrictive button
            unlockNextButton();
        }

        function cancelPdfUpload() {
            document.getElementById('file-pdf-input').value = '';
            document.getElementById('dropzone-pdf-box').classList.remove('hidden');
            document.getElementById('preview-pdf-container').classList.add('hidden');

            // Lock button again
            lockNextButton();
        }

        function unlockNextButton() {
            const btn = document.getElementById('btn-next-page');
            const text = document.getElementById('btn-next-text');

            btn.disabled = false;
            btn.className = 'w-full py-3.5 px-4 rounded-2xl text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-amber-600 hover:from-emerald-700 hover:to-amber-700 cursor-pointer transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/30 select-none animate-pulse';
            text.innerText = 'Halaman Selanjutnya (Tahap Selesai)';
        }

        function lockNextButton() {
            const btn = document.getElementById('btn-next-page');
            const text = document.getElementById('btn-next-text');

            btn.disabled = true;
            btn.className = 'w-full py-3.5 px-4 rounded-2xl text-xs font-extrabold text-white bg-slate-300 cursor-not-allowed transition-all flex items-center justify-center gap-2 shadow-sm select-none';
            text.innerText = '🔒 Unggah Berkas Laporan PDF Dahulu';
        }
    </script>
</body>
</html>
