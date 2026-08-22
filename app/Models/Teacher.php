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
     * Relasi mata pelajaran yang diampu oleh guru ini.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects');
    }

    /**
     * Mengambil daftar nama mata pelajaran yang diampu guru dalam bentuk string terformat.
     * Contoh: "Informatika & Teknik Elektro"
     */
    public function subjectNames(): string
    {
        $names = $this->subjects->pluck('name')->toArray();
        if (empty($names)) {
            return 'Belum Ada Mapel';
        }
        if (count($names) === 1) {
            return $names[0];
        }
        $last = array_pop($names);
        return implode(', ', $names) . ' & ' . $last;
    }

    /**
     * Mengambil daftar kelas binaan (kelas-kelas yang memiliki modul dari guru ini).
     * Dapat difilter berdasarkan subject_id jika ada.
     */
    public function assignedClasses(?int $subjectId = null)
    {
        $query = $this->modules()->with('schoolClass');
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query
            ->get()
            ->pluck('schoolClass')
            ->filter()
            ->unique('id');
    }
}
