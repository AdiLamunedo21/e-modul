<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Teacher\ModuleManagerController;
use App\Http\Controllers\Teacher\BagianAwalController;
use App\Http\Controllers\Teacher\PendahuluanController;
use App\Http\Controllers\Teacher\DaftarPustakaController;
use App\Http\Controllers\Teacher\PreTestController;
use App\Http\Controllers\Teacher\MateriController;
use App\Http\Controllers\Teacher\VideoController;
use App\Http\Controllers\Teacher\EmbedController;
use App\Http\Controllers\Teacher\JobSheetController;
use App\Http\Controllers\Teacher\LkpdController;
use App\Http\Controllers\Teacher\PostTestController;
use App\Http\Controllers\Teacher\GradingController;
use App\Http\Controllers\Teacher\ReportController;
use App\Http\Controllers\Teacher\ClassController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ModuleLibraryController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\MajorController as AdminMajorController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;

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
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/portal', [AdminDashboardController::class, 'index'])->name('dashboard.admin');

    // Master Data & Pendaftaran Guru
    Route::get('/teachers',              [AdminTeacherController::class, 'index'])->name('admin.teachers.index');
    Route::post('/teachers',             [AdminTeacherController::class, 'store'])->name('admin.teachers.store');
    Route::patch('/teachers/{teacher}',   [AdminTeacherController::class, 'update'])->name('admin.teachers.update');
    Route::delete('/teachers/{teacher}',  [AdminTeacherController::class, 'destroy'])->name('admin.teachers.destroy');

    // Master Data & Pendaftaran Siswa
    Route::get('/students',               [AdminStudentController::class, 'index'])->name('admin.students.index');
    Route::get('/students/class/{class}', [AdminStudentController::class, 'showClass'])->name('admin.students.class');
    Route::post('/students',              [AdminStudentController::class, 'store'])->name('admin.students.store');
    Route::patch('/students/{student}',    [AdminStudentController::class, 'update'])->name('admin.students.update');
    Route::delete('/students/{student}',   [AdminStudentController::class, 'destroy'])->name('admin.students.destroy');

    // Master Data Mata Pelajaran
    Route::get('/subjects',              [AdminSubjectController::class, 'index'])->name('admin.subjects.index');
    Route::post('/subjects',             [AdminSubjectController::class, 'store'])->name('admin.subjects.store');
    Route::patch('/subjects/{subject}',   [AdminSubjectController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/subjects/{subject}',  [AdminSubjectController::class, 'destroy'])->name('admin.subjects.destroy');

    // Master Data Jurusan / Konsentrasi Keahlian
    Route::get('/majors',                [AdminMajorController::class, 'index'])->name('admin.majors.index');
    Route::post('/majors',               [AdminMajorController::class, 'store'])->name('admin.majors.store');
    Route::patch('/majors/{major}',       [AdminMajorController::class, 'update'])->name('admin.majors.update');
    Route::delete('/majors/{major}',      [AdminMajorController::class, 'destroy'])->name('admin.majors.destroy');

    // Master Data Rombel Kelas
    Route::get('/classes',               [AdminClassController::class, 'index'])->name('admin.classes.index');
    Route::post('/classes',              [AdminClassController::class, 'store'])->name('admin.classes.store');
    Route::patch('/classes/{class}',      [AdminClassController::class, 'update'])->name('admin.classes.update');
    Route::delete('/classes/{class}',     [AdminClassController::class, 'destroy'])->name('admin.classes.destroy');
});

