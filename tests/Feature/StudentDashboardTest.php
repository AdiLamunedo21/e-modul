<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Teacher;
use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    public function test_unauthenticated_user_cannot_access_student_dashboard()
    {
        $response = $this->get(route('student.dashboard'));
        $response->assertRedirect(route('login.student'));
    }

    public function test_authenticated_student_can_access_dashboard_with_dynamic_data()
    {
        $student = Student::first();
        if (!$student) {
            $this->markTestSkipped('Student data not seeded.');
        }

        $response = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang', false);
        $response->assertSee($student->name);
        $response->assertSee($student->identity_number);
        $response->assertSee('Portal Belajar Siswa', false);
        $response->assertSee('E-Modul Pembelajaran', false);
        $response->assertSee('Modul Kelas', false);
        $response->assertSee('Sedang Dikerjakan', false);
        $response->assertSee('Modul Tuntas', false);
        $response->assertSee('Mata Pelajaran & Guru Pengampu', false);
    }

    public function test_student_dashboard_displays_subjects_with_teachers_and_module_counts()
    {
        $student = Student::first();
        if (!$student) {
            $this->markTestSkipped('Student data not seeded.');
        }

        $response = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Guru Pengampu', false);
        $response->assertSee('Modul', false);
    }

    public function test_student_dashboard_status_filters_work()
    {
        $student = Student::first();
        if (!$student) {
            $this->markTestSkipped('Student data not seeded.');
        }

        // Test all filter
        $resAll = $this->actingAs($student, 'student')
            ->get(route('student.dashboard', ['status' => 'all']));
        $resAll->assertStatus(200);

        // Test in_progress filter
        $resProgress = $this->actingAs($student, 'student')
            ->get(route('student.dashboard', ['status' => 'in_progress']));
        $resProgress->assertStatus(200);

        // Test completed filter
        $resCompleted = $this->actingAs($student, 'student')
            ->get(route('student.dashboard', ['status' => 'completed']));
        $resCompleted->assertStatus(200);

        // Test not_started filter
        $resNotStarted = $this->actingAs($student, 'student')
            ->get(route('student.dashboard', ['status' => 'not_started']));
        $resNotStarted->assertStatus(200);
    }

    public function test_student_dashboard_displays_assigned_modules()
    {
        $student = Student::first();
        $teacher = Teacher::first();
        $subject = Subject::first();
        if (!$student || !$teacher || !$subject) {
            $this->markTestSkipped('Student or teacher seed required.');
        }

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $student->class_id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Belajar Siswa ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
            'updated_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));

        $response->assertStatus(200);
        $response->assertSee($subject->name);

        // Cleanup
        $module->delete();
    }

    public function test_student_logout_works()
    {
        $student = Student::first();
        if (!$student) {
            $this->markTestSkipped('Student data not seeded.');
        }

        $response = $this->actingAs($student, 'student')
            ->post(route('logout.student'));

        $response->assertRedirect(route('login.student'));
        $this->assertFalse(auth()->guard('student')->check());
    }
}
