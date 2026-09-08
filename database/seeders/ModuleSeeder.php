<?php

namespace Database\Seeders;

use App\Models\EmbedSubmission;
use App\Models\JobSheet;
use App\Models\JobSheetSubmission;
use App\Models\Lkpd;
use App\Models\Major;
use App\Models\Module;
use App\Models\PostTest;
use App\Models\PostTestQuestion;
use App\Models\PreTest;
use App\Models\PreTestQuestion;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\Teacher;
use App\Models\VideoSummary;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = Teacher::where('identity_number', '198501152010011002')->first();
        $majorTe = Major::where('code', 'TE')->first();
        $classTe1 = SchoolClass::where('major_id', $majorTe?->id)->where('grade', 'X')->where('section', '1')->first();
        $subjectInformatika = Subject::where('code', 'INF')->first();

        if (!$teacher || !$classTe1 || !$subjectInformatika) {
            return;
        }

        $moduleTitle = 'Pengertian Notasi & Algoritma';

        // 1. Cari modul utama (utamakan yang memiliki relasi studentResults terbanyak atau ID terkecil)
        $module = Module::where('title', $moduleTitle)
            ->withCount('studentResults')
            ->orderByDesc('student_results_count')
            ->orderBy('id')
            ->first();

        // 2. Jika modul belum pernah ada, buat baru lengkap
        if (!$module) {
            $informasiUmum = [
                'kata_pengantar'      => "Puji syukur kami panjatkan kepada Tuhan Yang Maha Esa atas tersusunnya E-Modul Pembelajaran {$moduleTitle}. Modul ini disusun secara terstruktur dengan pendekatan saintifik dan interaktif untuk membimbing peserta didik memahami logika pemecahan masalah serta penyusunan algoritma komputasi secara runut dan efisien.",
                'tujuan_pembelajaran' => "Peserta didik mampu memahami konsep fundamental algoritma, menganalisis struktur logika masalah komputasi, menyajikan solusi menggunakan notasi deskriptif, diagram alir (flowchart), dan pseudocode, serta menerapkannya dalam pemecahan masalah nyata secara mandiri dan kritis.",
                'peta_konsep'         => "Peta konsep modul mencakup: 1. Konsep Dasar Algoritma → 2. Struktur Dasar Logika (Sekuensial, Percabangan, Perulangan) → 3. Tiga Notasi Algoritma (Deskriptif, Flowchart, Pseudocode) → 4. Uji Coba Simulasi Praktik & Evaluasi Komputasi.",
                'petunjuk_penggunaan' => "1. Bacalah kata pengantar dan pahami tujuan pembelajaran yang harus dicapai.\n2. Kerjakan kuis diagnostik Pre-Test untuk mengukur pemahaman awal Anda.\n3. Pelajari secara saksama uraian teori dan slide materi pendukung.\n4. Tonton tayangan video YouTube interaktif dan susun resume intisari pembelajarannya.\n5. Bereksplorasi dengan simulator embed interaktif.\n6. Unduh Job Sheet praktikum dan selesaikan tugas LKPD yang diberikan.\n7. Uji pencapaian akhir Anda melalui evaluasi Post-Test.",
                'glosarium'           => [
                    ['istilah' => 'Algoritma', 'definisi' => 'Urutan langkah-langkah logis dan terstruktur yang disusun secara sistematis untuk menyelesaikan suatu masalah atau menghasilkan output tertentu.'],
                    ['istilah' => 'Flowchart', 'definisi' => 'Diagram grafis yang menggambarkan aliran proses atau algoritma menggunakan simbol-simbol standar seperti terminator, proses, dan percabangan.'],
                    ['istilah' => 'Pseudocode', 'definisi' => 'Notasi penulisan algoritma yang menyerupai bahasa pemrograman tingkat tinggi namun ditulis dengan bahasa yang mudah dipahami manusia tanpa aturan sintaks yang kaku.'],
                    ['istilah' => 'Notasi Deskriptif', 'definisi' => 'Penyajian algoritma menggunakan untaian kalimat bahasa alami sehari-hari secara terurut dari awal hingga akhir.'],
                    ['istilah' => 'Percabangan (Branching)', 'definisi' => 'Struktur kontrol algoritma di mana eksekusi langkah selanjutnya ditentukan oleh hasil evaluasi suatu kondisi logika (True/False).'],
                    ['istilah' => 'Perulangan (Looping)', 'definisi' => 'Struktur kontrol yang mengeksekusi satu atau serangkaian instruksi secara berulang-ulang selama kondisi tertentu masih terpenuhi.'],
                ],
                'daftar_isi'          => [
                    ['judul' => 'Bagian I — Orientasi & Fondasi Notasi Algoritma'],
                    ['judul' => 'Bagian II — Notasi Deskriptif & Diagram Alir (Flowchart)'],
                    ['judul' => 'Bagian III — Notasi Pseudocode & Studi Kasus Logika'],
                    ['judul' => 'Bagian IV — Praktik Simulator, Job Sheet, dan Evaluasi'],
                ],
                'daftar_pustaka'      => [
                    ['judul' => 'Buku Siswa Informatika Kelas X Kurikulum Merdeka', 'penulis' => 'Pusat Kurikulum dan Perbukuan Kemendikbudristek', 'tahun' => '2024', 'tautan' => 'https://kemdikbud.go.id'],
                    ['judul' => 'Algoritma dan Pemrograman dalam Bahasa Pascal dan C', 'penulis' => 'Rinaldi Munir', 'tahun' => '2021', 'tautan' => ''],
                    ['judul' => 'Introduction to Algorithms, Fourth Edition', 'penulis' => 'Thomas H. Cormen, Charles E. Leiserson', 'tahun' => '2022', 'tautan' => 'https://mitpress.mit.edu'],
                ],
                'toggles'             => [
                    'cover'               => true,
                    'kata_pengantar'      => true,
                    'daftar_isi'          => true,
                    'peta_konsep'         => true,
                    'glosarium'           => true,
                    'petunjuk_penggunaan' => true,
                    'tujuan_pembelajaran' => true,
                    'daftar_pustaka'      => true,
                ],
            ];

            $materiData = [
                'judul_materi'     => 'Kegiatan Belajar 1: Konsep Algoritma, Notasi Deskriptif, Flowchart, dan Pseudocode',
                'estimasi_waktu'   => 45,
                'uraian_materi'    => "## A. Hakikat dan Definisi Algoritma\nAlgoritma adalah serangkaian langkah logis yang terdefinisi secara jelas (*unambiguous*), berurutan, dan memiliki kondisi awal serta kondisi akhir yang pasti untuk menyelesaikan suatu permasalahan tertentu.\n\n### Karakteristik Algoritma yang Baik:\n1. **Finiteness (Keterbatasan)**: Algoritma harus berakhir setelah sejumlah langkah yang berhingga.\n2. **Definiteness (Kepastian)**: Setiap langkah harus didefinisikan secara tepat tanpa ambiguitas.\n3. **Input (Masukan)**: Memiliki nol atau lebih masukan yang diberikan ke algoritma.\n4. **Output (Keluaran)**: Menghasilkan minimal satu keluaran yang merupakan solusi masalah.\n5. **Effectiveness (Efektivitas)**: Setiap instruksi harus sederhana dan dapat dikerjakan dalam waktu wajar.\n\n---\n\n## B. Tiga Jenis Notasi Algoritma\n\n### 1. Notasi Deskriptif\nMenuliskan algoritma menggunakan bahasa manusia (bahasa Indonesia/Inggris). Cocok untuk masalah sederhana, namun kurang efektif untuk algoritma kompleks karena berpotensi menimbulkan multitafsir.\n\n### 2. Diagram Alir (Flowchart)\nPenggambaran alur algoritma secara visual dengan simbol-simbol standar internasional (ANSI):\n- **Oval / Terminator**: Menandai awal (*Start*) atau akhir (*End*) algoritma.\n- **Persegi Panjang / Process**: Menandakan operasi pengolahan data atau kalkulasi aritmatika.\n- **Jajar Genjang / Input-Output**: Menandakan operasi pembacaan data masukan atau pencetakan hasil.\n- **Belah Ketupat / Decision**: Menandakan percabangan berdasarkan pengujian kondisi logika.\n- **Garis Alir / Flowline**: Menunjukkan arah urutan eksekusi instruksi.\n\n### 3. Pseudocode\nNotasi yang menjembatani bahasa alami dengan bahasa pemrograman komputer. Struktur umumnya menggunakan kata kunci seperti `IF...THEN...ELSE`, `WHILE...DO`, `FOR...TO`, dan `PRINT`.\n\n> [!NOTE]\n> Pilihlah notasi yang paling sesuai dengan tingkat kompleksitas masalah. Flowchart sangat baik untuk presentasi logika alur, sedangkan Pseudocode sangat ideal sebelum implementasi koding langsung.",
                'ringkasan_materi' => "Algoritma merupakan fondasi utama computational thinking. Penguasaan tiga notasi (Deskriptif, Flowchart, dan Pseudocode) memungkinkan seorang pengembang merancang solusi komputasi yang efisien, mudah diverifikasi, dan siap diimplementasikan dalam bahasa pemrograman apa pun.",
                'poin_penting'     => [
                    'Algoritma harus memiliki kejelasan langkah dan pasti berhenti (Finiteness).',
                    'Simbol Decision (Belah Ketupat) pada Flowchart selalu menghasilkan dua arah cabang logika (Ya/Tidak).',
                    'Pseudocode mempermudah konversi logika ke dalam berbagai bahasa pemrograman seperti Python, C++, atau PHP.',
                ],
                'ppt_file_path'    => null,
                'ppt_file_name'    => null,
                'ppt_file_size'    => null,
            ];

            $preTestQ = [
                [
                    'id' => 1,
                    'pertanyaan' => 'Urutan langkah-langkah logis dan sistematis yang disusun secara runtut untuk menyelesaikan suatu permasalahan komputasi disebut...',
                    'pilihan' => ['A' => 'Algoritma', 'B' => 'Hardware', 'C' => 'Pseudocode', 'D' => 'Topologi', 'E' => 'Operating System'],
                    'kunci_jawaban' => 'A',
                    'bobot' => 50,
                    'pembahasan' => 'Algoritma adalah urutan langkah logis penyelesaian masalah yang sistematis dan terstruktur.',
                ],
                [
                    'id' => 2,
                    'pertanyaan' => 'Simbol diagram alir (Flowchart) yang berbentuk belah ketupat (diamond) berfungsi untuk...',
                    'pilihan' => ['A' => 'Pengambilan keputusan / percabangan kondisi', 'B' => 'Memulai atau mengakhiri program', 'C' => 'Melakukan operasi aritmatika proses', 'D' => 'Memasukkan input data manual', 'E' => 'Menghubungkan alur antar-halaman'],
                    'kunci_jawaban' => 'A',
                    'bobot' => 50,
                    'pembahasan' => 'Simbol belah ketupat (Decision) digunakan untuk percabangan pemilihan kondisi logika (True/False).',
                ],
            ];

            $postTestQ = [
                [
                    'id' => 1,
                    'pertanyaan' => 'Notasi penulisan algoritma yang menyerupai sintaks bahasa pemrograman namun menggunakan bahasa yang mudah dipahami manusia tanpa aturan sintaksis kaku disebut...',
                    'pilihan' => ['A' => 'Pseudocode', 'B' => 'Flowchart', 'C' => 'Notasi Deskriptif', 'D' => 'Source Code Murni', 'E' => 'Machine Language'],
                    'kunci_jawaban' => 'A',
                    'bobot' => 50,
                    'pembahasan' => 'Pseudocode adalah representasi algoritma informal tingkat tinggi yang menyerupai bahasa pemrograman.',
                ],
                [
                    'id' => 2,
                    'pertanyaan' => 'Manakah di bawah ini yang merupakan ciri utama dari sebuah algoritma yang baik (Finiteness)?',
                    'pilihan' => ['A' => 'Algoritma harus berakhir setelah melakukan sejumlah langkah terhingga', 'B' => 'Algoritma harus berjalan terus-menerus tanpa henti', 'C' => 'Algoritma harus ditulis dengan bahasa biner', 'D' => 'Algoritma tidak boleh memiliki masukan sama sekali', 'E' => 'Algoritma hanya boleh menggunakan simbol gambar'],
                    'kunci_jawaban' => 'A',
                    'bobot' => 50,
                    'pembahasan' => 'Karakteristik Finiteness mensyaratkan algoritma harus memiliki titik akhir yang pasti setelah langkah berhingga.',
                ],
            ];

            $preTestData = [
                'judul'        => "Pre-test: Pemahaman Awal {$moduleTitle}",
                'durasi_menit' => 15,
                'kktp'         => 75,
                'petunjuk'     => 'Kerjakan soal pre-test ini untuk mengukur pemahaman awal Anda mengenai notasi dan algoritma.',
                'acak_soal'    => false,
                'questions'    => $preTestQ,
            ];

            $postTestData = [
                'judul'        => "Post-test: Evaluasi Akhir {$moduleTitle}",
                'durasi_menit' => 20,
                'kktp'         => 75,
                'petunjuk'     => 'Kerjakan soal evaluasi post-test berikut secara cermat untuk mengukur ketuntasan belajar Anda.',
                'acak_soal'    => false,
                'questions'    => $postTestQ,
            ];

            $module = Module::create([
                'teacher_id'          => $teacher->id,
                'class_id'            => $classTe1->id,
                'subject_id'          => $subjectInformatika->id,
                'title'               => $moduleTitle,
                'informasi_umum_data' => $informasiUmum,
                'materi_data'         => $materiData,
                'pre_test_data'       => $preTestData,
                'post_test_data'      => $postTestData,
                'has_pre_test'        => true,
                'has_materi'          => true,
                'has_video'           => true,
                'has_embed'           => true,
                'has_job_sheet'       => true,
                'has_lkpd'            => true,
                'has_post_test'       => true,
                'status'              => 'published',
                'is_shared'           => true,
                'shared_at'           => now(),
            ]);

            $preTestModel = PreTest::create([
                'module_id'           => $module->id,
                'title'               => "Pre-test: Pemahaman Awal {$moduleTitle}",
                'kktp'                => 75,
                'instructions'        => 'Kerjakan soal pre-test ini untuk mengukur pemahaman awal Anda.',
                'randomize_questions' => false,
            ]);

            foreach ($preTestQ as $idx => $q) {
                PreTestQuestion::create([
                    'pre_test_id'        => $preTestModel->id,
                    'question_text'      => $q['pertanyaan'],
                    'options'            => $q['pilihan'],
                    'correct_answer'     => $q['kunci_jawaban'],
                    'score_weight'       => $q['bobot'],
                    'time_limit_seconds' => ($idx % 2 === 0 ? 20 : 15),
                    'explanation'        => $q['pembahasan'],
                    'order_num'          => $idx + 1,
                ]);
            }

            $postTestModel = PostTest::create([
                'module_id'           => $module->id,
                'title'               => "Post-test: Evaluasi Akhir {$moduleTitle}",
                'kktp'                => 75,
                'instructions'        => 'Kerjakan soal evaluasi post-test berikut.',
                'randomize_questions' => false,
            ]);

            foreach ($postTestQ as $idx => $q) {
                PostTestQuestion::create([
                    'post_test_id'       => $postTestModel->id,
                    'question_text'      => $q['pertanyaan'],
                    'options'            => $q['pilihan'],
                    'correct_answer'     => $q['kunci_jawaban'],
                    'score_weight'       => $q['bobot'],
                    'time_limit_seconds' => ($idx % 2 === 0 ? 25 : 20),
                    'explanation'        => $q['pembahasan'],
                    'order_num'          => $idx + 1,
                ]);
            }

            JobSheet::create([
                'module_id'     => $module->id,
                'pdf_file_path' => 'job_sheets/sample_jobsheet.pdf',
            ]);

            Lkpd::create([
                'module_id'     => $module->id,
                'pdf_file_path' => 'lkpds/sample_lkpd.pdf',
            ]);
        } else {
            // Modul sudah ada (misal Module ID 1): JANGAN HAPUS, cukup pastikan relasi guru dan kelas terhubung tepat
            $module->update([
                'teacher_id' => $teacher->id,
                'class_id'   => $classTe1->id,
                'subject_id' => $subjectInformatika->id,
            ]);
        }
    }
}
