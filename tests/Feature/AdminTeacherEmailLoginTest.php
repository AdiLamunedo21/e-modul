<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTeacherEmailLoginTest extends TestCase
{
    private Admin $admin;
    private Teacher $teacher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::updateOrCreate(
            ['identity_number' => '197501011999031001'],
            [
                'name'     => 'Drs. Ahmad Fauzi, M.Pd.',
                'email'    => 'ahmad@gmail.com',
                'password' => Hash::make('password'),
            ]
        );

        $this->teacher = Teacher::updateOrCreate(
            ['identity_number' => '198501152010011002'],
            [
                'name'     => 'Budi Santoso, S.Kom.',
                'email'    => 'budi@gmail.com',
                'password' => Hash::make('password'),
            ]
        );
    }

    protected function tearDown(): void
    {
        $this->admin->update(['password' => Hash::make('password')]);
        $this->teacher->update(['password' => Hash::make('password')]);
        parent::tearDown();
    }

    public function test_admin_can_login_using_email(): void
    {
        $response = $this->post(route('login.admin'), [
            'email'    => 'ahmad@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post(route('login.admin'), [
            'email'    => 'ahmad@gmail.com',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_teacher_can_login_using_email(): void
    {
        $response = $this->post(route('login.teacher'), [
            'email'    => 'budi@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertAuthenticatedAs($this->teacher, 'teacher');
    }

    public function test_teacher_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post(route('login.teacher'), [
            'email'    => 'budi@gmail.com',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('teacher');
    }

    public function test_admin_can_create_teacher_with_email(): void
    {
        $uniqueNip = '999' . rand(100000, 999999);
        $uniqueEmail = 'rudi' . rand(100, 999) . '@gmail.com';

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.teachers.store'), [
                'name'            => 'Rudi Hartono, M.Pd.',
                'email'           => $uniqueEmail,
                'identity_number' => $uniqueNip,
                'password'        => 'password123',
            ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teachers', [
            'name'            => 'Rudi Hartono, M.Pd.',
            'email'           => $uniqueEmail,
            'identity_number' => $uniqueNip,
        ]);

        // Clean up
        Teacher::where('identity_number', $uniqueNip)->delete();
    }

    public function test_admin_can_update_teacher_email(): void
    {
        $uniqueNip = '999' . rand(100000, 999999);
        $initialEmail = 'dewi' . rand(100, 999) . '@gmail.com';
        $updatedEmail = 'dewibaru' . rand(100, 999) . '@gmail.com';

        $teacher = Teacher::create([
            'name'            => 'Dewi Sartika',
            'email'           => $initialEmail,
            'identity_number' => $uniqueNip,
            'password'        => Hash::make('password123'),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.teachers.update', $teacher), [
                'name'            => 'Dewi Sartika, S.T.',
                'email'           => $updatedEmail,
                'identity_number' => $uniqueNip,
            ]);

        $response->assertRedirect(route('admin.teachers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teachers', [
            'id'    => $teacher->id,
            'email' => $updatedEmail,
        ]);

        // Clean up
        $teacher->delete();
    }

    public function test_teacher_creation_rejects_invalid_email_format(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.teachers.store'), [
                'name'            => 'Testing Guru Salah Email',
                'email'           => 'bukan-email-valid',
                'identity_number' => '999' . rand(100000, 999999),
                'password'        => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }
}
