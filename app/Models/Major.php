<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $guarded = [];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'major_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, SchoolClass::class, 'major_id', 'class_id');
    }

    public function modules()
    {
        return $this->hasManyThrough(Module::class, SchoolClass::class, 'major_id', 'class_id');
    }
}
