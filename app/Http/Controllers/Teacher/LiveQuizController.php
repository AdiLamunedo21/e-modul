<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LiveQuizAnswer;
use App\Models\LiveQuizParticipant;
use App\Models\LiveQuizSession;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\StudentResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LiveQuizController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    private function authorizeSession(LiveQuizSession $session): void
    {
        if ($session->teacher_id !== $this->teacher()->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola sesi kuis ini.');
        }
    }

    /**
     * Tampilan Utama: Direktori Kuis Live Guru.
     */
    public function index()
    {
        $teacher = $this->teacher();

        $sessions = LiveQuizSession::where('teacher_id', $teacher->id)
            ->with(['module.subject', 'schoolClass', 'participants'])
            ->latest()
            ->paginate(15);

        $activeSessionsCount = LiveQuizSession::where('teacher_id', $teacher->id)
            ->whereIn('status', ['lobby', 'question', 'reveal', 'leaderboard'])
            ->count();

        $completedSessionsCount = LiveQuizSession::where('teacher_id', $teacher->id)
            ->where('status', 'finished')
            ->count();

        return view('pages.teacher.live-quiz.index', compact('sessions', 'activeSessionsCount', 'completedSessionsCount'));
    }

    /**
     * Tampilan Formulir Pembuatan Sesi Kuis Live Baru.
     */
    public function create(Request $request)
    {
        $teacher = $this->teacher();

        $requestedModuleId = $request->query('module_id');
        $activeModule = null;

        if ($requestedModuleId) {
            $activeModule = Module::where('teacher_id', $teacher->id)
                ->where('id', $requestedModuleId)
                ->with(['subject', 'schoolClass', 'preTest.questions', 'postTest.questions'])
                ->first();
        }

        if (!$activeModule) {
            // Dapatkan modul yang sedang AKTIF di kelas milik guru ini
            $activeModule = Module::where('teacher_id', $teacher->id)
                ->where('is_active', true)
                ->with(['subject', 'schoolClass', 'preTest.questions', 'postTest.questions'])
                ->latest('updated_at')
                ->latest('id')
                ->first();
        }

        if (!$activeModule) {
            // Fallback ke modul apapun milik guru jika belum ada modul yang di-set is_active = true
            $activeModule = Module::where('teacher_id', $teacher->id)
                ->with(['subject', 'schoolClass', 'preTest.questions', 'postTest.questions'])
                ->latest('updated_at')
                ->latest('id')
                ->first();
        }

        $selectedModule = $activeModule;
        $selectedModuleId = $activeModule?->id;
        $moduleData = null;
        $initialTestType = '';

        if ($activeModule) {
            $preCount = $activeModule->preTest ? $activeModule->preTest->questions->count() : $activeModule->preTestQuestionCount();
            $postCount = $activeModule->postTestQuestionCount();
            $hasPre = (bool) $activeModule->has_pre_test && ($preCount > 0);
            $hasPost = (bool) $activeModule->has_post_test && ($postCount > 0);

            $moduleData = [
                'id'              => $activeModule->id,
                'title'           => $activeModule->title,
                'subject_name'    => $activeModule->subject->name ?? 'Mata Pelajaran',
                'class_name'      => $activeModule->schoolClass->name ?? ($activeModule->schoolClass->full_name ?? 'Semua Kelas'),
                'class_id'        => $activeModule->class_id,
                'is_active'       => true,
                'has_pre_test'    => $hasPre,
                'has_post_test'   => $hasPost,
                'pre_test_count'  => $preCount,
                'post_test_count' => $postCount,
            ];

            // Tentukan initial test_type berdasarkan ketersediaan soal di modul yang aktif
            $requestedType = $request->query('test_type') ?? old('test_type');
            if ($requestedType === 'pre_test' && $hasPre) {
                $initialTestType = 'pre_test';
            } elseif ($requestedType === 'post_test' && $hasPost) {
                $initialTestType = 'post_test';
            } elseif ($hasPost && !$hasPre) {
                $initialTestType = 'post_test';
            } elseif ($hasPre && !$hasPost) {
                $initialTestType = 'pre_test';
            } elseif ($hasPre && $hasPost) {
                $initialTestType = $requestedType ?: 'pre_test';
            }
        }

        $allModules = $activeModule ? collect([$activeModule]) : collect();
        $modules = $allModules;
        $modulesData = $moduleData ? [(string) $activeModule->id => $moduleData] : [];

        return view('pages.teacher.live-quiz.create', compact(
            'teacher',
            'activeModule',
            'selectedModule',
            'selectedModuleId',
            'moduleData',
            'modulesData',
            'initialTestType',
            'allModules',
            'modules'
        ));
    }

    /**
     * Membuat Sesi Kuis Live Baru.
     */
    public function store(Request $request)
    {
        $teacher = $this->teacher();

        $validated = $request->validate([
            'module_id'          => 'required|exists:modules,id',
            'test_type'          => 'required|in:pre_test,post_test',
            'class_id'           => 'nullable|exists:classes,id',
            'time_limit_seconds' => 'nullable|integer|min:5|max:180',
            'default_time_limit' => 'nullable|integer|min:5|max:180',
        ]);

        $module = Module::where('id', $validated['module_id'])
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $timeLimit = (int) ($request->input('time_limit_seconds') ?: $request->input('default_time_limit', 30));

        // Verifikasi ketersediaan soal
        $questions = collect();
        if ($validated['test_type'] === 'pre_test') {
            $test = $module->preTest;
            $questions = $test ? $test->questions()->orderBy('order_num')->get() : collect();
        } else {
            $questions = $module->getEffectivePostTestQuestions();
        }

        if ($questions->isEmpty()) {
            return back()->withInput()->withErrors(['module_id' => 'Instrumen soal terpilih belum memiliki butir pertanyaan. Tambahkan butir soal terlebih dahulu di builder modul.']);
        }

        $pin = LiveQuizSession::generatePin();
        $testLabel = $validated['test_type'] === 'pre_test' ? 'Pre-Test' : 'Post-Test';
        $class = !empty($validated['class_id']) ? SchoolClass::find($validated['class_id']) : $module->schoolClass;
        $classSuffix = $class ? " - {$class->full_name}" : '';

        $session = LiveQuizSession::create([
            'teacher_id'             => $teacher->id,
            'module_id'              => $module->id,
            'class_id'               => $class?->id,
            'test_type'              => $validated['test_type'],
            'title'                  => "Kuis Live: {$module->title} ({$testLabel}){$classSuffix}",
            'pin_code'               => $pin,
            'status'                 => 'lobby',
            'current_question_index' => 0,
            'current_question_id'    => $questions->first()?->id,
            'time_limit_seconds'     => $timeLimit,
            'total_questions'        => $questions->count(),
            'grades_saved'           => false,
        ]);

        return redirect()->route('teacher.live-quiz.host', $session);
    }

    /**
     * Layar Proyektor Utama Host Guru (Fullscreen View).
     */
    public function host(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $session->loadMissing(['module.subject', 'schoolClass', 'participants.student.schoolClass']);
        $questions = $session->getQuestionsList();
        $currentQuestion = $questions->get($session->current_question_index);

        return view('pages.teacher.live-quiz.host', compact('session', 'questions', 'currentQuestion'));
    }

    /**
     * Memulai Kuis dari Lobby ke Soal Pertama.
     */
    public function start(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $questions = $session->getQuestionsList();
        if ($questions->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada soal tersedia.'], 422);
        }

        $firstQuestion = $questions->first();

        $session->update([
            'status'                 => 'question',
            'current_question_index' => 0,
            'current_question_id'    => $firstQuestion->id,
            'question_started_at'    => now(),
        ]);

        return response()->json([
            'success'          => true,
            'status'           => 'question',
            'question_index'   => 0,
            'current_question' => [
                'id'            => $firstQuestion->id,
                'question_text' => $firstQuestion->question_text,
                'options'       => $firstQuestion->options,
            ],
        ]);
    }

    /**
     * Menampilkan Kunci Jawaban & Distribusi Pilihan Siswa.
     */
    public function reveal(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $session->update([
            'status' => 'reveal',
        ]);

        $distribution = $session->getAnswerDistribution();

        return response()->json([
            'success'      => true,
            'status'       => 'reveal',
            'distribution' => $distribution,
        ]);
    }

    /**
     * Menampilkan Papan Peringkat Live (Leaderboard Top 5).
     */
    public function leaderboard(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $session->update([
            'status' => 'leaderboard',
        ]);

        $topLeaderboard = $session->getTopLeaderboard(5)->map(fn($p) => [
            'nickname'          => $p->nickname,
            'class_name'        => $p->student?->schoolClass?->full_name ?? '-',
            'total_score'       => $p->total_score,
            'last_score_earned' => $p->last_score_earned,
            'correct_count'     => $p->correct_answers_count,
        ]);

        return response()->json([
            'success'     => true,
            'status'      => 'leaderboard',
            'leaderboard' => $topLeaderboard,
        ]);
    }

    /**
     * Menggeser Slide ke Soal Berikutnya.
     */
    public function nextQuestion(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $nextIndex = $session->current_question_index + 1;
        $questions = $session->getQuestionsList();

        // Jika soal telah habis, arahkan ke podium akhir
        if ($nextIndex >= $questions->count()) {
            $session->update([
                'status' => 'finished',
            ]);

            return response()->json([
                'success' => true,
                'status'  => 'finished',
            ]);
        }

        $nextQuestion = $questions->get($nextIndex);

        $session->update([
            'status'                 => 'question',
            'current_question_index' => $nextIndex,
            'current_question_id'    => $nextQuestion->id,
            'question_started_at'    => now(),
        ]);

        return response()->json([
            'success'          => true,
            'status'           => 'question',
            'question_index'   => $nextIndex,
            'current_question' => [
                'id'            => $nextQuestion->id,
                'question_text' => $nextQuestion->question_text,
                'options'       => $nextQuestion->options,
            ],
        ]);
    }

    /**
     * Mengakhiri Kuis secara Manual dan Menampilkan Podium.
     */
    public function finish(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $session->update([
            'status' => 'finished',
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'finished',
        ]);
    }

    /**
     * Menyimpan Nilai Hasil Kuis Live ke Matriks Pusat Penilaian (student_results).
     */
    public function saveGrades(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $participants = $session->participants()->with('student')->get();
        $totalQuestions = max(1, $session->total_questions);
        $module = $session->module;
        $testType = $session->test_type; // 'pre_test' | 'post_test'

        DB::transaction(function () use ($participants, $totalQuestions, $module, $testType, $session) {
            foreach ($participants as $part) {
                if (!$part->student_id) continue;

                // Konversi skor ke skala 0 - 100
                $calculatedScore = (int) round(($part->correct_answers_count / $totalQuestions) * 100);

                $result = StudentResult::firstOrNew([
                    'module_id'  => $module->id,
                    'student_id' => $part->student_id,
                ]);

                if ($testType === 'pre_test') {
                    $result->pre_test_score = $calculatedScore;
                } else {
                    $result->post_test_score = $calculatedScore;
                }

                $result->summative_score = $result->calculateSummativeScore($module);
                $result->grading_status = 'graded';
                $result->save();
            }

            $session->update(['grades_saved' => true]);
        });

        return response()->json([
            'success' => true,
            'count'   => $participants->count(),
            'message' => "Nilai berhasil disimpan ke Pusat Penilaian untuk {$participants->count()} siswa!",
        ]);
    }

    /**
     * Endpoint Polling Ringan untuk Layar Host Guru.
     */
    public function hostPoll(LiveQuizSession $session)
    {
        $this->authorizeSession($session);

        $participantsCount = $session->participants()->count();
        $currentQuestion = $session->currentQuestion();

        $answersCount = 0;
        if ($session->status === 'question' && $currentQuestion) {
            $answersCount = $session->answers()
                ->where('question_id', $currentQuestion->id)
                ->count();
        }

        $participants = $session->participants()->with('student.schoolClass')->get()->map(fn($p) => [
            'id'    => $p->id,
            'name'  => $p->nickname,
            'class' => $p->student?->schoolClass?->name ?? 'Siswa',
        ]);

        $questionData = null;
        if ($currentQuestion) {
            $questionData = [
                'id'             => $currentQuestion->id,
                'question_text'  => $currentQuestion->question_text,
                'options'        => $currentQuestion->options,
                'correct_answer' => $currentQuestion->correct_answer,
            ];
        }

        $leaderboardData = $session->getTopLeaderboard(10)->map(fn($p) => [
            'participant_id' => $p->id,
            'name'           => $p->nickname,
            'class_name'     => $p->student?->schoolClass?->name ?? 'Siswa',
            'total_score'    => $p->total_score,
            'correct_count'  => $p->correct_answers_count,
        ]);

        $podiumRaw = $session->getPodium();
        $podiumData = [
            'first'  => isset($podiumRaw[0]) ? ['name' => $podiumRaw[0]->nickname, 'score' => $podiumRaw[0]->total_score, 'class' => $podiumRaw[0]->student?->schoolClass?->name] : null,
            'second' => isset($podiumRaw[1]) ? ['name' => $podiumRaw[1]->nickname, 'score' => $podiumRaw[1]->total_score, 'class' => $podiumRaw[1]->student?->schoolClass?->name] : null,
            'third'  => isset($podiumRaw[2]) ? ['name' => $podiumRaw[2]->nickname, 'score' => $podiumRaw[2]->total_score, 'class' => $podiumRaw[2]->student?->schoolClass?->name] : null,
        ];

        $dist = $session->getAnswerDistribution();
        $distCounts = $dist['counts'] ?? ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0];

        $data = [
            'status'                 => $session->status,
            'current_question_index' => $session->current_question_index,
            'total_questions'        => $session->total_questions,
            'time_limit'             => $session->time_limit_seconds,
            'remaining_seconds'      => $session->remainingSeconds(),
            'started_at'             => $session->question_started_at?->timestamp,
            'show_answer'            => in_array($session->status, ['reveal', 'leaderboard', 'finished']),
            'show_leaderboard'       => in_array($session->status, ['leaderboard']),
            'total_participants'     => $participantsCount,
            'participants_count'     => $participantsCount,
            'participants'           => $participants,
            'answers_count'          => $answersCount,
            'answer_distribution'    => $distCounts,
            'distribution'           => $dist,
            'question'               => $questionData,
            'leaderboard'            => $leaderboardData,
            'podium'                 => $podiumData,
            'grades_saved'           => (bool) $session->grades_saved,
        ];

        return response()->json($data);
    }

    /**
     * Menghapus Sesi Kuis Live.
     */
    public function destroy(LiveQuizSession $session)
    {
        $this->authorizeSession($session);
        $session->delete();

        return redirect()->route('teacher.live-quiz.index')->with('success', 'Sesi kuis live berhasil dihapus.');
    }
}
