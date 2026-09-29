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
        $majorTo = Major::where('code', 'TO')->first();
        $majorTl = Major::where('code', 'TL')->first();

        $classes = [
            [
                'major_id'   => $majorTo?->id,
                'grade'      => 'X',
                'section'    => '2',
                'major_name' => 'TO',
            ],
            [
                'major_id'   => $majorTl?->id,
                'grade'      => 'X',
                'section'    => '3',
                'major_name' => 'TL',
            ],
        ];

        foreach ($classes as $classData) {
            SchoolClass::updateOrCreate(
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
