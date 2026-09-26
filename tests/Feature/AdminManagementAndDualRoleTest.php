<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminManagementAndDualRoleTest extends TestCase
{
    private Admin $primaryAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan ada admin utama untuk pengujian
        $this->primaryAdmin = Admin::firstOrCreate(
            ['identity_number' => '197501011999031001'],
            [
                'name'     => 'Drs. Ahmad Fauzi, M.Pd.',
                'password' => Hash::make('password'),
            ]
        );
    }

    protected function tearDown(): void
    {
        // Bersihkan data dummy pengujian (NIP yang berawalan 888xxx atau 777xxx)
        Admin::where('identity_number', 'like', '888%')
            ->orWhere('identity_number', 'like', '777%')
            ->delete();

        Teacher::where('identity_number', 'like', '888%')
            ->orWhere('identity_number', 'like', '777%')
            ->delete();

        parent::tearDown();
    }

    public function test_unauthenticated_user_cannot_access_admins_page()
    {
        $this->get(route('admin.admins.index'))->assertRedirect(route('login.admin'));
    }

    public function test_admin_can_view_admins_index()
    {
        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->get(route('admin.admins.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Administrator', false);
        $response->assertSee('Total Administrator', false);
        $response->assertSee('Merangkap Guru', false);
        $response->assertSee($this->primaryAdmin->name, false);
    }

    public function test_admin_can_create_new_admin_manually()
    {
        $testNip = '888' . rand(10000000, 99999999);

        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->post(route('admin.admins.store'), [
                'source_type'           => 'manual',
                'name'                  => 'Admin Cadangan Satu',
                'identity_number'       => $testNip,
                'password'              => 'admin123',
                'password_confirmation' => 'admin123',
            ]);

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admins', [
            'name'            => 'Admin Cadangan Satu',
            'identity_number' => $testNip,
        ]);
    }

    public function test_admin_can_create_admin_from_existing_teacher_with_same_nip()
    {
        $teacherNip = '888' . rand(10000000, 99999999);
        $teacher = Teacher::create([
            'name'            => 'Guru Calon Admin',
            'identity_number' => $teacherNip,
            'password'        => Hash::make('gurupassword123'),
        ]);

        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->post(route('admin.admins.store'), [
                'source_type' => 'teacher',
                'teacher_id'  => $teacher->id,
            ]);

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admins', [
            'name'            => 'Guru Calon Admin',
            'identity_number' => $teacherNip,
        ]);

        // Verifikasi kata sandi di admin sama dengan guru
        $createdAdmin = Admin::where('identity_number', $teacherNip)->first();
        $this->assertTrue(Hash::check('gurupassword123', $createdAdmin->password));
    }

    public function test_admin_can_promote_teacher_via_make_admin_endpoint()
    {
        $teacherNip = '888' . rand(10000000, 99999999);
        $teacher = Teacher::create([
            'name'            => 'Guru Promosi',
            'identity_number' => $teacherNip,
            'password'        => Hash::make('password123'),
        ]);

        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->post(route('admin.teachers.make-admin', $teacher));

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admins', [
            'name'            => 'Guru Promosi',
            'identity_number' => $teacherNip,
        ]);
    }

    public function test_admin_can_register_admin_as_teacher_via_make_teacher_endpoint()
    {
        $adminNip = '888' . rand(10000000, 99999999);
        $secondaryAdmin = Admin::create([
            'name'            => 'Admin Ingin Mengajar',
            'identity_number' => $adminNip,
            'password'        => Hash::make('rahasia456'),
        ]);

        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->post(route('admin.admins.make-teacher', $secondaryAdmin));

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teachers', [
            'name'            => 'Admin Ingin Mengajar',
            'identity_number' => $adminNip,
        ]);

        $teacher = Teacher::where('identity_number', $adminNip)->first();
        $this->assertTrue(Hash::check('rahasia456', $teacher->password));
    }

    public function test_admin_cannot_delete_themselves()
    {
        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->delete(route('admin.admins.destroy', $this->primaryAdmin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('admins', ['id' => $this->primaryAdmin->id]);
    }

    public function test_admin_cannot_delete_the_last_admin()
    {
        // Pastikan hanya ada 1 admin
        Admin::where('id', '!=', $this->primaryAdmin->id)->delete();

        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->delete(route('admin.admins.destroy', $this->primaryAdmin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('admins', ['id' => $this->primaryAdmin->id]);
    }

    public function test_admin_can_update_secondary_admin()
    {
        $testNip = '888' . rand(10000000, 99999999);
        $secondaryAdmin = Admin::create([
            'name'            => 'Admin Lama',
            'identity_number' => $testNip,
            'password'        => Hash::make('password123'),
        ]);

        $updatedNip = '888' . rand(10000000, 99999999);

        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->patch(route('admin.admins.update', $secondaryAdmin), [
                'name'            => 'Admin Baru Terupdate',
                'identity_number' => $updatedNip,
                'password'        => 'newpassword789',
            ]);

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        $secondaryAdmin->refresh();
        $this->assertEquals('Admin Baru Terupdate', $secondaryAdmin->name);
        $this->assertEquals($updatedNip, $secondaryAdmin->identity_number);
        $this->assertTrue(Hash::check('newpassword789', $secondaryAdmin->password));
    }

    public function test_admin_can_delete_secondary_admin_without_deleting_teacher()
    {
        $dualNip = '888' . rand(10000000, 99999999);

        $secondaryAdmin = Admin::create([
            'name'            => 'Admin Cadangan Dual Role',
            'identity_number' => $dualNip,
            'password'        => Hash::make('password123'),
        ]);

        $teacher = Teacher::create([
            'name'            => 'Admin Cadangan Dual Role',
            'identity_number' => $dualNip,
            'password'        => Hash::make('password123'),
        ]);

        $response = $this->actingAs($this->primaryAdmin, 'admin')
            ->delete(route('admin.admins.destroy', $secondaryAdmin));

        $response->assertRedirect(route('admin.admins.index'));
        $response->assertSessionHas('success');

        // Admin terhapus, tetapi Teacher tetap ada
        $this->assertDatabaseMissing('admins', ['id' => $secondaryAdmin->id]);
        $this->assertDatabaseHas('teachers', ['id' => $teacher->id]);
    }

    public function test_dual_role_admin_can_switch_to_teacher_portal()
    {
        $dualNip = '888' . rand(10000000, 99999999);

        $admin = Admin::create([
            'name'            => 'User Dual Role',
            'identity_number' => $dualNip,
            'password'        => Hash::make('password123'),
        ]);

        $teacher = Teacher::create([
            'name'            => 'User Dual Role',
            'identity_number' => $dualNip,
            'password'        => Hash::make('password123'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.switch-to-teacher'));

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertTrue(Auth::guard('teacher')->check());
        $this->assertEquals($teacher->id, Auth::guard('teacher')->id());
    }

    public function test_dual_role_teacher_can_switch_to_admin_panel()
    {
        $dualNip = '888' . rand(10000000, 99999999);

        $admin = Admin::create([
            'name'            => 'User Dual Role 2',
            'identity_number' => $dualNip,
            'password'        => Hash::make('password123'),
        ]);

        $teacher = Teacher::create([
            'name'            => 'User Dual Role 2',
            'identity_number' => $dualNip,
            'password'        => Hash::make('password123'),
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.switch-to-admin'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertEquals($admin->id, Auth::guard('admin')->id());
    }
}
