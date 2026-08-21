<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Student;
use App\Models\Teacher;
use Tests\TestCase;

class GradingCenterTest extends TestCase
{
    public function test_teacher_can_access_grading_center_index()
    {
        $teacher = Teacher::first();
        if (!$teacher) {
            $this->markTestSkipped('Teacher data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.grading.index'));

        $response->assertStatus(200);
        $response->assertSee('Grading Center');
    }

    public function test_teacher_can_access_module_grading_show()
    {
        $teacher = Teacher::first();
        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.grading.show', $module));

        $response->assertStatus(200);
        $response->assertSee($module->title);
    }

    public function test_teacher_can_update_student_grade()
    {
        $teacher = Teacher::first();
        $module = Module::where('teacher_id', $teacher->id)->first();
        $student = Student::where('class_id', $module->class_id)->first();
        if (!$student) {
            $this->markTestSkipped('Student data not seeded.');
        }

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.grading.student.update', [$module, $student]), [
                'pre_test_score' => 90,
                'video_score' => 95,
                'embed_score' => 88,
                'job_sheet_score' => 92,
                'lkpd_score' => 90,
                'post_test_score' => 100,
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('student_results', [
            'student_id' => $student->id,
            'module_id' => $module->id,
            'video_score' => 95,
            'grading_status' => 'graded',
        ]);
    }

    public function test_adaptive_matrix_table_renders_only_active_module_components()
    {
        $teacher = Teacher::first();
        $module = Module::where('teacher_id', $teacher->id)->first();
        if (!$module) {
            $this->markTestSkipped('Module data not seeded.');
        }

        // 1. Skenario: Hanya LKPD dan Post-test yang aktif
        $module->update([
            'has_pre_test'  => false,
            'has_video'     => false,
            'has_embed'     => false,
            'has_job_sheet' => false,
            'has_lkpd'      => true,
            'has_post_test' => true,
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.grading.show', $module));

        $response->assertStatus(200);
        $activeComps = $response->viewData('activeComponents');
        $this->assertArrayHasKey('lkpd', $activeComps);
        $this->assertArrayHasKey('post_test', $activeComps);
        $this->assertArrayNotHasKey('pre_test', $activeComps);
        $this->assertArrayNotHasKey('video', $activeComps);
        $this->assertArrayNotHasKey('embed', $activeComps);
        $this->assertArrayNotHasKey('job_sheet', $activeComps);
        $this->assertCount(2, $activeComps);

        // 2. Skenario: Guru mengaktifkan Job Sheet
        $module->update([
            'has_job_sheet' => true,
        ]);

        $responseWithJobSheet = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.grading.show', $module));

        $responseWithJobSheet->assertStatus(200);
        $activeCompsUpdated = $responseWithJobSheet->viewData('activeComponents');
        $this->assertArrayHasKey('job_sheet', $activeCompsUpdated);
        $this->assertArrayHasKey('lkpd', $activeCompsUpdated);
        $this->assertArrayHasKey('post_test', $activeCompsUpdated);
        $this->assertCount(3, $activeCompsUpdated);

        // Kembalikan ke state awal
        $module->update([
            'has_pre_test'  => true,
            'has_video'     => true,
            'has_embed'     => true,
            'has_job_sheet' => true,
            'has_lkpd'      => true,
            'has_post_test' => true,
        ]);
    }
}
