@extends('layouts.admin.dashboardadmin')

@section('title', 'Profil & Pengaturan Keamanan — Admin E-Modul')
@section('page-title', 'Profil Administrator')

@section('content')

{{-- ══ 1. HEADER ══ --}}
<div class="mb-8">
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>Pengaturan Akun & Keamanan</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Admin Kurikulum
                </span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola identitas personal administrator dan perbarui kata sandi akses sistem secara berkala.
            </p>
        </div>
    </div>
</div>

{{-- ══ Flash Alerts ══ --}}
@if(session('profile_success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('profile_success') }}</span>
    </div>
@endif

@if(session('password_success'))
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('password_success') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">
        <div class="flex items-center gap-2 font-bold mb-1">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM12 15.75h.008v.008H12v-.008z"/>
            </svg>
            <span>Terdapat kesalahan pada formulir:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

    {{-- ══ KOLOM KIRI: KARTU IDENTITAS ADMIN ══ --}}
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 text-center overflow-hidden relative">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-indigo-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-indigo-600/30 ring-4 ring-indigo-50 mb-4">
                {{ strtoupper(substr($admin->name ?? 'A', 0, 2)) }}
            </div>
            <h2 class="text-base font-black text-slate-900 tracking-tight leading-snug">{{ $admin->name }}</h2>
            <p class="text-xs text-slate-400 font-mono mt-0.5">NIP: {{ $admin->identity_number }}</p>

            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-around text-xs">
                <div class="text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Wewenang</span>
                    <span class="font-bold text-indigo-600">Full Access</span>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Status Akun</span>
                    <span class="font-bold text-emerald-600 flex items-center justify-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                </div>
            </div>
        </div>

        {{-- Kartu Tips Keamanan --}}
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-sm">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <h3 class="text-xs font-black uppercase tracking-wider text-indigo-200">Tips Keamanan</h3>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
                Gunakan kombinasi minimal 8 karakter dengan campuran huruf besar, huruf kecil, dan angka. Hindari membagikan kata sandi admin kepada pihak yang tidak berwenang.
            </p>
        </div>
    </div>

    {{-- ══ KOLOM KANAN: FORM UPDATE PROFIL & GANTI PASSWORD ══ --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- 1. Form Update Informasi Akun --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Perbarui Informasi Profil</h3>
                    <p class="text-xs text-slate-400">Ubah nama identitas dan NIP akun administrator Anda.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $admin->name) }}"
                               required
                               placeholder="Contoh: Drs. Ahmad Fauzi, M.Pd."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">NIP / Nomor Identitas <span class="text-red-500">*</span></label>
                        <input type="text"
                               name="identity_number"
                               value="{{ old('identity_number', $admin->identity_number) }}"
                               required
                               placeholder="Contoh: 197501011999031001"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 font-mono">
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span>Simpan Perubahan Profil</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- 2. Form Ubah Kata Sandi --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-7">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-5">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Ubah Kata Sandi</h3>
                    <p class="text-xs text-slate-400">Verifikasi kata sandi lama Anda sebelum menyimpan kata sandi baru.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kata Sandi Saat Ini <span class="text-red-500">*</span></label>
                        <input type="password"
                               name="current_password"
                               required
                               placeholder="Masukkan kata sandi aktif Anda"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kata Sandi Baru <span class="text-red-500">*</span></label>
                            <input type="password"
                                   name="password"
                                   required
                                   minlength="6"
                                   placeholder="Minimal 6 karakter"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ulangi Kata Sandi Baru <span class="text-red-500">*</span></label>
                            <input type="password"
                                   name="password_confirmation"
                                   required
                                   minlength="6"
                                   placeholder="Ketik ulang kata sandi baru"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800">
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md shadow-amber-600/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

@endsection
