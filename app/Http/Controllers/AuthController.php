<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    // STUDENT AUTH & REGISTRATION
    // ==========================================
    public function showStudentLogin()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
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
                return redirect()->intended(route('student.dashboard'));
            }

            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'identity_number' => 'NISN atau Password yang dimasukkan salah.',
        ])->onlyInput('identity_number');
    }

    public function showStudentRegister()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }
        return view('auth.student-register');
    }

    public function studentRegister(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'identity_number' => ['required', 'string', 'max:100', 'unique:students,identity_number'],
            'password'        => ['required', 'string', 'min:6', 'confirmed'],
            'class_code'      => ['nullable', 'string', 'max:20'],
        ], [
            'name.required'            => 'Nama lengkap wajib diisi.',
            'identity_number.required' => 'NISN / No. Identitas wajib diisi.',
            'identity_number.unique'   => 'NISN ini sudah terdaftar di sistem.',
            'password.required'        => 'Password wajib diisi.',
            'password.min'             => 'Password minimal 6 karakter.',
            'password.confirmed'       => 'Konfirmasi password tidak cocok.',
        ]);

        $class = null;
        if (!empty($validated['class_code'])) {
            $classCode = strtoupper(trim($validated['class_code']));
            $class = SchoolClass::where('code', $classCode)->first();
            if (!$class) {
                return back()->withErrors([
                    'class_code' => "Kode kelas '{$classCode}' tidak ditemukan. Pastikan kode kelas yang dimasukkan sudah benar.",
                ])->withInput();
            }
        }

        $student = Student::create([
            'name'            => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'password'        => Hash::make($validated['password']),
            'class_id'        => $class?->id,
        ]);

        if ($class) {
            $student->joinClass($class);
        }

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        $message = $class
            ? "Pendaftaran berhasil! Anda telah otomatis bergabung ke {$class->full_name}."
            : "Pendaftaran berhasil! Akun Anda telah aktif. Masukkan kode kelas dari guru Anda untuk mulai belajar.";

        return redirect()->route('student.dashboard')->with('success', $message);
    }

    public function studentLogout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.student');
    }
}

