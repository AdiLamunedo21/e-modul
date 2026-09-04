<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Relasi banyak kelas yang diikuti oleh siswa (Many-to-Many).
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')->withTimestamps();
    }

    /**
     * Alias relasi classes.
     */
    public function schoolClasses()
    {
        return $this->classes();
    }

    /**
     * Relasi kelas aktif / fallback terakhir siswa.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Cache ID kelas yang diikuti siswa untuk siklus request aktif.
     */
    protected ?array $memoizedJoinedClassIds = null;

    /**
     * Mengambil seluruh array ID kelas yang diikuti oleh siswa ini.
     *
     * @return int[]
     */
    public function joinedClassIds(): array
    {
        if ($this->memoizedJoinedClassIds !== null) {
            return $this->memoizedJoinedClassIds;
        }

        if ($this->relationLoaded('classes')) {
            $ids = $this->classes->pluck('id')->map(fn($id) => (int)$id)->toArray();
        } else {
            $ids = $this->classes()->pluck('classes.id')->map(fn($id) => (int)$id)->toArray();
        }

        if (empty($ids) && $this->class_id) {
            $ids = [(int) $this->class_id];
        }

        return $this->memoizedJoinedClassIds = array_values(array_unique(array_filter($ids)));
    }

    /**
     * Menggabungkan siswa ke rombel kelas tertentu dan otomatis menyinkronkan mata pelajaran.
     */
    public function joinClass(SchoolClass $schoolClass): void
    {
        $this->memoizedJoinedClassIds = null;

        // 1. Daftarkan siswa ke rombel kelas di tabel pivot class_student (banyak kelas)
        $this->classes()->syncWithoutDetaching([$schoolClass->id]);

        // 2. Simpan juga ke kolom class_id sebagai penanda kelas aktif terakhir
        $this->update(['class_id' => $schoolClass->id]);

        // 3. Cari seluruh mata pelajaran yang ada di modul-modul kelas ini
        $subjectIds = $schoolClass->modules()
            ->whereNotNull('subject_id')
            ->pluck('subject_id')
            ->unique()
            ->toArray();

        if (!empty($subjectIds)) {
            $this->subjects()->syncWithoutDetaching($subjectIds);
        }
    }

    /**
     * Mengeluarkan siswa dari rombel kelas tertentu.
     * Menghapus seluruh data nilai, progres, dan submission siswa khusus untuk modul-modul di kelas ini.
     * Catatan: Kelas dan modul di database serta dashboard guru tetap aman dan tidak terhapus.
     */
    public function leaveClass(SchoolClass $schoolClass): void
    {
        $this->memoizedJoinedClassIds = null;

        // 1. Dapatkan seluruh ID modul di kelas ini
        $moduleIds = $schoolClass->modules()->pluck('id')->toArray();

        if (!empty($moduleIds)) {
            // Hapus hasil belajar & nilai siswa pada modul-modul kelas ini
            StudentResult::where('student_id', $this->id)
                ->whereIn('module_id', $moduleIds)
                ->delete();

            // Hapus ringkasan video siswa
            VideoSummary::where('student_id', $this->id)
                ->whereIn('module_id', $moduleIds)
                ->delete();

            // Hapus screenshot praktik embed siswa
            EmbedSubmission::where('student_id', $this->id)
                ->whereIn('module_id', $moduleIds)
                ->delete();

            // Hapus submission Job Sheet siswa
            $jobSheetIds = JobSheet::whereIn('module_id', $moduleIds)->pluck('id')->toArray();
            if (!empty($jobSheetIds)) {
                JobSheetSubmission::where('student_id', $this->id)
                    ->whereIn('job_sheet_id', $jobSheetIds)
                    ->delete();
            }

            // Hapus submission LKPD siswa
            $lkpdIds = Lkpd::whereIn('module_id', $moduleIds)->pluck('id')->toArray();
            if (!empty($lkpdIds)) {
                Submission::where('student_id', $this->id)
                    ->whereIn('lkpd_id', $lkpdIds)
                    ->delete();
            }
        }

        // 2. Lepas relasi kelas dari tabel pivot class_student
        $this->classes()->detach($schoolClass->id);

        // 3. Jika class_id aktif sama dengan kelas ini, alihkan ke kelas lain yang masih diikuti atau null
        if ($this->class_id == $schoolClass->id) {
            $nextClass = $this->classes()->first();
            $this->update(['class_id' => $nextClass?->id]);
        }
    }

    /**
     * Relasi mata pelajaran yang ditempuh oleh siswa ini.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects');
    }

    /**
     * Mengambil daftar nama mata pelajaran yang ditempuh siswa dalam bentuk string terformat.
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

    public function studentResults()
    {
        return $this->hasMany(StudentResult::class);
    }

    public function videoSummaries()
    {
        return $this->hasMany(VideoSummary::class);
    }

    public function embedSubmissions()
    {
        return $this->hasMany(EmbedSubmission::class);
    }

    public function jobSheetSubmissions()
    {
        return $this->hasMany(JobSheetSubmission::class);
    }

    public function lkpdSubmissions()
    {
        return $this->hasMany(Submission::class);
    }

    /** Mendapatkan hasil belajar untuk modul tertentu */
    public function resultForModule(int|string $moduleId): ?StudentResult
    {
        return $this->studentResults->firstWhere('module_id', $moduleId);
    }

    /** Mendapatkan ringkasan video untuk modul tertentu */
    public function videoSummaryForModule(int|string $moduleId): ?VideoSummary
    {
        return $this->videoSummaries->firstWhere('module_id', $moduleId);
    }

    /** Mendapatkan submission embed untuk modul tertentu */
    public function embedSubmissionForModule(int|string $moduleId): ?EmbedSubmission
    {
        return $this->embedSubmissions->firstWhere('module_id', $moduleId);
    }

    /** Mendapatkan submission job sheet untuk modul tertentu */
    public function jobSheetSubmissionForModule(int|string $moduleId): ?JobSheetSubmission
    {
        return $this->jobSheetSubmissions->first(function ($sub) use ($moduleId) {
            return $sub->jobSheet && $sub->jobSheet->module_id == $moduleId;
        });
    }

    /** Mendapatkan submission LKPD untuk modul tertentu */
    public function lkpdSubmissionForModule(int|string $moduleId): ?Submission
    {
        return $this->lkpdSubmissions->first(function ($sub) use ($moduleId) {
            return $sub->lkpd && $sub->lkpd->module_id == $moduleId;
        });
    }
}