// ─── Teacher Protected ─────────────────────────────────────────────────────
Route::middleware('auth:teacher')->prefix('teacher')->name('teacher.')->group(function () {

    // Dashboard utama
    Route::get('/dashboard',                            [DashboardController::class, 'index'])->name('dashboard');

    // Manajer Modul (CRUD)
    Route::get('/modules',                              [ModuleManagerController::class, 'index'])->name('modules.index');
    Route::get('/modules/create',                       [ModuleManagerController::class, 'create'])->name('modules.create');
    Route::post('/modules',                             [ModuleManagerController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}',                     [ModuleManagerController::class, 'show'])->name('modules.show');
    Route::patch('/modules/{module}/status',            [ModuleManagerController::class, 'updateStatus'])->name('modules.status');
    Route::post('/modules/{module}/toggle-share',       [ModuleLibraryController::class, 'toggleShare'])->name('modules.toggle-share');
    Route::delete('/modules/{module}',                  [ModuleManagerController::class, 'destroy'])->name('modules.destroy');

    // Library Modul (Shared Module Repository & Cloning)
    Route::get('/library',                              [ModuleLibraryController::class, 'index'])->name('library.index');
    Route::get('/library/{module}',                     [ModuleLibraryController::class, 'show'])->name('library.show');
    Route::post('/library/{module}/clone',              [ModuleLibraryController::class, 'clone'])->name('library.clone');

    // 1. Bagian Awal: 4 Komponen (Cover, Kata Pengantar, Daftar Isi, Petunjuk Penggunaan)
    Route::get('/modules/{module}/bagian-awal',                 [BagianAwalController::class, 'edit'])->name('modules.bagian-awal.edit');
    Route::patch('/modules/{module}/bagian-awal',                [BagianAwalController::class, 'update'])->name('modules.bagian-awal.update');
    Route::post('/modules/{module}/bagian-awal/toggle/{component}', [BagianAwalController::class, 'toggle'])->name('modules.bagian-awal.toggle');

    // 2. Pendahuluan: 3 Komponen (Tujuan Pembelajaran & Capaian, Peta Konsep, Glosarium)
    Route::get('/modules/{module}/pendahuluan',                 [PendahuluanController::class, 'edit'])->name('modules.pendahuluan.edit');
    Route::patch('/modules/{module}/pendahuluan',                [PendahuluanController::class, 'update'])->name('modules.pendahuluan.update');
    Route::post('/modules/{module}/pendahuluan/toggle/{component}', [PendahuluanController::class, 'toggle'])->name('modules.pendahuluan.toggle');

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

    // 5. Bagian Akhir: Daftar Pustaka (Kepustakaan & Rujukan)
    Route::get('/modules/{module}/daftar-pustaka',          [DaftarPustakaController::class, 'edit'])->name('modules.daftar-pustaka.edit');
    Route::patch('/modules/{module}/daftar-pustaka',         [DaftarPustakaController::class, 'update'])->name('modules.daftar-pustaka.update');
    Route::post('/modules/{module}/daftar-pustaka/toggle',   [DaftarPustakaController::class, 'toggle'])->name('modules.daftar-pustaka.toggle');

    // Grading Center (Pusat Penilaian Adaptif)
    Route::get('/grading',                                                  [GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/modules/{module}',                                 [GradingController::class, 'show'])->name('grading.show');
    Route::get('/grading/modules/{module}/students/{student}',              [GradingController::class, 'getStudentDetail'])->name('grading.student.detail');
    Route::post('/grading/modules/{module}/students/{student}',             [GradingController::class, 'updateStudentGrade'])->name('grading.student.update');
    Route::post('/grading/modules/{module}/batch',                          [GradingController::class, 'batchUpdate'])->name('grading.batch.update');

    // Laporan Spreadsheet / Excel (.xlsx)
    Route::get('/reports',                                  [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/modules/{module}/export',          [ReportController::class, 'exportModule'])->name('reports.export.module');
    Route::get('/modules/{module}/export-grades',           [ReportController::class, 'exportModule'])->name('modules.export.grades');

    // Kelas Binaan & Direktori Siswa
    Route::get('/classes',                                                  [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/{class}',                                          [ClassController::class, 'show'])->name('classes.show');
    Route::get('/classes/{class}/students/{student}/summary',               [ClassController::class, 'getStudentAcademicSummary'])->name('classes.student.summary');
});

// ─── Student Protected ─────────────────────────────────────────────────────
Route::middleware('auth:student')->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    // Alias untuk rute dashboard.student
    Route::get('/portal', [StudentDashboardController::class, 'index'])->name('dashboard.student');

    // Modul Belajar Siswa per Mata Pelajaran
    Route::get('/modules/subject/{subject}', [StudentModuleController::class, 'bySubject'])->name('student.modules.subject');
});
