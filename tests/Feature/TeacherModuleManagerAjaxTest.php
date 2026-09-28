<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Teacher;
use Tests\TestCase;

class TeacherModuleManagerAjaxTest extends TestCase
{
    public function test_teacher_can_update_module_status_via_ajax()
    {
        $teacher = Teacher::first();
        $module = $teacher ? $teacher->modules()->first() : null;
        if (!$teacher || !$module) {
            $this->markTestSkipped('Teacher / module seed required.');
        }

        // Test published status via AJAX
        $response = $this->actingAs($teacher, 'teacher')
            ->patchJson(route('teacher.modules.status', $module), [
                'status' => 'published',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status'  => 'published',
            'message' => 'Modul berhasil dipublikasikan dan dapat diakses siswa!',
        ]);

        $this->assertEquals('published', $module->fresh()->status);

        // Test closed status via AJAX
        $response = $this->actingAs($teacher, 'teacher')
            ->patchJson(route('teacher.modules.status', $module), [
                'status' => 'closed',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status'  => 'closed',
            'message' => 'Modul ditutup dan tidak bisa diakses siswa.',
        ]);

        $this->assertEquals('closed', $module->fresh()->status);
    }

    public function test_teacher_can_toggle_share_via_ajax()
    {
        $teacher = Teacher::first();
        $module = $teacher ? $teacher->modules()->first() : null;
        if (!$teacher || !$module) {
            $this->markTestSkipped('Teacher / module seed required.');
        }

        $initialShared = (bool) $module->is_shared;

        $response = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.modules.toggle-share', $module));

        $response->assertStatus(200);
        $response->assertJson([
            'success'   => true,
            'is_shared' => !$initialShared,
        ]);

        $this->assertEquals(!$initialShared, (bool) $module->fresh()->is_shared);

        // Revert back
        $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.modules.toggle-share', $module));
        $this->assertEquals($initialShared, (bool) $module->fresh()->is_shared);
    }

    public function test_teacher_can_delete_module_via_ajax()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher seed required.');
        }

        // Create temporary module to delete
        $module = Module::create([
            'teacher_id'      => $teacher->id,
            'class_id'        => $teacher->classes()->first()?->id ?? 1,
            'subject_id'      => $teacher->subjects()->first()?->id ?? 1,
            'title'           => 'Temporary Test Module for AJAX Deletion',
            'semester'        => '1',
            'status'          => 'draft',
            'has_materi'      => false,
            'has_video'       => false,
            'has_embed'       => false,
            'has_job_sheet'   => false,
            'has_lkpd'        => false,
            'has_pre_test'    => false,
            'has_post_test'   => false,
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->deleteJson(route('teacher.modules.destroy', $module));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Modul berhasil dihapus.',
        ]);

        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
    }

    public function test_teacher_can_toggle_components_via_ajax()
    {
        $teacher = Teacher::first();
        $module = $teacher ? $teacher->modules()->first() : null;
        if (!$teacher || !$module) {
            $this->markTestSkipped('Teacher / module seed required.');
        }

        // Test toggle Materi
        $initialMateri = (bool) $module->has_materi;
        $response = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.modules.materi.toggle', $module));

        $response->assertStatus(200);
        $response->assertJson([
            'success'    => true,
            'has_materi' => !$initialMateri,
        ]);
        $this->assertEquals(!$initialMateri, (bool) $module->fresh()->has_materi);

        // Revert back
        $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.modules.materi.toggle', $module));

        // Test toggle Bagian Awal (Kata Pengantar)
        $response = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.modules.bagian-awal.toggle', ['module' => $module, 'component' => 'kata_pengantar']));

        $response->assertStatus(200);
        $response->assertJson([
            'success'   => true,
            'component' => 'kata_pengantar',
        ]);
    }
}
