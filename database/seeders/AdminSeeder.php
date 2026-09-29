<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name'            => 'Superadmin1',
                'identity_number' => '100000000000001',
                'email'           => 'superskagata@gamil.com',
                'password'        => Hash::make('superadmin123'),
            ],
            [
                'name'            => 'MARYONO, S.Pd, M.Pd',
                'identity_number' => '197205172006041012',
                'email'           => 'maryonoskagata@gmail.com',
                'password'        => Hash::make('mayono123'),
            ],
        ];

        foreach ($admins as $adminData) {
            Admin::updateOrCreate(
                ['identity_number' => $adminData['identity_number']],
                [
                    'name'     => $adminData['name'],
                    'email'    => $adminData['email'],
                    'password' => $adminData['password'],
                ]
            );
        }
    }
}
