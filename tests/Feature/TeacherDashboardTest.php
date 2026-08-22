<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Teacher;
use Tests\TestCase;

class TeacherDashboardTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_teacher_dashboard()
    {
        $response = $this->get(route('teacher.dashboard'));
        $response->assertRedirect(route('login.teacher'));
    }

    public function test_authenticated_teacher_can_access_dashboard_with_dynamic_data()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang', false);
        $response->assertSee($teacher->name);
        $response->assertSee('E-Modul Terbaru & Draf Pengerjaan', false);
        $response->assertSee('Perpustakaan Modul', false);
        $response->assertSee('Grading Center', false);
        $response->assertSee('Rekap Nilai Excel', false);
        $response->assertSee('Kelas Binaan', false);
    }

    public function test_teacher_dashboard_status_filters_work()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        // Test filter status published
        $responsePub = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.dashboard', ['status' => 'published']));
        $responsePub->assertStatus(200);

        // Test filter status draft
        $responseDraft = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.dashboard', ['status' => 'draft']));
        $responseDraft->assertStatus(200);

        // Test filter status shared
        $responseShared = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.dashboard', ['status' => 'shared']));
        $responseShared->assertStatus(200);
    }

    public function test_teacher_dashboard_renders_stats_and_sections()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total E-Modul Saya', false);
        $response->assertSee('Siswa Binaan', false);
        $response->assertSee('Perlu Dinilai (Grading)', false);
        $response->assertSee('Rata-rata Skor Sumatif', false);
        $response->assertSee('Arsitektur E-Modul 5 Bagian Pedagogis', false);
    }
}
