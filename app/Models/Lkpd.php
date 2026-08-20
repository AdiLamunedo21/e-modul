<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lkpd extends Model
{
    protected $guarded = [];
    protected $table = 'lkpds';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
