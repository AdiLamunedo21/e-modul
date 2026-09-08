<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class LiveQuizSession extends Model
{
    protected $table = 'live_quiz_sessions';

    protected $guarded = [];

    protected $casts = [
        'current_question_index' => 'integer',
        'time_limit_seconds'     => 'integer',
        'total_questions'        => 'integer',
        'grades_saved'           => 'boolean',
        'question_started_at'    => 'datetime',
    ];

    /** Accessor agar $session->pin mengembalikan pin_code */
    public function getPinAttribute(): string
    {
        return $this->pin_code ?? '';
    }

    /** Relasi ke Guru Host */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /** Relasi ke Modul Induk */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    /** Relasi ke Rombel Kelas sasaran (opsional) */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /** Relasi ke Peserta Kuis */
    public function participants(): HasMany
    {
        return $this->hasMany(LiveQuizParticipant::class, 'live_quiz_session_id');
    }

    /** Relasi ke Jawaban */
    public function answers(): HasMany
    {
        return $this->hasMany(LiveQuizAnswer::class, 'live_quiz_session_id');
    }

    /**
     * Generate PIN 6 digit unik untuk sesi live yang aktif
     */
    public static function generatePin(): string
    {
        do {
            $pin = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (static::where('pin_code', $pin)->where('status', '!=', 'finished')->exists());

        return $pin;
    }

    /**
     * Mengambil daftar butir soal sesuai tipe tes (pre_test / post_test)
     */
    public function getQuestionsList(): Collection
    {
        if ($this->test_type === 'pre_test') {
            $test = $this->module->preTest;
            return $test ? $test->questions()->orderBy('order_num', 'asc')->get() : collect();
        } else {
            $test = $this->module->postTest;
            return $test ? $test->questions()->orderBy('order_num', 'asc')->get() : collect();
        }
    }

    /**
     * Mengambil soal aktif saat ini
     */
    public function currentQuestion()
    {
        $questions = $this->getQuestionsList();
        return $questions->get($this->current_question_index);
    }

    /**
     * Menghitung sisa waktu pengerjaan soal dalam detik
     */
    public function remainingSeconds(): int
    {
        if (!$this->question_started_at || $this->status !== 'question') {
            return 0;
        }

        $elapsed = max(0, now()->timestamp - $this->question_started_at->timestamp);
        $remaining = $this->time_limit_seconds - $elapsed;

        return max(0, (int) $remaining);
    }

    /**
     * Menghitung distribusi jawaban peserta untuk butir soal saat ini
     */
    public function getAnswerDistribution(): array
    {
        $currentQuestion = $this->currentQuestion();
        if (!$currentQuestion) {
            return [];
        }

        $answers = $this->answers()
            ->where('question_id', $currentQuestion->id)
            ->get();

        $distribution = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0,
        ];

        foreach ($answers as $ans) {
            $opt = strtoupper($ans->selected_option);
            if (isset($distribution[$opt])) {
                $distribution[$opt]++;
            } else {
                $distribution[$opt] = 1;
            }
        }

        return [
            'counts'        => $distribution,
            'total_answers' => $answers->count(),
            'correct_key'   => strtoupper($currentQuestion->correct_answer),
        ];
    }

    /**
     * Mengambil Papan Peringkat (Top 5 Siswa)
     */
    public function getTopLeaderboard(int $limit = 5): Collection
    {
        return $this->participants()
            ->with('student.schoolClass')
            ->orderByDesc('total_score')
            ->orderByDesc('correct_answers_count')
            ->orderBy('last_answered_at')
            ->take($limit)
            ->get();
    }

    /**
     * Mengambil 3 Juara Teratas untuk Podium Akhir (Hanya data siswa, tanpa karakter maskot)
     */
    public function getPodium(): Collection
    {
        return $this->participants()
            ->with('student.schoolClass')
            ->orderByDesc('total_score')
            ->orderByDesc('correct_answers_count')
            ->orderBy('last_answered_at')
            ->take(3)
            ->get();
    }
}
