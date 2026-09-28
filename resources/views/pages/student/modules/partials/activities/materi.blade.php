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
                    $pdfPreview = \App\Services\PptConverterService::getPdfPreviewPath($pptPath, $materiData['pdf_preview_path'] ?? null);
                    $hasPdfPreview = !empty($pdfPreview);
                    $vParam = substr(md5(($pdfPreview ?? $pptPath) . ($materiData['ppt_file_size'] ?? '')), 0, 8);
                    $streamUrl = route('student.modules.materi.stream-ppt', ['module' => $module, 'v' => $vParam]);
                    $downloadUrl = route('student.modules.materi.download-ppt', $module);
                    $fullUrl = url('storage/' . $pptPath);
                    $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($fullUrl);
                    $googleViewerUrl = 'https://docs.google.com/viewer?url=' . urlencode($fullUrl) . '&embedded=true';
                @endphp

                <div x-data="{
                        isFullscreen: false,
                        expandedHeight: true,
                        isWide: true,
                        cloudViewer: 'office',
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
                     class="mt-8 space-y-3">
                    
                    {{-- Container Frame Pratinjau Dokumen --}}
                    <div x-ref="docPreviewContainer"
                         class="rounded-3xl border border-slate-200/90 shadow-md bg-slate-900 overflow-hidden flex flex-col transition-all duration-300"
                         :class="isFullscreen ? 'fixed inset-0 z-[99999] rounded-none border-none shadow-none h-screen w-screen' : (isWide ? 'w-auto -mx-3 sm:-mx-6 lg:-mx-8' : 'w-full')">
                        
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
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 shrink-0">
                                                <span>✨</span> Slide Presentasi Visual
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        @if($isPdf)
                                            Dokumen PDF Pembelajaran Interaktif (Pratinjau Langsung)
                                        @else
                                            Pratinjau Visual Slide Sempurna (Format & Desain Asli)
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Action Toolbar: Mode Fokus, Ukuran Halaman, Layar Penuh, Buka Tab Baru, Unduh --}}
                            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                {{-- Mode Fokus (Tutup / Buka Sidebar untuk ruang baca maksimal) --}}
                                <button type="button"
                                        @click="$dispatch('toggle-sidebar-focus')"
                                        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all border border-slate-700 cursor-pointer shadow-xs"
                                        title="Buka / Sembunyikan Sidebar untuk memperluas ruang baca">
                                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    </svg>
                                    <span>Mode Fokus</span>
                                </button>

                                {{-- Toggle Tinggi Penuh (1 Lembar Word A4) vs Mode Ringkas --}}
                                <button type="button"
                                        @click="expandedHeight = !expandedHeight"
                                        class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-700 cursor-pointer shadow-xs"
                                        :title="expandedHeight ? 'Beralih ke ukuran ringkas (680px)' : 'Perlebar seukuran 1 halaman Word penuh (~1180px)'">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                                    </svg>
                                    <span class="hidden sm:inline" x-text="expandedHeight ? '1 Halaman Penuh' : 'Mode Ringkas'">1 Halaman Penuh</span>
                                </button>

                                {{-- Toggle Lebar Maksimal Kontainer Dokumen --}}
                                <button type="button"
                                        @click="isWide = !isWide"
                                        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold transition-all border border-slate-700 cursor-pointer shadow-xs"
                                        :title="isWide ? 'Kembalikan ke lebar standar kartu' : 'Lebarkan ruang baca dokumen hingga batas tepi kartu'">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                    </svg>
                                    <span x-text="isWide ? 'Lebar Normal' : 'Lebar Maksimal'">Lebar Maksimal</span>
                                </button>

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

                                {{-- Tombol Unduh Berkas Asli --}}
                                <a href="{{ $downloadUrl }}"
                                   target="_blank"
                                   class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm shadow-blue-600/30"
                                   title="Unduh file presentasi asli ke perangkat">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                    </svg>
                                    <span>Unduh</span>
                                </a>
                            </div>
                        </div>

                        {{-- Frame Konten Pratinjau Dokumen --}}
                        <div class="relative w-full bg-slate-950 flex-1 overflow-hidden transition-all duration-300"
                             style="min-height: 1180px; height: 1180px;"
                             :style="isFullscreen ? 'height: 100vh !important; min-height: 100vh !important;' : (expandedHeight ? 'height: 1180px !important; min-height: 1180px !important;' : 'height: 680px !important; min-height: 680px !important;')">
                            
                            {{-- 1. Pratinjau Visual Slide / Dokumen PDF (Native Browser Stream) --}}
                            @if($isPdf || $hasPdfPreview)
                                <iframe src="{{ $streamUrl }}#toolbar=1&navpanes=0&view=FitH"
                                        class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                        style="width: 100%; height: 100%; min-height: 100%;"
                                        type="application/pdf"
                                        allowfullscreen
                                        title="{{ $pptName }}">
                                </iframe>
                                <object data="{{ $streamUrl }}" type="application/pdf" class="w-full h-full hidden">
                                    <embed src="{{ $streamUrl }}" type="application/pdf" class="w-full h-full" />
                                </object>
                            @else
                                {{-- 2. Fallback Cloud Viewer (Office / Google Docs) --}}
                                <iframe :src="cloudViewer === 'office' ? '{{ $officeViewerUrl }}' : '{{ $googleViewerUrl }}'"
                                        class="w-full h-full border-0 absolute inset-0 bg-slate-100"
                                        style="width: 100%; height: 100%; min-height: 100%;"
                                        allowfullscreen
                                        title="{{ $pptName }}">
                                </iframe>
                            @endif

                        </div>

                        {{-- Footer Informasi & Opsi Pratinjau --}}
                        <div class="px-4 sm:px-6 py-2.5 bg-slate-900 border-t border-slate-800 text-[11px] sm:text-xs text-slate-400 flex flex-wrap items-center justify-between gap-3 shrink-0">
                            <span class="flex items-center gap-1.5">
                                <span>💡</span>
                                @if($isPdf)
                                    <span>Gunakan roda mouse atau kontrol PDF di dalam frame untuk memperbesar (zoom) atau menelusuri halaman.</span>
                                @else
                                    <span>Pratinjau visual PowerPoint ditampilkan dengan resolusi tinggi (100% tata letak, warna, dan gambar asli).</span>
                                @endif
                            </span>

                            @if(!$isPdf && !$hasPdfPreview)
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            @click="cloudViewer = (cloudViewer === 'office' ? 'google' : 'office')"
                                            class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium border border-slate-700 transition">
                                        Ganti Viewer: <span class="font-bold text-amber-300 uppercase" x-text="cloudViewer"></span>
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
