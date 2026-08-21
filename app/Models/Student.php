<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
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
