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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('title');
            $table->text('informasi_umum_data')->nullable(); // JSON
            $table->text('bagian_akhir_data')->nullable(); // JSON
            $table->boolean('has_pre_test')->default(false);
            $table->boolean('has_materi')->default(false);
            $table->boolean('has_video')->default(false);
            $table->boolean('has_embed')->default(false);
            $table->boolean('has_job_sheet')->default(false);
            $table->boolean('has_lkpd')->default(false);
            $table->boolean('has_post_test')->default(false);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
        });

        Schema::create('job_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('pdf_file_path');
            $table->timestamps();
        });

        Schema::create('job_sheet_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_sheet_id')->constrained('job_sheets')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('uploaded_file_path');
            $table->integer('manual_score')->nullable();
            $table->timestamps();
        });

        Schema::create('lkpds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('pdf_file_path');
            $table->timestamps();
        });

        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkpd_id')->constrained('lkpds')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('uploaded_file_path');
            $table->integer('manual_score')->nullable();
            $table->timestamps();
        });

        Schema::create('embed_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('screenshot_path');
            $table->integer('manual_score')->nullable();
            $table->timestamps();
        });

        Schema::create('video_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->text('summary_text');
            $table->integer('manual_score')->nullable();
            $table->timestamps();
        });

        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->integer('pre_test_score')->nullable();
            $table->integer('video_score')->nullable();
            $table->integer('embed_score')->nullable();
            $table->integer('job_sheet_score')->nullable();
            $table->integer('lkpd_score')->nullable();
            $table->integer('post_test_score')->nullable();
            $table->integer('summative_score');
            $table->enum('grading_status', ['pending', 'graded'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_results');
        Schema::dropIfExists('video_summaries');
        Schema::dropIfExists('embed_submissions');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('lkpds');
        Schema::dropIfExists('job_sheet_submissions');
        Schema::dropIfExists('job_sheets');
        Schema::dropIfExists('modules');
    }
};
