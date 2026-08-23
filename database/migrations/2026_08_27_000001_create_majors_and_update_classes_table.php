<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // Contoh: Pengembangan Perangkat Lunak dan GIM
            $table->string('code')->unique();   // Contoh: PPLG atau RPL
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->foreignId('major_id')->nullable()->after('id')->constrained('majors')->onDelete('cascade');
            $table->string('section')->nullable()->default('1')->after('grade'); // Nomor Rombel: 1, 2, 3, A, B
            $table->string('major_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->dropColumn(['major_id', 'section']);
        });

        Schema::dropIfExists('majors');
    }
};
