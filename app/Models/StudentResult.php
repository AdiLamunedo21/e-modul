<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    protected $guarded = [];

    protected $casts = [
        'pre_test_score'   => 'integer',
        'video_score'      => 'integer',
        'embed_score'      => 'integer',
        'job_sheet_score'  => 'integer',
        'lkpd_score'       => 'integer',
        'post_test_score'  => 'integer',
        'summative_score'  => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /** Menghitung nilai sumatif adaptif berdasarkan komponen aktif modul */
    public function calculateSummativeScore(?Module $module = null): int
    {
        $targetModule = $module ?? $this->module;
        if (!$targetModule) {
            return $this->summative_score ?? 0;
        }

        $scores = [];

        if ($targetModule->has_pre_test && $this->pre_test_score !== null) {
            $scores[] = (int) $this->pre_test_score;
        }
        if ($targetModule->has_video && $this->video_score !== null) {
            $scores[] = (int) $this->video_score;
        }
        if ($targetModule->has_embed && $this->embed_score !== null) {
            $scores[] = (int) $this->embed_score;
        }
        if ($targetModule->has_job_sheet && $this->job_sheet_score !== null) {
            $scores[] = (int) $this->job_sheet_score;
        }
        if ($targetModule->has_lkpd && $this->lkpd_score !== null) {
            $scores[] = (int) $this->lkpd_score;
        }
        if ($targetModule->has_post_test && $this->post_test_score !== null) {
            $scores[] = (int) $this->post_test_score;
        }

        if (count($scores) === 0) {
            return 0;
        }

        return (int) round(array_sum($scores) / count($scores));
    }

    /** Memeriksa apakah penilaian sudah tuntas */
    public function isGraded(): bool
    {
        return $this->grading_status === 'graded';
    }

    /** Memeriksa apakah penilaian masih pending */
    public function isPending(): bool
    {
        return $this->grading_status === 'pending';
    }

    /** Badge status penilaian */
    public function statusBadge(): array
    {
        return match ($this->grading_status) {
            'graded'  => [
                'label' => 'Selesai Dinilai',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'dot'   => 'bg-emerald-500',
            ],
            default   => [
                'label' => 'Menunggu Penilaian',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
                'dot'   => 'bg-amber-500',
            ],
        };
    }
}
