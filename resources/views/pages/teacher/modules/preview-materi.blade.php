<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Materi — {{ $data['judul_materi'] ?? $module->title }}</title>
    <link rel="icon" href="{{ asset('lgsmk.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }

        /* ── Prose-like styling for materi content ── */
        .materi-prose h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: .75rem; margin-top: 1.5rem; color: #0f172a; }
        .materi-prose h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1.25rem; color: #1e293b; }
        .materi-prose h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1rem; color: #334155; }
        .materi-prose p { margin-bottom: .75rem; line-height: 1.75; }
        .materi-prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: .75rem; }
        .materi-prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: .75rem; }
        .materi-prose li { margin-bottom: .25rem; line-height: 1.65; }
        .materi-prose img { max-width: 100%; height: auto; border-radius: .75rem; margin: 1rem auto; display: block; }
        .materi-prose table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .materi-prose th, .materi-prose td { border: 1px solid #e2e8f0; padding: .5rem .75rem; text-align: left; font-size: .875rem; }
        .materi-prose th { background: #f1f5f9; font-weight: 700; }
        .materi-prose blockquote { border-left: 4px solid #6366f1; background: #eef2ff; padding: .75rem 1rem; margin: 1rem 0; border-radius: 0 .5rem .5rem 0; font-style: italic; }
        .materi-prose hr { border: none; border-top: 2px solid #e2e8f0; margin: 1.5rem 0; }
        .materi-prose a { color: #4f46e5; text-decoration: underline; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp .4s ease-out; }
    </style>
</head>
<body class="bg-slate-100 antialiased text-slate-900 min-h-screen">

    {{-- ═══ TOP BAR ═══ --}}
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 shadow-xl">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-lg shrink-0">📱</div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan Materi Siswa</h1>
                    <p class="text-[11px] text-slate-400">Pratinjau lengkap — uraian teks, dokumen slide, dan rangkuman poin.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.materi.edit', $module) }}"
                   class="px-4 py-2 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition-all flex items-center gap-1.5">
                    <span>←</span> Kembali ke Editor
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ KONTEN PRATINJAU ═══ --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-16">
        <div class="w-full animate-fadeInUp">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200/60">

                {{-- Header Materi --}}
                <div class="p-6 sm:p-8 border-b border-slate-100">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            📖 Uraian Materi Terstruktur
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        {{ $data['judul_materi'] ?? 'Materi Pembelajaran' }}
                    </h1>
                    <p class="text-sm text-slate-500 mt-2 font-medium">
                        {{ $module->title }} — {{ $module->schoolClass->name ?? 'Kelas' }}
                    </p>
                </div>

                {{-- Isi Uraian Materi --}}
                <div class="p-6 sm:p-8">
                    <div class="materi-prose text-slate-800 leading-relaxed text-sm sm:text-base">
                        @if(!empty($data['uraian_materi']))
                            {!! $data['uraian_materi'] !!}
                        @else
                            <p class="text-slate-400 italic">Belum ada uraian materi yang ditulis. Silakan kembali ke editor dan tulis uraian materi terlebih dahulu.</p>
                        @endif
                    </div>

                    {{-- ══ PRATINJAU BERKAS PPT / PDF (GAYA GOOGLE SITES) ══ --}}
                    {{-- ══ PRATINJAU BERKAS PPT / PDF (GAYA GOOGLE SITES) ══ --}}
                    @if(!empty($data['ppt_file_path']))
                        @php
                            $pptPath = $data['ppt_file_path'];
                            $pptName = $data['ppt_file_name'] ?? 'Berkas Presentasi Pembelajaran';
                            $pptExt = strtolower(pathinfo($pptName, PATHINFO_EXTENSION) ?: pathinfo($pptPath, PATHINFO_EXTENSION));
                            $isPdf = $pptExt === 'pdf';
                            $streamUrl = route('teacher.modules.materi.stream-ppt', $module);
                            $downloadUrl = route('teacher.modules.materi.download-ppt', $module);
                            $parsedSlides = $slides ?? \App\Services\PptxParserService::parse($pptPath);
                            $hasSlides = !empty($parsedSlides) && count($parsedSlides) > 0;
                            $fullUrl = url('storage/' . $pptPath);
                            $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($fullUrl);
                            $googleViewerUrl = 'https://docs.google.com/viewer?url=' . urlencode($fullUrl) . '&embedded=true';
                        @endphp

                        <div x-data="{
                                isFullscreen: false,
                                currentSlide: 1,
                                totalSlides: {{ count($parsedSlides) }},
                                viewMode: '{{ $isPdf ? 'pdf' : ($hasSlides ? 'slides' : 'cloud') }}',
                                cloudViewer: 'office',
                                nextSlide() {
                                    if (this.currentSlide < this.totalSlides) {
                                        this.currentSlide++;
                                    }
                                },
                                prevSlide() {
                                    if (this.currentSlide > 1) {
                                        this.currentSlide--;
                                    }
                                },
                                goToSlide(n) {
                                    const val = parseInt(n);
                                    if (!isNaN(val) && val >= 1 && val <= this.totalSlides) {
                                        this.currentSlide = val;
                                    }
                                },
                                toggleFullscreen() {
                                    const el = this.$refs.teacherDocPreview;
                                    if (!document.fullscreenElement) {
                                        if (el.requestFullscreen) {
                                            el.requestFullscreen();
                                        } else if (el.webkitRequestFullscreen) {
                                            el.webkitRequestFullscreen();
                                        }
                                        this.isFullscreen = true;
                                    } else {
                                        if (document.exitFullscreen) {
                                            document.exitFullscreen();
                                        }
                                        this.isFullscreen = false;
                                    }
                                }
                             }"
                             @fullscreenchange.window="isFullscreen = !!document.fullscreenElement"
                             @keydown.window="if ($event.key === 'ArrowRight' || $event.key === 'PageDown') nextSlide(); else if ($event.key === 'ArrowLeft' || $event.key === 'PageUp') prevSlide();"
                             class="mt-8 space-y-3">
                            
                            {{-- Container Frame Pratinjau Dokumen --}}
                            <div x-ref="teacherDocPreview"
                                 class="rounded-3xl border border-violet-200 shadow-md bg-slate-900 overflow-hidden flex flex-col transition-all"
                                 :class="isFullscreen ? 'fixed inset-0 z-[99999] rounded-none border-none shadow-none h-screen w-screen' : 'w-full'">
                                
                                {{-- Top Header / Toolbar ala Google Sites --}}
                                <div class="px-4 sm:px-6 py-3.5 bg-slate-900 text-white flex flex-wrap items-center justify-between gap-3 shrink-0 border-b border-slate-800">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-xl {{ $isPdf ? 'bg-rose-500' : 'bg-violet-600' }} text-white flex items-center justify-center text-xs font-black shadow-sm shrink-0">
                                            {{ $isPdf ? 'PDF' : 'PPT' }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="text-xs sm:text-sm font-bold text-white truncate max-w-[200px] sm:max-w-md" title="{{ $pptName }}">
                                                    {{ $pptName }}
                                                </h4>
                                                @if($isPdf)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500/20 text-rose-300 border border-rose-400/30 shrink-0">
                                                        <span>📄</span> Dokumen PDF
                                                    </span>
                                                @elseif($hasSlides)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-violet-500/20 text-violet-300 border border-violet-400/30 shrink-0">
                                                        <span>📊</span> {{ count($parsedSlides) }} Slide Interaktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 shrink-0">
                                                        <span>✓</span> Tersedia untuk Siswa
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-400 truncate">
                                                @if($isPdf)
                                                    Dokumen PDF Pembelajaran Interaktif (Simulasi Siswa)
                                                @elseif($hasSlides)
                                                    Penampil Slide PPTX Interaktif Mandiri (100% Offline)
                                                @else
                                                    Slide Presentasi Pembelajaran
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Action Toolbar: Navigasi Slide, Layar Penuh, Buka Tab Baru, Unduh --}}
                                    <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                        @if(!$isPdf && $hasSlides)
                                            <div class="hidden sm:flex items-center bg-slate-800 rounded-xl p-0.5 border border-slate-700" x-show="viewMode === 'slides'">
                                                <button type="button"
                                                        @click="prevSlide()"
                                                        :disabled="currentSlide <= 1"
                                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700 disabled:opacity-30 disabled:cursor-not-allowed transition"
                                                        title="Slide Sebelumnya (Panah Kiri)">
                                                    ←
                                                </button>
                                                <span class="px-2.5 text-xs font-mono font-bold text-violet-300">
                                                    <span x-text="currentSlide">1</span> / {{ count($parsedSlides) }}
                                                </span>
                                                <button type="button"
                                                        @click="nextSlide()"
                                                        :disabled="currentSlide >= totalSlides"
                                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700 disabled:opacity-30 disabled:cursor-not-allowed transition"
                                                        title="Slide Selanjutnya (Panah Kanan)">
                                                    →
                                                </button>
                                            </div>
                                        @endif

                                        {{-- Tombol Mode Layar Penuh --}}
                                        <button type="button"
                                                @click="toggleFullscreen()"
                                                class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-700 cursor-pointer shadow-xs"
                                                :title="isFullscreen ? 'Keluar dari Layar Penuh (Esc)' : 'Buka dalam Layar Penuh (Fullscreen)'">
                                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/>
                                            </svg>
                                            <span class="hidden sm:inline" x-text="isFullscreen ? 'Keluar' : 'Layar Penuh'">Layar Penuh</span>
                                        </button>

                                        {{-- Buka di Tab Baru / Stream Inline --}}
                                        <a href="{{ $streamUrl }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-700 shadow-xs"
                                           title="Buka dokumen di tab baru">
                                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                            </svg>
                                            <span class="hidden sm:inline">Tab Baru</span>
                                        </a>

                                        {{-- Tombol Unduh Berkas --}}
                                        <a href="{{ $downloadUrl }}"
                                           target="_blank"
                                           class="px-3.5 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm shadow-violet-600/30"
                                           title="Unduh berkas presentasi">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                            </svg>
                                            <span>Unduh</span>
                                        </a>
                                    </div>
                                </div>

                                {{-- Frame Konten Pratinjau Dokumen --}}
                                <div class="relative w-full bg-slate-950 flex-1 overflow-hidden"
                                     :class="isFullscreen ? 'h-full flex items-center justify-center p-4' : 'h-[500px] sm:h-[600px] lg:h-[680px]'">
                                    @if($isPdf)
                                        {{-- 1. PDF Native Streaming --}}
                                        <iframe src="{{ $streamUrl }}#toolbar=1&navpanes=1"
                                                class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                                type="application/pdf"
                                                allowfullscreen
                                                title="{{ $pptName }}">
                                        </iframe>
                                        <object data="{{ $streamUrl }}" type="application/pdf" class="w-full h-full hidden">
                                            <embed src="{{ $streamUrl }}" type="application/pdf" class="w-full h-full" />
                                        </object>

                                    @elseif($hasSlides)
                                        {{-- 2. Slide Deck Interaktif --}}
                                        <div x-show="viewMode === 'slides'"
                                             class="w-full h-full flex flex-col justify-between overflow-y-auto p-4 sm:p-6 lg:p-8 select-text">
                                            
                                            {{-- Slide Active Container --}}
                                            <div class="max-w-4xl w-full mx-auto flex-1 flex flex-col justify-center">
                                                @foreach($parsedSlides as $sIdx => $slide)
                                                    <div x-show="currentSlide === {{ $slide['number'] }}"
                                                         x-cloak
                                                         class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden flex flex-col transition-all">
                                                        
                                                        {{-- Kartu Header Slide --}}
                                                        <div class="px-6 py-4 bg-gradient-to-r from-violet-950 via-slate-900 to-indigo-950 text-white flex items-center justify-between border-b border-violet-800">
                                                            <div class="flex items-center gap-2.5">
                                                                <span class="w-7 h-7 rounded-lg bg-violet-500 text-white flex items-center justify-center font-black text-xs shadow-xs">
                                                                    {{ $slide['number'] }}
                                                                </span>
                                                                <span class="text-xs font-bold text-violet-200 uppercase tracking-wider">
                                                                    Slide {{ $slide['number'] }} dari {{ count($parsedSlides) }}
                                                                </span>
                                                            </div>
                                                            <span class="text-[11px] font-medium text-slate-400 truncate max-w-[200px]">
                                                                {{ $module->title }}
                                                            </span>
                                                        </div>

                                                        {{-- Konten Utama Slide --}}
                                                        <div class="p-6 sm:p-8 lg:p-10 space-y-6 flex-1 bg-gradient-to-b from-white to-slate-50">
                                                            {{-- Judul Slide --}}
                                                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 leading-snug border-b border-slate-200/80 pb-4">
                                                                {{ $slide['title'] }}
                                                            </h3>

                                                            {{-- Baris Poin / Paragraf Slide --}}
                                                            @if(!empty($slide['lines']) && count($slide['lines']) > 0)
                                                                <div class="space-y-3">
                                                                    @foreach($slide['lines'] as $lineIdx => $line)
                                                                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-slate-200/70 shadow-2xs hover:border-violet-200 transition">
                                                                            <div class="w-6 h-6 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center text-xs font-black shrink-0 mt-0.5">
                                                                                {{ $lineIdx + 1 }}
                                                                            </div>
                                                                            <p class="text-sm sm:text-base text-slate-800 font-medium leading-relaxed">
                                                                                {{ $line }}
                                                                            </p>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="py-12 text-center text-slate-400 italic">
                                                                    (Halaman judul atau transisi slide materi)
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Footer Mini Slide --}}
                                                        <div class="px-6 py-3 bg-slate-100 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-500">
                                                            <span>🏫 SMKN 3 Yogyakarta — E-Modul Pembelajaran</span>
                                                            <span class="font-mono font-bold text-violet-700">Slide {{ $slide['number'] }} / {{ count($parsedSlides) }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Bottom Slide Navigation Bar --}}
                                            <div class="max-w-4xl w-full mx-auto mt-4 pt-3 flex flex-wrap items-center justify-between gap-3 bg-slate-900/90 backdrop-blur-md p-3.5 rounded-2xl border border-slate-800 text-white shrink-0">
                                                <div class="flex items-center gap-2">
                                                    <button type="button"
                                                            @click="prevSlide()"
                                                            :disabled="currentSlide <= 1"
                                                            class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 disabled:opacity-30 disabled:cursor-not-allowed text-xs font-bold text-white transition flex items-center gap-1.5 border border-slate-700 shadow-xs cursor-pointer">
                                                        <span>←</span>
                                                        <span class="hidden sm:inline">Sebelumnya</span>
                                                    </button>
                                                    <button type="button"
                                                            @click="nextSlide()"
                                                            :disabled="currentSlide >= totalSlides"
                                                            class="px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-500 disabled:opacity-30 disabled:cursor-not-allowed text-xs font-bold text-white transition flex items-center gap-1.5 shadow-sm shadow-violet-600/30 cursor-pointer">
                                                        <span class="hidden sm:inline">Selanjutnya</span>
                                                        <span>→</span>
                                                    </button>
                                                </div>

                                                {{-- Slider / Range Slide Selector --}}
                                                <div class="flex items-center gap-3 flex-1 max-w-xs justify-center">
                                                    <input type="range"
                                                           min="1"
                                                           :max="totalSlides"
                                                           x-model="currentSlide"
                                                           class="w-full h-1.5 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-violet-500">
                                                    <span class="text-xs font-mono font-bold text-violet-300 shrink-0">
                                                        <span x-text="currentSlide"></span>/{{ count($parsedSlides) }}
                                                    </span>
                                                </div>

                                                <div class="text-[11px] text-slate-400 hidden md:block">
                                                    💡 Gunakan tombol panah keyboard ← / →
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Fallback Cloud Viewer --}}
                                        <div x-show="viewMode === 'cloud'" x-cloak class="w-full h-full relative">
                                            <iframe :src="cloudViewer === 'office' ? '{{ $officeViewerUrl }}' : '{{ $googleViewerUrl }}'"
                                                    class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                                    allowfullscreen
                                                    title="{{ $pptName }}">
                                            </iframe>
                                        </div>

                                    @else
                                        {{-- Fallback PPT Binary --}}
                                        <div class="w-full h-full relative">
                                            <iframe :src="cloudViewer === 'office' ? '{{ $officeViewerUrl }}' : '{{ $googleViewerUrl }}'"
                                                    class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                                    allowfullscreen
                                                    title="{{ $pptName }}">
                                            </iframe>
                                        </div>
                                    @endif
                                </div>

                                {{-- Footer Informasi --}}
                                <div class="px-4 sm:px-6 py-2.5 bg-slate-900 border-t border-slate-800 text-[11px] sm:text-xs text-slate-400 flex flex-wrap items-center justify-between gap-3 shrink-0">
                                    <span class="flex items-center gap-1.5">
                                        <span>👁️</span>
                                        @if($isPdf)
                                            <span>Ini adalah tampilan yang akan dilihat langsung oleh siswa (format PDF native streaming).</span>
                                        @elseif($hasSlides)
                                            <span>Slide interaktif diproses langsung dari berkas PPTX secara mandiri dan dapat dioperasikan secara offline.</span>
                                        @else
                                            <span>Gunakan opsi di samping jika pratinjau cloud belum termuat.</span>
                                        @endif
                                    </span>

                                    @if(!$isPdf && $hasSlides)
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                    @click="viewMode = (viewMode === 'slides' ? 'cloud' : 'slides')"
                                                    class="text-violet-400 hover:text-violet-300 font-semibold underline cursor-pointer">
                                                <span x-text="viewMode === 'slides' ? 'Coba Cloud Viewer (Office/Google) ↗' : 'Kembali ke Slide Interaktif ←'"></span>
                                            </button>
                                        </div>
                                    @elseif(!$isPdf)
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                    @click="cloudViewer = (cloudViewer === 'office' ? 'google' : 'office')"
                                                    class="text-violet-400 hover:text-violet-300 font-semibold underline cursor-pointer">
                                                <span x-text="cloudViewer === 'office' ? 'Ganti ke Google Docs Viewer ↗' : 'Ganti ke Office Viewer ↗'"></span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Poin Penting / Rangkuman --}}
                    @if(!empty($data['poin_penting']) && count($data['poin_penting']) > 0)
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h4 class="text-sm font-extrabold text-slate-900 mb-3 flex items-center gap-2">
                                <span>📌</span> Rangkuman Poin Kunci:
                            </h4>
                            <div class="space-y-2.5">
                                @foreach($data['poin_penting'] as $idx => $poin)
                                    @if(!empty(trim($poin)))
                                        <div class="flex items-start gap-3 p-3 rounded-xl bg-amber-50/70 border border-amber-200/80 text-xs text-amber-950">
                                            <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center font-bold text-[10px] shrink-0">{{ $idx + 1 }}</span>
                                            <p class="leading-relaxed font-medium">{{ $poin }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5 font-medium text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Mode Membaca Siswa (Responsif)
                    </span>
                    <span class="text-slate-400 font-medium">Data tersimpan terakhir dari server</span>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
