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
        $module = Module::where('status', 'published')->first();
        if ($module) {
            $resModule = $this->get(route('student.modules.show', $module));
            $resModule->assertRedirect(route('login.student'));
        }
    }

    public function test_redundant_subject_module_route_is_removed()
    {
        $student = Student::first();
        $subject = Subject::first();
        if (!$student || !$subject) {
            $this->markTestSkipped('Student and Subject required.');
        }

        // Akses route lama /student/modules/subject/{id} yang sudah dihapus
        $response = $this->actingAs($student, 'student')
            ->get("/student/modules/subject/{$subject->id}");

        $response->assertStatus(404);
    }


    public function test_authenticated_student_can_access_assigned_module_detail()
    {
        $student = Student::first();
        $class = SchoolClass::first();
        $teacher = Teacher::first();
        $subject = Subject::first();
        if (!$student || !$class || !$teacher || !$subject) {
            $this->markTestSkipped('Seed required.');
        }

        $student->classes()->syncWithoutDetaching([$class->id]);

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'title'      => 'Test Modul Detail ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.modules.show', $module));

        $response->assertStatus(200);
        $response->assertSee($module->title);

        $module->delete();
    }
}
