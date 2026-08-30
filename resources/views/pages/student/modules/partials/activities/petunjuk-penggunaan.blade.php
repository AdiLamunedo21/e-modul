{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- 2. PETUNJUK PENGGUNAAN ════════════════════════════════════════ --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="activePage === 'petunjuk_penggunaan'" x-cloak class="w-full space-y-6 text-left">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Petunjuk Siswa --}}
        <div class="rounded-3xl bg-white border border-teal-200/80 p-6 sm:p-7 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-teal-100">
                <span class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">🎓</span>
                <div>
                    <h3 class="text-base font-bold text-teal-950">Petunjuk untuk Siswa</h3>
                    <p class="text-xs text-teal-700">Langkah-langkah belajar mandiri</p>
                </div>
            </div>
            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700">
                @if(!empty($informasiUmum['petunjuk_penggunaan']['petunjuk_siswa']))
                    @foreach((array) $informasiUmum['petunjuk_penggunaan']['petunjuk_siswa'] as $item)
                        <li class="flex items-start gap-2.5">
                            <span class="text-teal-600 font-bold mt-0.5">✓</span>
                            <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                        </li>
                    @endforeach
                @elseif(!empty($informasiUmum['petunjuk_penggunaan']) && is_string($informasiUmum['petunjuk_penggunaan']))
                    <div class="whitespace-pre-line leading-relaxed">{!! nl2br(e($informasiUmum['petunjuk_penggunaan'])) !!}</div>
                @else
                    <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">1.</span><span>Baca dan pahami tujuan pembelajaran sebelum masuk ke materi inti.</span></li>
                    <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">2.</span><span>Kerjakan soal latihan diagnostik (Pre-test) untuk mengukur pengetahuan awal.</span></li>
                    <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">3.</span><span>Pelajari uraian materi dan tonton multimedia video pembelajaran.</span></li>
                    <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">4.</span><span>Lakukan praktik pada simulator embed dan kumpulkan tugas LKPD serta Job Sheet.</span></li>
                    <li class="flex items-start gap-2.5"><span class="text-teal-600 font-bold mt-0.5">5.</span><span>Selesaikan evaluasi Post-test di bagian akhir modul.</span></li>
                @endif
            </ul>
        </div>

        {{-- Petunjuk Guru --}}
        <div class="rounded-3xl bg-white border border-indigo-200/80 p-6 sm:p-7 shadow-sm space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-indigo-100">
                <span class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">👨‍🏫</span>
                <div>
                    <h3 class="text-base font-bold text-indigo-950">Peran & Bimbingan Guru</h3>
                    <p class="text-xs text-indigo-700">Fasilitasi pembelajaran peserta didik</p>
                </div>
            </div>
            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700">
                @if(!empty($informasiUmum['petunjuk_penggunaan']['petunjuk_guru']))
                    @foreach((array) $informasiUmum['petunjuk_penggunaan']['petunjuk_guru'] as $item)
                        <li class="flex items-start gap-2.5">
                            <span class="text-indigo-600 font-bold mt-0.5">✓</span>
                            <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                        </li>
                    @endforeach
                @else
                    <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">1.</span><span>Membimbing siswa yang mengalami kendala saat menyelesaikan aktivitas modul.</span></li>
                    <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">2.</span><span>Memantau antrean pengumpulan tugas dan memberikan penilaian serta umpan balik di Grading Center.</span></li>
                    <li class="flex items-start gap-2.5"><span class="text-indigo-600 font-bold mt-0.5">3.</span><span>Mengarahkan siswa pada sesi refleksi dan penguatan kompetensi kejuruan.</span></li>
                @endif
            </ul>
        </div>
    </div>

    {{-- Tombol Tandai Selesai Dibaca & Lanjut --}}
    <div class="rounded-3xl bg-white border border-slate-200/90 p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-slate-500">
            💡 Tandai sudah membaca petunjuk untuk membuka tahap Pendahuluan & Capaian Pembelajaran.
        </p>
        <button type="button"
                @click="markAsReadAndGoNext('petunjuk_penggunaan', nextPage ? nextPage.id : null)"
                class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-black text-xs sm:text-sm shadow-md shadow-teal-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
            <span>Tandai Selesai Dibaca & Lanjut</span>
            <span>→</span>
        </button>
    </div>
</div>
