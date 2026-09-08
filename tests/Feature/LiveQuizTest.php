<?php

namespace Tests\Feature;

use App\Models\LiveQuizAnswer;
use App\Models\LiveQuizParticipant;
use App\Models\LiveQuizSession;
use App\Models\Module;
use App\Models\PostTest;
use App\Models\PostTestQuestion;
use App\Models\PreTestQuestion;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Teacher;
use Tests\TestCase;

class LiveQuizTest extends TestCase
{
    private function getOrCreateModuleWithQuestions(Teacher $teacher, ?SchoolClass $class = null): Module
    {
        $subject = Subject::first() ?? Subject::create([
            'name' => 'Pemrograman Web',
            'code' => 'PW-01',
        ]);

        if (!$class) {
            $class = SchoolClass::first() ?? SchoolClass::create([
                'name'        => 'XII RPL 1',
                'grade_level' => 'XII',
                'major_id'    => 1,
                'code'        => 'RPL123',
            ]);
        }

        $module = Module::create([
            'teacher_id'   => $teacher->id,
            'subject_id'   => $subject->id,
            'class_id'     => $class->id,
            'title'        => 'Modul Pengenalan Laravel Live Quiz',
            'semester'     => '1',
            'status'       => 'published',
            'is_active'    => true,
            'has_pre_test' => true,
        ]);

        $preTest = \App\Models\PreTest::create([
            'module_id'           => $module->id,
            'title'               => 'Pre-Test Pengenalan Laravel',
            'kktp'                => 75,
            'instructions'        => 'Jawab soal dengan teliti',
            'randomize_questions' => false,
        ]);

        // Create pre-test questions
        PreTestQuestion::create([
            'pre_test_id'        => $preTest->id,
            'question_text'      => 'Apa fungsi dari Route::get pada Laravel?',
            'options'            => [
                'A' => 'Mendefinisikan endpoint HTTP GET',
                'B' => 'Membuat database migration',
                'C' => 'Menghapus table user',
                'D' => 'Menjalankan server web',
            ],
            'correct_answer'     => 'A',
            'score_weight'       => 10,
            'order_num'          => 1,
        ]);

        PreTestQuestion::create([
            'pre_test_id'        => $preTest->id,
            'question_text'      => 'Perintah artisan apa untuk menjalankan migrasi?',
            'options'            => [
                'A' => 'php artisan serve',
                'B' => 'php artisan migrate',
                'C' => 'php artisan make:model',
                'D' => 'php artisan config:clear',
            ],
            'correct_answer'     => 'B',
            'score_weight'       => 10,
            'order_num'          => 2,
        ]);

        return $module;
    }

    public function test_teacher_can_access_live_quiz_index()
    {
        $teacher = Teacher::first() ?? Teacher::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.live-quiz.index'));

        $response->assertStatus(200);
        $response->assertSee('Kuis Live (Mode Pantau)');
        $response->assertSee('Mulai Kuis Live Baru');
    }

    public function test_teacher_can_access_create_form()
    {
        $teacher = Teacher::first() ?? Teacher::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.live-quiz.create'));

        $response->assertStatus(200);
        $response->assertSee('Mulai Sesi Kuis Live');
        $response->assertSee('Pilih E-Modul Sumber Soal');
        $response->assertSee('liveQuizCreateForm()');
    }

