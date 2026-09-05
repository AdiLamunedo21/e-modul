@extends('layouts.teacher.dashboardteacher')
@section('title', $class->full_name . ' — Detail Kelas & Siswa')
@section('page-title', 'Detail Kelas & Siswa')

@section('content')
<div x-data="classDetailPage()" class="space-y-8 pb-12">

    {{-- Flash Alert --}}
    @include('pages.teacher.classes.partials.alerts')

    {{-- ══ 1. HEADER & BREADCRUMB ══ --}}
    @include('pages.teacher.classes.partials.header')

    {{-- ══ 2. RINGKASAN METRIK KELAS KHUSUS GURU ══ --}}
    @include('pages.teacher.classes.partials.stats')

    {{-- ══ 3. TAB KONTEN: DIREKTORI SISWA VS PORTOFOLIO MODUL ══ --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        {{-- Tab Switcher --}}
        @include('pages.teacher.classes.partials.tab-nav')

        {{-- Tab Content 1: Direktori Siswa --}}
        @include('pages.teacher.classes.partials.tab-students')

        {{-- Tab Content 2: Portofolio Modul Guru --}}
        @include('pages.teacher.classes.partials.tab-modules')
    </div>

    {{-- ══ MODAL DIALOGS ══ --}}
    @include('pages.teacher.classes.partials.modal-import')
    @include('pages.teacher.classes.partials.modal-delete')
    @include('pages.teacher.classes.partials.modal-student-summary')

</div>

{{-- ══ SCRIPTS ══ --}}
@include('pages.teacher.classes.partials.scripts')
@endsection
