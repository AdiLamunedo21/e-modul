<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Tests\TestCase;

class StudentModuleTest extends TestCase
{
    public function test_unauthenticated_student_cannot_access_module_pages()
    {
        $subject = Subject::first();
        if ($subject) {
            $resSubject = $this->get(route('student.modules.subject', $subject));
            $resSubject->assertRedirect(route('login.student'));
        }
    }

    public function test_authenticated_student_can_access_subject_module_page()
    {
        $student = Student::first();
        $subject = Subject::first();
        if (!$student || !$subject) {
            $this->markTestSkipped('Student and Subject required.');
        }

        $student->subjects()->syncWithoutDetaching([$subject->id]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.subject', $subject));

        $response->assertStatus(200);
        $response->assertSee($subject->name);
        $response->assertSee('Guru Pengampu', false);
        $response->assertSee('Modul Belajar', false);
    }

    public function test_subject_module_page_displays_assigned_modules_and_filters()
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
            'title'      => 'Test Modul Mapel ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
            'updated_at' => now()->addMinutes(5),
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.subject', $subject));

        $response->assertStatus(200);
        $response->assertSee($module->title);

        // Test status filters on subject page
        $resFilter = $this->actingAs($student, 'student')
            ->get(route('student.modules.subject', ['subject' => $subject, 'status' => 'not_started']));
        $resFilter->assertStatus(200);
        $resFilter->assertSee($module->title);

        $module->delete();
    }

    public function test_student_cannot_access_unassigned_subject_module_page()
    {
        $student = Student::first();
        $subjectAssigned = Subject::first();
        $subjectUnassigned = Subject::where('id', '!=', $subjectAssigned->id)->first();

        if (!$student || !$subjectAssigned || !$subjectUnassigned) {
            $this->markTestSkipped('Student and multiple subjects required.');
        }

        // Set student only to subjectAssigned
        $student->subjects()->sync([$subjectAssigned->id]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.subject', $subjectUnassigned));

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('error');
    }
}
