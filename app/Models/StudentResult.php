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
        'read_components'  => 'array',
        'test_attempts'    => 'array',
    ];

    /** Batas maksimal riwayat pengerjaan yang disimpan di database */
    public const MAX_STORED_ATTEMPTS = 3;

    /** Mendapatkan daftar riwayat pengerjaan tes ('pre_test' atau 'post_test') dibatasi maksimal 3 */
    public function getTestAttempts(string $testType): array
    {
        $attempts = $this->test_attempts ?? [];
        $list = $attempts[$testType] ?? [];

        if (count($list) > self::MAX_STORED_ATTEMPTS) {
            $hasInitial = !empty($list[0]['is_initial']);
            if ($hasInitial) {
                $initialAttempt = $list[0];
                $recentAttempts = array_slice($list, -(self::MAX_STORED_ATTEMPTS - 1));
                return array_values(array_merge([$initialAttempt], $recentAttempts));
            }
            return array_values(array_slice($list, -self::MAX_STORED_ATTEMPTS));
        }

        return $list;
    }

    /** Jumlah total percobaan pengerjaan tes (maksimal 3) */
    public function getTestAttemptCount(string $testType): int
    {
        $attempts = $this->getTestAttempts($testType);
        if (!empty($attempts)) {
            return count($attempts);
        }
        $initial = $testType === 'pre_test' ? $this->pre_test_score : $this->post_test_score;
        return $initial !== null ? 1 : 0;
    }

    /** Mendapatkan nilai latihan pengulangan terbaru (jika sudah ada pengulangan) */
    public function getLatestRetakeScore(string $testType): ?int
    {
        $attempts = $this->getTestAttempts($testType);
        if (count($attempts) > 1) {
            $latest = end($attempts);
            return isset($latest['score']) ? (int) $latest['score'] : null;
        }
        return null;
    }

    /** Mencatat percobaan pengerjaan tes baru dengan mengunci nilai awal tes dan membatasi maksimal 3 riwayat */
    public function recordTestAttempt(string $testType, int $score, int $correctCount, int $totalQuestions): array
    {
        $attemptsData = $this->test_attempts ?? [];
        $typeAttempts = $attemptsData[$testType] ?? [];
        $isFirst = false;

        if ($testType === 'pre_test') {
            if ($this->pre_test_score === null) {
                $this->pre_test_score = $score;
                $isFirst = true;
            }
        } elseif ($testType === 'post_test') {
            if ($this->post_test_score === null) {
                $this->post_test_score = $score;
                $isFirst = true;
            }
        }

        // Jika data riwayat lama belum ada tetapi sudah punya skor awal
        if (empty($typeAttempts)) {
            $initialScore = $testType === 'pre_test' ? $this->pre_test_score : $this->post_test_score;
            if (!$isFirst && $initialScore !== null) {
                $typeAttempts[] = [
                    'attempt'       => 1,
                    'score'         => (int) $initialScore,
                    'correct_count' => null,
                    'total'         => $totalQuestions,
                    'timestamp'     => $this->created_at ? $this->created_at->toIso8601String() : now()->toIso8601String(),
                    'is_initial'    => true,
                ];
            }
        }

        $attemptNumber = count($typeAttempts) + 1;
        $typeAttempts[] = [
            'attempt'       => $attemptNumber,
            'score'         => $score,
            'correct_count' => $correctCount,
            'total'         => $totalQuestions,
            'timestamp'     => now()->toIso8601String(),
            'is_initial'    => $isFirst,
        ];

        // Batasi riwayat maksimal 3 pengerjaan agar hemat database.
        // Pertahankan percobaan awal resmi (is_initial) dan 2 percobaan terbaru.
        if (count($typeAttempts) > self::MAX_STORED_ATTEMPTS) {
            $hasInitial = !empty($typeAttempts[0]['is_initial']);
            if ($hasInitial) {
                $initialAttempt = $typeAttempts[0];
                $recentAttempts = array_slice($typeAttempts, -(self::MAX_STORED_ATTEMPTS - 1));
                $typeAttempts = array_values(array_merge([$initialAttempt], $recentAttempts));
            } else {
                $typeAttempts = array_values(array_slice($typeAttempts, -self::MAX_STORED_ATTEMPTS));
            }
        }

        $attemptsData[$testType] = $typeAttempts;
        $this->test_attempts = $attemptsData;

        return [
            'attempt'    => $attemptNumber,
            'is_initial' => $isFirst,
        ];
    }

    /** Memeriksa apakah komponen sudah dibaca oleh siswa */
    public function isComponentRead(string $component): bool
    {
        $reads = $this->read_components ?? [];
        return in_array($component, $reads, true);
    }

    /** Menandai komponen sebagai sudah dibaca */
    public function markComponentRead(string $component): void
    {
        $reads = $this->read_components ?? [];
        if (!in_array($component, $reads, true)) {
            $reads[] = $component;
            $this->read_components = $reads;
            $this->save();
        }
    }

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

        // Catatan: Pre-test sengaja tidak diikutsertakan dalam akumulasi nilai akhir,
        // karena murni berfungsi sebagai asesmen diagnostik awal untuk memetakan pemahaman materi.
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
