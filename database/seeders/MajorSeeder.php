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
                'code'        => 'TE',
                'name'        => 'Teknik Elektro',
                'description' => 'Program keahlian sistem elektronika, instrumentasi, logika digital, dan sistem kontrol.',
            ],
            [
                'code'        => 'DP',
                'name'        => 'Desain Permodelan & Informasi Bangunan',
                'description' => 'Program keahlian gambar teknik digital, pemodelan 3D, dan Building Information Modeling (BIM).',
            ],
            [
                'code'        => 'TKJ',
                'name'        => 'Teknik Komputer & Jaringan',
                'description' => 'Program keahlian infrastruktur jaringan komputer, sistem komputasi, dan rekayasa perangkat lunak.',
            ],
        ];

        foreach ($majors as $major) {
            Major::firstOrCreate(
                ['code' => $major['code']],
                [
                    'name'        => $major['name'],
                    'description' => $major['description'],
                ]
            );
        }
    }
}
