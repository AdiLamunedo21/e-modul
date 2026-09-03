{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 12. POST-TEST (EVALUASI AKHIR MODUL) ══════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_post_test && $module->postTest)
@php
    $postTestAttempts = $studentResult?->getTestAttempts('post_test') ?? [];
    $postTestAttemptCount = $studentResult?->getTestAttemptCount('post_test') ?? 0;
    $latestPostRetakeScore = $studentResult?->getLatestRetakeScore('post_test');
    $initialPostScore = $studentResult?->post_test_score;
    $hasRetake = $latestPostRetakeScore !== null;
@endphp

<div x-show="activePage === 'post_test'" x-cloak class="w-full space-y-6 text-left"
     x-data="{ showRetakeForm: false, showHistory: false }">
    <div class="rounded-3xl bg-white border border-rose-200/90 shadow-sm overflow-hidden" id="section-post-test">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <span class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold shrink-0">🏆</span>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-rose-600">Bagian 5 • Evaluasi Akhir</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Post-test Sumatif</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ $module->postTest->title ?? 'Post-test: Evaluasi Pemahaman' }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Durasi: {{ $module->postTest->duration_minutes ?? 20 }} Menit • Target KKTP: {{ $module->postTest->kktp ?? 75 }}</p>
                </div>
            </div>

            @if($initialPostScore !== null)
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <div class="bg-rose-50/90 px-3.5 py-1.5 rounded-2xl border border-rose-200 text-center">
                        <span class="text-[9px] font-bold text-rose-700 uppercase tracking-wider block">Nilai Awal (Resmi)</span>
                        <span class="text-xl font-black text-rose-900">{{ $initialPostScore }}/100</span>
                    </div>
                    @if($hasRetake)
                        <div class="bg-indigo-50/90 px-3.5 py-1.5 rounded-2xl border border-indigo-200 text-center">
                            <span class="text-[9px] font-bold text-indigo-700 uppercase tracking-wider block">Latihan Terakhir</span>
                            <span class="text-xl font-black text-indigo-900">{{ $latestPostRetakeScore }}/100</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-6 sm:p-8">
            @if($initialPostScore !== null)
                {{-- ═══ 1. CARD HASIL & PERBANDINGAN NILAI POST-TEST ═══ --}}
                <div x-show="!showRetakeForm" class="space-y-6">
                    <div class="rounded-3xl bg-gradient-to-br from-rose-50/70 via-pink-50/40 to-slate-50 border border-rose-200/90 p-6 sm:p-8 space-y-6">
                        
                        {{-- Header Status --}}
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                            <div class="w-14 h-14 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-2xl shrink-0 shadow-md shadow-rose-600/20">
                                🏆
                            </div>
                            <div class="space-y-1 flex-1">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <h3 class="text-xl font-black text-slate-900">Post-test Berhasil Diselesaikan!</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        {{ $postTestAttemptCount > 1 ? $postTestAttemptCount . 'x Dikerjakan' : 'Percobaan Pertama' }}
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
                                    Anda dapat mengerjakan ulang soal Post-test ini untuk mengukur kemajuan penguasaan materi. 
                                    <strong>Nilai awal resmi (percobaan pertama) tetap menjadi acuan permanen nilai akhir modul</strong>.
                                </p>
                            </div>
                        </div>

                        {{-- Panel Perbandingan Nilai Awal vs Latihan Pengulangan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            {{-- Box 1: Nilai Awal Resmi --}}
                            <div class="p-5 rounded-2xl bg-white border border-rose-200/80 shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Awal (Resmi)</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-100 text-rose-800 border border-rose-200 uppercase">
                                        Permanen / Penentu Nilai
                                    </span>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-rose-600">{{ $initialPostScore }}</span>
                                    <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                </div>
                                <div class="pt-1 border-t border-slate-100 text-[11px] text-slate-500 leading-normal">
                                    @if($studentResult->pre_test_score !== null)
                                        @php $deltaPrePost = $initialPostScore - $studentResult->pre_test_score; @endphp
                                        <span class="font-semibold {{ $deltaPrePost >= 0 ? 'text-emerald-700' : 'text-slate-600' }}">
                                            📊 Kemajuan dari Pre-test: {{ $deltaPrePost >= 0 ? '+' . $deltaPrePost : $deltaPrePost }} Poin
                                        </span>
                                    @else
                                        <span>📌 Nilai ini tersimpan pada rekapitulasi nilai akhir modul Anda.</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Box 2: Nilai Latihan Pengulangan Terbaru --}}
                            <div class="p-5 rounded-2xl bg-white border {{ $hasRetake ? 'border-indigo-200/90' : 'border-slate-200/80 border-dashed' }} shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Latihan Terakhir</span>
                                    @if($hasRetake)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase">
                                            Percobaan ke-{{ $postTestAttemptCount }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            Belum Diulang
                                        </span>
                                    @endif
                                </div>

                                @if($hasRetake)
                                    @php $deltaPost = $latestPostRetakeScore - $initialPostScore; @endphp
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black text-indigo-700">{{ $latestPostRetakeScore }}</span>
                                        <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                    </div>
                                    <div class="pt-1 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold">
                                        @if($deltaPost > 0)
                                            <span class="text-emerald-600 flex items-center gap-1">
                                                <span>📈</span>
                                                <span>Peningkatan: +{{ $deltaPost }} Poin dari nilai awal</span>
                                            </span>
                                        @elseif($deltaPost === 0)
                                            <span class="text-blue-600 flex items-center gap-1">
                                                <span>🎯</span>
                                                <span>Konsisten: Sama dengan nilai awal ({{ $initialPostScore }})</span>
                                            </span>
                                        @else
                                            <span class="text-amber-600 flex items-center gap-1">
                                                <span>📉</span>
                                                <span>Selisih: {{ $deltaPost }} Poin dari nilai awal</span>
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="py-2 text-xs text-slate-400 italic">
                                        Anda belum pernah mengulang soal Post-test ini. Klik tombol <strong>"Latihan Ulang Soal"</strong> di bawah untuk menguji kembali pemahaman Anda.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Riwayat Percobaan Lengkap (Collapsible jika > 1 kali) --}}
                        @if(count($postTestAttempts) > 1)
                            <div class="pt-2">
                                <button type="button"
                                        @click="showHistory = !showHistory"
                                        class="text-xs font-bold text-rose-700 hover:text-rose-900 inline-flex items-center gap-1.5 cursor-pointer">
                                    <span x-text="showHistory ? '▼ Sembunyikan Riwayat Percobaan' : '▶ Tampilkan Riwayat Percobaan (' + {{ count($postTestAttempts) }} + 'x pengerjaan)'"></span>
                                </button>
                                
                                <div x-show="showHistory" x-cloak class="mt-3 rounded-2xl bg-white border border-slate-200 overflow-hidden">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                                            <tr>
                                                <th class="py-2.5 px-4">Percobaan</th>
                                                <th class="py-2.5 px-4">Status Nilai</th>
                                                <th class="py-2.5 px-4 text-center">Skor</th>
                                                <th class="py-2.5 px-4 text-right">Waktu Pengerjaan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($postTestAttempts as $att)
                                                <tr class="{{ !empty($att['is_initial']) ? 'bg-rose-50/40 font-semibold' : '' }}">
                                                    <td class="py-2 px-4">Percobaan ke-{{ $att['attempt'] ?? 1 }}</td>
                                                    <td class="py-2 px-4">
                                                        @if(!empty($att['is_initial']))
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Nilai Awal Resmi</span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Latihan Ulang</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-4 text-center font-bold {{ !empty($att['is_initial']) ? 'text-rose-700' : 'text-slate-800' }}">
                                                        {{ $att['score'] ?? '-' }}/100
                                                    </td>
                                                    <td class="py-2 px-4 text-right text-slate-400 text-[11px]">
                                                        {{ !empty($att['timestamp']) ? \Carbon\Carbon::parse($att['timestamp'])->locale('id')->isoFormat('D MMM Y, HH:mm') : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        {{-- Action Buttons: Latihan Ulang & Lanjut Halaman --}}
                        <div class="pt-4 border-t border-rose-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <button type="button"
                                    @click="showRetakeForm = true"
                                    class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-white hover:bg-rose-50 text-rose-700 border border-rose-300 font-bold text-xs sm:text-sm shadow-2xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <span>🔄</span>
                                <span>Kerjakan Ulang Soal (Latihan Mandiri)</span>
                            </button>

                            <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs sm:text-sm font-bold">
                                <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-black">✓</span>
                                <span>Sudah Selesai Dikerjakan</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ═══ 2. FORM PENGERJAAN SOAL POST-TEST (TAMPIL JIKA BELUM PERNAH ATAU SEDANG RETAKE) ═══ --}}
            <div x-show="showRetakeForm || {{ $initialPostScore === null ? 'true' : 'false' }}">
                @if($initialPostScore !== null)
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">💡</span>
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <strong class="font-bold block text-sm mb-0.5">Mode Latihan Ulang Soal Post-Test</strong>
                                Anda sedang mengerjakan ulang soal sebagai pengayaan mandiri. 
                                <strong>Nilai awal resmi Anda ({{ $initialPostScore }}/100) tetap terkunci</strong> sebagai penentu nilai akhir modul. Hasil pengulangan ini dicatat sebagai perbandingan kemajuan belajar Anda.
                            </div>
                        </div>
                        <button type="button"
                                @click="showRetakeForm = false"
                                class="px-3 py-1.5 rounded-xl bg-white text-slate-700 hover:bg-slate-100 border border-amber-300 text-xs font-bold shrink-0 transition cursor-pointer">
                            ✕ Batal
                        </button>
                    </div>
                @endif

                <form action="{{ route('student.modules.post-test.submit', $module) }}" method="POST" class="space-y-8">
                    @csrf
                    <p class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                        💡 <strong>Petunjuk Post-test:</strong> Jawablah seluruh butir soal dengan teliti dan mandiri, kemudian klik tombol <strong>{{ $initialPostScore !== null ? 'Kirim Jawaban Latihan Ulang' : 'Kirim Jawaban Post-test' }}</strong>.
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

                    <div class="pt-4 flex items-center justify-between gap-4">
                        @if($initialPostScore !== null)
                            <button type="button"
                                    @click="showRetakeForm = false"
                                    class="px-6 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition cursor-pointer">
                                ← Kembali ke Ringkasan
                            </button>
                        @else
                            <div></div>
                        @endif

                        <button type="button"
                                @click="openSubmitModal({
                                    title: '{{ $initialPostScore !== null ? 'Kirim Jawaban Latihan Ulang?' : 'Kirim Jawaban Post-test?' }}',
                                    description: 'Pastikan Anda telah memeriksa kembali jawaban Anda sebelum mengumpulkan.',
                                    accentColor: 'rose',
                                    warningText: '{{ $initialPostScore !== null ? 'Nilai awal resmi Anda tetap terkunci (' . $initialPostScore . '/100). Skor hasil kali ini akan dicatat sebagai perbandingan latihan.' : 'Post-test ini menentukan nilai akhir modul Anda.' }}',
                                    confirmLabel: '{{ $initialPostScore !== null ? 'Kirim Jawaban Latihan' : 'Kirim Jawaban Post-test' }}'
                                }, $el.closest('form'))"
                                class="px-8 py-3.5 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-md shadow-rose-600/30 transition cursor-pointer">
                            {{ $initialPostScore !== null ? 'Kirim Jawaban Latihan Ulang →' : 'Kirim Jawaban Post-test →' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
