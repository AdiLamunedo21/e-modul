<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    private function getAdmin(): Admin
    {
        $admin = Admin::first();
        if (!$admin) {
            $admin = Admin::create([
                'name' => 'Admin Supervisi',
                'identity_number' => 'ADM_TEST_001',
                'password' => Hash::make('password'),
            ]);
        }
        return $admin;
    }

    public function test_unauthenticated_user_cannot_access_management_pages()
    {
        $this->get(route('admin.teachers.index'))->assertRedirect(route('login.admin'));
        $this->get(route('admin.students.index'))->assertRedirect(route('login.admin'));
    }

    public function test_admin_can_view_teachers_index()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.teachers.index'));

        $response->assertStatus(200);
        $response->assertSee('Master Data & Registrasi Guru', false);
        $response->assertSee('Daftarkan Guru Baru', false);
    }

    public function test_admin_can_register_new_teacher_with_subjects()
    {
        $admin = $this->getAdmin();
        $subject = Subject::first();

        $nip = 'NIP_TEST_' . rand(10000, 99999);
        $payload = [
            'name'            => 'Guru Pengajar Baru, M.Kom.',
            'identity_number' => $nip,
            'password'        => 'password123',
            'subject_ids'     => $subject ? [$subject->id] : [],
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.teachers.store'), $payload);

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teachers', [
            'name'            => 'Guru Pengajar Baru, M.Kom.',
            'identity_number' => $nip,
        ]);

        $newTeacher = Teacher::where('identity_number', $nip)->first();
        if ($subject) {
            $this->assertTrue($newTeacher->subjects->contains($subject->id));
        }

        // Clean up
        $newTeacher->subjects()->detach();
        $newTeacher->delete();
    }

    public function test_admin_can_update_teacher()
    {
        $admin = $this->getAdmin();
        $nip = 'NIP_TEST_UPDATE_' . rand(10000, 99999);

        $teacher = Teacher::create([
            'name' => 'Guru Lama',
            'identity_number' => $nip,
            'password' => Hash::make('password'),
        ]);

        $updatePayload = [
            'name'            => 'Guru Terupdate, S.Pd.',
            'identity_number' => $nip,
            'password'        => '',
            'subject_ids'     => [],
        ];

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.teachers.update', $teacher), $updatePayload);

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teachers', [
            'id'   => $teacher->id,
            'name' => 'Guru Terupdate, S.Pd.',
        ]);

        $teacher->delete();
    }

    public function test_admin_can_delete_teacher()
    {
        $admin = $this->getAdmin();
        $nip = 'NIP_TEST_DEL_' . rand(10000, 99999);

        $teacher = Teacher::create([
            'name' => 'Guru Hapus',
            'identity_number' => $nip,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.teachers.destroy', $teacher));

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('teachers', [
            'id' => $teacher->id,
        ]);
    }

    public function test_admin_can_view_students_index()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.students.index'));

        $response->assertStatus(200);
        $response->assertSee('Master Data & Registrasi Siswa', false);
        $response->assertSee('Daftarkan Siswa Baru', false);
    }

    public function test_admin_can_register_new_student_with_class()
    {
        $admin = $this->getAdmin();
        $schoolClass = SchoolClass::first();

        if (!$schoolClass) {
            $schoolClass = SchoolClass::create([
                'grade' => 'X',
                'major_name' => 'Teknik Komputer',
            ]);
        }

        $nisn = 'NISN_TEST_' . rand(10000, 99999);
        $payload = [
            'name'            => 'Siswa Baru Berbakat',
            'identity_number' => $nisn,
            'class_id'        => $schoolClass->id,
            'password'        => 'password123',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.students.store'), $payload);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'name'            => 'Siswa Baru Berbakat',
            'identity_number' => $nisn,
            'class_id'        => $schoolClass->id,
        ]);

        // Clean up
        Student::where('identity_number', $nisn)->delete();
    }

    public function test_admin_can_update_student()
    {
        $admin = $this->getAdmin();
        $schoolClass = SchoolClass::first();
        $nisn = 'NISN_TEST_UPD_' . rand(10000, 99999);

        $student = Student::create([
            'name' => 'Siswa Lama',
            'identity_number' => $nisn,
            'class_id' => $schoolClass->id,
            'password' => Hash::make('password'),
        ]);

        $updatePayload = [
            'name'            => 'Siswa Terupdate',
            'identity_number' => $nisn,
            'class_id'        => $schoolClass->id,
            'password'        => '',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.students.update', $student), $updatePayload);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'id'   => $student->id,
            'name' => 'Siswa Terupdate',
        ]);

        $student->delete();
    }

    public function test_admin_can_delete_student()
    {
        $admin = $this->getAdmin();
        $schoolClass = SchoolClass::first();
        $nisn = 'NISN_TEST_DEL_' . rand(10000, 99999);

        $student = Student::create([
            'name' => 'Siswa Hapus',
            'identity_number' => $nisn,
            'class_id' => $schoolClass->id,
            'password' => Hash::make('password'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.students.destroy', $student));

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }
}
