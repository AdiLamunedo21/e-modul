<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
