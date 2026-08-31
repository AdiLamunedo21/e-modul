<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Materi — {{ $data['judul_materi'] ?? $module->title }}</title>
    <link rel="icon" href="{{ asset('lgsmk.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }

        /* ── Prose-like styling for materi content ── */
        .materi-prose h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: .75rem; margin-top: 1.5rem; color: #0f172a; }
        .materi-prose h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1.25rem; color: #1e293b; }
        .materi-prose h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1rem; color: #334155; }
        .materi-prose p { margin-bottom: .75rem; line-height: 1.75; }
        .materi-prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: .75rem; }
        .materi-prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: .75rem; }
        .materi-prose li { margin-bottom: .25rem; line-height: 1.65; }
        .materi-prose img { max-width: 100%; height: auto; border-radius: .75rem; margin: 1rem auto; display: block; }
        .materi-prose table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .materi-prose th, .materi-prose td { border: 1px solid #e2e8f0; padding: .5rem .75rem; text-align: left; font-size: .875rem; }
        .materi-prose th { background: #f1f5f9; font-weight: 700; }
        .materi-prose blockquote { border-left: 4px solid #6366f1; background: #eef2ff; padding: .75rem 1rem; margin: 1rem 0; border-radius: 0 .5rem .5rem 0; font-style: italic; }
        .materi-prose hr { border: none; border-top: 2px solid #e2e8f0; margin: 1.5rem 0; }
        .materi-prose a { color: #4f46e5; text-decoration: underline; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp .4s ease-out; }
    </style>
</head>
<body class="bg-slate-100 antialiased text-slate-900 min-h-screen">

    {{-- ═══ TOP BAR ═══ --}}
    <div class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 shadow-xl">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-lg shrink-0">📱</div>
                <div>
                    <h1 class="text-sm font-bold text-white">Simulasi Tampilan Materi Siswa</h1>
                    <p class="text-[11px] text-slate-400">Pratinjau lengkap — uraian teks, dokumen slide, dan rangkuman poin.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.modules.materi.edit', $module) }}"
                   class="px-4 py-2 text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition-all flex items-center gap-1.5">
                    <span>←</span> Kembali ke Editor
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ KONTEN PRATINJAU ═══ --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-16">
        <div class="w-full animate-fadeInUp">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200/60">

                {{-- Header Materi --}}
                <div class="p-6 sm:p-8 border-b border-slate-100">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            📖 Uraian Materi Terstruktur
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        {{ $data['judul_materi'] ?? 'Materi Pembelajaran' }}
                    </h1>
                    <p class="text-sm text-slate-500 mt-2 font-medium">
                        {{ $module->title }} — {{ $module->schoolClass->name ?? 'Kelas' }}
                    </p>
                </div>

                {{-- Isi Uraian Materi --}}
                <div class="p-6 sm:p-8">
                    <div class="materi-prose text-slate-800 leading-relaxed text-sm sm:text-base">
                        @if(!empty($data['uraian_materi']))
                            {!! $data['uraian_materi'] !!}
                        @else
                            <p class="text-slate-400 italic">Belum ada uraian materi yang ditulis. Silakan kembali ke editor dan tulis uraian materi terlebih dahulu.</p>
                        @endif
                    </div>

                    {{-- Berkas PPT / PDF --}}
                    @if(!empty($data['ppt_file_path']))
                        @php
                            $isPdf = str_ends_with(strtolower($data['ppt_file_name'] ?? ''), '.pdf');
                        @endphp
                        <div class="mt-8 p-5 rounded-2xl bg-gradient-to-r from-violet-50 to-indigo-50 border border-violet-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-violet-600 text-white flex items-center justify-center font-black text-xs shadow-md">
                                        {{ $isPdf ? 'PDF' : 'PPT' }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-slate-900">{{ $data['ppt_file_name'] ?? 'Berkas Presentasi' }}</h4>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Tersedia untuk Siswa</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">Siswa dapat mengunduh dan membaca slide presentasi ini.</p>
                                    </div>
                                </div>
                                <a href="{{ route('teacher.modules.materi.download-ppt', $module) }}" target="_blank"
                                   class="px-4 py-2 text-xs font-bold text-violet-700 bg-white hover:bg-violet-100 border border-violet-300 rounded-xl shadow-sm transition-all flex items-center gap-1.5 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    Unduh Berkas
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Poin Penting / Rangkuman --}}
                    @if(!empty($data['poin_penting']) && count($data['poin_penting']) > 0)
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h4 class="text-sm font-extrabold text-slate-900 mb-3 flex items-center gap-2">
                                <span>📌</span> Rangkuman Poin Kunci:
                            </h4>
                            <div class="space-y-2.5">
                                @foreach($data['poin_penting'] as $idx => $poin)
                                    @if(!empty(trim($poin)))
                                        <div class="flex items-start gap-3 p-3 rounded-xl bg-amber-50/70 border border-amber-200/80 text-xs text-amber-950">
                                            <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-900 flex items-center justify-center font-bold text-[10px] shrink-0">{{ $idx + 1 }}</span>
                                            <p class="leading-relaxed font-medium">{{ $poin }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-6 sm:px-8 py-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5 font-medium text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Mode Membaca Siswa (Responsif)
                    </span>
                    <span class="text-slate-400 font-medium">Data tersimpan terakhir dari server</span>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
