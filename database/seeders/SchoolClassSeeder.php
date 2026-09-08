<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majorTe = Major::where('code', 'TE')->first();
        $majorDp = Major::where('code', 'DP')->first();
        $majorTkj = Major::where('code', 'TKJ')->first();

        $classes = [
            [
                'major_id'   => $majorTe?->id,
                'grade'      => 'X',
                'section'    => '1',
                'major_name' => 'TE',
            ],
            [
                'major_id'   => $majorDp?->id,
                'grade'      => 'X',
                'section'    => '1',
                'major_name' => 'DP',
            ],
            [
                'major_id'   => $majorTkj?->id,
                'grade'      => 'X',
                'section'    => '1',
                'major_name' => 'TKJ',
            ],
        ];

        foreach ($classes as $classData) {
            SchoolClass::firstOrCreate(
                [
                    'major_id' => $classData['major_id'],
                    'grade'    => $classData['grade'],
                    'section'  => $classData['section'],
                ],
                [
                    'major_name' => $classData['major_name'],
                ]
            );
        }
    }
}
