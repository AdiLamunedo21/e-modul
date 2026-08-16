<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ADMIN
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'identity_number' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'identity_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('identity_number');
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // TEACHER
    public function showTeacherLogin()
    {
        return view('auth.teacher-login');
    }

    public function teacherLogin(Request $request)
    {
        $credentials = $request->validate([
            'identity_number' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('teacher')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/teacher/dashboard');
        }

        return back()->withErrors([
            'identity_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('identity_number');
    }

    public function teacherLogout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // STUDENT
    public function showStudentLogin()
    {
        return view('auth.student-login');
    }

    public function studentLogin(Request $request)
    {
        $credentials = $request->validate([
            'identity_number' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/student/dashboard');
        }

        return back()->withErrors([
            'identity_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('identity_number');
    }

    public function studentLogout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
