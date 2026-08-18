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
        'video_data'        => 'array',
        'embed_data'        => 'array',
        'job_sheet_data'    => 'array',
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

    public function videoSummaries()
    {
        return $this->hasMany(VideoSummary::class);
    }

    public function embedSubmissions()
    {
        return $this->hasMany(EmbedSubmission::class);
    }

    public function jobSheets()
    {
        return $this->hasMany(JobSheet::class);
    }

    public function jobSheetSubmissions()
    {
        return $this->hasManyThrough(JobSheetSubmission::class, JobSheet::class);
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

    /** Mendapatkan judul video YouTube */
    public function videoTitle(): string
    {
        return $this->video_data['video_title'] ?? 'Video Pembelajaran: ' . $this->title;
    }

    /** Mendapatkan YouTube ID */
    public function youtubeId(): ?string
    {
        return $this->video_data['youtube_id'] ?? null;
    }

    /** Mendapatkan Embed URL YouTube untuk iframe */
    public function youtubeEmbedUrl(): ?string
    {
        $id = $this->youtubeId();
        return $id ? "https://www.youtube-nocookie.com/embed/{$id}?rel=0" : null;
    }

    /** Mendapatkan judul praktik embed */
    public function embedTitle(): string
    {
        return $this->embed_data['embed_title'] ?? 'Praktik Interaktif: ' . $this->title;
    }

    /** Mendapatkan kode embed / HTML / iframe */
    public function embedCode(): string
    {
        return $this->embed_data['embed_code'] ?? '';
    }

    /** Mendapatkan tipe embed ('code' atau 'url') */
    public function embedType(): string
    {
        return $this->embed_data['embed_type'] ?? 'code';
    }

    /** Mendapatkan direct embed url */
    public function embedUrl(): ?string
    {
        return $this->embed_data['embed_url'] ?? null;
    }

    /** Mendapatkan daftar checklist target praktik */
    public function embedChecklist(): array
    {
        if (!is_array($this->embed_data)) {
            return [];
        }
        return $this->embed_data['checklist_items'] ?? [];
    }

    /** Mendapatkan judul Job Sheet */
    public function jobSheetTitle(): string
    {
        return $this->job_sheet_data['job_sheet_title'] ?? 'Lembar Praktikum: ' . $this->title;
    }

    /** Memeriksa apakah Job Sheet memiliki file PDF terunggah */
    public function hasJobSheetPdf(): bool
    {
        return !empty($this->job_sheet_data['pdf_file_path']);
    }

    /** Mendapatkan path file PDF Job Sheet */
    public function jobSheetPdfPath(): ?string
    {
        return $this->job_sheet_data['pdf_file_path'] ?? null;
    }

    /** Mendapatkan nama asli berkas PDF Job Sheet */
    public function jobSheetPdfName(): string
    {
        return $this->job_sheet_data['pdf_file_name'] ?? 'Job-Sheet-' . $this->id . '.pdf';
    }

    /** Mendapatkan daftar alat & bahan */
    public function jobSheetTools(): array
    {
        if (!is_array($this->job_sheet_data)) {
            return [];
        }
        return $this->job_sheet_data['tools_and_materials'] ?? [];
    }

    /** Mendapatkan petunjuk K3 / Keselamatan Kerja */
    public function jobSheetSafety(): string
    {
        return $this->job_sheet_data['safety_guidelines'] ?? '';
    }
}
