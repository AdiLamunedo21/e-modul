<?php

namespace Database\Seeders;

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
        $subjectInf = Subject::where('code', 'INF')->first();
        $classTo2 = SchoolClass::where('major_name', 'TO')->where('grade', 'X')->where('section', '2')->first();
        $classTl3 = SchoolClass::where('major_name', 'TL')->where('grade', 'X')->where('section', '3')->first();

        $allClassIds = array_values(array_filter([$classTo2?->id, $classTl3?->id]));

        $teachers = [
            [
                'name'            => 'Jumari, S.Pd., M.Eng.',
                'identity_number' => '198311152024211006',
                'email'           => 'jumariskagata@gmail.com',
                'password'        => Hash::make('jumari123'),
                'classes'         => $allClassIds,
            ],
            [
                'name'            => 'Febriyana, S.T',
                'identity_number' => '198402032024212007',
                'email'           => 'febriyanaskagata@gmail.com',
                'password'        => Hash::make('febriyana123'),
                'classes'         => [],
            ],
            [
                'name'            => 'Siti Nurhidayatun, S.Kom',
                'identity_number' => '1000000000003333',
                'email'           => 'sitinurskagata@gmail.com',
                'password'        => Hash::make('sitinur123'),
                'classes'         => [],
            ],
            [
                'name'            => 'Adi Chandra W PPG',
                'identity_number' => 'Nim25105260007',
                'email'           => 'adikun879@gmail.com',
                'password'        => Hash::make('Meliodas4693'),
                'classes'         => $allClassIds,
            ],
        ];

        foreach ($teachers as $tData) {
            $teacher = Teacher::updateOrCreate(
                ['identity_number' => $tData['identity_number']],
                [
                    'name'     => $tData['name'],
                    'email'    => $tData['email'],
                    'password' => $tData['password'],
                ]
            );

            if ($subjectInf) {
                $teacher->subjects()->sync([$subjectInf->id]);
            }

            if (!empty($tData['classes'])) {
                $teacher->classes()->sync($tData['classes']);
            } else {
                $teacher->classes()->detach();
            }
        }
    }
}
