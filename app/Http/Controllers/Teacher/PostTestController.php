<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostTestController extends Controller
{
    private function teacher()
    {
        return Auth::guard('teacher')->user();
    }

    private function authorize(Module $module): void
    {
        abort_if($module->teacher_id !== $this->teacher()->id, 403);
    }

    /**
     * Halaman Quiz Builder Post-test.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $postTestData = is_array($module->post_test_data) ? $module->post_test_data : [];

        $data = array_merge([
            'judul'        => 'Post-test: Evaluasi Pemahaman Materi',
            'durasi_menit' => 20,
            'kktp'         => 75,
            'petunjuk'     => 'Kerjakan soal post-test berikut secara mandiri dan teliti untuk mengukur penguasaan materi setelah menyelesaikan seluruh tahapan pembelajaran.',
            'acak_soal'    => false,
            'questions'    => [],
        ], $postTestData);

        return view('pages.teacher.modules.post-test', compact('module', 'data'));
    }

    /**
     * Halaman Pratinjau Post-test — tampilan simulasi siswa mandiri.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $postTestData = is_array($module->post_test_data) ? $module->post_test_data : [];

        $data = array_merge([
            'judul'        => 'Post-test: Evaluasi Pemahaman Materi',
            'durasi_menit' => 20,
            'kktp'         => 75,
            'petunjuk'     => '',
            'acak_soal'    => false,
            'questions'    => [],
        ], $postTestData);

        return view('pages.teacher.modules.preview-post-test', compact('module', 'data'));
    }

    /**
     * Simpan konfigurasi dan soal Post-test.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasPostTest = $request->boolean('has_post_test');

        $rules = [
            'judul'        => ['nullable', 'string', 'max:255'],
            'durasi_menit' => ['nullable', 'integer', 'min:1', 'max:300'],
            'kktp'         => ['nullable', 'integer', 'min:0', 'max:100'],
            'petunjuk'     => ['nullable', 'string'],
            'acak_soal'    => ['nullable'],
        ];

        // Jika post-test diaktifkan, validasi butir soal
        if ($hasPostTest) {
            $rules['questions']                    = ['required', 'array', 'min:1'];
            $rules['questions.*.pertanyaan']       = ['required', 'string', 'min:3'];
            $rules['questions.*.kunci_jawaban']    = ['required', 'string', 'in:A,B,C,D,E'];
            $rules['questions.*.pilihan.A']        = ['required', 'string'];
            $rules['questions.*.pilihan.B']        = ['required', 'string'];
            $rules['questions.*.pilihan.C']        = ['nullable', 'string'];
            $rules['questions.*.pilihan.D']        = ['nullable', 'string'];
            $rules['questions.*.pilihan.E']        = ['nullable', 'string'];
            $rules['questions.*.bobot']            = ['nullable', 'integer', 'min:1'];
            $rules['questions.*.pembahasan']       = ['nullable', 'string'];
        }

        $validated = $request->validate($rules, [
            'questions.required'                 => 'Minimal harus ada 1 butir soal jika fitur Post-test diaktifkan.',
            'questions.min'                      => 'Minimal harus ada 1 butir soal jika fitur Post-test diaktifkan.',
            'questions.*.pertanyaan.required'    => 'Teks pertanyaan wajib diisi pada setiap butir soal.',
            'questions.*.kunci_jawaban.required' => 'Pilih kunci jawaban yang benar (A/B/C/D/E) pada setiap butir soal.',
            'questions.*.pilihan.A.required'     => 'Pilihan A wajib diisi.',
            'questions.*.pilihan.B.required'     => 'Pilihan B wajib diisi.',
        ]);

        // Proses sanitasi struktur questions
        $cleanQuestions = [];
        if (!empty($request->questions) && is_array($request->questions)) {
            foreach ($request->questions as $index => $q) {
                if (empty(trim($q['pertanyaan'] ?? ''))) {
                    continue;
                }

                // Filter pilihan yang tidak kosong
                $pilihan = [];
                foreach (['A', 'B', 'C', 'D', 'E'] as $key) {
                    $val = trim($q['pilihan'][$key] ?? '');
                    if ($val !== '') {
                        $pilihan[$key] = $val;
                    }
                }

                $cleanQuestions[] = [
                    'id'            => $index + 1,
                    'pertanyaan'    => $q['pertanyaan'],
                    'pilihan'       => $pilihan,
                    'kunci_jawaban' => strtoupper($q['kunci_jawaban'] ?? 'A'),
                    'bobot'         => !empty($q['bobot']) ? (int) $q['bobot'] : 10,
                    'pembahasan'    => $q['pembahasan'] ?? '',
                ];
            }
        }

        $payload = [
            'judul'        => $request->input('judul', 'Post-test: Evaluasi Pemahaman Materi'),
            'durasi_menit' => (int) $request->input('durasi_menit', 20),
            'kktp'         => (int) $request->input('kktp', 75),
            'petunjuk'     => $request->input('petunjuk', ''),
            'acak_soal'    => $request->boolean('acak_soal'),
            'questions'    => $cleanQuestions,
        ];

        $module->update([
            'has_post_test'  => $hasPostTest,
            'post_test_data' => $payload,
        ]);

        $statusText = $hasPostTest ? 'diaktifkan & disimpan' : 'disimpan (status Non-Aktif)';
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
