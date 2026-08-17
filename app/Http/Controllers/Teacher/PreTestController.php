<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreTestController extends Controller
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
     * Halaman Quiz Builder Pre-test.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $preTestData = is_array($module->pre_test_data) ? $module->pre_test_data : [];

        $data = array_merge([
            'judul'        => 'Pre-test Pembuka',
            'durasi_menit' => 15,
            'kktp'         => 75,
            'petunjuk'     => 'Kerjakan soal pre-test berikut secara mandiri untuk mengukur pemahaman awal Anda sebelum memulai materi.',
            'acak_soal'    => false,
            'questions'    => [],
        ], $preTestData);

        return view('pages.teacher.modules.pre-test', compact('module', 'data'));
    }

    /**
     * Halaman Pratinjau Pre-test — tampilan mandiri tanpa dashboard.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $preTestData = is_array($module->pre_test_data) ? $module->pre_test_data : [];

        $data = array_merge([
            'judul'        => 'Pre-test Pembuka',
            'durasi_menit' => 15,
            'kktp'         => 75,
            'petunjuk'     => '',
            'acak_soal'    => false,
            'questions'    => [],
        ], $preTestData);

        return view('pages.teacher.modules.preview-pre-test', compact('module', 'data'));
    }

    /**
     * Simpan konfigurasi dan soal Pre-test.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasPreTest = $request->boolean('has_pre_test');

        $rules = [
            'judul'        => ['nullable', 'string', 'max:255'],
            'durasi_menit' => ['nullable', 'integer', 'min:1', 'max:300'],
            'kktp'         => ['nullable', 'integer', 'min:0', 'max:100'],
            'petunjuk'     => ['nullable', 'string'],
            'acak_soal'    => ['nullable'],
        ];

        // Jika pre-test diaktifkan, validasi soal
        if ($hasPreTest) {
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
            'questions.required' => 'Minimal harus ada 1 soal jika fitur Pre-test diaktifkan.',
            'questions.min'      => 'Minimal harus ada 1 soal jika fitur Pre-test diaktifkan.',
            'questions.*.pertanyaan.required' => 'Teks pertanyaan wajib diisi pada setiap soal.',
            'questions.*.kunci_jawaban.required' => 'Pilih kunci jawaban yang benar (A/B/C/D/E) pada setiap soal.',
            'questions.*.pilihan.A.required' => 'Pilihan A wajib diisi.',
            'questions.*.pilihan.B.required' => 'Pilihan B wajib diisi.',
        ]);

        // Proses struktur questions
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
            'judul'        => $request->input('judul', 'Pre-test Pembuka'),
            'durasi_menit' => (int) $request->input('durasi_menit', 15),
            'kktp'         => (int) $request->input('kktp', 75),
            'petunjuk'     => $request->input('petunjuk', ''),
            'acak_soal'    => $request->boolean('acak_soal'),
            'questions'    => $cleanQuestions,
        ];

        $module->update([
            'has_pre_test'  => $hasPreTest,
            'pre_test_data' => $payload,
        ]);

        $statusText = $hasPreTest ? 'diaktifkan & disimpan' : 'disimpan (status Non-Aktif)';
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
