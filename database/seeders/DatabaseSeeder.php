<?php

namespace Database\Seeders;

use App\Models\Admin;
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
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // ═════════════════════════════════════════════════════════════════
        // 1. ADMIN (Supervisi & Manajemen Kurikulum)
        // ═════════════════════════════════════════════════════════════════
        Admin::create([
            'name'            => 'Drs. Ahmad Fauzi, M.Pd.',
            'identity_number' => 'NIP999001',
            'password'        => $password,
        ]);

        // ═════════════════════════════════════════════════════════════════
        // 2. 4 JURUSAN (TE, DP, TL, TO)
        // ═════════════════════════════════════════════════════════════════
        $majorTe = Major::create([
            'name'        => 'Teknik Elektro',
            'code'        => 'TE',
            'description' => 'Program keahlian sistem elektronika, instrumentasi, logika digital, dan sistem kontrol.',
        ]);

        $majorDp = Major::create([
            'name'        => 'Desain Permodelan & Informasi Bangunan',
            'code'        => 'DP',
            'description' => 'Program keahlian gambar teknik digital, pemodelan 3D, dan Building Information Modeling (BIM).',
        ]);

        $majorTl = Major::create([
            'name'        => 'Teknik Listrik',
            'code'        => 'TL',
            'description' => 'Program keahlian instalasi tenaga listrik, panel daya industri, dan sistem kelistrikan.',
        ]);

        $majorTo = Major::create([
            'name'        => 'Teknik Otomotif',
            'code'        => 'TO',
            'description' => 'Program keahlian mekanika kendaraan, sistem kelistrikan otomotif, dan mesin bertenaga.',
        ]);

        // ═════════════════════════════════════════════════════════════════
        // 3. KELAS HANYA TINGKAT X (2 ROMBEL PER JURUSAN = 8 KELAS)
        // ═════════════════════════════════════════════════════════════════
        $classTe1 = SchoolClass::create(['major_id' => $majorTe->id, 'grade' => 'X', 'section' => '1', 'major_name' => 'TE']);
        $classTe2 = SchoolClass::create(['major_id' => $majorTe->id, 'grade' => 'X', 'section' => '2', 'major_name' => 'TE']);

        $classDp1 = SchoolClass::create(['major_id' => $majorDp->id, 'grade' => 'X', 'section' => '1', 'major_name' => 'DP']);
        $classDp2 = SchoolClass::create(['major_id' => $majorDp->id, 'grade' => 'X', 'section' => '2', 'major_name' => 'DP']);

        $classTl1 = SchoolClass::create(['major_id' => $majorTl->id, 'grade' => 'X', 'section' => '1', 'major_name' => 'TL']);
        $classTl2 = SchoolClass::create(['major_id' => $majorTl->id, 'grade' => 'X', 'section' => '2', 'major_name' => 'TL']);

        $classTo1 = SchoolClass::create(['major_id' => $majorTo->id, 'grade' => 'X', 'section' => '1', 'major_name' => 'TO']);
        $classTo2 = SchoolClass::create(['major_id' => $majorTo->id, 'grade' => 'X', 'section' => '2', 'major_name' => 'TO']);

        // ═════════════════════════════════════════════════════════════════
        // 4. 2 MATA PELAJARAN: Informatika dan Jaringan
        // ═════════════════════════════════════════════════════════════════
        $subjectInformatika = Subject::create([
            'name'        => 'Informatika',
            'code'        => 'INF',
            'icon'        => '💻',
            'color'       => 'blue',
            'description' => 'Mata pelajaran informatika, logika pemrograman, algoritma, dan sistem komputasi.',
        ]);

        $subjectJaringan = Subject::create([
            'name'        => 'Jaringan',
            'code'        => 'JAR',
            'icon'        => '🌐',
            'color'       => 'indigo',
            'description' => 'Mata pelajaran infrastruktur jaringan komputer, konfigurasi subnetting, routing, dan komunikasi data.',
        ]);

        // ═════════════════════════════════════════════════════════════════
        // 5. 3 GURU BERBEDA (SETIAP GURU MENGAMPU INFORMATIKA & JARINGAN)
        // ═════════════════════════════════════════════════════════════════
        $teacher1 = Teacher::create([
            'name'            => 'Budi Santoso, S.Kom.',
            'identity_number' => 'NIP123456',
            'password'        => $password,
        ]);
        $teacher1->subjects()->attach([$subjectInformatika->id, $subjectJaringan->id]);

        $teacher2 = Teacher::create([
            'name'            => 'Siti Aminah, M.T.',
            'identity_number' => 'NIP123457',
            'password'        => $password,
        ]);
        $teacher2->subjects()->attach([$subjectInformatika->id, $subjectJaringan->id]);

        $teacher3 = Teacher::create([
            'name'            => 'Hendra Wijaya, S.T.',
            'identity_number' => 'NIP123458',
            'password'        => $password,
        ]);
        $teacher3->subjects()->attach([$subjectInformatika->id, $subjectJaringan->id]);

        // ═════════════════════════════════════════════════════════════════
        // 6. SISWA BINAAN DI KELAS TINGKAT X
        // ═════════════════════════════════════════════════════════════════
        $studentsData = [
            ['name' => 'Ahmad Pratama',  'identity_number' => 'NISN001', 'class_id' => $classTe1->id],
            ['name' => 'Bunga Citra',    'identity_number' => 'NISN002', 'class_id' => $classTe1->id],
            ['name' => 'Candra Wijaya',  'identity_number' => 'NISN003', 'class_id' => $classDp1->id],
            ['name' => 'Dedi Kurniawan', 'identity_number' => 'NISN004', 'class_id' => $classDp1->id],
            ['name' => 'Eka Safitri',    'identity_number' => 'NISN005', 'class_id' => $classTl1->id],
            ['name' => 'Fajar Hidayat',  'identity_number' => 'NISN006', 'class_id' => $classTo1->id],
            ['name' => 'Gita Lestari',   'identity_number' => 'NISN007', 'class_id' => $classTo2->id],
        ];

        $studentModels = [];
        foreach ($studentsData as $st) {
            $student = Student::create([
                'name'            => $st['name'],
                'identity_number' => $st['identity_number'],
                'class_id'        => $st['class_id'],
                'password'        => $password,
            ]);
            $student->subjects()->attach([$subjectInformatika->id, $subjectJaringan->id]);
            $studentModels[] = $student;
        }

        // Helper closure to build complete module record
        $createModuleRecord = function ($teacher, $class, $subject, $title, $materiJudul, $materiContent, $preTestQ, $postTestQ, $isShared = false) {
            $informasiUmum = [
                'kata_pengantar'      => "Puji syukur kami panjatkan kepada Tuhan Yang Maha Esa atas tersusunnya E-Modul {$title}.",
                'tujuan_pembelajaran' => "Peserta didik mampu memahami dan menguasai kompetensi dasar serta praktik langsung pada materi {$title}.",
                'peta_konsep'         => "Peta konsep modul mencakup fondasi teoritis, demonstrasi interaktif, simulasi praktik, dan evaluasi hasil belajar mandiri.",
                'petunjuk_penggunaan' => "1. Pelajari uraian materi secara mandiri.\n2. Tonton tayangan video pembelajaran dan buat rangkuman.\n3. Kerjakan tugas lembar kerja dan uji pemahaman Anda pada evaluasi akhir.",
                'glosarium'           => [
                    ['istilah' => 'Konsep Pokok', 'definisi' => 'Prinsip fundamental yang mendasari materi pembelajaran ini.'],
                    ['istilah' => 'Implementasi', 'definisi' => 'Penerapan praktis konsep dalam proyek nyata.'],
                ],
                'daftar_isi'          => [
                    ['judul' => 'Bagian I — Pengantar & Konsep Fundamental'],
                    ['judul' => 'Bagian II — Penerapan & Lembar Praktik'],
                ],
                'daftar_pustaka'      => [
                    ['judul' => "Buku Panduan {$title} Kurikulum Merdeka", 'penulis' => 'Pusat Kurikulum dan Perbukuan Kemendikbudristek', 'tahun' => '2024', 'tautan' => 'https://kemdikbud.go.id'],
                    ['judul' => 'Standar Kompetensi Keahlian SMK Nasional', 'penulis' => 'Direktorat SMK', 'tahun' => '2023', 'tautan' => ''],
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
                'judul_materi'     => $materiJudul,
                'estimasi_waktu'   => 45,
                'uraian_materi'    => "## A. Pengantar Teori\n{$materiContent}\n\n### 1. Prinsip Kerja & Fondasi\nModul ini mengedepankan pendekatan *Project-Based Learning* untuk memperkuat pemahaman aplikatif peserta didik.\n\n[INFO] Ikuti setiap instruksi keselamatan kerja dan langkah praktik yang tertera pada lembar kerja.",
                'ringkasan_materi' => "Ringkasan intisari materi {$title}.",
                'poin_penting'     => [
                    'Kuasai konsep dasar sebelum memulai pengujian praktik.',
                    'Gunakan instrumen dan simulator yang disediakan untuk memvalidasi pemahaman.',
                ],
                'ppt_file_path'    => null,
                'ppt_file_name'    => null,
                'ppt_file_size'    => null,
            ];

            $preTestData = [
                'judul'        => "Pre-test: Pemahaman Awal {$title}",
                'durasi_menit' => 15,
                'kktp'         => 75,
                'petunjuk'     => 'Kerjakan soal pre-test ini untuk mengukur pemahaman awal Anda sebelum memulai pembelajaran.',
                'acak_soal'    => false,
                'questions'    => $preTestQ,
            ];

            $postTestData = [
                'judul'        => "Post-test: Evaluasi Akhir {$title}",
                'durasi_menit' => 20,
                'kktp'         => 75,
                'petunjuk'     => 'Kerjakan soal evaluasi post-test berikut untuk mengukur ketuntasan belajar Anda.',
                'acak_soal'    => false,
                'questions'    => $postTestQ,
            ];

            $mod = Module::create([
                'teacher_id'          => $teacher->id,
                'class_id'            => $class->id,
                'subject_id'          => $subject->id,
                'title'               => $title,
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
                'is_shared'           => $isShared,
                'shared_at'           => $isShared ? now() : null,
            ]);

            // Create relational PreTest
            $preTestModel = PreTest::create([
                'module_id'           => $mod->id,
                'title'               => "Pre-test: Pemahaman Awal {$title}",
                'duration_minutes'    => 15,
                'kktp'                => 75,
                'instructions'        => 'Kerjakan soal pre-test ini untuk mengukur pemahaman awal Anda.',
                'randomize_questions' => false,
            ]);

            foreach ($preTestQ as $idx => $q) {
                PreTestQuestion::create([
                    'pre_test_id'    => $preTestModel->id,
                    'question_text'  => $q['pertanyaan'],
                    'options'        => $q['pilihan'],
                    'correct_answer' => $q['kunci_jawaban'],
                    'score_weight'   => $q['bobot'],
                    'explanation'    => $q['pembahasan'] ?? 'Pembahasan soal pre-test.',
                    'order_num'      => $idx + 1,
                ]);
            }

            // Create relational PostTest
            $postTestModel = PostTest::create([
                'module_id'           => $mod->id,
                'title'               => "Post-test: Evaluasi Akhir {$title}",
                'duration_minutes'    => 20,
                'kktp'                => 75,
                'instructions'        => 'Kerjakan soal evaluasi post-test berikut.',
                'randomize_questions' => false,
            ]);

            foreach ($postTestQ as $idx => $q) {
                PostTestQuestion::create([
                    'post_test_id'   => $postTestModel->id,
                    'question_text'  => $q['pertanyaan'],
                    'options'        => $q['pilihan'],
                    'correct_answer' => $q['kunci_jawaban'],
                    'score_weight'   => $q['bobot'],
                    'explanation'    => $q['pembahasan'] ?? 'Pembahasan soal post-test.',
                    'order_num'      => $idx + 1,
                ]);
            }

            // Create JobSheet & LKPD
            $jobSheet = JobSheet::create([
                'module_id'     => $mod->id,
                'pdf_file_path' => 'job_sheets/sample_jobsheet.pdf',
            ]);

            $lkpd = Lkpd::create([
                'module_id'     => $mod->id,
                'pdf_file_path' => 'lkpds/sample_lkpd.pdf',
            ]);

            return ['module' => $mod, 'jobSheet' => $jobSheet, 'lkpd' => $lkpd];
        };

        // ═════════════════════════════════════════════════════════════════
        // 7. 3 MODUL GURU 1: Budi Santoso, S.Kom.
        // ═════════════════════════════════════════════════════════════════
        $qPre1 = [
            [
                'id' => 1, 'pertanyaan' => 'Tag HTML standar yang digunakan untuk membuat judul utama paling tinggi tingkatannya adalah...',
                'pilihan' => ['A' => '<h1>', 'B' => '<head>', 'C' => '<title>', 'D' => '<header>', 'E' => '<top>'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Tag <h1> merepresentasikan heading tingkat pertama pada dokumen HTML.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Struktur kontrol percabangan logika dalam bahasa pemrograman menggunakan kata kunci...',
                'pilihan' => ['A' => 'if - else', 'B' => 'for - loop', 'C' => 'while - do', 'D' => 'echo - print', 'E' => 'include - require'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'if-else digunakan untuk percabangan pemilihan kondisi.',
            ]
        ];
        $qPost1 = [
            [
                'id' => 1, 'pertanyaan' => 'Properti CSS yang digunakan untuk mengatur warna latar belakang elemen adalah...',
                'pilihan' => ['A' => 'background-color', 'B' => 'color', 'C' => 'font-color', 'D' => 'border-color', 'E' => 'fill-color'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'background-color menetapkan warna background elemen.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Tipe data yang hanya menyimpan nilai kebenaran TRUE atau FALSE adalah...',
                'pilihan' => ['A' => 'Boolean', 'B' => 'Integer', 'C' => 'String', 'D' => 'Float', 'E' => 'Array'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Boolean merepresentasikan nilai logika benar atau salah.',
            ]
        ];
        $res1 = $createModuleRecord(
            $teacher1, $classTe1, $subjectInformatika,
            'Dasar Pemrograman Web & Struktur Logika Algoritma',
            'Kegiatan Belajar 1: Pengantar HTML5, Tata Letak CSS3, dan Logika Percabangan Algoritma',
            'Pemrograman web dasar mempelajari struktur dokumen semantik HTML5, penataan gaya visual CSS3, serta logika algoritma dasar untuk merancang halaman web interaktif.',
            $qPre1, $qPost1, true
        );

        $qPre2 = [
            [
                'id' => 1, 'pertanyaan' => 'Model referensi OSI terdiri dari berapa lapisan (layers)?',
                'pilihan' => ['A' => '7 Layer', 'B' => '4 Layer', 'C' => '5 Layer', 'D' => '6 Layer', 'E' => '8 Layer'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'OSI 7 Layer: Physical, Data Link, Network, Transport, Session, Presentation, Application.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Jenis konektor yang umum digunakan pada kabel UTP jaringan LAN adalah...',
                'pilihan' => ['A' => 'RJ-45', 'B' => 'RJ-11', 'C' => 'BNC', 'D' => 'FC', 'E' => 'SC'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Konektor RJ-45 adalah standar terminasi kabel UTP Cat5e/Cat6.',
            ]
        ];
        $qPost2 = [
            [
                'id' => 1, 'pertanyaan' => 'Urutan warna standar kabel straight-through T568B diawali dengan warna...',
                'pilihan' => ['A' => 'Putih-Oranye, Oranye', 'B' => 'Putih-Hijau, Hijau', 'C' => 'Putih-Biru, Biru', 'D' => 'Putih-Cokelat, Cokelat', 'E' => 'Oranye, Putih-Oranye'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Standar T568B dimulai dengan: Putih-Oranye, Oranye, Putih-Hijau, Biru, Putih-Biru, Hijau, Putih-Cokelat, Cokelat.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Perangkat keras jaringan yang bekerja pada Data Link Layer (Layer 2) untuk meneruskan frame berdasarkan MAC Address adalah...',
                'pilihan' => ['A' => 'Switch', 'B' => 'Hub', 'C' => 'Repeater', 'D' => 'Modem dial-up', 'E' => 'Kabel Coaxial'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Switch Layer 2 membaca MAC address table untuk switching frame data.',
            ]
        ];
        $res2 = $createModuleRecord(
            $teacher1, $classTe1, $subjectJaringan,
            'Fondasi Jaringan Komputer, Model OSI & Pengkabelan LAN',
            'Kegiatan Belajar 1: Analisis Model OSI 7 Layer, Standar Crimping T568A/B, dan Uji Konektivitas LAN',
            'Jaringan komputer menghubungkan berbagai node perangkat untuk berbagi sumber daya melalui media transmisi terstandarisasi dan protokol komunikasi yang andal.',
            $qPre2, $qPost2, false
        );

        $qPre3 = [
            [
                'id' => 1, 'pertanyaan' => 'Perintah Linux CLI untuk melihat direktori aktif saat ini adalah...',
                'pilihan' => ['A' => 'pwd', 'B' => 'ls', 'C' => 'cd', 'D' => 'whoami', 'E' => 'dir'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'pwd (print working directory) menampilkan path direktori yang sedang dibuka.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Superuser dengan hak akses tertinggi pada sistem operasi Linux adalah...',
                'pilihan' => ['A' => 'root', 'B' => 'admin', 'C' => 'supervisor', 'D' => 'master', 'E' => 'system'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'root adalah akun administrator utama di lingkungan Unix/Linux.',
            ]
        ];
        $qPost3 = [
            [
                'id' => 1, 'pertanyaan' => 'Perintah untuk membuat folder/direktori baru di terminal Linux adalah...',
                'pilihan' => ['A' => 'mkdir', 'B' => 'touch', 'C' => 'cat', 'D' => 'rm', 'E' => 'mv'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'mkdir (make directory) membuat folder baru.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Hak akses chmod 755 pada file Linux memberikan izin kepada pemilik (owner) berupa...',
                'pilihan' => ['A' => 'Read, Write, Execute (rwx)', 'B' => 'Read Only (r--)', 'C' => 'Write Only (-w-)', 'D' => 'Execute Only (--x)', 'E' => 'No Access (---)'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Angka 7 pada chmod bernilai 4 (read) + 2 (write) + 1 (execute) = rwx.',
            ]
        ];
        $res3 = $createModuleRecord(
            $teacher1, $classDp1, $subjectInformatika,
            'Pengenalan Sistem Operasi Linux & Perintah Dasar CLI',
            'Kegiatan Belajar 1: Instalasi Linux Server, Manajemen File Direktori, dan Hak Akses Pengguna',
            'Sistem operasi Linux merupakan platform terbuka yang banyak digunakan pada server dan sistem komputasi modern karena stabilitas dan keamanannya.',
            $qPre3, $qPost3, false
        );

        // ═════════════════════════════════════════════════════════════════
        // 8. 3 MODUL GURU 2: Siti Aminah, M.T.
        // ═════════════════════════════════════════════════════════════════
        $qPre4 = [
            [
                'id' => 1, 'pertanyaan' => 'Berapa jumlah total host yang valid pada subnet IPv4 dengan prefix /29?',
                'pilihan' => ['A' => '6 host', 'B' => '8 host', 'C' => '14 host', 'D' => '30 host', 'E' => '2 host'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => '2^(32-29) - 2 = 8 - 2 = 6 host valid.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Alamat IP khusus yang digunakan untuk testing antarmuka loopback lokal adalah...',
                'pilihan' => ['A' => '127.0.0.1', 'B' => '192.168.1.1', 'C' => '10.0.0.1', 'D' => '255.255.255.255', 'E' => '0.0.0.0'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => '127.0.0.1 adalah standar IP loopback IPv4.',
            ]
        ];
        $qPost4 = [
            [
                'id' => 1, 'pertanyaan' => 'Proses membagi sebuah blok jaringan besar menjadi beberapa subjaringan yang lebih kecil disebut...',
                'pilihan' => ['A' => 'Subnetting', 'B' => 'Bridging', 'C' => 'Switching', 'D' => 'Routing', 'E' => 'Broadcasting'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Subnetting mengoptimalkan alokasi IP dan mereduksi broadcast domain.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Rute default (Default Gateway) pada konfigurasi tabel routing static dinotasikan dengan...',
                'pilihan' => ['A' => '0.0.0.0/0', 'B' => '255.255.255.255/32', 'C' => '127.0.0.1/8', 'D' => '192.168.0.0/16', 'E' => '10.0.0.0/8'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => '0.0.0.0/0 mewakili seluruh tujuan paket di luar subnet lokal.',
            ]
        ];
        $res4 = $createModuleRecord(
            $teacher2, $classDp1, $subjectJaringan,
            'Konfigurasi Subnetting IPv4 & Routing Dasar RouterOS',
            'Kegiatan Belajar 1: Perhitungan VLSM, Alokasi IP Address, dan Konfigurasi Static Routing',
            'Subnetting dan routing merupakan fondasi utama pengalamatan logis serta pengiriman paket data antar-jaringan yang berbeda.',
            $qPre4, $qPost4, true
        );

        $qPre5 = [
            [
                'id' => 1, 'pertanyaan' => 'Struktur data LIFO (Last In First Out) diterapkan pada...',
                'pilihan' => ['A' => 'Stack', 'B' => 'Queue', 'C' => 'Array', 'D' => 'Graph', 'E' => 'Tree'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Stack menggunakan prinsip LIFO.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Prinsip OOP yang membungkus data dan fungsi ke dalam objek terproteksi disebut...',
                'pilihan' => ['A' => 'Enkapsulasi', 'B' => 'Inheritance', 'C' => 'Polimorfisme', 'D' => 'Overloading', 'E' => 'Inisialisasi'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Enkapsulasi menyatukan data dan metode sekaligus membatasi akses langsung.',
            ]
        ];
        $qPost5 = [
            [
                'id' => 1, 'pertanyaan' => 'Konsep pewarisan sifat dan fungsi dari kelas induk ke kelas turunan dalam OOP adalah...',
                'pilihan' => ['A' => 'Inheritance', 'B' => 'Polymorphism', 'C' => 'Encapsulation', 'D' => 'Abstraction', 'E' => 'Aggregation'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Inheritance memungkinkan kelas anak mewarisi method dan atribut induk.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Tipe struktur data antrean yang menerapkan prinsip FIFO (First In First Out) adalah...',
                'pilihan' => ['A' => 'Queue', 'B' => 'Stack', 'C' => 'Linked List', 'D' => 'Binary Tree', 'E' => 'Heap'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Queue (antrean) menerapkan prinsip FIFO.',
            ]
        ];
        $res5 = $createModuleRecord(
            $teacher2, $classTl1, $subjectInformatika,
            'Algoritma Pemrograman Berorientasi Objek & Modularitas Kode',
            'Kegiatan Belajar 1: Pemodelan Kelas, Objek, Enkapsulasi, dan Implementasi Queue FIFO',
            'Pemrograman berorientasi objek meningkatkan modularitas, keterbacaan, dan pemeliharaan kode perangkat lunak melalui abstraksi kelas dan objek.',
            $qPre5, $qPost5, false
        );

        $qPre6 = [
            [
                'id' => 1, 'pertanyaan' => 'Frekuensi radio standar yang digunakan oleh teknologi jaringan Wi-Fi modern adalah...',
                'pilihan' => ['A' => '2.4 GHz dan 5 GHz', 'B' => '100 MHz dan 200 MHz', 'C' => '900 MHz dan 1.8 GHz', 'D' => '10 GHz dan 20 GHz', 'E' => '50 Hz dan 60 Hz'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Wi-Fi bekerja pada pita frekuensi 2.4 GHz dan 5 GHz (serta 6 GHz pada Wi-Fi 6E).',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Nama pengenal jaringan nirkabel yang dipancarkan oleh Access Point disebut...',
                'pilihan' => ['A' => 'SSID', 'B' => 'BSSID', 'C' => 'WPA', 'D' => 'DHCP', 'E' => 'NAT'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'SSID (Service Set Identifier) adalah nama jaringan nirkabel.',
            ]
        ];
        $qPost6 = [
            [
                'id' => 1, 'pertanyaan' => 'Protokol enkripsi keamanan Wi-Fi standar yang paling aman saat ini adalah...',
                'pilihan' => ['A' => 'WPA3', 'B' => 'WEP', 'C' => 'WPA', 'D' => 'Open Authentication', 'E' => 'WPA-TKIP'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'WPA3 menggunakan enkripsi 192-bit dan algoritma SAE.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Fitur manajemen bandwidth pada router untuk membatasi kecepatan unduh dan unggah klien adalah...',
                'pilihan' => ['A' => 'Queue / QoS (Quality of Service)', 'B' => 'DNS Server', 'C' => 'NTP Client', 'D' => 'Telnet', 'E' => 'SNMP'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Queue / QoS mengatur prioritas dan pembatasan bandwidth data.',
            ]
        ];
        $res6 = $createModuleRecord(
            $teacher2, $classTl1, $subjectJaringan,
            'Manajemen Bandwidth & Keamanan Jaringan Nirkabel Wi-Fi',
            'Kegiatan Belajar 1: Konfigurasi Access Point, Simple Queue MikroTik, dan Enkripsi WPA2/WPA3',
            'Manajemen bandwidth dan pengamanan jaringan nirkabel sangat penting untuk menjamin kualitas layanan data (QoS) serta melindungi data dari akses tanpa izin.',
            $qPre6, $qPost6, false
        );

        // ═════════════════════════════════════════════════════════════════
        // 9. 3 MODUL GURU 3: Hendra Wijaya, S.T.
        // ═════════════════════════════════════════════════════════════════
        $qPre7 = [
            [
                'id' => 1, 'pertanyaan' => 'Perintah SQL DDL yang digunakan untuk membuat tabel baru adalah...',
                'pilihan' => ['A' => 'CREATE TABLE', 'B' => 'INSERT INTO', 'C' => 'UPDATE SET', 'D' => 'DROP TABLE', 'E' => 'SELECT FROM'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'CREATE TABLE membuat entitas tabel baru dalam database.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Kolom unik yang membedakan setiap baris rekaman pada tabel database disebut...',
                'pilihan' => ['A' => 'Primary Key', 'B' => 'Foreign Key', 'C' => 'Index Key', 'D' => 'Candidate Key', 'E' => 'Composite Key'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Primary Key menjamin keunikan identitas data pada setiap baris.',
            ]
        ];
        $qPost7 = [
            [
                'id' => 1, 'pertanyaan' => 'Tahap normalisasi 1NF (First Normal Form) mensyaratkan setiap kolom bernilai...',
                'pilihan' => ['A' => 'Atomik (tunggal)', 'B' => 'Ganda', 'C' => 'Array', 'D' => 'Tergantung transitif', 'E' => 'Kombinasi'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => '1NF mengharuskan setiap field hanya memuat nilai tunggal (atomik).',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Perintah SQL untuk menggabungkan data dari dua tabel berdasarkan relasi kunci asing adalah...',
                'pilihan' => ['A' => 'JOIN', 'B' => 'UNION', 'C' => 'GROUP BY', 'D' => 'ORDER BY', 'E' => 'HAVING'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'JOIN menggabungkan baris dari dua tabel atau lebih.',
            ]
        ];
        $res7 = $createModuleRecord(
            $teacher3, $classTo1, $subjectInformatika,
            'Perancangan Basis Data Relasional & Normalisasi Tabel SQL',
            'Kegiatan Belajar 1: Desain ERD, Normalisasi 1NF-3NF, dan Query Relasi Tabel',
            'Basis data relasional menyusun data dalam relasi tabel matematis dengan foreign key untuk menjamin integritas referensial dan konsistensi transaksi.',
            $qPre7, $qPost7, true
        );

        $qPre8 = [
            [
                'id' => 1, 'pertanyaan' => 'Topologi jaringan yang menghubungkan semua komputer ke satu titik pusat (Switch/Hub) disebut topologi...',
                'pilihan' => ['A' => 'Star (Bintang)', 'B' => 'Bus', 'C' => 'Ring (Cincin)', 'D' => 'Mesh', 'E' => 'Tree'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Topologi Star menghubungkan setiap node ke switch sentral.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Teknologi virtualisasi jaringan lokal untuk mengisolasi traffic secara logis pada switch adalah...',
                'pilihan' => ['A' => 'VLAN (Virtual LAN)', 'B' => 'VPN', 'C' => 'NAT', 'D' => 'DNS', 'E' => 'DHCP'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'VLAN membagi switch fisik menjadi beberapa broadcast domain logis.',
            ]
        ];
        $qPost8 = [
            [
                'id' => 1, 'pertanyaan' => 'Protokol IEEE standar untuk VLAN Trunking yang menyisipkan tag VLAN ID pada header frame Ethernet adalah...',
                'pilihan' => ['A' => 'IEEE 802.1Q', 'B' => 'IEEE 802.11', 'C' => 'IEEE 802.3', 'D' => 'IEEE 802.15', 'E' => 'IEEE 802.1X'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'IEEE 802.1Q adalah standar industri untuk VLAN Tagging.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Jaringan yang mencakup area geografis sangat luas menghubungkan antar-kota atau negara adalah...',
                'pilihan' => ['A' => 'WAN (Wide Area Network)', 'B' => 'LAN', 'C' => 'PAN', 'D' => 'WLAN', 'E' => 'SAN'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'WAN mencakup area luas menggunakan sirkuit telekomunikasi.',
            ]
        ];
        $res8 = $createModuleRecord(
            $teacher3, $classTo1, $subjectJaringan,
            'Arsitektur Jaringan Komputer, Topologi LAN/WAN & Switch VLAN',
            'Kegiatan Belajar 1: Perancangan Topologi Star, Konfigurasi Switch VLAN, dan Trunking Port',
            'Arsitektur jaringan yang dirancang dengan baik memastikan skalabilitas, efisiensi lalu lintas data, dan isolasi keamanan menggunakan teknologi VLAN.',
            $qPre8, $qPost8, false
        );

        $qPre9 = [
            [
                'id' => 1, 'pertanyaan' => 'Perintah terminal untuk menguji konektivitas end-to-end ke host tujuan dengan protokol ICMP adalah...',
                'pilihan' => ['A' => 'ping', 'B' => 'tracert', 'C' => 'netstat', 'D' => 'nslookup', 'E' => 'ipconfig'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'ping mengirimkan paket ICMP Echo Request untuk menguji koneksi.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Perangkat lunak simulasi jaringan interaktif buatan Cisco yang populer untuk pembelajaran adalah...',
                'pilihan' => ['A' => 'Cisco Packet Tracer', 'B' => 'Wireshark', 'C' => 'PuTTY', 'D' => 'Notepad++', 'E' => 'VirtualBox'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Cisco Packet Tracer adalah simulator jaringan visual interaktif.',
            ]
        ];
        $qPost9 = [
            [
                'id' => 1, 'pertanyaan' => 'Perintah untuk melacak jalur lompatan (hop) router yang dilewati paket menuju host tujuan adalah...',
                'pilihan' => ['A' => 'traceroute / tracert', 'B' => 'ping', 'C' => 'arp -a', 'D' => 'route print', 'E' => 'hostname'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'traceroute/tracert melacak rute hop yang dilalui paket ke alamat tujuan.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Status balasan ping "Request Timed Out (RTO)" mengindikasikan bahwa...',
                'pilihan' => ['A' => 'Paket tidak menerima balasan dalam batas waktu tertentu', 'B' => 'Kabel terputus total', 'C' => 'IP address bertabrakan', 'D' => 'DNS gagal resolve', 'E' => 'Kartu jaringan rusak'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'RTO terjadi saat balasan ICMP Echo Reply tidak tiba sebelum timeout.',
            ]
        ];
        $res9 = $createModuleRecord(
            $teacher3, $classTo2, $subjectJaringan,
            'Simulasi Jaringan Interaktif & Troubleshooting Konektivitas TCP/IP',
            'Kegiatan Belajar 1: Simulasi Packet Tracer, Analisis Logika Ping, dan Uji Jalur Traceroute',
            'Troubleshooting jaringan memerlukan metode diagnosis bertahap (bottom-up / top-down) untuk mengidentifikasi letak kegagalan komunikasi data.',
            $qPre9, $qPost9, false
        );

        // ═════════════════════════════════════════════════════════════════
        // 10. CONTOH SUBMISSIONS & HASIL PENILAIAN SISWA
        // ═════════════════════════════════════════════════════════════════
        // Siswa 1 pada Modul 1 (Informatika - Budi Santoso)
        VideoSummary::create([
            'module_id'    => $res1['module']->id,
            'student_id'   => $studentModels[0]->id,
            'summary_text' => 'Video menjelaskan konsep struktur HTML5, styling CSS3, dan logika percabangan. Saya memahami cara membangun layout responsif dan logika program.',
            'manual_score' => 92,
        ]);

        EmbedSubmission::create([
            'module_id'       => $res1['module']->id,
            'student_id'      => $studentModels[0]->id,
            'screenshot_path' => 'embed_submissions/sample_terminal.png',
            'manual_score'    => 90,
        ]);

        JobSheetSubmission::create([
            'job_sheet_id'       => $res1['jobSheet']->id,
            'student_id'         => $studentModels[0]->id,
            'uploaded_file_path' => 'job_sheet_submissions/laporan_praktik_siswa1.pdf',
            'manual_score'       => 95,
        ]);

        Submission::create([
            'lkpd_id'            => $res1['lkpd']->id,
            'student_id'         => $studentModels[0]->id,
            'uploaded_file_path' => 'lkpd_submissions/jawaban_lkpd_siswa1.pdf',
            'manual_score'       => 94,
        ]);

        StudentResult::create([
            'student_id'      => $studentModels[0]->id,
            'module_id'       => $res1['module']->id,
            'pre_test_score'  => 100,
            'video_score'     => 92,
            'embed_score'     => 90,
            'job_sheet_score' => 95,
            'lkpd_score'      => 94,
            'post_test_score' => 100,
            'summative_score' => 95,
            'grading_status'  => 'graded',
        ]);

        // Siswa 2 pada Modul 1 (Pending)
        VideoSummary::create([
            'module_id'    => $res1['module']->id,
            'student_id'   => $studentModels[1]->id,
            'summary_text' => 'Rangkuman materi HTML5 dan CSS dasar untuk perancangan halaman antarmuka web modern.',
            'manual_score' => null,
        ]);

        JobSheetSubmission::create([
            'job_sheet_id'       => $res1['jobSheet']->id,
            'student_id'         => $studentModels[1]->id,
            'uploaded_file_path' => 'job_sheet_submissions/laporan_praktik_siswa2.pdf',
            'manual_score'       => null,
        ]);

        StudentResult::create([
            'student_id'      => $studentModels[1]->id,
            'module_id'       => $res1['module']->id,
            'pre_test_score'  => 100,
            'video_score'     => null,
            'embed_score'     => null,
            'job_sheet_score' => null,
            'lkpd_score'      => null,
            'post_test_score' => 100,
            'summative_score' => 100,
            'grading_status'  => 'pending',
        ]);

        // Siswa 3 pada Modul 4 (Jaringan - Siti Aminah)
        VideoSummary::create([
            'module_id'    => $res4['module']->id,
            'student_id'   => $studentModels[2]->id,
            'summary_text' => 'Video mendemonstrasikan perhitungan subnetting VLSM dan konfigurasi static routing pada router jaringan.',
            'manual_score' => 90,
        ]);

        StudentResult::create([
            'student_id'      => $studentModels[2]->id,
            'module_id'       => $res4['module']->id,
            'pre_test_score'  => 100,
            'video_score'     => 90,
            'embed_score'     => null,
            'job_sheet_score' => null,
            'lkpd_score'      => null,
            'post_test_score' => 100,
            'summative_score' => 96,
            'grading_status'  => 'graded',
        ]);

        // Siswa 6 pada Modul 7 (Informatika - Hendra Wijaya)
        VideoSummary::create([
            'module_id'    => $res7['module']->id,
            'student_id'   => $studentModels[5]->id,
            'summary_text' => 'Rangkuman mengenai konsep normalisasi basis data relasional 1NF-3NF dan implementasi query SELECT JOIN.',
            'manual_score' => 94,
        ]);

        StudentResult::create([
            'student_id'      => $studentModels[5]->id,
            'module_id'       => $res7['module']->id,
            'pre_test_score'  => 100,
            'video_score'     => 94,
            'embed_score'     => null,
            'job_sheet_score' => null,
            'lkpd_score'      => null,
            'post_test_score' => 100,
            'summative_score' => 98,
            'grading_status'  => 'graded',
        ]);
    }
}
