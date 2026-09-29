<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UniqueRegistrationCheckTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_detect_duplicate_student_nisn(): void
    {
        Student::create([
            'name' => 'Budi Santoso',
            'identity_number' => '0012345678',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->getJson(route('api.check-unique', [
            'type' => 'student_nisn',
            'value' => '0012345678',
        ]));

        $response->assertOk()
            ->assertJson([
                'exists' => true,
                'message' => 'tidak bisa submit karena data ini sudah digunakan',
            ]);

        $responseAvailable = $this->getJson(route('api.check-unique', [
            'type' => 'student_nisn',
            'value' => '9999999999',
        ]));

        $responseAvailable->assertOk()
            ->assertJson([
                'exists' => false,
                'message' => '',
            ]);
    }

    public function test_can_detect_duplicate_teacher_nip_and_email(): void
    {
        Teacher::create([
            'name' => 'Guru Hebat',
            'email' => 'guru@gmail.com',
            'identity_number' => '198501012010011001',
            'password' => bcrypt('password123'),
        ]);

        $resNip = $this->getJson(route('api.check-unique', [
            'type' => 'teacher_nip',
            'value' => '198501012010011001',
        ]));
        $resNip->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);

        $resEmail = $this->getJson(route('api.check-unique', [
            'type' => 'teacher_email',
            'value' => 'guru@gmail.com',
        ]));
        $resEmail->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);
    }

    public function test_can_detect_duplicate_admin_nip_and_email(): void
    {
        Admin::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@gmail.com',
            'identity_number' => '199001012015011002',
            'password' => bcrypt('password123'),
        ]);

        $resNip = $this->getJson(route('api.check-unique', [
            'type' => 'admin_nip',
            'value' => '199001012015011002',
        ]));
        $resNip->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);

        $resEmail = $this->getJson(route('api.check-unique', [
            'type' => 'admin_email',
            'value' => 'admin@gmail.com',
        ]));
        $resEmail->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);
    }

    public function test_can_detect_duplicate_subject_and_major(): void
    {
        Subject::create([
            'name' => 'Pemrograman Web Testing',
            'code' => 'PW-TST-01',
            'color' => 'blue',
        ]);

        $resSubCode = $this->getJson(route('api.check-unique', [
            'type' => 'subject_code',
            'value' => 'pw-tst-01',
        ]));
        $resSubCode->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);

        $resSubName = $this->getJson(route('api.check-unique', [
            'type' => 'subject_name',
            'value' => 'Pemrograman Web Testing',
        ]));
        $resSubName->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);

        Major::create([
            'name' => 'Rekayasa Perangkat Lunak Testing',
            'code' => 'RPLTST',
        ]);

        $resMajCode = $this->getJson(route('api.check-unique', [
            'type' => 'major_code',
            'value' => 'rpltst',
        ]));
        $resMajCode->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);

        $resMajName = $this->getJson(route('api.check-unique', [
            'type' => 'major_name',
            'value' => 'Rekayasa Perangkat Lunak Testing',
        ]));
        $resMajName->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);
    }

    public function test_can_detect_duplicate_school_class(): void
    {
        $major = Major::create([
            'name' => 'Teknik Jaringan Testing',
            'code' => 'TKJTST',
        ]);

        SchoolClass::create([
            'grade' => 'X',
            'major_id' => $major->id,
            'major_name' => $major->code,
            'section' => '1',
            'code' => 'TKJX99',
        ]);

        $resClass = $this->getJson(route('api.check-unique', [
            'type' => 'class_exists',
            'grade' => 'X',
            'major_id' => $major->id,
            'section' => '1',
        ]));
        $resClass->assertOk()->assertJson(['exists' => true, 'message' => 'tidak bisa submit karena data ini sudah digunakan']);

        $resClassDiff = $this->getJson(route('api.check-unique', [
            'type' => 'class_exists',
            'grade' => 'X',
            'major_id' => $major->id,
            'section' => '2',
        ]));
        $resClassDiff->assertOk()->assertJson(['exists' => false, 'message' => '']);
    }
}
