<?php

use Database\Seeders\StudentSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Jalankan seeder penambahan data siswa X TL 3 dan X TO 2 secara otomatis saat proses deployment VPS
        if (!class_exists(StudentSeeder::class)) {
            require_once database_path('seeders/StudentSeeder.php');
        }

        $seeder = new StudentSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data siswa dan nilai dipertahankan untuk menjaga integritas basis data
    }
};
