<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = [];

    protected $casts = [
        'bagian_awal_data'  => 'array',
        'bagian_akhir_data' => 'array',
        'pre_test_data'     => 'array',
        'materi_data'       => 'array',
        'has_pre_test'      => 'boolean',
        'has_materi'        => 'boolean',
        'has_video'         => 'boolean',
        'has_embed'         => 'boolean',
        'has_job_sheet'     => 'boolean',
        'has_lkpd'          => 'boolean',
        'has_post_test'     => 'boolean',
    ];

    /* ─── Relationships ─────────────────────────── */

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function studentResults()
    {
        return $this->hasMany(StudentResult::class);
    }

    /* ─── Helpers ───────────────────────────────── */

    /** Label badge warna sesuai status modul */
    public function statusLabel(): array
    {
        return match($this->status) {
            'published' => ['label' => 'Published', 'color' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
            'closed'    => ['label' => 'Closed',    'color' => 'bg-slate-100 text-slate-600 border-slate-200'],
            default     => ['label' => 'Draft',     'color' => 'bg-amber-100 text-amber-800 border-amber-200'],
        };
    }

    /** Daftar 7 komponen Bagian Inti yang diaktifkan */
    public function activeComponents(): array
    {
        $map = [
            'has_pre_test'  => '1. Pre-test',
            'has_materi'    => '2. Materi & PPT',
            'has_video'     => '3. Video YouTube',
            'has_embed'     => '4. Praktik Embed',
            'has_job_sheet' => '5. Job Sheet PDF',
            'has_lkpd'      => '6. LKPD Kelompok',
            'has_post_test' => '7. Post-test',
        ];

        return array_values(array_filter($map, fn($_, $key) => $this->$key, ARRAY_FILTER_USE_BOTH));
    }

    /** Mendapatkan daftar soal pre-test */
    public function preTestQuestions(): array
    {
        if (!is_array($this->pre_test_data)) {
            return [];
        }
        return $this->pre_test_data['questions'] ?? [];
    }

    /** Jumlah soal pre-test */
    public function preTestQuestionCount(): int
    {
        return count($this->preTestQuestions());
    }

    /** Memeriksa apakah materi memiliki file PPT/PDF */
    public function hasPptFile(): bool
    {
        return !empty($this->materi_data['ppt_file_path']);
    }

    /** Mendapatkan judul materi */
    public function materiTitle(): string
    {
        return $this->materi_data['judul_materi'] ?? 'Materi Pembelajaran';
    }
}


