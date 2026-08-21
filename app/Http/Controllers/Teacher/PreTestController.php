<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\PreTest;
use App\Models\PreTestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * =============================================================================
 * CONTROLLER: PreTestController
 * =============================================================================
 * KLASIFIKASI E-MODUL: Bagian 4 — Evaluasi & Latihan (Soal Latihan Diagnostik)
 * -----------------------------------------------------------------------------
 * Controller ini mengelola konfigurasi kuis Pre-test dan butir-butir soal
 * pilihan ganda (PreTest & PreTestQuestion) yang dikontrol oleh flag `has_pre_test`.
 * =============================================================================
 */
class PreTestController extends Controller
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
     * Halaman Quiz Builder Pre-test.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $preTest = $module->preTest()->firstOrCreate([], [
            'title'               => 'Pre-test Pembuka',
            'duration_minutes'    => 15,
            'kktp'                => 75,
            'instructions'        => 'Kerjakan soal pre-test berikut secara mandiri untuk mengukur pemahaman awal Anda sebelum memulai materi.',
            'randomize_questions' => false,
        ]);

        $preTest->load('questions');

        return view('pages.teacher.modules.pre-test', compact('module', 'preTest'));
    }

    /**
     * Halaman Pratinjau Pre-test — tampilan simulasi mandiri.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $preTest = $module->preTest()->with('questions')->firstOrCreate([], [
            'title'               => 'Pre-test Pembuka',
            'duration_minutes'    => 15,
            'kktp'                => 75,
            'instructions'        => '',
            'randomize_questions' => false,
        ]);

        return view('pages.teacher.modules.preview-pre-test', compact('module', 'preTest'));
    }

    /**
     * Simpan konfigurasi dan soal Pre-test langsung ke database.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasPreTest = $request->boolean('has_pre_test');

        $rules = [
            'title'               => ['nullable', 'string', 'max:255'],
            'duration_minutes'    => ['nullable', 'integer', 'min:1', 'max:300'],
            'kktp'                => ['nullable', 'integer', 'min:0', 'max:100'],
            'instructions'        => ['nullable', 'string'],
            'randomize_questions' => ['nullable'],
        ];

        // Validasi butir soal jika pre-test diaktifkan
        if ($hasPreTest) {
            $rules['questions']                    = ['required', 'array', 'min:1'];
            $rules['questions.*.question_text']    = ['required', 'string', 'min:3'];
            $rules['questions.*.correct_answer']   = ['required', 'string', 'in:A,B,C,D,E'];
            $rules['questions.*.options.A']        = ['required', 'string'];
            $rules['questions.*.options.B']        = ['required', 'string'];
            $rules['questions.*.score_weight']     = ['nullable', 'integer', 'min:1'];
            $rules['questions.*.explanation']      = ['nullable', 'string'];
        }

        $request->validate($rules, [
            'questions.required'                 => 'Minimal harus ada 1 butir soal jika fitur Pre-test diaktifkan.',
            'questions.min'                      => 'Minimal harus ada 1 butir soal jika fitur Pre-test diaktifkan.',
            'questions.*.question_text.required' => 'Teks pertanyaan wajib diisi pada setiap butir soal.',
            'questions.*.correct_answer.required'=> 'Pilih kunci jawaban yang benar (A/B/C/D/E) pada setiap butir soal.',
            'questions.*.options.A.required'     => 'Pilihan A wajib diisi.',
            'questions.*.options.B.required'     => 'Pilihan B wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $module, $hasPreTest) {
            // Update status flag di tabel modules
            $module->update(['has_pre_test' => $hasPreTest]);

            // Update / Create PreTest di tabel pre_tests
            $preTest = $module->preTest()->firstOrCreate([]);
            $preTest->update([
                'title'               => $request->input('title', 'Pre-test Pembuka'),
                'duration_minutes'    => (int) $request->input('duration_minutes', 15),
                'kktp'                => (int) $request->input('kktp', 75),
                'instructions'        => $request->input('instructions', ''),
                'randomize_questions' => $request->boolean('randomize_questions'),
            ]);

            // Sinkronisasi data butir soal ke tabel pre_test_questions
            $preTest->questions()->delete();

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

                    $preTest->questions()->create([
                        'question_text'  => $qText,
                        'options'        => $options,
                        'correct_answer' => strtoupper($q['correct_answer'] ?? $q['kunci_jawaban'] ?? 'A'),
                        'score_weight'   => !empty($q['score_weight'] ?? $q['bobot'] ?? null) ? (int) ($q['score_weight'] ?? $q['bobot']) : 10,
                        'explanation'    => trim($q['explanation'] ?? $q['pembahasan'] ?? ''),
                        'order_num'      => $order++,
                    ]);
                }
            }
        });

        $statusText = $hasPreTest ? 'diaktifkan & disimpan ke database' : 'disimpan (status Non-Aktif)';
        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', "Konfigurasi Pre-test berhasil {$statusText}! ✅");
    }

    /**
     * Toggle cepat status aktif/non-aktif Pre-test.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $module->update([
            'has_pre_test' => !$module->has_pre_test,
        ]);

        $status = $module->has_pre_test ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Fitur Pre-test berhasil {$status}! ✅");
    }
}
