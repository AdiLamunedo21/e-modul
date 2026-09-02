<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherMateriTest extends TestCase
{
    private function getTeacherAndModule()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        $subject = Subject::first();

        if (!$teacher || !$class || !$subject) {
            return [null, null];
        }

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Uji Materi ' . uniqid(),
            'status'     => 'published',
            'has_materi' => false,
        ]);

        return [$teacher, $module];
    }

    public function test_teacher_can_view_materi_editor()
    {
        [$teacher, $module] = $this->getTeacherAndModule();
        if (!$teacher) {
            $this->markTestSkipped('Data pengujian tidak mencukupi.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.materi.edit', $module));

        $response->assertStatus(200);
        $response->assertSee('Notepad Editor: Materi & PPT', false);
        $response->assertSee('Simpan Materi & PPT', false);
        $response->assertSee('id="materi-form"', false);
        $response->assertSee('id="materi-toast"', false);
    }

    public function test_teacher_can_save_materi_via_regular_submit()
    {
        [$teacher, $module] = $this->getTeacherAndModule();
        if (!$teacher) {
            $this->markTestSkipped('Data pengujian tidak mencukupi.');
        }

        $payload = [
            'has_materi'    => '1',
            'judul_materi'  => 'Kegiatan Belajar Reguler',
            'uraian_materi' => '<p>Uraian materi pembelajaran lengkap dengan panjang lebih dari 20 karakter.</p>',
            'poin_penting'  => ['Poin satu rangkuman', 'Poin dua rangkuman'],
        ];

        $response = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.materi.update', $module), $payload);

        $response->assertRedirect(route('teacher.modules.show', $module));
        $response->assertSessionHas('success');

        $module->refresh();
        $this->assertTrue((bool)$module->has_materi);
        $this->assertEquals('Kegiatan Belajar Reguler', $module->materi_data['judul_materi']);
    }

    public function test_teacher_can_save_materi_via_ajax_without_refresh()
    {
        [$teacher, $module] = $this->getTeacherAndModule();
        if (!$teacher) {
            $this->markTestSkipped('Data pengujian tidak mencukupi.');
        }

        $payload = [
            'has_materi'    => '1',
            'judul_materi'  => 'Kegiatan Belajar AJAX Realtime',
            'uraian_materi' => '<p>Konten materi baru tersimpan secara asynchronous tanpa browser refresh!</p>',
            'poin_penting'  => ['Prinsip 1 Asynchronous', 'Prinsip 2 Reactive DOM Update'],
        ];

        $response = $this->actingAs($teacher, 'teacher')
            ->patchJson(route('teacher.modules.materi.update', $module), $payload, [
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data'    => [
                'has_materi'   => true,
                'judul_materi' => 'Kegiatan Belajar AJAX Realtime',
                'poin_penting' => ['Prinsip 1 Asynchronous', 'Prinsip 2 Reactive DOM Update'],
            ],
        ]);

        $module->refresh();
        $this->assertTrue((bool)$module->has_materi);
        $this->assertEquals('Kegiatan Belajar AJAX Realtime', $module->materi_data['judul_materi']);
    }

    public function test_teacher_can_upload_and_remove_ppt_file_via_ajax()
    {
        Storage::fake('public');

        [$teacher, $module] = $this->getTeacherAndModule();
        if (!$teacher) {
            $this->markTestSkipped('Data pengujian tidak mencukupi.');
        }

        $file = UploadedFile::fake()->create('slide_basis_data.pdf', 500, 'application/pdf');

        // 1. Upload PPT/PDF via AJAX
        $response = $this->actingAs($teacher, 'teacher')
            ->patchJson(route('teacher.modules.materi.update', $module), [
                'has_materi'    => '1',
                'judul_materi'  => 'Materi Slide Presentasi',
                'uraian_materi' => '<p>Uraian materi disertai berkas slide PPT atau dokumen PDF resmi.</p>',
                'ppt_file'      => $file,
            ], [
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ]);

        $response->assertStatus(200);
        $resData = $response->json();
        $this->assertTrue($resData['success']);
        $this->assertEquals('slide_basis_data.pdf', $resData['data']['ppt_file_name']);
        $this->assertNotNull($resData['data']['ppt_file_path']);
        $this->assertTrue($resData['data']['ppt_file_is_pdf']);
        $this->assertNotNull($resData['data']['ppt_download_url']);

        Storage::disk('public')->assertExists($resData['data']['ppt_file_path']);

        // 2. Remove file via AJAX
        $removeResponse = $this->actingAs($teacher, 'teacher')
            ->patchJson(route('teacher.modules.materi.update', $module), [
                'has_materi'      => '1',
                'judul_materi'    => 'Materi Slide Presentasi',
                'uraian_materi'   => '<p>Uraian materi disertai berkas slide PPT atau dokumen PDF resmi.</p>',
                'remove_ppt_file' => '1',
            ], [
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ]);

        $removeResponse->assertStatus(200);
        $removeData = $removeResponse->json();
        $this->assertTrue($removeData['success']);
        $this->assertNull($removeData['data']['ppt_file_path']);

        $module->refresh();
        $this->assertNull($module->materi_data['ppt_file_path']);
    }

    public function test_ajax_save_materi_validation_failure_returns_422_json()
    {
        [$teacher, $module] = $this->getTeacherAndModule();
        if (!$teacher) {
            $this->markTestSkipped('Data pengujian tidak mencukupi.');
        }

        // has_materi aktif tapi uraian materi kurang dari 20 karakter
        $response = $this->actingAs($teacher, 'teacher')
            ->patchJson(route('teacher.modules.materi.update', $module), [
                'has_materi'    => '1',
                'judul_materi'  => '',
                'uraian_materi' => 'Pendek',
            ], [
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'application/json',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['judul_materi', 'uraian_materi']);
    }
}
