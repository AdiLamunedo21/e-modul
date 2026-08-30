<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
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
        $response->assertSee('Pusat Kelas Didik');
        $response->assertSee('Dikelola oleh Admin');
    }

    public function test_classes_index_filters_work()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $teacher->classes()->syncWithoutDetaching([$class->id]);

        // Filter by grade
        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.index', ['grade' => $class->grade]));

        $response->assertStatus(200);
        $response->assertSee($class->full_name);

        // Filter by search
        $responseSearch = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.classes.index', ['search' => $class->section]));

        $responseSearch->assertStatus(200);
        $responseSearch->assertSee($class->full_name);
    }

    public function test_admin_can_assign_classes_to_teacher()
    {
        $admin = \App\Models\Admin::first();
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$admin || !$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.teachers.update', $teacher), [
                'name'            => $teacher->name,
                'identity_number' => $teacher->identity_number,
                'class_ids'       => [$class->id],
            ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $this->assertTrue($teacher->fresh()->classes->contains($class->id));
    }

    public function test_teacher_can_import_modules_from_another_class()
    {
        $teacher = Teacher::first();
        $sourceModule = Module::where('teacher_id', $teacher->id)->first();
        $major = Major::first();

        if (!$teacher || !$sourceModule || !$major) {
            $this->markTestSkipped('Seed data required.');
        }

        // Buat kelas target
        $targetClass = SchoolClass::create([
            'grade'      => 'XI',
            'major_id'   => $major->id,
            'section'    => 'T' . rand(10, 99),
            'major_name' => $major->code,
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.classes.import-modules', $targetClass), [
                'module_ids' => [$sourceModule->id],
            ]);

        $response->assertRedirect(route('teacher.classes.show', ['class' => $targetClass->id, 'tab' => 'modules']));

        // Verifikasi bahwa modul telah disalin ke kelas target
        $this->assertDatabaseHas('modules', [
            'class_id'   => $targetClass->id,
            'teacher_id' => $teacher->id,
        ]);
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
        $response->assertSee('Import Modul');
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
                'id'              => $student->id,
                'name'            => $student->name,
                'identity_number' => $student->identity_number,
            ],
            'class' => [
                'id'        => $class->id,
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

    public function test_teacher_can_delete_class_and_preserve_student_accounts()
    {
        $teacher = Teacher::first();
        $major = Major::first();
        $subject = Subject::first();

        if (!$teacher || !$major || !$subject) {
            $this->markTestSkipped('Seed data required.');
        }

        // 1. Buat kelas sementara untuk dihapus
        $deleteClass = SchoolClass::create([
            'grade'      => 'XII',
            'major_id'   => $major->id,
            'section'    => 'P' . rand(10, 99),
            'major_name' => $major->code,
        ]);

        // 2. Buat siswa terdaftar di kelas ini
        $student = Student::create([
            'name'            => 'Siswa Tetap Aman ' . uniqid(),
            'identity_number' => 'NISN' . rand(100000, 999999),
            'class_id'        => $deleteClass->id,
            'password'        => bcrypt('password'),
        ]);
        $student->subjects()->attach($subject->id);

        // 3. Buat modul di kelas ini
        $module = Module::create([
            'title'      => 'Modul Kelas Hapus ' . uniqid(),
            'teacher_id' => $teacher->id,
            'class_id'   => $deleteClass->id,
            'subject_id' => $subject->id,
            'status'     => 'published',
        ]);

        // 4. Eksekusi Hapus Kelas
        $response = $this->actingAs($teacher, 'teacher')
            ->delete(route('teacher.classes.destroy', $deleteClass));

        $response->assertRedirect(route('teacher.classes.index'));
        $response->assertSessionHas('success');

        // 5. Verifikasi bahwa kelas & modul terhapus, tetapi akun siswa TETAP ADA dan class_id menjadi null
        $this->assertDatabaseMissing('classes', ['id' => $deleteClass->id]);
        $this->assertDatabaseMissing('modules', ['id' => $module->id]);
        
        $this->assertDatabaseHas('students', [
            'id'       => $student->id,
            'class_id' => null,
            'name'     => $student->name,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_teacher_classes()
    {
        $response = $this->get(route('teacher.classes.index'));
        $response->assertRedirect(route('login.teacher'));
    }
}
