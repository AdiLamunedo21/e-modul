{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 7. URAIAN MATERI PEMBELAJARAN & PPT ═══════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_materi)
<div x-show="activePage === 'materi'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100 bg-gradient-to-r from-blue-50/70 to-slate-50">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-slate-200/80 text-slate-800 text-xs font-bold">
                        📖 Bagian {{ $secMap[3] ?? 3 }}: Kegiatan Belajar
                    </span>
                </div>
                <template x-if="isCompleted('materi')">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Selesai Dipelajari</span>
                    </span>
                </template>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ $materiData['judul_materi'] ?? $module->title }}
            </h2>
        </div>

        {{-- Teks Uraian --}}
        <div class="p-6 sm:p-8 space-y-6">
            <div class="materi-prose text-slate-800 leading-relaxed text-sm sm:text-base">
                @if(!empty($materiData['uraian_materi']))
                    {!! $materiData['uraian_materi'] !!}
                @else
                    <p class="text-slate-400 italic">Materi pembelajaran belum diunggah oleh guru pengampu.</p>
                @endif
            </div>

            {{-- ══ PRATINJAU DOKUMEN SLIDE PRESENTASI & PDF (GAYA GOOGLE SITES) ══ --}}
            @if(!empty($materiData['ppt_file_path']))
                @php
                    $pptPath = $materiData['ppt_file_path'];
                    $pptName = $materiData['ppt_file_name'] ?? 'Dokumen Slide Presentasi';
                    $pptExt = strtolower(pathinfo($pptName, PATHINFO_EXTENSION) ?: pathinfo($pptPath, PATHINFO_EXTENSION));
                    $isPdf = $pptExt === 'pdf';
                    $streamUrl = route('student.modules.materi.stream-ppt', $module);
                    $downloadUrl = route('student.modules.materi.download-ppt', $module);
                    $slides = $materiSlides ?? \App\Services\PptxParserService::parse($pptPath);
                    $hasSlides = !empty($slides) && count($slides) > 0;
                    $fullUrl = url('storage/' . $pptPath);
                    $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($fullUrl);
                    $googleViewerUrl = 'https://docs.google.com/viewer?url=' . urlencode($fullUrl) . '&embedded=true';
                @endphp

                <div x-data="{
                        isFullscreen: false,
                        currentSlide: 1,
                        totalSlides: {{ count($slides) }},
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
                            const el = this.$refs.docPreviewContainer;
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
                     @keydown.window="if (isFullscreen || activePage === 'materi') { if ($event.key === 'ArrowRight' || $event.key === 'PageDown') nextSlide(); else if ($event.key === 'ArrowLeft' || $event.key === 'PageUp') prevSlide(); }"
                     class="mt-8 space-y-3">
                    
                    {{-- Container Frame Pratinjau Dokumen --}}
                    <div x-ref="docPreviewContainer"
                         class="rounded-3xl border border-slate-200/90 shadow-md bg-slate-900 overflow-hidden flex flex-col transition-all"
                         :class="isFullscreen ? 'fixed inset-0 z-[99999] rounded-none border-none shadow-none h-screen w-screen' : 'w-full'">
                        
                        {{-- Top Header / Toolbar ala Google Sites --}}
                        <div class="px-4 sm:px-6 py-3.5 bg-slate-900 text-white flex flex-wrap items-center justify-between gap-3 shrink-0 border-b border-slate-800">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl {{ $isPdf ? 'bg-rose-500' : 'bg-amber-500' }} text-white flex items-center justify-center text-xs font-black shadow-sm shrink-0">
                                    {{ $isPdf ? 'PDF' : 'PPT' }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-xs sm:text-sm font-bold text-white truncate max-w-[200px] sm:max-w-md" title="{{ $pptName }}">
                                            {{ $pptName }}
                                        </h4>
                                        @if($isPdf)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-500/20 text-blue-300 border border-blue-400/30 shrink-0">
                                                <span>📄</span> Dokumen PDF
                                            </span>
                                        @elseif($hasSlides)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 shrink-0">
                                                <span>📊</span> {{ count($slides) }} Slide Interaktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500/20 text-amber-300 border border-amber-400/30 shrink-0">
                                                <span>📑</span> Presentasi PPT
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        @if($isPdf)
                                            Dokumen PDF Pembelajaran Interaktif (Pratinjau Langsung)
                                        @elseif($hasSlides)
                                            Penampil Slide Presentasi Interaktif Mandiri (100% Offline)
                                        @else
                                            Slide Presentasi Pembelajaran
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Action Toolbar: Navigasi Slide, Layar Penuh, Buka Tab Baru, Unduh --}}
                            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                @if(!$isPdf && $hasSlides)
                                    {{-- Kontrol Navigasi Slide Sebelumnya & Selanjutnya --}}
                                    <div class="hidden sm:flex items-center bg-slate-800 rounded-xl p-0.5 border border-slate-700" x-show="viewMode === 'slides'">
                                        <button type="button"
                                                @click="prevSlide()"
                                                :disabled="currentSlide <= 1"
                                                class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700 disabled:opacity-30 disabled:cursor-not-allowed transition"
                                                title="Slide Sebelumnya (Panah Kiri)">
                                            ←
                                        </button>
                                        <span class="px-2.5 text-xs font-mono font-bold text-amber-300">
                                            <span x-text="currentSlide">1</span> / {{ count($slides) }}
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
                                   title="Buka berkas di tab browser baru">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                    </svg>
                                    <span class="hidden sm:inline">Tab Baru</span>
                                </a>

                                {{-- Tombol Unduh Berkas --}}
                                <a href="{{ $downloadUrl }}"
                                   target="_blank"
                                   class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm shadow-blue-600/30"
                                   title="Unduh berkas ke perangkat">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                    </svg>
                                    <span>Unduh</span>
                                </a>
                            </div>
                        </div>

                        {{-- Frame Konten Pratinjau Dokumen --}}
                        <div class="relative w-full bg-slate-950 flex-1 overflow-hidden"
                             :class="isFullscreen ? 'h-full flex items-center justify-center p-4' : 'h-[520px] sm:h-[620px] lg:h-[700px]'">
                            
                            @if($isPdf)
                                {{-- 1. Pratinjau PDF Native Browser (Streaming via Route) --}}
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
                                {{-- 2. Pratinjau PPTX: Slide Deck Interaktif (Mode Utama) --}}
                                <div x-show="viewMode === 'slides'"
                                     class="w-full h-full flex flex-col justify-between overflow-y-auto p-4 sm:p-6 lg:p-8 select-text">
                                    
                                    {{-- Slide Active Container --}}
                                    <div class="max-w-4xl w-full mx-auto flex-1 flex flex-col justify-center">
                                        @foreach($slides as $sIdx => $slide)
                                            <div x-show="currentSlide === {{ $slide['number'] }}"
                                                 x-cloak
                                                 class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden flex flex-col transition-all">
                                                
                                                {{-- Kartu Header Slide --}}
                                                <div class="px-6 py-4 bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white flex items-center justify-between border-b border-indigo-800">
                                                    <div class="flex items-center gap-2.5">
                                                        <span class="w-7 h-7 rounded-lg bg-amber-500 text-white flex items-center justify-center font-black text-xs shadow-xs">
                                                            {{ $slide['number'] }}
                                                        </span>
                                                        <span class="text-xs font-bold text-indigo-200 uppercase tracking-wider">
                                                            Slide {{ $slide['number'] }} dari {{ count($slides) }}
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
                                                                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-slate-200/70 shadow-2xs hover:border-indigo-200 transition">
                                                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-black shrink-0 mt-0.5">
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
                                                    <span class="font-mono font-bold text-indigo-700">Slide {{ $slide['number'] }} / {{ count($slides) }}</span>
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
                                                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 disabled:opacity-30 disabled:cursor-not-allowed text-xs font-bold text-white transition flex items-center gap-1.5 shadow-sm shadow-indigo-600/30 cursor-pointer">
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
                                                   class="w-full h-1.5 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                                            <span class="text-xs font-mono font-bold text-indigo-300 shrink-0">
                                                <span x-text="currentSlide"></span>/{{ count($slides) }}
                                            </span>
                                        </div>

                                        <div class="text-[11px] text-slate-400 hidden md:block">
                                            💡 Gunakan tombol panah keyboard ← / →
                                        </div>
                                    </div>
                                </div>

                                {{-- Fallback Cloud Viewer (Office / Google Docs) --}}
                                <div x-show="viewMode === 'cloud'" x-cloak class="w-full h-full relative">
                                    <iframe :src="cloudViewer === 'office' ? '{{ $officeViewerUrl }}' : '{{ $googleViewerUrl }}'"
                                            class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                            allowfullscreen
                                            title="{{ $pptName }}">
                                    </iframe>
                                </div>

                            @else
                                {{-- 3. File PPT Binary / Tanpa Slide Parser (Office/Google Viewer Fallback) --}}
                                <div class="w-full h-full relative">
                                    <iframe :src="cloudViewer === 'office' ? '{{ $officeViewerUrl }}' : '{{ $googleViewerUrl }}'"
                                            class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                            allowfullscreen
                                            title="{{ $pptName }}">
                                    </iframe>
                                </div>
                            @endif

                        </div>

                        {{-- Footer Informasi & Opsi Pratinjau --}}
                        <div class="px-4 sm:px-6 py-2.5 bg-slate-900 border-t border-slate-800 text-[11px] sm:text-xs text-slate-400 flex flex-wrap items-center justify-between gap-3 shrink-0">
                            <span class="flex items-center gap-1.5">
                                <span>💡</span>
                                @if($isPdf)
                                    <span>Gunakan roda mouse atau kontrol PDF di dalam frame untuk memperbesar (zoom) atau menelusuri halaman.</span>
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
                                            class="text-indigo-400 hover:text-indigo-300 font-semibold underline cursor-pointer">
                                        <span x-text="viewMode === 'slides' ? 'Coba Cloud Viewer (Office/Google) ↗' : 'Kembali ke Slide Interaktif ←'"></span>
                                    </button>
                                </div>
                            @elseif(!$isPdf)
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            @click="cloudViewer = (cloudViewer === 'office' ? 'google' : 'office')"
                                            class="text-blue-400 hover:text-blue-300 font-semibold underline cursor-pointer">
                                        <span x-text="cloudViewer === 'office' ? 'Ganti ke Google Docs Viewer ↗' : 'Ganti ke Office Viewer ↗'"></span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Sentinel akhir bahan bacaan untuk deteksi scroll --}}
            <div class="reading-end-sentinel h-1 w-full pointer-events-none my-1" data-page="materi"></div>

            {{-- Tombol Tandai Selesai Membaca Materi (Hanya tampil di desktop, di mobile sudah terwakili di nav bawah) --}}
            <div class="hidden lg:flex mt-8 pt-6 border-t border-slate-100 flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">
                    💡 Tandai materi ini telah dipelajari untuk membuka langkah berikutnya pada navigasi bawah.
                </p>
                <div>
                    <template x-if="!isCompleted('materi')">
                        <button type="button"
                                @click="markAsRead('materi')"
                                class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs sm:text-sm shadow-md shadow-blue-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                            <span>✓</span>
                            <span>Tandai Selesai Mempelajari</span>
                        </button>
                    </template>
                    <template x-if="isCompleted('materi')">
                        <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs sm:text-sm font-bold">
                            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                            <span>Sudah Selesai Dipelajari</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
