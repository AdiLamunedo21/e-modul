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
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'semester')) {
                $table->enum('semester', ['1', '2'])->default('1')->after('subject_id');
                $table->index(['class_id', 'semester', 'status'], 'idx_modules_class_semester_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (Schema::hasColumn('modules', 'semester')) {
                $table->dropIndex('idx_modules_class_semester_status');
                $table->dropColumn('semester');
            }
        });
    }
};
