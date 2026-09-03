<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreTestQuestion extends Model
{
    protected $table = 'pre_test_questions';

    protected $guarded = [];

    protected $casts = [
        'options'            => 'array',
        'score_weight'       => 'integer',
        'time_limit_seconds' => 'integer',
        'order_num'          => 'integer',
    ];

    /** Relasi ke PreTest induk */
    public function preTest(): BelongsTo
    {
        return $this->belongsTo(PreTest::class, 'pre_test_id');
    }
}
