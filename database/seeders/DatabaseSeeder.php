<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Module;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // 1 Admin (Supervisi & Manajemen Kurikulum)
        Admin::create([
            'name' => 'Drs. Ahmad Fauzi, M.Pd.',
            'identity_number' => 'NIP999001',
            'password' => $password,
        ]);

        // 4 Jurusan / Konsentrasi Keahlian
        $majorRpl = Major::create([
            'name'        => 'Pengembangan Perangkat Lunak dan GIM',
            'code'        => 'PPLG',
            'description' => 'Konsentrasi keahlian pemrograman aplikasi web, mobile, basis data, dan rekayasa perangkat lunak.',
        ]);

        $majorTkj = Major::create([
            'name'        => 'Teknik Jaringan Komputer dan Telekomunikasi',
            'code'        => 'TJKT',
            'description' => 'Konsentrasi keahlian infrastruktur jaringan komputer, sistem administrasi server, dan keamanan siber.',
        ]);

        $majorTitl = Major::create([
            'name'        => 'Teknik Ketenagalistrikan',
            'code'        => 'TITL',
            'description' => 'Konsentrasi keahlian instalasi penerangan, tenaga kelistrikan, dan kontrol motor industri.',
        ]);

        $majorDkv = Major::create([
            'name'        => 'Desain Komunikasi Visual',
            'code'        => 'DKV',
            'description' => 'Konsentrasi keahlian desain grafis, ilustrasi digital, animasi, dan multimedia interaktif.',
        ]);

        // 4 Mata Pelajaran
        $subjectInformatika = Subject::create([
            'name'        => 'Informatika',
            'code'        => 'INF',
            'icon'        => '💻',
            'color'       => 'blue',
            'description' => 'Mata pelajaran informatika, pemrograman, basis data, dan rekayasa perangkat lunak.',
        ]);

        $subjectElektro = Subject::create([
            'name'        => 'Teknik Elektro Dasar',
            'code'        => 'ELK',
            'icon'        => '⚡',
            'color'       => 'amber',
            'description' => 'Mata pelajaran dasar kelistrikan, logika digital, dan sistem elektronika industri.',
        ]);

        $subjectMatematika = Subject::create([
            'name'        => 'Matematika',
            'code'        => 'MAT',
            'icon'        => '📐',
            'color'       => 'indigo',
            'description' => 'Mata pelajaran matematika terapan dan logika kalkulasi teknik.',
        ]);

        $subjectIndonesia = Subject::create([
            'name'        => 'Bahasa Indonesia',
            'code'        => 'IND',
            'icon'        => '📚',
            'color'       => 'emerald',
            'description' => 'Mata pelajaran literasi bahasa, teks laporan, dan komunikasi profesional.',
        ]);

        // 4 Rombel Kelas Spesifik (Tingkat + Jurusan + Rombel)
        $class1 = SchoolClass::create([
            'major_id'   => $majorRpl->id,
            'grade'      => 'XI',
            'section'    => '1',
            'major_name' => 'PPLG',
        ]);

        $class2 = SchoolClass::create([
            'major_id'   => $majorTkj->id,
            'grade'      => 'XII',
            'section'    => '1',
            'major_name' => 'TJKT',
        ]);

        $class3 = SchoolClass::create([
            'major_id'   => $majorRpl->id,
            'grade'      => 'X',
            'section'    => '1',
            'major_name' => 'PPLG',
        ]);

        $class4 = SchoolClass::create([
            'major_id'   => $majorTitl->id,
            'grade'      => 'X',
            'section'    => '2',
            'major_name' => 'TITL',
        ]);

        // 2 Guru
        // Budi Santoso mengampu 2 Mapel: Informatika dan Teknik Elektro
        $teacher1 = Teacher::create([
            'name' => 'Budi Santoso',
            'identity_number' => 'NIP123456',
            'password' => $password,
        ]);
        $teacher1->subjects()->attach([$subjectInformatika->id, $subjectElektro->id]);

        // Siti Aminah mengampu Matematika dan Bahasa Indonesia
        $teacher2 = Teacher::create([
            'name' => 'Siti Aminah',
            'identity_number' => 'NIP123457',
            'password' => $password,
        ]);
        $teacher2->subjects()->attach([$subjectMatematika->id, $subjectIndonesia->id]);

        // 5 Siswa
        Student::insert([
            ['name' => 'Siswa Satu', 'identity_number' => 'NISN001', 'class_id' => $class1->id, 'password' => $password],
            ['name' => 'Siswa Dua', 'identity_number' => 'NISN002', 'class_id' => $class1->id, 'password' => $password],
            ['name' => 'Siswa Tiga', 'identity_number' => 'NISN003', 'class_id' => $class1->id, 'password' => $password],
            ['name' => 'Siswa Empat', 'identity_number' => 'NISN004', 'class_id' => $class2->id, 'password' => $password],
            ['name' => 'Siswa Lima', 'identity_number' => 'NISN005', 'class_id' => $class2->id, 'password' => $password],
        ]);

        // Modul 1 (Informatika - Budi Santoso)
        $module = Module::create([
            'teacher_id' => $teacher1->id,
            'class_id'   => $class1->id,
            'subject_id' => $subjectInformatika->id,
            'title'      => 'Sistem Basis Data Lanjut',
            'informasi_umum_data' => [
                'kata_pengantar' => 'Puji syukur kami panjatkan kepada Tuhan Yang Maha Esa atas tersusunnya modul Sistem Basis Data ini.',
                'tujuan_pembelajaran' => 'Memahami konsep basis data relasional dan operasi dasar SQL.',
                'glosarium' => [
                    ['istilah' => 'DBMS', 'definisi' => 'Sistem perangkat lunak untuk mengelola basis data.'],
                    ['istilah' => 'Query', 'definisi' => 'Perintah untuk mengambil atau memanipulasi data.']
                ],
                'daftar_isi' => [
                    ['judul' => 'Bab 1 — Pengantar Basis Data'],
                    ['judul' => 'Bab 2 — Desain ERD & Normalisasi']
                ],
                'daftar_pustaka' => [
                    ['judul' => 'Database System Concepts (7th Edition)', 'penulis' => 'Silberschatz, A., Korth, H. F., & Sudarshan, S.', 'tahun' => '2020', 'tautan' => 'https://db-book.com'],
                    ['judul' => 'Dasar Perancangan Basis Data Relasional', 'penulis' => 'Kemendikbudristek', 'tahun' => '2023', 'tautan' => '']
                ],
                'toggles' => [
                    'cover' => true,
                    'kata_pengantar' => true,
                    'daftar_isi' => true,
                    'peta_konsep' => true,
                    'glosarium' => true,
                    'petunjuk_penggunaan' => true,
                    'tujuan_pembelajaran' => true,
                    'daftar_pustaka' => true,
                ]
            ],
            'pre_test_data' => [
                'judul' => 'Pre-test: Pemahaman Awal Basis Data',
                'durasi_menit' => 15,
                'kktp' => 75,
                'petunjuk' => 'Kerjakan soal-soal berikut untuk mengukur pemahaman awal Anda sebelum memulai kegiatan belajar.',
                'acak_soal' => false,
                'questions' => [
                    [
                        'id' => 1,
                        'pertanyaan' => 'Perangkat lunak yang digunakan untuk mengelola, membuat, dan memanipulasi database disebut...',
                        'pilihan' => [
                            'A' => 'DBMS (Database Management System)',
                            'B' => 'Operating System (OS)',
                            'C' => 'Spreadsheet Application',
                            'D' => 'Web Browser',
                            'E' => 'Compiler'
                        ],
                        'kunci_jawaban' => 'A',
                        'bobot' => 50,
                        'pembahasan' => 'DBMS adalah software pengelola basis data seperti MySQL, PostgreSQL, dan Oracle.'
                    ],
                    [
                        'id' => 2,
                        'pertanyaan' => 'Perintah SQL yang digunakan untuk mengambil data dari tabel adalah...',
                        'pilihan' => [
                            'A' => 'INSERT INTO',
                            'B' => 'SELECT',
                            'C' => 'UPDATE',
                            'D' => 'DROP TABLE',
                            'E' => 'ALTER TABLE'
                        ],
                        'kunci_jawaban' => 'B',
                        'bobot' => 50,
                        'pembahasan' => 'Perintah SELECT adalah bagian dari DQL/DML untuk query data.'
                    ]
                ]
            ],
            'materi_data' => [
                'judul_materi' => 'Kegiatan Belajar 1: Konsep Dasar & Arsitektur Sistem Basis Data',
                'estimasi_waktu' => 45,
                'uraian_materi' => "## A. Pengantar Sistem Basis Data\nBasis data adalah kumpulan data terstruktur yang saling berhubungan secara logis.\n\n### 1. Definisi DBMS\nDBMS (Database Management System) adalah perangkat lunak untuk mengelola basis data.\n\n[INFO] Modul ini menggunakan MySQL / MariaDB di Laragon.",
                'ringkasan_materi' => 'Konsep utama database relasional dan pengantar perintah SQL dasar.',
                'poin_penting' => [
                    'DBMS bertindak sebagai perantara aplikasi dan berkas data.',
                    'SQL dibagi menjadi DDL, DML, DCL, dan TCL.'
                ],
                'ppt_file_path' => null,
                'ppt_file_name' => null,
                'ppt_file_size' => null,
            ],
            'post_test_data' => [
                'judul' => 'Post-test: Evaluasi Pemahaman Sistem Basis Data',
                'durasi_menit' => 20,
                'kktp' => 75,
                'petunjuk' => 'Kerjakan soal evaluasi post-test berikut untuk mengukur ketuntasan belajar Anda setelah menyelesaikan seluruh materi dan tugas.',
                'acak_soal' => false,
                'questions' => [
                    [
                        'id' => 1,
                        'pertanyaan' => 'Perintah SQL yang digunakan untuk menggabungkan baris dari dua tabel berdasarkan relasi kolom yang cocok antar keduanya adalah...',
                        'pilihan' => [
                            'A' => 'INNER JOIN',
                            'B' => 'UNION ALL',
                            'C' => 'GROUP BY',
                            'D' => 'ORDER BY',
                            'E' => 'HAVING'
                        ],
                        'kunci_jawaban' => 'A',
                        'bobot' => 50,
                        'pembahasan' => 'INNER JOIN mencocokkan data antar tabel berdasarkan foreign key dan primary key yang berhubungan.'
                    ],
                    [
                        'id' => 2,
                        'pertanyaan' => 'Perintah TCL yang digunakan untuk membatalkan seluruh operasi transaksi database yang belum disimpan secara permanen adalah...',
                        'pilihan' => [
                            'A' => 'COMMIT',
                            'B' => 'SAVEPOINT',
                            'C' => 'ROLLBACK',
                            'D' => 'REVOKE',
                            'E' => 'GRANT'
                        ],
                        'kunci_jawaban' => 'C',
                        'bobot' => 50,
                        'pembahasan' => 'ROLLBACK membatalkan transaksi dan mengembalikan kondisi data sebelum transaksi dimulai.'
                    ]
                ]
            ],
            'has_pre_test' => true,
            'has_materi' => true,
            'has_video' => true,
            'has_embed' => true,
            'has_job_sheet' => true,
            'has_lkpd' => true,
            'has_post_test' => true,
            'status' => 'published',
        ]);

        // Seed Pre-test Relasional
        $preTest = \App\Models\PreTest::create([
            'module_id'           => $module->id,
            'title'               => 'Pre-test: Pemahaman Awal Basis Data',
            'duration_minutes'    => 15,
            'kktp'                => 75,
            'instructions'        => 'Kerjakan soal-soal berikut untuk mengukur pemahaman awal Anda sebelum memulai kegiatan belajar.',
            'randomize_questions' => false,
        ]);

        \App\Models\PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Perangkat lunak yang digunakan untuk mengelola, membuat, dan memanipulasi database disebut...',
            'options'        => [
                'A' => 'DBMS (Database Management System)',
                'B' => 'Operating System (OS)',
                'C' => 'Spreadsheet Application',
                'D' => 'Web Browser',
                'E' => 'Compiler'
            ],
            'correct_answer' => 'A',
            'score_weight'   => 50,
            'explanation'    => 'DBMS adalah software pengelola basis data seperti MySQL, PostgreSQL, dan Oracle.',
            'order_num'      => 1,
        ]);

        \App\Models\PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Perintah SQL yang digunakan untuk mengambil data dari tabel adalah...',
            'options'        => [
                'A' => 'INSERT INTO',
                'B' => 'SELECT',
                'C' => 'UPDATE',
                'D' => 'DROP TABLE',
                'E' => 'ALTER TABLE'
            ],
            'correct_answer' => 'B',
            'score_weight'   => 50,
            'explanation'    => 'Perintah SELECT adalah bagian dari DQL/DML untuk query data.',
            'order_num'      => 2,
        ]);

        // Seed Post-test Relasional
        $postTest = \App\Models\PostTest::create([
            'module_id'           => $module->id,
            'title'               => 'Post-test: Evaluasi Pemahaman Sistem Basis Data',
            'duration_minutes'    => 20,
            'kktp'                => 75,
            'instructions'        => 'Kerjakan soal evaluasi post-test berikut untuk mengukur ketuntasan belajar Anda setelah menyelesaikan seluruh materi dan tugas.',
            'randomize_questions' => false,
        ]);

        \App\Models\PostTestQuestion::create([
            'post_test_id'   => $postTest->id,
            'question_text'  => 'Perintah SQL yang digunakan untuk menggabungkan baris dari dua tabel berdasarkan relasi kolom yang cocok antar keduanya adalah...',
            'options'        => [
                'A' => 'INNER JOIN',
                'B' => 'UNION ALL',
                'C' => 'GROUP BY',
                'D' => 'ORDER BY',
                'E' => 'HAVING'
            ],
            'correct_answer' => 'A',
            'score_weight'   => 50,
            'explanation'    => 'INNER JOIN mencocokkan data antar tabel berdasarkan foreign key dan primary key yang berhubungan.',
            'order_num'      => 1,
        ]);

        \App\Models\PostTestQuestion::create([
            'post_test_id'   => $postTest->id,
            'question_text'  => 'Perintah TCL yang digunakan untuk membatalkan seluruh operasi transaksi database yang belum disimpan secara permanen adalah...',
            'options'        => [
                'A' => 'COMMIT',
                'B' => 'SAVEPOINT',
                'C' => 'ROLLBACK',
                'D' => 'REVOKE',
                'E' => 'GRANT'
            ],
            'correct_answer' => 'C',
            'score_weight'   => 50,
            'explanation'    => 'ROLLBACK membatalkan transaksi dan mengembalikan kondisi data sebelum transaksi dimulai.',
            'order_num'      => 2,
        ]);

        // Seed JobSheet & LKPD untuk modul
        $jobSheet = \App\Models\JobSheet::create([
            'module_id'     => $module->id,
            'pdf_file_path' => 'job_sheets/jobsheet_basis_data.pdf',
        ]);

        $lkpd = \App\Models\Lkpd::create([
            'module_id'     => $module->id,
            'pdf_file_path' => 'lkpds/lkpd_studi_kasus_database.pdf',
        ]);

        $student1 = \App\Models\Student::where('identity_number', 'NISN001')->first();
        $student2 = \App\Models\Student::where('identity_number', 'NISN002')->first();
        $student3 = \App\Models\Student::where('identity_number', 'NISN003')->first();

        // ─── Submissions Siswa 1 (Sudah Dinilai Tuntas / Graded) ───
        if ($student1) {
            \App\Models\VideoSummary::create([
                'module_id'    => $module->id,
                'student_id'   => $student1->id,
                'summary_text' => 'Video ini menjelaskan arsitektur client-server DBMS, konsep relasi antar tabel (Primary Key & Foreign Key), serta implementasi query JOIN dalam pengelolaan basis data relasional.',
                'manual_score' => 90,
            ]);

            \App\Models\EmbedSubmission::create([
                'module_id'       => $module->id,
                'student_id'      => $student1->id,
                'screenshot_path' => 'embed_submissions/sample_terminal.png',
                'manual_score'    => 85,
            ]);

            \App\Models\JobSheetSubmission::create([
                'job_sheet_id'       => $jobSheet->id,
                'student_id'         => $student1->id,
                'uploaded_file_path' => 'job_sheet_submissions/laporan_praktik_siswa1.pdf',
                'manual_score'       => 90,
            ]);

            \App\Models\Submission::create([
                'lkpd_id'            => $lkpd->id,
                'student_id'         => $student1->id,
                'uploaded_file_path' => 'lkpd_submissions/jawaban_lkpd_siswa1.pdf',
                'manual_score'       => 95,
            ]);

            \App\Models\StudentResult::create([
                'student_id'      => $student1->id,
                'module_id'       => $module->id,
                'pre_test_score'  => 100,
                'video_score'     => 90,
                'embed_score'     => 85,
                'job_sheet_score' => 90,
                'lkpd_score'      => 95,
                'post_test_score' => 100,
                'summative_score' => 93,
                'grading_status'  => 'graded',
            ]);
        }

        // ─── Submissions Siswa 2 (Menunggu Penilaian / Pending) ───
        if ($student2) {
            \App\Models\VideoSummary::create([
                'module_id'    => $module->id,
                'student_id'   => $student2->id,
                'summary_text' => 'Saya telah mempelajari definisi perintah SQL DDL dan DML. Pemahaman tentang kunci primer sangat penting untuk mencegah inkonsistensi redundansi data.',
                'manual_score' => null,
            ]);

            \App\Models\EmbedSubmission::create([
                'module_id'       => $module->id,
                'student_id'      => $student2->id,
                'screenshot_path' => 'embed_submissions/sample_terminal.png',
                'manual_score'    => null,
            ]);

            \App\Models\JobSheetSubmission::create([
                'job_sheet_id'       => $jobSheet->id,
                'student_id'         => $student2->id,
                'uploaded_file_path' => 'job_sheet_submissions/laporan_praktik_siswa2.pdf',
                'manual_score'       => null,
            ]);

            \App\Models\Submission::create([
                'lkpd_id'            => $lkpd->id,
                'student_id'         => $student2->id,
                'uploaded_file_path' => 'lkpd_submissions/jawaban_lkpd_siswa2.pdf',
                'manual_score'       => null,
            ]);

            \App\Models\StudentResult::create([
                'student_id'      => $student2->id,
                'module_id'       => $module->id,
                'pre_test_score'  => 50,
                'video_score'     => null,
                'embed_score'     => null,
                'job_sheet_score' => null,
                'lkpd_score'      => null,
                'post_test_score' => 100,
                'summative_score' => 75,
                'grading_status'  => 'pending',
            ]);
        }

        // ─── Submissions Siswa 3 (Baru mengerjakan sebagian) ───
        if ($student3) {
            \App\Models\VideoSummary::create([
                'module_id'    => $module->id,
                'student_id'   => $student3->id,
                'summary_text' => 'Ringkasan singkat mengenai pengantar basis data dan fungsi DBMS pada industri perangkat lunak modern.',
                'manual_score' => null,
            ]);

            \App\Models\StudentResult::create([
                'student_id'      => $student3->id,
                'module_id'       => $module->id,
                'pre_test_score'  => 100,
                'video_score'     => null,
                'embed_score'     => null,
                'job_sheet_score' => null,
                'lkpd_score'      => null,
                'post_test_score' => null,
                'summative_score' => 100,
                'grading_status'  => 'pending',
            ]);
        }

        // ─── Modul 2: Teknik Elektro (Guru: Budi Santoso, Target: XII TKJ) ───
        $module2 = Module::create([
            'teacher_id' => $teacher1->id,
            'class_id'   => $class2->id,
            'subject_id' => $subjectElektro->id,
            'title'      => 'Dasar Pengukuran Listrik & Logika Digital',
            'informasi_umum_data' => [
                'kata_pengantar' => 'Modul ini disusun untuk memberikan pemahaman aplikatif mengenai rangkaian listrik dasar dan gerbang logika.',
                'tujuan_pembelajaran' => 'Mampu menganalisis hukum Ohm, hukum Kirchhoff, dan tabel kebenaran gerbang logika digital.',
                'glosarium' => [
                    ['istilah' => 'Multimeter', 'definisi' => 'Alat ukur untuk mengukur tegangan, arus, dan resistansi.'],
                    ['istilah' => 'Gerbang Logika', 'definisi' => 'Blok pembangun dasar sirkuit elektronika digital.']
                ],
                'daftar_isi' => [
                    ['judul' => 'Bab 1 — Hukum Dasar Rangkaian Listrik'],
                    ['judul' => 'Bab 2 — Gerbang Logika & Aljabar Boolean']
                ],
                'daftar_pustaka' => [
                    ['judul' => 'Prinsip-Prinsip Elektronika Dasar', 'penulis' => 'Malvino, A. P.', 'tahun' => '2021', 'tautan' => ''],
                ],
                'toggles' => [
                    'cover' => true,
                    'kata_pengantar' => true,
                    'daftar_isi' => true,
                    'peta_konsep' => true,
                    'glosarium' => true,
                    'petunjuk_penggunaan' => true,
                    'tujuan_pembelajaran' => true,
                    'daftar_pustaka' => true,
                ]
            ],
            'materi_data' => [
                'judul_materi' => 'Kegiatan Belajar 1: Hukum Ohm & Analisis Sirkuit Seri-Paralel',
                'estimasi_waktu' => 45,
                'uraian_materi' => "## A. Dasar Hukum Ohm\nHukum Ohm menyatakan bahwa arus yang mengalir melalui konduktor berbanding lurus dengan beda potensial dan berbanding terbalik dengan hambatan (V = I * R).",
                'ringkasan_materi' => 'Prinsip kalkulasi tegangan, kuat arus, dan hambatan total rangkaian kombinasi.',
                'poin_penting' => [
                    'Arus pada rangkaian seri bernilai konstan di setiap cabang.',
                    'Tegangan pada rangkaian paralel bernilai sama di seluruh percabangan.'
                ],
            ],
            'has_pre_test'   => false,
            'has_materi'     => true,
            'has_video'      => true,
            'has_embed'      => true,
            'has_job_sheet'  => true,
            'has_lkpd'       => false,
            'has_post_test'  => false,
            'status'         => 'published',
        ]);

        $student4 = \App\Models\Student::where('identity_number', 'NISN004')->first();
        if ($student4) {
            \App\Models\StudentResult::create([
                'student_id'      => $student4->id,
                'module_id'       => $module2->id,
                'pre_test_score'  => null,
                'video_score'     => 88,
                'embed_score'     => 92,
                'job_sheet_score' => 85,
                'lkpd_score'      => null,
                'post_test_score' => null,
                'summative_score' => 88,
                'grading_status'  => 'graded',
            ]);
        }
    }
}
