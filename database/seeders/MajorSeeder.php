<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            [
                'code'        => 'INF',
                'name'        => 'Informatika',
                'description' => 'Program keahlian informatika, logika algoritma, dan teknologi sistem komputer.',
            ],
            [
                'code'        => 'TO',
                'name'        => 'Teknik Otomotif',
                'description' => 'Program keahlian rekayasa teknologi dan perawatan kendaraan bermotor.',
            ],
            [
                'code'        => 'TL',
                'name'        => 'Teknik Ketenagalistrikan',
                'description' => 'Program keahlian instalasi tenaga listrik dan sistem kontrol ketenagalistrikan.',
            ],
        ];

        foreach ($majors as $major) {
            Major::updateOrCreate(
                ['code' => $major['code']],
                [
                    'name'        => $major['name'],
                    'description' => $major['description'],
                ]
            );
        }
    }
}
