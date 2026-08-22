<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Teacher;
use Tests\TestCase;

class TeacherClassTest extends TestCase
{
    public function test_teacher_can_access_classes_index()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.index'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Kelas Binaan', false);
        $response->assertSee('Direktori Siswa', false);
        $response->assertSee('Kelas Binaan');
    }

    public function test_classes_index_filters_work()
    {
        $teacher = Teacher::first();
        $class = $teacher ? $teacher->assignedClasses()->first() : null;
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        // Filter by grade
        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.index', ['grade' => $class->grade]));

        $response->assertStatus(200);
        $response->assertSee($class->full_name);

        // Filter by search
        $responseSearch = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.index', ['search' => $class->major_name]));

        $responseSearch->assertStatus(200);
        $responseSearch->assertSee($class->full_name);
    }

    public function test_teacher_can_access_class_show_detail()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.show', $class));

        $response->assertStatus(200);
        $response->assertSee($class->full_name);
        $response->assertSee('Direktori Siswa');
        $response->assertSee('Portofolio Modul Guru');
    }

    public function test_teacher_can_fetch_student_academic_summary_json()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        $student = Student::where('class_id', $class->id)->first();

        if (!$teacher || !$class || !$student) {
            $this->markTestSkipped('Seed data required.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.student.summary', [$class, $student]));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'identity_number' => $student->identity_number,
            ],
            'class' => [
                'id' => $class->id,
                'full_name' => $class->full_name,
            ],
        ]);
        $response->assertJsonStructure([
            'success',
            'student',
            'class',
            'modules_summary',
            'overall_avg',
            'kktp_status',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_teacher_classes()
    {
        $response = $this->get(route('teacher.classes.index'));
        $response->assertRedirect(route('login.teacher'));
    }
}
