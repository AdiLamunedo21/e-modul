<header class="z-10 py-4 bg-white shadow-sm border-b border-slate-200">
    <div class="container flex items-center justify-between h-full px-6 mx-auto text-blue-600">
        <!-- Mobile hamburger -->
        <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-blue" aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
        </button>
        <!-- Search input -->
        <div class="flex justify-center flex-1 lg:mr-32">
            <div class="relative w-full max-w-xl mr-6 focus-within:text-blue-500">
                <div class="absolute inset-y-0 flex items-center pl-2">
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input class="w-full pl-8 pr-2 text-sm text-slate-700 placeholder-slate-400 bg-slate-100 border-0 rounded-md focus:placeholder-slate-500 focus:bg-white focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-100 py-2 form-input" type="text" placeholder="Cari modul..." aria-label="Search" />
            </div>
        </div>
        <ul class="flex items-center flex-shrink-0 space-x-6">
            <!-- Profile menu -->
            <li class="relative">
                <button class="align-middle rounded-full focus:shadow-outline-blue focus:outline-none text-slate-600 font-medium text-sm flex items-center gap-2" aria-label="Account" aria-haspopup="true">
                    <span>Teacher</span>
                    <img class="object-cover w-8 h-8 rounded-full border border-slate-200" src="https://ui-avatars.com/api/?name=Teacher&background=random" alt="" aria-hidden="true" />
                </button>
            </li>
        </ul>
    </div>
</header>
