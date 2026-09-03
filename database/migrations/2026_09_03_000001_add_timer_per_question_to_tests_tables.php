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
            $table->integer('timer_per_question')->nullable()->default(null)->after('duration_minutes');
        });

        Schema::table('post_tests', function (Blueprint $table) {
            $table->integer('timer_per_question')->nullable()->default(null)->after('duration_minutes');
        });

        Schema::table('pre_test_questions', function (Blueprint $table) {
            $table->integer('time_limit_seconds')->nullable()->default(null)->after('score_weight');
        });

        Schema::table('post_test_questions', function (Blueprint $table) {
            $table->integer('time_limit_seconds')->nullable()->default(null)->after('score_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_test_questions', function (Blueprint $table) {
            $table->dropColumn('time_limit_seconds');
        });

        Schema::table('pre_test_questions', function (Blueprint $table) {
            $table->dropColumn('time_limit_seconds');
        });

        Schema::table('post_tests', function (Blueprint $table) {
            $table->dropColumn('timer_per_question');
        });

        Schema::table('pre_tests', function (Blueprint $table) {
            $table->dropColumn('timer_per_question');
        });
    }
};
