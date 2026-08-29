<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SchoolClass extends Model
{
    protected $table = 'classes';
    protected $guarded = ['id'];

    /**
     * Boot model events untuk otomatisasi kode kelas.
     */
    protected static function booted()
    {
        static::creating(function ($schoolClass) {
            if (empty($schoolClass->code)) {
                $schoolClass->code = self::generateUniqueCode();
            }
        });
    }

    /**
     * Menghasilkan kode kelas acak unik (6 karakter alfanumerik huruf kapital).
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Memperbarui / mengacak ulang kode kelas.
     */
    public function regenerateCode(): string
    {
        $newCode = self::generateUniqueCode();
        $this->update(['code' => $newCode]);
        return $newCode;
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id')->withTimestamps();
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'class_id');
    }

    /** Label nama kelas lengkap (Contoh: Kelas X RPL 1 atau Kelas XI RPL) */
    public function getFullNameAttribute(): string
    {
        $majorStr = $this->major ? ($this->major->code ?: $this->major->name) : $this->major_name;
        $sectionStr = $this->section ? ' ' . $this->section : '';
        return 'Kelas ' . $this->grade . ' ' . $majorStr . $sectionStr;
    }

    /** Label rombel ringkas (Contoh: X RPL 1) */
    public function getShortNameAttribute(): string
    {
        $majorStr = $this->major ? ($this->major->code ?: $this->major->name) : $this->major_name;
        $sectionStr = $this->section ? ' ' . $this->section : '';
        return $this->grade . ' ' . $majorStr . $sectionStr;
    }

    /**
     * Mengambil modul-modul milik guru tertentu yang ditugaskan ke kelas ini.
     */
    public function teacherModules(int $teacherId)
    {
        return $this->modules()->where('teacher_id', $teacherId);
    }

    /**
     * Menghitung statistik kelas untuk guru tertentu:
     * - total siswa
     * - total modul terbit
     * - total pengumpulan tugas
     * - rata-rata nilai kelas
     */
    public function statsForTeacher(int $teacherId): array
    {
        // Gunakan relasi modules yang sudah di-eager load jika ada untuk mencegah query N+1
        if ($this->relationLoaded('modules')) {
            $teacherModules = $this->modules->where('teacher_id', $teacherId);
        } else {
            $teacherModules = $this->teacherModules($teacherId)->with('studentResults')->get();
        }

        $totalModules = $teacherModules->count();
        $publishedModules = $teacherModules->where('status', 'published')->count();
        
        $totalStudents = $this->relationLoaded('students')
            ? $this->students->count()
            : ($this->students_count ?? $this->students()->count());

        $allResults = $teacherModules->pluck('studentResults')->flatten()->filter();
        $gradedResults = $allResults->where('grading_status', 'graded');
        $avgScore = $gradedResults->count() > 0 ? (int) round($gradedResults->avg('summative_score')) : 0;

        return [
            'total_students'    => $totalStudents,
            'total_modules'     => $totalModules,
            'published_modules' => $publishedModules,
            'total_submissions' => $allResults->count(),
            'graded_count'      => $gradedResults->count(),
            'avg_score'         => $avgScore,
            'is_assigned'       => $totalModules > 0,
        ];
    }
}
