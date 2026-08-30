<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLibraryTest extends TestCase
{
    private function getAdmin(): Admin
    {
        $admin = Admin::first();
        if (!$admin) {
            $admin = Admin::create([
                'name'            => 'Admin Supervisi',
                'identity_number' => 'ADM_LIB_TEST',
                'password'        => Hash::make('password'),
            ]);
        }
        return $admin;
    }

    public function test_unauthenticated_admin_cannot_access_library()
    {
        $response = $this->get(route('admin.library.index'));
        $response->assertRedirect(route('login.admin'));
    }

    public function test_admin_can_access_library_index_and_see_shared_modules()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.library.index'));

        $response->assertStatus(200);
        $response->assertSee('Supervisi Perpustakaan Modul', false);
        $response->assertSee('Total Adopsi / Kloning', false);
    }

    public function test_admin_can_inspect_library_module_detail()
    {
        $admin = $this->getAdmin();
        $module = Module::where('is_shared', true)->first();

        if (!$module) {
            $teacher = Teacher::first();
            $class = SchoolClass::first();
            $module = Module::create([
                'teacher_id' => $teacher->id,
                'class_id'   => $class->id,
                'title'      => 'Modul Library Bersama Inspect Test',
                'status'     => 'published',
                'is_shared'  => true,
                'shared_at'  => now(),
                'clone_count' => 2,
            ]);
        }

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.library.show', $module));

        $response->assertStatus(200);
        $response->assertSee(e($module->title), false);
        $response->assertSee('Inspeksi & Pratinjau Modul', false);
        $response->assertSee('Rincian 5 Bagian Kurikulum E-Modul', false);
    }

    public function test_admin_can_toggle_share_status()
    {
        $admin = $this->getAdmin();
        $teacher = Teacher::first();
        $class = SchoolClass::first();

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class ? $class->id : 1,
            'title'      => 'Modul Moderasi Test ' . rand(100, 999),
            'status'     => 'published',
            'is_shared'  => true,
            'shared_at'  => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.library.toggle-share', $module));

        $response->assertRedirect();
        $this->assertFalse((bool) $module->fresh()->is_shared);

        // Toggle back
        $response2 = $this->actingAs($admin, 'admin')
            ->post(route('admin.library.toggle-share', $module));

        $response2->assertRedirect();
        $this->assertTrue((bool) $module->fresh()->is_shared);

        $module->delete();
    }
}
