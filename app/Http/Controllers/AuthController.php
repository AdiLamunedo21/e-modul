<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ==========================================
    // ADMIN AUTH
    // ==========================================
    public function showAdminLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard.admin');
        }
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
            
            $intended = session()->get('url.intended');
            if ($intended && str_contains($intended, '/admin')) {
                return redirect()->intended(route('dashboard.admin'));
            }
            
            return redirect()->route('dashboard.admin');
        }

        return back()->withErrors([
            'identity_number' => 'NIP atau Password yang dimasukkan salah.',
        ])->onlyInput('identity_number');
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.admin');
    }

    // ==========================================
    // TEACHER AUTH
    // ==========================================
    public function showTeacherLogin()
    {
        if (Auth::guard('teacher')->check()) {
            return redirect()->route('teacher.dashboard');
        }
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

            $intended = session()->get('url.intended');
            if ($intended && str_contains($intended, '/teacher')) {
                return redirect()->intended(route('teacher.dashboard'));
            }

            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors([
            'identity_number' => 'NUPTK / NIP atau Password yang dimasukkan salah.',
        ])->onlyInput('identity_number');
    }

    public function teacherLogout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.teacher');
    }

    // ==========================================
    // STUDENT AUTH
    // ==========================================
    public function showStudentLogin()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('dashboard.student');
        }
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

            $intended = session()->get('url.intended');
            if ($intended && str_contains($intended, '/student')) {
                return redirect()->intended(route('dashboard.student'));
            }

            return redirect()->route('dashboard.student');
        }

        return back()->withErrors([
            'identity_number' => 'NISN atau Password yang dimasukkan salah.',
        ])->onlyInput('identity_number');
    }

    public function studentLogout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.student');
    }
}
