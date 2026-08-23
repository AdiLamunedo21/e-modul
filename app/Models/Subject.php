<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subjects');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects');
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Helper badge styling untuk UI
     */
    public function badgeClasses(): string
    {
        return match ($this->color) {
            'amber', 'yellow' => 'bg-amber-50 text-amber-800 border-amber-200',
            'emerald', 'green' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'indigo', 'purple' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
            'rose', 'red'      => 'bg-rose-50 text-rose-800 border-rose-200',
            'cyan', 'teal'     => 'bg-cyan-50 text-cyan-800 border-cyan-200',
            default            => 'bg-blue-50 text-blue-800 border-blue-200',
        };
    }

    /**
     * Helper icon badge styling untuk tab pills
     */
    public function pillClasses(bool $active = false): string
    {
        if ($active) {
            return 'bg-blue-600 text-white shadow-sm shadow-blue-600/30';
        }

        return 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200/80';
    }
}
