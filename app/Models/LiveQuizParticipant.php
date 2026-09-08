<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveQuizParticipant extends Model
{
    protected $table = 'live_quiz_participants';

    protected $guarded = [];

    protected $casts = [
        'total_score'           => 'integer',
        'correct_answers_count' => 'integer',
        'last_score_earned'     => 'integer',
        'last_answered_at'      => 'datetime',
    ];

    /** Relasi ke Sesi Kuis */
    public function session(): BelongsTo
    {
        return $this->belongsTo(LiveQuizSession::class, 'live_quiz_session_id');
    }

    /** Relasi ke Akun Siswa */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /** Relasi ke Rekaman Jawaban */
    public function answers(): HasMany
    {
        return $this->hasMany(LiveQuizAnswer::class, 'live_quiz_participant_id');
    }
}
