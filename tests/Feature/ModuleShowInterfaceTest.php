<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Teacher;
use Tests\TestCase;

class ModuleShowInterfaceTest extends TestCase
{
    public function test_teacher_can_view_module_detail_with_five_pedagogical_sections()
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
            ->get(route('teacher.modules.show', $module));

        $response->assertStatus(200);

        // 1. Bagian Awal
        $response->assertSee('1. Bagian Awal');
        $response->assertSee('Halaman Sampul (Cover)');
        $response->assertSee('Kata Pengantar');
        $response->assertSee('Daftar Isi');
        $response->assertSee('Petunjuk Penggunaan');

        // 2. Pendahuluan
        $response->assertSee('2. Pendahuluan');
        $response->assertSee('Tujuan Pembelajaran &amp; Capaian', false);
        $response->assertSee('Peta Konsep');
        $response->assertSee('Glosarium');

        // 3. Kegiatan Belajar (Isi Materi)
        $response->assertSee('3. Kegiatan Belajar (Isi Materi)');
        $response->assertSee('Uraian Materi Pembelajaran &amp; PPT', false);
        $response->assertSee('Multimedia Video Pembelajaran');
        $response->assertSee('Lembar Kerja Praktik (Job Sheet)');

        // 4. Evaluasi & Latihan
        $response->assertSee('4. Evaluasi &amp; Latihan', false);
        $response->assertSee('Pre-test (Soal Latihan Diagnostik)');
        $response->assertSee('Game Edukasi &amp; Media Interaktif', false);
        $response->assertSee('Tugas LKPD &amp; Umpan Balik', false);

        // 5. Bagian Akhir
        $response->assertSee('5. Bagian Akhir');
        $response->assertSee('Post-test (Tes Akhir Modul)');
        $response->assertSee('Daftar Pustaka');
    }
}
