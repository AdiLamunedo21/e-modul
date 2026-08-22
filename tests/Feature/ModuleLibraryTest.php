<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\PostTest;
use App\Models\PostTestQuestion;
use App\Models\PreTest;
use App\Models\PreTestQuestion;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Tests\TestCase;

class ModuleLibraryTest extends TestCase
{
    public function test_teacher_can_access_library_index()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.library.index'));

        $response->assertStatus(200);
        $response->assertSee('Library Modul', false);
        $response->assertSee('SMK Negeri 3 Yogyakarta', false);
        $response->assertSee('Modul di Library', false);
    }

    public function test_shared_module_appears_in_library_catalogue()
    {
        $teachers = Teacher::take(2)->get();
        $class = SchoolClass::first();
        if ($teachers->count() < 2 || !$class) {
            $this->markTestSkipped('At least 2 teachers and 1 class required.');
        }

        $author = $teachers[0];
        $viewer = $teachers[1];

        $sharedModule = Module::create([
            'teacher_id' => $author->id,
            'class_id'   => $class->id,
            'title'      => 'Modul Kolaboratif IoT ' . uniqid(),
            'status'     => 'published',
            'is_shared'  => true,
            'shared_at'  => now(),
            'has_materi' => true,
        ]);

        $response = $this->actingAs($viewer, 'teacher')
            ->get(route('teacher.library.index'));

        $response->assertStatus(200);
        $response->assertSee($sharedModule->title);
    }

    public function test_private_module_does_not_appear_in_library_catalogue()
    {
        $teachers = Teacher::take(2)->get();
        $class = SchoolClass::first();
        if ($teachers->count() < 2 || !$class) {
            $this->markTestSkipped('At least 2 teachers and 1 class required.');
        }

        $author = $teachers[0];
        $viewer = $teachers[1];

        $privateModule = Module::create([
            'teacher_id' => $author->id,
            'class_id'   => $class->id,
            'title'      => 'Modul Rahasia Privat ' . uniqid(),
            'status'     => 'published',
            'is_shared'  => false,
        ]);

        $response = $this->actingAs($viewer, 'teacher')
            ->get(route('teacher.library.index'));

        $response->assertStatus(200);
        $response->assertDontSee($privateModule->title);
    }

    public function test_teacher_can_preview_shared_module()
    {
        $teachers = Teacher::take(2)->get();
        $class = SchoolClass::first();
        if ($teachers->count() < 2 || !$class) {
            $this->markTestSkipped('At least 2 teachers and 1 class required.');
        }

        $author = $teachers[0];
        $viewer = $teachers[1];

        $module = Module::create([
            'teacher_id' => $author->id,
            'class_id'   => $class->id,
            'title'      => 'Modul Pemrograman Web Interaktif ' . uniqid(),
            'status'     => 'published',
            'is_shared'  => true,
            'has_materi' => true,
            'materi_data'=> ['notepad_content' => 'Belajar HTML & CSS Terpadu'],
        ]);

        $response = $this->actingAs($viewer, 'teacher')
            ->get(route('teacher.library.show', $module));

        $response->assertStatus(200);
        $response->assertSee($module->title);
        $response->assertSee('Struktur 5 Bagian Kurikulum E-Modul');
        $response->assertSee('Salin Modul ke Workspace Saya');
    }

    public function test_teacher_can_toggle_share_status_on_own_module()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'title'      => 'Modul Toggle Share ' . uniqid(),
            'status'     => 'published',
            'is_shared'  => false,
        ]);

        // Toggle On
        $responseOn = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.modules.toggle-share', $module));

        $responseOn->assertRedirect();
        $module->refresh();
        $this->assertTrue($module->is_shared);
        $this->assertNotNull($module->shared_at);

        // Toggle Off
        $responseOff = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.modules.toggle-share', $module));

        $responseOff->assertRedirect();
        $module->refresh();
        $this->assertFalse($module->is_shared);
    }

    public function test_teacher_cannot_toggle_share_on_other_teacher_module()
    {
        $teachers = Teacher::take(2)->get();
        $class = SchoolClass::first();
        if ($teachers->count() < 2 || !$class) {
            $this->markTestSkipped('At least 2 teachers and 1 class required.');
        }

        $author = $teachers[0];
        $attacker = $teachers[1];

        $module = Module::create([
            'teacher_id' => $author->id,
            'class_id'   => $class->id,
            'title'      => 'Modul Korban ' . uniqid(),
            'status'     => 'published',
            'is_shared'  => false,
        ]);

        $response = $this->actingAs($attacker, 'teacher')
            ->post(route('teacher.modules.toggle-share', $module));

        $response->assertStatus(403);
    }

    public function test_teacher_can_clone_shared_module_with_all_components()
    {
        $teachers = Teacher::take(2)->get();
        $classes = SchoolClass::take(2)->get();
        if ($teachers->count() < 2 || $classes->count() < 2) {
            $this->markTestSkipped('At least 2 teachers and 2 classes required.');
        }

        $author = $teachers[0];
        $cloner = $teachers[1];
        $targetClass = $classes[1];

        // 1. Buat modul sumber lengkap dengan Pre-test & Post-test
        $sourceModule = Module::create([
            'teacher_id'          => $author->id,
            'class_id'            => $classes[0]->id,
            'title'               => 'Modul Master Jaringan Komputer ' . uniqid(),
            'informasi_umum_data' => ['kata_pengantar' => 'Salam sejahtera'],
            'bagian_akhir_data'   => ['daftar_pustaka' => 'Buku Jaringan 2026'],
            'materi_data'         => ['notepad_content' => 'Topologi Jaringan Star & Mesh'],
            'video_data'          => ['youtube_url' => 'https://youtube.com/watch?v=demo123'],
            'has_pre_test'        => true,
            'has_materi'          => true,
            'has_video'           => true,
            'has_post_test'       => true,
            'status'              => 'published',
            'is_shared'           => true,
            'shared_at'           => now(),
            'clone_count'         => 0,
        ]);

        $preTest = PreTest::create([
            'module_id'           => $sourceModule->id,
            'title'               => 'Pre-test Diagnostik Jaringan',
            'duration_minutes'    => 15,
            'kktp'                => 75,
            'randomize_questions' => true,
        ]);

        PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Apa kepanjangan dari LAN?',
            'options'        => ['A' => 'Local Area Network', 'B' => 'Large Area Node'],
            'correct_answer' => 'A',
            'score_weight'   => 10,
            'order_num'      => 1,
        ]);

        $postTest = PostTest::create([
            'module_id'           => $sourceModule->id,
            'title'               => 'Post-test Sumatif Jaringan',
            'duration_minutes'    => 20,
            'kktp'                => 80,
            'randomize_questions' => false,
        ]);

        PostTestQuestion::create([
            'post_test_id'   => $postTest->id,
            'question_text'  => 'Protokol apa yang digunakan untuk web browsing aman?',
            'options'        => ['A' => 'HTTP', 'B' => 'HTTPS'],
            'correct_answer' => 'B',
            'score_weight'   => 10,
            'order_num'      => 1,
        ]);

        // 2. Cloner menyalin modul ke workspace pribadinya
        $clonedTitle = 'Modul Jaringan Kelas XII (Adaptasi Guru B)';
        $response = $this->actingAs($cloner, 'teacher')
            ->post(route('teacher.library.clone', $sourceModule), [
                'class_id' => $targetClass->id,
                'title'    => $clonedTitle,
            ]);

        $clonedModule = Module::where('teacher_id', $cloner->id)
            ->where('title', $clonedTitle)
            ->first();

        $this->assertNotNull($clonedModule);
        $response->assertRedirect(route('teacher.modules.show', $clonedModule));

        // Verifikasi properti modul salinan
        $this->assertEquals($targetClass->id, $clonedModule->class_id);
        $this->assertEquals('draft', $clonedModule->status);
        $this->assertFalse($clonedModule->is_shared);
        $this->assertEquals($sourceModule->id, $clonedModule->cloned_from_id);
        $this->assertEquals('Topologi Jaringan Star & Mesh', $clonedModule->materi_data['notepad_content']);

        // Verifikasi duplikasi Pre-test & Butir Soal
        $this->assertNotNull($clonedModule->preTest);
        $this->assertEquals(1, $clonedModule->preTest->questions()->count());
        $this->assertEquals('Apa kepanjangan dari LAN?', $clonedModule->preTest->questions->first()->question_text);

        // Verifikasi duplikasi Post-test & Butir Soal
        $this->assertNotNull($clonedModule->postTest);
        $this->assertEquals(1, $clonedModule->postTest->questions()->count());
        $this->assertEquals('Protokol apa yang digunakan untuk web browsing aman?', $clonedModule->postTest->questions->first()->question_text);

        // Verifikasi counter kloning pada modul sumber bertambah
        $sourceModule->refresh();
        $this->assertEquals(1, $sourceModule->clone_count);

        // Verifikasi bahwa modifikasi pada modul salinan TIDAK memengaruhi modul sumber
        $clonedModule->update([
            'title'       => 'Modul Modifikasi Mandiri',
            'materi_data' => ['notepad_content' => 'Konten Baru yang Diubah Guru B'],
        ]);

        $sourceModule->refresh();
        $this->assertEquals('Modul Master Jaringan Komputer ' . substr($sourceModule->title, 31), $sourceModule->title);
        $this->assertEquals('Topologi Jaringan Star & Mesh', $sourceModule->materi_data['notepad_content']);
    }

    public function test_unauthenticated_user_cannot_access_library()
    {
        $response = $this->get(route('teacher.library.index'));
        $response->assertRedirect(route('login.teacher'));
    }
}
