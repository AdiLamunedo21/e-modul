{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 8. VIDEO YOUTUBE & RESUME ══════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_video)
@php
    $vList = !empty($videosList) ? $videosList : $module->videosList();
    $minCharsRequired = (int)($videoData['min_summary_chars'] ?? 20);
@endphp
<div x-show="activePage === 'video'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-video">
        {{-- Header Multimedia --}}
        <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold shadow-xs">▶️</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600">Multimedia Pembelajaran ({{ count($vList) }} Video)</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900">{{ $videoData['video_title'] ?? ($videoData['judul_video'] ?? 'Video Pembelajaran YouTube') }}</h2>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @if($videoSummary)
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $videoSummary->manual_score !== null ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        {{ $videoSummary->manual_score !== null ? 'Nilai: ' . $videoSummary->manual_score : 'Resume Terkirim (Pending)' }}
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            {{-- Multi-Video Player & Playlist Switcher --}}
            @if(!empty($vList))
                <div x-data="{ activeVid: 0, vids: {{ json_encode($vList) }} }" class="space-y-4">
                    {{-- Playlist Tabs jika terdapat lebih dari 1 video --}}
                    <template x-if="vids.length > 1">
                        <div class="bg-slate-900 rounded-2xl p-2.5 flex items-center gap-2 overflow-x-auto border border-slate-800 shadow-inner">
                            <span class="text-xs font-bold text-red-400 px-2 shrink-0">Daftar Video:</span>
                            <template x-for="(v, vIdx) in vids" :key="vIdx">
                                <button type="button"
                                        @click="activeVid = vIdx"
                                        :class="activeVid === vIdx ? 'bg-red-600 text-white shadow-md' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 cursor-pointer">
                                    <span>▶</span>
                                    <span x-text="'Video ' + (vIdx + 1)"></span>
                                </button>
                            </template>
                        </div>
                    </template>

                    {{-- Active Video Player --}}
                    <div class="aspect-video w-full rounded-2xl overflow-hidden shadow-lg border border-slate-200 bg-black relative">
                        <template x-for="(v, vIdx) in vids" :key="vIdx">
                            <iframe x-show="activeVid === vIdx"
                                    class="w-full h-full absolute inset-0"
                                    :src="v.embed_url"
                                    :title="v.title"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </template>
                    </div>

                    {{-- Active Video Info & Keterangan --}}
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-red-600"
                                  x-text="'Sedang Diputar: Video ' + (activeVid + 1) + ' dari ' + vids.length"></span>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900" x-text="vids[activeVid]?.title || 'Video Pembelajaran'"></h4>
                        </div>
                        <template x-if="vids[activeVid]?.description && vids[activeVid]?.description.trim().length > 0">
                            <div class="text-xs text-slate-600 leading-relaxed bg-white p-3.5 rounded-xl border border-slate-200/70 whitespace-pre-line shadow-2xs">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan Video:</div>
                                <p x-text="vids[activeVid]?.description"></p>
                            </div>
                        </template>
                    </div>
                </div>
            @endif

            {{-- Petunjuk Belajar & Poin Panduan (Jika ada) --}}
            @if(!empty($videoData['instructions']))
                <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 text-xs text-amber-950 space-y-1">
                    <h5 class="font-bold flex items-center gap-1.5 text-amber-900">
                        <span>📌</span>
                        <span>Petunjuk Belajar:</span>
                    </h5>
                    <p class="leading-relaxed whitespace-pre-line text-amber-900/90">{{ $videoData['instructions'] }}</p>
                </div>
            @endif

            {{-- Form / Tampilan Satu Ringkasan Terpadu Siswa --}}
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>📝</span>
                            <span>Ringkasan / Resume Video Siswa</span>
                        </h4>
                        <p class="text-xs text-slate-500 mt-0.5">Tuliskan 1 (satu) resume intisari pemahaman yang merangkum seluruh video di atas.</p>
                    </div>

                    @if($videoSummary && $videoSummary->manual_score === null)
                        <form action="{{ route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'video']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    @click="openCancelModal({
                                        title: 'Batalkan Resume Video?',
                                        description: 'Apakah Anda yakin ingin membatalkan resume video ini untuk mengedit ulang?',
                                        warningText: 'Teks resume sebelumnya akan dihapus dari sistem dan status pengerjaan modul ini akan direset sampai Anda mengirimkan resume baru.',
                                        confirmLabel: 'Ya, Batalkan Resume'
                                    }, $el.closest('form'))"
                                    class="text-xs text-rose-600 hover:text-rose-700 font-bold underline cursor-pointer">
                                Batalkan / Edit Ulang
                            </button>
                        </form>
                    @endif
                </div>

                @if($videoSummary)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 min-w-0 max-w-full overflow-hidden">
                        <p class="text-xs text-slate-500">Dikirim pada: {{ $videoSummary->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                        <div class="text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium break-words break-all sm:break-words [overflow-wrap:anywhere] [word-break:break-word] max-w-full overflow-hidden">
                            {{ $videoSummary->summary_text }}
                        </div>
                        @if($videoSummary->manual_score !== null)
                            <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between text-xs font-bold text-emerald-800">
                                <span>Nilai Guru:</span>
                                <span class="text-sm">{{ $videoSummary->manual_score }}/100</span>
                            </div>
                        @endif
                        <div class="pt-3 border-t border-slate-200 flex justify-end">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black">✓</span>
                                <span>Sudah Selesai Dikerjakan</span>
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('student.modules.video.submit', $module) }}" method="POST" class="space-y-3"
                          x-data="{ summaryText: '', get charCount() { return this.summaryText.length; }, minChars: {{ $minCharsRequired }} }">
                        @csrf
                        <textarea name="summary_text"
                                  x-model="summaryText"
                                  rows="5"
                                  required
                                  placeholder="Tuliskan poin-poin penting, intisari materi, dan pemahaman yang Anda peroleh setelah menyimak seluruh video di atas (minimal {{ $minCharsRequired }} karakter)..."
                                  class="w-full p-4 text-xs sm:text-sm bg-slate-50 border border-slate-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition outline-none"></textarea>

                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span :class="charCount < minChars ? 'text-amber-600 font-bold' : 'text-emerald-600 font-bold'">
                                Karakter: <span x-text="charCount"></span> (Min. <span x-text="minChars"></span>)
                            </span>
                            <button type="button"
                                    :disabled="charCount < minChars"
                                    :class="charCount < minChars ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 cursor-pointer'"
                                    class="px-6 py-2.5 rounded-xl text-white font-bold text-xs transition"
                                    @click="if (charCount >= minChars) openSubmitModal({
                                        title: 'Kirim Resume Video?',
                                        description: 'Resume video Anda akan disimpan dan dinilai oleh guru. Pastikan isi resume sudah mencakup intisari seluruh video.',
                                        accentColor: 'blue',
                                        warningText: 'Resume yang sudah dikirim tidak dapat diedit kembali.',
                                        confirmLabel: 'Simpan & Kirim Resume'
                                    }, $el.closest('form'))">
                                Simpan & Kirim Resume Video
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
