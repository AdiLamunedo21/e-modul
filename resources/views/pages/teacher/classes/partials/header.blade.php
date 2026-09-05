{{-- ══ 1. HEADER & BREADCRUMB ══ --}}
@php
    $teacherName = auth('teacher')->user()?->name ?? ($class->teachers->first()?->name ?? 'Guru Pengampu');
    $majorName = $class->major ? $class->major->name : $class->major_name;
    $registerUrl = route('register.student') . '?code=' . $class->code;

    $invitationMessage = "*UNDANGAN BERGABUNG KELAS E-MODUL*\n"
        . "SMK Negeri 3 Yogyakarta\n\n"
        . "Halo siswa-siswi *{$class->full_name}*,\n"
        . "Silakan bergabung ke kelas pembelajaran kita di platform E-Modul menggunakan kode kelas berikut:\n\n"
        . "🔑 *Kode Kelas:* {$class->code}\n"
        . "👨‍🏫 *Guru Pengampu:* {$teacherName}\n"
        . "📚 *Rombel / Jurusan:* {$majorName}\n\n"
        . "🔗 *Link Pendaftaran Siswa (Kode Otomatis Terpasang):*\n"
        . "{$registerUrl}\n\n"
        . "💡 *Petunjuk:*\n"
        . "• *Siswa Baru:* Klik link di atas untuk mendaftar. Kode kelas sudah otomatis terisi.\n"
        . "• *Sudah Punya Akun:* Masuk ke dashboard siswa, klik 'Gabung Kelas', lalu masukkan kode: *{$class->code}*\n\n"
        . "Selamat belajar dan sukses selalu!";

    $waShareUrl = 'https://api.whatsapp.com/send?text=' . rawurlencode($invitationMessage);
@endphp

<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-800 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl shadow-blue-950/20 border border-blue-700/40 mb-8">
    {{-- Glow Elements --}}
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute right-1/3 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 space-y-4">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs font-semibold text-blue-200/90">
            <a href="{{ route('teacher.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
            <span class="text-blue-300/40">/</span>
            <a href="{{ route('teacher.classes.index') }}" class="hover:text-white transition-colors">Build Kelas</a>
            <span class="text-blue-300/40">/</span>
            <span class="text-white font-bold">{{ $class->full_name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm flex-wrap">
                    <span class="flex items-center gap-1.5 text-blue-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Kelas {{ $class->grade }} &bull; Rombel {{ $class->section }}</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-blue-400/20 text-blue-300 border border-blue-400/40 uppercase tracking-wider">
                        {{ $class->major ? $class->major->code : $class->major_name }}
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="text-[11px] text-slate-300 font-medium">SMK Negeri 3 Yogyakarta</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    {{ $class->full_name }}
                </h1>
                <p class="text-slate-200 text-sm max-w-2xl leading-relaxed font-normal">
                    {{ $class->major ? $class->major->name : $class->major_name }} &bull; Kelola data siswa, portofolio modul, impor modul dari kelas lain, dan rekapitulasi nilai akademik.
                </p>

                {{-- ══ KODE KELAS & SHARE SISWA DENGAN WHATSAPP & COPY TOOL ══ --}}
                <div class="pt-2 flex flex-wrap items-center gap-3"
                     x-data="{
                         copiedCode: false,
                         classCode: '{{ $class->code }}',
                         copyCode() {
                             const setFeedback = () => {
                                 this.copiedCode = true;
                                 setTimeout(() => this.copiedCode = false, 2500);
                             };

                             if (navigator.clipboard && window.isSecureContext) {
                                 navigator.clipboard.writeText(this.classCode).then(setFeedback).catch(() => {
                                     this.fallbackCopy(this.classCode);
                                     setFeedback();
                                 });
                             } else {
                                 this.fallbackCopy(this.classCode);
                                 setFeedback();
                             }
                         },
                         fallbackCopy(text) {
                             const ta = document.createElement('textarea');
                             ta.value = text;
                             ta.style.position = 'fixed';
                             ta.style.left = '-9999px';
                             ta.style.top = '-9999px';
                             ta.setAttribute('readonly', '');
                             document.body.appendChild(ta);
                             ta.select();
                             try {
                                 document.execCommand('copy');
                             } catch (e) {
                                 console.error('Fallback copy error:', e);
                             }
                             document.body.removeChild(ta);
                         }
                     }">
                    
                    {{-- 1. Pill Kode Kelas dengan Tombol Salin Cepat --}}
                    <div class="inline-flex items-center gap-3 bg-slate-950/60 border border-white/20 px-4 py-2 rounded-2xl backdrop-blur-md shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-blue-200">KODE KELAS:</span>
                            <span class="font-mono text-base font-black text-yellow-300 tracking-widest select-all">{{ $class->code }}</span>
                        </div>
                        <div class="h-4 w-px bg-white/20"></div>
                        <button type="button"
                                @click="copyCode()"
                                class="inline-flex items-center gap-1.5 text-xs font-extrabold text-white hover:text-blue-300 transition-colors cursor-pointer"
                                title="Salin Kode Kelas Saja">
                            <svg x-show="!copiedCode" class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/>
                            </svg>
                            <svg x-show="copiedCode" x-cloak class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            <span x-text="copiedCode ? 'Kode Tersalin!' : 'Salin Kode'"></span>
                        </button>
                    </div>

                    {{-- 2. Tombol Langsung: Bagikan ke WhatsApp (Buka WA / Web) --}}
                    <a href="{{ $waShareUrl }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-500 border border-emerald-400/40 text-white text-xs font-black transition-all flex items-center gap-2 backdrop-blur-md shadow-lg shadow-emerald-950/40 hover:scale-[1.02] cursor-pointer"
                       title="Buka WhatsApp untuk bagikan pesan undangan ke kontak atau grup kelas">
                        <svg class="w-4 h-4 fill-current text-white shrink-0" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <span>Bagikan ke WhatsApp</span>
                    </a>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('teacher.classes.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                    ← Kembali ke Build Kelas
                </a>

                {{-- Tombol Import Modul --}}
                <button @click="importModalOpen = true"
                        type="button"
                        class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-lg shadow-indigo-950/40 flex items-center gap-2 border border-indigo-400/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Import Modul Lain</span>
                </button>

                {{-- Tombol Hapus Kelas (Purge) --}}
                <button @click="deleteModalOpen = true"
                        type="button"
                        class="px-4 py-2.5 rounded-xl bg-rose-600/80 hover:bg-rose-600 text-white text-xs font-bold transition-all shadow-lg shadow-rose-950/40 flex items-center gap-2 border border-rose-400/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>Hapus Kelas</span>
                </button>
            </div>
        </div>
    </div>
</div>
