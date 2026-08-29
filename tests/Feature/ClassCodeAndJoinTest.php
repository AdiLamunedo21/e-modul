<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClassCodeAndJoinTest extends TestCase
{
    public function test_class_creation_automatically_generates_unique_code()
    {
        $major = Major::first();
        if (!$major) {
            $major = Major::create(['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL']);
        }

        $class = SchoolClass::create([
            'grade'      => 'X',
            'major_id'   => $major->id,
            'section'    => 'CodeTest1',
            'major_name' => $major->code,
        ]);

        $this->assertNotEmpty($class->code);
        $this->assertEquals(6, strlen($class->code));
        $this->assertEquals(strtoupper($class->code), $class->code);

        $this->assertDatabaseHas('classes', [
            'id'   => $class->id,
            'code' => $class->code,
        ]);
    }

    public function test_teacher_creating_class_via_controller_generates_code()
    {
        $teacher = Teacher::first();
        $major = Major::first();
        if (!$teacher || !$major) {
            $this->markTestSkipped('Seed data required.');
        }

        $section = 'C' . rand(100, 999);

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.classes.store'), [
                'grade'    => 'XI',
                'major_id' => $major->id,
                'section'  => $section,
            ]);

        $newClass = SchoolClass::where('section', $section)->first();
        $this->assertNotNull($newClass);
        $this->assertNotEmpty($newClass->code);

        $response->assertRedirect(route('teacher.classes.show', $newClass));
        $response->assertSessionHas('success');
    }

    public function test_teacher_can_regenerate_class_code()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $oldCode = $class->code;

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.classes.regenerate-code', $class));

        $response->assertRedirect();
        $class->refresh();

        $this->assertNotEmpty($class->code);
        $this->assertNotEquals($oldCode, $class->code);
    }

    public function test_teacher_classes_index_displays_class_code()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.index'));

        $response->assertStatus(200);
        $response->assertSee('Kode Gabung Kelas');
        $response->assertSee($class->code);
    }

    public function test_teacher_class_show_displays_class_code()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.show', $class));

        $response->assertStatus(200);
        $response->assertSee('KODE KELAS:');
        $response->assertSee($class->code);
    }

    public function test_student_can_view_registration_page()
    {
        $response = $this->get(route('register.student'));
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Siswa Baru');
    }

    public function test_student_can_register_with_valid_class_code_and_join_immediately()
    {
        $class = SchoolClass::first();
        if (!$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $nisn = 'NISN' . rand(1000000, 9999999);

        $response = $this->post(route('register.student'), [
            'name'                  => 'Siswa Registrasi Berkelas',
            'identity_number'       => $nisn,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'class_code'            => $class->code,
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated('student');

        $student = Student::where('identity_number', $nisn)->first();
        $this->assertNotNull($student);
        $this->assertEquals($class->id, $student->class_id);
    }

    public function test_student_can_register_without_class_code_and_lands_on_empty_dashboard()
    {
        $nisn = 'NISN' . rand(1000000, 9999999);

        $response = $this->post(route('register.student'), [
            'name'                  => 'Siswa Baru Tanpa Kelas',
            'identity_number'       => $nisn,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'class_code'            => '',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated('student');

        $student = Student::where('identity_number', $nisn)->first();
        $this->assertNotNull($student);
        $this->assertNull($student->class_id);

        // Akses dashboard siswa yang kosong
        $dashboardResponse = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));

        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Gabung ke Kelas Pembelajaran');
        $dashboardResponse->assertSee('class_code');
    }

    public function test_student_on_empty_dashboard_can_join_class_using_code()
    {
        $class = SchoolClass::first();
        if (!$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $student = Student::create([
            'name'            => 'Siswa Kosong Mandiri',
            'identity_number' => 'NISN' . rand(1000000, 9999999),
            'password'        => Hash::make('password123'),
            'class_id'        => null,
        ]);

        $response = $this->actingAs($student, 'student')
            ->post(route('student.join-class'), [
                'class_code' => strtolower($class->code), // Menguji case-insensitive
            ]);

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('success');

        $student->refresh();
        $this->assertEquals($class->id, $student->class_id);
    }

    public function test_joining_class_with_invalid_code_returns_error()
    {
        $student = Student::create([
            'name'            => 'Siswa Invalid Test',
            'identity_number' => 'NISN' . rand(1000000, 9999999),
            'password'        => Hash::make('password123'),
            'class_id'        => null,
        ]);

        $response = $this->actingAs($student, 'student')
            ->post(route('student.join-class'), [
                'class_code' => 'INVALID999',
            ]);

        $response->assertSessionHasErrors('class_code');
        $student->refresh();
        $this->assertNull($student->class_id);
    }

    public function test_student_can_join_multiple_classes_and_see_all_classes_and_modules_on_dashboard()
    {
        $teacher = Teacher::first();
        $major = Major::first();
        $subject = Subject::first();

        if (!$teacher || !$major || !$subject) {
            $this->markTestSkipped('Seed data required.');
        }

        // 1. Buat Kelas A dan Modul A
        $classA = SchoolClass::create([
            'grade'      => 'X',
            'major_id'   => $major->id,
            'section'    => 'MultiA_' . rand(100, 999),
            'major_name' => $major->code,
        ]);
        $moduleA = Module::create([
            'title'      => 'Modul Kelas A Unik ' . uniqid(),
            'teacher_id' => $teacher->id,
            'class_id'   => $classA->id,
            'subject_id' => $subject->id,
            'status'     => 'published',
        ]);

        // 2. Buat Kelas B dan Modul B
        $classB = SchoolClass::create([
            'grade'      => 'XI',
            'major_id'   => $major->id,
            'section'    => 'MultiB_' . rand(100, 999),
            'major_name' => $major->code,
        ]);
        $moduleB = Module::create([
            'title'      => 'Modul Kelas B Unik ' . uniqid(),
            'teacher_id' => $teacher->id,
            'class_id'   => $classB->id,
            'subject_id' => $subject->id,
            'status'     => 'published',
        ]);

        // 3. Buat Kelas C dan Modul C
        $classC = SchoolClass::create([
            'grade'      => 'XII',
            'major_id'   => $major->id,
            'section'    => 'MultiC_' . rand(100, 999),
            'major_name' => $major->code,
        ]);
        $moduleC = Module::create([
            'title'      => 'Modul Kelas C Unik ' . uniqid(),
            'teacher_id' => $teacher->id,
            'class_id'   => $classC->id,
            'subject_id' => $subject->id,
            'status'     => 'published',
        ]);

        // 4. Buat Siswa baru
        $student = Student::create([
            'name'            => 'Siswa Multi Kelas Test',
            'identity_number' => 'NISN' . rand(1000000, 9999999),
            'password'        => Hash::make('password123'),
            'class_id'        => null,
        ]);

        // 5. Siswa bergabung ke Kelas A
        $this->actingAs($student, 'student')
            ->post(route('student.join-class'), ['class_code' => $classA->code]);
        $student->refresh();
        $this->assertCount(1, $student->classes);

        // Dashboard harus memuat Kelas A & Modul A
        $resp1 = $this->actingAs($student, 'student')->get(route('student.dashboard'));
        $resp1->assertStatus(200);
        $resp1->assertSee($classA->full_name);

        // 6. Siswa bergabung ke Kelas B
        $this->actingAs($student, 'student')
            ->post(route('student.join-class'), ['class_code' => $classB->code]);
        $student->refresh();
        $this->assertCount(2, $student->classes);

        // Dashboard harus memuat KEDUA Kelas (Kelas A DAN Kelas B)
        $resp2 = $this->actingAs($student, 'student')->get(route('student.dashboard'));
        $resp2->assertStatus(200);
        $resp2->assertSee($classA->full_name);
        $resp2->assertSee($classB->full_name);

        // 7. Siswa bergabung ke Kelas C
        $this->actingAs($student, 'student')
            ->post(route('student.join-class'), ['class_code' => $classC->code]);
        $student->refresh();
        $this->assertCount(3, $student->classes);

        // Dashboard harus memuat KETIGA Kelas (Kelas A, Kelas B, DAN Kelas C)
        $resp3 = $this->actingAs($student, 'student')->get(route('student.dashboard'));
        $resp3->assertStatus(200);
        $resp3->assertSee($classA->full_name);
        $resp3->assertSee($classB->full_name);
        $resp3->assertSee($classC->full_name);

        // 8. Filter per kelas di Dashboard bekerja
        $respFilterA = $this->actingAs($student, 'student')
            ->get(route('student.dashboard', ['class_id' => $classA->id]));
        $respFilterA->assertStatus(200);
        $respFilterA->assertSee($classA->full_name);

        // 9. Siswa dapat mengakses seluruh modul dari ketiga kelas tanpa 403 Forbidden
        $this->actingAs($student, 'student')
            ->get(route('student.modules.show', $moduleA))
            ->assertStatus(200);

        $this->actingAs($student, 'student')
            ->get(route('student.modules.show', $moduleB))
            ->assertStatus(200);

        $this->actingAs($student, 'student')
            ->get(route('student.modules.show', $moduleC))
            ->assertStatus(200);
    }

    public function test_student_can_leave_class_and_cleans_up_grades_without_deleting_class_or_modules()
    {
        $teacher = Teacher::first();
        $major = Major::first();
        $subject = Subject::first();

        if (!$teacher || !$major || !$subject) {
            $this->markTestSkipped('Seed data required.');
        }

        // 1. Buat Kelas A & Modul A
        $classA = SchoolClass::create([
            'grade'      => 'X',
            'major_id'   => $major->id,
            'section'    => 'LeaveA_' . rand(1000, 9999),
            'major_name' => $major->code,
        ]);
        $moduleA = Module::create([
            'title'      => 'Modul Kelas A Leave Test ' . uniqid(),
            'teacher_id' => $teacher->id,
            'class_id'   => $classA->id,
            'subject_id' => $subject->id,
            'status'     => 'published',
            'has_materi' => true,
        ]);

        // 2. Buat Kelas B & Modul B
        $classB = SchoolClass::create([
            'grade'      => 'XI',
            'major_id'   => $major->id,
            'section'    => 'LeaveB_' . rand(1000, 9999),
            'major_name' => $major->code,
        ]);
        $moduleB = Module::create([
            'title'      => 'Modul Kelas B Leave Test ' . uniqid(),
            'teacher_id' => $teacher->id,
            'class_id'   => $classB->id,
            'subject_id' => $subject->id,
            'status'     => 'published',
            'has_materi' => true,
        ]);

        // 3. Siswa mendaftar & gabung ke kedua kelas
        $student = Student::create([
            'name'            => 'Siswa Keluar Kelas Test',
            'identity_number' => 'NISN' . rand(1000000, 9999999),
            'password'        => Hash::make('password123'),
            'class_id'        => null,
        ]);
        $student->joinClass($classA);
        $student->joinClass($classB);

        $this->assertCount(2, $student->fresh()->classes);

        // 4. Buat data nilai/hasil belajar dan submission untuk Modul A
        \App\Models\StudentResult::create([
            'student_id'      => $student->id,
            'module_id'       => $moduleA->id,
            'pre_test_score'  => 85,
            'summative_score' => 90,
            'grading_status'  => 'graded',
        ]);
        \App\Models\VideoSummary::create([
            'student_id'   => $student->id,
            'module_id'    => $moduleA->id,
            'summary_text' => 'Ringkasan video modul A',
        ]);

        // Pastikan data nilai tercatat
        $this->assertDatabaseHas('student_results', [
            'student_id' => $student->id,
            'module_id'  => $moduleA->id,
        ]);
        $this->assertDatabaseHas('video_summaries', [
            'student_id' => $student->id,
            'module_id'  => $moduleA->id,
        ]);

        // 5. Siswa keluar dari Kelas A
        $response = $this->actingAs($student, 'student')
            ->post(route('student.classes.leave', $classA));

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('success');

        // 6. Verifikasi: Siswa sudah tidak terdaftar di Kelas A, tapi MASIH terdaftar di Kelas B
        $student->refresh();
        $this->assertFalse(in_array($classA->id, $student->joinedClassIds()));
        $this->assertTrue(in_array($classB->id, $student->joinedClassIds()));

        // 7. Verifikasi: Nilai & submission untuk modul di Kelas A telah dihapus dari akun siswa
        $this->assertDatabaseMissing('student_results', [
            'student_id' => $student->id,
            'module_id'  => $moduleA->id,
        ]);
        $this->assertDatabaseMissing('video_summaries', [
            'student_id' => $student->id,
            'module_id'  => $moduleA->id,
        ]);

        // 8. Verifikasi: Kelas A dan Modul A TETAP ADA di database (tidak terhapus untuk guru)
        $this->assertDatabaseHas('classes', [
            'id' => $classA->id,
        ]);
        $this->assertDatabaseHas('modules', [
            'id' => $moduleA->id,
        ]);

        // Cleanup
        $moduleA->delete();
        $moduleB->delete();
        $classA->delete();
        $classB->delete();
        $student->delete();
    }

    public function test_student_cannot_leave_a_class_they_have_not_joined()
    {
        $major = Major::first();
        if (!$major) {
            $this->markTestSkipped('Major seed required.');
        }

        $unjoinedClass = SchoolClass::create([
            'grade'      => 'X',
            'major_id'   => $major->id,
            'section'    => 'Unjoined_' . rand(1000, 9999),
            'major_name' => $major->code,
        ]);

        $student = Student::create([
            'name'            => 'Siswa Non Member',
            'identity_number' => 'NISN' . rand(1000000, 9999999),
            'password'        => Hash::make('password123'),
            'class_id'        => null,
        ]);

        $response = $this->actingAs($student, 'student')
            ->post(route('student.classes.leave', $unjoinedClass));

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('error');

        $unjoinedClass->delete();
        $student->delete();
    }
}
