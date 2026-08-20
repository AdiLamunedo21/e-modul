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
        // ── 1. Tabel Pre-test ───────────────────────────────────
        Schema::create('pre_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->unique()->constrained('modules')->onDelete('cascade');
            $table->string('title')->default('Pre-test Pembuka');
            $table->integer('duration_minutes')->default(15);
            $table->integer('kktp')->default(75);
            $table->text('instructions')->nullable();
            $table->boolean('randomize_questions')->default(false);
            $table->timestamps();
        });

        // ── 2. Tabel Butir Soal Pre-test ────────────────────────
        Schema::create('pre_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_test_id')->constrained('pre_tests')->onDelete('cascade');
            $table->text('question_text');
            $table->json('options'); // ['A' => '...', 'B' => '...', 'C' => '...', 'D' => '...', 'E' => '...']
            $table->string('correct_answer', 5)->default('A');
            $table->integer('score_weight')->default(10);
            $table->text('explanation')->nullable();
            $table->integer('order_num')->default(1);
            $table->timestamps();
        });

        // ── 3. Tabel Post-test ──────────────────────────────────
        Schema::create('post_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->unique()->constrained('modules')->onDelete('cascade');
            $table->string('title')->default('Post-test: Evaluasi Pemahaman Materi');
            $table->integer('duration_minutes')->default(20);
            $table->integer('kktp')->default(75);
            $table->text('instructions')->nullable();
            $table->boolean('randomize_questions')->default(false);
            $table->timestamps();
        });

        // ── 4. Tabel Butir Soal Post-test ───────────────────────
        Schema::create('post_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_test_id')->constrained('post_tests')->onDelete('cascade');
            $table->text('question_text');
            $table->json('options');
            $table->string('correct_answer', 5)->default('A');
            $table->integer('score_weight')->default(10);
            $table->text('explanation')->nullable();
            $table->integer('order_num')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_test_questions');
        Schema::dropIfExists('post_tests');
        Schema::dropIfExists('pre_test_questions');
        Schema::dropIfExists('pre_tests');
    }
};
