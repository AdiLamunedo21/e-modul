{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 6. PRE-TEST (SOAL LATIHAN DIAGNOSTIK) ═════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
@if($module->has_pre_test && $module->preTest)
@php
    $preTestAttempts = $studentResult?->getTestAttempts('pre_test') ?? [];
    $preTestAttemptCount = $studentResult?->getTestAttemptCount('pre_test') ?? 0;
    $latestPreRetakeScore = $studentResult?->getLatestRetakeScore('pre_test');
    $initialPreScore = $studentResult?->pre_test_score;
    $hasRetake = $latestPreRetakeScore !== null;
@endphp

<div x-show="activePage === 'pre_test'" x-cloak class="w-full space-y-6 text-left"
     x-data="{ showRetakeForm: false, showHistory: false }">
    <div class="rounded-3xl bg-white border border-teal-200/90 shadow-sm overflow-hidden" id="section-pre-test">
        <div class="p-6 sm:p-7 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <span class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center text-2xl font-bold shrink-0">⚡</span>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-600">Bagian 2 • Latihan Diagnostik</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">Pre-test Diagnostik</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight mt-0.5">{{ $module->preTest->title ?? 'Pre-test Pembuka' }}</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Durasi: {{ $module->preTest->duration_minutes ?? 15 }} Menit • Target KKTP: {{ $module->preTest->kktp ?? 75 }}</p>
                </div>
            </div>

            @if($initialPreScore !== null)
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <div class="bg-teal-50/90 px-3.5 py-1.5 rounded-2xl border border-teal-200 text-center">
                        <span class="text-[9px] font-bold text-teal-700 uppercase tracking-wider block">Nilai Awal (Resmi)</span>
                        <span class="text-xl font-black text-teal-900">{{ $initialPreScore }}/100</span>
                    </div>
                    @if($hasRetake)
                        <div class="bg-indigo-50/90 px-3.5 py-1.5 rounded-2xl border border-indigo-200 text-center">
                            <span class="text-[9px] font-bold text-indigo-700 uppercase tracking-wider block">Latihan Terakhir</span>
                            <span class="text-xl font-black text-indigo-900">{{ $latestPreRetakeScore }}/100</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-6 sm:p-8">
            @if($initialPreScore !== null)
                {{-- ═══ 1. CARD HASIL & PERBANDINGAN NILAI (TAMPIL JIKA TIDAK SEDANG RETAKE) ═══ --}}
                <div x-show="!showRetakeForm" class="space-y-6">
                    <div class="rounded-3xl bg-gradient-to-br from-teal-50/60 via-emerald-50/40 to-slate-50 border border-teal-200/90 p-6 sm:p-8 space-y-6">
                        
                        {{-- Header Status --}}
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl shrink-0 shadow-md shadow-emerald-500/20">
                                ✓
                            </div>
                            <div class="space-y-1 flex-1">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <h3 class="text-xl font-black text-slate-900">Pre-test Diagnostik Selesai!</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        {{ $preTestAttemptCount > 1 ? $preTestAttemptCount . 'x Dikerjakan' : 'Percobaan Pertama' }}
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
                                    Soal Pre-test ini dapat Anda kerjakan ulang secara bebas untuk melatih dan memperdalam pemahaman materi. 
                                    <strong>Nilai awal resmi Anda tetap dikunci dan tidak akan berubah</strong>.
                                </p>
                            </div>
                        </div>

                        {{-- Panel Perbandingan Nilai Awal vs Latihan Pengulangan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            {{-- Box 1: Nilai Awal Resmi --}}
                            <div class="p-5 rounded-2xl bg-white border border-teal-200/80 shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Awal (Resmi)</span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-teal-100 text-teal-800 border border-teal-200 uppercase">
                                        Permanen / Terkunci
                                    </span>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-teal-700">{{ $initialPreScore }}</span>
                                    <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-normal pt-1 border-t border-slate-100">
                                    📌 Diperoleh pada percobaan pertama. Nilai ini menjadi rekam jejak awal kemampuan diagnostik Anda.
                                </p>
                            </div>

                            {{-- Box 2: Nilai Latihan Pengulangan Terbaru --}}
                            <div class="p-5 rounded-2xl bg-white border {{ $hasRetake ? 'border-indigo-200/90' : 'border-slate-200/80 border-dashed' }} shadow-2xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Latihan Terakhir</span>
                                    @if($hasRetake)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase">
                                            Percobaan ke-{{ $preTestAttemptCount }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            Belum Diulang
                                        </span>
                                    @endif
                                </div>

                                @if($hasRetake)
                                    @php $deltaPre = $latestPreRetakeScore - $initialPreScore; @endphp
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black text-indigo-700">{{ $latestPreRetakeScore }}</span>
                                        <span class="text-xs font-bold text-slate-400">/ 100 Poin</span>
                                    </div>
                                    <div class="pt-1 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold">
                                        @if($deltaPre > 0)
                                            <span class="text-emerald-600 flex items-center gap-1">
                                                <span>📈</span>
                                                <span>Perkembangan: +{{ $deltaPre }} Poin dari nilai awal</span>
                                            </span>
                                        @elseif($deltaPre === 0)
                                            <span class="text-blue-600 flex items-center gap-1">
                                                <span>🎯</span>
                                                <span>Konsisten: Sama dengan nilai awal ({{ $initialPreScore }})</span>
                                            </span>
                                        @else
                                            <span class="text-amber-600 flex items-center gap-1">
                                                <span>📉</span>
                                                <span>Selisih: {{ $deltaPre }} Poin dari nilai awal</span>
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="py-2 text-xs text-slate-400 italic">
                                        Anda belum pernah mengulang soal ini. Klik tombol <strong>"Latihan Ulang Soal"</strong> di bawah untuk menguji kembali kemampuan Anda.
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Riwayat Percobaan Lengkap (Collapsible jika > 1 kali) --}}
                        @if(count($preTestAttempts) > 1)
                            <div class="pt-2">
                                <button type="button"
                                        @click="showHistory = !showHistory"
                                        class="text-xs font-bold text-teal-700 hover:text-teal-900 inline-flex items-center gap-1.5 cursor-pointer">
                                    <span x-text="showHistory ? '▼ Sembunyikan Riwayat Percobaan' : '▶ Tampilkan Riwayat Percobaan (' + {{ count($preTestAttempts) }} + 'x pengerjaan)'"></span>
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
                                            @foreach($preTestAttempts as $att)
                                                <tr class="{{ !empty($att['is_initial']) ? 'bg-teal-50/40 font-semibold' : '' }}">
                                                    <td class="py-2 px-4">Percobaan ke-{{ $att['attempt'] ?? 1 }}</td>
                                                    <td class="py-2 px-4">
                                                        @if(!empty($att['is_initial']))
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800">Nilai Awal Resmi</span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Latihan Ulang</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-4 text-center font-bold {{ !empty($att['is_initial']) ? 'text-teal-700' : 'text-slate-800' }}">
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
                        <div class="pt-4 border-t border-teal-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <button type="button"
                                    @click="showRetakeForm = true"
                                    class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-white hover:bg-teal-50 text-teal-700 border border-teal-300 font-bold text-xs sm:text-sm shadow-2xs transition flex items-center justify-center gap-2 cursor-pointer">
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

            {{-- ═══ 2. FORM PENGERJAAN SOAL (TAMPIL JIKA BELUM PERNAH ATAU SEDANG RETAKE) ═══ --}}
            <div x-show="showRetakeForm || {{ $initialPreScore === null ? 'true' : 'false' }}">
                @if($initialPreScore !== null)
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">💡</span>
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <strong class="font-bold block text-sm mb-0.5">Mode Latihan Ulang Soal Pre-Test</strong>
                                Anda sedang mengerjakan ulang soal sebagai latihan penguatan konsep. 
                                <strong>Nilai awal resmi Anda ({{ $initialPreScore }}/100) tetap terkunci</strong> dan tidak akan berubah. Skor baru akan dicatat sebagai perbandingan latihan.
                            </div>
                        </div>
                        <button type="button"
                                @click="showRetakeForm = false"
                                class="px-3 py-1.5 rounded-xl bg-white text-slate-700 hover:bg-slate-100 border border-amber-300 text-xs font-bold shrink-0 transition cursor-pointer">
                            ✕ Batal
                        </button>
                    </div>
                @endif

                <form action="{{ route('student.modules.pre-test.submit', $module) }}" method="POST" class="space-y-8">
                    @csrf
                    <p class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-200/60">
                        💡 <strong>Petunjuk:</strong> Pilihlah salah satu jawaban yang paling tepat (A, B, C, D, atau E) untuk setiap butir soal, kemudian klik tombol <strong>{{ $initialPreScore !== null ? 'Kirim Jawaban Latihan Ulang' : 'Kirim Jawaban Pre-test' }}</strong>.
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

                    <div class="pt-4 flex items-center justify-between gap-4">
                        @if($initialPreScore !== null)
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
                                    title: '{{ $initialPreScore !== null ? 'Kirim Jawaban Latihan Ulang?' : 'Kirim Jawaban Pre-test?' }}',
                                    description: 'Pastikan Anda telah menjawab seluruh soal dengan teliti sebelum mengirimkan.',
                                    accentColor: 'teal',
                                    warningText: '{{ $initialPreScore !== null ? 'Nilai awal resmi Anda tetap terkunci (' . $initialPreScore . '/100). Skor hasil kali ini akan dicatat sebagai perbandingan latihan.' : 'Nilai pertama kali ini akan disimpan sebagai nilai awal resmi diagnostik.' }}',
                                    confirmLabel: '{{ $initialPreScore !== null ? 'Kirim Jawaban Latihan' : 'Kirim Jawaban Pre-test' }}'
                                }, $el.closest('form'))"
                                class="px-8 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-md shadow-teal-600/30 transition cursor-pointer">
                            {{ $initialPreScore !== null ? 'Kirim Jawaban Latihan Ulang →' : 'Kirim Jawaban Pre-test →' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
