<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTestQuestion extends Model
{
    protected $table = 'post_test_questions';

    protected $guarded = [];

    protected $casts = [
        'options'            => 'array',
        'score_weight'       => 'integer',
        'time_limit_seconds' => 'integer',
        'order_num'          => 'integer',
    ];

    /** Relasi ke PostTest induk */
    public function postTest(): BelongsTo
    {
        return $this->belongsTo(PostTest::class, 'post_test_id');
    }
}
