<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
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

        $teachers = [
            [
                'name'             => 'Budi Santoso, S.Kom.',
                'identity_number'  => '198501152010011002',
                'old_identities'   => ['NIP123456', '198501152010011002'],
                'classes'          => array_filter([$classTe1?->id, $classDp1?->id]),
            ],
            [
                'name'             => 'Siti Aminah, M.T.',
                'identity_number'  => '198804122015022001',
                'old_identities'   => ['NIP123457', '198804122015022001'],
                'classes'          => array_filter([$classDp1?->id]),
            ],
            [
                'name'             => 'Hendra Wijaya, S.T.',
                'identity_number'  => '199008202019031003',
                'old_identities'   => ['NIP123458', '199008202019031003'],
                'classes'          => array_filter([$classTkj1?->id]),
            ],
        ];

        foreach ($teachers as $tData) {
            // Cari guru utama (prioritaskan yang sudah memiliki modul)
            $teacher = Teacher::where('name', $tData['name'])
                ->orWhereIn('identity_number', $tData['old_identities'])
                ->withCount('modules')
                ->orderByDesc('modules_count')
                ->orderBy('id')
                ->first();

            if ($teacher) {
                // Bersihkan duplikat guru jika ada
                $duplicates = Teacher::where('id', '!=', $teacher->id)
                    ->where(function ($q) use ($tData) {
                        $q->where('name', $tData['name'])
                          ->orWhereIn('identity_number', $tData['old_identities']);
                    })
                    ->get();

                foreach ($duplicates as $dup) {
                    $dup->modules()->update(['teacher_id' => $teacher->id]);
                    $dup->classes()->detach();
                    $dup->subjects()->detach();
                    $dup->delete();
                }

                $teacher->update([
                    'name'            => $tData['name'],
                    'identity_number' => $tData['identity_number'],
                    'password'        => $password,
                ]);
            } else {
                $teacher = Teacher::create([
                    'name'            => $tData['name'],
                    'identity_number' => $tData['identity_number'],
                    'password'        => $password,
                ]);
            }

            if (!empty($subjectIds)) {
                $teacher->subjects()->syncWithoutDetaching($subjectIds);
            }
            if (!empty($tData['classes'])) {
                $teacher->classes()->syncWithoutDetaching($tData['classes']);
            }
        }
    }
}
