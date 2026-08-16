<header class="z-10 py-4 bg-white shadow-sm border-b border-slate-200">
    <div class="container flex items-center justify-between h-full px-6 mx-auto text-emerald-600">
        <!-- Mobile hamburger -->
        <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-emerald" aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>
        <!-- Search input -->
        <div class="flex justify-center flex-1 lg:mr-32">
            <div class="relative w-full max-w-xl mr-6 focus-within:text-emerald-500">
                <!-- No Search Input specifically needed for students on dashboard, maybe just a greeting -->
                <span class="font-medium text-slate-700">Selamat datang, Bagas!</span>
            </div>
        </div>
        <ul class="flex items-center flex-shrink-0 space-x-6">
            <!-- Profile menu -->
            <li class="relative">
                <button class="align-middle rounded-full focus:shadow-outline-emerald focus:outline-none text-slate-600 font-medium text-sm flex items-center gap-2" aria-label="Account" aria-haspopup="true">
                    <span>Bagas (Siswa)</span>
                    <img class="object-cover w-8 h-8 rounded-full border border-slate-200" src="https://ui-avatars.com/api/?name=Bagas&background=random" alt="" aria-hidden="true" />
                </button>
            </li>
        </ul>
    </div>
</header>
