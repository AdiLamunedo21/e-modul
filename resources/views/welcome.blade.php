<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Modul - SMK Negeri 3 Yogyakarta</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('lgsmk.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6', // Teal
                            600: '#0d9488',
                            900: '#134e4a',
                        },
                        accent: {
                            500: '#6366f1', // Indigo
                            600: '#4f46e5',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden relative">

    <!-- Background Blobs -->
    <div class="fixed inset-0 w-full h-full z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 -left-4 w-72 h-72 bg-brand-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-accent-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navbar -->
    <nav class="glass sticky top-0 z-50 w-full transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('LGskagata.png') }}" alt="Logo SMKN 3 Yogyakarta" class="w-10 h-10 object-contain drop-shadow-sm" />
                    <div>
                        <span class="font-heading font-bold text-xl tracking-tight text-slate-900 block leading-tight">E-Modul</span>
                        <span class="text-xs font-medium text-slate-500 block leading-tight">SMK N 3 Yogyakarta</span>
                    </div>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#fitur" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">Fitur Utama</a>
                    <a href="#portal" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition">Akses Portal</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="relative z-10">
        <!-- Hero Section -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 md:pt-32 md:pb-32">
            <div class="text-center max-w-4xl mx-auto animate-fade-in-up">
                <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-brand-700 bg-brand-50 border border-brand-200 mb-8 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-brand-500"></span>
                    Platform Pembelajaran Digital Vokasi
                </div>
                <h1 class="font-heading text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8 leading-tight">
                    Transformasi Belajar <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 via-accent-500 to-brand-500">
                        Lebih Interaktif & Terstruktur
                    </span>
                </h1>
                <p class="mt-4 text-lg md:text-xl text-slate-600 mb-12 leading-relaxed font-medium">
                    Sistem Manajemen Konten E-Modul revolusioner untuk SMK. Dirancang dengan pendekatan modular, adaptif, dan berorientasi pada kompetensi siswa.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#portal" class="inline-flex justify-center items-center rounded-2xl bg-slate-900 px-8 py-4 text-base font-bold text-white shadow-xl shadow-slate-900/20 hover:bg-slate-800 hover:-translate-y-1 transition-all duration-300">
                        Mulai Belajar Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#fitur" class="inline-flex justify-center items-center rounded-2xl bg-white px-8 py-4 text-base font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:-translate-y-1 transition-all duration-300">
                        Pelajari Fitur
                    </a>
                </div>
            </div>
        </main>

        <!-- Feature Section -->
        <section id="fitur" class="py-24 bg-white/50 border-y border-slate-200/50 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mb-4">Fitur Unggulan Platform</h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">Dikembangkan khusus untuk menjawab tantangan pembelajaran di sekolah kejuruan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 shadow-inner">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-3">Paginated Learning</h3>
                        <p class="text-slate-600 leading-relaxed">Materi dipecah menjadi halaman-halaman sistematis untuk mencegah disorientasi dan menjaga fokus siswa saat belajar.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 bg-brand-100 rounded-2xl flex items-center justify-center text-brand-600 mb-6 shadow-inner">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-3">Modular Builder</h3>
                        <p class="text-slate-600 leading-relaxed">Guru memiliki kebebasan penuh merakit E-Modul dengan 7 fitur interaktif opsional (Video, Praktik, LKPD, dll).</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-card rounded-3xl p-8 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-6 shadow-inner">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-3">Penilaian Adaptif</h3>
                        <p class="text-slate-600 leading-relaxed">Sistem penilaian cerdas yang otomatis menyesuaikan dengan komponen aktif, dilengkapi ekspor laporan spreadsheet Excel (.xlsx) dinamis.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Portals Section -->
        <section id="portal" class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-slate-900 mb-4">Pilih Gerbang Masuk</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Silakan masuk sesuai dengan peran Anda untuk mulai menggunakan platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Siswa Card -->
                <div class="glass-card rounded-[2rem] p-8 text-center group hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 border-t-4 border-t-blue-500">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-4xl">🎓</span>
                    </div>
                    <h3 class="font-heading text-2xl font-bold text-slate-900 mb-2">Portal Siswa</h3>
                    <p class="text-slate-500 mb-8 min-h-[48px]">Akses materi, kerjakan tugas, dan pantau progres belajarmu.</p>
                    <a href="{{ route('login.student') }}" class="block w-full rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-colors">
                        Masuk sebagai Siswa
                    </a>
                </div>

                <!-- Guru Card -->
                <div class="glass-card rounded-[2rem] p-8 text-center group hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 border-t-4 border-t-brand-500 relative transform md:-translate-y-4">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-brand-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        Kreator Konten
                    </div>
                    <div class="w-20 h-20 bg-brand-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 mt-2">
                        <span class="text-4xl">👨‍🏫</span>
                    </div>
                    <h3 class="font-heading text-2xl font-bold text-slate-900 mb-2">Ruang Guru</h3>
                    <p class="text-slate-500 mb-8 min-h-[48px]">Rancang E-Modul interaktif dan evaluasi capaian kelas.</p>
                    <a href="{{ route('login.teacher') }}" class="block w-full rounded-xl bg-brand-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-500/30 hover:bg-brand-700 transition-colors">
                        Masuk sebagai Guru
                    </a>
                </div>

                <!-- Admin Card -->
                <div class="glass-card rounded-[2rem] p-8 text-center group hover:shadow-2xl hover:shadow-slate-500/10 transition-all duration-300 border-t-4 border-t-slate-700">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-4xl">⚙️</span>
                    </div>
                    <h3 class="font-heading text-2xl font-bold text-slate-900 mb-2">Supervisi</h3>
                    <p class="text-slate-500 mb-8 min-h-[48px]">Kelola data master dan pantau kualitas pembelajaran.</p>
                    <a href="{{ route('login.admin') }}" class="block w-full rounded-xl bg-slate-800 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-500/30 hover:bg-slate-900 transition-colors">
                        Masuk sebagai Admin
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-200/60 bg-white/50 backdrop-blur-md pt-12 pb-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('LGskagata.png') }}" alt="Logo SMKN 3 Yogyakarta" class="w-8 h-8 object-contain drop-shadow-sm" />
                    <span class="font-heading font-bold text-lg text-slate-900">E-Modul SMK</span>
                </div>
                <div class="text-sm font-medium text-slate-500 text-center md:text-left">
                    &copy; {{ date('Y') }} SMK Negeri 3 Yogyakarta. Mengembangkan Pendidikan Vokasi Berbasis Teknologi.
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
