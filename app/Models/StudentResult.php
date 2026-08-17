<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
