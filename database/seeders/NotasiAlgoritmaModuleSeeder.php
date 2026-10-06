<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\PreTest;
use App\Models\PreTestQuestion;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NotasiAlgoritmaModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ── 1. PASTIKAN AKUN GURU PENGAMPU SIAP ─────────────────────────────────────
        $teacher = Teacher::where('identity_number', 'Nim25105260007')
            ->orWhere('email', 'adikun879@gmail.com')
            ->orWhere('name', 'like', '%Adi Chandra%')
            ->first();

        if (!$teacher) {
            $teacher = Teacher::create([
                'name'            => 'Adi Chandra W PPG',
                'identity_number' => 'Nim25105260007',
                'email'           => 'adikun879@gmail.com',
                'password'        => Hash::make('rima4693'),
            ]);
        } else {
            // Pastikan password akun guru diketahui dengan pasti (rima4693)
            $teacher->update([
                'password' => Hash::make('rima4693'),
                'email'    => 'adikun879@gmail.com',
            ]);
        }

        // ── 2. MATA PELAJARAN DAN TARGET KELAS ──────────────────────────────────────
        $subject = Subject::where('code', 'INF')->first() ?? Subject::first();
        if ($subject) {
            $teacher->subjects()->syncWithoutDetaching([$subject->id]);
        }

        // Target kelas pengampu: Utamakan kelas X TO 2 (grade X, TO, section 2) atau X TL 3
        $schoolClass = SchoolClass::where('grade', 'X')->where('major_name', 'TO')->where('section', '2')->first()
            ?? SchoolClass::where('grade', 'X')->where('major_name', 'TL')->where('section', '3')->first()
            ?? SchoolClass::first();

        if ($schoolClass) {
            $teacher->classes()->syncWithoutDetaching([$schoolClass->id]);
            $schoolClass->syncAllStudentsSubjects();
        }

        $classId = $schoolClass ? $schoolClass->id : 1;
        $subjectId = $subject ? $subject->id : 1;

        // ── 3. DATA KONTEN KAYA MODUL NOTASI ALGORITMA ─────────────────────────────
        $informasiUmumData = [
            'kata_pengantar' => "Puji syukur kami panjatkan ke hadirat Tuhan Yang Maha Esa atas tersusunnya E-Modul Pembelajaran Informatika dengan topik utama 'Notasi Algoritma: Deskriptif, Flowchart, dan Pseudocode'.\n\nModul digital interaktif ini disusun khusus untuk membimbing peserta didik kelas X Sekolah Menengah Kejuruan (SMK) dalam membangun fondasi berpikir komputasional (*computational thinking*) dan logika perancangan program. Sebelum seorang calon programmer menulis baris-baris kode pada bahasa pemrograman tertentu, penguasaan notasi algoritma yang baku, terstruktur, dan bebas dari ambiguitas merupakan keterampilan mutlak yang harus dikuasai.\n\nSemoga modul ini dapat menjadi panduan belajar mandiri yang menyenangkan, interaktif, serta menumbuhkan daya nalar analitis peserta didik dalam memecahkan masalah komputasi secara terstruktur.",
            'petunjuk_penggunaan' => "Agar proses pembelajaran menggunakan E-Modul ini berjalan optimal, ikutilah panduan belajar berikut:\n1. Pahami Peta Konsep dan Tujuan Pembelajaran untuk mengetahui arah capaian kompetensi.\n2. Kerjakan Pre-test (15 Butir Soal Diagnostik) secara mandiri untuk mengukur pemahaman awal Anda sebelum mempelajari materi.\n3. Pelajari Uraian Materi Kegiatan Belajar secara berurutan, mulai dari konsep dasar algoritma, kaidah bahasa deskriptif, simbol-simbol flowchart, hingga struktur penulisan pseudocode.\n4. Amati dan analisislah studi kasus konversi notasi yang disajikan pada setiap bab.\n5. Tanyakan kepada guru pengampu apabila menemukan konsep atau logika alur yang belum Anda pahami.",
            'tujuan_pembelajaran' => [
                'capaian_pembelajaran' => "Pada akhir fase E, peserta didik mampu menerapkan strategi algoritmik standar untuk menghasilkan beberapa solusi persoalan dengan data diskrit bervolume tidak kecil pada kehidupan sehari-hari maupun implementasinya dalam sistem komputer.",
                'tujuan_pembelajaran' => [
                    'Peserta didik mampu mendefinisikan konsep dasar algoritma dan menganalisis 5 karakteristik algoritma yang baik menurut kaidah Donald Knuth.',
                    'Peserta didik mampu membedakan karakteristik, kelebihan, dan kelemahan tiga jenis notasi algoritma: Kalimat Deskriptif, Flowchart (Bagan Alir), dan Pseudocode.',
                    'Peserta didik mampu mengidentifikasi dan menerapkan simbol-simbol standar internasional flowchart (Terminator, Input/Output, Process, Decision, dan Connector) secara tepat.',
                    'Peserta didik mampu menyusun algoritma terstruktur menggunakan tiga struktur kontrol dasar: Runtunan (Sequence), Percabangan (Selection), dan Perulangan (Iteration).',
                    'Peserta didik mampu mengonversi masalah kontekstual ke dalam notasi pseudocode dan diagram flowchart yang logis dan efisien.'
                ]
            ],
            'peta_konsep_text' => "Masalah Nyata (Problem Context) ➔ Analisis Kebutuhan & Logika ➔ 3 Bentuk Notasi Algoritma [1. Kalimat Deskriptif | 2. Bagan Alir / Flowchart | 3. Pseudocode] ➔ 3 Struktur Kontrol Dasar [Runtunan (Sequence), Percabangan (Selection), Perulangan (Iteration)] ➔ Implementasi Bahasa Pemrograman (Coding Terstruktur)",
            'glosarium' => [
                ['istilah' => 'Algoritma', 'definisi' => 'Urutan langkah-langkah logis dan sistematis yang terdefinisi dengan jelas untuk menyelesaikan suatu masalah tertentu.'],
                ['istilah' => 'Notasi Algoritma', 'definisi' => 'Tata cara atau format standar yang digunakan untuk menuliskan dan mendokumentasikan langkah-langkah algoritma agar dapat dipahami oleh manusia.'],
                ['istilah' => 'Flowchart', 'definisi' => 'Bagan alir yang menggambarkan urutan proses dan hubungan antar-instruksi menggunakan simbol-simbol grafis geometris standar.'],
                ['istilah' => 'Pseudocode', 'definisi' => 'Kode semu atau tiruan yang menyerupai bahasa pemrograman tingkat tinggi tetapi tidak terikat pada aturan sintaksis mesin/compiler.'],
                ['istilah' => 'Terminator', 'definisi' => 'Simbol bangun oval/kapsul pada flowchart yang digunakan untuk menandai titik awal (Start) atau akhir (End) dari suatu alur program.'],
                ['istilah' => 'Decision', 'definisi' => 'Simbol belah ketupat pada flowchart yang berfungsi untuk menguji suatu kondisi logika guna memilih jalur percabangan yang tepat.'],
                ['istilah' => 'Sequence', 'definisi' => 'Struktur runtunan di mana setiap baris instruksi dijalankan berurutan satu per satu dari atas ke bawah tanpa adanya lompatan alur.'],
                ['istilah' => 'Selection', 'definisi' => 'Struktur percabangan atau pengambilan keputusan berdasarkan pemenuhan kondisi boolean (IF-THEN-ELSE).'],
                ['istilah' => 'Iteration / Looping', 'definisi' => 'Struktur perulangan instruksi secara berulang selama kondisi tertentu masih terpenuhi (misal: FOR, WHILE, DO-WHILE).'],
                ['istilah' => 'Variable', 'definisi' => 'Wadah penyimpanan dalam memori yang digunakan untuk menampung nilai data yang dapat berubah selama eksekusi program berlangsung.'],
            ]
        ];

        $uraianMateriHtml = <<<'HTML'
