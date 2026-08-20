<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $guarded = [];
    protected $table = 'submissions';

    public function lkpd()
    {
        return $this->belongsTo(Lkpd::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
