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
        $response->assertSee($student->name);
        $response->assertSee($student->identity_number);
        $response->assertSee('Kelas yang Anda Ikuti', false);
        $response->assertSee('Rombel Diikuti', false);
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
        $response->assertSee('Kelas', false);
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

        $student->subjects()->syncWithoutDetaching([$subject->id]);

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

    public function test_welcome_banner_only_displays_within_first_10_minutes_of_registration()
    {
        $student = Student::first();
        if (!$student) {
            $this->markTestSkipped('Student data not seeded.');
        }

        // 1. Siswa baru terdaftar (misal 2 menit lalu) -> Banner HARUS muncul
        $student->created_at = now()->subMinutes(2);
        $student->save();

        $responseNew = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));

        $responseNew->assertStatus(200);
        $responseNew->assertSee('Selamat Datang, ' . $student->name, false);

        // 2. Siswa lama terdaftar (misal 15 menit lalu) -> Banner TIDAK boleh muncul
        $student->created_at = now()->subMinutes(15);
        $student->save();

        $responseOld = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));

        $responseOld->assertStatus(200);
        $responseOld->assertDontSee('Selamat Datang, ' . $student->name, false);
    }

    public function test_student_can_open_class_detail_page_and_see_subjects_only()
    {
        $student = Student::first();
        $class = SchoolClass::first();
        $teacher = Teacher::first();
        $subject = Subject::first();
        if (!$student || !$class || !$teacher || !$subject) {
            $this->markTestSkipped('Seed data required.');
        }

        $student->joinClass($class);

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Khusus Kelas ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
        ]);

        // Buka halaman kelas -> Tampil 2 Kartu Semester
        $response = $this->actingAs($student, 'student')
            ->get(route('student.classes.show', $class->id));

        $response->assertStatus(200);
        $response->assertSee($class->full_name);
        $response->assertSee($class->code);
        $response->assertSee('Pilihan Semester Pembelajaran');
        $response->assertSee('Semester 1 (Ganjil)');
        $response->assertSee('Semester 2 (Genap)');

        // Buka semester 1 -> Tampil Daftar Mata Pelajaran
        $responseS1 = $this->actingAs($student, 'student')
            ->get(route('student.classes.show', ['class' => $class->id, 'semester' => 1]));

        $responseS1->assertStatus(200);
        $responseS1->assertSee($subject->name);
        $responseS1->assertSee('Daftar Mata Pelajaran', false);

        $module->delete();
    }

    public function test_student_can_open_class_subject_modules_page_and_see_modules()
    {
        $student = Student::first();
        $class = SchoolClass::first();
        $teacher = Teacher::first();
        $subject = Subject::first();
        if (!$student || !$class || !$teacher || !$subject) {
            $this->markTestSkipped('Seed data required.');
        }

        $student->joinClass($class);

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'title'      => 'Modul Khusus Mapel ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.classes.subject', ['class' => $class->id, 'subject' => $subject->id]));

        $response->assertStatus(200);
        $response->assertSee($class->full_name);
        $response->assertSee($subject->name);
        $response->assertSee($module->title);

        $module->delete();
    }

    public function test_student_cannot_access_unjoined_class_detail_page()
    {
        $student = Student::first();
        if (!$student) {
            $this->markTestSkipped('Student data not seeded.');
        }

        $unjoinedClass = SchoolClass::create([
            'grade'      => 'XII',
            'major_id'   => 1,
            'major_name' => 'Teknik',
            'section'    => 'UNJ_' . rand(1000, 9999),
            'code'       => 'UNJ' . rand(100, 999),
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.classes.show', $unjoinedClass->id));

        $response->assertStatus(403);

        $responseSubj = $this->actingAs($student, 'student')
            ->get(route('student.classes.subject', ['class' => $unjoinedClass->id, 'subject' => 1]));

        $responseSubj->assertStatus(403);

        $unjoinedClass->delete();
    }

    public function test_student_sees_two_semester_cards_and_can_filter_by_semester()
    {
        $student = Student::first();
        $class = SchoolClass::first();
        $teacher = Teacher::first();
        $subject = Subject::first();
        if (!$student || !$class || !$teacher || !$subject) {
            $this->markTestSkipped('Seed data required.');
        }

        $student->joinClass($class);

        $moduleS1 = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'semester'   => '1',
            'title'      => 'Modul Semester 1 ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
        ]);

        $moduleS2 = Module::create([
            'teacher_id' => $teacher->id,
            'class_id'   => $class->id,
            'subject_id' => $subject->id,
            'semester'   => '2',
            'title'      => 'Modul Semester 2 ' . uniqid(),
            'status'     => 'published',
            'has_materi' => true,
        ]);

        // 1. Buka kelas tanpa filter semester -> Tampil 2 Kartu Semester
        $response = $this->actingAs($student, 'student')
            ->get(route('student.classes.show', $class->id));

        $response->assertStatus(200);
        $response->assertSee('Semester 1 (Ganjil)');
        $response->assertSee('Semester 2 (Genap)');
        $response->assertSee('Pilihan Semester Pembelajaran');

        // 2. Filter ke Semester 1
        $responseS1 = $this->actingAs($student, 'student')
            ->get(route('student.classes.subject', ['class' => $class->id, 'subject' => $subject->id, 'semester' => 1]));

        $responseS1->assertStatus(200);
        $responseS1->assertSee($moduleS1->title);
        $responseS1->assertDontSee($moduleS2->title);

        // 3. Filter ke Semester 2
        $responseS2 = $this->actingAs($student, 'student')
            ->get(route('student.classes.subject', ['class' => $class->id, 'subject' => $subject->id, 'semester' => 2]));

        $responseS2->assertStatus(200);
        $responseS2->assertSee($moduleS2->title);
        $responseS2->assertDontSee($moduleS1->title);

        $moduleS1->delete();
        $moduleS2->delete();
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
