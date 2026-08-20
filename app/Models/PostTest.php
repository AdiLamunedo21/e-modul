<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostTest extends Model
{
    protected $table = 'post_tests';

    protected $guarded = [];

    protected $casts = [
        'duration_minutes'    => 'integer',
        'kktp'                => 'integer',
        'randomize_questions' => 'boolean',
    ];

    /** Relasi ke modul */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /** Relasi ke butir soal */
    public function questions(): HasMany
    {
        return $this->hasMany(PostTestQuestion::class, 'post_test_id')->orderBy('order_num', 'asc');
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