<div class="space-y-8 text-slate-800 leading-relaxed">
    <!-- Header Pengantar -->
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-2xl border border-indigo-100 shadow-sm">
        <h2 class="text-2xl font-bold text-indigo-900 mb-3 flex items-center gap-2">
            <span>💡</span> Memahami Logika dan Notasi Algoritma
        </h2>
        <p class="text-slate-700 leading-relaxed text-base">
            Sebelum sebuah program komputer ditulis ke dalam bahasa pemrograman seperti Python, C++, atau Java, seorang pemrogram harus merancang <strong>alur logika berpikir</strong> terlebih dahulu. Rencana alur pemecahan masalah yang sistematis inilah yang dinamakan <strong>Algoritma</strong>. Agar rancangan algoritma tersebut dapat dibaca, dikomunikasikan, dan ditelaah oleh sesama manusia, kita membutuhkan <strong>Notasi Algoritma</strong>.
        </p>
    </div>

    <!-- Bab 1 -->
    <div>
        <h3 class="text-xl font-bold text-slate-900 border-b-2 border-indigo-500 pb-2 mb-4 flex items-center gap-2">
            <span class="bg-indigo-600 text-white w-7 h-7 rounded-lg inline-flex items-center justify-center text-sm font-semibold">1</span>
            Pengertian & Karakteristik Algoritma
        </h3>
        <p class="mb-4">
            Istilah <em>algoritma</em> berasal dari nama ilmuwan matematika dan astronomi muslim ternama, <strong>Abu Ja'far Muhammad bin Musa Al-Khwarizmi</strong> (780–850 M). Dalam dunia ilmu komputer modern, algoritma didefinisikan sebagai <em>urutan instruksi logis dan terstruktur yang disusun secara sistematis untuk memecahkan suatu masalah atau mencapai tujuan komputasi tertentu</em>.
        </p>
        <p class="mb-4 font-semibold text-slate-800">
            Menurut <strong>Donald E. Knuth</strong>, seorang algoritma yang baik dan efektif wajib memenuhi 5 (lima) karakteristik mutlak:
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 transition-all">
                <span class="font-bold text-indigo-700 text-base">1. Finiteness (Keterhinggaan)</span>
                <p class="text-sm text-slate-600 mt-1">Algoritma harus selalu berhenti setelah memproses sejumlah langkah yang berhingga. Algoritma tidak boleh berjalan tanpa henti (infinite loop).</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 transition-all">
                <span class="font-bold text-indigo-700 text-base">2. Definiteness (Kepastian)</span>
                <p class="text-sm text-slate-600 mt-1">Setiap baris instruksi harus didefinisikan secara tepat, tidak ambigu, dan tidak menimbulkan makna ganda bagi yang membaca.</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 transition-all">
                <span class="font-bold text-indigo-700 text-base">3. Input (Masukan)</span>
                <p class="text-sm text-slate-600 mt-1">Algoritma memiliki nol atau lebih masukan yang diberikan dari luar sebelum atau saat proses berjalan.</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 transition-all">
                <span class="font-bold text-indigo-700 text-base">4. Output (Keluaran)</span>
                <p class="text-sm text-slate-600 mt-1">Algoritma harus menghasilkan minimal satu keluaran yang menjadi solusi akhir dari masalah yang diselesaikan.</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 transition-all md:col-span-2">
                <span class="font-bold text-indigo-700 text-base">5. Effectiveness (Efektivitas)</span>
                <p class="text-sm text-slate-600 mt-1">Setiap instruksi harus sesederhana mungkin dan masuk akal untuk dikerjakan dalam rentang waktu yang wajar.</p>
            </div>
        </div>
    </div>

    <!-- Bab 2 -->
    <div>
        <h3 class="text-xl font-bold text-slate-900 border-b-2 border-indigo-500 pb-2 mb-4 flex items-center gap-2">
            <span class="bg-indigo-600 text-white w-7 h-7 rounded-lg inline-flex items-center justify-center text-sm font-semibold">2</span>
            Tiga Bentuk Notasi Algoritma
        </h3>
        <p class="mb-4">
            Notasi algoritma tidak bergantung pada bahasa pemrograman tertentu (bersifat independen). Tiga bentuk notasi yang paling umum digunakan adalah:
        </p>

        <!-- 2.1 Kalimat Deskriptif -->
        <div class="mb-6 p-5 bg-slate-50 rounded-xl border border-slate-200">
            <h4 class="font-bold text-lg text-slate-900 mb-2 flex items-center gap-2">
                <span class="text-indigo-600">A.</span> Notasi Kalimat Deskriptif (Natural Language)
            </h4>
            <p class="text-sm text-slate-700 mb-3">
                Ditulis menggunakan bahasa alami sehari-hari (misalnya Bahasa Indonesia atau Bahasa Inggris). Setiap langkah diawali dengan kata kerja aksi seperti <em>Hitung, Masukkan, Tampilkan, Jika, dsb.</em>
            </p>
            <div class="bg-white p-4 rounded-lg border border-slate-200 text-sm font-mono text-slate-800">
                <strong>Contoh Algoritma Menentukan Kelulusan:</strong><br>
                1. Masukkan nilai ujian siswa.<br>
                2. Jika nilai ujian lebih besar atau sama dengan 75, maka cetak "LULUS".<br>
                3. Jika nilai ujian kurang dari 75, maka cetak "REMIDI".<br>
                4. Proses selesai.
            </div>
            <p class="text-xs text-amber-700 mt-2 font-medium">⚠️ <em>Kelemahan:</em> Rentan menimbulkan ambiguitas (tafsir ganda) dan cenderung bertele-tele jika masalah yang diselesaikan sangat rumit.</p>
        </div>

        <!-- 2.2 Bagan Alir / Flowchart -->
        <div class="mb-6 p-5 bg-slate-50 rounded-xl border border-slate-200">
            <h4 class="font-bold text-lg text-slate-900 mb-2 flex items-center gap-2">
                <span class="text-indigo-600">B.</span> Bagan Alir (Flowchart)
            </h4>
            <p class="text-sm text-slate-700 mb-3">
                Menggambarkan alur logika menggunakan simbol-simbol grafis geometris standar internasional (ANSI / ISO). Flowchart sangat efektif untuk memvisualisasikan alur percabangan dan perulangan secara intuitif.
            </p>

            <div class="overflow-x-auto my-3">
                <table class="w-full text-sm text-left border border-slate-200 rounded-lg overflow-hidden bg-white">
                    <thead class="bg-indigo-50 text-indigo-900 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-4 border-r">Nama Simbol</th>
                            <th class="py-2.5 px-4 border-r">Bentuk Geometri</th>
                            <th class="py-2.5 px-4">Fungsi dan Kegunaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-2.5 px-4 font-semibold text-slate-900 border-r">Terminator</td>
                            <td class="py-2.5 px-4 border-r text-indigo-600">Oval / Kapsul</td>
                            <td class="py-2.5 px-4">Menandai awal (Start) atau akhir (End) dari bagan alir.</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4 font-semibold text-slate-900 border-r">Input / Output</td>
                            <td class="py-2.5 px-4 border-r text-indigo-600">Jajaran Genjang</td>
                            <td class="py-2.5 px-4">Menerima input data atau menampilkan output hasil ke pengguna.</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4 font-semibold text-slate-900 border-r">Process (Proses)</td>
                            <td class="py-2.5 px-4 border-r text-indigo-600">Persegi Panjang</td>
                            <td class="py-2.5 px-4">Melakukan kalkulasi matematika, pengolahan, atau penugasan variabel.</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4 font-semibold text-slate-900 border-r">Decision (Keputusan)</td>
                            <td class="py-2.5 px-4 border-r text-indigo-600">Belah Ketupat (Diamond)</td>
                            <td class="py-2.5 px-4">Menguji kondisi boolean (Ya / Tidak) untuk menentukan arah cabang aliran.</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4 font-semibold text-slate-900 border-r">On-page Connector</td>
                            <td class="py-2.5 px-4 border-r text-indigo-600">Lingkaran Kecil</td>
                            <td class="py-2.5 px-4">Menyambungkan alur diagram pada halaman kertas yang sama.</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4 font-semibold text-slate-900 border-r">Flowline</td>
                            <td class="py-2.5 px-4 border-r text-indigo-600">Garis Panah</td>
                            <td class="py-2.5 px-4">Menunjukkan arah jalannya urutan instruksi dari satu simbol ke simbol lain.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2.3 Pseudocode -->
        <div class="mb-6 p-5 bg-slate-50 rounded-xl border border-slate-200">
            <h4 class="font-bold text-lg text-slate-900 mb-2 flex items-center gap-2">
                <span class="text-indigo-600">C.</span> Pseudocode (Kode Semu)
            </h4>
            <p class="text-sm text-slate-700 mb-3">
                Berasal dari kata <em>pseudo</em> (palsu/semu) dan <em>code</em> (kode program). Pseudocode adalah notasi yang menyerupai bahasa pemrograman tingkat tinggi (seperti Pascal atau Python), namun bebas dari aturan sintaksis mesin yang kaku.
            </p>
            <div class="bg-slate-900 text-emerald-400 p-4 rounded-xl font-mono text-sm overflow-x-auto shadow-inner">
                <span class="text-slate-400">// 1. BAGIAN JUDUL / HEADER</span><br>
                <span class="text-amber-300">PROGRAM</span> HitungLuasSegitiga<br>
                <span class="text-slate-400">{ Menghitung luas segitiga dengan rumus 0.5 * alas * tinggi }</span><br><br>

                <span class="text-slate-400">// 2. BAGIAN DEKLARASI / KAMUS</span><br>
                <span class="text-amber-300">DEKLARASI:</span><br>
                &nbsp;&nbsp;alas, tinggi, luas : <span class="text-cyan-300">real</span><br><br>

                <span class="text-slate-400">// 3. BAGIAN DESKRIPSI LOGIKA UTAMA</span><br>
                <span class="text-amber-300">ALGORITMA:</span><br>
                &nbsp;&nbsp;<span class="text-pink-400">read</span>(alas)<br>
                &nbsp;&nbsp;<span class="text-pink-400">read</span>(tinggi)<br>
                &nbsp;&nbsp;luas &leftarrow; 0.5 * alas * tinggi<br>
                &nbsp;&nbsp;<span class="text-pink-400">write</span>('Luas segitiga adalah: ', luas)
            </div>
        </div>
    </div>

    <!-- Bab 3 -->
    <div>
        <h3 class="text-xl font-bold text-slate-900 border-b-2 border-indigo-500 pb-2 mb-4 flex items-center gap-2">
            <span class="bg-indigo-600 text-white w-7 h-7 rounded-lg inline-flex items-center justify-center text-sm font-semibold">3</span>
            Tiga Struktur Dasar Algoritma
        </h3>
        <p class="mb-4">
            Semua persoalan pemrograman di dunia, serumit apa pun itu, pada dasarnya dapat dipecahkan menggunakan kombinasi dari 3 struktur kontrol logika dasar:
        </p>

        <div class="space-y-4">
            <div class="p-4 bg-white rounded-xl border border-slate-200">
                <h5 class="font-bold text-slate-900 text-base mb-1">1. Runtunan (Sequential Structure)</h5>
                <p class="text-sm text-slate-600">Instruksi dieksekusi secara berurutan langkah demi langkah dari atas ke bawah. Setiap instruksi dikerjakan tepat satu kali.</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200">
                <h5 class="font-bold text-slate-900 text-base mb-1">2. Percabangan / Pemilihan (Selection / Branching Structure)</h5>
                <p class="text-sm text-slate-600">Menentukan instruksi mana yang akan dijalankan berdasarkan hasil evaluasi suatu kondisi logika (bernilai Benar/True atau Salah/False). Contoh: pernyataan <code>IF-THEN-ELSE</code> atau <code>SWITCH-CASE</code>.</p>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200">
                <h5 class="font-bold text-slate-900 text-base mb-1">3. Perulangan (Iteration / Looping Structure)</h5>
                <p class="text-sm text-slate-600">Menjalankan satu atau sekelompok instruksi secara berulang-ulang selama syarat kondisi terpenuhi. Contoh: perulangan <code>FOR</code> (jumlah perulangan pasti) dan <code>WHILE</code> (berdasarkan syarat kondisi).</p>
            </div>
        </div>
    </div>
