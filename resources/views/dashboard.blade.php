<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard {{ $role }} - E-Modul</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen">
    
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">E</div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">Dashboard {{ $role }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-slate-600">Selamat datang, <strong>{{ Auth::user()->name }}</strong></span>
                    <form method="POST" action="{{ route('logout.' . strtolower($role === 'Guru' ? 'teacher' : ($role === 'Siswa' ? 'student' : 'admin'))) }}">
                        @csrf
                        <button type="submit" class="text-sm bg-red-50 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 font-medium transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">🎉</div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Login Berhasil!</h1>
            <p class="text-slate-500">Anda berhasil masuk sebagai <span class="font-semibold text-slate-700">{{ $role }}</span> (ID: {{ Auth::user()->identity_number }}).</p>
            <p class="text-slate-400 mt-4 text-sm">Halaman dashboard fungsional belum diimplementasikan pada versi MVP ini.</p>
        </div>
    </main>

</body>
</html>
