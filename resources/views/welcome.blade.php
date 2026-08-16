<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Modul - SMK Negeri 3 Yogyakarta</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS (CDN for instant preview, usually you'd use Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">E</div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">E-Modul SMK</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-slate-500 hidden sm:inline-block">SMK Negeri 3 Yogyakarta</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow flex items-center justify-center pt-10 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 mb-6 ring-1 ring-inset ring-blue-500/20">
                🚀 Platform Belajar Digital
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 tracking-tight mb-6">
                Masa Depan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Pendidikan Vokasi</span>
            </h1>
            <p class="mt-4 text-lg md:text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                Akses E-Modul interaktif dengan mudah. Belajar terstruktur, sistematis, dan pantau progres capaian kompetensi Anda secara real-time.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto mt-12">
                <!-- Siswa Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 hover:shadow-md transition duration-300 relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">🎓</div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Portal Siswa</h3>
                        <p class="text-sm text-slate-500 mb-6">Akses materi belajar interaktif dan pantau nilai Anda.</p>
                        <a href="{{ route('login.student') }}" class="inline-flex justify-center items-center w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition">
                            Masuk Siswa
                        </a>
                    </div>
                </div>

                <!-- Guru Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 hover:shadow-md transition duration-300 relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-indigo-50/50 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">👨‍🏫</div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Ruang Guru</h3>
                        <p class="text-sm text-slate-500 mb-6">Kelola modul, evaluasi tugas, dan pantau progres kelas.</p>
                        <a href="{{ route('login.teacher') }}" class="inline-flex justify-center items-center w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                            Masuk Guru
                        </a>
                    </div>
                </div>

                <!-- Admin Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 hover:shadow-md transition duration-300 relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-slate-50/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center mx-auto mb-4 text-2xl">⚙️</div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Supervisi</h3>
                        <p class="text-sm text-slate-500 mb-6">Manajemen sistem, master data, dan kontrol kualitas.</p>
                        <a href="{{ route('login.admin') }}" class="inline-flex justify-center items-center w-full rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition">
                            Masuk Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8 text-center text-sm text-slate-500">
        <p>&copy; {{ date('Y') }} E-Modul SMK Negeri 3 Yogyakarta. All rights reserved.</p>
    </footer>

</body>
</html>
