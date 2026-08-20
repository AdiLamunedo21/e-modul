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
        if (Schema::hasColumn('modules', 'bagian_awal_data')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->renameColumn('bagian_awal_data', 'informasi_umum_data');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('modules', 'informasi_umum_data')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->renameColumn('informasi_umum_data', 'bagian_awal_data');
            });
        }
    }
};
