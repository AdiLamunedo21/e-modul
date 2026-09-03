<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreTest extends Model
{
    protected $table = 'pre_tests';

    protected $guarded = [];

    protected $casts = [
        'kktp'                => 'integer',
        'randomize_questions' => 'boolean',
    ];

    /** Total estimasi durasi soal dalam detik */
    public function totalDurationSeconds(): int
    {
        return (int) $this->questions()->sum('time_limit_seconds');
    }

    /** Relasi ke modul */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /** Relasi ke butir soal */
    public function questions(): HasMany
    {
        return $this->hasMany(PreTestQuestion::class, 'pre_test_id')->orderBy('order_num', 'asc');
    }

    /** Total butir soal */
    public function questionCount(): int
    {
        return $this->questions()->count();
    }

    /** Total akumulasi bobot nilai */
    public function totalScore(): int
    {
        return (int) $this->questions()->sum('score_weight');
    }
}
