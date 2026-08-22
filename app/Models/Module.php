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
        'is_shared'         => 'boolean',
        'shared_at'         => 'datetime',
        'clone_count'       => 'integer',
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

    public function clonedFrom()
    {
        return $this->belongsTo(Module::class, 'cloned_from_id');
    }

    public function clones()
    {
        return $this->hasMany(Module::class, 'cloned_from_id');
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

    /* ─── Scopes ────────────────────────────────── */

    /** Scope untuk memuat modul yang dibagikan ke library */
    public function scopeSharedToLibrary($query)
    {
        return $query->where('is_shared', true);
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

    /** 1. Bagian Awal Components */
    public function bagianAwalComponents(): array
    {
        return [
            'cover' => [
                'key'         => 'cover',
                'name'        => 'Halaman Sampul (Cover)',
                'emoji'       => '📷',
                'badge'       => 'Identitas Visual',
                'desc'        => 'Halaman sampul depan dan identitas visual modul pembelajaran',
                'is_active'   => $this->isInfoComponentActive('cover'),
                'edit_url'    => route('teacher.modules.bagian-awal.edit', $this) . '#sec-cover',
                'toggle_url'  => route('teacher.modules.bagian-awal.toggle', [$this, 'cover']),
                'preview_url' => null,
            ],
            'kata_pengantar' => [
                'key'         => 'kata_pengantar',
                'name'        => 'Kata Pengantar',
                'emoji'       => '✏️',
                'badge'       => 'Prakata Guru',
                'desc'        => 'Sambutan pembuka dan kata pengantar modul dari guru pengampu',
                'is_active'   => $this->isInfoComponentActive('kata_pengantar'),
                'edit_url'    => route('teacher.modules.bagian-awal.edit', $this) . '#sec-kata',
                'toggle_url'  => route('teacher.modules.bagian-awal.toggle', [$this, 'kata_pengantar']),
                'preview_url' => null,
            ],
            'daftar_isi' => [
                'key'         => 'daftar_isi',
                'name'        => 'Daftar Isi',
                'emoji'       => '📋',
                'badge'       => 'Navigasi Modul',
                'desc'        => 'Daftar isi dan struktur navigasi halaman modul pembelajaran',
                'is_active'   => $this->isInfoComponentActive('daftar_isi'),
                'edit_url'    => route('teacher.modules.bagian-awal.edit', $this) . '#sec-daftar',
                'toggle_url'  => route('teacher.modules.bagian-awal.toggle', [$this, 'daftar_isi']),
                'preview_url' => null,
            ],
            'petunjuk_penggunaan' => [
                'key'         => 'petunjuk_penggunaan',
                'name'        => 'Petunjuk Penggunaan',
                'emoji'       => '💡',
                'badge'       => 'Panduan Belajar',
                'desc'        => 'Petunjuk penggunaan e-modul bagi siswa dan guru',
                'is_active'   => $this->isInfoComponentActive('petunjuk_penggunaan'),
                'edit_url'    => route('teacher.modules.bagian-awal.edit', $this) . '#sec-petunjuk',
                'toggle_url'  => route('teacher.modules.bagian-awal.toggle', [$this, 'petunjuk_penggunaan']),
                'preview_url' => null,
            ],
        ];
    }

    /** 2. Pendahuluan Components */
    public function pendahuluanComponents(): array
    {
        return [
            'tujuan_pembelajaran' => [
                'key'         => 'tujuan_pembelajaran',
                'name'        => 'Tujuan Pembelajaran & Capaian',
                'emoji'       => '🎯',
                'badge'       => 'Target Capaian',
                'desc'        => 'Rumusan capaian pembelajaran, tujuan, dan kompetensi yang harus dicapai',
                'is_active'   => $this->isInfoComponentActive('tujuan_pembelajaran'),
                'edit_url'    => route('teacher.modules.pendahuluan.edit', $this) . '#sec-tujuan',
                'toggle_url'  => route('teacher.modules.pendahuluan.toggle', [$this, 'tujuan_pembelajaran']),
                'preview_url' => null,
            ],
            'peta_konsep' => [
                'key'         => 'peta_konsep',
                'name'        => 'Peta Konsep',
                'emoji'       => '🗺️',
                'badge'       => 'Alur Konsep',
                'desc'        => 'Diagram alur konsep materi dan keterkaitan kompetensi',
                'is_active'   => $this->isInfoComponentActive('peta_konsep'),
                'edit_url'    => route('teacher.modules.pendahuluan.edit', $this) . '#sec-peta',
                'toggle_url'  => route('teacher.modules.pendahuluan.toggle', [$this, 'peta_konsep']),
                'preview_url' => null,
            ],
            'glosarium' => [
                'key'         => 'glosarium',
                'name'        => 'Glosarium',
                'emoji'       => '📖',
                'badge'       => 'Istilah Penting',
                'desc'        => 'Daftar istilah penting dan definisi kata kunci materi pembelajaran',
                'is_active'   => $this->isInfoComponentActive('glosarium'),
                'edit_url'    => route('teacher.modules.pendahuluan.edit', $this) . '#sec-glosarium',
                'toggle_url'  => route('teacher.modules.pendahuluan.toggle', [$this, 'glosarium']),
                'preview_url' => null,
            ],
            'pre_test' => [
                'key'         => 'pre_test',
                'name'        => 'Pre-test (Soal Latihan Diagnostik)',
                'emoji'       => '⚡',
                'badge'       => 'Soal Latihan Awal',
                'desc'        => 'Soal latihan awal dan diagnostik kemampuan prasyarat siswa',
                'is_active'   => (bool)$this->has_pre_test,
                'edit_url'    => route('teacher.modules.pre-test.edit', $this),
                'toggle_url'  => route('teacher.modules.pre-test.toggle', $this),
                'preview_url' => route('teacher.modules.pre-test.preview', $this),
            ],
        ];
    }

    /** 3. Kegiatan Belajar (Isi Materi) Components */
    public function kegiatanBelajarComponents(): array
    {
        return [
            'materi' => [
                'key'         => 'materi',
                'name'        => 'Uraian Materi Pembelajaran & PPT',
                'emoji'       => '📖',
                'badge'       => 'Uraian Materi',
                'desc'        => 'Uraian materi pembelajaran berbasis teks, slide PPT interaktif, & ringkasan materi',
                'is_active'   => (bool)$this->has_materi,
                'edit_url'    => route('teacher.modules.materi.edit', $this),
                'toggle_url'  => route('teacher.modules.materi.toggle', $this),
                'preview_url' => route('teacher.modules.materi.preview', $this),
            ],
            'video' => [
                'key'         => 'video',
                'name'        => 'Multimedia Video Pembelajaran',
                'emoji'       => '▶️',
                'badge'       => 'Elemen Multimedia',
                'desc'        => 'Tautan video pembelajaran interaktif (YouTube) dengan ringkasan otomatis',
                'is_active'   => (bool)$this->has_video,
                'edit_url'    => route('teacher.modules.video.edit', $this),
                'toggle_url'  => route('teacher.modules.video.toggle', $this),
                'preview_url' => route('teacher.modules.video.preview', $this),
            ],
        ];
    }

    /** 4. Evaluasi & Latihan Components */
    public function evaluasiLatihanComponents(): array
    {
        return [
            'embed' => [
                'key'         => 'embed',
                'name'        => 'Game Edukasi & Media Interaktif',
                'emoji'       => '🎮',
                'badge'       => 'Kuis & Game Online',
                'desc'        => 'Game edukasi interaktif, kuis online (Wordwall, Quizizz), & simulator embed',
                'is_active'   => (bool)$this->has_embed,
                'edit_url'    => route('teacher.modules.embed.edit', $this),
                'toggle_url'  => route('teacher.modules.embed.toggle', $this),
                'preview_url' => route('teacher.modules.embed.preview', $this),
            ],
            'job_sheet' => [
                'key'         => 'job_sheet',
                'name'        => 'Lembar Kerja Praktik (Job Sheet)',
                'emoji'       => '📑',
                'badge'       => 'Lembar Kerja',
                'desc'        => 'Lembar kerja petunjuk instruksi praktikum siswa (file PDF)',
                'is_active'   => (bool)$this->has_job_sheet,
                'edit_url'    => route('teacher.modules.job-sheet.edit', $this),
                'toggle_url'  => route('teacher.modules.job-sheet.toggle', $this),
                'preview_url' => route('teacher.modules.job-sheet.preview', $this),
            ],
            'lkpd' => [
                'key'         => 'lkpd',
                'name'        => 'Tugas LKPD & Umpan Balik',
                'emoji'       => '👥',
                'badge'       => 'Umpan Balik (Feedback)',
                'desc'        => 'Lembar kerja peserta didik (kelompok/individu), pengumpulan tugas & umpan balik nilai',
                'is_active'   => (bool)$this->has_lkpd,
                'edit_url'    => route('teacher.modules.lkpd.edit', $this),
                'toggle_url'  => route('teacher.modules.lkpd.toggle', $this),
                'preview_url' => route('teacher.modules.lkpd.preview', $this),
            ],
        ];
    }

    /** 5. Bagian Akhir Components */
    public function bagianAkhirComponents(): array
    {
        return [
            'post_test' => [
                'key'         => 'post_test',
                'name'        => 'Post-test (Tes Akhir Modul)',
                'emoji'       => '🎯',
                'badge'       => 'Tes Akhir Modul',
                'desc'        => 'Tes akhir modul & uji kompetensi untuk evaluasi ketercapaian belajar siswa',
                'is_active'   => (bool)$this->has_post_test,
                'edit_url'    => route('teacher.modules.post-test.edit', $this),
                'toggle_url'  => route('teacher.modules.post-test.toggle', $this),
                'preview_url' => route('teacher.modules.post-test.preview', $this),
            ],
            'daftar_pustaka' => [
                'key'         => 'daftar_pustaka',
                'name'        => 'Daftar Pustaka',
                'emoji'       => '📚',
                'badge'       => 'Kepustakaan & Rujukan',
                'desc'        => 'Daftar pustaka, referensi buku, jurnal ilmiah, dan sumber bacaan penyusun',
                'is_active'   => $this->isInfoComponentActive('daftar_pustaka'),
                'edit_url'    => route('teacher.modules.daftar-pustaka.edit', $this),
                'toggle_url'  => route('teacher.modules.daftar-pustaka.toggle', $this),
                'preview_url' => null,
            ],
        ];
    }

    /** Ringkasan 5 Bagian Standar E-Modul */
    public function moduleSectionsSummary(): array
    {
        $sec1 = $this->bagianAwalComponents();
        $sec2 = $this->pendahuluanComponents();
        $sec3 = $this->kegiatanBelajarComponents();
        $sec4 = $this->evaluasiLatihanComponents();
        $sec5 = $this->bagianAkhirComponents();

        return [
            'bagian_awal' => [
                'id'           => 'sec-bagian-awal',
                'number'       => 1,
                'title'        => 'Bagian Awal',
                'subtitle'     => 'Halaman sampul (cover), kata pengantar, daftar isi, serta petunjuk penggunaan e-modul bagi siswa dan guru.',
                'theme'        => 'indigo',
                'header_bg'    => 'bg-indigo-600',
                'badge_color'  => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                'icon_bg'      => 'bg-indigo-100 text-indigo-700',
                'components'   => $sec1,
                'active_count' => collect($sec1)->filter(fn($c) => $c['is_active'])->count(),
                'total_count'  => count($sec1),
                'edit_all_url' => route('teacher.modules.bagian-awal.edit', $this),
                'edit_all_label' => 'Edit 4 Komponen Bagian Awal',
            ],
            'pendahuluan' => [
                'id'           => 'sec-pendahuluan',
                'number'       => 2,
                'title'        => 'Pendahuluan',
                'subtitle'     => 'Rumusan capaian pembelajaran, tujuan pembelajaran, alur konsep materi, glosarium, dan pre-test diagnostik.',
                'theme'        => 'teal',
                'header_bg'    => 'bg-teal-600',
                'badge_color'  => 'bg-teal-100 text-teal-800 border-teal-200',
                'icon_bg'      => 'bg-teal-100 text-teal-700',
                'components'   => $sec2,
                'active_count' => collect($sec2)->filter(fn($c) => $c['is_active'])->count(),
                'total_count'  => count($sec2),
                'edit_all_url' => route('teacher.modules.pendahuluan.edit', $this),
                'edit_all_label' => 'Edit 3 Komponen Pendahuluan',
            ],
            'kegiatan_belajar' => [
                'id'           => 'sec-kegiatan-belajar',
                'number'       => 3,
                'title'        => 'Kegiatan Belajar (Isi Materi)',
                'subtitle'     => 'Uraian materi pembelajaran berbasis teks, slide PPT interaktif, dan multimedia video.',
                'theme'        => 'blue',
                'header_bg'    => 'bg-blue-600',
                'badge_color'  => 'bg-blue-100 text-blue-800 border-blue-200',
                'icon_bg'      => 'bg-blue-100 text-blue-700',
                'components'   => $sec3,
                'active_count' => collect($sec3)->filter(fn($c) => $c['is_active'])->count(),
                'total_count'  => count($sec3),
                'edit_all_url' => null,
                'edit_all_label' => null,
            ],
            'evaluasi_latihan' => [
                'id'           => 'sec-evaluasi-latihan',
                'number'       => 4,
                'title'        => 'Evaluasi & Latihan',
                'subtitle'     => 'Game edukasi interaktif, lembar kerja praktik (job sheet), serta tugas lembar kerja peserta didik (LKPD).',
                'theme'        => 'amber',
                'header_bg'    => 'bg-amber-600',
                'badge_color'  => 'bg-amber-100 text-amber-800 border-amber-200',
                'icon_bg'      => 'bg-amber-100 text-amber-800',
                'components'   => $sec4,
                'active_count' => collect($sec4)->filter(fn($c) => $c['is_active'])->count(),
                'total_count'  => count($sec4),
                'edit_all_url' => null,
                'edit_all_label' => null,
            ],
            'bagian_akhir' => [
                'id'           => 'sec-bagian-akhir',
                'number'       => 5,
                'title'        => 'Bagian Akhir',
                'subtitle'     => 'Rangkuman materi, tes akhir modul, daftar pustaka, dan profil pengembang.',
                'theme'        => 'rose',
                'header_bg'    => 'bg-rose-600',
                'badge_color'  => 'bg-rose-100 text-rose-800 border-rose-200',
                'icon_bg'      => 'bg-rose-100 text-rose-700',
                'components'   => $sec5,
                'active_count' => collect($sec5)->filter(fn($c) => $c['is_active'])->count(),
                'total_count'  => count($sec5),
                'edit_all_url' => null,
                'edit_all_label' => null,
            ],
        ];
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

    /**
     * Melakukan kloning modul secara menyeluruh (deep copy) ke guru target
     * tanpa menyalin data penilaian/pengumpulan siswa.
     */
    public function cloneToTeacher(Teacher $targetTeacher, int $targetClassId, ?string $newTitle = null): self
    {
        $newTitle = $newTitle ?: $this->title . ' (Salinan)';

        // 1. Duplikasi record utama modul
        $cloned = self::create([
            'teacher_id'          => $targetTeacher->id,
            'class_id'            => $targetClassId,
            'title'               => $newTitle,
            'informasi_umum_data' => $this->informasi_umum_data,
            'bagian_akhir_data'   => $this->bagian_akhir_data,
            'pre_test_data'       => $this->pre_test_data,
            'materi_data'         => $this->materi_data,
            'video_data'          => $this->video_data,
            'embed_data'          => $this->embed_data,
            'job_sheet_data'      => $this->job_sheet_data,
            'lkpd_data'           => $this->lkpd_data,
            'post_test_data'      => $this->post_test_data,
            'has_pre_test'        => $this->has_pre_test,
            'has_materi'          => $this->has_materi,
            'has_video'           => $this->has_video,
            'has_embed'           => $this->has_embed,
            'has_job_sheet'       => $this->has_job_sheet,
            'has_lkpd'            => $this->has_lkpd,
            'has_post_test'       => $this->has_post_test,
            'status'              => 'draft',
            'is_shared'           => false,
            'shared_at'           => null,
            'cloned_from_id'      => $this->id,
            'clone_count'         => 0,
        ]);

        // 2. Duplikasi Pre-test & Butir Soal jika ada
        if ($this->preTest) {
            $newPreTest = PreTest::create([
                'module_id'           => $cloned->id,
                'title'               => $this->preTest->title,
                'duration_minutes'    => $this->preTest->duration_minutes,
                'kktp'                => $this->preTest->kktp,
                'instructions'        => $this->preTest->instructions,
                'randomize_questions' => $this->preTest->randomize_questions,
            ]);

            foreach ($this->preTest->questions as $q) {
                PreTestQuestion::create([
                    'pre_test_id'    => $newPreTest->id,
                    'question_text'  => $q->question_text,
                    'options'        => $q->options,
                    'correct_answer' => $q->correct_answer,
                    'score_weight'   => $q->score_weight,
                    'explanation'    => $q->explanation,
                    'order_num'      => $q->order_num,
                ]);
            }
        }

        // 3. Duplikasi Post-test & Butir Soal jika ada
        if ($this->postTest) {
            $newPostTest = PostTest::create([
                'module_id'           => $cloned->id,
                'title'               => $this->postTest->title,
                'duration_minutes'    => $this->postTest->duration_minutes,
                'kktp'                => $this->postTest->kktp,
                'instructions'        => $this->postTest->instructions,
                'randomize_questions' => $this->postTest->randomize_questions,
            ]);

            foreach ($this->postTest->questions as $q) {
                PostTestQuestion::create([
                    'post_test_id'   => $newPostTest->id,
                    'question_text'  => $q->question_text,
                    'options'        => $q->options,
                    'correct_answer' => $q->correct_answer,
                    'score_weight'   => $q->score_weight,
                    'explanation'    => $q->explanation,
                    'order_num'      => $q->order_num,
                ]);
            }
        }

        // 4. Duplikasi Job Sheets jika ada
        foreach ($this->jobSheets as $js) {
            JobSheet::create([
                'module_id'     => $cloned->id,
                'pdf_file_path' => $js->pdf_file_path,
            ]);
        }

        // 5. Duplikasi LKPD jika ada
        foreach ($this->lkpds as $lkpd) {
            Lkpd::create([
                'module_id'     => $cloned->id,
                'pdf_file_path' => $lkpd->pdf_file_path,
            ]);
        }

        // 6. Tingkatkan counter kloning pada modul sumber
        $this->increment('clone_count');

        return $cloned;
    }
}
