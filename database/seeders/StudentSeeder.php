<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $majorTe = Major::where('code', 'TE')->first();
        $majorDp = Major::where('code', 'DP')->first();
        $majorTkj = Major::where('code', 'TKJ')->first();

        $classTe1 = SchoolClass::where('major_id', $majorTe?->id)->where('grade', 'X')->where('section', '1')->first();
        $classDp1 = SchoolClass::where('major_id', $majorDp?->id)->where('grade', 'X')->where('section', '1')->first();
        $classTkj1 = SchoolClass::where('major_id', $majorTkj?->id)->where('grade', 'X')->where('section', '1')->first();

        $subjectIds = Subject::whereIn('code', ['INF', 'JAR'])->pluck('id')->toArray();

        $studentsData = [
            ['name' => 'Ahmad Pratama',  'identity_number' => '0109049076', 'old_identities' => ['NISN001', '0081423001', '0109049076'], 'class_id' => $classTe1?->id],
            ['name' => 'Bunga Citra',    'identity_number' => '0109049077', 'old_identities' => ['NISN002', '0109049077'], 'class_id' => $classTe1?->id],
            ['name' => 'Candra Wijaya',  'identity_number' => '0109049078', 'old_identities' => ['NISN003', '0109049078'], 'class_id' => $classTe1?->id],
            ['name' => 'Dedi Kurniawan', 'identity_number' => '0109049079', 'old_identities' => ['NISN004', '0109049079'], 'class_id' => $classDp1?->id],
            ['name' => 'Eka Safitri',    'identity_number' => '0109049080', 'old_identities' => ['NISN005', '0109049080'], 'class_id' => $classDp1?->id],
            ['name' => 'Fajar Hidayat',  'identity_number' => '0109049081', 'old_identities' => ['NISN006', '0109049081'], 'class_id' => $classDp1?->id],
            ['name' => 'Gita Lestari',   'identity_number' => '0109049082', 'old_identities' => ['NISN007', '0109049082'], 'class_id' => $classTkj1?->id],
            ['name' => 'Hendra Pratama', 'identity_number' => '0109049083', 'old_identities' => ['NISN008', '0109049083'], 'class_id' => $classTkj1?->id],
            ['name' => 'Indah Permata',  'identity_number' => '0109049084', 'old_identities' => ['NISN009', '0109049084'], 'class_id' => $classTkj1?->id],
            ['name' => 'Joko Susilo',    'identity_number' => '0109049085', 'old_identities' => ['NISN010', '0109049085'], 'class_id' => $classTkj1?->id],
        ];

        foreach ($studentsData as $st) {
            // Cari siswa utama (prioritas yang memiliki riwayat studentResults terbanyak)
            $student = Student::where('name', $st['name'])
                ->orWhereIn('identity_number', $st['old_identities'])
                ->withCount('studentResults')
                ->orderByDesc('student_results_count')
                ->orderBy('id')
                ->first();

            if ($student) {
                // Bersihkan duplikat jika ada
                $duplicates = Student::where('id', '!=', $student->id)
                    ->where(function ($q) use ($st) {
                        $q->where('name', $st['name'])
                          ->orWhereIn('identity_number', $st['old_identities']);
                    })
                    ->get();

                foreach ($duplicates as $dup) {
                    $dup->classes()->detach();
                    $dup->subjects()->detach();
                    $dup->delete();
                }

                $student->update([
                    'name'            => $st['name'],
                    'identity_number' => $st['identity_number'],
                    'class_id'        => $st['class_id'],
                    'password'        => $password,
                ]);
            } else {
                $student = Student::create([
                    'name'            => $st['name'],
                    'identity_number' => $st['identity_number'],
                    'class_id'        => $st['class_id'],
                    'password'        => $password,
                ]);
            }

            if ($st['class_id']) {
                $student->classes()->syncWithoutDetaching([$st['class_id']]);
            }

            if (!empty($subjectIds)) {
                $student->subjects()->syncWithoutDetaching($subjectIds);
            }
        }
    }
}
