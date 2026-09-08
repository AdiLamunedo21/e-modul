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
        // ── 1. Sesi Kuis Live (Host Guru) ──────────────────────────────────
        Schema::create('live_quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
            $table->string('test_type', 20)->default('pre_test'); // 'pre_test' | 'post_test'
            $table->string('title');
            $table->string('pin_code', 6)->index();
            $table->enum('status', ['lobby', 'question', 'reveal', 'leaderboard', 'finished'])->default('lobby')->index();
            $table->integer('current_question_index')->default(0);
            $table->unsignedBigInteger('current_question_id')->nullable();
            $table->timestamp('question_started_at')->nullable();
            $table->integer('time_limit_seconds')->default(30);
            $table->integer('total_questions')->default(0);
            $table->boolean('grades_saved')->default(false);
            $table->timestamps();

            $table->index(['teacher_id', 'status']);
        });

        // ── 2. Peserta Siswa Kuis Live ─────────────────────────────────────
        Schema::create('live_quiz_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_quiz_session_id')->constrained('live_quiz_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('nickname');
            $table->integer('total_score')->default(0)->index();
            $table->integer('correct_answers_count')->default(0);
            $table->integer('last_score_earned')->default(0);
            $table->timestamp('last_answered_at')->nullable();
            $table->timestamps();

            $table->unique(['live_quiz_session_id', 'student_id'], 'uq_session_student');
        });

        // ── 3. Rekaman Jawaban Siswa per Butir Soal ─────────────────────────
        Schema::create('live_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_quiz_session_id')->constrained('live_quiz_sessions')->onDelete('cascade');
            $table->foreignId('live_quiz_participant_id')->constrained('live_quiz_participants')->onDelete('cascade');
            $table->unsignedBigInteger('question_id');
            $table->integer('question_index')->default(0);
            $table->string('selected_option', 10);
            $table->boolean('is_correct')->default(false);
            $table->integer('response_time_ms')->default(0);
            $table->integer('score_earned')->default(0);
            $table->timestamps();

            $table->unique(['live_quiz_session_id', 'live_quiz_participant_id', 'question_id'], 'uq_session_part_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_quiz_answers');
        Schema::dropIfExists('live_quiz_participants');
        Schema::dropIfExists('live_quiz_sessions');
    }
};
