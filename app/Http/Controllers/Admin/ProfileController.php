<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil & pengaturan keamanan administrator.
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('pages.admin.profile', compact('admin'));
    }

    /**
     * Memperbarui informasi profil admin (nama dan NIP / identitas).
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'identity_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('admins', 'identity_number')->ignore($admin->id),
            ],
        ], [
            'name.required'            => 'Nama lengkap administrator wajib diisi.',
            'identity_number.required' => 'NIP / Nomor Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Nomor Identitas ini sudah digunakan akun lain.',
        ]);

        $admin->update([
            'name'            => $validated['name'],
            'identity_number' => $validated['identity_number'],
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('profile_success', 'Informasi profil administrator berhasil diperbarui.');
    }

    /**
     * Memperbarui kata sandi administrator.
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required'         => 'Kata sandi baru wajib diisi.',
            'password.min'              => 'Kata sandi baru minimal terdiri dari 6 karakter.',
            'password.confirmed'        => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
            ]);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('password_success', 'Kata sandi administrator berhasil diubah.');
    }
}
