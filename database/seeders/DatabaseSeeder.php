<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            MajorSeeder::class,
            SchoolClassSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            ModuleSeeder::class,
        ]);
    }
}