</div>
HTML;

        $materiData = [
            'judul_materi'     => 'Kegiatan Belajar: Notasi Algoritma Pemrograman',
            'uraian_materi'    => $uraianMateriHtml,
            'ringkasan_materi' => "Notasi algoritma adalah cara penulisan alur pemecahan masalah yang independen dari bahasa pemrograman. Tiga notasi utamanya adalah Kalimat Deskriptif, Bagan Alir (Flowchart), dan Pseudocode. Seluruh logika program dibangun dari 3 struktur kontrol dasar: Runtunan (Sequence), Percabangan (Selection), dan Perulangan (Iteration).",
            'poin_penting'     => [
                'Algoritma adalah urutan langkah logis dan sistematis untuk memecahkan suatu persoalan komputasi.',
                '5 kriteria algoritma menurut Donald Knuth: Finiteness, Definiteness, Input, Output, dan Effectiveness.',
                '3 bentuk notasi algoritma: Kalimat Deskriptif (bahasa alami), Flowchart (grafis simbolik), dan Pseudocode (kode semu terstruktur).',
                'Simbol standar flowchart: Terminator (oval), Input/Output (jajaran genjang), Proses (persegi panjang), Decision (belah ketupat), dan Konektor (lingkaran).',
                'Struktur dasar logika algoritma: Runtunan (Sequence), Percabangan (Selection), dan Perulangan (Iteration).'
            ],
            'ppt_file_path'    => null,
            'ppt_file_name'    => null,
            'ppt_file_size'    => null,
            'pdf_preview_path' => null,
        ];

        // ── 4. BUAT ATAU PERBARUI MODEL MODULE ─────────────────────────────────────
        $module = Module::updateOrCreate(
            [
                'teacher_id' => $teacher->id,
                'title'      => 'Notasi Algoritma: Deskriptif, Flowchart, dan Pseudocode',
            ],
            [
                'class_id'            => $classId,
                'subject_id'          => $subjectId,
                'semester'            => '1',
                'status'              => 'published',
                'is_active'           => true,
                'has_pre_test'        => true,
                'has_materi'          => true,
                'has_video'           => false,
                'has_embed'           => false,
                'has_job_sheet'       => false,
                'has_lkpd'            => false,
                'has_post_test'       => false,
                'informasi_umum_data' => $informasiUmumData,
                'materi_data'         => $materiData,
            ]
        );

        // ── 5. PRE-TEST SETUP (15 BUTIR SOAL PILIHAN GANDA) ────────────────────────
        $preTest = PreTest::updateOrCreate(
            ['module_id' => $module->id],
            [
                'title'               => 'Pre-test: Notasi Algoritma',
                'kktp'                => 75,
                'instructions'        => 'Kerjakan 15 butir soal pilihan ganda berikut secara mandiri dan teliti guna mengukur pemahaman awal Anda terhadap konsep notasi algoritma (Kalimat Deskriptif, Flowchart, dan Pseudocode).',
                'randomize_questions' => false,
            ]
        );

        // Hapus butir soal lama untuk memastikan tidak ada duplikasi data
        $preTest->questions()->delete();

        // ── 6. 15 BUTIR SOAL LENGKAP DENGAN PEMBAHASAN MENDALAM ───────────────────
        $questions = [
            [
                'order_num'     => 1,
                'question_text' => 'Urutan langkah-langkah logis dan sistematis yang terdefinisi dengan jelas untuk memecahkan suatu masalah atau mencapai tujuan tertentu dinamakan...',
                'options'       => [
                    'A' => 'Program',
                    'B' => 'Algoritma',
                    'C' => 'Pseudocode',
                    'D' => 'Flowchart',
                    'E' => 'Sintaksis',
                ],
                'correct_answer'     => 'B',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Algoritma adalah urutan langkah logis yang disusun secara sistematis untuk memecahkan masalah. Program adalah implementasi konkret dari algoritma menggunakan bahasa pemrograman komputer tertentu.',
            ],
            [
                'order_num'     => 2,
                'question_text' => 'Ilmuwan komputer ternama Donald E. Knuth menyatakan bahwa sebuah algoritma wajib memiliki karakteristik "Finiteness". Maksud dari sifat Finiteness tersebut adalah...',
                'options'       => [
                    'A' => 'Algoritma harus dapat menyelesaikan masalah dalam waktu kurang dari satu detik',
                    'B' => 'Algoritma harus memiliki instruksi yang efektif dan tidak membutuhkan banyak memori',
                    'C' => 'Algoritma harus selalu berakhir dan berhenti setelah mengerjakan sejumlah langkah terhingga',
                    'D' => 'Algoritma harus memiliki minimal dua buah nilai masukan (input)',
                    'E' => 'Setiap instruksi harus bersifat absolut dan tidak boleh diubah oleh pemrogram',
                ],
                'correct_answer'     => 'C',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Finiteness (keterhinggaan) mensyaratkan bahwa setiap algoritma harus memiliki titik pemberhentian setelah sejumlah berhingga langkah komputasi, sehingga tidak terjebak dalam perulangan tanpa akhir (infinite loop).',
            ],
            [
                'order_num'     => 3,
                'question_text' => 'Dalam dunia informatika, terdapat 3 (tiga) cara baku yang diakui secara luas untuk menuliskan notasi algoritma, yaitu...',
                'options'       => [
                    'A' => 'Kalimat Deskriptif, Flowchart, dan Pseudocode',
                    'B' => 'Bahasa Biner, Bahasa Assembler, dan Bahasa Mesin',
                    'C' => 'Source Code, Object Code, dan Executable',
                    'D' => 'Diagram Batang, Diagram Lingkaran, dan Tabel Frekuensi',
                    'E' => 'Struktur Data, Basis Data, dan Relasi Entitas',
                ],
                'correct_answer'     => 'A',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Tiga jenis notasi algoritma yang umum digunakan adalah: (1) Kalimat Deskriptif (Natural Language), (2) Bagan Alir (Flowchart), dan (3) Kode Semu (Pseudocode).',
            ],
            [
                'order_num'     => 4,
                'question_text' => 'Pada bagan alir (flowchart), simbol grafis geometris yang digunakan khusus untuk merepresentasikan kegiatan membaca data masukan (Input) atau menampilkan hasil keluaran (Output) adalah...',
                'options'       => [
                    'A' => 'Persegi panjang (Rectangle)',
                    'B' => 'Belah ketupat (Rhombus / Diamond)',
                    'C' => 'Jajaran genjang (Parallelogram)',
                    'D' => 'Oval / Kapsul (Terminator)',
                    'E' => 'Lingkaran kecil (Small Circle)',
                ],
                'correct_answer'     => 'C',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Simbol jajaran genjang (parallelogram) digunakan khusus untuk operasi Input/Output data, misalnya membaca nilai variabel dari keyboard atau mencetak hasil ke layar.',
            ],
            [
                'order_num'     => 5,
                'question_text' => 'Perhatikan simbol geometris flowchart: sebuah bangun datar "Belah Ketupat" (Diamond). Fungsi utama dari simbol tersebut dalam alur algoritma adalah...',
                'options'       => [
                    'A' => 'Menandai titik awal (Start) atau akhir (End) jalannya program',
                    'B' => 'Melakukan pemrosesan perhitungan rumus matematika',
                    'C' => 'Menguji kondisi logika untuk menentukan arah cabang aliran (Decision)',
                    'D' => 'Menyambungkan alur diagram yang berada pada lembar halaman berbeda',
                    'E' => 'Menetapkan nilai awal atau inisialisasi variabel perhitungan',
                ],
                'correct_answer'     => 'C',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Simbol belah ketupat (Decision) berfungsi sebagai pengambil keputusan berdasarkan evaluasi kondisi boolean (misalnya Ya/Tidak atau True/False) yang menghasilkan cabang alur yang berbeda.',
            ],
            [
                'order_num'     => 6,
                'question_text' => 'Simbol bangun oval atau persegi panjang dengan sudut membulat (kapsul) pada bagan alir dikenal dengan nama "Terminator". Simbol ini diletakkan pada bagian...',
                'options'       => [
                    'A' => 'Setiap kali terjadi operasi pembagian aritmatika',
                    'B' => 'Titik permulaan (Start) dan titik penutup (End) dari bagan alir',
                    'C' => 'Percabangan logika yang memiliki lebih dari dua pilihan',
                    'D' => 'Titik pertemuan antar-garis alir yang saling bersilangan',
                    'E' => 'Sebelum instruksi input data dijalankan',
                ],
                'correct_answer'     => 'B',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Simbol Terminator digunakan khusus sebagai penanda titik awal (Start/Mulai) dan titik akhir (End/Selesai) dari seluruh rangkaian proses dalam bagan alir.',
            ],
            [
                'order_num'     => 7,
                'question_text' => 'Jika seorang pemrogram ingin memodelkan instruksi perhitungan rumus matematika: "Keliling = 2 * (Panjang + Lebar)" ke dalam bagan alir (flowchart), simbol manakah yang wajib digunakan?',
                'options'       => [
                    'A' => 'Persegi panjang (Process)',
                    'B' => 'Jajaran genjang (Input/Output)',
                    'C' => 'Belah ketupat (Decision)',
                    'D' => 'Lingkaran (Connector)',
                    'E' => 'Segi enam (Preparation)',
                ],
                'correct_answer'     => 'A',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Operasi pengolahan data, kalkulasi aritmatika, maupun pemberian nilai (assignment) pada variabel selalu digambarkan menggunakan simbol Persegi Panjang (Process).',
            ],
            [
                'order_num'     => 8,
                'question_text' => 'Ketika menggambar diagram alir yang cukup rumit pada satu halaman yang sama, simbol yang digunakan untuk menyambungkan garis alir agar bagan tetap rapi dan tidak berpotongan adalah...',
                'options'       => [
                    'A' => 'Off-page Connector (berbentuk segi lima terbalik)',
                    'B' => 'On-page Connector (berbentuk lingkaran kecil)',
                    'C' => 'Display (berbentuk pensil terpotong)',
                    'D' => 'Manual Input (berbentuk trapesium)',
                    'E' => 'Predefined Process (berbentuk persegi panjang ganda)',
                ],
                'correct_answer'     => 'B',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'On-page Connector (lingkaran kecil) digunakan untuk menghubungkan alur diagram pada lembar kertas yang sama guna menghindari garis panah yang bertabrakan atau saling melintang.',
            ],
            [
                'order_num'     => 9,
                'question_text' => 'Struktur penulisan notasi algoritma Pseudocode yang baku dan sistematis pada umumnya terbagi ke dalam 3 (tiga) bagian utama, yaitu...',
                'options'       => [
                    'A' => 'Input, Proses, dan Output',
                    'B' => 'Header (Judul), Kamus (Deklarasi), dan Deskripsi (Algoritma)',
                    'C' => 'Class, Method, dan Object',
                    'D' => 'Initialization, Condition, dan Increment',
                    'E' => 'Start, Looping, dan Stop',
                ],
                'correct_answer'     => 'B',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Struktur baku teks pseudocode terdiri atas: (1) Judul/Header (nama algoritma & spesifikasi), (2) Kamus/Deklarasi (pendefinisian variabel, konstanta, tipe data), dan (3) Deskripsi/Algoritma (langkah-langkah aksi komputasi).',
            ],
            [
                'order_num'     => 10,
                'question_text' => 'Mengapa programmer profesional sering menyusun algoritma dalam bentuk Pseudocode sebelum memulai tahap penulisan kode sumber (coding)?',
                'options'       => [
                    'A' => 'Karena pseudocode dapat langsung dieksekusi oleh mesin komputer tanpa perlu compiler',
                    'B' => 'Karena pseudocode menjembatani logika manusia dengan bahasa pemrograman tanpa terikat aturan sintaksis mesin yang kaku',
                    'C' => 'Karena pseudocode otomatis menghasilkan aplikasi yang bebas dari segala jenis bug',
                    'D' => 'Karena pseudocode berukuran memori lebih kecil daripada bahasa biner',
                    'E' => 'Karena pseudocode hanya berisi gambar sehingga lebih mudah dipahami oleh sistem operasi',
                ],
                'correct_answer'     => 'B',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Pseudocode adalah jembatan komunikasi logika yang sangat efektif karena strukturnya mirip bahasa pemrograman namun fleksibel, mudah dipahami manusia, dan mudah dikonversi ke bahasa apa pun (Python, C++, Java, dsb).',
            ],
            [
                'order_num'     => 11,
                'question_text' => 'Salah satu dari tiga struktur dasar algoritma adalah "Runtunan" (Sequence). Ciri utama dari struktur kontrol Runtunan adalah...',
                'options'       => [
                    'A' => 'Setiap baris instruksi dijalankan berurutan satu per satu dari atas ke bawah tanpa adanya percabangan atau lompatan',
                    'B' => 'Instruksi akan diulang sebanyak 10 kali sampai kondisi bernilai False',
                    'C' => 'Instruksi akan dilewati secara acak berdasarkan nilai variabel input',
                    'D' => 'Hanya baris bernomor genap yang akan dieksekusi oleh komputer',
                    'E' => 'Instruksi selalu membutuhkan evaluasi kondisi Ya atau Tidak',
                ],
                'correct_answer'     => 'A',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Struktur Runtunan (Sequential Structure) mengeksekusi instruksi secara linier dan sekuensial satu demi satu sesuai urutan penulisan, tanpa memilih cabang atau mengulang proses.',
            ],
            [
                'order_num'     => 12,
                'question_text' => "Perhatikan penggalan pseudocode berikut:\n\nIF (usia >= 17) THEN\n    output('Boleh membuat KTP')\nELSE\n    output('Belum cukup umur')\nENDIF\n\nStruktur kontrol logika yang digunakan pada kode di atas adalah...",
                'options'       => [
                    'A' => 'Struktur Runtunan Murni (Sequence)',
                    'B' => 'Struktur Percabangan / Pemilihan (Selection / Branching)',
                    'C' => 'Struktur Perulangan (Iteration / Looping)',
                    'D' => 'Struktur Rekursif (Recursion)',
                    'E' => 'Struktur Paralel (Multi-threading)',
                ],
                'correct_answer'     => 'B',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Klausa IF-THEN-ELSE adalah wujud dari struktur kontrol Percabangan / Pemilihan (Selection), di mana program memilih salah satu dari dua jalur aksi berdasarkan hasil evaluasi kondisi ekspresi boolean.',
            ],
            [
                'order_num'     => 13,
                'question_text' => "Perhatikan instruksi logika algoritma penukaran nilai berikut:\n1. A = 10\n2. B = 5\n3. A = A + B\n4. B = A - B\n5. A = A - B\n\nBerapakah nilai akhir dari variabel A dan B setelah seluruh instruksi di atas selesai dijalankan?",
                'options'       => [
                    'A' => 'A = 10 dan B = 5',
                    'B' => 'A = 15 dan B = 10',
                    'C' => 'A = 5 dan B = 10',
                    'D' => 'A = 15 dan B = 5',
                    'E' => 'A = 0 dan B = 0',
                ],
                'correct_answer'     => 'C',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Penelusuran langkah (tracing): Baris 1 (A=10), Baris 2 (B=5), Baris 3 (A=10+5=15), Baris 4 (B=15-5=10), Baris 5 (A=15-10=5). Hasil akhirnya nilai variabel berhasil ditukar menjadi A = 5 dan B = 10.',
            ],
            [
                'order_num'     => 14,
                'question_text' => 'Kelemahan paling mendasar dari penulisan algoritma menggunakan Notasi Kalimat Deskriptif dibandingkan Flowchart atau Pseudocode adalah...',
                'options'       => [
                    'A' => 'Hanya bisa dibaca oleh komputer mainframe',
                    'B' => 'Sering kali menimbulkan makna ganda (ambiguitas) karena sifat bahasa manusia yang luas dan tidak presisi',
                    'C' => 'Memerlukan aplikasi editor khusus yang berbayar',
                    'D' => 'Terlalu singkat dan tidak memiliki kata kerja aktif',
                    'E' => 'Tidak mampu menyelesaikan operasi penjumlahan angka',
                ],
                'correct_answer'     => 'B',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Bahasa alami manusia memiliki kekayaan kata yang rentan menimbulkan ambiguitas (tafsir ganda) dan ketidaktepatan logika ketika dibaca oleh pemrogram yang berbeda.',
            ],
            [
                'order_num'     => 15,
                'question_text' => 'Sebuah sistem absensi digital sekolah perlu mencetak nomor antrean dari angka 1 sampai dengan angka 100 secara berurutan. Struktur kontrol algoritma manakah yang paling efisien digunakan?',
                'options'       => [
                    'A' => 'Struktur Runtunan dengan mengetik perintah cetak sebanyak 100 baris secara manual',
                    'B' => 'Struktur Percabangan IF-THEN bersarang sebanyak 100 tingkat',
                    'C' => 'Struktur Perulangan (Looping / Iteration) seperti perulangan FOR atau WHILE',
                    'D' => 'Struktur Pemilihan Bertingkat SWITCH-CASE',
                    'E' => 'Struktur Rekursif tanpa titik henti (base case)',
                ],
                'correct_answer'     => 'C',
                'score_weight'       => 10,
                'time_limit_seconds' => 60,
                'explanation'        => 'Tugas komputasi yang melakukan pekerjaan berulang-ulang dengan pola data teratur (seperti mencetak angka 1 hingga 100) diselesaikan secara paling efisien menggunakan struktur Perulangan (Looping/Iteration) seperti loop FOR atau WHILE.',
            ],
        ];

        foreach ($questions as $q) {
            PreTestQuestion::create([
                'pre_test_id'        => $preTest->id,
                'question_text'      => $q['question_text'],
                'options'            => $q['options'],
                'correct_answer'     => $q['correct_answer'],
                'score_weight'       => $q['score_weight'],
                'time_limit_seconds' => $q['time_limit_seconds'],
                'explanation'        => $q['explanation'],
                'order_num'          => $q['order_num'],
            ]);
        }

        $this->command->info("Modul '{$module->title}' beserta 15 soal Pre-test berhasil dibuat dan diaktifkan!");
    }
}
