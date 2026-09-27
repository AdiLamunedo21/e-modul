<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LiveQuizAnswer;
use App\Models\LiveQuizParticipant;
use App\Models\LiveQuizSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveQuizController extends Controller
{
    private function student(): Student
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user();
        return $student;
    }

    /**
     * Endpoint API Ringan untuk Polling Deteksi Kuis Live yang Sedang Aktif di Dashboard Siswa.
     */
    public function checkActiveSession(Request $request)
    {
        $student = $this->student();

        $joinedClasses = $student->classes()->pluck('classes.id')->toArray();
        if ($student->class_id) {
            $joinedClasses[] = (int) $student->class_id;
        }
        $joinedClassIds = array_unique($joinedClasses);

        $activeLiveQuiz = LiveQuizSession::with(['module.subject', 'teacher', 'schoolClass'])
            ->where('is_active', true)
            ->where('status', '!=', 'finished')
            ->whereHas('module', function ($mq) {
                $mq->where('is_active', true);
            })
            ->where(function ($q) use ($joinedClassIds) {
                $q->whereNull('class_id');
                if (!empty($joinedClassIds)) {
                    $q->orWhereIn('class_id', $joinedClassIds)
                      ->orWhereHas('module', function ($mq) use ($joinedClassIds) {
                          $mq->whereIn('class_id', $joinedClassIds);
                      });
                }
            })
            ->latest()
            ->first();

        if (!$activeLiveQuiz) {
            return response()->json([
                'has_active_quiz' => false,
            ]);
        }

        return response()->json([
            'has_active_quiz' => true,
            'quiz' => [
                'id'              => $activeLiveQuiz->id,
                'pin'             => $activeLiveQuiz->pin,
                'title'           => $activeLiveQuiz->module->title ?? 'Kuis Live',
                'test_type'       => $activeLiveQuiz->test_type,
                'test_type_label' => $activeLiveQuiz->test_type === 'pre_test' ? 'Pre-Test' : 'Post-Test',
                'teacher_name'    => $activeLiveQuiz->teacher->name ?? 'Pengampu',
                'class_name'      => $activeLiveQuiz->schoolClass?->full_name ?? 'Umum',
                'status'          => $activeLiveQuiz->status,
            ],
        ]);
    }

    /**
     * Layar Masuk / Input PIN Kuis Live.
     */
    public function join(Request $request)
    {
        $student = $this->student();
        $prefillPin = $request->query('pin', '');

        // Cek apakah ada sesi kuis live yang sedang aktif untuk kelas siswa atau umum
        $studentClassIds = $student->classes()->pluck('classes.id')->toArray();
        if ($student->class_id) {
            $studentClassIds[] = (int) $student->class_id;
        }
        $studentClassIds = array_unique($studentClassIds);

        $activeClassSession = LiveQuizSession::where('is_active', true)
            ->where('status', '!=', 'finished')
            ->whereHas('module', function ($mq) {
                $mq->where('is_active', true);
            })
            ->where(function ($q) use ($studentClassIds) {
                $q->whereNull('class_id');
                if (!empty($studentClassIds)) {
                    $q->orWhereIn('class_id', $studentClassIds);
                }
            })
            ->latest()
            ->first();

        return view('pages.student.live-quiz.join', compact('prefillPin', 'activeClassSession'));
    }

    /**
     * Memproses Pendaftaran Siswa ke Sesi Kuis Live (Tanpa Wajib PIN).
     */
    public function submitJoin(Request $request)
    {
        $student = $this->student();

        $studentClassIds = $student->classes()->pluck('classes.id')->toArray();
        if ($student->class_id) {
            $studentClassIds[] = (int) $student->class_id;
        }
        $studentClassIds = array_unique($studentClassIds);

        $session = null;

        // 1. Coba cari sesi berdasarkan session_id
        if ($sessionId = $request->input('session_id')) {
            $session = LiveQuizSession::where('id', $sessionId)
                ->where('is_active', true)
                ->where('status', '!=', 'finished')
                ->whereHas('module', function ($mq) {
                    $mq->where('is_active', true);
                })
                ->first();
        }

        // 2. Coba cari sesi berdasarkan PIN jika diisi
        if (!$session && $pin = trim((string) ($request->input('pin_code') ?: $request->input('pin', '')))) {
            $session = LiveQuizSession::where('pin_code', $pin)
                ->where('is_active', true)
                ->where('status', '!=', 'finished')
                ->whereHas('module', function ($mq) {
                    $mq->where('is_active', true);
                })
                ->first();
        }

        // 3. Jika tidak ada ID/PIN, otomatis temukan sesi kuis yang sedang aktif untuk kelas siswa
        if (!$session) {
            $session = LiveQuizSession::where('is_active', true)
                ->where('status', '!=', 'finished')
                ->whereHas('module', function ($mq) {
                    $mq->where('is_active', true);
                })
                ->where(function ($q) use ($studentClassIds) {
                    $q->whereNull('class_id');
                    if (!empty($studentClassIds)) {
                        $q->orWhereIn('class_id', $studentClassIds);
                    }
                })
                ->latest()
                ->first();
        }

        if (!$session) {
            return back()->withInput()->withErrors([
                'pin'     => 'Sesi kuis live tidak ditemukan, sedang ditutup, atau kelas belum dibuka oleh guru.',
                'session' => 'Sesi kuis live tidak ditemukan, sedang ditutup, atau kelas belum dibuka oleh guru.',
            ]);
        }

        // Jika sesi dibatasi untuk kelas tertentu, pastikan siswa tergabung di kelas tersebut
        if ($session->class_id) {
            $isClassMember = $student->classes()->where('classes.id', $session->class_id)->exists()
                             || $student->class_id == $session->class_id;

            if (!$isClassMember) {
                return back()->withInput()->withErrors([
                    'pin'     => "Kuis ini hanya diperuntukkan bagi siswa rombel {$session->schoolClass?->full_name}.",
                    'session' => "Kuis ini hanya diperuntukkan bagi siswa rombel {$session->schoolClass?->full_name}.",
                ]);
            }
        }

        // Daftarkan atau ambil data peserta
        LiveQuizParticipant::firstOrCreate(
            [
                'live_quiz_session_id' => $session->id,
                'student_id'           => $student->id,
            ],
            [
                'nickname'              => $student->name,
                'total_score'           => 0,
                'correct_answers_count' => 0,
                'last_score_earned'     => 0,
            ]
        );

        return redirect()->route('student.live-quiz.play', $session);
    }

    /**
     * Layar Permainan Siswa di Smartphone / Laptop.
     */
    public function play(LiveQuizSession $session)
    {
        $student = $this->student();

        $participant = LiveQuizParticipant::where('live_quiz_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$participant) {
            return redirect()->route('student.live-quiz.join', ['pin' => $session->pin_code])
                ->with('error', 'Silakan masukkan PIN untuk bergabung ke sesi kuis ini.');
        }

        return view('pages.student.live-quiz.play', compact('session', 'participant'));
    }

    /**
     * Menerima Jawaban Siswa untuk Soal yang Sedang Aktif.
     */
    public function answer(Request $request, LiveQuizSession $session)
    {
        $student = $this->student();

        if (!$session->is_active || ($session->module && !$session->module->is_active)) {
            return response()->json(['success' => false, 'message' => 'Sesi kuis sedang dinonaktifkan / ditutup sementara oleh guru.'], 422);
        }

        if ($session->status !== 'question') {
            return response()->json(['success' => false, 'message' => 'Waktu menjawab soal telah berakhir.'], 422);
        }

        $participant = LiveQuizParticipant::where('live_quiz_session_id', $session->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $currentQuestion = $session->currentQuestion();
        if (!$currentQuestion) {
            return response()->json(['success' => false, 'message' => 'Soal tidak ditemukan.'], 404);
        }

        // Cegah jawaban ganda pada butir soal yang sama
        $existing = LiveQuizAnswer::where('live_quiz_session_id', $session->id)
            ->where('live_quiz_participant_id', $participant->id)
            ->where('question_id', $currentQuestion->id)
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Anda telah mengirimkan jawaban untuk soal ini.'], 422);
        }

        $selectedOption = strtoupper(trim((string) ($request->input('selected_option') ?: $request->input('chosen_option'))));
        if (empty($selectedOption)) {
            return response()->json(['success' => false, 'message' => 'Pilihan jawaban tidak valid.'], 422);
        }

        $correctAnswer = strtoupper(trim((string) $currentQuestion->correct_answer));
        $isCorrect = ($selectedOption === $correctAnswer);

        $responseTimeMs = (int) ($request->response_time_ms ?? 0);
        $scoreEarned = LiveQuizAnswer::calculatePoints($session->time_limit_seconds, $responseTimeMs, $isCorrect);

        LiveQuizAnswer::create([
            'live_quiz_session_id'     => $session->id,
            'live_quiz_participant_id' => $participant->id,
            'question_id'              => $currentQuestion->id,
            'question_index'           => $session->current_question_index,
            'selected_option'          => $selectedOption,
            'is_correct'               => $isCorrect,
            'response_time_ms'         => $responseTimeMs,
            'score_earned'             => $scoreEarned,
        ]);

        // Perbarui skor peserta
        $participant->total_score += $scoreEarned;
        if ($isCorrect) {
            $participant->correct_answers_count++;
        }
        $participant->last_score_earned = $scoreEarned;
        $participant->last_answered_at = now();
        $participant->save();

        return response()->json([
            'success'       => true,
            'is_correct'    => $isCorrect,
            'points'        => $scoreEarned,
            'score_earned'  => $scoreEarned,
            'total_score'   => $participant->total_score,
        ]);
    }

    /**
     * Endpoint Polling Ringan untuk Perangkat Siswa.
     */
    public function playerPoll(LiveQuizSession $session)
    {
        $student = $this->student();

        $participant = LiveQuizParticipant::where('live_quiz_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$participant) {
            return response()->json(['error' => 'not_registered'], 403);
        }

        $currentQuestion = $session->currentQuestion();

        $hasAnswered = false;
        $myAnswer = null;
        if ($currentQuestion) {
            $ans = LiveQuizAnswer::where('live_quiz_session_id', $session->id)
                ->where('live_quiz_participant_id', $participant->id)
                ->where('question_id', $currentQuestion->id)
                ->first();

            if ($ans) {
                $hasAnswered = true;
                $myAnswer = [
                    'chosen_option'   => $ans->selected_option,
                    'selected_option' => $ans->selected_option,
                    'is_correct'      => $ans->is_correct,
                    'points'          => $ans->score_earned,
                    'score_earned'    => $ans->score_earned,
                ];
            }
        }

        // Peringkat siswa saat ini
        $myRank = LiveQuizParticipant::where('live_quiz_session_id', $session->id)
            ->where('total_score', '>', $participant->total_score)
            ->count() + 1;

        $data = [
            'status'                 => !$session->is_active ? 'inactive' : (in_array($session->status, ['question', 'reveal', 'leaderboard']) ? 'active' : $session->status),
            'raw_status'             => $session->status,
            'is_active'              => (bool) $session->is_active,
            'current_question_index' => $session->current_question_index,
            'total_questions'        => $session->total_questions,
            'time_limit'             => $session->time_limit_seconds,
            'remaining_seconds'      => $session->remainingSeconds(),
            'started_at'             => $session->question_started_at?->timestamp,
            'show_answer'            => in_array($session->status, ['reveal', 'leaderboard', 'finished']),
            'show_leaderboard'       => in_array($session->status, ['leaderboard']),
            'has_answered'           => $hasAnswered,
            'my_total_score'         => $participant->total_score,
            'my_score'               => $participant->total_score,
            'my_rank'                => $myRank,
            'total_participants'     => $session->participants()->count(),
            'my_answer'              => $myAnswer,
        ];

        // Saat soal aktif, reveal, atau leaderboard: kirim pertanyaan & opsi jawaban lengkap
        if (in_array($session->status, ['question', 'reveal', 'leaderboard']) && $currentQuestion) {
            $data['question'] = [
                'id'            => $currentQuestion->id,
                'question_text' => $currentQuestion->question_text,
                'options'       => $currentQuestion->options,
                'time_limit'    => $session->time_limit_seconds,
            ];
        }

        // Saat reveal / leaderboard: kirim informasi kunci jawaban & penjelasan
        if (in_array($session->status, ['reveal', 'leaderboard']) && $currentQuestion) {
            $data['reveal'] = [
                'correct_answer' => strtoupper($currentQuestion->correct_answer),
                'explanation'    => $currentQuestion->explanation ?? null,
            ];
        }

        return response()->json($data);
    }

    /**
     * Siswa Keluar dari Room Kuis Live.
     */
    public function leave(LiveQuizSession $session)
    {
        $student = $this->student();

        $participant = LiveQuizParticipant::where('live_quiz_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if ($participant) {
            // Jika sesi masih di tahap lobby (belum dimulai guru), hapus data peserta agar akurat di layar guru
            if ($session->status === 'lobby') {
                $participant->delete();
            }
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Anda telah keluar dari room kuis live.',
                'redirect' => route('student.dashboard'),
            ]);
        }

        return redirect()->route('student.dashboard')->with('success', 'Anda telah keluar dari room kuis live.');
    }
}
