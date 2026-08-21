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
        $response->assertSee('Halaman Sampul (Cover)');
        $response->assertSee('Kata Pengantar');
        $response->assertSee('Daftar Isi (Navigasi Modul)');
        $response->assertSee('Petunjuk Penggunaan');
    }

    public function test_teacher_can_update_bagian_awal_and_preserve_other_sections(): void
    {
        Storage::fake('public');

        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $file = UploadedFile::fake()->image('cover_modul.jpg', 600, 800);

        $payload = [
            'cover_image'         => $file,
            'kata_pengantar'      => 'Kata pengantar baru yang lebih komprehensif untuk siswa.',
            'petunjuk_penggunaan' => '1. Baca modul secara berurutan. 2. Kerjakan tugas LKPD.',
            'daftar_isi'          => [
                ['judul' => 'Bab 1: Pengenalan HTML & CSS', 'anchor' => '#bab-1'],
                ['judul' => 'Bab 2: Desain Web Responsif', 'anchor' => '#bab-2'],
            ],
        ];

        $response = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.bagian-awal.update', $module), $payload);

        $response->assertRedirect(route('teacher.modules.show', $module));
        $response->assertSessionHas('success');

        $module->refresh();
        $infoData = $module->informasi_umum_data;

        // Verify Bagian Awal updated
        $this->assertNotNull($infoData['cover_image_path']);
        Storage::disk('public')->assertExists($infoData['cover_image_path']);
        $this->assertEquals('Kata pengantar baru yang lebih komprehensif untuk siswa.', $infoData['kata_pengantar']);
        $this->assertEquals('1. Baca modul secara berurutan. 2. Kerjakan tugas LKPD.', $infoData['petunjuk_penggunaan']);
        $this->assertCount(2, $infoData['daftar_isi']);
        $this->assertEquals('Bab 2: Desain Web Responsif', $infoData['daftar_isi'][1]['judul']);
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
