<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Auth
Route::get('/login/admin', [AuthController::class, 'showAdminLogin'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'adminLogin']);
Route::post('/logout/admin', [AuthController::class, 'adminLogout'])->name('logout.admin');

// Teacher Auth
Route::get('/login/teacher', [AuthController::class, 'showTeacherLogin'])->name('login.teacher');
Route::post('/login/teacher', [AuthController::class, 'teacherLogin']);
Route::post('/logout/teacher', [AuthController::class, 'teacherLogout'])->name('logout.teacher');

// Student Auth
Route::get('/login/student', [AuthController::class, 'showStudentLogin'])->name('login.student');
Route::post('/login/student', [AuthController::class, 'studentLogin']);
Route::post('/logout/student', [AuthController::class, 'studentLogout'])->name('logout.student');

// Protected Dashboards
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('dashboard.admin');
});

Route::middleware('auth:teacher')->group(function () {
    Route::get('/teacher/dashboard', function () {
        return view('pages.teacher.dashboard');
    })->name('dashboard.teacher');
});

Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', function () {
        return view('pages.student.dashboard');
    })->name('dashboard.student');
});
