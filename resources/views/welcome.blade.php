<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Modul Interaktif - SMK Negeri 3 Yogyakarta</title>
    <meta name="description" content="Platform E-Modul Pembelajaran Digital Interaktif SMK Negeri 3 Yogyakarta. Pembelajaran terstruktur, simulator praktik, LKPD, hingga Job Sheet industri.">
    <meta name="author" content="SMK Negeri 3 Yogyakarta">
    <link rel="icon" type="image/x-icon" href="{{ asset('lgsmk.ico') }}">
    
    <!-- Fonts: Outfit (Heading) & Plus Jakarta Sans (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Vite Compiled Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', system-ui, -apple-system, sans-serif;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.08);
        }
        .hero-pattern {
            background-image: radial-gradient(rgba(13, 148, 136, 0.12) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        /* Native details disclosure styling */
        details[name="faq"] summary::-webkit-details-marker {
            display: none;
        }
        details[name="faq"] summary {
            list-style: none;
        }
        details[name="faq"][open] summary svg {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden selection:bg-teal-500 selection:text-white relative" x-data="{ mobileMenuOpen: false }">

    <!-- Ambient Glowing Gradient Orbs -->
    <div class="fixed inset-0 w-full h-full z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-20 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl animate-blob"></div>
        <div class="absolute top-1/4 -right-20 w-[28rem] h-[28rem] bg-indigo-400/15 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute top-2/3 left-10 w-96 h-96 bg-emerald-400/15 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation Header -->
    <header class="glass-nav sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center gap-4">
                
                <!-- School & Platform Brand -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-teal-500 rounded-xl p-1">
                    <img src="{{ asset('LGskagata.png') }}" 
                         alt="Logo SMK Negeri 3 Yogyakarta" 
                         class="w-11 h-11 object-contain drop-shadow-md group-hover:scale-105 transition-transform" />
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-black text-xl tracking-tight text-slate-900 group-hover:text-teal-600 transition-colors">E-MODUL</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-teal-100 text-teal-800 border border-teal-200 uppercase tracking-wide">Vokasi</span>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 block leading-tight">SMK Negeri 3 Yogyakarta</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600" aria-label="Navigasi Utama">
                    <a href="#beranda" class="hover:text-teal-600 transition-colors">Beranda</a>
                    <a href="#alur-belajar" class="hover:text-teal-600 transition-colors">Alur Belajar</a>
                    <a href="#fitur" class="hover:text-teal-600 transition-colors">Fitur Vokasi</a>
                    <a href="#faq" class="hover:text-teal-600 transition-colors">FAQ</a>
                </nav>

                <!-- Auth / Quick Action Buttons -->
                <div class="hidden sm:flex items-center gap-3">
                    @if(Auth::guard('student')->check())
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-teal-600/20 transition-all hover:-translate-y-0.5">
                            <span>🎓 Dashboard Siswa</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @elseif(Auth::guard('teacher')->check())
                        <a href="{{ route('teacher.dashboard') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-indigo-600/20 transition-all hover:-translate-y-0.5">
                            <span>👨‍🏫 Dashboard Guru</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @elseif(Auth::guard('admin')->check())
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs sm:text-sm shadow-md shadow-slate-800/20 transition-all hover:-translate-y-0.5">
                            <span>⚙️ Panel Admin</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @else
                        <a href="#portal" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-teal-600/25 transition-all hover:-translate-y-0.5">
                            <span>Masuk Portal</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none"
                        aria-label="Toggle menu navigasi">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Dropdown Nav -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak
             class="lg:hidden bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 pt-3 pb-6 space-y-3">
            <a @click="mobileMenuOpen = false" href="#beranda" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-teal-50 hover:text-teal-700">Beranda</a>
            <a @click="mobileMenuOpen = false" href="#alur-belajar" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-teal-50 hover:text-teal-700">Alur Belajar</a>
            <a @click="mobileMenuOpen = false" href="#fitur" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-teal-50 hover:text-teal-700">Fitur Vokasi</a>
            <a @click="mobileMenuOpen = false" href="#faq" class="block px-3 py-2 rounded-xl text-sm font-bold text-slate-700 hover:bg-teal-50 hover:text-teal-700">FAQ</a>
            <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
                <a @click="mobileMenuOpen = false" href="#portal" class="text-center w-full py-2.5 rounded-xl bg-teal-600 text-white font-bold text-sm shadow-md shadow-teal-600/20">Buka Portal Masuk</a>
            </div>
        </div>
    </header>

    <div class="relative z-10 hero-pattern">
        
        <!-- ═══ Hero Section (Clean, Focused & Centered) ═══ -->
        <main id="beranda" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-24 lg:pt-24 lg:pb-32">
            <div class="max-w-4xl mx-auto space-y-6 sm:space-y-8 text-center animate-fade-in-up">
                
                <!-- Tagline Pill -->
                <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs sm:text-sm font-bold text-teal-800 bg-teal-100/70 border border-teal-300 shadow-2xs">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-teal-500 animate-pulse"></span>
                    <span>SMK Negeri 3 Yogyakarta</span>
                </div>

                <!-- Main H1 Heading -->
                <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                    Transformasi Belajar <br class="hidden sm:inline" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-emerald-600 to-indigo-600">
                        Interaktif & Terstruktur
                    </span>
                </h1>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 pt-2">
                    <a href="#portal" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white font-black text-sm sm:text-base shadow-xl shadow-teal-600/25 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                        <span>Mulai Belajar Sekarang</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>

                    <a href="#alur-belajar" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm sm:text-base border border-slate-200 shadow-2xs hover:-translate-y-1 transition-all duration-300">
                        <span>Alur 5 Fase Belajar</span>
                    </a>

                    <a href="#fitur" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm sm:text-base border border-slate-200 shadow-2xs hover:-translate-y-1 transition-all duration-300">
                        <span>Fitur Platform</span>
                    </a>
                </div>
            </div>
        </main>

        <!-- ═══ Alur Belajar Terstruktur (Learning Journey) ═══ -->
        <section id="alur-belajar" class="py-24 bg-white/70 border-t border-slate-200/70 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-teal-600 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">
                        Metodologi Belajar Vokasi
                    </span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Alur 5 Fase Pembelajaran Berkesinambungan
                    </h2>
                    <p class="text-base text-slate-600 font-normal">
                        Materi tidak sekadar dibaca, melainkan dipelajari secara berurutan melalui tahapan interaktif untuk menjamin ketercapaian kompetensi kerja.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    
                    <!-- Fase 1 -->
                    <div class="glass-card rounded-3xl p-6 space-y-4 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-teal-500">
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center font-heading font-black text-lg shadow-inner">
                            01
                        </div>
                        <h3 class="font-heading text-lg font-bold text-slate-900">Orientasi & Diagnostik</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Pengenalan Capaian Pembelajaran, Peta Konsep, Glosarium, dan Pre-Test diagnostik bertimer untuk mengukur pemahaman awal.
                        </p>
                        <div class="pt-2 text-[11px] font-bold text-teal-700 flex items-center gap-1.5">
                            <span>⏱️ Pre-Test Berwaktu</span>
                        </div>
                    </div>

                    <!-- Fase 2 -->
                    <div class="glass-card rounded-3xl p-6 space-y-4 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-emerald-500">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-heading font-black text-lg shadow-inner">
                            02
                        </div>
                        <h3 class="font-heading text-lg font-bold text-slate-900">Materi & Video Refleksi</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Pendalaman teori bertahap (paginated) bebas distraksi serta integrasi video pembelajaran dengan kolom ringkasan mandiri.
                        </p>
                        <div class="pt-2 text-[11px] font-bold text-emerald-700 flex items-center gap-1.5">
                            <span>📖 Paginated Reading</span>
                        </div>
                    </div>

                    <!-- Fase 3 -->
                    <div class="glass-card rounded-3xl p-6 space-y-4 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-blue-500">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-heading font-black text-lg shadow-inner">
                            03
                        </div>
                        <h3 class="font-heading text-lg font-bold text-slate-900">Praktik & Simulasi</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Uji coba langsung pada media embed simulator interaktif (Wokwi, Tinkercad, dll) serta unggah dokumentasi bukti kerja.
                        </p>
                        <div class="pt-2 text-[11px] font-bold text-blue-700 flex items-center gap-1.5">
                            <span>🔌 Praktikum Interaktif</span>
                        </div>
                    </div>

                    <!-- Fase 4 -->
                    <div class="glass-card rounded-3xl p-6 space-y-4 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-indigo-500">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-heading font-black text-lg shadow-inner">
                            04
                        </div>
                        <h3 class="font-heading text-lg font-bold text-slate-900">LKPD & Job Sheet</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Pengerjaan Lembar Kerja Peserta Didik dan Job Sheet bengkel terstandar industri dengan pengumpulan laporan terstruktur.
                        </p>
                        <div class="pt-2 text-[11px] font-bold text-indigo-700 flex items-center gap-1.5">
                            <span>📋 Standar Industri</span>
                        </div>
                    </div>

                    <!-- Fase 5 -->
                    <div class="glass-card rounded-3xl p-6 space-y-4 hover:-translate-y-2 transition-all duration-300 border-t-4 border-t-rose-500">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-heading font-black text-lg shadow-inner">
                            05
                        </div>
                        <h3 class="font-heading text-lg font-bold text-slate-900">Evaluasi & Post-Test</h3>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            Ujian evaluasi Post-test sumatif berstandar KKTP untuk memvalidasi ketuntasan kompetensi akhir peserta didik.
                        </p>
                        <div class="pt-2 text-[11px] font-bold text-rose-700 flex items-center gap-1.5">
                            <span>🎯 Target KKM Tuntas</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ═══ Fitur Unggulan Platform ═══ -->
        <section id="fitur" class="py-24 bg-slate-50/80 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                    <span class="text-xs font-black uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">
                        Ekosistem Lengkap
                    </span>
                    <h2 class="font-heading text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Fitur Andal untuk Guru & Peserta Didik
                    </h2>
                    <p class="text-base text-slate-600 font-normal">
                        Dirancang fleksibel memenuhi kebutuhan RPP Merdeka Belajar dan standar kompetensi kejuruan SMK Negeri 3 Yogyakarta.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Feature 1 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center mb-6 text-2xl shadow-inner group-hover:scale-110 transition-transform">
                            🧩
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">Modular Component Builder</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-normal">
                            Guru bebas merakit modul dengan fitur opsional (Materi, Video, Simulator Embed, Job Sheet, LKPD, Pre-Test & Post-Test) menggunakan sakelar toggle 1-klik AJAX.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mb-6 text-2xl shadow-inner group-hover:scale-110 transition-transform">
                            🎬
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">Video Interaktif & Refleksi</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-normal">
                            Penyampaian materi audio visual terintegrasi dengan pemutar video dan formulir ringkasan mandiri siswa yang dinilai langsung oleh guru pengampu.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 text-2xl shadow-inner group-hover:scale-110 transition-transform">
                            🔌
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">Simulasi Praktik & Embed</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-normal">
                            Integrasi simulator eksternal (Wokwi, Tinkercad, GeoGebra, dll.) langsung di dalam modul dengan fitur pengumpulan bukti kerja siswa.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 text-2xl shadow-inner group-hover:scale-110 transition-transform">
                            📊
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">Penilaian & Ekspor Excel (.xlsx)</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-normal">
                            Sistem menghitung otomatis pembobotan nilai pre-test, post-test, review video, tugas LKPD, dan job sheet dengan opsi unduh rekap nilai instan ke Microsoft Excel.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6 text-2xl shadow-inner group-hover:scale-110 transition-transform">
                            📱
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">Desain Responsif Desktop & Mobile</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-normal">
                            Pengalaman belajar optimal di layar smartphone siswa berkat bilah navigasi dock bawah khusus mobile serta tampilan baca yang lapang tanpa distraksi.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6 text-2xl shadow-inner group-hover:scale-110 transition-transform">
                            📚
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2">Module Library Kolaboratif</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-normal">
                            Guru dapat membagikan modul ajar terbaik ke perpustakaan modul bersama antar mata pelajaran untuk saling memperkaya konten pembelajaran sekolah.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- ═══ Portals Section (Pilih Gerbang Masuk) ═══ -->
        <section id="portal" class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-black uppercase tracking-wider text-teal-600 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">
                    Akses Sistem
                </span>
                <h2 class="font-heading text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Pilih Gerbang Masuk Akun Anda
                </h2>
                <p class="text-base text-slate-600 font-normal">
                    Silakan masuk sesuai peran dan hak akses Anda di SMK Negeri 3 Yogyakarta.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto items-stretch">
                
                <!-- 1. Siswa Portal Card -->
                <div class="glass-card rounded-[2.5rem] p-8 text-center flex flex-col justify-between group hover:shadow-2xl hover:shadow-teal-500/15 transition-all duration-300 border-t-4 border-t-teal-500 relative">
                    <div>
                        <div class="w-20 h-20 bg-teal-50 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                            <span class="text-4xl">🎓</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-teal-100 text-teal-800 uppercase tracking-wider inline-block mb-3">Peserta Didik</span>
                        <h3 class="font-heading text-2xl font-black text-slate-900 mb-2">Portal Siswa</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mb-8 leading-relaxed font-normal">
                            Pelajari materi kejuruan, kerjakan praktikum simulator, kirim tugas LKPD, dan pantau kartu capaian modulmu.
                        </p>
                    </div>
                    <div class="space-y-3 w-full">
                        <a href="{{ route('login.student') }}" 
                           class="block w-full py-3.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-lg shadow-teal-500/30 transition-all hover:scale-102">
                            Masuk sebagai Siswa
                        </a>
                        <a href="{{ route('register.student') }}" 
                           class="block w-full py-2.5 rounded-xl bg-teal-50 hover:bg-teal-100 border border-teal-200 text-teal-800 font-bold text-xs transition">
                            Daftar Akun Siswa Baru
                        </a>
                    </div>
                </div>

                <!-- 2. Guru Portal Card (Highlighted Center) -->
                <div class="glass-card rounded-[2.5rem] p-8 text-center flex flex-col justify-between group hover:shadow-2xl hover:shadow-indigo-500/20 transition-all duration-300 border-2 border-indigo-400 relative md:-translate-y-4 bg-gradient-to-b from-white via-indigo-50/20 to-white">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-indigo-600 to-teal-600 text-white text-[11px] font-black uppercase tracking-widest px-4 py-1 rounded-full shadow-md">
                        Pendidik & Kreator
                    </div>
                    <div class="pt-2">
                        <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                            <span class="text-4xl">👨‍🏫</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-indigo-100 text-indigo-800 uppercase tracking-wider inline-block mb-3">Guru Pengampu</span>
                        <h3 class="font-heading text-2xl font-black text-slate-900 mb-2">Ruang Guru</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mb-8 leading-relaxed font-normal">
                            Rancang e-modul interaktif, kelola kelas belajar, pantau progres rombel, dan unduh laporan nilai Excel.
                        </p>
                    </div>
                    <div class="space-y-3 w-full">
                        <a href="{{ route('login.teacher') }}" 
                           class="block w-full py-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-xl shadow-indigo-600/30 transition-all hover:scale-102">
                            Masuk sebagai Guru
                        </a>
                    </div>
                </div>

                <!-- 3. Admin Portal Card -->
                <div class="glass-card rounded-[2.5rem] p-8 text-center flex flex-col justify-between group hover:shadow-2xl hover:shadow-slate-500/15 transition-all duration-300 border-t-4 border-t-slate-700 relative">
                    <div>
                        <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-inner">
                            <span class="text-4xl">⚙️</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-slate-200 text-slate-800 uppercase tracking-wider inline-block mb-3">Administrator</span>
                        <h3 class="font-heading text-2xl font-black text-slate-900 mb-2">Supervisi Sistem</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mb-8 leading-relaxed font-normal">
                            Kelola data induk guru, siswa, kelas, konsentrasi keahlian/jurusan, kenaikan kelas, dan profil institusi sekolah.
                        </p>
                    </div>
                    <div class="space-y-3 w-full">
                        <a href="{{ route('login.admin') }}" 
                           class="block w-full py-3.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm shadow-lg shadow-slate-800/30 transition-all hover:scale-102">
                            Masuk sebagai Admin
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <!-- ═══ FAQ Section (Menggunakan Native Modern Details) ═══ -->
        <section id="faq" class="py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 space-y-2">
                <span class="text-xs font-black uppercase tracking-wider text-teal-600 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">
                    Bantuan & FAQ
                </span>
                <h2 class="font-heading text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Pertanyaan Seputar E-Modul Skagata
                </h2>
                <p class="text-sm text-slate-500 font-normal">
                    Informasi penting penggunaan platform bagi peserta didik dan bapak/ibu guru.
                </p>
            </div>

            <div class="space-y-4">
                
                <details name="faq" class="glass-card rounded-2xl p-5 border border-slate-200/80 transition-all duration-200 group">
                    <summary class="flex justify-between items-center cursor-pointer font-bold text-slate-800 text-sm sm:text-base select-none">
                        <span>Bagaimana cara siswa baru mulai belajar di E-Modul?</span>
                        <span class="w-8 h-8 rounded-xl bg-slate-100 group-open:bg-teal-100 group-open:text-teal-700 flex items-center justify-center shrink-0 transition">
                            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Siswa cukup mendaftarkan akun melalui menu <strong>Daftar Akun Siswa Baru</strong> dengan memilih rombel kelas yang sesuai. Setelah masuk, modul yang diaktifkan oleh guru pengampu akan langsung muncul pada tab <em>Sedang Dikerjakan</em> atau <em>Semua Modul</em>.
                    </p>
                </details>

                <details name="faq" class="glass-card rounded-2xl p-5 border border-slate-200/80 transition-all duration-200 group">
                    <summary class="flex justify-between items-center cursor-pointer font-bold text-slate-800 text-sm sm:text-base select-none">
                        <span>Bagaimana cara bapak/ibu guru memantau dan mengevaluasi nilai siswa?</span>
                        <span class="w-8 h-8 rounded-xl bg-slate-100 group-open:bg-teal-100 group-open:text-teal-700 flex items-center justify-center shrink-0 transition">
                            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Guru memiliki akses pusat penilaian (*Grading Center*) dengan matriks penilaian otomatis per komponen aktif (Pre-test, Video, Embed, LKPD, Job Sheet, Post-test) serta fitur ekspor laporan rekapitulasi nilai lengkap ke format spreadsheet Microsoft Excel (.xlsx).
                    </p>
                </details>

                <details name="faq" class="glass-card rounded-2xl p-5 border border-slate-200/80 transition-all duration-200 group">
                    <summary class="flex justify-between items-center cursor-pointer font-bold text-slate-800 text-sm sm:text-base select-none">
                        <span>Apakah hasil pengerjaan simulator praktikum dan LKPD tersimpan?</span>
                        <span class="w-8 h-8 rounded-xl bg-slate-100 group-open:bg-teal-100 group-open:text-teal-700 flex items-center justify-center shrink-0 transition">
                            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Ya. Siswa dapat mengunggah bukti tangkapan layar (screenshot) simulasi atau file laporan LKPD/Job Sheet secara langsung ke platform. Guru dapat memeriksa dan memberikan penilaian langsung di dashboard penilaian kelas.
                    </p>
                </details>

                <details name="faq" class="glass-card rounded-2xl p-5 border border-slate-200/80 transition-all duration-200 group">
                    <summary class="flex justify-between items-center cursor-pointer font-bold text-slate-800 text-sm sm:text-base select-none">
                        <span>Apa fungsi Pre-test Diagnostik dan Post-test Sumatif?</span>
                        <span class="w-8 h-8 rounded-xl bg-slate-100 group-open:bg-teal-100 group-open:text-teal-700 flex items-center justify-center shrink-0 transition">
                            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        <strong>Pre-test</strong> digunakan untuk memetakan kesiapan dan pengetahuan awal siswa sebelum mempelajari modul baru. Sedangkan <strong>Post-test</strong> menguji pencapaian ketuntasan kompetensi akhir siswa setelah menyelesaikan seluruh rangkaian materi dan tugas praktikum.
                    </p>
                </details>

            </div>
        </section>

    </div>

    <!-- ═══ Footer ═══ -->
    <footer class="relative z-10 border-t border-slate-200 bg-white/90 backdrop-blur-md pt-16 pb-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-12 border-b border-slate-100">
                
                <!-- School Info & Logo -->
                <div class="md:col-span-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('LGskagata.png') }}" alt="Logo SMKN 3 Yogyakarta" class="w-10 h-10 object-contain drop-shadow-sm" />
                        <div>
                            <span class="font-heading font-black text-xl text-slate-900 block leading-tight">E-MODUL SKAGATA</span>
                            <span class="text-xs font-semibold text-slate-500 block">SMK Negeri 3 Yogyakarta</span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 max-w-md leading-relaxed font-normal">
                        Platform Manajemen Pembelajaran Digital Vokasi yang mengintegrasikan teori, simulasi praktik industri, dan evaluasi berbasis kompetensi.
                    </p>
                    <p class="text-xs text-slate-400">
                        📍 Jl. R.W. Monginsidi No. 2, Jetis, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55233
                    </p>
                </div>

                <!-- Tautan Cepat -->
                <div class="md:col-span-3 space-y-3">
                    <h4 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider">Tautan Pintas</h4>
                    <ul class="space-y-2 text-xs font-semibold text-slate-500">
                        <li><a href="#beranda" class="hover:text-teal-600 transition">Beranda</a></li>
                        <li><a href="#alur-belajar" class="hover:text-teal-600 transition">Alur Belajar Siswa</a></li>
                        <li><a href="#fitur" class="hover:text-teal-600 transition">Fitur Unggulan</a></li>
                        <li><a href="#portal" class="hover:text-teal-600 transition">Pintu Masuk Portal</a></li>
                    </ul>
                </div>

                <!-- Portal Masuk -->
                <div class="md:col-span-3 space-y-3">
                    <h4 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider">Akses Portal</h4>
                    <ul class="space-y-2 text-xs font-semibold text-slate-500">
                        <li><a href="{{ route('login.student') }}" class="hover:text-teal-600 transition">🎓 Portal Peserta Didik</a></li>
                        <li><a href="{{ route('register.student') }}" class="hover:text-teal-600 transition">📝 Registrasi Siswa Baru</a></li>
                        <li><a href="{{ route('login.teacher') }}" class="hover:text-indigo-600 transition">👨‍🏫 Ruang Guru Pengampu</a></li>
                        <li><a href="{{ route('login.admin') }}" class="hover:text-slate-900 transition">⚙️ Supervisi Administrator</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs font-medium text-slate-400">
                <div>
                    &copy; {{ date('Y') }} <strong>SMK Negeri 3 Yogyakarta</strong>. Seluruh hak cipta dilindungi.
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Sistem Aktif & Siap Digunakan</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
