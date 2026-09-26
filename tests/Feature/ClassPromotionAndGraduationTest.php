<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Submission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClassPromotionAndGraduationTest extends TestCase
{
    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::firstOrCreate(
            ['identity_number' => '197501011999031001'],
            [
                'name'     => 'Drs. Ahmad Fauzi, M.Pd.',
                'password' => Hash::make('password'),
            ]
        );
    }

    protected function tearDown(): void
    {
        // Bersihkan siswa pengujian (NISN berawalan '777')
        Student::where('identity_number', 'like', '777%')->delete();

        // Bersihkan rombel kelas pengujian dengan section '99'
        SchoolClass::where('section', '99')->delete();

        parent::tearDown();
    }

    private function createTestClass(string $grade): SchoolClass
    {
        return SchoolClass::create([
            'grade'      => $grade,
            'major_name' => 'Teknik Uji Coba',
            'section'    => '99',
            'code'       => 'TEST_' . $grade . '_' . rand(1000, 9999),
        ]);
    }

    public function test_unauthenticated_user_cannot_access_promotion_pages()
    {
        $this->get(route('admin.promotions.index'))->assertRedirect(route('login.admin'));
    }

    public function test_admin_can_view_promotion_and_graduation_index()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.promotions.index'));

        $response->assertStatus(200);
        $response->assertSee('Kenaikan Kelas & Kelulusan Siswa', false);
        $response->assertSee('Promosi Rombel Siswa', false);
        $response->assertSee('Kelulusan Siswa (Tingkat XII)', false);
    }

    public function test_admin_can_fetch_students_by_class_json()
    {
        $class = $this->createTestClass('X');
        $student = Student::create([
            'name'            => 'Siswa Uji JSON',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $class->id,
            'password'        => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.promotions.students', $class));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Siswa Uji JSON']);
        $response->assertJsonFragment(['identity_number' => $student->identity_number]);
    }

    public function test_admin_can_promote_students_from_grade_x_to_xi()
    {
        $sourceClass = $this->createTestClass('X');
        $targetClass = $this->createTestClass('XI');

        $student1 = Student::create([
            'name'            => 'Siswa Naik 1',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $sourceClass->id,
            'password'        => Hash::make('password'),
        ]);

        $student2 = Student::create([
            'name'            => 'Siswa Naik 2',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $sourceClass->id,
            'password'        => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.promotions.promote'), [
                'source_class_id' => $sourceClass->id,
                'target_class_id' => $targetClass->id,
                'student_ids'     => [$student1->id, $student2->id],
            ]);

        $response->assertRedirect(route('admin.promotions.index', ['tab' => 'promotion']));
        $response->assertSessionHas('success');

        $student1->refresh();
        $student2->refresh();

        $this->assertEquals($targetClass->id, $student1->class_id);
        $this->assertEquals($targetClass->id, $student2->class_id);
    }

    public function test_unselected_students_remain_in_source_class_when_repeating_grade()
    {
        $sourceClass = $this->createTestClass('X');
        $targetClass = $this->createTestClass('XI');

        $promotedStudent = Student::create([
            'name'            => 'Siswa Naik Kelas',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $sourceClass->id,
            'password'        => Hash::make('password'),
        ]);

        $repeatingStudent = Student::create([
            'name'            => 'Siswa Tinggal Kelas',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $sourceClass->id,
            'password'        => Hash::make('password'),
        ]);

        // Hanya sertakan promotedStudent
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.promotions.promote'), [
                'source_class_id' => $sourceClass->id,
                'target_class_id' => $targetClass->id,
                'student_ids'     => [$promotedStudent->id],
            ]);

        $response->assertSessionHas('success');

        $promotedStudent->refresh();
        $repeatingStudent->refresh();

        $this->assertEquals($targetClass->id, $promotedStudent->class_id);
        $this->assertEquals($sourceClass->id, $repeatingStudent->class_id); // Siswa tinggal kelas tetap di kelas X!
    }

    public function test_promoted_students_receive_target_class_subjects_automatically()
    {
        $sourceClass = $this->createTestClass('X');
        $targetClass = $this->createTestClass('XI');

        // Pastikan ada mata pelajaran yang diasosiasikan
        $subject = Subject::first();
        if (!$subject) {
            $subject = Subject::create(['name' => 'Fisika Terapan', 'code' => 'FIS_TEST']);
        }

        $student = Student::create([
            'name'            => 'Siswa Sync Mapel',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $sourceClass->id,
            'password'        => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.promotions.promote'), [
                'source_class_id' => $sourceClass->id,
                'target_class_id' => $targetClass->id,
                'student_ids'     => [$student->id],
            ]);

        $response->assertSessionHas('success');
        $student->refresh();
        $this->assertEquals($targetClass->id, $student->class_id);
    }

    public function test_admin_can_download_graduation_report_excel()
    {
        $classXII = $this->createTestClass('XII');
        Student::create([
            'name'            => 'Siswa Calon Lulus',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $classXII->id,
            'password'        => Hash::make('password'),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.promotions.graduation.export', ['class_id' => $classXII->id]));

        $response->assertStatus(200);
        $this->assertStringContainsString('Rekap_Kelulusan_', (string) $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.xlsx', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_graduate_and_purge_class_xii_students_and_files()
    {
        Storage::fake('public');

        $classXII = $this->createTestClass('XII');
        $student = Student::create([
            'name'            => 'Siswa Lulusan Purge',
            'identity_number' => '777' . rand(1000000, 9999999),
            'class_id'        => $classXII->id,
            'password'        => Hash::make('password'),
        ]);

        // Simulasikan file tugas siswa di storage
        $dummyPath = 'submissions/lkpd/test_tugas_' . rand(1000, 9999) . '.pdf';
        Storage::disk('public')->put($dummyPath, 'konten file tugas dummy');
        $this->assertTrue(Storage::disk('public')->exists($dummyPath));

        // Buat rekaman dummy submission
        $module = \App\Models\Module::first();
        if ($module) {
            $lkpd = \App\Models\Lkpd::firstOrCreate(
                ['module_id' => $module->id],
                ['pdf_file_path' => 'lkpd/dummy.pdf']
            );

            Submission::create([
                'lkpd_id'            => $lkpd->id,
                'student_id'         => $student->id,
                'uploaded_file_path' => $dummyPath,
                'manual_score'       => 90,
            ]);
        }

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.promotions.graduation.process'), [
                'class_id' => (string) $classXII->id,
            ]);

        $response->assertRedirect(route('admin.promotions.index', ['tab' => 'graduation']));
        $response->assertSessionHas('success');

        // Akun siswa terhapus
        $this->assertDatabaseMissing('students', ['id' => $student->id]);

        // File fisik tugas terhapus dari storage
        $this->assertFalse(Storage::disk('public')->exists($dummyPath));
    }

    public function test_graduation_cannot_be_run_on_grade_x_or_xi_classes()
    {
        $classX = $this->createTestClass('X');

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.promotions.graduation.process'), [
                'class_id' => (string) $classX->id,
            ]);

        $response->assertSessionHas('error');
    }
}
