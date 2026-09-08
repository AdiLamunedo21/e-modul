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
                'color'       => 'blue',
                'description' => 'Mata pelajaran informatika, logika pemrograman, algoritma, dan sistem komputasi.',
            ],
            [
                'code'        => 'JAR',
                'name'        => 'Jaringan',
                'icon'        => '🌐',
                'color'       => 'indigo',
                'description' => 'Mata pelajaran infrastruktur jaringan komputer, konfigurasi subnetting, routing, dan komunikasi data.',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
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
