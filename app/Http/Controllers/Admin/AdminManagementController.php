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

            if (Admin::where('identity_number', $teacher->identity_number)->exists()) {
                return back()->with('error', "Guru {$teacher->name} (NIP: {$teacher->identity_number}) sudah terdaftar sebagai Administrator.");
            }

            $adminPassword = !empty($validated['password'])
                ? Hash::make($validated['password'])
                : $teacher->password; // Gunakan kata sandi akun guru jika tidak diisi

            $admin = Admin::create([
                'name'            => $teacher->name,
                'identity_number' => $teacher->identity_number,
                'password'        => $adminPassword,
            ]);

            return redirect()->route('admin.admins.index')
                ->with('success', "Akun Administrator untuk guru {$admin->name} (NIP: {$admin->identity_number}) berhasil dibuat.");
        }

        // Input Manual
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'identity_number'       => ['required', 'string', 'max:100', 'unique:admins,identity_number'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required'            => 'Nama lengkap administrator wajib diisi.',
            'identity_number.required' => 'NIP / Nomor Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Nomor Identitas ini sudah terdaftar sebagai Administrator.',
            'password.required'        => 'Kata sandi wajib diisi.',
            'password.min'             => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'       => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $admin = Admin::create([
            'name'            => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'password'        => Hash::make($validated['password']),
        ]);

        $msg = "Akun Administrator {$admin->name} berhasil ditambahkan.";
        if (Teacher::where('identity_number', $admin->identity_number)->exists()) {
            $msg .= " NIP ini juga terhubung dengan akun Guru aktif.";
        }

        return redirect()->route('admin.admins.index')->with('success', $msg);
    }

    /**
     * Memperbarui data akun Administrator.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'identity_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('admins', 'identity_number')->ignore($admin->id),
            ],
            'password'        => ['nullable', 'string', 'min:6'],
        ], [
            'name.required'            => 'Nama lengkap administrator wajib diisi.',
            'identity_number.required' => 'NIP / Nomor Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Nomor Identitas ini sudah terdaftar untuk administrator lain.',
            'password.min'             => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $admin->name = $validated['name'];
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
        if (Teacher::where('identity_number', $admin->identity_number)->exists()) {
            return back()->with('info', "Administrator {$admin->name} sudah terdaftar sebagai Guru.");
        }

        Teacher::create([
            'name'            => $admin->name,
            'identity_number' => $admin->identity_number,
            'password'        => $admin->password, // gunakan hash password yang sama
        ]);

        return back()->with('success', "Administrator {$admin->name} (NIP: {$admin->identity_number}) kini resmi terdaftar juga sebagai Guru pendidik dan dapat mengampu modul ajar.");
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

        $teacher = Teacher::where('identity_number', $admin->identity_number)->first();
        if (!$teacher) {
            return back()->with('error', 'Akun NIP Anda belum terdaftar sebagai Guru. Silakan daftarkan terlebih dahulu.');
        }

        Auth::guard('teacher')->login($teacher);

        return redirect()->route('teacher.dashboard')
            ->with('success', "Selamat datang di Workspace Guru, {$teacher->name}!");
    }
}
