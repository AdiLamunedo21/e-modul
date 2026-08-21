<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * =============================================================================
 * CONTROLLER: VideoController
 * =============================================================================
 * KLASIFIKASI E-MODUL: Bagian 3 — Kegiatan Belajar (Multimedia Video YouTube)
 * -----------------------------------------------------------------------------
 * Controller ini mengelola tautan video pembelajaran YouTube, instruksi resume,
 * dan fitur ringkasan video siswa yang dikontrol oleh flag `has_video`.
 * =============================================================================
 */
class VideoController extends Controller
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
     * Ekstraksi YouTube Video ID dari berbagai variasi URL.
     */
    public static function extractYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // Jika langsung 11 karakter ID video YouTube
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        // Coba parse query parameter 'v'
        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $queryParams);
            if (isset($queryParams['v']) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $queryParams['v'])) {
                return $queryParams['v'];
            }
        }

        // Pattern untuk youtu.be, embed, shorts, live, v
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|shorts\/|live\/))([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
            return $matches[1];
        }

        // Fallback regex umum
        if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Halaman Editor Komponen Video YouTube & Panduan Ringkasan.
     */
    public function edit(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $videoData = is_array($module->video_data) ? $module->video_data : [];

        $data = array_merge([
            'video_title'        => 'Video Pembelajaran: ' . $module->title,
            'youtube_url'        => '',
            'youtube_id'         => '',
            'estimated_duration' => 15,
            'instructions'       => 'Simak video pembelajaran di bawah ini secara seksama. Catat poin-poin penting dan tuliskan ringkasan pemahaman Anda pada kolom yang disediakan sebelum beralih ke tahapan berikutnya.',
            'guiding_questions'  => [
                'Apa konsep atau topik utama yang dijelaskan dalam video ini?',
                'Sebutkan langkah kerja atau poin krusial yang harus diperhatikan!',
                'Bagaimana penerapan konsep tersebut dalam praktik kejuruan Anda?',
            ],
            'min_summary_chars'  => 100,
            'min_summary_words'  => 20,
        ], $videoData);

        if (empty($data['youtube_id']) && !empty($data['youtube_url'])) {
            $data['youtube_id'] = self::extractYoutubeId($data['youtube_url']);
        }

        return view('pages.teacher.modules.video', compact('module', 'data'));
    }

    /**
     * Halaman Pratinjau Video — simulasi tampilan siswa.
     */
    public function preview(Module $module)
    {
        $this->authorize($module);
        $module->load('schoolClass');

        $videoData = is_array($module->video_data) ? $module->video_data : [];

        $data = array_merge([
            'video_title'        => 'Video Pembelajaran: ' . $module->title,
            'youtube_url'        => '',
            'youtube_id'         => '',
            'estimated_duration' => 15,
            'instructions'       => 'Simak video pembelajaran di bawah ini secara seksama. Catat poin-poin penting dan tuliskan ringkasan pemahaman Anda pada kolom yang disediakan.',
            'guiding_questions'  => [],
            'min_summary_chars'  => 100,
            'min_summary_words'  => 20,
        ], $videoData);

        if (empty($data['youtube_id']) && !empty($data['youtube_url'])) {
            $data['youtube_id'] = self::extractYoutubeId($data['youtube_url']);
        }

        return view('pages.teacher.modules.preview-video', compact('module', 'data'));
    }

    /**
     * Simpan Pengaturan Video YouTube & Panduan Ringkasan.
     */
    public function update(Request $request, Module $module)
    {
        $this->authorize($module);

        $hasVideo = $request->boolean('has_video');
        $rawUrl = $request->input('youtube_url');
        $youtubeId = self::extractYoutubeId($rawUrl);

        $rules = [
            'video_title'         => [$hasVideo ? 'required' : 'nullable', 'string', 'max:255'],
            'youtube_url'         => [$hasVideo ? 'required' : 'nullable', 'string', 'max:500'],
            'estimated_duration'  => ['nullable', 'integer', 'min:1', 'max:240'],
            'instructions'        => ['nullable', 'string'],
            'guiding_questions'   => ['nullable', 'array'],
            'guiding_questions.*' => ['nullable', 'string', 'max:255'],
            'min_summary_chars'   => ['nullable', 'integer', 'min:10', 'max:2000'],
            'min_summary_words'   => ['nullable', 'integer', 'min:5', 'max:500'],
        ];

        $request->validate($rules, [
            'video_title.required' => 'Judul video pembelajaran wajib diisi jika fitur Video diaktifkan.',
            'youtube_url.required' => 'Tautan / URL YouTube wajib diisi jika fitur Video diaktifkan.',
        ]);

        if ($hasVideo && empty($youtubeId)) {
            return back()
                ->withInput()
                ->withErrors(['youtube_url' => 'Format URL YouTube tidak valid. Pastikan URL berasal dari YouTube (contoh: https://www.youtube.com/watch?v=... atau https://youtu.be/...)']);
        }

        // Filter pertanyaan panduan
        $guidingQuestions = collect($request->input('guiding_questions', []))
            ->map(fn($q) => trim($q))
            ->filter(fn($q) => !empty($q))
            ->values()
            ->toArray();

        $payload = [
            'video_title'        => $request->input('video_title', 'Video Pembelajaran: ' . $module->title),
            'youtube_url'        => $rawUrl,
            'youtube_id'         => $youtubeId,
            'estimated_duration' => (int) $request->input('estimated_duration', 15),
            'instructions'       => $request->input('instructions', ''),
            'guiding_questions'  => $guidingQuestions,
            'min_summary_chars'  => (int) $request->input('min_summary_chars', 100),
            'min_summary_words'  => (int) $request->input('min_summary_words', 20),
        ];

        $module->update([
            'has_video'  => $hasVideo,
            'video_data' => $payload,
        ]);

        $statusText = $hasVideo ? 'diaktifkan & disimpan' : 'disimpan (status Non-Aktif)';
        return redirect()
            ->route('teacher.modules.show', $module)
            ->with('success', "Komponen Video & Ringkasan YouTube berhasil {$statusText}! ✅");
    }

    /**
     * Toggle cepat status aktif/nonaktif Video YouTube.
     */
    public function toggle(Request $request, Module $module)
    {
        $this->authorize($module);

        $module->update([
            'has_video' => !$module->has_video,
        ]);

        $status = $module->has_video ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Komponen Video & Ringkasan YouTube berhasil {$status}! ✅");
    }
}
