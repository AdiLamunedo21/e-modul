<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    /**
     * Menampilkan daftar akun Administrator.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Admin::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        $admins = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        // Kumpulkan NIP seluruh admin yang ada di halaman ini untuk cek status guru
        $adminNips = $admins->pluck('identity_number')->toArray();
        $existingTeacherNips = Teacher::whereIn('identity_number', $adminNips)->pluck('identity_number')->toArray();

        // Ambil daftar guru yang belum menjadi admin untuk opsi "Pilih dari Guru"
        $allAdminNips = Admin::pluck('identity_number')->toArray();
        $availableTeachers = Teacher::whereNotIn('identity_number', $allAdminNips)
            ->orderBy('name')
            ->get(['id', 'name', 'identity_number']);

        $totalAdmins = Admin::count();
        $dualRoleCount = Teacher::whereIn('identity_number', $allAdminNips)->count();
        $pureAdminCount = $totalAdmins - $dualRoleCount;

        $stats = [
            'total'      => $totalAdmins,
            'dual_role'  => $dualRoleCount,
            'pure_admin' => $pureAdminCount,
        ];

        return view('pages.admin.admins.index', compact(
            'admins',
            'existingTeacherNips',
            'availableTeachers',
            'stats',
            'search'
        ));
    }

    /**
     * Menyimpan akun Administrator baru (bisa dari data guru atau input manual).
     */
    public function store(Request $request)
    {
        $sourceType = $request->input('source_type', 'manual');

        if ($sourceType === 'teacher') {
            $validated = $request->validate([
                'teacher_id' => ['required', 'exists:teachers,id'],
                'password'   => ['nullable', 'string', 'min:6'],
            ], [
                'teacher_id.required' => 'Silakan pilih akun guru yang akan dijadikan administrator.',
                'teacher_id.exists'   => 'Akun guru yang dipilih tidak valid.',
                'password.min'        => 'Password baru minimal terdiri dari 6 karakter.',
            ]);

            $teacher = Teacher::findOrFail($validated['teacher_id']);

            $exists = Admin::where('identity_number', $teacher->identity_number)
                ->when(!empty($teacher->email), fn($q) => $q->orWhere('email', $teacher->email))
                ->exists();

            if ($exists) {
                return back()->with('error', "Guru {$teacher->name} (NIP: {$teacher->identity_number}) sudah terdaftar sebagai Administrator.");
            }

            $adminPassword = !empty($validated['password'])
                ? Hash::make($validated['password'])
                : $teacher->password; // Gunakan kata sandi akun guru jika tidak diisi

            $email = !empty($teacher->email)
                ? $teacher->email
                : (strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $teacher->name)[0] ?? 'admin')) . rand(100, 9999) . '@gmail.com');

            $admin = Admin::create([
                'name'            => $teacher->name,
                'email'           => $email,
                'identity_number' => $teacher->identity_number,
                'password'        => $adminPassword,
            ]);

            return redirect()->route('admin.admins.index')
                ->with('success', "Akun Administrator untuk guru {$admin->name} (Email: {$admin->email} | NIP: {$admin->identity_number}) berhasil dibuat.");
        }

        // Input Manual
        $rules = [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['nullable', 'string', 'email', 'max:255', 'unique:admins,email'],
            'identity_number'       => ['required', 'string', 'max:100', 'unique:admins,identity_number'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
        ];

        if ($request->has('email')) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:admins,email'];
        }

        $validated = $request->validate($rules, [
            'name.required'            => 'Nama lengkap administrator wajib diisi.',
            'email.required'           => 'Email administrator wajib diisi.',
            'email.email'              => 'Format email tidak valid (contoh: namaSingkat@gmail.com).',
            'email.unique'             => 'Email ini sudah terdaftar sebagai Administrator.',
            'identity_number.required' => 'NIP / Nomor Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Nomor Identitas ini sudah terdaftar sebagai Administrator.',
            'password.required'        => 'Kata sandi wajib diisi.',
            'password.min'             => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'       => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = $validated['email'] ?? null;
        if (empty($email)) {
            $clean = preg_replace('/^(Drs\.|Dr\.|Ir\.|Prof\.|H\.|Hj\.|Ust\.)\s+/i', '', trim($validated['name']));
            $parts = explode(',', $clean);
            $words = preg_split('/\s+/', trim($parts[0]));
            $firstWord = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $words[0] ?? 'admin'));
            $email = ($firstWord ?: 'admin') . rand(100, 9999) . '@gmail.com';
        } else {
            $email = strtolower(trim($email));
        }

        $admin = Admin::create([
            'name'            => $validated['name'],
            'email'           => $email,
            'identity_number' => $validated['identity_number'],
            'password'        => Hash::make($validated['password']),
        ]);

        $msg = "Akun Administrator {$admin->name} (Email: {$admin->email}) berhasil ditambahkan.";
        if (Teacher::where('identity_number', $admin->identity_number)->orWhere('email', $admin->email)->exists()) {
            $msg .= " Akun ini juga terhubung dengan akun Guru aktif.";
        }

        return redirect()->route('admin.admins.index')->with('success', $msg);
    }

    /**
     * Memperbarui data akun Administrator.
     */
    public function update(Request $request, Admin $admin)
    {
        $emailRule = [
            'nullable',
            'string',
            'email',
            'max:255',
            Rule::unique('admins', 'email')->ignore($admin->id),
        ];

        if ($request->has('email')) {
            $emailRule = [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ];
        }

        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => $emailRule,
            'identity_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('admins', 'identity_number')->ignore($admin->id),
            ],
            'password'        => ['nullable', 'string', 'min:6'],
        ], [
            'name.required'            => 'Nama lengkap administrator wajib diisi.',
            'email.required'           => 'Email administrator wajib diisi.',
            'email.email'              => 'Format email tidak valid (contoh: namaSingkat@gmail.com).',
            'email.unique'             => 'Email ini sudah terdaftar untuk administrator lain.',
            'identity_number.required' => 'NIP / Nomor Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Nomor Identitas ini sudah terdaftar untuk administrator lain.',
            'password.min'             => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $admin->name = $validated['name'];
        if (!empty($validated['email'])) {
            $admin->email = strtolower(trim($validated['email']));
        }
        $admin->identity_number = $validated['identity_number'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')
            ->with('success', "Data Administrator {$admin->name} berhasil diperbarui.");
    }

    /**
     * Menghapus akun Administrator cadangan dengan proteksi keamanan.
     */
    public function destroy(Admin $admin)
    {
        // 1. Larang menghapus diri sendiri yang sedang login
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun administrator yang sedang Anda gunakan untuk login saat ini.');
        }

        // 2. Larang menghapus jika hanya ada 1 admin di sistem
        if (Admin::count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus administrator terakhir. Sistem harus memiliki minimal satu akun administrator.');
        }

        $name = $admin->name;
        $nip = $admin->identity_number;

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', "Akun Administrator {$name} (NIP: {$nip}) berhasil dihapus. Akun guru dengan NIP terkait (jika ada) tetap aman.");
    }

    /**
     * Mendaftarkan akun admin ini ke tabel Guru jika belum terdaftar.
     */
    public function makeTeacher(Admin $admin)
    {
        $exists = Teacher::where('identity_number', $admin->identity_number)
            ->when(!empty($admin->email), fn($q) => $q->orWhere('email', $admin->email))
            ->exists();

        if ($exists) {
            return back()->with('info', "Administrator {$admin->name} sudah terdaftar sebagai Guru.");
        }

        $email = !empty($admin->email)
            ? $admin->email
            : (strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $admin->name)[0] ?? 'guru')) . rand(100, 9999) . '@gmail.com');

        Teacher::create([
            'name'            => $admin->name,
            'email'           => $email,
            'identity_number' => $admin->identity_number,
            'password'        => $admin->password, // gunakan hash password yang sama
        ]);

        return back()->with('success', "Administrator {$admin->name} (Email: {$email} | NIP: {$admin->identity_number}) kini resmi terdaftar juga sebagai Guru pendidik dan dapat mengampu modul ajar.");
    }

    /**
     * Alih peran instan dari Admin ke Guru.
     */
    public function switchToTeacher(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('login.admin');
        }

        $teacher = Teacher::where('identity_number', $admin->identity_number)
            ->orWhere(function ($q) use ($admin) {
                if ($admin->email) {
                    $q->where('email', $admin->email);
                }
            })->first();

        if (!$teacher) {
            return back()->with('error', 'Akun NIP/Email Anda belum terdaftar sebagai Guru. Silakan daftarkan terlebih dahulu.');
        }

        Auth::guard('teacher')->login($teacher);

        return redirect()->route('teacher.dashboard')
            ->with('success', "Selamat datang di Workspace Guru, {$teacher->name}!");
    }
}
