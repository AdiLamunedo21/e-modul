<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmbedSubmission extends Model
{
    protected $guarded = [];
    protected $table = 'embed_submissions';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
