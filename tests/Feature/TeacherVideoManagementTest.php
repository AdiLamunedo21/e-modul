<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherVideoManagementTest extends TestCase
{
    public function test_teacher_can_view_multi_video_editor()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher required.');
        }

        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module required.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.video.edit', $module));

        $response->assertStatus(200);
        $response->assertSee('Daftar Video Pembelajaran YouTube');
        $response->assertSee('+ Tambah Video Baru');
        $response->assertSee('Hapus Video');
    }

    public function test_teacher_can_save_multiple_videos_and_delete_video()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        $subject = Subject::first();

        if (!$teacher || !$class || !$subject) {
            $this->markTestSkipped('Teacher, class, subject required.');
        }

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Multi Video ' . uniqid(),
            'status'     => 'published',
            'has_video'  => false,
        ]);

        // 1. Simpan 2 video dengan keterangan
        $response = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.video.update', $module), [
                'has_video' => '1',
                'video_title' => 'Kumpulan Video Pembelajaran Rangkaian',
                'instructions' => 'Simak kedua video di atas dan tuliskan intisarinya.',
                'videos' => [
                    [
                        'title'       => 'Video 1: Pengantar Teori',
                        'url'         => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'description' => 'Keterangan video 1 tentang pengantar teori sirkuit.',
                    ],
                    [
                        'title'       => 'Video 2: Praktik Perakitan',
                        'url'         => 'https://youtu.be/HXV3zeQKqGY',
                        'description' => 'Keterangan video 2 tentang perakitan papan sirkuit.',
                    ]
                ],
                'guiding_questions' => [
                    'Poin penting video 1',
                    'Poin penting video 2'
                ],
                'min_summary_chars' => 50,
                'min_summary_words' => 10,
            ]);

        $response->assertRedirect(route('teacher.modules.show', $module));
        $module->refresh();

        $this->assertTrue((bool)$module->has_video);
        $this->assertEquals(2, $module->totalVideosCount());

        $videosList = $module->videosList();
        $this->assertCount(2, $videosList);
        $this->assertEquals('dQw4w9WgXcQ', $videosList[0]['id']);
        $this->assertEquals('HXV3zeQKqGY', $videosList[1]['id']);
        $this->assertEquals('Keterangan video 1 tentang pengantar teori sirkuit.', $videosList[0]['description']);
        $this->assertEquals('Keterangan video 2 tentang perakitan papan sirkuit.', $videosList[1]['description']);
        $this->assertEquals('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?rel=0', $videosList[0]['embed_url']);

        // 2. Simulasikan "Hapus Video": Guru mengupdate hanya menyisakan 1 video
        $deleteResponse = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.video.update', $module), [
                'has_video' => '1',
                'video_title' => 'Video Pembelajaran Rangkaian',
                'videos' => [
                    [
                        'title'       => 'Video 1: Pengantar Teori Saja',
                        'url'         => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'description' => 'Keterangan khusus video pengantar teori saja.',
                    ]
                ],
            ]);

        $deleteResponse->assertRedirect(route('teacher.modules.show', $module));
        $module->refresh();

        $this->assertEquals(1, $module->totalVideosCount());
        $this->assertEquals('Video 1: Pengantar Teori Saja', $module->videoTitle());
        $this->assertEquals('Keterangan khusus video pengantar teori saja.', $module->videosList()[0]['description']);

        $module->delete();
    }

    public function test_teacher_preview_displays_multiple_videos()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        $subject = Subject::first();

        if (!$teacher || !$class || !$subject) {
            $this->markTestSkipped('Teacher, class, subject required.');
        }

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Preview Multi Video ' . uniqid(),
            'status'     => 'published',
            'has_video'  => true,
            'video_data' => [
                'video_title' => 'Video Multi Materi',
                'videos' => [
                    [
                        'title'       => 'Video Part 1',
                        'url'         => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'id'          => 'dQw4w9WgXcQ',
                        'description' => 'Keterangan penting part 1',
                    ],
                    [
                        'title'       => 'Video Part 2',
                        'url'         => 'https://www.youtube.com/watch?v=HXV3zeQKqGY',
                        'id'          => 'HXV3zeQKqGY',
                        'description' => 'Keterangan penting part 2',
                    ]
                ],
            ]
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.video.preview', $module));

        $response->assertStatus(200);
        $response->assertSee('Daftar Putar (2 Video)');
        $response->assertSee('Video 1');
        $response->assertSee('Video 2');
        $response->assertSee('Keterangan penting part 1');
        $response->assertSee('Kolom Ringkasan Siswa');

        $module->delete();
    }

    public function test_student_can_view_multi_video_playlist_and_submit_single_summary()
    {
        $student = Student::first();
        $teacher = Teacher::first();
        $subject = Subject::first();

        if (!$student || !$teacher || !$subject) {
            $this->markTestSkipped('Student, teacher, subject required.');
        }

        $student->subjects()->syncWithoutDetaching([$subject->id]);

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $student->class_id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Multi Video Siswa ' . uniqid(),
            'status'     => 'published',
            'has_video'  => true,
            'video_data' => [
                'video_title' => 'Materi Multi Video Basis Data',
                'videos' => [
                    [
                        'title'       => 'Pengantar Database Part 1',
                        'url'         => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'id'          => 'dQw4w9WgXcQ',
                        'description' => 'Perhatikan bagian normalisasi tabel pada video ini.',
                    ],
                    [
                        'title'       => 'Praktik Query Part 2',
                        'url'         => 'https://www.youtube.com/watch?v=HXV3zeQKqGY',
                        'id'          => 'HXV3zeQKqGY',
                        'description' => 'Pelajari penulisan JOIN dan WHERE clause.',
                    ]
                ],
                'min_summary_chars' => 20,
            ]
        ]);

        // 1. Student views section 3
        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.show', ['module' => $module->id, 'section' => 3]));

        $response->assertStatus(200);
        $response->assertSee('Multimedia Pembelajaran (2 Video)');
        $response->assertSee('Ringkasan / Resume Video Siswa');

        // 2. Student submits ONE single unified summary for both videos
        $submitResponse = $this->actingAs($student, 'student')
            ->post(route('student.modules.video.submit', $module), [
                'summary_text' => 'Ini adalah satu ringkasan terpadu yang merangkum keseluruhan pemahaman dari video 1 dan video 2.',
            ]);

        $submitResponse->assertRedirect(route('student.modules.show', ['module' => $module->id, 'page' => 'video']));
        $this->assertDatabaseHas('video_summaries', [
            'module_id'  => $module->id,
            'student_id' => $student->id,
        ]);

        $module->delete();
    }
}
