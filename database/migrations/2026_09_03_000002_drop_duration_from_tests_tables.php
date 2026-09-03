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
        Schema::table('pre_tests', function (Blueprint $table) {
            if (Schema::hasColumn('pre_tests', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }
            if (Schema::hasColumn('pre_tests', 'timer_per_question')) {
                $table->dropColumn('timer_per_question');
            }
        });

        Schema::table('post_tests', function (Blueprint $table) {
            if (Schema::hasColumn('post_tests', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }
            if (Schema::hasColumn('post_tests', 'timer_per_question')) {
                $table->dropColumn('timer_per_question');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_tests', function (Blueprint $table) {
            $table->integer('duration_minutes')->default(15)->after('title');
            $table->integer('timer_per_question')->nullable()->default(null)->after('duration_minutes');
        });

        Schema::table('post_tests', function (Blueprint $table) {
            $table->integer('duration_minutes')->default(20)->after('title');
            $table->integer('timer_per_question')->nullable()->default(null)->after('duration_minutes');
        });
    }
};
