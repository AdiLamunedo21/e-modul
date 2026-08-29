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
        $response->assertSee('Bagian Awal');
        $response->assertSee('Kata Pengantar');
        $response->assertSee('Petunjuk Penggunaan');

        // 2. Pendahuluan
        $response->assertSee('Pendahuluan');
        $response->assertSee('Tujuan Pembelajaran &amp; Capaian', false);
        $response->assertSee('Peta Konsep');
        $response->assertSee('Glosarium');
        $response->assertSee('Pre-test (Soal Latihan Diagnostik)');
        $response->assertSee('Edit 3 Komponen Pendahuluan');
        $response->assertSee('Edit / Kelola Soal Pre-test');

        // 3. Kegiatan Belajar (Isi Materi)
        $response->assertSee('Kegiatan Belajar (Isi Materi)');
        $response->assertSee('Uraian Materi Pembelajaran &amp; PPT', false);
        $response->assertSee('Multimedia Video Pembelajaran');
        $response->assertDontSee('Buka Editor Materi & PPT');

        // 4. Evaluasi & Latihan
        $response->assertSee('Evaluasi &amp; Latihan', false);
        $response->assertSee('Game Edukasi &amp; Media Interaktif', false);
        $response->assertSee('Lembar Kerja Praktik (Job Sheet)');
        $response->assertSee('Tugas LKPD &amp; Umpan Balik', false);
        $response->assertDontSee('Kelola Tugas LKPD');

        // 5. Bagian Akhir
        $response->assertSee('Bagian Akhir');
        $response->assertSee('Post-test (Tes Akhir Modul)');
        $response->assertSee('Daftar Pustaka');
        $response->assertDontSee('Edit Daftar Pustaka');
        $response->assertDontSee('Kelola Soal Post-test');
    }
}
