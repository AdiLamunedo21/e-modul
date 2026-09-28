<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTeacherPlottingTest extends TestCase
{
    private function createAdmin(): Admin
    {
        return Admin::firstOrCreate(
            ['identity_number' => 'admin_test_nip'],
            [
                'name' => 'Admin Testing',
                'password' => Hash::make('password123'),
                'is_super_admin' => true,
            ]
        );
    }

    public function test_admin_can_update_teacher_subjects_and_classes()
    {
        $admin = $this->createAdmin();

        $teacher = Teacher::create([
            'name' => 'Guru Test Update',
            'identity_number' => 'NIP_TEST_' . uniqid(),
            'password' => Hash::make('password123'),
        ]);

        $subject1 = Subject::firstOrCreate(['code' => 'TEST_S1'], ['name' => 'Mapel Test 1']);
        $subject2 = Subject::firstOrCreate(['code' => 'TEST_S2'], ['name' => 'Mapel Test 2']);
        $class = SchoolClass::first();

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.teachers.update', $teacher), [
            'name' => 'Guru Test Update Edited',
            'identity_number' => $teacher->identity_number,
            'subject_ids' => [$subject1->id, $subject2->id],
            'class_ids' => [$class->id],
        ]);

        $response->assertRedirect(route('admin.teachers.index'));

        $teacher->refresh();
        $this->assertEquals('Guru Test Update Edited', $teacher->name);
        $this->assertTrue($teacher->subjects->contains($subject1->id));
        $this->assertTrue($teacher->subjects->contains($subject2->id));
        $this->assertTrue($teacher->classes->contains($class->id));
    }

    public function test_teacher_module_subject_is_preserved_when_admin_updates()
    {
        $admin = $this->createAdmin();

        $teacher = Teacher::create([
            'name' => 'Guru Module Preserved',
            'identity_number' => 'NIP_TEST_' . uniqid(),
            'password' => Hash::make('password123'),
        ]);

        $subjectModule = Subject::firstOrCreate(['code' => 'TEST_MOD'], ['name' => 'Mapel Modul Guru']);
        $subjectAdmin = Subject::firstOrCreate(['code' => 'TEST_ADM'], ['name' => 'Mapel Ditambah Admin']);
        $class = SchoolClass::first();

        // Buat modul yang mengampu subjectModule
        Module::create([
            'teacher_id' => $teacher->id,
            'class_id' => $class->id,
            'subject_id' => $subjectModule->id,
            'title' => 'Modul Penjaga Mapel',
            'semester' => '1',
            'status' => 'draft',
        ]);

        // Admin hanya mengirim subjectAdmin (tanpa mencentang subjectModule)
        $this->actingAs($admin, 'admin')->patch(route('admin.teachers.update', $teacher), [
            'name' => $teacher->name,
            'identity_number' => $teacher->identity_number,
            'subject_ids' => [$subjectAdmin->id],
            'class_ids' => [$class->id],
        ]);

        $teacher->refresh();
        // Keduanya harus tetap ada di pivot subjects
        $this->assertTrue($teacher->subjects->contains($subjectAdmin->id));
        $this->assertTrue($teacher->subjects->contains($subjectModule->id));
        $this->assertContains($subjectModule->id, $teacher->allAssignedSubjectIds());
        $this->assertContains($subjectAdmin->id, $teacher->allAssignedSubjectIds());
    }

    public function test_creating_module_auto_syncs_subject_to_teacher_pivot()
    {
        $teacher = Teacher::create([
            'name' => 'Guru Auto Sync Module',
            'identity_number' => 'NIP_TEST_' . uniqid(),
            'password' => Hash::make('password123'),
        ]);

        $subject = Subject::firstOrCreate(['code' => 'TEST_SYNC'], ['name' => 'Mapel Auto Sync']);
        $class = SchoolClass::first();

        $this->actingAs($teacher, 'teacher')->post(route('teacher.modules.store'), [
            'title' => 'Modul Baru Auto Sync',
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'semester' => '1',
        ]);

        $teacher->refresh();
        $this->assertTrue($teacher->subjects->contains($subject->id));
        $this->assertTrue($teacher->classes->contains($class->id));
    }

    public function test_admin_can_view_teacher_module_content_from_teacher_page()
    {
        $admin = $this->createAdmin();

        $teacher = Teacher::create([
            'name' => 'Guru Inspeksi Modul',
            'identity_number' => 'NIP_TEST_' . uniqid(),
            'password' => Hash::make('password123'),
        ]);

        $subject = Subject::firstOrCreate(['code' => 'TEST_INSP'], ['name' => 'Mapel Inspeksi']);
        $class = SchoolClass::first();

        $module = Module::create([
            'teacher_id' => $teacher->id,
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'title' => 'Modul Khusus Inspeksi Admin Master',
            'semester' => '1',
            'status' => 'draft',
        ]);

        // 1. Cek halaman detail guru menampilkan link & tombol Lihat Isi Modul
        $responseShowTeacher = $this->actingAs($admin, 'admin')->get(route('admin.teachers.show', $teacher));
        $responseShowTeacher->assertStatus(200);
        $responseShowTeacher->assertSee('Modul Khusus Inspeksi Admin Master');
        $responseShowTeacher->assertSee(route('admin.teachers.modules.show', [$teacher, $module]));
        $responseShowTeacher->assertSee('Lihat Isi Modul');

        // 2. Akses isi modul oleh admin master
        $responseModule = $this->actingAs($admin, 'admin')->get(route('admin.teachers.modules.show', [$teacher, $module]));
        $responseModule->assertStatus(200);
        $responseModule->assertSee('Pratinjau E-Modul Pembelajaran');
        $responseModule->assertSee('Mode Supervisi Admin');
        $responseModule->assertSee('Modul Khusus Inspeksi Admin Master');
        $responseModule->assertSee('Kembali ke Detail Guru');
    }
}

