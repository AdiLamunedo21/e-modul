<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSheetSubmission extends Model
{
    protected $guarded = [];
    protected $table = 'job_sheet_submissions';

    public function jobSheet()
    {
        return $this->belongsTo(JobSheet::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

