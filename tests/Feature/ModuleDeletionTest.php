<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Tests\TestCase;

class ModuleDeletionTest extends TestCase
{
    public function test_modules_index_contains_delete_modal_markup()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.index'));

        $response->assertStatus(200);
        $response->assertSee('Hapus E-Modul Pembelajaran?', false);
        $response->assertSee('Ya, Hapus Modul Permanen', false);
        $response->assertSee('deleteModalOpen', false);
    }

    public function test_module_show_contains_delete_modal_markup()
    {
        $teacher = Teacher::first();
        $module = $teacher ? $teacher->modules()->first() : null;
        if (!$teacher || !$module) {
            $this->markTestSkipped('Teacher / module seed required.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.show', $module));

        $response->assertStatus(200);
        $response->assertSee('Hapus E-Modul Pembelajaran?', false);
        $response->assertSee('Ya, Hapus Modul Permanen', false);
    }

    public function test_teacher_dashboard_contains_delete_modal_markup()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Hapus E-Modul Pembelajaran?', false);
        $response->assertSee('Ya, Hapus Modul Permanen', false);
    }

    public function test_teacher_can_delete_own_module()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Teacher / class seed required.');
        }

        // Buat modul sementara untuk pengujian hapus
        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'title'      => 'Modul Uji Hapus ' . uniqid(),
            'status'     => 'draft',
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->delete(route('teacher.modules.destroy', $module));

        $response->assertRedirect(route('teacher.modules.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
    }

    public function test_teacher_cannot_delete_other_teacher_module()
    {
        $teachers = Teacher::take(2)->get();
        if ($teachers->count() < 2) {
            $this->markTestSkipped('At least 2 teachers required.');
        }

        $teacher1 = $teachers[0];
        $teacher2 = $teachers[1];
        $class = SchoolClass::first();

        // Modul milik guru 2
        $module = Module::create([
            'teacher_id' => $teacher2->id,
            'class_id'   => $class->id,
            'title'      => 'Modul Guru 2 ' . uniqid(),
            'status'     => 'draft',
        ]);

        // Guru 1 mencoba menghapus modul milik guru 2
        $response = $this->actingAs($teacher1, 'teacher')
            ->delete(route('teacher.modules.destroy', $module));

        $response->assertStatus(403);
        $this->assertDatabaseHas('modules', ['id' => $module->id]);

        // Cleanup
        $module->delete();
    }
}
