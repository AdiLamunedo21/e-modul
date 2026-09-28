<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom email pada tabel admins
        if (!Schema::hasColumn('admins', 'email')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->string('email')->nullable()->unique()->after('name');
            });
        }

        // 2. Tambah kolom email pada tabel teachers
        if (!Schema::hasColumn('teachers', 'email')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('email')->nullable()->unique()->after('name');
            });
        }

        // 3. Helper pembuat email namaSingkat@gmail.com
        $generateEmail = function (string $name, int $id, array &$usedEmails): string {
            $clean = preg_replace('/^(Drs\.|Dr\.|Ir\.|Prof\.|H\.|Hj\.|Ust\.)\s+/i', '', trim($name));
            $parts = explode(',', $clean);
            $mainName = trim($parts[0]);
            $words = preg_split('/\s+/', $mainName);
            $firstWord = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $words[0] ?? 'user'));
            if (empty($firstWord)) {
                $firstWord = 'user' . $id;
            }

            $email = $firstWord . '@gmail.com';
            $counter = 1;
            while (in_array($email, $usedEmails, true)) {
                $counter++;
                $email = $firstWord . $counter . '@gmail.com';
            }
            $usedEmails[] = $email;
            return $email;
        };

        // 4. Backfill data Admin yang sudah ada
        $usedAdminEmails = [];
        $admins = DB::table('admins')->orderBy('id')->get();
        foreach ($admins as $admin) {
            if (empty($admin->email)) {
                $email = $generateEmail($admin->name, $admin->id, $usedAdminEmails);
                DB::table('admins')->where('id', $admin->id)->update(['email' => $email]);
            } else {
                $usedAdminEmails[] = $admin->email;
            }
        }

        // 5. Backfill data Teacher yang sudah ada
        $usedTeacherEmails = [];
        $teachers = DB::table('teachers')->orderBy('id')->get();
        foreach ($teachers as $teacher) {
            if (empty($teacher->email)) {
                $email = $generateEmail($teacher->name, $teacher->id, $usedTeacherEmails);
                DB::table('teachers')->where('id', $teacher->id)->update(['email' => $email]);
            } else {
                $usedTeacherEmails[] = $teacher->email;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('admins', 'email')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

        if (Schema::hasColumn('teachers', 'email')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};
