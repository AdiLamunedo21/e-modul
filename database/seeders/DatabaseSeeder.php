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
            'bagian_awal_data' => json_encode(['cover' => 'cover1.jpg', 'tujuan' => 'Memahami konsep basis data relational']),
            'bagian_akhir_data' => json_encode(['pustaka' => 'Buku A', 'kunci_jawaban' => []]),
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
