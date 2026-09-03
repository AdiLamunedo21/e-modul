<?php

use App\Models\StudentResult;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membersihkan dan membatasi riwayat percobaan yang melebihi 3x pada data yang sudah ada.
     */
    public function up(): void
    {
        StudentResult::whereNotNull('test_attempts')->each(function ($result) {
            $attemptsData = $result->test_attempts;
            if (!is_array($attemptsData)) {
                return;
            }

            $changed = false;
            foreach (['pre_test', 'post_test'] as $type) {
                if (isset($attemptsData[$type]) && is_array($attemptsData[$type]) && count($attemptsData[$type]) > StudentResult::MAX_STORED_ATTEMPTS) {
                    $list = $attemptsData[$type];
                    $hasInitial = !empty($list[0]['is_initial']);
                    if ($hasInitial) {
                        $initialAttempt = $list[0];
                        $recentAttempts = array_slice($list, -(StudentResult::MAX_STORED_ATTEMPTS - 1));
                        $attemptsData[$type] = array_values(array_merge([$initialAttempt], $recentAttempts));
                    } else {
                        $attemptsData[$type] = array_values(array_slice($list, -StudentResult::MAX_STORED_ATTEMPTS));
                    }
                    $changed = true;
                }
            }

            if ($changed) {
                $result->test_attempts = $attemptsData;
                $result->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pruning is irreversible
    }
};
