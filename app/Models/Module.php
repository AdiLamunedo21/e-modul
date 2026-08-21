<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = [];

    protected $casts = [
        'informasi_umum_data' => 'array',
        'bagian_akhir_data' => 'array',
        'pre_test_data'     => 'array',
        'materi_data'       => 'array',
        'video_data'        => 'array',
        'embed_data'        => 'array',
        'job_sheet_data'    => 'array',
        'lkpd_data'         => 'array',
        'post_test_data'    => 'array',
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

    public function lkpds()
    {
        return $this->hasMany(Lkpd::class);
    }

    public function lkpdSubmissions()
    {
        return $this->hasManyThrough(Submission::class, Lkpd::class);
    }

    public function preTest()
    {
        return $this->hasOne(PreTest::class);
    }

    public function postTest()
    {
        return $this->hasOne(PostTest::class);
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

    /** Daftar 7 Komponen Inti yang diaktifkan */
    public function activeComponents(): array
    {
        $map = [
            'has_pre_test'  => '1. Pre-test',
            'has_materi'    => '2. Materi & PPT',
            'has_video'     => '3. Video YouTube',
            'has_embed'     => '4. Praktik Embed',
            'has_job_sheet' => '5. Job Sheet PDF',
            'has_lkpd'      => '6. Tugas LKPD',
            'has_post_test' => '7. Post-test',
        ];

        return array_values(array_filter($map, fn($_, $key) => $this->$key, ARRAY_FILTER_USE_BOTH));
    }

    /** Memeriksa apakah sub-komponen Informasi Umum aktif (default true) */
    public function isInfoComponentActive(string $key): bool
    {
        $toggles = $this->informasi_umum_data['toggles'] ?? [];
        return isset($toggles[$key]) ? (bool)$toggles[$key] : true;
    }

    /** Daftar komponen Informasi Umum yang diaktifkan */
    public function activeInfoComponents(): array
    {
        $all = [
            'cover'               => '1. Halaman Cover',
            'kata_pengantar'      => '2. Kata Pengantar',
            'daftar_isi'          => '3. Daftar Isi',
            'peta_konsep'         => '4. Peta Konsep',
            'glosarium'           => '5. Glosarium',
            'petunjuk_penggunaan' => '6. Petunjuk Penggunaan',
            'tujuan_pembelajaran' => '7. Tujuan Pembelajaran',
            'daftar_pustaka'      => '8. Daftar Pustaka',
        ];
        $active = [];
        foreach ($all as $key => $label) {
            if ($this->isInfoComponentActive($key)) {
                $active[$key] = $label;
            }
        }
        return $active;
    }

    /** Mendapatkan daftar soal pre-test */
    public function preTestQuestions(): array
    {
        if ($this->preTest && $this->preTest->questions()->exists()) {
            return $this->preTest->questions->map(fn($q) => [
                'id'            => $q->id,
                'pertanyaan'    => $q->question_text,
                'pilihan'       => $q->options ?? [],
                'kunci_jawaban' => $q->correct_answer,
                'bobot'         => $q->score_weight,
                'pembahasan'    => $q->explanation ?? '',
            ])->toArray();
        }

        if (is_array($this->pre_test_data)) {
            return $this->pre_test_data['questions'] ?? [];
        }

        return [];
    }

    /** Jumlah soal pre-test */
    public function preTestQuestionCount(): int
    {
        if ($this->preTest) {
            return $this->preTest->questions()->count();
        }
        return count($this->preTestQuestions());
    }

    /** Judul Pre-test */
    public function preTestTitle(): string
    {
        return $this->preTest?->title ?? ($this->pre_test_data['judul'] ?? 'Pre-test Pembuka');
    }

    /** Durasi Pre-test */
    public function preTestDuration(): int
    {
        return $this->preTest?->duration_minutes ?? (int)($this->pre_test_data['durasi_menit'] ?? 15);
    }

    /** KKTP Pre-test */
    public function preTestKktp(): int
    {
        return $this->preTest?->kktp ?? (int)($this->pre_test_data['kktp'] ?? 75);
    }

    /** Petunjuk Pre-test */
    public function preTestInstructions(): string
    {
        return $this->preTest?->instructions ?? ($this->pre_test_data['petunjuk'] ?? '');
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

    /* ─── LKPD Helpers ─────────────────────────── */

    /** Mendapatkan judul LKPD */
    public function lkpdTitle(): string
    {
        return $this->lkpd_data['lkpd_title'] ?? 'Lembar Kerja Peserta Didik: ' . $this->title;
    }

    /** Mode pengerjaan: 'group' (kelompok) atau 'individual' (individu) */
    public function lkpdWorkMode(): string
    {
        return $this->lkpd_data['work_mode'] ?? 'group';
    }

    /** Apakah mode pengerjaan adalah kelompok */
    public function isLkpdGroup(): bool
    {
        return $this->lkpdWorkMode() === 'group';
    }

    /** Ukuran kelompok / jumlah anggota */
    public function lkpdGroupSize(): string
    {
        return $this->lkpd_data['group_size'] ?? '3 - 4 Siswa';
    }

    /** Deskripsi skenario studi kasus teknis */
    public function lkpdCaseStudy(): string
    {
        return $this->lkpd_data['case_study'] ?? '';
    }

    /** Petunjuk & tahapan kerja */
    public function lkpdInstructions(): string
    {
        return $this->lkpd_data['instructions'] ?? '';
    }

    /** Rubrik / kriteria penilaian LKPD */
    public function lkpdRubric(): array
    {
        if (!is_array($this->lkpd_data)) {
            return [];
        }
        return $this->lkpd_data['assessment_rubric'] ?? [];
    }

    /** Memeriksa apakah LKPD memiliki file PDF panduan terunggah */
    public function hasLkpdPdf(): bool
    {
        return !empty($this->lkpd_data['pdf_file_path']);
    }

    /** Mendapatkan path file PDF panduan LKPD */
    public function lkpdPdfPath(): ?string
    {
        return $this->lkpd_data['pdf_file_path'] ?? null;
    }

    /** Mendapatkan nama asli berkas PDF LKPD */
    public function lkpdPdfName(): string
    {
        return $this->lkpd_data['pdf_file_name'] ?? 'LKPD-' . $this->id . '.pdf';
    }

    /* ─── Post-Test Helpers ─────────────────────── */

    /** Mendapatkan daftar soal post-test */
    public function postTestQuestions(): array
    {
        if ($this->postTest && $this->postTest->questions()->exists()) {
            return $this->postTest->questions->map(fn($q) => [
                'id'            => $q->id,
                'pertanyaan'    => $q->question_text,
                'pilihan'       => $q->options ?? [],
                'kunci_jawaban' => $q->correct_answer,
                'bobot'         => $q->score_weight,
                'pembahasan'    => $q->explanation ?? '',
            ])->toArray();
        }

        if (is_array($this->post_test_data)) {
            return $this->post_test_data['questions'] ?? [];
        }

        return [];
    }

    /** Jumlah soal post-test */
    public function postTestQuestionCount(): int
    {
        if ($this->postTest) {
            return $this->postTest->questions()->count();
        }
        return count($this->postTestQuestions());
    }

    /** Mendapatkan judul post-test */
    public function postTestTitle(): string
    {
        return $this->postTest?->title ?? ($this->post_test_data['judul'] ?? 'Post-test: Evaluasi Pemahaman Materi');
    }

    /** Mendapatkan durasi pengerjaan post-test dalam menit */
    public function postTestDuration(): int
    {
        return $this->postTest?->duration_minutes ?? (int) ($this->post_test_data['durasi_menit'] ?? 20);
    }

    /** Mendapatkan nilai ambang batas KKTP post-test */
    public function postTestKktp(): int
    {
        return $this->postTest?->kktp ?? (int) ($this->post_test_data['kktp'] ?? 75);
    }

    /** Mendapatkan petunjuk pengerjaan post-test */
    public function postTestInstructions(): string
    {
        return $this->postTest?->instructions ?? ($this->post_test_data['petunjuk'] ?? '');
    }

    /** Daftar komponen penilaian yang aktif pada modul */
    public function activeGradedComponents(): array
    {
        $components = [];

        if ($this->has_pre_test) {
            $components['pre_test'] = [
                'name'      => 'Pre-test',
                'field'     => 'pre_test_score',
                'type'      => 'auto',
                'badge'     => 'bg-blue-100 text-blue-800 border-blue-200',
                'icon'      => '📝',
                'max_score' => 100,
            ];
        }
        if ($this->has_video) {
            $components['video'] = [
                'name'      => 'Ringkasan Video',
                'field'     => 'video_score',
                'type'      => 'manual',
                'badge'     => 'bg-red-100 text-red-800 border-red-200',
                'icon'      => '🎬',
                'max_score' => 100,
            ];
        }
        if ($this->has_embed) {
            $components['embed'] = [
                'name'      => 'Praktik Embed',
                'field'     => 'embed_score',
                'type'      => 'manual',
                'badge'     => 'bg-violet-100 text-violet-800 border-violet-200',
                'icon'      => '⚡',
                'max_score' => 100,
            ];
        }
        if ($this->has_job_sheet) {
            $components['job_sheet'] = [
                'name'      => 'Job Sheet (PDF)',
                'field'     => 'job_sheet_score',
                'type'      => 'manual',
                'badge'     => 'bg-amber-100 text-amber-800 border-amber-200',
                'icon'      => '📋',
                'max_score' => 100,
            ];
        }
        if ($this->has_lkpd) {
            $components['lkpd'] = [
                'name'      => 'Tugas LKPD',
                'field'     => 'lkpd_score',
                'type'      => 'manual',
                'badge'     => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                'icon'      => '📑',
                'max_score' => 100,
            ];
        }
        if ($this->has_post_test) {
            $components['post_test'] = [
                'name'      => 'Post-test',
                'field'     => 'post_test_score',
                'type'      => 'auto',
                'badge'     => 'bg-teal-100 text-teal-800 border-teal-200',
                'icon'      => '🎯',
                'max_score' => 100,
            ];
        }

        return $components;
    }

    /** Statistik penilaian modul */
    public function gradingStats(): array
    {
        $totalStudents = $this->schoolClass ? $this->schoolClass->students()->count() : 0;
        $results = $this->studentResults;

        $submittedCount = $results->count();
        $gradedCount    = $results->where('grading_status', 'graded')->count();
        $pendingCount   = $results->where('grading_status', 'pending')->count();

        $avgScore = $gradedCount > 0 ? (int) round($results->where('grading_status', 'graded')->avg('summative_score')) : 0;
        $progressPct = $totalStudents > 0 ? (int) round(($gradedCount / $totalStudents) * 100) : 0;

        return [
            'total_students'  => $totalStudents,
            'submitted_count' => $submittedCount,
            'graded_count'    => $gradedCount,
            'pending_count'   => $pendingCount,
            'avg_score'       => $avgScore,
            'progress_pct'    => $progressPct,
        ];
    }
}
