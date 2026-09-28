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
     * Mengambil seluruh model Subject yang diampu guru,
     * baik yang diplot secara eksplisit (teacher_subjects)
     * maupun yang digunakan pada modul ajar milik guru.
     */
    public function allAssignedSubjects()
    {
        $pivotSubjects = $this->relationLoaded('subjects') ? $this->subjects : $this->subjects()->get();
        $moduleSubjects = $this->relationLoaded('modules')
            ? $this->modules->pluck('subject')->filter()
            : $this->modules()->with('subject')->get()->pluck('subject')->filter();

        return $pivotSubjects->concat($moduleSubjects)->unique('id')->values();
    }

    /**
     * Mengambil array ID seluruh mata pelajaran yang diampu guru.
     *
     * @return int[]
     */
    public function allAssignedSubjectIds(): array
    {
        $pivotIds = $this->subjects()->pluck('subjects.id')->toArray();
        $moduleIds = $this->modules()->whereNotNull('subject_id')->pluck('subject_id')->toArray();
        return array_values(array_unique(array_map('intval', array_merge($pivotIds, $moduleIds))));
    }

    /**
     * Mengambil array ID seluruh kelas yang dibina guru.
     *
     * @return int[]
     */
    public function allAssignedClassIds(): array
    {
        $pivotIds = $this->classes()->pluck('classes.id')->toArray();
        $moduleIds = $this->modules()->whereNotNull('class_id')->pluck('class_id')->toArray();
        return array_values(array_unique(array_map('intval', array_merge($pivotIds, $moduleIds))));
    }

    /**
     * Mengambil daftar nama mata pelajaran yang diampu guru dalam bentuk string terformat.
     * Contoh: "Informatika & Teknik Elektro"
     */
    public function subjectNames(): string
    {
        $names = $this->allAssignedSubjects()->pluck('name')->toArray();
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
     * Relasi kelas didik yang menjadi tanggung jawab guru ini.
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_teacher', 'teacher_id', 'class_id')->withTimestamps();
    }

    /**
     * Mengambil daftar kelas binaan (kelas didik yang ditugaskan ke guru).
     * Dapat difilter berdasarkan subject_id jika ada.
     */
    public function assignedClasses(?int $subjectId = null)
    {
        if ($this->classes()->exists()) {
            return $this->classes()->with(['major', 'students', 'modules.studentResults'])->get();
        }

        $query = $this->modules()->with(['schoolClass.major', 'schoolClass.students', 'schoolClass.modules.studentResults']);
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
