<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveQuizAnswer extends Model
{
    protected $table = 'live_quiz_answers';

    protected $guarded = [];

    protected $casts = [
        'is_correct'       => 'boolean',
        'response_time_ms' => 'integer',
        'score_earned'     => 'integer',
        'question_index'   => 'integer',
    ];

    /** Relasi ke Sesi Kuis */
    public function session(): BelongsTo
    {
        return $this->belongsTo(LiveQuizSession::class, 'live_quiz_session_id');
    }

    /** Relasi ke Peserta Kuis */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(LiveQuizParticipant::class, 'live_quiz_participant_id');
    }

    /**
     * Menghitung poin perolehan berbasis kecepatan & ketepatan (Formula Gamifikasi Kahoot)
     * Jawaban Benar = 500 s.d. 1000 poin (makin cepat menjawab makin mendekati 1000)
     * Jawaban Salah = 0 poin
     */
    public static function calculatePoints(int $timeLimitSeconds, int $responseTimeMs, bool $isCorrect): int
    {
        if (!$isCorrect) {
            return 0;
        }

        $totalMs = max(1000, $timeLimitSeconds * 1000);
        $clampedResponse = min($totalMs, max(0, $responseTimeMs));
        $timeRatio = $clampedResponse / $totalMs;

        // Poin dasar 500 + bonus kecepatan hingga 500
        $points = (int) round(1000 * (1 - ($timeRatio / 2)));

        return max(500, min(1000, $points));
    }
}
