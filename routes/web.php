<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Teacher\ModuleManagerController;
use App\Http\Controllers\Teacher\InformasiUmumController;
use App\Http\Controllers\Teacher\PreTestController;
use App\Http\Controllers\Teacher\MateriController;
use App\Http\Controllers\Teacher\VideoController;
use App\Http\Controllers\Teacher\EmbedController;
use App\Http\Controllers\Teacher\JobSheetController;
use App\Http\Controllers\Teacher\LkpdController;
use App\Http\Controllers\Teacher\PostTestController;
use App\Http\Controllers\Teacher\GradingController;

Route::get('/', function () {
    return view('welcome');
});

// ─── Admin Auth ────────────────────────────────────────────────────────────
Route::get('/login/admin',  [AuthController::class, 'showAdminLogin'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'adminLogin']);
Route::post('/logout/admin', [AuthController::class, 'adminLogout'])->name('logout.admin');

// ─── Teacher Auth ──────────────────────────────────────────────────────────
Route::get('/login/teacher',  [AuthController::class, 'showTeacherLogin'])->name('login.teacher');
Route::post('/login/teacher', [AuthController::class, 'teacherLogin']);
Route::post('/logout/teacher', [AuthController::class, 'teacherLogout'])->name('logout.teacher');

// ─── Student Auth ──────────────────────────────────────────────────────────
Route::get('/login/student',  [AuthController::class, 'showStudentLogin'])->name('login.student');
Route::post('/login/student', [AuthController::class, 'studentLogin']);
Route::post('/logout/student', [AuthController::class, 'studentLogout'])->name('logout.student');

// ─── Admin Protected ───────────────────────────────────────────────────────
Route::middleware('auth:admin')->prefix('admin')->name('dashboard.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.admin.dashboard'))->name('admin');
});

