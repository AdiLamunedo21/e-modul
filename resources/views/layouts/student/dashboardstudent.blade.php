<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Dashboard - E-Modul SMKN 3 Yogyakarta')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Vite (Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Extra Styles -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('layouts.student.sidebar')
        
        <!-- Main Content Wrapper -->
        <div class="flex flex-col flex-1 w-full">
            <!-- Header/Topbar -->
            @include('layouts.student.header')

            <!-- Main Content Area -->
            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto py-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Extra Scripts -->
    @stack('scripts')
</body>
</html>
