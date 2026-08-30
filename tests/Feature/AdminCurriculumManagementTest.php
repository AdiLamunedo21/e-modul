<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCurriculumManagementTest extends TestCase
{
    private function getAdmin(): Admin
    {
        $admin = Admin::first();
        if (!$admin) {
            $admin = Admin::create([
                'name' => 'Admin Supervisi',
                'identity_number' => 'ADM_CURR_TEST',
                'password' => Hash::make('password'),
            ]);
        }
        return $admin;
    }

    public function test_unauthenticated_user_cannot_access_curriculum_pages()
    {
        $this->get(route('admin.subjects.index'))->assertRedirect(route('login.admin'));
        $this->get(route('admin.majors.index'))->assertRedirect(route('login.admin'));
        $this->get(route('admin.classes.index'))->assertRedirect(route('login.admin'));
    }

    /* ─── SUBJECT TESTS ─── */
    public function test_admin_can_view_subjects_index()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.subjects.index'));

        $response->assertStatus(200);
        $response->assertSee('Master Mata Pelajaran', false);
        $response->assertSee('Tambah Mata Pelajaran', false);
    }

    public function test_admin_can_create_subject()
    {
        $admin = $this->getAdmin();
        $code = 'SUBJ_' . substr(uniqid(), -6);

        $payload = [
            'name'        => 'Pemrograman Web Lanjut',
            'code'        => $code,
            'icon'        => '💻',
            'color'       => 'blue',
            'description' => 'Mata pelajaran pemrograman web full-stack modern.',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.subjects.store'), $payload);

        $response->assertRedirect(route('admin.subjects.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('subjects', [
            'name' => 'Pemrograman Web Lanjut',
            'code' => $code,
        ]);
    }

    public function test_admin_can_update_subject()
    {
        $admin = $this->getAdmin();
        $code = 'SU_' . substr(uniqid(), -6);

        $subject = Subject::create([
            'name'  => 'Mapel Lama',
            'code'  => $code,
            'color' => 'indigo',
        ]);

        $updatePayload = [
            'name'        => 'Mapel Terupdate',
            'code'        => $code,
            'color'       => 'emerald',
            'description' => 'Deskripsi baru',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.subjects.update', $subject), $updatePayload);

        $response->assertRedirect(route('admin.subjects.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('subjects', [
            'id'   => $subject->id,
            'name' => 'Mapel Terupdate',
        ]);
    }

    public function test_admin_can_delete_subject()
    {
        $admin = $this->getAdmin();
        $code = 'SUBJ_DEL_' . substr(uniqid(), -6);

        $subject = Subject::create([
            'name'  => 'Mapel Hapus',
            'code'  => $code,
            'color' => 'rose',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.subjects.destroy', $subject));

        $response->assertRedirect(route('admin.subjects.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
        ]);
    }

    /* ─── MAJOR TESTS ─── */
    public function test_admin_can_view_majors_index()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.majors.index'));

        $response->assertStatus(200);
        $response->assertSee('Master Jurusan & Keahlian', false);
        $response->assertSee('Tambah Jurusan Baru', false);
    }

    public function test_admin_can_create_major()
    {
        $admin = $this->getAdmin();
        $code = 'MAJ_' . substr(uniqid(), -6);

        $payload = [
            'name'        => 'Teknik Mekatronika Industri',
            'code'        => $code,
            'description' => 'Program keahlian integrasi mekanika dan elektronika.',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.majors.store'), $payload);

        $response->assertRedirect(route('admin.majors.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('majors', [
            'name' => 'Teknik Mekatronika Industri',
            'code' => $code,
        ]);
    }

    public function test_admin_can_update_major()
    {
        $admin = $this->getAdmin();
        $code = 'MAJ_UPD_' . substr(uniqid(), -6);

        $major = Major::create([
            'name' => 'Jurusan Lama',
            'code' => $code,
        ]);

        $updatePayload = [
            'name'        => 'Jurusan Terupdate',
            'code'        => $code,
            'description' => 'Deskripsi terupdate',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.majors.update', $major), $updatePayload);

        $response->assertRedirect(route('admin.majors.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('majors', [
            'id'   => $major->id,
            'name' => 'Jurusan Terupdate',
        ]);
    }

    public function test_admin_can_delete_major()
    {
        $admin = $this->getAdmin();
        $code = 'MAJ_DEL_' . substr(uniqid(), -6);

        $major = Major::create([
            'name' => 'Jurusan Hapus',
            'code' => $code,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.majors.destroy', $major));

        $response->assertRedirect(route('admin.majors.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('majors', [
            'id' => $major->id,
        ]);
    }

    /* ─── CLASS / ROMBEL TESTS ─── */
    public function test_admin_can_view_classes_index()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.classes.index'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Build Kelas & Rombongan Belajar', false);
        $response->assertSee('Build Kelas Baru', false);
    }

    public function test_admin_can_create_class_with_grade_major_and_section()
    {
        $admin = $this->getAdmin();
        $major = Major::first();

        if (!$major) {
            $major = Major::create([
                'name' => 'PPLG',
                'code' => 'PPLG',
            ]);
        }

        $payload = [
            'grade'    => 'X',
            'major_id' => $major->id,
            'section'  => '3',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.classes.store'), $payload);

        $response->assertRedirect(route('admin.classes.index'));
        $response->assertSessionHas('success');

        $newClass = SchoolClass::where('section', '3')->where('major_id', $major->id)->first();
        $this->assertNotNull($newClass);
        $this->assertNotEmpty($newClass->code);
        $this->assertEquals(6, strlen($newClass->code));

        $this->assertDatabaseHas('classes', [
            'grade'    => 'X',
            'major_id' => $major->id,
            'section'  => '3',
        ]);
    }

    public function test_admin_can_regenerate_class_code()
    {
        $admin = $this->getAdmin();
        $class = SchoolClass::first();
        if (!$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $oldCode = $class->code;

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.classes.regenerate-code', $class));

        $response->assertRedirect(route('admin.classes.index'));
        $this->assertNotEquals($oldCode, $class->fresh()->code);
    }

    public function test_admin_can_update_class()
    {
        $admin = $this->getAdmin();
        $major = Major::first();

        $class = SchoolClass::create([
            'grade'      => 'XI',
            'major_id'   => $major->id,
            'section'    => '1',
            'major_name' => $major->code,
        ]);

        $updatePayload = [
            'grade'    => 'XII',
            'major_id' => $major->id,
            'section'  => '2',
        ];

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.classes.update', $class), $updatePayload);

        $response->assertRedirect(route('admin.classes.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('classes', [
            'id'      => $class->id,
            'grade'   => 'XII',
            'section' => '2',
        ]);
    }

    public function test_admin_can_delete_class()
    {
        $admin = $this->getAdmin();
        $major = Major::first();

        $class = SchoolClass::create([
            'grade'      => 'X',
            'major_id'   => $major->id,
            'section'    => '9',
            'major_name' => $major->code,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.classes.destroy', $class));

        $response->assertRedirect(route('admin.classes.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('classes', [
            'id' => $class->id,
        ]);
    }
}
