<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Video & Ringkasan — {{ $data['video_title'] ?? $module->title }}</title>
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
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-slate-900 to-red-950 shadow-xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-500/20 border border-red-500/30 flex items-center justify-center text-red-400 font-black shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan Video YouTube Siswa</h1>
                    <p class="text-[11px] text-slate-400">Navigasi Restriktif: Siswa wajib mengisi ringkasan sebelum lanjut.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.video.edit', $module) }}"
                   class="px-4 py-2 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition-all flex items-center gap-1.5">
                    <span>←</span> Kembali ke Editor
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ KONTEN PRATINJAU UTAMA ═══ --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-20">
        <div class="w-full animate-fadeInUp space-y-6">

            {{-- Breadcrumb Navigasi Siswa (Paginated Simulation) --}}
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700">E-Modul: {{ $module->title }}</span>
                    <span>/</span>
                    <span class="text-red-600">Bagian Inti (Kegiatan Belajar)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        Tahap: Video Pembelajaran & Ringkasan
                    </span>
                </div>
            </div>

            {{-- Grid Konten Belajar Siswa --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- ── Left Side: Video Player & Info (7 cols) ── --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Card Player --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                        {{-- Video Embed --}}
                        <div class="relative w-full aspect-video bg-black flex items-center justify-center">
                            @if(!empty($data['youtube_id']))
                                <iframe src="https://www.youtube-nocookie.com/embed/{{ $data['youtube_id'] }}?rel=0"
                                        class="w-full h-full absolute inset-0"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen></iframe>
                            @else
                                <div class="p-8 text-center text-slate-400">
                                    <svg class="w-16 h-16 mx-auto mb-2 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                    <p class="text-sm font-semibold">Tautan YouTube Belum Disimpan</p>
                                    <p class="text-xs text-slate-500 mt-1">Silakan atur URL YouTube di halaman editor</p>
                                </div>
                            @endif
                        </div>

                        {{-- Video Info --}}
                        <div class="p-6 space-y-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-red-100 text-red-700">
                                    ⏱️ Durasi Menonton: {{ $data['estimated_duration'] ?? 15 }} Menit
                                </span>
                                <span class="text-xs text-slate-400">Disematkan oleh Guru</span>
                            </div>

                            <h2 class="text-xl font-extrabold text-slate-900 leading-snug">
                                {{ $data['video_title'] ?? 'Video Pembelajaran' }}
                            </h2>

                            @if(!empty($data['instructions']))
                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/70 text-xs text-slate-700 leading-relaxed">
                                    <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Petunjuk Belajar:
                                    </h4>
                                    <p class="whitespace-pre-line">{{ $data['instructions'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Panduan Poin Fokus --}}
                    @if(!empty($data['guiding_questions']) && count($data['guiding_questions']) > 0)
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                    💡
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">Poin Fokus yang Harus Diperhatikan</h3>
                            </div>
                            <ul class="space-y-2">
                                @foreach($data['guiding_questions'] as $q)
                                    <li class="flex items-start gap-2.5 text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                                        <span class="w-5 h-5 rounded-full bg-blue-500 text-white font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                            {{ $loop->iteration }}
                                        </span>
                                        <span class="leading-relaxed">{{ $q }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                {{-- ── Right Side: Ringkasan Form & Gatekeeper (5 cols) ── --}}
                <div class="lg:col-span-5 space-y-6">

                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 space-y-5 sticky top-20">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                                    ✍️
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Kolom Ringkasan Siswa</h3>
                                    <p class="text-[11px] text-slate-500">Ketik intisari video di sini</p>
                                </div>
                            </div>
                        </div>

                        {{-- Form Textarea Sim --}}
                        <div class="space-y-2">
                            <label for="sim_summary_text" class="block text-xs font-bold text-slate-700 flex items-center justify-between">
                                <span>Tulis Ringkasan Anda <span class="text-red-500">*</span></span>
                                <span id="summary-status" class="text-[11px] font-bold text-rose-600">Belum Memenuhi Syarat</span>
                            </label>
                            <textarea id="sim_summary_text"
                                      rows="9"
                                      placeholder="Tuliskan pemahaman Anda dari video di samping secara rinci dan terstruktur..."
                                      class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all resize-y leading-relaxed"></textarea>
                            
                            {{-- Live Counter Indicators --}}
                            <div class="flex items-center justify-between text-[11px] pt-1 px-1">
                                <div class="flex items-center gap-3 font-semibold">
                                    <span id="char-count" class="text-slate-500">0 Karakter</span>
                                    <span class="text-slate-300">•</span>
                                    <span id="word-count" class="text-slate-500">0 Kata</span>
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Min. {{ $data['min_summary_chars'] ?? 100 }} Karakter &bull; {{ $data['min_summary_words'] ?? 20 }} Kata
                                </div>
                            </div>
                        </div>

                        {{-- Gatekeeper Info Box --}}
                        <div id="gatekeeper-box" class="rounded-2xl bg-rose-50 border border-rose-200 p-4 transition-all">
                            <div class="flex items-start gap-2.5 text-xs text-rose-800">
                                <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25-2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                <div>
                                    <p class="font-bold">Halaman Selanjutnya Terkunci 🔒</p>
                                    <p class="text-[11px] text-rose-700 mt-0.5">
                                        Siswa wajib mengetik ringkasan minimal <b>{{ $data['min_summary_chars'] ?? 100 }} karakter</b> (atau <b>{{ $data['min_summary_words'] ?? 20 }} kata</b>) untuk membuka tombol lanjut.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Interactive Simulation Actions --}}
                        <div class="space-y-2 pt-2">
                            <button type="button"
                                    id="btn-next-sim"
                                    disabled
                                    onclick="showSimulateSuccess()"
                                    class="w-full py-3 px-4 rounded-xl text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed transition-all flex items-center justify-center gap-2">
                                <span>Simpan Ringkasan & Lanjut ke Halaman Berikutnya</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </button>

                            <button type="button"
                                    onclick="fillSampleSummary()"
                                    class="w-full py-2 text-[11px] font-semibold text-blue-600 hover:text-blue-700 bg-blue-50/60 hover:bg-blue-50 rounded-lg transition-colors">
                                ⚡ Coba Isi Otomatis Teks Contoh Ringkasan
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- Toast Notifikasi Simulasi --}}
    <div id="sim-toast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div class="bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-slate-700 text-xs font-bold">
            <span class="text-emerald-400 text-base">✅</span>
            <span>Simulasi Berhasil: Ringkasan tersimpan ke database & siswa diarahkan ke tahapan berikutnya!</span>
        </div>
    </div>

    <script>
        const minChars = {{ (int) ($data['min_summary_chars'] ?? 100) }};
        const minWords = {{ (int) ($data['min_summary_words'] ?? 20) }};

        const textarea = document.getElementById('sim_summary_text');
        const charCount = document.getElementById('char-count');
        const wordCount = document.getElementById('word-count');
        const statusLabel = document.getElementById('summary-status');
        const gatekeeperBox = document.getElementById('gatekeeper-box');
        const nextBtn = document.getElementById('btn-next-sim');
        const toast = document.getElementById('sim-toast');

        function countWords(str) {
            return str.trim().split(/\s+/).filter(w => w.length > 0).length;
        }

        function validateSummary() {
            const text = textarea.value;
            const chars = text.length;
            const words = countWords(text);

            charCount.textContent = `${chars} Karakter`;
            wordCount.textContent = `${words} Kata`;

            const isValid = chars >= minChars || words >= minWords;

            if (isValid && chars > 0) {
                statusLabel.textContent = 'Syarat Terpenuhi ✓';
                statusLabel.className = 'text-[11px] font-bold text-emerald-600';

                gatekeeperBox.className = 'rounded-2xl bg-emerald-50 border border-emerald-200 p-4 transition-all';
                gatekeeperBox.innerHTML = `
                    <div class="flex items-start gap-2.5 text-xs text-emerald-800">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25-2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        <div>
                            <p class="font-bold">Kunci Terbuka! 🔓</p>
                            <p class="text-[11px] text-emerald-700 mt-0.5">Ringkasan telah memenuhi panjang minimal. Tombol pengerjaan berikutnya aktif.</p>
                        </div>
                    </div>
                `;

                nextBtn.disabled = false;
                nextBtn.className = 'w-full py-3 px-4 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-600/20 cursor-pointer transition-all flex items-center justify-center gap-2';
            } else {
                statusLabel.textContent = 'Belum Memenuhi Syarat';
                statusLabel.className = 'text-[11px] font-bold text-rose-600';

                gatekeeperBox.className = 'rounded-2xl bg-rose-50 border border-rose-200 p-4 transition-all';
                gatekeeperBox.innerHTML = `
                    <div class="flex items-start gap-2.5 text-xs text-rose-800">
                        <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25-2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        <div>
                            <p class="font-bold">Halaman Selanjutnya Terkunci 🔒</p>
                            <p class="text-[11px] text-rose-700 mt-0.5">
                                Siswa wajib mengetik ringkasan minimal <b>${minChars} karakter</b> (atau <b>${minWords} kata</b>) untuk membuka tombol lanjut.
                            </p>
                        </div>
                    </div>
                `;

                nextBtn.disabled = true;
                nextBtn.className = 'w-full py-3 px-4 rounded-xl text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed transition-all flex items-center justify-center gap-2';
            }
        }

        textarea.addEventListener('input', validateSummary);

        function fillSampleSummary() {
            textarea.value = "Berdasarkan video pembelajaran yang telah disimak secara seksama, topik utama yang dibahas adalah pemahaman konsep dasar dan implementasi praktis di lingkungan kejuruan. Poin-poin krusial yang dijelaskan meliputi standarisasi alur kerja, penerapan sintaks query yang efisien, serta langkah mitigasi kesalahan saat eksekusi data di server produksi.";
            validateSummary();
        }

        function showSimulateSuccess() {
            toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-y-0', 'opacity-100');
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
            }, 3500);
        }
    </script>
</body>
</html>
