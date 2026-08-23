<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Module;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login.admin'));
    }

    public function test_authenticated_admin_can_access_dashboard_with_real_time_data()
    {
        $admin = Admin::first();
        if (!$admin) {
            $this->markTestSkipped('Admin data not seeded.');
        }

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Supervisi', false);
        $response->assertSee('Real-Time Monitor', false);
        $response->assertSee('Total Guru Aktif', false);
        $response->assertSee('Total Peserta Didik', false);
        $response->assertSee('E-Modul Terbit', false);
        $response->assertSee('Monitoring Produktivitas Guru', false);
        $response->assertSee('Aktivitas Siswa Terbaru', false);
    }

    public function test_admin_dashboard_displays_kpi_metrics_and_curriculum_stats()
    {
        $admin = Admin::first();
        if (!$admin) {
            $this->markTestSkipped('Admin data not seeded.');
        }

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Distribusi Modul per Mata Pelajaran', false);
        $response->assertSee('Rombongan Belajar (Kelas)', false);
    }
}
