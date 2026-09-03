<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\PostTest;
use App\Models\PostTestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * =============================================================================
 * CONTROLLER: PostTestController
 * =============================================================================
 * KLASIFIKASI E-MODUL: Bagian 5 — Bagian Akhir (Tes Akhir Modul / Uji Kompetensi)
 * -----------------------------------------------------------------------------
 * Controller ini mengelola kuis evaluasi akhir modul dan butir-butir soal
 * pilihan ganda (PostTest & PostTestQuestion) yang dikontrol oleh flag `has_post_test`.
 * =============================================================================
 */
class PostTestController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    private function authorize(Module $module): void
    {
        abort_if($module->teacher_id !== $this->teacher()->id, 403, 'Anda tidak memiliki akses ke modul ini.');
    }

    /**
     * Halaman Quiz Builder Post-test.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $postTest = $module->postTest()->firstOrCreate([], [
            'title'               => 'Post-test: Evaluasi Pemahaman Materi',
            'kktp'                => 75,
            'instructions'        => 'Kerjakan soal post-test berikut secara mandiri dan teliti untuk mengukur penguasaan materi setelah menyelesaikan seluruh tahapan pembelajaran.',
            'randomize_questions' => false,
        ]);

        $postTest->load('questions');

        return view('pages.teacher.modules.post-test', compact('module', 'postTest'));
    }

    /**
     * Halaman Pratinjau Post-test — tampilan simulasi siswa mandiri.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $postTest = $module->postTest()->with('questions')->firstOrCreate([], [
            'title'               => 'Post-test: Evaluasi Pemahaman Materi',
            'kktp'                => 75,
            'instructions'        => '',
            'randomize_questions' => false,
        ]);

        return view('pages.teacher.modules.preview-post-test', compact('module', 'postTest'));
    }

    /**
     * Simpan konfigurasi dan soal Post-test langsung ke database.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasPostTest = $request->boolean('has_post_test');

        $rules = [
            'title'               => ['nullable', 'string', 'max:255'],
            'kktp'                => ['nullable', 'integer', 'min:0', 'max:100'],
            'instructions'        => ['nullable', 'string'],
            'randomize_questions' => ['nullable'],
        ];

        // Validasi butir soal jika post-test diaktifkan
        if ($hasPostTest) {
            $rules['questions']                         = ['required', 'array', 'min:1'];
            $rules['questions.*.question_text']         = ['required', 'string', 'min:3'];
            $rules['questions.*.correct_answer']        = ['required', 'string', 'in:A,B,C,D,E'];
            $rules['questions.*.options.A']             = ['required', 'string'];
            $rules['questions.*.options.B']             = ['required', 'string'];
            $rules['questions.*.score_weight']          = ['nullable', 'integer', 'min:1'];
            $rules['questions.*.time_limit_seconds']     = ['nullable', 'integer', 'min:0', 'max:3600'];
            $rules['questions.*.explanation']           = ['nullable', 'string'];
        }

        $request->validate($rules, [
            'questions.required'                 => 'Minimal harus ada 1 butir soal jika fitur Post-test diaktifkan.',
            'questions.min'                      => 'Minimal harus ada 1 butir soal jika fitur Post-test diaktifkan.',
            'questions.*.question_text.required' => 'Teks pertanyaan wajib diisi pada setiap butir soal.',
            'questions.*.correct_answer.required'=> 'Pilih kunci jawaban yang benar (A/B/C/D/E) pada setiap butir soal.',
            'questions.*.options.A.required'     => 'Pilihan A wajib diisi.',
            'questions.*.options.B.required'     => 'Pilihan B wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $module, $hasPostTest) {
            // Update status flag di tabel modules
            $module->update(['has_post_test' => $hasPostTest]);

            // Update / Create PostTest di tabel post_tests
            $postTest = $module->postTest()->firstOrCreate([]);
            $postTest->update([
                'title'               => $request->input('title', 'Post-test: Evaluasi Pemahaman Materi'),
                'kktp'                => (int) $request->input('kktp', 75),
                'instructions'        => $request->input('instructions', ''),
                'randomize_questions' => $request->boolean('randomize_questions'),
            ]);

            // Sinkronisasi data butir soal ke tabel post_test_questions
            $postTest->questions()->delete();

            $order = 1;
            if (!empty($request->questions) && is_array($request->questions)) {
                foreach ($request->questions as $q) {
                    $qText = trim($q['question_text'] ?? $q['pertanyaan'] ?? '');
                    if (empty($qText)) {
                        continue;
                    }

                    $rawOptions = $q['options'] ?? $q['pilihan'] ?? [];
                    $options = [];
                    foreach (['A', 'B', 'C', 'D', 'E'] as $key) {
                        $val = trim($rawOptions[$key] ?? '');
                        if ($val !== '') {
                            $options[$key] = $val;
                        }
                    }

                    $qTimeLimit = !empty($q['time_limit_seconds']) ? (int) $q['time_limit_seconds'] : null;

                    $postTest->questions()->create([
                        'question_text'      => $qText,
                        'options'            => $options,
                        'correct_answer'     => strtoupper($q['correct_answer'] ?? $q['kunci_jawaban'] ?? 'A'),
                        'score_weight'       => !empty($q['score_weight'] ?? $q['bobot'] ?? null) ? (int) ($q['score_weight'] ?? $q['bobot']) : 10,
                        'time_limit_seconds' => ($qTimeLimit > 0) ? $qTimeLimit : null,
                        'explanation'        => trim($q['explanation'] ?? $q['pembahasan'] ?? ''),
                        'order_num'          => $order++,
                    ]);
                }
            }
        });

        $statusText = $hasPostTest ? 'diaktifkan & disimpan ke database' : 'disimpan (status Non-Aktif)';
        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', "Konfigurasi Post-test berhasil {$statusText}! ✅");
    }

    /**
     * Toggle cepat status aktif/non-aktif Post-test.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $module->update([
            'has_post_test' => !$module->has_post_test,
        ]);

        $status = $module->has_post_test ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Fitur Post-test berhasil {$status}! ✅");
    }
}
