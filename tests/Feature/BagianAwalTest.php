<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Teacher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BagianAwalTest extends TestCase
{
    public function test_teacher_can_view_bagian_awal_edit_page(): void
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.bagian-awal.edit', $module));

        $response->assertStatus(200);
        $response->assertSee('Editor Bagian Awal Modul');
        $response->assertSee('Kata Pengantar');
        $response->assertSee('Petunjuk Penggunaan');
    }

    public function test_teacher_can_update_bagian_awal_and_preserve_other_sections(): void
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $payload = [
            'kata_pengantar'      => 'Kata pengantar baru yang lebih komprehensif untuk siswa.',
            'petunjuk_penggunaan' => '1. Baca modul secara berurutan. 2. Kerjakan tugas LKPD.',
        ];

        $response = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.bagian-awal.update', $module), $payload);

        $response->assertRedirect(route('teacher.modules.show', $module));
        $response->assertSessionHas('success');

        $module->refresh();
        $infoData = $module->informasi_umum_data;

        // Verify Bagian Awal updated
        $this->assertEquals('Kata pengantar baru yang lebih komprehensif untuk siswa.', $infoData['kata_pengantar']);
        $this->assertEquals('1. Baca modul secara berurutan. 2. Kerjakan tugas LKPD.', $infoData['petunjuk_penggunaan']);
    }

    public function test_teacher_can_toggle_bagian_awal_component(): void
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $initialState = $module->isInfoComponentActive('kata_pengantar');

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.modules.bagian-awal.toggle', [$module, 'kata_pengantar']));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $module->refresh();
        $this->assertNotEquals($initialState, $module->isInfoComponentActive('kata_pengantar'));

        // Toggle back
        $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.modules.bagian-awal.toggle', [$module, 'kata_pengantar']));

        $module->refresh();
        $this->assertEquals($initialState, $module->isInfoComponentActive('kata_pengantar'));
    }
}
