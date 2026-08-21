@extends('layouts.teacher.dashboardteacher')

@section('title', 'Editor Daftar Pustaka — ' . $module->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- ══ Breadcrumb Navigation ══ --}}
    <nav class="flex items-center gap-2 text-xs font-medium text-slate-500" aria-label="Breadcrumb">
        <a href="{{ route('teacher.modules.index') }}" class="hover:text-blue-600 transition-colors">
            Modul Ajar
        </a>
        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('teacher.modules.show', $module) }}" class="hover:text-blue-600 transition-colors truncate max-w-[200px]">
            {{ $module->title }}
        </a>
        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-800 font-semibold">Daftar Pustaka</span>
    </nav>

    {{-- ══ Flash Alerts ══ --}}
    @if(session('success'))
    <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800 shadow-sm">
        <p class="font-bold mb-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
            Terdapat {{ $errors->count() }} kesalahan input:
        </p>
        <ul class="list-disc list-inside space-y-1 text-xs text-rose-700">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ══ Header Banner ══ --}}
    <div class="bg-gradient-to-r from-rose-800 via-pink-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-rose-950/20 mb-8 relative overflow-hidden border border-rose-700/40">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-48 h-48 bg-pink-500/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-slate-950/60 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-white shadow-sm">
                    <span class="flex items-center gap-1.5 text-rose-200">
                        <span class="w-5 h-5 rounded-md bg-rose-600 text-white flex items-center justify-center font-black text-[10px]">5</span>
                        <span>Bagian Akhir</span>
                    </span>
                    <span class="text-white/30">•</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-rose-400/20 text-rose-300 border border-rose-400/40 uppercase tracking-wider">
                        Kepustakaan & Rujukan
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">
                    Editor Daftar Pustaka Modul
                </h1>
                <p class="text-slate-200 text-sm leading-relaxed max-w-2xl font-normal">
                    Kelola daftar referensi buku pedoman, modul rujukan, jurnal ilmiah, dan sumber digital terpercaya yang digunakan dalam menyusun e-modul ini.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('teacher.modules.show', $module) }}"
                   class="px-4 py-2.5 rounded-xl bg-slate-900/50 hover:bg-slate-900/80 text-white border border-white/25 hover:border-white/40 text-xs font-bold transition-all flex items-center gap-2 backdrop-blur-sm shadow-sm">
                    ← Kembali ke Detail
                </a>
                <button form="daftar-pustaka-form" type="submit"
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition-all shadow-lg shadow-rose-950/40 flex items-center gap-2 border border-rose-400/30 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- ══ Form Daftar Pustaka ══ --}}
    <form id="daftar-pustaka-form"
          action="{{ route('teacher.modules.daftar-pustaka.update', $module) }}"
          method="POST">
        @csrf
        @method('PATCH')

        {{-- ══ Main Layout Grid (Standard 12 Columns) ══ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- ── Left Side: Form Card (8 Cols) ── --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Card: Daftar Pustaka & Referensi --}}
                <div id="sec-pustaka" class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6 scroll-mt-24">
                    <div class="flex items-start justify-between gap-4 pb-5 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-lg shrink-0 shadow-xs">
                                📚
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-base font-extrabold text-slate-900">Daftar Pustaka & Sumber Rujukan</h2>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                        Rujukan Akademik
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Daftar buku, artikel, jurnal, atau dokumentasi digital sebagai sumber materi pembelajaran.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Repeater Table --}}
                    <div>
                        {{-- Header Kolom Desktop --}}
                        <div class="hidden sm:grid grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 mb-2 px-1">
                            <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Judul Buku / Sumber</span>
                            <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Penulis / Penerbit</span>
                            <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Tahun</span>
                            <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Tautan Web (Opsional)</span>
                            <span class="w-8"></span>
                        </div>

                        @php $pustakaItems = old('daftar_pustaka', $data['daftar_pustaka'] ?? []); @endphp
                        <div id="pustaka-list" class="space-y-3 mb-4">
                            @if(empty($pustakaItems))
                                <div class="repeater-row grid grid-cols-1 sm:grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 items-start p-3 sm:p-0 rounded-2xl bg-slate-50 sm:bg-transparent border sm:border-0 border-slate-200">
                                    <input type="text" name="daftar_pustaka[0][judul]" placeholder="Basis Data SMK/MAK Kelas XI"
                                           class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                    <input type="text" name="daftar_pustaka[0][penulis]" placeholder="Kemendikbudristek / Erlangga"
                                           class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                    <input type="text" name="daftar_pustaka[0][tahun]" placeholder="2023"
                                           class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                    <input type="url" name="daftar_pustaka[0][tautan]" placeholder="https://..."
                                           class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                    <button type="button" onclick="removePustakaRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @else
                                @foreach($pustakaItems as $pi => $pItem)
                                    <div class="repeater-row grid grid-cols-1 sm:grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 items-start p-3 sm:p-0 rounded-2xl bg-slate-50 sm:bg-transparent border sm:border-0 border-slate-200">
                                        <input type="text" name="daftar_pustaka[{{ $pi }}][judul]"
                                               value="{{ is_array($pItem) ? ($pItem['judul'] ?? '') : $pItem }}"
                                               placeholder="Judul Buku / Sumber..."
                                               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                        <input type="text" name="daftar_pustaka[{{ $pi }}][penulis]"
                                               value="{{ is_array($pItem) ? ($pItem['penulis'] ?? '') : '' }}"
                                               placeholder="Penulis / Penerbit..."
                                               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                        <input type="text" name="daftar_pustaka[{{ $pi }}][tahun]"
                                               value="{{ is_array($pItem) ? ($pItem['tahun'] ?? '') : '' }}"
                                               placeholder="Tahun..."
                                               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                        <input type="url" name="daftar_pustaka[{{ $pi }}][tautan]"
                                               value="{{ is_array($pItem) ? ($pItem['tautan'] ?? '') : '' }}"
                                               placeholder="https://..."
                                               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
                                        <button type="button" onclick="removePustakaRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="button" onclick="addPustakaRow()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Tambah Referensi Pustaka
                        </button>
                    </div>
                </div>

                {{-- ══ Bottom Action Bar ══ --}}
                <div class="flex items-center justify-between gap-4 pt-4">
                    <a href="{{ route('teacher.modules.show', $module) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-2xl transition-all shadow-sm">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                        Batal & Kembali
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-8 py-3 text-xs sm:text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-2xl shadow-lg shadow-rose-600/25 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Simpan Daftar Pustaka
                    </button>
                </div>

            </div>

            {{-- ── Right Side: Info & Guidelines Column (4 Cols) ── --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Card 1: Ringkasan Status --}}
                <div class="rounded-3xl bg-white border border-slate-200/80 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-900">Kelengkapan Bagian Akhir</h3>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-100 uppercase tracking-wide">
                            Daftar Pustaka
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <span class="font-medium text-slate-700 flex items-center gap-2">
                                <span>📚</span> Referensi Tersimpan
                            </span>
                            <span class="font-bold text-rose-700">
                                {{ count($pustakaItems ?? []) }} Sumber
                            </span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-md shadow-rose-600/20 flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                {{-- Card 2: Panduan Penulisan Referensi --}}
                <div class="rounded-3xl bg-rose-50/60 border border-rose-100 p-6 space-y-3">
                    <div class="flex items-center gap-2 text-rose-900 font-bold text-xs">
                        <span class="text-base">💡</span>
                        <span>Panduan Penulisan Daftar Pustaka</span>
                    </div>
                    <p class="text-[11px] text-rose-900/80 leading-relaxed">
                        Sertakan seluruh referensi buku teks, modul kurikulum merdeka/K13, dokumentasi resmi, atau video referensi yang digunakan dalam menyusun materi.
                    </p>
                    <ul class="text-[11px] text-rose-900/80 space-y-1.5 list-disc list-inside">
                        <li>Gunakan nama penulis atau institusi penerbit resmi.</li>
                        <li>Cantumkan tahun terbit berkas atau modul.</li>
                        <li>Sertakan tautan web jika sumber berupa dokumentasi atau modul daring.</li>
                    </ul>
                </div>

            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
