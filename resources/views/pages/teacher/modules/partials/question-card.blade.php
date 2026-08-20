@php
    $qId = $qIndex ?? 0;
    if (is_object($question)) {
        $pertanyaan = $question->question_text;
        $pilihan = $question->options ?? ['A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => ''];
        $kunci = $question->correct_answer ?? 'A';
        $bobot = $question->score_weight ?? 10;
        $pembahasan = $question->explanation ?? '';
    } else {
        $pertanyaan = $question['question_text'] ?? $question['pertanyaan'] ?? '';
        $pilihan = $question['options'] ?? $question['pilihan'] ?? ['A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => ''];
        $kunci = $question['correct_answer'] ?? $question['kunci_jawaban'] ?? 'A';
        $bobot = $question['score_weight'] ?? $question['bobot'] ?? 10;
        $pembahasan = $question['explanation'] ?? $question['pembahasan'] ?? '';
    }
    $accent = $accent ?? 'blue';

    $isTeal = ($accent === 'teal');
    $badgeBg = $isTeal ? 'bg-teal-600 shadow-teal-500/30' : 'bg-blue-600 shadow-blue-500/30';
    $pointColor = $isTeal ? 'text-teal-700' : 'text-blue-600';
    $focusClass = $isTeal ? 'focus:border-teal-500 focus:ring-teal-500/20' : 'focus:border-blue-500 focus:ring-blue-500/20';
    $optFocus = $isTeal ? 'focus:border-teal-500 focus:ring-teal-500' : 'focus:border-blue-500 focus:ring-blue-500';
    $hoverText = $isTeal ? 'hover:text-teal-700' : 'hover:text-blue-600';
@endphp

<div class="question-card fade-in-item bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative" id="question-card-{{ $qId }}">
    <div class="flex items-center justify-between gap-4 mb-4 pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <span class="q-number-badge w-7 h-7 rounded-xl {{ $badgeBg }} text-white flex items-center justify-center text-xs font-black shadow-sm">
                {{ $qId + 1 }}
            </span>
            <span class="text-xs font-bold text-slate-800">Pertanyaan Soal #{{ $qId + 1 }}</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1">
                <label class="text-[11px] font-semibold text-slate-500">Bobot Poin:</label>
                <input type="number" name="questions[{{ $qId }}][score_weight]" value="{{ $bobot }}" min="1" max="100"
                       oninput="updateSummary()"
                       class="w-12 text-center text-xs font-bold {{ $pointColor }} bg-transparent focus:outline-none">
            </div>
            <button type="button" onclick="duplicateQuestion({{ $qId }})" title="Duplikat Soal"
                    class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
            </button>
            <button type="button" onclick="deleteQuestion({{ $qId }})" title="Hapus Soal"
                    class="w-8 h-8 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
            </button>
        </div>
    </div>

    {{-- Teks Pertanyaan --}}
    <div class="mb-4">
        <label class="block text-xs font-bold text-slate-700 mb-1.5">Teks Soal / Pertanyaan</label>
        <textarea name="questions[{{ $qId }}][question_text]" rows="3" required
                  placeholder="Tuliskan butir soal di sini..."
                  class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 {{ $focusClass }} focus:bg-white focus:outline-none focus:ring-2 transition-all">{{ $pertanyaan }}</textarea>
    </div>

    {{-- Pilihan Ganda (A, B, C, D, E) --}}
    <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
            <label class="block text-xs font-bold text-slate-700">Pilihan Jawaban & Kunci Jawaban</label>
            <span class="text-[11px] text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 font-semibold">
                Pilih radio button pada jawaban yang benar
            </span>
        </div>

        <div class="space-y-2.5">
            @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                @php
                    $isCorrect = ($kunci === $opt);
                    $optVal = is_array($pilihan) ? ($pilihan[$opt] ?? '') : '';
                @endphp
                <div class="option-row flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 {{ $isCorrect ? 'is-correct' : '' }}">
                    <label class="flex items-center gap-2 cursor-pointer shrink-0">
                        <input type="radio" name="questions[{{ $qId }}][correct_answer]" value="{{ $opt }}"
                               {{ $isCorrect ? 'checked' : '' }}
                               onchange="onCorrectAnswerChange(this, {{ $qId }})"
                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <span class="w-6 h-6 rounded-lg {{ $isCorrect ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-700' }} flex items-center justify-center text-xs font-bold option-label">
                            {{ $opt }}
                        </span>
                    </label>
                    <input type="text" name="questions[{{ $qId }}][options][{{ $opt }}]"
                           value="{{ $optVal }}"
                           {{ $opt === 'A' || $opt === 'B' ? 'required' : '' }}
                           placeholder="Pilihan {{ $opt }} {{ $opt === 'A' || $opt === 'B' ? '(Wajib)' : '(Opsional)' }}..."
                           class="flex-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-900 {{ $optFocus }} focus:outline-none focus:ring-1 transition-all">
                </div>
            @endforeach
        </div>
    </div>

    {{-- Pembahasan Jawaban (Opsional) --}}
    <div class="pt-3 border-t border-slate-100">
        <details class="group" {{ !empty($pembahasan) ? 'open' : '' }}>
            <summary class="cursor-pointer text-xs font-bold text-slate-600 {{ $hoverText }} flex items-center gap-1.5 select-none">
                <svg class="w-3.5 h-3.5 transform group-open:rotate-90 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span>Tambahkan Pembahasan / Keterangan Jawaban (Opsional)</span>
            </summary>
            <div class="mt-2.5 pl-5">
                <textarea name="questions[{{ $qId }}][explanation]" rows="2"
                          placeholder="Tuliskan alasan mengapa jawaban tersebut benar untuk referensi..."
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs text-slate-800 {{ $optFocus }} focus:bg-white focus:outline-none transition-all resize-none">{{ $pembahasan }}</textarea>
            </div>
        </details>
    </div>
</div>
