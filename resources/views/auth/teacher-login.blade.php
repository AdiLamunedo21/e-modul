<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Guru - E-Modul</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-indigo-50 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-indigo-100 overflow-hidden">
        <div class="bg-indigo-600 p-6 text-center">
            <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">👨‍🏫</div>
            <h2 class="text-2xl font-bold text-white">Login Guru</h2>
            <p class="text-indigo-200 text-sm mt-1">E-Module Builder & Penilaian</p>
        </div>
        <div class="p-8">
            <form action="{{ route('login.teacher') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="identity_number" class="block text-sm font-medium text-slate-700 mb-1">NUPTK / NIP</label>
                    <input type="text" name="identity_number" id="identity_number" value="{{ old('identity_number') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none" placeholder="Masukkan NUPTK/NIP Anda">
                    @error('identity_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-500 transition duration-300 shadow-md hover:shadow-lg focus:ring-4 focus:ring-indigo-500/50">
                    Masuk ke Ruang Guru
                </button>
            </form>
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-sm text-slate-500 hover:text-indigo-600 transition">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
