<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // 1 Admin
        Admin::create([
            'name' => 'Admin Utama',
            'identity_number' => 'NIP123456',
            'password' => $password,
        ]);

        // 2 Kelas
        $class1 = SchoolClass::create(['major_name' => 'RPL', 'grade' => 'XI']);
        $class2 = SchoolClass::create(['major_name' => 'TKJ', 'grade' => 'XII']);

        // 2 Guru
        $teacher1 = Teacher::create([
            'name' => 'Budi Santoso',
            'identity_number' => 'NUPTK001',
            'password' => $password,
        ]);
        $teacher2 = Teacher::create([
            'name' => 'Siti Aminah',
            'identity_number' => 'NUPTK002',
            'password' => $password,
        ]);

        // 5 Siswa
        Student::insert([
            ['name' => 'Siswa Satu', 'identity_number' => 'NISN001', 'class_id' => $class1->id, 'password' => $password],
            ['name' => 'Siswa Dua', 'identity_number' => 'NISN002', 'class_id' => $class1->id, 'password' => $password],
            ['name' => 'Siswa Tiga', 'identity_number' => 'NISN003', 'class_id' => $class1->id, 'password' => $password],
            ['name' => 'Siswa Empat', 'identity_number' => 'NISN004', 'class_id' => $class2->id, 'password' => $password],
            ['name' => 'Siswa Lima', 'identity_number' => 'NISN005', 'class_id' => $class2->id, 'password' => $password],
        ]);

        // 2 Modul (Contoh)
        Module::create([
            'teacher_id' => $teacher1->id,
            'class_id' => $class1->id,
            'title' => 'Sistem Basis Data Lanjut',
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
                ]
            ],
            'bagian_akhir_data' => [
                'daftar_pustaka' => "1. Silberschatz, A. Database System Concepts.\n2. Date, C.J. An Introduction to Database Systems."
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
    }
}
