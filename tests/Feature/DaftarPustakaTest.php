<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Teacher;
use Tests\TestCase;

class DaftarPustakaTest extends TestCase
{
    public function test_teacher_can_view_daftar_pustaka_edit_page()
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
            ->get(route('teacher.modules.daftar-pustaka.edit', $module));

        $response->assertStatus(200);
        $response->assertSee('Editor Daftar Pustaka Modul');
        $response->assertSee('Judul Buku / Sumber');
        $response->assertSee('Penulis / Penerbit');
        $response->assertSee('Tahun');
    }

    public function test_teacher_can_update_daftar_pustaka_and_preserve_other_sections()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        // Set initial data in Bagian Awal & Pendahuluan
        $module->update([
            'informasi_umum_data' => [
                'kata_pengantar'      => 'Pengantar Awal Teruji',
                'tujuan_pembelajaran' => 'Tujuan Pembelajaran Teruji',
                'daftar_pustaka'      => [],
            ]
        ]);

        $payload = [
            'daftar_pustaka' => [
                [
                    'judul'   => 'Buku Basis Data Terapan',
                    'penulis' => 'Penerbit IT',
                    'tahun'   => '2024',
                    'tautan'  => 'https://example.com/buku',
                ],
                [
                    'judul'   => 'Dokumentasi Web Laravel',
                    'penulis' => 'Taylor Otwell',
                    'tahun'   => '2024',
                    'tautan'  => 'https://laravel.com/docs',
                ],
            ],
        ];

        $response = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.daftar-pustaka.update', $module), $payload);

        $response->assertRedirect(route('teacher.modules.show', $module));
        $response->assertSessionHas('success');

        $module->refresh();
        $infoData = $module->informasi_umum_data;

        // Verify other sections are preserved intact
        $this->assertEquals('Pengantar Awal Teruji', $infoData['kata_pengantar']);
        $this->assertEquals('Tujuan Pembelajaran Teruji', $infoData['tujuan_pembelajaran']);

        // Verify Daftar Pustaka updated accurately
        $this->assertCount(2, $infoData['daftar_pustaka']);
        $this->assertEquals('Buku Basis Data Terapan', $infoData['daftar_pustaka'][0]['judul']);
        $this->assertEquals('Taylor Otwell', $infoData['daftar_pustaka'][1]['penulis']);
    }

    public function test_teacher_can_toggle_daftar_pustaka_component()
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
            ->post(route('teacher.modules.daftar-pustaka.toggle', $module));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $module->refresh();
        $infoData = $module->informasi_umum_data;
        $this->assertArrayHasKey('toggles', $infoData);
        $this->assertArrayHasKey('daftar_pustaka', $infoData['toggles']);
    }
}
