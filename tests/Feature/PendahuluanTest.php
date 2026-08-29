<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Teacher;
use Tests\TestCase;

class PendahuluanTest extends TestCase
{
    public function test_teacher_can_view_pendahuluan_edit_page(): void
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
            ->get(route('teacher.modules.pendahuluan.edit', $module));

        $response->assertStatus(200);
        $response->assertSee('Editor Pendahuluan Modul');
        $response->assertSee('Tujuan Pembelajaran & Capaian', false);
        $response->assertSee('Peta Konsep (Alur & Hierarki Materi)', false);
        $response->assertSee('Glosarium (Istilah Teknis Penting)');
    }

    public function test_teacher_can_update_pendahuluan_and_preserve_other_sections(): void
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        // Set initial Bagian Awal and Bagian Akhir data
        $initialData = [
            'kata_pengantar'      => 'Pengantar awal yang tidak boleh terhapus.',
            'tujuan_pembelajaran' => 'Tujuan lama.',
            'peta_konsep_text'    => 'Peta konsep lama.',
            'glosarium'           => [],
            'daftar_pustaka'      => [
                ['judul' => 'Buku Referensi Laravel', 'penulis' => 'Taylor Otwell', 'tahun' => '2024'],
            ],
            'toggles'             => ['kata_pengantar' => true],
        ];
        $module->update(['informasi_umum_data' => $initialData]);

        $payload = [
            'tujuan_pembelajaran' => 'Setelah pembelajaran ini, peserta didik diharapkan mampu memahami konsep MVC dan REST API.',
            'peta_konsep_text'    => 'Alur Pembelajaran: 1. Konsep Model -> 2. Controller -> 3. View Blade.',
            'glosarium'           => [
                ['istilah' => 'MVC', 'definisi' => 'Model View Controller pola arsitektur perangkat lunak.'],
                ['istilah' => 'Blade', 'definisi' => 'Template engine bawaan Laravel yang kuat dan ringan.'],
            ],
        ];

        $response = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.pendahuluan.update', $module), $payload);

        $response->assertRedirect(route('teacher.modules.show', $module));
        $response->assertSessionHas('success');

        $module->refresh();
        $infoData = $module->informasi_umum_data;

        // 1. Verify Pendahuluan components updated
        $this->assertEquals('Setelah pembelajaran ini, peserta didik diharapkan mampu memahami konsep MVC dan REST API.', $infoData['tujuan_pembelajaran']);
        $this->assertEquals('Alur Pembelajaran: 1. Konsep Model -> 2. Controller -> 3. View Blade.', $infoData['peta_konsep_text']);
        $this->assertCount(2, $infoData['glosarium']);
        $this->assertEquals('MVC', $infoData['glosarium'][0]['istilah']);
        $this->assertEquals('Blade', $infoData['glosarium'][1]['istilah']);

        // 2. Verify Bagian Awal & Bagian Akhir preserved
        $this->assertEquals('Pengantar awal yang tidak boleh terhapus.', $infoData['kata_pengantar']);
        $this->assertCount(1, $infoData['daftar_pustaka']);
        $this->assertEquals('Buku Referensi Laravel', $infoData['daftar_pustaka'][0]['judul']);
        $this->assertTrue($infoData['toggles']['kata_pengantar']);
    }

    public function test_teacher_can_toggle_pendahuluan_component(): void
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $initialState = $module->isInfoComponentActive('tujuan_pembelajaran');

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.modules.pendahuluan.toggle', [$module, 'tujuan_pembelajaran']));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $module->refresh();
        $this->assertEquals(!$initialState, $module->isInfoComponentActive('tujuan_pembelajaran'));
    }
}
