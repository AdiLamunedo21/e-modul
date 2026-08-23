<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Menampilkan daftar Master Data Mata Pelajaran.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Subject::withCount(['teachers', 'modules']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $subjects = $query->latest('updated_at')->paginate(12)->withQueryString();

        $stats = [
            'total'         => Subject::count(),
            'total_modules' => \App\Models\Module::count(),
        ];

        return view('pages.admin.subjects.index', compact('subjects', 'stats', 'search'));
    }

    /**
     * Menyimpan mata pelajaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'color'       => ['required', 'string', Rule::in(['blue', 'indigo', 'emerald', 'amber', 'rose', 'cyan'])],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique'   => 'Kode mata pelajaran ini sudah digunakan.',
            'color.required'=> 'Warna tema badge wajib dipilih.',
        ]);

        $subject = Subject::create([
            'name'        => $validated['name'],
            'code'        => strtoupper($validated['code']),
            'icon'        => $validated['icon'] ?? '📚',
            'color'       => $validated['color'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', "Mata Pelajaran {$subject->name} ({$subject->code}) berhasil ditambahkan.");
    }

    /**
     * Memperbarui mata pelajaran.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'code'        => [
                'required',
                'string',
                'max:20',
                Rule::unique('subjects', 'code')->ignore($subject->id),
            ],
            'icon'        => ['nullable', 'string', 'max:10'],
            'color'       => ['required', 'string', Rule::in(['blue', 'indigo', 'emerald', 'amber', 'rose', 'cyan'])],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique'   => 'Kode mata pelajaran ini sudah digunakan.',
            'color.required'=> 'Warna tema badge wajib dipilih.',
        ]);

        $subject->update([
            'name'        => $validated['name'],
            'code'        => strtoupper($validated['code']),
            'icon'        => $validated['icon'] ?? $subject->icon,
            'color'       => $validated['color'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.subjects.index')
            ->with('success', "Data Mata Pelajaran {$subject->name} berhasil diperbarui.");
    }

    /**
     * Menghapus mata pelajaran.
     */
    public function destroy(Subject $subject)
    {
        $name = $subject->name;
        $code = $subject->code;

        $subject->teachers()->detach();
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', "Mata Pelajaran {$name} ({$code}) berhasil dihapus dari Master Data.");
    }
}