    public function test_create_form_filters_active_modules_and_provides_toggle()
    {
        $teacher = Teacher::first() ?? Teacher::factory()->create();
        $class = SchoolClass::first();

        // 1. Modul aktif
        $activeModule = $this->getOrCreateModuleWithQuestions($teacher, $class);
        $activeModule->update(['is_active' => true, 'status' => 'published']);

        // 2. Modul tidak aktif (arsip)
        $inactiveModule = Module::create([
            'teacher_id'   => $teacher->id,
            'subject_id'   => $activeModule->subject_id,
            'class_id'     => $class->id,
            'title'        => 'Modul Arsip Lama Nonaktif ' . uniqid(),
            'semester'     => '1',
            'status'       => 'published',
            'is_active'    => false,
            'has_pre_test' => true,
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.live-quiz.create'));

        $response->assertStatus(200);
        $response->assertSee('Menampilkan');
        $response->assertSee('yang sedang aktif diajarkan di kelas');
        $response->assertSee($activeModule->title);
        $response->assertDontSee($inactiveModule->title);
        $response->assertDontSee('Ganti Modul Lain');

        // Cleanup
        $activeModule->delete();
        $inactiveModule->delete();
    }

    public function test_teacher_can_create_session_and_student_can_join_and_play()
    {
        $teacher = Teacher::first() ?? Teacher::factory()->create();
        $class = SchoolClass::first() ?? SchoolClass::create([
            'name'        => 'XII RPL 1',
            'grade_level' => 'XII',
            'major_id'    => 1,
            'code'        => 'RPL123',
        ]);

        // Hubungkan guru dengan kelas
        $teacher->classes()->syncWithoutDetaching([$class->id]);

        $module = $this->getOrCreateModuleWithQuestions($teacher, $class);

        // 1. Guru membuat sesi kuis live
        $storeResponse = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.live-quiz.store'), [
                'module_id'          => $module->id,
                'test_type'          => 'pre_test',
                'class_id'           => $class->id,
                'time_limit_seconds' => 30,
            ]);

        $session = LiveQuizSession::latest()->first();
        $this->assertNotNull($session);
        $this->assertEquals('lobby', $session->status);
        $this->assertNotEmpty($session->pin);

        $storeResponse->assertRedirect(route('teacher.live-quiz.host', $session));

        // 2. Guru membuka layar host
        $hostResponse = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.live-quiz.host', $session));
        $hostResponse->assertStatus(200);
        $hostResponse->assertSee($session->pin);

        // 3. Siswa melihat layar join
        $student = Student::first() ?? Student::factory()->create(['class_id' => $class->id]);
        $student->classes()->syncWithoutDetaching([$class->id]);

        $joinPageResponse = $this->actingAs($student, 'student')
            ->get(route('student.live-quiz.join'));
        $joinPageResponse->assertStatus(200);
        $joinPageResponse->assertSee('Game PIN');

        // 4. Siswa memasukkan PIN untuk gabung kuis
        $submitJoinResponse = $this->actingAs($student, 'student')
            ->post(route('student.live-quiz.submit-join'), [
                'pin' => $session->pin,
            ]);
        $submitJoinResponse->assertRedirect(route('student.live-quiz.play', $session));

        $this->assertDatabaseHas('live_quiz_participants', [
            'live_quiz_session_id' => $session->id,
            'student_id'           => $student->id,
        ]);

        // 5. Guru memulai kuis (slide 1)
        $startResponse = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.live-quiz.start', $session));
        $startResponse->assertStatus(200);
        $startResponse->assertJson(['success' => true, 'status' => 'question']);

        $session->refresh();
        $this->assertEquals('question', $session->status);
        $this->assertEquals(0, $session->current_question_index);

        // 5b. Periksa polling siswa: pertanyaan, opsi jawaban, dan timer harus diterima di layar siswa
        $playerPoll = $this->actingAs($student, 'student')
            ->getJson(route('student.live-quiz.poll', $session));
        $playerPoll->assertStatus(200);
        $this->assertNotNull($playerPoll->json('question'));
        $this->assertEquals('Apa fungsi dari Route::get pada Laravel?', $playerPoll->json('question.question_text'));
        $this->assertEquals('Mendefinisikan endpoint HTTP GET', $playerPoll->json('question.options.A'));
        $this->assertEquals(30, $playerPoll->json('time_limit'));
        $this->assertEquals($session->question_started_at->timestamp, $playerPoll->json('started_at'));
        $this->assertGreaterThan(0, $playerPoll->json('remaining_seconds'));
        $this->assertLessThanOrEqual(30, $playerPoll->json('remaining_seconds'));

        // 6. Siswa menjawab soal (pilih opsi A - Benar)
        $answerResponse = $this->actingAs($student, 'student')
            ->postJson(route('student.live-quiz.answer', $session), [
                'chosen_option'    => 'A',
                'response_time_ms' => 2500,
            ]);
        $answerResponse->assertStatus(200);
        $answerResponse->assertJson(['success' => true, 'is_correct' => true]);
        $this->assertGreaterThan(500, $answerResponse->json('points'));

        // 7. Guru membuka kunci jawaban (reveal)
        $revealResponse = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.live-quiz.reveal', $session));
        $revealResponse->assertStatus(200);
        $revealResponse->assertJson(['success' => true, 'status' => 'reveal']);
        $this->assertArrayHasKey('E', $revealResponse->json('distribution.counts'));

        $hostPollResponse = $this->actingAs($teacher, 'teacher')
            ->getJson(route('teacher.live-quiz.host-poll', $session));
        $this->assertArrayHasKey('E', $hostPollResponse->json('answer_distribution'));

        // 8. Guru membuka leaderboard
        $lbResponse = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.live-quiz.leaderboard', $session));
        $lbResponse->assertStatus(200);
        $lbResponse->assertJson(['success' => true, 'status' => 'leaderboard']);

        // 9. Guru menyelesaikan kuis (finish)
        $finishResponse = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.live-quiz.finish', $session));
        $finishResponse->assertStatus(200);
        $finishResponse->assertJson(['success' => true, 'status' => 'finished']);

        $session->refresh();
        $this->assertEquals('finished', $session->status);

        // 10. Polling host memeriksa podium (hanya nama siswa dan skor, tidak ada maskot)
        $pollResponse = $this->actingAs($teacher, 'teacher')
            ->getJson(route('teacher.live-quiz.host-poll', $session));
        $pollResponse->assertStatus(200);
        $pollData = $pollResponse->json();
        $this->assertNotNull($pollData['podium']['first']);
        $this->assertEquals($student->name, $pollData['podium']['first']['name']);

        // 11. Guru menyimpan nilai ke Pusat Penilaian
        $saveResponse = $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.live-quiz.save-grades', $session));
        $saveResponse->assertStatus(200);
        $saveResponse->assertJson(['success' => true]);

        // Verifikasi tabel student_results
        $result = StudentResult::where('module_id', $module->id)
            ->where('student_id', $student->id)
            ->first();
        $this->assertNotNull($result);
        $this->assertNotNull($result->pre_test_score);

        // 12. Guru menghapus sesi kuis
        $deleteResponse = $this->actingAs($teacher, 'teacher')
            ->delete(route('teacher.live-quiz.destroy', $session));
        $deleteResponse->assertRedirect(route('teacher.live-quiz.index'));
        $this->assertDatabaseMissing('live_quiz_sessions', ['id' => $session->id]);
    }