// ─── Teacher Protected ─────────────────────────────────────────────────────
Route::middleware('auth:teacher')->prefix('teacher')->name('teacher.')->group(function () {

    // Dashboard utama
    Route::get('/dashboard', fn () => view('pages.teacher.dashboard'))->name('dashboard');

    // Manajer Modul (CRUD)
    Route::get('/modules',                      [ModuleManagerController::class, 'index'])->name('modules.index');
    Route::get('/modules/create',               [ModuleManagerController::class, 'create'])->name('modules.create');
    Route::post('/modules',                     [ModuleManagerController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}',             [ModuleManagerController::class, 'show'])->name('modules.show');
    Route::patch('/modules/{module}/status',    [ModuleManagerController::class, 'updateStatus'])->name('modules.status');
    Route::delete('/modules/{module}',          [ModuleManagerController::class, 'destroy'])->name('modules.destroy');

    // Informasi Umum Editor & Toggle
    Route::get('/modules/{module}/informasi-umum',                  [InformasiUmumController::class, 'edit'])->name('modules.informasi-umum.edit');
    Route::patch('/modules/{module}/informasi-umum',                 [InformasiUmumController::class, 'update'])->name('modules.informasi-umum.update');
    Route::post('/modules/{module}/informasi-umum/toggle/{component}', [InformasiUmumController::class, 'toggle'])->name('modules.informasi-umum.toggle');

    // Komponen Inti: 1. Pre-test Quiz Builder
    Route::get('/modules/{module}/pre-test',        [PreTestController::class, 'edit'])->name('modules.pre-test.edit');
    Route::patch('/modules/{module}/pre-test',       [PreTestController::class, 'update'])->name('modules.pre-test.update');
    Route::post('/modules/{module}/pre-test/toggle',  [PreTestController::class, 'toggle'])->name('modules.pre-test.toggle');
    Route::get('/modules/{module}/pre-test/preview',  [PreTestController::class, 'preview'])->name('modules.pre-test.preview');

    // Komponen Inti: 2. Materi & PPT
    Route::get('/modules/{module}/materi',              [MateriController::class, 'edit'])->name('modules.materi.edit');
    Route::patch('/modules/{module}/materi',             [MateriController::class, 'update'])->name('modules.materi.update');
    Route::post('/modules/{module}/materi/toggle',       [MateriController::class, 'toggle'])->name('modules.materi.toggle');
    Route::get('/modules/{module}/materi/preview',       [MateriController::class, 'preview'])->name('modules.materi.preview');
    Route::get('/modules/{module}/materi/download-ppt',   [MateriController::class, 'downloadPpt'])->name('modules.materi.download-ppt');
    Route::post('/modules/{module}/materi/upload-image', [MateriController::class, 'uploadImage'])->name('modules.materi.upload-image');

    // Komponen Inti: 3. Video YouTube & Ringkasan
    Route::get('/modules/{module}/video',               [VideoController::class, 'edit'])->name('modules.video.edit');
    Route::patch('/modules/{module}/video',              [VideoController::class, 'update'])->name('modules.video.update');
    Route::post('/modules/{module}/video/toggle',        [VideoController::class, 'toggle'])->name('modules.video.toggle');
    Route::get('/modules/{module}/video/preview',        [VideoController::class, 'preview'])->name('modules.video.preview');

    // Komponen Inti: 4. Praktik Interaktif (Embed Code / Simulator)
    Route::get('/modules/{module}/embed',               [EmbedController::class, 'edit'])->name('modules.embed.edit');
    Route::patch('/modules/{module}/embed',              [EmbedController::class, 'update'])->name('modules.embed.update');
    Route::post('/modules/{module}/embed/toggle',        [EmbedController::class, 'toggle'])->name('modules.embed.toggle');
    Route::get('/modules/{module}/embed/preview',        [EmbedController::class, 'preview'])->name('modules.embed.preview');

    // Komponen Inti: 5. Lembar Praktikum (Job Sheet PDF)
    Route::get('/modules/{module}/job-sheet',           [JobSheetController::class, 'edit'])->name('modules.job-sheet.edit');
    Route::patch('/modules/{module}/job-sheet',          [JobSheetController::class, 'update'])->name('modules.job-sheet.update');
    Route::post('/modules/{module}/job-sheet/toggle',    [JobSheetController::class, 'toggle'])->name('modules.job-sheet.toggle');
    Route::get('/modules/{module}/job-sheet/preview',    [JobSheetController::class, 'preview'])->name('modules.job-sheet.preview');
    Route::get('/modules/{module}/job-sheet/download',   [JobSheetController::class, 'downloadPdf'])->name('modules.job-sheet.download');

    // Komponen Inti: 6. Tugas LKPD (Kerjasama Kelompok / Individu)
    Route::get('/modules/{module}/lkpd',               [LkpdController::class, 'edit'])->name('modules.lkpd.edit');
    Route::patch('/modules/{module}/lkpd',              [LkpdController::class, 'update'])->name('modules.lkpd.update');
    Route::post('/modules/{module}/lkpd/toggle',        [LkpdController::class, 'toggle'])->name('modules.lkpd.toggle');
    Route::get('/modules/{module}/lkpd/preview',        [LkpdController::class, 'preview'])->name('modules.lkpd.preview');
    Route::get('/modules/{module}/lkpd/download',       [LkpdController::class, 'downloadPdf'])->name('modules.lkpd.download');

    // Komponen Inti: 7. Post-test (Kuis Penutup Pembelajaran)
    Route::get('/modules/{module}/post-test',           [PostTestController::class, 'edit'])->name('modules.post-test.edit');
    Route::patch('/modules/{module}/post-test',          [PostTestController::class, 'update'])->name('modules.post-test.update');
    Route::post('/modules/{module}/post-test/toggle',    [PostTestController::class, 'toggle'])->name('modules.post-test.toggle');
    Route::get('/modules/{module}/post-test/preview',    [PostTestController::class, 'preview'])->name('modules.post-test.preview');

    // Grading Center (Pusat Penilaian Adaptif)
    Route::get('/grading',                                                  [GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/modules/{module}',                                 [GradingController::class, 'show'])->name('grading.show');
    Route::get('/grading/modules/{module}/students/{student}',              [GradingController::class, 'getStudentDetail'])->name('grading.student.detail');
    Route::post('/grading/modules/{module}/students/{student}',             [GradingController::class, 'updateStudentGrade'])->name('grading.student.update');
    Route::post('/grading/modules/{module}/batch',                          [GradingController::class, 'batchUpdate'])->name('grading.batch.update');

    // Laporan PDF (placeholder)
    Route::get('/reports', fn () => view('pages.teacher.reports.index'))->name('reports.index');

    // Kelas Binaan (placeholder)
    Route::get('/classes', fn () => view('pages.teacher.classes.index'))->name('classes.index');
});

// ─── Student Protected ─────────────────────────────────────────────────────
Route::middleware('auth:student')->prefix('student')->name('dashboard.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.student.dashboard'))->name('student');
});
