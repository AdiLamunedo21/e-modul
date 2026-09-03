{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 9. SIMULATOR EMBED / MEDIA INTERAKTIF ═════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_embed)
<div x-show="activePage === 'embed'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-slate-200/90 shadow-sm overflow-hidden" id="section-embed">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold shadow-xs">⚡</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600">Bagian 4 • Praktik Interaktif</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900">{{ $embedData['judul_embed'] ?? 'Eksplorasi Simulator / Embed Media' }}</h2>
                </div>
            </div>
            @if($embedSubmission)
                <span class="px-3.5 py-1 rounded-full text-xs font-bold {{ $embedSubmission->manual_score !== null ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                    {{ $embedSubmission->manual_score !== null ? 'Nilai: ' . $embedSubmission->manual_score : 'Screenshot Terunggah' }}
                </span>
            @endif
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            @if(!empty($embedData['instruksi_praktik']))
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
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
                                    class="text-xs text-red-600 hover:text-red-700 font-bold underline cursor-pointer">
                                Batalkan / Unggah Ulang
                            </button>
                        </form>
                    @endif
                </div>

                @if($embedSubmission)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center gap-4">
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
                            <div class="pt-2">
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                    <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black">✓</span>
                                    <span>Sudah Selesai Dikerjakan</span>
                                </div>
                            </div>
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
                            <button type="button"
                                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer"
                                    @click="openSubmitModal({
                                        title: 'Kirim Bukti Screenshot?',
                                        description: 'Screenshot praktik Anda akan diunggah dan diperiksa oleh guru. Pastikan gambar menampilkan hasil pekerjaan dengan jelas.',
                                        accentColor: 'indigo',
                                        warningText: 'Screenshot yang sudah dikirim tidak dapat diganti.',
                                        confirmLabel: 'Kirim Screenshot'
                                    }, $el.closest('form'))">
                                Kirim Bukti Screenshot
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