    public function test_create_form_auto_selects_active_module_and_locks_pre_test_when_only_post_test_exists()
    {
        $teacher = Teacher::create([
            'name'            => 'Guru Post Only',
            'identity_number' => 'NIP_POST_' . uniqid(),
            'password'        => bcrypt('password'),
        ]);
        $class = SchoolClass::first() ?? SchoolClass::create([
            'name'        => 'XII RPL 2',
            'grade_level' => 'XII',
            'major_id'    => 1,
            'code'        => 'RPL-POSTONLY',
        ]);
        $subject = Subject::first() ?? Subject::create(['name' => 'Pemrograman Web', 'code' => 'PW-02']);

        // Buat modul aktif yang HANYA memiliki Post-Test (pre-test 0 soal)
        $postOnlyModule = Module::create([
            'teacher_id'    => $teacher->id,
            'subject_id'    => $subject->id,
            'class_id'      => $class->id,
            'title'         => 'Modul Khusus Post Test Saja ' . uniqid(),
            'semester'      => '1',
            'status'        => 'published',
            'is_active'     => true,
            'has_pre_test'  => false,
            'has_post_test' => true,
        ]);

        $postTest = PostTest::create([
            'module_id'           => $postOnlyModule->id,
            'title'               => 'Post-Test Khusus',
            'kktp'                => 75,
            'instructions'        => 'Jawab soal akhir',
            'randomize_questions' => false,
        ]);

        PostTestQuestion::create([
            'post_test_id'   => $postTest->id,
            'question_text'  => 'Soal post test 1?',
            'options'        => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'],
            'correct_answer' => 'A',
            'score_weight'   => 10,
            'order_num'      => 1,
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.live-quiz.create'));

        $response->assertStatus(200);
        $response->assertSee($postOnlyModule->title);
        // Pre-test harus terkunci karena 0 soal
        $response->assertSee('Terkunci (Tidak ada soal)');

        // Periksa data yang dikirim ke view
        $viewData = $response->original->getData();
        $this->assertEquals($postOnlyModule->id, $viewData['selectedModuleId']);
        $this->assertEquals('post_test', $viewData['initialTestType']);
        $this->assertFalse($viewData['modulesData'][(string) $postOnlyModule->id]['has_pre_test']);
        $this->assertTrue($viewData['modulesData'][(string) $postOnlyModule->id]['has_post_test']);

        // Cleanup
        $postOnlyModule->delete();
    }

    public function test_teacher_can_create_session_without_explicit_class_id()
    {
        $teacher = Teacher::first() ?? Teacher::factory()->create();
        $class = SchoolClass::first() ?? SchoolClass::create([
            'name'        => 'XII RPL 3',
            'grade_level' => 'XII',
            'major_id'    => 1,
            'code'        => 'RPL-INHERIT',
        ]);

        $module = $this->getOrCreateModuleWithQuestions($teacher, $class);

        // Submit form TANPA mengirim field class_id (karena field target kelas sudah dihapus dari UI)
        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.live-quiz.store'), [
                'module_id'          => $module->id,
                'test_type'          => 'pre_test',
                'time_limit_seconds' => 20,
            ]);

        $session = LiveQuizSession::where('module_id', $module->id)->latest()->first();
        $this->assertNotNull($session);
        // Memastikan session otomatis mewarisi class_id dari modul
        $this->assertEquals($module->class_id, $session->class_id);
        $response->assertRedirect(route('teacher.live-quiz.host', $session));

        $session->delete();
        $module->delete();
    }

