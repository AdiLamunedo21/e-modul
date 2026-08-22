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

    /**
     * Mengambil daftar kelas binaan (kelas-kelas yang memiliki modul dari guru ini).
     */
    public function assignedClasses()
    {
        return $this->modules()
            ->with('schoolClass')
            ->get()
            ->pluck('schoolClass')
            ->filter()
            ->unique('id');
    }
}
