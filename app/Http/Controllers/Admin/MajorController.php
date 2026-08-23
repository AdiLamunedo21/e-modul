<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MajorController extends Controller
{
    /**
     * Menampilkan daftar Master Data Jurusan / Kompetensi Keahlian.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Major::withCount(['classes']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $majors = $query->latest('updated_at')->paginate(12)->withQueryString();

        $stats = [
            'total'         => Major::count(),
            'total_classes' => \App\Models\SchoolClass::count(),
            'total_students'=> \App\Models\Student::count(),
        ];

        return view('pages.admin.majors.index', compact('majors', 'stats', 'search'));
    }

    /**
     * Menyimpan jurusan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'max:20', 'unique:majors,code'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama jurusan / konsentrasi keahlian wajib diisi.',
            'code.required' => 'Kode singkatan jurusan wajib diisi.',
            'code.unique'   => 'Kode singkatan jurusan ini sudah digunakan.',
        ]);

        $major = Major::create([
            'name'        => $validated['name'],
            'code'        => strtoupper($validated['code']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.majors.index')
            ->with('success', "Jurusan {$major->name} ({$major->code}) berhasil ditambahkan.");
    }

    /**
     * Memperbarui jurusan.
     */
    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'code'        => [
                'required',
                'string',
                'max:20',
                Rule::unique('majors', 'code')->ignore($major->id),
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama jurusan / konsentrasi keahlian wajib diisi.',
            'code.required' => 'Kode singkatan jurusan wajib diisi.',
            'code.unique'   => 'Kode singkatan jurusan ini sudah digunakan.',
        ]);

        $major->update([
            'name'        => $validated['name'],
            'code'        => strtoupper($validated['code']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.majors.index')
            ->with('success', "Data Jurusan {$major->name} berhasil diperbarui.");
    }

    /**
     * Menghapus jurusan.
     */
    public function destroy(Major $major)
    {
        $name = $major->name;
        $code = $major->code;

        $major->delete();

        return redirect()->route('admin.majors.index')
            ->with('success', "Jurusan {$name} ({$code}) berhasil dihapus dari Master Data.");
    }
}
