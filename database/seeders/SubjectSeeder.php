<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'code'        => 'INF',
                'name'        => 'Informatika',
                'icon'        => '💻',
                'color'       => 'indigo',
                'description' => 'Mata pelajaran Informatika: dasar komputasi, algoritma pemrograman, dan literasi digital.',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                [
                    'name'        => $subject['name'],
                    'icon'        => $subject['icon'],
                    'color'       => $subject['color'],
                    'description' => $subject['description'],
                ]
            );
        }
    }
}
