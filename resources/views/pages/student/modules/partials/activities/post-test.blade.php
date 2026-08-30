{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 12. POST-TEST (EVALUASI AKHIR MODUL) ══════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_post_test && $module->postTest)
<div x-show="activePage === 'post_test'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-rose-200/90 shadow-sm overflow-hidden" id="section-post-test">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <span class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold shrink-0">🏆</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-600">Bagian 5 • Evaluasi Akhir</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">{{ $module->postTest->title ?? 'Post-test: Evaluasi Pemahaman' }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Durasi: {{ $module->postTest->duration_minutes ?? 20 }} Menit • Target KKTP: {{ $module->postTest->kktp ?? 75 }}</p>
                </div>
            </div>

            @if($studentResult && $studentResult->post_test_score !== null)
                <div class="bg-rose-50 px-4 py-2 rounded-2xl border border-rose-200 text-center shrink-0">
                    <span class="text-[10px] font-bold text-rose-700 uppercase block">Skor Post-test</span>
                    <span class="text-2xl font-black text-rose-900">{{ $studentResult->post_test_score }}/100</span>
                </div>
            @endif
        </div>

        <div class="p-6 sm:p-8">
            @if($studentResult && $studentResult->post_test_score !== null)
                <div class="rounded-2xl bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-200 p-6 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-rose-600 text-white flex items-center justify-center text-3xl mx-auto shadow-md">
                        🏆
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Post-test Berhasil Diselesaikan!</h3>
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
                    <div class="pt-2">
                        <template x-if="nextPage">
                            <button type="button"
                                    @click="goToPage(nextPage.id)"
                                    class="px-8 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-rose-600/25 transition cursor-pointer">
                                <span>Lanjut ke <strong x-text="nextPage.title"></strong></span>
                                <span>→</span>
                            </button>
                        </template>
                    </div>
                </div>
            @else
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
                        <button type="button"
                                @click="openSubmitModal({
                                    title: 'Kirim Jawaban Post-test?',
                                    description: 'Ini adalah evaluasi akhir modul. Pastikan seluruh jawaban sudah diperiksa kembali sebelum mengumpulkan.',
                                    accentColor: 'rose',
                                    warningText: 'Post-test hanya dapat dikerjakan satu kali dan menentukan nilai akhir modul.',
                                    confirmLabel: 'Kirim & Selesaikan Modul'
                                }, $el.closest('form'))"
                                class="px-8 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-md shadow-rose-600/30 transition cursor-pointer">
                            Kirim Jawaban Post-test →
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endif
