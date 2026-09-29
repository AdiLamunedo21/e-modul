<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah kolom teks modul dari TEXT (maks 64 KB / ~1500-2000 kata)
     * menjadi LONGTEXT (maks 4 GB) agar guru bebas menulis uraian materi tak terbatas
     * dan mencegah error MySQL 'Data too long for column'.
     */
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->longText('informasi_umum_data')->nullable()->change();
            $table->longText('bagian_akhir_data')->nullable()->change();
            $table->longText('pre_test_data')->nullable()->change();
            $table->longText('materi_data')->nullable()->change();
            $table->longText('video_data')->nullable()->change();
            $table->longText('embed_data')->nullable()->change();
            $table->longText('job_sheet_data')->nullable()->change();
            $table->longText('lkpd_data')->nullable()->change();
            $table->longText('post_test_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->text('informasi_umum_data')->nullable()->change();
            $table->text('bagian_akhir_data')->nullable()->change();
            $table->text('pre_test_data')->nullable()->change();
            $table->text('materi_data')->nullable()->change();
            $table->text('video_data')->nullable()->change();
            $table->text('embed_data')->nullable()->change();
            $table->text('job_sheet_data')->nullable()->change();
            $table->text('lkpd_data')->nullable()->change();
            $table->text('post_test_data')->nullable()->change();
        });
    }
};
