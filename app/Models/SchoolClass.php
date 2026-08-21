<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';
    protected $guarded = ['id'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'class_id');
    }

    /** Label nama kelas lengkap (Contoh: Kelas XI RPL) */
    public function getFullNameAttribute(): string
    {
        return 'Kelas ' . $this->grade . ' ' . $this->major_name;
    }
}
