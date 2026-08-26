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
        // 2. 3 JURUSAN / KONSENTRASI KEAHLIAN
        // ═════════════════════════════════════════════════════════════════
        $majorPplg = Major::create([
            'name'        => 'Pengembangan Perangkat Lunak dan GIM',
            'code'        => 'PPLG',
            'description' => 'Konsentrasi keahlian pemrograman web, mobile apps, database engineering, dan rekayasa perangkat lunak.',
        ]);

        $majorTjkt = Major::create([
            'name'        => 'Teknik Jaringan Komputer dan Telekomunikasi',
            'code'        => 'TJKT',
            'description' => 'Konsentrasi keahlian infrastruktur jaringan komputer, sistem administrasi server Linux, dan keamanan siber.',
        ]);

        $majorTitl = Major::create([
            'name'        => 'Teknik Ketenagalistrikan',
            'code'        => 'TITL',
            'description' => 'Konsentrasi keahlian instalasi penerangan, panel daya industri, kontrol motor 3 fasa, dan PLC.',
        ]);

        // ═════════════════════════════════════════════════════════════════
        // 3. 3 KELAS / ROMBONGAN BELAJAR
        // ═════════════════════════════════════════════════════════════════
        $class1 = SchoolClass::create([
            'major_id'   => $majorPplg->id,
            'grade'      => 'X',
            'section'    => '1',
            'major_name' => 'PPLG',
        ]);

        $class2 = SchoolClass::create([
            'major_id'   => $majorTjkt->id,
            'grade'      => 'XI',
            'section'    => '1',
            'major_name' => 'TJKT',
        ]);

        $class3 = SchoolClass::create([
            'major_id'   => $majorTitl->id,
            'grade'      => 'XII',
            'section'    => '1',
            'major_name' => 'TITL',
        ]);

        // ═════════════════════════════════════════════════════════════════
        // 4. MATA PELAJARAN
        // ═════════════════════════════════════════════════════════════════
        $subjectPplg = Subject::create([
            'name'        => 'Rekayasa Perangkat Lunak',
            'code'        => 'RPL',
            'icon'        => '💻',
            'color'       => 'blue',
            'description' => 'Mata pelajaran rekayasa aplikasi web, pemrograman terstruktur, dan basis data.',
        ]);

        $subjectTjkt = Subject::create([
            'name'        => 'Teknik Jaringan Komputer',
            'code'        => 'TJK',
            'icon'        => '🌐',
            'color'       => 'indigo',
            'description' => 'Mata pelajaran infrastruktur jaringan, routing dinamis, dan administrasi server.',
        ]);

        $subjectTitl = Subject::create([
            'name'        => 'Instalasi Tenaga Listrik',
            'code'        => 'ITL',
            'icon'        => '⚡',
            'color'       => 'amber',
            'description' => 'Mata pelajaran instalasi tenaga listrik, motor industri, dan sistem otomasi PLC.',
        ]);

        $subjectMatematika = Subject::create([
            'name'        => 'Matematika Terapan',
            'code'        => 'MAT',
            'icon'        => '📐',
            'color'       => 'violet',
            'description' => 'Mata pelajaran kalkulasi logika dan aljabar terapan teknik.',
        ]);

        $subjectIndonesia = Subject::create([
            'name'        => 'Bahasa Indonesia Kejuruan',
            'code'        => 'IND',
            'icon'        => '📚',
            'color'       => 'emerald',
            'description' => 'Mata pelajaran komunikasi profesional, teks laporan teknik, dan literasi.',
        ]);

        // ═════════════════════════════════════════════════════════════════
        // 5. 3 GURU BERBEDA
        // ═════════════════════════════════════════════════════════════════
        $teacher1 = Teacher::create([
            'name'            => 'Budi Santoso, S.Kom.',
            'identity_number' => 'NIP123456',
            'password'        => $password,
        ]);
        $teacher1->subjects()->attach([$subjectPplg->id, $subjectMatematika->id]);

        $teacher2 = Teacher::create([
            'name'            => 'Siti Aminah, M.T.',
            'identity_number' => 'NIP123457',
            'password'        => $password,
        ]);
        $teacher2->subjects()->attach([$subjectTjkt->id, $subjectIndonesia->id]);

        $teacher3 = Teacher::create([
            'name'            => 'Hendra Wijaya, S.T.',
            'identity_number' => 'NIP123458',
            'password'        => $password,
        ]);
        $teacher3->subjects()->attach([$subjectTitl->id, $subjectMatematika->id]);

        // ═════════════════════════════════════════════════════════════════
        // 6. SISWA BINAAN
        // ═════════════════════════════════════════════════════════════════
        $student1 = Student::create(['name' => 'Ahmad Pratama', 'identity_number' => 'NISN001', 'class_id' => $class1->id, 'password' => $password]);
        $student1->subjects()->attach([$subjectPplg->id, $subjectMatematika->id, $subjectIndonesia->id]);

        $student2 = Student::create(['name' => 'Bunga Citra', 'identity_number' => 'NISN002', 'class_id' => $class1->id, 'password' => $password]);
        $student2->subjects()->attach([$subjectPplg->id, $subjectMatematika->id]);

        $student3 = Student::create(['name' => 'Candra Wijaya', 'identity_number' => 'NISN003', 'class_id' => $class1->id, 'password' => $password]);
        $student3->subjects()->attach([$subjectPplg->id, $subjectIndonesia->id]);

        $student4 = Student::create(['name' => 'Dedi Kurniawan', 'identity_number' => 'NISN004', 'class_id' => $class2->id, 'password' => $password]);
        $student4->subjects()->attach([$subjectTjkt->id, $subjectIndonesia->id]);

        $student5 = Student::create(['name' => 'Eka Safitri', 'identity_number' => 'NISN005', 'class_id' => $class2->id, 'password' => $password]);
        $student5->subjects()->attach([$subjectTjkt->id, $subjectIndonesia->id]);

        $student6 = Student::create(['name' => 'Fajar Hidayat', 'identity_number' => 'NISN006', 'class_id' => $class3->id, 'password' => $password]);
        $student6->subjects()->attach([$subjectTitl->id, $subjectMatematika->id]);

        $student7 = Student::create(['name' => 'Gita Lestari', 'identity_number' => 'NISN007', 'class_id' => $class3->id, 'password' => $password]);
        $student7->subjects()->attach([$subjectTitl->id, $subjectMatematika->id]);

        // Helper closure to build rich pedagogical data
        $createModuleRecord = function ($teacher, $class, $subject, $title, $materiJudul, $materiContent, $preTestQ, $postTestQ, $isShared = false) {
            $informasiUmum = [
                'kata_pengantar'      => "Puji syukur kami panjatkan kepada Tuhan Yang Maha Esa atas tersusunnya E-Modul {$title}.",
                'tujuan_pembelajaran' => "Peserta didik mampu memahami dan menguasai kompetensi dasar serta praktik langsung pada topik {$title}.",
                'peta_konsep'         => "Peta konsep modul mencakup fondasi teoritis, demonstrasi interaktif, simulasi praktik, dan evaluasi hasil belajar mandiri.",
                'petunjuk_penggunaan' => "1. Pelajari uraian materi dengan seksama.\n2. Tonton tayangan video pembelajaran dan buat rangkuman.\n3. Kerjakan tugas lembar kerja dan uji pemahaman Anda pada evaluasi akhir.",
                'glosarium'           => [
                    ['istilah' => 'Konsep Utama', 'definisi' => 'Prinsip fundamental yang mendasari materi pembelajaran ini.'],
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
        // 7. 3 MODUL GURU 1: Budi Santoso, S.Kom. (Jurusan PPLG, Kelas X PPLG 1)
        // ═════════════════════════════════════════════════════════════════
        $qPre1 = [
            [
                'id' => 1, 'pertanyaan' => 'Arsitektur MVC dalam pengembangan web memisahkan aplikasi menjadi Model, View, dan...',
                'pilihan' => ['A' => 'Controller', 'B' => 'Compiler', 'C' => 'Connector', 'D' => 'Container', 'E' => 'Converter'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'MVC singkatan dari Model-View-Controller.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Perintah Artisan Laravel untuk membuat migration dan model sekaligus adalah...',
                'pilihan' => ['A' => 'php artisan make:model -m', 'B' => 'php artisan db:seed', 'C' => 'php artisan serve', 'D' => 'php artisan route:list', 'E' => 'php artisan key:generate'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Opsi -m membuat file migrasi otomatis bersamaan dengan Model Eloquent.',
            ]
        ];
        $qPost1 = [
            [
                'id' => 1, 'pertanyaan' => 'Fitur routing dengan parameter dinamis pada Laravel dituliskan dengan sintaks...',
                'pilihan' => ['A' => 'Route::get("/user/{id}", ...)', 'B' => 'Route::get("/user/$id", ...)', 'C' => 'Route::get("/user/#id", ...)', 'D' => 'Route::get("/user/?id", ...)', 'E' => 'Route::get("/user/@id", ...)'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Parameter URL pada route Laravel dibungkus dengan tanda kurung kurawal {}.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Utility class Tailwind CSS untuk membuat tata letak grid dengan 3 kolom responsif adalah...',
                'pilihan' => ['A' => 'grid grid-cols-1 md:grid-cols-3', 'B' => 'flex flex-row-3', 'C' => 'display: table-3', 'D' => 'col-span-3', 'E' => 'auto-grid-3'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'grid grid-cols-1 md:grid-cols-3 mengatur grid 1 kolom di layar kecil dan 3 kolom di layar sedang ke atas.',
            ]
        ];
        $res1 = $createModuleRecord(
            $teacher1, $class1, $subjectPplg,
            'Pengembangan Aplikasi Web Modern dengan Framework Laravel & Tailwind CSS',
            'Kegiatan Belajar 1: Konsep MVC, Routing, Controller, dan Blade Templating Engine',
            'Laravel adalah framework PHP modern yang mengadopsi arsitektur MVC (Model-View-Controller). Dilengkapi dengan Blade Templating, Eloquent ORM, dan ekosistem tooling yang kuat.',
            $qPre1, $qPost1, true
        );

        $qPre2 = [
            [
                'id' => 1, 'pertanyaan' => 'Struktur data LIFO (Last In First Out) diterapkan pada...',
                'pilihan' => ['A' => 'Stack', 'B' => 'Queue', 'C' => 'Array', 'D' => 'Binary Tree', 'E' => 'Graph'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Stack menggunakan prinsip LIFO (elemen terakhir masuk akan keluar pertama).',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Prinsip OOP yang membungkus data dan method ke dalam satu unit terlindung disebut...',
                'pilihan' => ['A' => 'Encapsulation', 'B' => 'Inheritance', 'C' => 'Polymorphism', 'D' => 'Abstraction', 'E' => 'Overloading'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Enkapsulasi menjaga integritas data melalui access modifier (private/protected/public).',
            ]
        ];
        $qPost2 = [
            [
                'id' => 1, 'pertanyaan' => 'Kompleksitas waktu terbaik untuk algoritma Binary Search pada array terurut adalah...',
                'pilihan' => ['A' => 'O(1)', 'B' => 'O(log n)', 'C' => 'O(n)', 'D' => 'O(n log n)', 'E' => 'O(n^2)'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Kasus terbaik (best case) Binary Search adalah O(1) saat elemen dicari berada tepat di tengah array.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Pilar OOP yang memungkinkan sebuah method memiliki banyak bentuk implementasi adalah...',
                'pilihan' => ['A' => 'Polymorphism', 'B' => 'Inheritance', 'C' => 'Encapsulation', 'D' => 'Modularization', 'E' => 'Initialization'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Polimorfisme memungkinkan method dengan nama sama berperilaku berbeda sesuai objek turunannya.',
            ]
        ];
        $res2 = $createModuleRecord(
            $teacher1, $class1, $subjectPplg,
            'Struktur Data & Algoritma Pemrograman Berorientasi Objek',
            'Kegiatan Belajar 1: Implementasi Stack, Queue, dan Enkapsulasi Kelas OOP',
            'Pemrograman berorientasi objek memodelkan sistem dunia nyata ke dalam bentuk entitas kelas dan objek dengan relasi pewarisan serta abstraksi.',
            $qPre2, $qPost2, false
        );

        $qPre3 = [
            [
                'id' => 1, 'pertanyaan' => 'Perintah SQL DDL yang digunakan untuk membuat tabel baru di basis data adalah...',
                'pilihan' => ['A' => 'CREATE TABLE', 'B' => 'INSERT INTO', 'C' => 'ALTER DATABASE', 'D' => 'DROP TABLE', 'E' => 'UPDATE SET'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'CREATE TABLE termasuk DDL (Data Definition Language).',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Primary Key pada tabel basis data relasional berfungsi sebagai...',
                'pilihan' => ['A' => 'Identitas unik setiap baris rekaman data', 'B' => 'Format tampilan teks', 'C' => 'Penyimpan indeks sementara', 'D' => 'Enkripsi data rahasia', 'E' => 'Penghubung kabel fisik'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Primary key menjamin keunikan identitas rekaman data pada tabel.',
            ]
        ];
        $qPost3 = [
            [
                'id' => 1, 'pertanyaan' => 'Operasi SQL JOIN yang menampilkan semua baris dari tabel kiri dan baris yang cocok dari tabel kanan adalah...',
                'pilihan' => ['A' => 'LEFT JOIN', 'B' => 'RIGHT JOIN', 'C' => 'INNER JOIN', 'D' => 'CROSS JOIN', 'E' => 'FULL OUTER JOIN'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'LEFT JOIN mempertahankan semua data pada entitas kiri.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Bentuk normalisasi 3NF mensyaratkan tabel telah memenuhi 2NF dan tidak memiliki ketergantungan...',
                'pilihan' => ['A' => 'Transitif', 'B' => 'Fungsional penuh', 'C' => 'Parsial', 'D' => 'Multi-nilai', 'E' => 'Determinan'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => '3NF mengeliminasi ketergantungan transitif antar atribut non-kunci.',
            ]
        ];
        $res3 = $createModuleRecord(
            $teacher1, $class1, $subjectPplg,
            'Perancangan Basis Data Relasional & Optimasi Query SQL',
            'Kegiatan Belajar 1: Desain ERD, Normalisasi 1NF-3NF, dan Query Multi-Table',
            'Basis data relasional menyusun data dalam relasi tabel matematis dengan foreign key untuk menjamin integritas referensial dan konsistensi transaksi ACID.',
            $qPre3, $qPost3, false
        );

        // ═════════════════════════════════════════════════════════════════
        // 8. 3 MODUL GURU 2: Siti Aminah, M.T. (Jurusan TJKT, Kelas XI TJKT 1)
        // ═════════════════════════════════════════════════════════════════
        $qPre4 = [
            [
                'id' => 1, 'pertanyaan' => 'Protokol routing dinamis Link-State yang menggunakan algoritma Dijkstra adalah...',
                'pilihan' => ['A' => 'OSPF', 'B' => 'RIPv2', 'C' => 'EIGRP', 'D' => 'BGP', 'E' => 'STATIC'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'OSPF (Open Shortest Path First) menggunakan Shortest Path First (Dijkstra).',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Berapa subnet mask default untuk alamat IPv4 kelas C /24?',
                'pilihan' => ['A' => '255.255.255.0', 'B' => '255.255.0.0', 'C' => '255.0.0.0', 'D' => '255.255.255.128', 'E' => '255.255.255.252'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Prefix /24 memiliki 24 bit bernilai 1, yaitu 255.255.255.0.',
            ]
        ];
        $qPost4 = [
            [
                'id' => 1, 'pertanyaan' => 'Administrative Distance (AD) default untuk rute OSPF pada router Cisco/MikroTik adalah...',
                'pilihan' => ['A' => '110', 'B' => '90', 'C' => '120', 'D' => '1', 'E' => '200'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'AD default untuk OSPF adalah 110.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Protokol Exterior Gateway Protocol (EGP) standar yang menghubungkan antar Autonomous System (AS) di internet adalah...',
                'pilihan' => ['A' => 'BGP', 'B' => 'OSPF', 'C' => 'IS-IS', 'D' => 'RIP', 'E' => 'ICMP'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'BGP (Border Gateway Protocol) adalah tulang punggung routing global antar-AS.',
            ]
        ];
        $res4 = $createModuleRecord(
            $teacher2, $class2, $subjectTjkt,
            'Arsitektur Jaringan Komputer & Routing Dinamis OSPF/BGP',
            'Kegiatan Belajar 1: Konfigurasi Router MikroTik & Routing Inter-VLAN',
            'Jaringan skala enterprise membutuhkan protokol routing dinamis adaptif seperti OSPF dan BGP untuk memastikan ketersediaan jalur komunikasi tinggi (High Availability).',
            $qPre4, $qPost4, true
        );

        $qPre5 = [
            [
                'id' => 1, 'pertanyaan' => 'Perintah Linux CLI untuk memeriksa kapasitas penggunaan ruang disk partisi penyimpanan adalah...',
                'pilihan' => ['A' => 'df -h', 'B' => 'free -m', 'C' => 'top', 'D' => 'uname -r', 'E' => 'lsblk'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'df -h (disk free human-readable) menampilkan penggunaan ruang disk partisi.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Web server open-source populer berbasis event-driven asynchronous adalah...',
                'pilihan' => ['A' => 'NGINX', 'B' => 'MS IIS', 'C' => 'PostgreSQL', 'D' => 'Samba', 'E' => 'OpenSSH'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'NGINX dirancang untuk performa tinggi dan konkurensi koneksi besar.',
            ]
        ];
        $qPost5 = [
            [
                'id' => 1, 'pertanyaan' => 'Teknologi containerization yang memungkinkan isolasi aplikasi secara ringan tanpa overhead VM adalah...',
                'pilihan' => ['A' => 'Docker', 'B' => 'VirtualBox', 'C' => 'VMware ESXi', 'D' => 'QEMU', 'E' => 'Hyper-V'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Docker menggunakan isolasi kernel namespaces dan cgroups.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Port default yang digunakan protokol SSH terenkripsi untuk remote server Linux adalah...',
                'pilihan' => ['A' => '22', 'B' => '80', 'C' => '443', 'D' => '21', 'E' => '3306'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Port 22 adalah port standar SSH daemon.',
            ]
        ];
        $res5 = $createModuleRecord(
            $teacher2, $class2, $subjectTjkt,
            'Administrasi Server Linux & Layanan Cloud Computing',
            'Kegiatan Belajar 1: Manajemen User, Izin Berkas chmod, SSH Hardening, dan NGINX Web Server',
            'Sistem operasi Linux merupakan pondasi server global. Pemahaman konfigurasi layanan daemon, keamanan SSH, dan containerization sangat krusial bagi administrator sistem.',
            $qPre5, $qPost5, false
        );

        $qPre6 = [
            [
                'id' => 1, 'pertanyaan' => 'Serangan siber yang membanjiri lalu lintas server dengan paket palsu hingga lumpuh disebut...',
                'pilihan' => ['A' => 'DDoS Attack', 'B' => 'SQL Injection', 'C' => 'Cross-Site Scripting (XSS)', 'D' => 'Phishing', 'E' => 'Man-in-the-Middle'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'DDoS (Distributed Denial of Service) menghabiskan bandwidth dan sumber daya server.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Protokol keamanan jaringan nirkabel (Wi-Fi) paling mutakhir dan aman saat ini adalah...',
                'pilihan' => ['A' => 'WPA3', 'B' => 'WEP', 'C' => 'WPA', 'D' => 'WPA2-TKIP', 'E' => 'Open System'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'WPA3 menggunakan protokol SAE (Simultaneous Authentication of Equals) tahan serangan kamus.',
            ]
        ];
        $qPost6 = [
            [
                'id' => 1, 'pertanyaan' => 'Alat scanner keamanan jaringan open-source untuk audit port dan pemetaan host adalah...',
                'pilihan' => ['A' => 'Nmap', 'B' => 'Putty', 'C' => 'WinSCP', 'D' => 'FileZilla', 'E' => 'VLC Media Player'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Nmap adalah utilitas standar industri untuk penemuan jaringan dan pemindaian port kerentanan.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Firewall Linux bawaan kernel modern yang menggantikan iptables adalah...',
                'pilihan' => ['A' => 'nftables', 'B' => 'ipfw', 'C' => 'pfSense', 'D' => 'Snort', 'E' => 'Suricata'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'nftables adalah subsistem pemfilteran paket generasi baru di Linux kernel.',
            ]
        ];
        $res6 = $createModuleRecord(
            $teacher2, $class2, $subjectTjkt,
            'Keamanan Siber, Manajemen Firewall & Pengujian Penetrasi',
            'Kegiatan Belajar 1: Konfigurasi Firewall Statefull & Audit Keamanan Port Jaringan',
            'Keamanan siber berfokus pada prinsip CIA Triad (Confidentiality, Integrity, Availability) melalui implementasi sistem pertahanan berlapis (Defense-in-Depth).',
            $qPre6, $qPost6, false
        );

        // ═════════════════════════════════════════════════════════════════
        // 9. 3 MODUL GURU 3: Hendra Wijaya, S.T. (Jurusan TITL, Kelas XII TITL 1)
        // ═════════════════════════════════════════════════════════════════
        $qPre7 = [
            [
                'id' => 1, 'pertanyaan' => 'Komponen proteksi listrik yang memutus sirkuit saat terjadi hubung singkat (short circuit) dan beban lebih adalah...',
                'pilihan' => ['A' => 'MCB (Miniature Circuit Breaker)', 'B' => 'Relay', 'C' => 'Voltmeter', 'D' => 'Kapasitor', 'E' => 'Transformator Step-Up'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'MCB bekerja secara termal (bimetal) dan elektromagnetik untuk pengamanan sirkuit.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Warna kabel standar PUIL untuk fasa R, S, T, dan Netral secara berurutan adalah...',
                'pilihan' => ['A' => 'Hitam, Cokelat, Abu-abu, dan Biru', 'B' => 'Merah, Kuning, Hitam, dan Biru', 'C' => 'Kuning, Hijau, Biru, dan Hitam', 'D' => 'Cokelat, Merah, Biru, dan Kuning', 'E' => 'Hitam, Putih, Merah, dan Hijau'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Standar PUIL 2011/2016 mengadopsi IEC: Fasa R=Hitam, S=Cokelat, T=Abu-abu, Netral=Biru.',
            ]
        ];
        $qPost7 = [
            [
                'id' => 1, 'pertanyaan' => 'Alat pengaman kelistrikan yang mendeteksi arus bocor ke tanah untuk mencegah bahaya tersengat listrik pada manusia adalah...',
                'pilihan' => ['A' => 'ELCB / RCCB', 'B' => 'MCB 1 Fasa', 'C' => 'Fuse Tabung', 'D' => 'Surge Arrester', 'E' => 'Thermal Overload Relay (TOR)'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'ELCB/RCCB (Earth Leakage Circuit Breaker) mendeteksi ketidakseimbangan arus akibat kebocoran ke tanah.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Nilai tahanan pembumian (grounding) maksimum yang diizinkan sesuai standar PUIL adalah...',
                'pilihan' => ['A' => '5 Ohm', 'B' => '50 Ohm', 'C' => '100 Ohm', 'D' => '220 Ohm', 'E' => '1000 Ohm'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Standar instalasi listrik PUIL menetapkan tahanan pembumian grounding maksimal <= 5 Ohm.',
            ]
        ];
        $res7 = $createModuleRecord(
            $teacher3, $class3, $subjectTitl,
            'Instalasi Tenaga Listrik Industri & Distribusi Panel Daya',
            'Kegiatan Belajar 1: Desain Single Line Diagram Panel Distribusi & Perhitungan KHA Kabel',
            'Instalasi tenaga listrik industri melibatkan distribusi daya 3 fasa, penataan panel distribusi utama (MDP), kompensasi daya reaktif (Kapasitor Bank), dan sistem grounding terstandar.',
            $qPre7, $qPost7, true
        );

        $qPre8 = [
            [
                'id' => 1, 'pertanyaan' => 'Bahasa pemrograman standar PLC yang paling banyak digunakan berbentuk diagram logika kontak relai adalah...',
                'pilihan' => ['A' => 'Ladder Diagram (LD)', 'B' => 'Structured Text (ST)', 'C' => 'Function Block Diagram (FBD)', 'D' => 'Instruction List (IL)', 'E' => 'Sequential Function Chart (SFC)'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Ladder Diagram (LD) memodelkan sirkuit kontrol elektromekanik dengan kontak NO/NC dan coil.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Instruksi timer pada PLC yang menunda pengaktifan output selama waktu preset disebut...',
                'pilihan' => ['A' => 'TON (Timer On-Delay)', 'B' => 'TOF (Timer Off-Delay)', 'C' => 'TP (Pulse Timer)', 'D' => 'CTU (Up Counter)', 'E' => 'CTD (Down Counter)'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'TON menghitung waktu tunda sebelum output aktif saat input sinyal berlogika TRUE.',
            ]
        ];
        $qPost8 = [
            [
                'id' => 1, 'pertanyaan' => 'Siklus operasi CPU PLC yang berulang terus menerus terdiri dari...',
                'pilihan' => ['A' => 'Input Scan -> Program Execution -> Output Scan -> Housekeeping', 'B' => 'Compilation -> Linking -> Executing -> Termination', 'C' => 'Fetch -> Decode -> Execute -> Writeback', 'D' => 'Booting -> Formatting -> Uploading -> Resetting', 'E' => 'Start -> Interrupt -> Halt -> Shutdown'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'PLC bekerja dengan membaca status input, memproses logika instruksi, dan memperbarui status output sirkuit.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Rangkaian pengunci logika (Latching Circuit) pada Ladder Diagram digunakan untuk...',
                'pilihan' => ['A' => 'Mempertahankan output tetap aktif meskipun tombol tekan start (NO) telah dilepas', 'B' => 'Mematikan sumber listrik darurat', 'C' => 'Menurunkan frekuensi inverter', 'D' => 'Membalik arah putaran motor secara mendadak', 'E' => 'Menghitung jumlah produk conveyor'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Latching menghubungkan kontak output secara paralel dengan tombol start untuk menjaga status aktif.',
            ]
        ];
        $res8 = $createModuleRecord(
            $teacher3, $class3, $subjectTitl,
            'Otomasi Industri berbasis Programmable Logic Controller (PLC)',
            'Kegiatan Belajar 1: Pemrograman Ladder Diagram, Instruksi Timer, dan Interlocking Sirkuit',
            'PLC (Programmable Logic Controller) adalah komputer industri terprogram untuk mengontrol aktuator, konveyor, dan mesin otomatis dengan keandalan tinggi di lingkungan pabrik.',
            $qPre8, $qPost8, false
        );

        $qPre9 = [
            [
                'id' => 1, 'pertanyaan' => 'Metode starting motor induksi 3 fasa untuk mereduksi lonjakan arus awal (inrush current) adalah...',
                'pilihan' => ['A' => 'Star-Delta (Bintang-Segitiga)', 'B' => 'Direct On Line (DOL)', 'C' => 'Short Circuit', 'D' => 'Open Phase', 'E' => 'Over Voltage'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Star-Delta menurunkan tegangan awal motor menjadi 1/√3 sehingga arus start turun hingga 1/3 dari DOL.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Komponen elektromekanik yang menghubungkan/memutus beban listrik daya besar menggunakan koil elektromagnet adalah...',
                'pilihan' => ['A' => 'Magnetic Contactor', 'B' => 'Push Button', 'C' => 'Potensiometer', 'D' => 'Resistor Shunt', 'E' => 'Dioda Zener'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Kontaktor magnet bekerja berdasarkan medan magnet koil untuk menarik kontak utama daya.',
            ]
        ];
        $qPost9 = [
            [
                'id' => 1, 'pertanyaan' => 'Untuk membalik arah putaran motor induksi 3 fasa (Forward-Reverse), langkah yang harus dilakukan adalah...',
                'pilihan' => ['A' => 'Menukar posisi sambungan dua dari tiga fasa suplai (misal R dan S)', 'B' => 'Membalik kabel netral dan grounding', 'C' => 'Menaikkan frekuensi suplai jala-jala', 'D' => 'Menambahkan resistor pada kabel netral', 'E' => 'Memasang kapasitor secara paralel'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'Menukar dua fasa suplai akan membalik arah medan putar magnetik stator.',
            ],
            [
                'id' => 2, 'pertanyaan' => 'Perangkat elektronika daya yang digunakan untuk mengatur kecepatan putaran motor AC secara presisi dengan modulasi frekuensi adalah...',
                'pilihan' => ['A' => 'VFD (Variable Frequency Drive / Inverter)', 'B' => 'Transformator Step-Down', 'C' => 'Rectifier Setengah Gelombang', 'D' => 'Kondensator Mika', 'E' => 'Sakelar Togel'],
                'kunci_jawaban' => 'A', 'bobot' => 50, 'pembahasan' => 'VFD mengontrol kecepatan motor AC dengan mengatur perbandingan tegangan dan frekuensi (V/f).',
            ]
        ];
        $res9 = $createModuleRecord(
            $teacher3, $class3, $subjectTitl,
            'Sistem Kontrol Motor Listrik 3 Fasa & Elektronika Daya',
            'Kegiatan Belajar 1: Rangkaian Kontrol DOL, Star-Delta Otomatis, dan Forward-Reverse Interlock',
            'Pengendalian motor listrik 3 fasa memadukan komponen elektromekanik (Kontaktor, TOR, Timer) dan elektronika daya (VFD/Soft Starter) untuk efisiensi sistem penggerak industri.',
            $qPre9, $qPost9, false
        );

        // ═════════════════════════════════════════════════════════════════
        // 10. CONTOH SUBMISSIONS & HASIL PENILAIAN SISWA
        // ═════════════════════════════════════════════════════════════════
        // Siswa 1 pada Modul 1 (PPLG)
        VideoSummary::create([
            'module_id'    => $res1['module']->id,
            'student_id'   => $student1->id,
            'summary_text' => 'Video menjelaskan konsep routing Laravel, controller resource, dan integrasi Tailwind CSS. Saya memahami alur request HTTP dari route menuju controller hingga di-render oleh Blade view.',
            'manual_score' => 92,
        ]);

        EmbedSubmission::create([
            'module_id'       => $res1['module']->id,
            'student_id'      => $student1->id,
            'screenshot_path' => 'embed_submissions/sample_terminal.png',
            'manual_score'    => 90,
        ]);

        JobSheetSubmission::create([
            'job_sheet_id'       => $res1['jobSheet']->id,
            'student_id'         => $student1->id,
            'uploaded_file_path' => 'job_sheet_submissions/laporan_laravel_siswa1.pdf',
            'manual_score'       => 95,
        ]);

        Submission::create([
            'lkpd_id'            => $res1['lkpd']->id,
            'student_id'         => $student1->id,
            'uploaded_file_path' => 'lkpd_submissions/jawaban_lkpd_siswa1.pdf',
            'manual_score'       => 94,
        ]);

        StudentResult::create([
            'student_id'      => $student1->id,
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
            'student_id'   => $student2->id,
            'summary_text' => 'Rangkuman materi arsitektur MVC Laravel dan cara membuat view komponen menggunakan Blade templating.',
            'manual_score' => null,
        ]);

        JobSheetSubmission::create([
            'job_sheet_id'       => $res1['jobSheet']->id,
            'student_id'         => $student2->id,
            'uploaded_file_path' => 'job_sheet_submissions/laporan_laravel_siswa2.pdf',
            'manual_score'       => null,
        ]);

        StudentResult::create([
            'student_id'      => $student2->id,
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

        // Siswa 4 pada Modul 4 (TJKT)
        VideoSummary::create([
            'module_id'    => $res4['module']->id,
            'student_id'   => $student4->id,
            'summary_text' => 'Video ini mendemonstrasikan konfigurasi OSPF multi-area pada MikroTik RouterOS serta verifikasi routing table.',
            'manual_score' => 88,
        ]);

        StudentResult::create([
            'student_id'      => $student4->id,
            'module_id'       => $res4['module']->id,
            'pre_test_score'  => 100,
            'video_score'     => 88,
            'embed_score'     => null,
            'job_sheet_score' => null,
            'lkpd_score'      => null,
            'post_test_score' => 100,
            'summative_score' => 96,
            'grading_status'  => 'graded',
        ]);

        // Siswa 6 pada Modul 7 (TITL)
        VideoSummary::create([
            'module_id'    => $res7['module']->id,
            'student_id'   => $student6->id,
            'summary_text' => 'Video memberikan panduan praktis instalasi panel distribusi daya 3 fasa, penataan kabel, dan pemasangan ELCB pengaman kebocoran tanah.',
            'manual_score' => 90,
        ]);

        StudentResult::create([
            'student_id'      => $student6->id,
            'module_id'       => $res7['module']->id,
            'pre_test_score'  => 100,
            'video_score'     => 90,
            'embed_score'     => null,
            'job_sheet_score' => null,
            'lkpd_score'      => null,
            'post_test_score' => 100,
            'summative_score' => 97,
            'grading_status'  => 'graded',
        ]);
    }
}
