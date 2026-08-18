<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Praktik Interaktif — {{ $data['embed_title'] ?? $module->title }}</title>
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
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-slate-900 to-teal-950 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400 font-black shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan Praktik Interaktif Siswa</h1>
                    <p class="text-[11px] text-slate-400">Navigasi Restriktif: Siswa wajib mengunggah bukti screenshot sebelum lanjut.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.embed.edit', $module) }}"
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
                    <span class="text-cyan-700">Bagian Inti (Kegiatan Belajar)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-cyan-50 text-cyan-800 border border-cyan-200">
                        <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span>
                        Tahap: Praktik Interaktif (Embed)
                    </span>
                </div>
            </div>

            {{-- Grid Konten Belajar Siswa --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- ── Left Side: Interactive Simulator Sandbox Player (7 cols) ── --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Card Simulator Frame --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col">
                        {{-- Simulator Header Bar --}}
                        <div class="bg-slate-900 text-white p-4 border-b border-slate-800 flex items-center justify-between gap-3 select-none">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-mono text-xs font-bold">
                                    &lt;/&gt;
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold truncate max-w-sm sm:max-w-md">{{ $data['embed_title'] ?? 'Praktik Interaktif' }}</h2>
                                    <p class="text-[11px] text-slate-400">Simulator Interaktif & Laboratorium Virtual</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="reloadStudentIframe()" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium transition-colors" title="Muat Ulang Simulator">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                </button>
                                <button type="button" onclick="toggleFullscreenSimulator()" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium transition-colors" title="Mode Layar Penuh">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Iframe Sandbox Canvas --}}
                        <div id="sim-container" class="relative w-full bg-slate-950 min-h-[460px] flex flex-col">
                            <iframe id="student-simulator-iframe"
                                    sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-modals"
                                    class="w-full flex-1 min-h-[460px] border-none bg-white"
                                    title="Interactive Simulator"></iframe>
                        </div>

                        {{-- Status Bar Bawah Simulator --}}
                        <div class="px-5 py-3 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-600">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="font-medium">Simulator Aktif & Siap Dijalankan</span>
                            </div>
                            <span class="text-slate-400 font-mono text-[11px]">Estimasi: {{ $data['estimated_duration'] ?? 20 }} Menit</span>
                        </div>
                    </div>

                    {{-- Petunjuk Kerja / Narasi --}}
                    @if(!empty($data['instructions']))
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-3">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>📖</span> Petunjuk Praktik bagi Siswa
                        </h3>
                        <div class="text-xs text-slate-700 leading-relaxed space-y-2 prose max-w-none">
                            {!! nl2br(e($data['instructions'])) !!}
                        </div>
                    </div>
                    @endif

                </div>

                {{-- ── Right Side: Target Checklist & Screenshot Upload (5 cols) ── --}}
                <div class="lg:col-span-5 space-y-6 sticky top-20">

                    {{-- Card Target Indikator --}}
                    @php
                        $checklistItems = $data['checklist_items'] ?? [];
                    @endphp
                    @if(!empty($checklistItems) && count($checklistItems) > 0)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>🎯</span> Target Capaian Praktik
                            </h3>
                            <span class="text-[11px] font-bold text-cyan-800 bg-cyan-50 px-2.5 py-0.5 rounded-full border border-cyan-200">
                                {{ count($checklistItems) }} Poin
                            </span>
                        </div>

                        <div class="space-y-2.5">
                            @foreach($checklistItems as $idx => $item)
                                <label class="flex items-start gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100/80 cursor-pointer transition-colors select-none group">
                                    <input type="checkbox" class="mt-0.5 w-4 h-4 rounded text-cyan-600 focus:ring-cyan-500 border-slate-300">
                                    <span class="text-xs text-slate-700 group-hover:text-slate-900 leading-snug">{{ $item }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Card Unggah Bukti Tangkapan Layar (Screenshot) --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                    📸
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">Bukti Tangkapan Layar</h3>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full border border-rose-200">Wajib</span>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $data['screenshot_guide'] ?? 'Unggah tangkapan layar (screenshot) bukti hasil eksekusi simulasi Anda.' }}
                        </p>

                        {{-- Dropzone Container --}}
                        <div id="dropzone-box"
                             onclick="document.getElementById('file-upload-input').click()"
                             ondragover="handleDragOver(event)"
                             ondragleave="handleDragLeave(event)"
                             ondrop="handleFileDrop(event)"
                             class="border-2 border-dashed border-slate-300 hover:border-cyan-500 bg-slate-50 hover:bg-cyan-50/40 rounded-2xl p-6 text-center cursor-pointer transition-all flex flex-col items-center justify-center space-y-2">
                            <input type="file" id="file-upload-input" accept="image/png, image/jpeg, image/webp" class="hidden" onchange="handleFileSelected(this.files)">
                            
                            <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-cyan-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Klik atau seret file gambar ke sini</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Format: JPG, PNG, WEBP (Maksimal 2 MB)</p>
                            </div>
                        </div>

                        {{-- Preview Gambar yang Diunggah --}}
                        <div id="preview-uploaded-container" class="hidden space-y-3 pt-1">
                            <div class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 aspect-video flex items-center justify-center group shadow-sm">
                                <img id="preview-img" src="" alt="Screenshot Bukti" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button type="button" onclick="cancelUpload()" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow transition-colors flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Hapus & Unggah Ulang
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-600 px-1">
                                <span id="preview-filename" class="font-mono truncate max-w-[14rem]">bukti-praktik.png</span>
                                <span id="preview-filesize" class="font-bold text-emerald-600">✓ 1.2 MB</span>
                            </div>
                        </div>

                        {{-- Tombol Navigasi Siswa Terkunci / Aktif --}}
                        <div class="pt-3 border-t border-slate-100 space-y-2">
                            <button type="button"
                                    id="btn-next-page"
                                    disabled
                                    class="w-full py-3.5 px-4 rounded-2xl text-xs font-extrabold text-white bg-slate-300 cursor-not-allowed transition-all flex items-center justify-center gap-2 shadow-sm select-none">
                                <span id="btn-next-text">🔒 Unggah Bukti Screenshot Dahulu</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </button>
                            <p class="text-[11px] text-center text-slate-400">Simulasi: Tombol di atas akan terbuka otomatis setelah file valid terpasang.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        const embedData = @json($data);

        function renderStudentSandbox() {
            const iframe = document.getElementById('student-simulator-iframe');
            if (embedData.embed_type === 'url' && embedData.embed_url) {
                iframe.src = embedData.embed_url;
            } else {
                const code = embedData.embed_code || '<div style="padding:40px;text-align:center;font-family:sans-serif;">Simulator Interaktif</div>';
                const doc = iframe.contentDocument || iframe.contentWindow.document;
                doc.open();
                if (code.toLowerCase().includes('<iframe') || code.toLowerCase().includes('<html')) {
                    doc.write(code);
                } else {
                    doc.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{font-family:system-ui,sans-serif;margin:0;padding:16px;}</style></head><body>${code}</body></html>`);
                }
                doc.close();
            }
        }

        function reloadStudentIframe() {
            renderStudentSandbox();
        }

        function toggleFullscreenSimulator() {
            const container = document.getElementById('sim-container');
            if (!document.fullscreenElement) {
                container.requestFullscreen?.().catch(err => alert('Gagal fullscreen: ' + err.message));
            } else {
                document.exitFullscreen?.();
            }
        }

        // Dropzone interactions
        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('dropzone-box').classList.add('border-cyan-500', 'bg-cyan-50');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            document.getElementById('dropzone-box').classList.remove('border-cyan-500', 'bg-cyan-50');
        }

        function handleFileDrop(e) {
            e.preventDefault();
            handleDragLeave(e);
            if (e.dataTransfer.files.length > 0) {
                handleFileSelected(e.dataTransfer.files);
            }
        }

        function handleFileSelected(files) {
            if (!files || files.length === 0) return;
            const file = files[0];

            // Validasi format
            if (!file.type.match(/image\/(jpeg|png|webp)/)) {
                alert('Format file tidak valid! Harap unggah gambar JPG, PNG, atau WEBP.');
                return;
            }

            // Validasi ukuran (2 MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)! Maksimal ukuran file adalah 2 MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-filename').innerText = file.name;
                document.getElementById('preview-filesize').innerText = '✓ ' + (file.size / 1024).toFixed(1) + ' KB';

                document.getElementById('dropzone-box').classList.add('hidden');
                document.getElementById('preview-uploaded-container').classList.remove('hidden');

                // Unlock restrictive button
                unlockNextButton();
            };
            reader.readAsDataURL(file);
        }

        function cancelUpload() {
            document.getElementById('file-upload-input').value = '';
            document.getElementById('preview-img').src = '';
            document.getElementById('dropzone-box').classList.remove('hidden');
            document.getElementById('preview-uploaded-container').classList.add('hidden');

            // Lock button again
            lockNextButton();
        }

        function unlockNextButton() {
            const btn = document.getElementById('btn-next-page');
            const text = document.getElementById('btn-next-text');

            btn.disabled = false;
            btn.className = 'w-full py-3.5 px-4 rounded-2xl text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 cursor-pointer transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/30 select-none animate-pulse';
            text.innerText = 'Halaman Selanjutnya (Tahap Selesai)';
        }

        function lockNextButton() {
            const btn = document.getElementById('btn-next-page');
            const text = document.getElementById('btn-next-text');

            btn.disabled = true;
            btn.className = 'w-full py-3.5 px-4 rounded-2xl text-xs font-extrabold text-white bg-slate-300 cursor-not-allowed transition-all flex items-center justify-center gap-2 shadow-sm select-none';
            text.innerText = '🔒 Unggah Bukti Screenshot Dahulu';
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderStudentSandbox();
        });
    </script>
</body>
</html>
