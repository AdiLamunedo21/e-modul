<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat tabel pivot class_student untuk mendukung banyak kelas per siswa
        if (!Schema::hasTable('class_student')) {
            Schema::create('class_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['student_id', 'class_id']);
            });
        }

        // 2. Backfill data dari kolom class_id pada tabel students ke tabel pivot class_student
        $existingStudents = DB::table('students')->whereNotNull('class_id')->get();
        foreach ($existingStudents as $st) {
            DB::table('class_student')->updateOrInsert(
                ['student_id' => $st->id, 'class_id' => $st->class_id],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