let pustakaIndex = {{ count($pustakaItems ?? []) > 0 ? count($pustakaItems) : 1 }};

function addPustakaRow() {
    const list = document.getElementById('pustaka-list');
    const row = document.createElement('div');
    row.className = 'repeater-row grid grid-cols-1 sm:grid-cols-[1.5fr_1.2fr_0.7fr_1.2fr_auto] gap-3 items-start p-3 sm:p-0 rounded-2xl bg-slate-50 sm:bg-transparent border sm:border-0 border-slate-200 transition-all';
    row.innerHTML = `
        <input type="text" name="daftar_pustaka[${pustakaIndex}][judul]" placeholder="Judul Buku / Sumber..."
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
        <input type="text" name="daftar_pustaka[${pustakaIndex}][penulis]" placeholder="Penulis / Penerbit..."
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
        <input type="text" name="daftar_pustaka[${pustakaIndex}][tahun]" placeholder="Tahun..."
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
        <input type="url" name="daftar_pustaka[${pustakaIndex}][tautan]" placeholder="https://..."
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder-slate-400 focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 transition-all">
        <button type="button" onclick="removePustakaRow(this)" class="w-8 h-8 mt-1 flex items-center justify-center text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    list.appendChild(row);
    pustakaIndex++;
}

function removePustakaRow(btn) {
    const rows = document.querySelectorAll('#pustaka-list .repeater-row');
    if (rows.length > 1) {
        btn.closest('.repeater-row').remove();
    } else {
        const inputs = rows[0].querySelectorAll('input');
        inputs.forEach(i => i.value = '');
    }
}
</script>
@endpush
@endsection
