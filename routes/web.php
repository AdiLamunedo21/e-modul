<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Teacher\ModuleManagerController;
use App\Http\Controllers\Teacher\BagianAwalController;
use App\Http\Controllers\Teacher\PreTestController;
use App\Http\Controllers\Teacher\MateriController;

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

    // Bagian Awal Editor
    Route::get('/modules/{module}/bagian-awal',   [BagianAwalController::class, 'edit'])->name('modules.bagian-awal.edit');
    Route::patch('/modules/{module}/bagian-awal',  [BagianAwalController::class, 'update'])->name('modules.bagian-awal.update');

    // Bagian Inti: 1. Pre-test Quiz Builder
    Route::get('/modules/{module}/pre-test',        [PreTestController::class, 'edit'])->name('modules.pre-test.edit');
    Route::patch('/modules/{module}/pre-test',       [PreTestController::class, 'update'])->name('modules.pre-test.update');
    Route::post('/modules/{module}/pre-test/toggle',  [PreTestController::class, 'toggle'])->name('modules.pre-test.toggle');
    Route::get('/modules/{module}/pre-test/preview',  [PreTestController::class, 'preview'])->name('modules.pre-test.preview');

    // Bagian Inti: 2. Materi & PPT
    Route::get('/modules/{module}/materi',              [MateriController::class, 'edit'])->name('modules.materi.edit');
    Route::patch('/modules/{module}/materi',             [MateriController::class, 'update'])->name('modules.materi.update');
    Route::post('/modules/{module}/materi/toggle',       [MateriController::class, 'toggle'])->name('modules.materi.toggle');
    Route::get('/modules/{module}/materi/preview',       [MateriController::class, 'preview'])->name('modules.materi.preview');
    Route::get('/modules/{module}/materi/download-ppt',   [MateriController::class, 'downloadPpt'])->name('modules.materi.download-ppt');
    Route::post('/modules/{module}/materi/upload-image', [MateriController::class, 'uploadImage'])->name('modules.materi.upload-image');

    // Grading Center (placeholder — dikembangkan berikutnya)
    Route::get('/grading', fn () => view('pages.teacher.grading.index'))->name('grading.index');

    // Laporan PDF (placeholder)
    Route::get('/reports', fn () => view('pages.teacher.reports.index'))->name('reports.index');

    // Kelas Binaan (placeholder)
    Route::get('/classes', fn () => view('pages.teacher.classes.index'))->name('classes.index');
});

// ─── Student Protected ─────────────────────────────────────────────────────
Route::middleware('auth:student')->prefix('student')->name('dashboard.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.student.dashboard'))->name('student');
});
