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
        Schema::table('modules', function (Blueprint $table) {
            $table->boolean('is_shared')->default(false)->after('status')->index();
            $table->timestamp('shared_at')->nullable()->after('is_shared');
            $table->foreignId('cloned_from_id')->nullable()->after('shared_at')->constrained('modules')->nullOnDelete();
            $table->unsignedInteger('clone_count')->default(0)->after('cloned_from_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['cloned_from_id']);
            $table->dropColumn(['is_shared', 'shared_at', 'cloned_from_id', 'clone_count']);
        });
    }
};
