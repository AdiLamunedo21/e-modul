<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom code pada tabel classes
        Schema::table('classes', function (Blueprint $table) {
            $table->string('code', 20)->nullable()->unique()->after('id');
        });

        // 2. Backfill kode unik untuk rombel kelas yang sudah ada
        $existingClasses = DB::table('classes')->get();
        foreach ($existingClasses as $class) {
            do {
                $code = strtoupper(Str::random(6));
            } while (DB::table('classes')->where('code', $code)->exists());

            DB::table('classes')->where('id', $class->id)->update(['code' => $code]);
        }

        // 3. Ubah class_id pada tabel students menjadi nullable agar siswa dapat mendaftar mandiri
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable(false)->change();
        });
    }
};
