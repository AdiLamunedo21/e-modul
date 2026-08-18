<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoSummary extends Model
{
    protected $guarded = [];
    protected $table = 'video_summaries';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
