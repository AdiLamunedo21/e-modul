<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSheet extends Model
{
    protected $guarded = [];
    protected $table = 'job_sheets';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function submissions()
    {
        return $this->hasMany(JobSheetSubmission::class);
    }
}

