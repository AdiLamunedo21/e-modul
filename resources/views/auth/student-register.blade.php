<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru - E-Modul SMKN 3 Yogyakarta</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('lgsmk.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50 flex items-center justify-center min-h-screen p-4 sm:p-6 py-10">
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-xl border border-blue-100/80 overflow-hidden">
        {{-- Top Header Banner --}}
        <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 p-6 sm:p-8 text-center relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/15 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-3 text-3xl border border-white/20 shadow-inner">
                    🎓
                </div>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">Pendaftaran Siswa Baru</h2>
                <p class="text-blue-100 text-xs sm:text-sm mt-1">Registrasi Akun Belajar E-Modul SMKN 3 Yogyakarta</p>
            </div>
        </div>

        {{-- Form Container --}}
        <div class="p-6 sm:p-8">
            <form action="{{ route('register.student') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nama Lengkap Siswa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('name') border-red-400 bg-red-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none"
                           placeholder="Masukkan nama lengkap Anda">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NISN --}}
                <div>
                    <label for="identity_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nomor Induk Siswa Nasional (NISN) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="identity_number" id="identity_number" value="{{ old('identity_number') }}" required
                           class="w-full px-4 py-2.5 text-sm font-mono bg-slate-50 border @error('identity_number') border-red-400 bg-red-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none"
                           placeholder="Contoh: 0081234567">
                    @error('identity_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode Kelas (Opsional) --}}
                <div class="p-3.5 rounded-2xl bg-blue-50/60 border border-blue-200/80 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="class_code" class="block text-xs font-bold uppercase tracking-wider text-blue-900">
                            Kode Kelas <span class="text-[11px] font-normal text-blue-600 lowercase">(opsional)</span>
                        </label>
                        <span class="text-[10px] font-bold bg-blue-200/80 text-blue-800 px-2 py-0.5 rounded-md">
                            Dari Guru
                        </span>
                    </div>
                    <input type="text" name="class_code" id="class_code" value="{{ old('class_code', request('code')) }}"
                           class="w-full px-4 py-2.5 text-sm font-mono font-bold uppercase tracking-wider bg-white border @error('class_code') border-red-400 bg-red-50/30 @else border-blue-200 @enderror rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none"
                           placeholder="Contoh: KLS-7X89">
                    @error('class_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Jika guru Anda telah membagikan Kode Kelas, masukkan di sini agar langsung terhubung. Anda juga bisa melewatinya dan bergabung nanti dari dashboard.
                    </p>
                </div>

                {{-- Password Grid (2 kolom) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border @error('password') border-red-400 bg-red-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none"
                               placeholder="Min. 6 karakter">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Ulangi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-3.5 rounded-xl transition duration-300 shadow-md shadow-blue-600/30 hover:shadow-lg focus:ring-4 focus:ring-blue-500/50 text-sm">
                        Daftar & Masuk ke E-Modul
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-slate-500">
                    Sudah memiliki akun siswa?
                    <a href="{{ route('login.student') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                        Masuk di Sini
                    </a>
                </p>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 text-center">
                <a href="{{ url('/') }}" class="text-xs font-medium text-slate-400 hover:text-blue-600 transition">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
