<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan hanya ada maksimal 1 modul aktif per kelas (pergantian mapel di kelas)
        $classesWithActiveModules = DB::table('modules')
            ->select('class_id')
            ->where('is_active', true)
            ->whereNotNull('class_id')
            ->groupBy('class_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('class_id');

        foreach ($classesWithActiveModules as $classId) {
            $latestActiveModuleId = DB::table('modules')
                ->where('class_id', $classId)
                ->where('is_active', true)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->value('id');

            if ($latestActiveModuleId) {
                DB::table('modules')
                    ->where('class_id', $classId)
                    ->where('id', '!=', $latestActiveModuleId)
                    ->update(['is_active' => false]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversible operation needed
    }
};
