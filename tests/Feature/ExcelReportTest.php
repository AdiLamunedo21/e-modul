<?php

namespace Tests\Feature;

use App\Exports\ModuleGradesExport;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Tests\TestCase;

class ExcelReportTest extends TestCase
{
    public function test_legacy_reports_url_redirects_to_grading_center()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.reports.index'));

        $response->assertRedirect(route('teacher.grading.index'));
    }

    public function test_legacy_class_reports_url_redirects_to_grading_class()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Teacher or Class data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.reports.class', $class->id));

        $response->assertRedirect(route('teacher.grading.class', $class->id));
    }

    public function test_teacher_can_export_module_grades_from_grading_center()
    {
        $teacher = Teacher::first();
        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.grading.export', $module));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertTrue($response->headers->has('content-disposition'));
        $this->assertStringContainsString('.xlsx', $response->headers->get('content-disposition'));
    }

    public function test_legacy_export_route_alias_still_works()
    {
        $teacher = Teacher::first();
        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.reports.export.module', $module));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertTrue($response->headers->has('content-disposition'));
        $this->assertStringContainsString('.xlsx', $response->headers->get('content-disposition'));
    }

    public function test_teacher_cannot_export_other_teachers_module()
    {
        $teacher1 = Teacher::first();
        $teacher2 = Teacher::skip(1)->first();

        if (!$teacher1 || !$teacher2) {
            $this->markTestSkipped('At least 2 teachers needed for test.');
        }

        $moduleOfTeacher1 = Module::where('teacher_id', $teacher1->id)->first();
        if (!$moduleOfTeacher1) {
            $this->markTestSkipped('Module of teacher 1 not found.');
        }

        // Teacher 2 tries to export module owned by Teacher 1
        $response = $this->actingAs($teacher2, 'teacher')
            ->get(route('teacher.grading.export', $moduleOfTeacher1));

        $response->assertStatus(403);
    }

    public function test_module_grades_export_class_generates_valid_spreadsheet_structure()
    {
        $teacher = Teacher::first();
        $class = SchoolClass::first();
        if (!$teacher || !$class) {
            $this->markTestSkipped('Seed data required.');
        }

        $module = Module::create([
            'teacher_id'    => $teacher->id,
            'class_id'      => $class->id,
            'title'         => 'Modul Uji Coba Excel Dinamis',
            'status'        => 'published',
            'has_pre_test'  => true,
            'has_video'     => true,
            'has_embed'     => false,
            'has_job_sheet' => true,
            'has_lkpd'      => false,
            'has_post_test' => true,
        ]);

        $export = new ModuleGradesExport($module);
        $spreadsheet = $export->generateSpreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Verifikasi Judul Institusional
        $this->assertEquals('LAPORAN REKAPITULASI HASIL BELAJAR SISWA', $sheet->getCell('A1')->getValue());
        $this->assertStringContainsString('SMK NEGERI 3 YOGYAKARTA', $sheet->getCell('A2')->getValue());

        // Verifikasi Header Tabel Baris 8
        $this->assertEquals('NO', $sheet->getCell('A8')->getValue());
        $this->assertEquals('NISN', $sheet->getCell('B8')->getValue());
        $this->assertEquals('NAMA LENGKAP SISWA', $sheet->getCell('C8')->getValue());

        // Cleanup
        $module->delete();
    }
}
