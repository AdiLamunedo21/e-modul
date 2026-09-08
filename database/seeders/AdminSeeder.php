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
        $password = Hash::make('password');

        $admin = Admin::where('identity_number', '197501011999031001')
            ->orWhere('identity_number', 'NIP999001')
            ->orWhere('name', 'Drs. Ahmad Fauzi, M.Pd.')
            ->orderBy('id')
            ->first();

        if ($admin) {
            // Hapus duplikat lain jika ada
            Admin::where('id', '!=', $admin->id)
                ->where(function ($q) {
                    $q->where('identity_number', '197501011999031001')
                      ->orWhere('name', 'Drs. Ahmad Fauzi, M.Pd.');
                })
                ->delete();

            $admin->update([
                'name'            => 'Drs. Ahmad Fauzi, M.Pd.',
                'identity_number' => '197501011999031001',
                'password'        => $password,
            ]);
        } else {
            Admin::create([
                'name'            => 'Drs. Ahmad Fauzi, M.Pd.',
                'identity_number' => '197501011999031001',
                'password'        => $password,
            ]);
        }
    }
}