    public function test_create_form_auto_selects_active_module_and_locks_post_test_when_only_pre_test_exists()
    {
        $teacher = Teacher::create([
            'name'            => 'Guru Pre Only',
            'identity_number' => 'NIP_PRE_' . uniqid(),
            'password'        => bcrypt('password'),
        ]);
        $class = SchoolClass::first() ?? SchoolClass::create([
            'name'        => 'XII RPL 4',
            'grade_level' => 'XII',
            'major_id'    => 1,
            'code'        => 'RPL-PREONLY',
        ]);

        $preOnlyModule = $this->getOrCreateModuleWithQuestions($teacher, $class);
        $preOnlyModule->update(['is_active' => true, 'has_post_test' => false]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.live-quiz.create'));

        $response->assertStatus(200);
        $response->assertSee($preOnlyModule->title);
        $response->assertSee('Terkunci (Tidak ada soal)');

        $viewData = $response->original->getData();
        $this->assertEquals($preOnlyModule->id, $viewData['selectedModuleId']);
        $this->assertEquals('pre_test', $viewData['initialTestType']);
        $this->assertTrue($viewData['modulesData'][(string) $preOnlyModule->id]['has_pre_test']);
        $this->assertFalse($viewData['modulesData'][(string) $preOnlyModule->id]['has_post_test']);

        $preOnlyModule->delete();
    }

    public function test_create_form_displays_empty_state_when_no_module_is_active()
    {
        $teacher = Teacher::create([
            'name'            => 'Guru Tanpa Modul Aktif',
            'identity_number' => 'NIP_NONE_' . uniqid(),
            'password'        => bcrypt('password'),
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.live-quiz.create'));

        $response->assertStatus(200);
        $response->assertSee('Belum Ada E-Modul yang Diaktifkan di Kelas');
        $response->assertSee('Buka Menu Kelas Didik');
    }

    public function test_active_live_quiz_appears_on_student_dashboard_when_started()
    {
        $teacher = Teacher::first() ?? Teacher::factory()->create();
        $class = SchoolClass::first() ?? SchoolClass::create([
            'name'        => 'XII RPL 1',
            'grade_level' => 'XII',
            'major_id'    => 1,
            'code'        => 'RPL123',
        ]);

        $teacher->classes()->syncWithoutDetaching([$class->id]);
        $module = $this->getOrCreateModuleWithQuestions($teacher, $class);

        $student = Student::first() ?? Student::factory()->create(['class_id' => $class->id]);
        $student->classes()->syncWithoutDetaching([$class->id]);

        // Bersihkan sesi lama kelas ini
        LiveQuizSession::where('class_id', $class->id)->delete();

        // Guru membuat sesi kuis
        $pin = LiveQuizSession::generatePin();
        $session = LiveQuizSession::create([
            'teacher_id'             => $teacher->id,
            'module_id'              => $module->id,
            'class_id'               => $class->id,
            'test_type'              => 'pre_test',
            'title'                  => "Kuis Live: {$module->title}",
            'pin_code'               => $pin,
            'status'                 => 'lobby',
            'current_question_index' => 0,
            'time_limit_seconds'     => 30,
            'total_questions'        => 2,
            'grades_saved'           => false,
        ]);

        // 1. Saat di lobby, kuis muncul di dashboard siswa
        $lobbyResponse = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));
        $lobbyResponse->assertStatus(200);
        $lobbyResponse->assertSee($session->pin);
        $lobbyResponse->assertSee('Gabung Kuis Sekarang');

        // 2. Guru memulai kuis (status berubah menjadi 'question')
        $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.live-quiz.start', $session));
        $session->refresh();
        $this->assertEquals('question', $session->status);

        // Kuis HARUS TETAP MUNCUL di dashboard siswa saat kuis sudah dimulai ('question')
        $startedResponse = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));
        $startedResponse->assertStatus(200);
        $startedResponse->assertSee($session->pin);
        $startedResponse->assertSee('Gabung Kuis Sekarang');

        // 3. Endpoint API active-check juga mengembalikan has_active_quiz: true
        $apiResponse = $this->actingAs($student, 'student')
            ->getJson(route('student.live-quiz.active-check'));
        $apiResponse->assertStatus(200);
        $apiResponse->assertJson([
            'has_active_quiz' => true,
            'quiz' => [
                'id'  => $session->id,
                'pin' => $session->pin,
            ],
        ]);

        // 4. Setelah kuis selesai (finish), kuis tidak lagi aktif
        $this->actingAs($teacher, 'teacher')
            ->postJson(route('teacher.live-quiz.finish', $session));
        $session->refresh();
        $this->assertEquals('finished', $session->status);

        $finishedApiResponse = $this->actingAs($student, 'student')
            ->getJson(route('student.live-quiz.active-check'));
        $finishedApiResponse->assertStatus(200);
        $finishedApiResponse->assertJson(['has_active_quiz' => false]);

        $session->delete();
    }
}

