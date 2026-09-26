<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AdminProfileAndImportTest extends TestCase
{
    private function getAdmin(): Admin
    {
        return Admin::firstOrCreate(
            ['identity_number' => '999000111222'],
            [
                'name'     => 'Admin Penguji',
                'password' => Hash::make('password'),
            ]
        );
    }

    protected function tearDown(): void
    {
        // Pastikan admin penguji dibersihkan atau dikembalikan jika ada perubahan
        Admin::where('identity_number', '999000111222')->delete();
        parent::tearDown();
    }

    public function test_unauthenticated_user_cannot_access_profile_page()
    {
        $this->get(route('admin.profile.edit'))->assertRedirect(route('login.admin'));
    }

    public function test_admin_can_view_profile_page()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Akun & Keamanan', false);
        $response->assertSee('Perbarui Informasi Profil', false);
        $response->assertSee('Ubah Kata Sandi', false);
        $response->assertSee($admin->name, false);
        $response->assertSee($admin->identity_number, false);
    }

    public function test_admin_can_update_profile_info()
    {
        $admin = $this->getAdmin();
        $newNip = '999' . rand(100000000, 999999999);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.profile.update'), [
                'name'            => 'Drs. Nama Admin Terupdate, M.Pd.',
                'identity_number' => $newNip,
            ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('profile_success');

        $this->assertDatabaseHas('admins', [
            'id'              => $admin->id,
            'name'            => 'Drs. Nama Admin Terupdate, M.Pd.',
            'identity_number' => $newNip,
        ]);

        // Bersihkan data tes
        $admin->delete();
    }

    public function test_admin_can_change_password_with_valid_current_password()
    {
        $admin = $this->getAdmin();
        $admin->update(['password' => Hash::make('rahasia123')]);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.profile.password'), [
                'current_password'      => 'rahasia123',
                'password'              => 'passwordBaru456',
                'password_confirmation' => 'passwordBaru456',
            ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('password_success');

        $admin->refresh();
        $this->assertTrue(Hash::check('passwordBaru456', $admin->password));

        // Kembalikan password default untuk tes lainnya
        $admin->update(['password' => Hash::make('password')]);
    }

    public function test_admin_cannot_change_password_with_invalid_current_password()
    {
        $admin = $this->getAdmin();
        $admin->update(['password' => Hash::make('rahasia123')]);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.profile.password'), [
                'current_password'      => 'passwordSalah',
                'password'              => 'passwordBaru456',
                'password_confirmation' => 'passwordBaru456',
            ]);

        $response->assertSessionHasErrors(['current_password']);

        $admin->refresh();
        $this->assertTrue(Hash::check('rahasia123', $admin->password));

        // Kembalikan password default untuk tes lainnya
        $admin->update(['password' => Hash::make('password')]);
    }

    public function test_admin_can_download_student_import_template()
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.students.import.template'));

        $response->assertStatus(200);
        $this->assertStringContainsString('Template_Import_Siswa_SMKN3.xlsx', (string) $response->headers->get('Content-Disposition'));
    }


    public function test_admin_can_import_students_from_excel_file_and_auto_sync_subjects()
    {
        $admin = $this->getAdmin();
        $schoolClass = SchoolClass::first();
        if (!$schoolClass) {
            $schoolClass = SchoolClass::create([
                'grade'      => 'X',
                'major_name' => 'Teknik Komputer',
                'section'    => '1',
            ]);
        }

        // Buat mock spreadsheet di memori
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Baris Header
        $sheet->setCellValue('A4', 'NISN');
        $sheet->setCellValue('B4', 'Nama Siswa');
        $sheet->setCellValue('C4', 'Kode Kelas');
        $sheet->setCellValue('D4', 'Password');

        // Data Siswa 1
        $nisn1 = 'NISN_IMP_' . rand(100000, 999999);
        $sheet->setCellValue('A5', $nisn1);
        $sheet->setCellValue('B5', 'Siswa Import Excel 1');
        $sheet->setCellValue('C5', $schoolClass->code);
        $sheet->setCellValue('D5', 'pass123');

        // Data Siswa 2
        $nisn2 = 'NISN_IMP_' . rand(100000, 999999);
        $sheet->setCellValue('A6', $nisn2);
        $sheet->setCellValue('B6', 'Siswa Import Excel 2');
        $sheet->setCellValue('C6', $schoolClass->code);
        $sheet->setCellValue('D6', ''); // password kosong -> default 'password'

        $tempPath = tempnam(sys_get_temp_dir(), 'test_import_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $file = new UploadedFile($tempPath, 'students_test.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.students.import'), [
                'file' => $file,
            ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        // Verifikasi siswa 1 berhasil dibuat dan otomatis terdaftar di kelas
        $student1 = Student::where('identity_number', $nisn1)->first();
        $this->assertNotNull($student1);
        $this->assertEquals('Siswa Import Excel 1', $student1->name);
        $this->assertEquals($schoolClass->id, $student1->class_id);
        $this->assertTrue(Hash::check('pass123', $student1->password));
        $this->assertTrue($student1->classes->contains($schoolClass->id));
        $this->assertTrue($student1->subjects()->exists(), 'Mapel kelas harus otomatis disinkronkan ke siswa hasil import.');

        // Verifikasi siswa 2 berhasil dibuat dengan password default
        $student2 = Student::where('identity_number', $nisn2)->first();
        $this->assertNotNull($student2);
        $this->assertEquals('Siswa Import Excel 2', $student2->name);
        $this->assertTrue(Hash::check('password', $student2->password));

        // Bersihkan data tes
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
        $student1->subjects()->detach();
        $student1->classes()->detach();
        $student1->delete();

        $student2->subjects()->detach();
        $student2->classes()->detach();
        $student2->delete();
    }

    public function test_import_skips_invalid_and_duplicate_rows_and_reports_errors()
    {
        $admin = $this->getAdmin();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A4', 'NISN');
        $sheet->setCellValue('B4', 'Nama Siswa');
        $sheet->setCellValue('C4', 'Kode Kelas');
        $sheet->setCellValue('D4', 'Password');

        // Baris tanpa NISN
        $sheet->setCellValue('A5', '');
        $sheet->setCellValue('B5', 'Siswa Tanpa NISN');
        $sheet->setCellValue('C5', 'KODE_INVALID');

        // Baris dengan Kode Kelas tidak dikenal
        $sheet->setCellValue('A6', 'NISN_FAKE_9999');
        $sheet->setCellValue('B6', 'Siswa Kelas Gaib');
        $sheet->setCellValue('C6', 'XYZ_NOT_EXIST');

        $tempPath = tempnam(sys_get_temp_dir(), 'test_import_err_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $file = new UploadedFile($tempPath, 'students_error_test.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.students.import'), [
                'file' => $file,
            ]);

        $response->assertSessionHas('import_errors');
        $errors = session('import_errors');
        $this->assertCount(2, $errors);

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }
}
