<?php

namespace Tests\Feature;

use App\Models\EmbedSubmission;
use App\Models\JobSheet;
use App\Models\JobSheetSubmission;
use App\Models\Lkpd;
use App\Models\Module;
use App\Models\PostTest;
use App\Models\PostTestQuestion;
use App\Models\PreTest;
use App\Models\PreTestQuestion;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\Teacher;
use App\Models\VideoSummary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentInteractiveLearningTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_student_can_view_published_module_interactive_learning_page()
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
            'title'      => 'Modul Belajar Interaktif ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
            'has_video'  => true,
            'materi_data' => [
                'judul_materi' => 'Uraian Pengenalan Web',
                'uraian_materi' => '<p>Materi Dasar Pembelajaran Web HTML dan CSS.</p>',
            ],
            'informasi_umum_data' => [
                'toggles' => [
                    'kata_pengantar' => true,
                    'petunjuk_penggunaan' => true,
                    'tujuan_pembelajaran' => true,
                ],
                'kata_pengantar' => 'Selamat datang di modul pembelajaran web.',
                'tujuan_pembelajaran' => [
                    'capaian_pembelajaran' => 'Memahami struktur web',
                    'tujuan_pembelajaran' => ['Membuat halaman web responsive'],
                ],
            ],
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.show', $module));

        $response->assertStatus(200);
        $response->assertSee($module->title);
        $response->assertSee('Bagian Awal');
        $response->assertSee('Pendahuluan');
        $response->assertSee('Kegiatan Belajar');
        $response->assertSee('Materi Dasar Pembelajaran Web HTML dan CSS', false);

        $module->delete();
    }

    public function test_student_cannot_view_draft_or_closed_module()
    {
        $student = Student::first();
        $teacher = Teacher::first();
        $subject = Subject::first();

        if (!$student || !$teacher || !$subject) {
            $this->markTestSkipped('Student, teacher, subject required.');
        }

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $student->class_id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Draft Belum Terbit ' . uniqid(),
            'status'     => 'draft',
            'has_materi' => true,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.show', $module));

        $response->assertStatus(404);

        $module->delete();
    }

    public function test_student_cannot_view_module_from_another_class()
    {
        $student = Student::first();
        $teacher = Teacher::first();
        $subject = Subject::first();
        $otherClass = SchoolClass::where('id', '!=', $student->class_id)->first();

        if (!$student || !$teacher || !$subject || !$otherClass) {
            $this->markTestSkipped('Student, other class required.');
        }

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $otherClass->id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Kelas Lain ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.show', $module));

        $response->assertStatus(403);

        $module->delete();
    }

    public function test_student_can_take_pre_test_and_get_auto_graded()
    {
        $student = Student::first();
        $teacher = Teacher::first();
        $subject = Subject::first();

        if (!$student || !$teacher || !$subject) {
            $this->markTestSkipped('Student, teacher, subject required.');
        }

        $student->subjects()->syncWithoutDetaching([$subject->id]);

        $module = Module::create([
            'teacher_id'   => $teacher->id,
            'class_id'     => $student->class_id,
            'subject_id'   => $subject->id,
            'title'        => 'Modul dengan Pre-test ' . uniqid(),
            'status'       => 'published',
            'has_pre_test' => true,
        ]);

        $preTest = PreTest::create([
            'module_id'        => $module->id,
            'title'            => 'Pre-test Diagnostik',
            'duration_minutes' => 15,
            'kktp'             => 75,
        ]);

        $q1 = PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'HTML singkatan dari apa?',
            'options'        => [
                'A' => 'Hypertext Markup Language',
                'B' => 'Home Tool Markup Language',
                'C' => 'Hyperlinks and Text Markup Language',
            ],
            'correct_answer' => 'A',
            'score_weight'   => 50,
            'order_num'      => 1,
        ]);

        $q2 = PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'CSS digunakan untuk apa?',
            'options'        => [
                'A' => 'Membuat database',
                'B' => 'Menghias dan menata tampilan web',
                'C' => 'Membuat sistem operasi',
            ],
            'correct_answer' => 'B',
            'score_weight'   => 50,
            'order_num'      => 2,
        ]);

        // Student submits answers: Q1 correct (A), Q2 wrong (A)
        $response = $this->actingAs($student, 'student')
            ->post(route('student.modules.pre-test.submit', $module), [
                'answers' => [
                    $q1->id => 'A',
                    $q2->id => 'A',
                ],
            ]);

        $response->assertRedirect(route('student.modules.show', ['module' => $module->id, 'section' => 2]));
        $response->assertSessionHas('success');

        $result = StudentResult::where('module_id', $module->id)->where('student_id', $student->id)->first();
        $this->assertNotNull($result);
        $this->assertEquals(50, $result->pre_test_score);

        $module->delete();
    }

    public function test_student_can_submit_video_summary_and_cancel_pending()
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
            'title'      => 'Modul Video ' . uniqid(),
            'status'     => 'published',
            'has_video'  => true,
            'video_data' => [
                'judul_video' => 'Video Pembelajaran Web',
                'youtube_id'  => 'dQw4w9WgXcQ',
            ],
        ]);

        // 1. Submit video summary
        $response = $this->actingAs($student, 'student')
            ->post(route('student.modules.video.submit', $module), [
                'summary_text' => 'Ini adalah ringkasan materi video pembelajaran web yang cukup panjang lebih dari 20 karakter.',
            ]);

        $response->assertRedirect(route('student.modules.show', ['module' => $module->id, 'section' => 3]));
        $this->assertDatabaseHas('video_summaries', [
            'module_id'  => $module->id,
            'student_id' => $student->id,
        ]);

        // 2. Cancel video summary while pending
        $cancelRes = $this->actingAs($student, 'student')
            ->delete(route('student.modules.submission.cancel', ['module' => $module->id, 'type' => 'video']));

        $cancelRes->assertSessionHas('success');
        $this->assertDatabaseMissing('video_summaries', [
            'module_id'  => $module->id,
            'student_id' => $student->id,
        ]);

        $module->delete();
    }

    public function test_student_can_upload_embed_screenshot_jobsheet_and_lkpd()
    {
        $student = Student::first();
        $teacher = Teacher::first();
        $subject = Subject::first();

        if (!$student || !$teacher || !$subject) {
            $this->markTestSkipped('Student, teacher, subject required.');
        }

        $student->subjects()->syncWithoutDetaching([$subject->id]);

        $module = Module::create([
            'teacher_id'    => $teacher->id,
            'class_id'      => $student->class_id,
            'subject_id'    => $subject->id,
            'title'         => 'Modul Praktik Komprehensif ' . uniqid(),
            'status'        => 'published',
            'has_embed'     => true,
            'has_job_sheet' => true,
            'has_lkpd'      => true,
            'has_post_test' => true,
        ]);

        $jobSheet = JobSheet::create(['module_id' => $module->id, 'pdf_file_path' => 'demo.pdf']);
        $lkpd = Lkpd::create(['module_id' => $module->id, 'pdf_file_path' => 'demo_lkpd.pdf']);

        $postTest = PostTest::create([
            'module_id' => $module->id,
            'title'     => 'Post-test Evaluasi',
        ]);
        $postQ = PostTestQuestion::create([
            'post_test_id'   => $postTest->id,
            'question_text'  => 'Soal post test 1',
            'options'        => ['A' => 'Benar', 'B' => 'Salah'],
            'correct_answer' => 'A',
            'score_weight'   => 100,
            'order_num'      => 1,
        ]);

        // 1. Upload Embed screenshot
        $imageFile = UploadedFile::fake()->image('screenshot.png', 800, 600);
        $resEmbed = $this->actingAs($student, 'student')
            ->post(route('student.modules.embed.submit', $module), [
                'screenshot' => $imageFile,
            ]);
        $resEmbed->assertRedirect(route('student.modules.show', ['module' => $module->id, 'section' => 4]));
        $this->assertDatabaseHas('embed_submissions', [
            'module_id'  => $module->id,
            'student_id' => $student->id,
        ]);

        // 2. Upload Job Sheet PDF
        $pdfJobSheet = UploadedFile::fake()->create('laporan_jobsheet.pdf', 500, 'application/pdf');
        $resJs = $this->actingAs($student, 'student')
            ->post(route('student.modules.job-sheet.submit', $module), [
                'job_sheet_file' => $pdfJobSheet,
            ]);
        $resJs->assertRedirect(route('student.modules.show', ['module' => $module->id, 'section' => 4]));
        $this->assertDatabaseHas('job_sheet_submissions', [
            'job_sheet_id' => $jobSheet->id,
            'student_id'   => $student->id,
        ]);

        // 3. Upload LKPD PDF
        $pdfLkpd = UploadedFile::fake()->create('jawaban_lkpd.pdf', 400, 'application/pdf');
        $resLkpd = $this->actingAs($student, 'student')
            ->post(route('student.modules.lkpd.submit', $module), [
                'lkpd_file' => $pdfLkpd,
            ]);
        $resLkpd->assertRedirect(route('student.modules.show', ['module' => $module->id, 'section' => 4]));
        $this->assertDatabaseHas('submissions', [
            'lkpd_id'    => $lkpd->id,
            'student_id' => $student->id,
        ]);

        // 4. Submit Post-test
        $resPost = $this->actingAs($student, 'student')
            ->post(route('student.modules.post-test.submit', $module), [
                'answers' => [$postQ->id => 'A'],
            ]);
        $resPost->assertRedirect(route('student.modules.show', ['module' => $module->id, 'section' => 5]));

        $result = StudentResult::where('module_id', $module->id)->where('student_id', $student->id)->first();
        $this->assertNotNull($result);
        $this->assertEquals(100, $result->post_test_score);

        $module->delete();
    }

    public function test_student_can_mark_component_read_and_unlock_sequential_steps()
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
            'title'      => 'Modul Sekuensial ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
            'informasi_umum_data' => [
                'toggles' => [
                    'kata_pengantar' => true,
                    'petunjuk_penggunaan' => true,
                ],
                'kata_pengantar' => 'Kata Pengantar Modul.',
            ],
        ]);

        $resAjax = $this->actingAs($student, 'student')
            ->postJson(route('student.modules.mark-read', $module), [
                'component' => 'kata_pengantar',
            ]);

        $resAjax->assertStatus(200);
        $resAjax->assertJsonFragment(['success' => true]);

        $result = StudentResult::where('module_id', $module->id)->where('student_id', $student->id)->first();
        $this->assertNotNull($result);
        $this->assertTrue($result->isComponentRead('kata_pengantar'));

        $module->delete();
    }
}

