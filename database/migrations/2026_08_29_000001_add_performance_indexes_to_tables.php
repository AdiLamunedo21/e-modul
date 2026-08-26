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
            $table->index(['teacher_id', 'status'], 'idx_modules_teacher_status');
            $table->index(['class_id', 'status'], 'idx_modules_class_status');
            $table->index('status', 'idx_modules_status');
            $table->index('is_shared', 'idx_modules_is_shared');
        });

        Schema::table('student_results', function (Blueprint $table) {
            $table->index(['student_id', 'module_id'], 'idx_results_student_module');
            $table->index(['module_id', 'grading_status'], 'idx_results_module_status');
            $table->index('grading_status', 'idx_results_grading_status');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->index('student_id', 'idx_submissions_student');
            $table->index('manual_score', 'idx_submissions_manual_score');
        });

        Schema::table('job_sheet_submissions', function (Blueprint $table) {
            $table->index('student_id', 'idx_js_submissions_student');
            $table->index('manual_score', 'idx_js_submissions_manual_score');
        });

        Schema::table('embed_submissions', function (Blueprint $table) {
            $table->index('student_id', 'idx_embed_sub_student');
            $table->index('manual_score', 'idx_embed_sub_manual_score');
        });

        Schema::table('video_summaries', function (Blueprint $table) {
            $table->index('student_id', 'idx_video_sum_student');
            $table->index('manual_score', 'idx_video_sum_manual_score');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->index(['grade', 'major_id'], 'idx_classes_grade_major');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropIndex('idx_modules_teacher_status');
            $table->dropIndex('idx_modules_class_status');
            $table->dropIndex('idx_modules_status');
            $table->dropIndex('idx_modules_is_shared');
        });

        Schema::table('student_results', function (Blueprint $table) {
            $table->dropIndex('idx_results_student_module');
            $table->dropIndex('idx_results_module_status');
            $table->dropIndex('idx_results_grading_status');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex('idx_submissions_student');
            $table->dropIndex('idx_submissions_manual_score');
        });

        Schema::table('job_sheet_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_js_submissions_student');
            $table->dropIndex('idx_js_submissions_manual_score');
        });

        Schema::table('embed_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_embed_sub_student');
            $table->dropIndex('idx_embed_sub_manual_score');
        });

        Schema::table('video_summaries', function (Blueprint $table) {
            $table->dropIndex('idx_video_sum_student');
            $table->dropIndex('idx_video_sum_manual_score');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex('idx_classes_grade_major');
        });
    }
};
