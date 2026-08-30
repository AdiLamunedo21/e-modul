{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 6. PRE-TEST (SOAL LATIHAN DIAGNOSTIK) ═════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_pre_test && $module->preTest)
<div x-show="activePage === 'pre_test'" x-cloak class="w-full space-y-6 text-left">
    <div class="rounded-3xl bg-white border border-teal-200/90 shadow-sm overflow-hidden" id="section-pre-test">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <span class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-2xl font-bold shrink-0">⚡</span>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-600">Bagian 2 • Latihan Diagnostik</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">{{ $module->preTest->title ?? 'Pre-test Pembuka' }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Durasi: {{ $module->preTest->duration_minutes ?? 15 }} Menit • Target KKTP: {{ $module->preTest->kktp ?? 75 }}</p>
                </div>
            </div>

            @if($studentResult && $studentResult->pre_test_score !== null)
                <div class="bg-teal-50 px-4 py-2 rounded-2xl border border-teal-200 text-center shrink-0">
                    <span class="text-[10px] font-bold text-teal-700 uppercase block">Skor Pre-test</span>
                    <span class="text-2xl font-black text-teal-900">{{ $studentResult->pre_test_score }}/100</span>
                </div>
            @endif
        </div>

        <div class="p-6 sm:p-8">
            @if($studentResult && $studentResult->pre_test_score !== null)
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-6 text-center space-y-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center text-2xl mx-auto shadow-md">
                        ✓
                    </div>
                    <h3 class="text-lg font-black text-emerald-950">Pre-test Telah Diselesaikan!</h3>
                    <p class="text-xs sm:text-sm text-emerald-800 max-w-md mx-auto leading-relaxed">
                        Anda telah menyelesaikan tes diagnostik ini dengan perolehan skor <strong>{{ $studentResult->pre_test_score }}</strong>.
                        Silakan lanjutkan mempelajari materi pembelajaran pada langkah berikutnya.
                    </p>
                    <div class="pt-2">
                        <template x-if="nextPage">
                            <button type="button"
                                    @click="goToPage(nextPage.id)"
                                    class="px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-600/25 transition cursor-pointer">
                                <span>Lanjut ke <strong x-text="nextPage.title"></strong></span>
                                <span>→</span>
                            </button>
                        </template>
                    </div>
                </div>
            @else
                <form action="{{ route('student.modules.pre-test.submit', $module) }}" method="POST" class="space-y-8">
                    @csrf
                    <p class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                        💡 <strong>Petunjuk:</strong> Pilihlah salah satu jawaban yang paling tepat (A, B, C, D, atau E) untuk setiap butir soal, kemudian klik tombol <strong>Kirim Jawaban Pre-test</strong>.
                    </p>

                    @foreach($module->preTest->questions as $idx => $q)
                        <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                            <div class="flex items-start gap-3">
                                <span class="w-7 h-7 rounded-xl bg-teal-600 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="text-sm font-bold text-slate-900 leading-relaxed flex-1">
                                    {{ $q->question_text }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-2.5 pl-10">
                                @foreach(['A', 'B', 'C', 'D', 'E'] as $optKey)
                                    @if(!empty($q->options[$optKey]))
                                        <label class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:border-teal-400 hover:bg-teal-50/20 cursor-pointer transition">
                                            <input type="radio"
                                                   name="answers[{{ $q->id }}]"
                                                   value="{{ $optKey }}"
                                                   required
                                                   class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-slate-300">
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
                                    title: 'Kirim Jawaban Pre-test?',
                                    description: 'Pastikan Anda telah menjawab seluruh soal dengan teliti. Jawaban tidak dapat diubah setelah dikirim.',
                                    accentColor: 'teal',
                                    warningText: 'Pre-test hanya dapat dikerjakan satu kali. Tindakan ini tidak dapat dibatalkan.',
                                    confirmLabel: 'Kirim Jawaban Pre-test'
                                }, $el.closest('form'))"
                                class="px-8 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-md shadow-teal-600/30 transition cursor-pointer">
                            Kirim Jawaban Pre-test →
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endif
