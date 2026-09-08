<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\PostTest;
use App\Models\PostTestQuestion;
use App\Models\PreTest;
use App\Models\PreTestQuestion;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Teacher;
use Tests\TestCase;

class PostTestInheritanceTest extends TestCase
{
    private function createTeacherAndStudent()
    {
        $teacher = Teacher::first() ?? Teacher::factory()->create();
        $class = SchoolClass::first();
        $subject = Subject::first();
        $student = Student::first();

        if ($student && $subject) {
            $student->subjects()->syncWithoutDetaching([$subject->id]);
            if ($class) {
                $student->update(['class_id' => $class->id]);
            }
        }

        return compact('teacher', 'class', 'subject', 'student');
    }

    public function test_post_test_inherits_pre_test_questions_when_no_custom_questions()
    {
        $data = $this->createTeacherAndStudent();
        $teacher = $data['teacher'];
        $class = $data['class'];
        $subject = $data['subject'];

        $module = Module::create([
            'teacher_id'    => $teacher->id,
            'subject_id'    => $subject->id,
            'class_id'      => $class?->id,
            'title'         => 'Modul Pewarisan Soal ' . uniqid(),
            'semester'      => '1',
            'status'        => 'published',
            'is_active'     => true,
            'has_pre_test'  => true,
            'has_post_test' => true,
        ]);

        $preTest = PreTest::create([
            'module_id'    => $module->id,
            'title'        => 'Pre-test Diagnostik Utama',
            'kktp'         => 75,
            'instructions' => 'Petunjuk Pre-test',
        ]);

        $q1 = PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Soal Pre-test Warisan 1',
            'options'        => ['A' => 'Jawaban A', 'B' => 'Jawaban B', 'C' => 'Jawaban C', 'D' => 'Jawaban D'],
            'correct_answer' => 'B',
            'explanation'    => 'Pembahasan Soal 1',
            'score_weight'   => 50,
            'order_num'      => 1,
        ]);

        $q2 = PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Soal Pre-test Warisan 2',
            'options'        => ['A' => 'Jawaban Benar A', 'B' => 'Jawaban Salah B', 'C' => 'Jawaban C', 'D' => 'Jawaban D'],
            'correct_answer' => 'A',
            'explanation'    => 'Pembahasan Soal 2',
            'score_weight'   => 50,
            'order_num'      => 2,
        ]);

        $module->refresh();

        // 1. Module model checks
        $this->assertTrue($module->isPostTestInheritingPreTest());
        $this->assertEquals(2, $module->postTestQuestionCount());

        $effectiveQuestions = $module->getEffectivePostTestQuestions();
        $this->assertCount(2, $effectiveQuestions);
        $this->assertEquals('Soal Pre-test Warisan 1', $effectiveQuestions->first()->question_text);

        // Clean up
        $module->delete();
    }

    public function test_student_sees_inherited_pre_test_questions_in_post_test_page()
    {
        $data = $this->createTeacherAndStudent();
        $teacher = $data['teacher'];
        $class = $data['class'];
        $subject = $data['subject'];
        $student = $data['student'];

        $module = Module::create([
            'teacher_id'    => $teacher->id,
            'subject_id'    => $subject->id,
            'class_id'      => $class?->id,
            'title'         => 'Modul Tampilan Siswa ' . uniqid(),
            'semester'      => '1',
            'status'        => 'published',
            'is_active'     => true,
            'has_pre_test'  => true,
            'has_post_test' => true,
        ]);

        $preTest = PreTest::create([
            'module_id'    => $module->id,
            'title'        => 'Pre-test Diagnostik',
            'kktp'         => 75,
            'instructions' => 'Petunjuk pengerjaan',
        ]);

        PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Apa nama protokol web standar?',
            'options'        => ['A' => 'HTTP/HTTPS', 'B' => 'FTP', 'C' => 'SSH', 'D' => 'SMTP'],
            'correct_answer' => 'A',
            'explanation'    => 'HTTP/HTTPS adalah protokol standar web.',
            'score_weight'   => 10,
            'order_num'      => 1,
        ]);

        // Student visits the module post_test page
        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.show', ['module' => $module->id, 'page' => 'post_test']));

        $response->assertStatus(200);
        $response->assertSee('Apa nama protokol web standar?');
        $response->assertSee('HTTP/HTTPS');
        $response->assertSee('Kirim Jawaban Post-test');

        $module->delete();
    }

    public function test_student_submits_post_test_and_is_graded_using_pre_test_keys()
    {
        $data = $this->createTeacherAndStudent();
        $teacher = $data['teacher'];
        $class = $data['class'];
        $subject = $data['subject'];
        $student = $data['student'];

        $module = Module::create([
            'teacher_id'    => $teacher->id,
            'subject_id'    => $subject->id,
            'class_id'      => $class?->id,
            'title'         => 'Modul Ujian Siswa ' . uniqid(),
            'semester'      => '1',
            'status'        => 'published',
            'is_active'     => true,
            'has_pre_test'  => true,
            'has_post_test' => true,
        ]);

        $preTest = PreTest::create([
            'module_id'    => $module->id,
            'title'        => 'Pre-test Diagnostik',
            'kktp'         => 75,
            'instructions' => 'Petunjuk',
        ]);

        $q1 = PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Soal Penilaian 1',
            'options'        => ['A' => 'Opsi A', 'B' => 'Opsi B', 'C' => 'Opsi C', 'D' => 'Opsi D'],
            'correct_answer' => 'C',
            'score_weight'   => 10,
            'order_num'      => 1,
        ]);

        $q2 = PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Soal Penilaian 2',
            'options'        => ['A' => 'Opsi A', 'B' => 'Opsi B', 'C' => 'Opsi C', 'D' => 'Opsi D'],
            'correct_answer' => 'A',
            'score_weight'   => 10,
            'order_num'      => 2,
        ]);

        // Submit 100% correct answers (q1 => C, q2 => A)
        $response = $this->actingAs($student, 'student')
            ->post(route('student.modules.post-test.submit', $module), [
                'answers' => [
                    $q1->id => 'C',
                    $q2->id => 'A',
                ],
            ]);

        $response->assertRedirect(route('student.modules.show', ['module' => $module->id, 'page' => 'post_test']));

        $result = StudentResult::where('module_id', $module->id)->where('student_id', $student->id)->first();
        $this->assertNotNull($result);
        $this->assertEquals(100, $result->post_test_score);

        // Student visits page after submission: should see review section
        $reviewResponse = $this->actingAs($student, 'student')
            ->get(route('student.modules.show', ['module' => $module->id, 'page' => 'post_test']));

        $reviewResponse->assertStatus(200);
        $reviewResponse->assertSee('Tinjau Butir Soal & Kunci Jawaban', false);
        $reviewResponse->assertSee('Soal Penilaian 1');
        $reviewResponse->assertSee('Soal Penilaian 2');

        $module->delete();
    }

    public function test_teacher_can_enable_post_test_without_retyping_questions()
    {
        $data = $this->createTeacherAndStudent();
        $teacher = $data['teacher'];
        $class = $data['class'];
        $subject = $data['subject'];

        $module = Module::create([
            'teacher_id'    => $teacher->id,
            'subject_id'    => $subject->id,
            'class_id'      => $class?->id,
            'title'         => 'Modul Guru ' . uniqid(),
            'semester'      => '1',
            'status'        => 'draft',
            'is_active'     => true,
            'has_pre_test'  => true,
            'has_post_test' => false,
        ]);

        $preTest = PreTest::create([
            'module_id'    => $module->id,
            'title'        => 'Pre-test Guru',
            'kktp'         => 75,
            'instructions' => 'Petunjuk',
        ]);

        PreTestQuestion::create([
            'pre_test_id'    => $preTest->id,
            'question_text'  => 'Pertanyaan bawaan pre-test',
            'options'        => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'],
            'correct_answer' => 'A',
            'score_weight'   => 10,
            'order_num'      => 1,
        ]);

        // Teacher accesses edit page: should see inherited questions information
        $editResponse = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.post-test.edit', $module));

        $editResponse->assertStatus(200);
        $editResponse->assertSee('Pertanyaan bawaan pre-test');

        // Teacher enables post-test without providing new questions
        $postResponse = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.modules.post-test.update', $module), [
                'has_post_test'       => '1',
                'title'               => 'Post-Test Evaluasi Akhir',
                'kktp'                => 75,
                'instructions'        => 'Petunjuk baru',
                'randomize_questions' => '0',
            ]);

        $postResponse->assertRedirect();
        $module->refresh();
        $this->assertTrue((bool) $module->has_post_test);
        $this->assertTrue($module->isPostTestInheritingPreTest());

        // Teacher previews post-test
        $previewResponse = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.modules.post-test.preview', $module));

        $previewResponse->assertStatus(200);
        $previewResponse->assertSee('Mewarisi Butir Soal & Kunci Jawaban Pre-test', false);
        $previewResponse->assertSee('Pertanyaan bawaan pre-test');

        $module->delete();
    }
}
