<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Menampilkan daftar Master Data Guru.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $subjectId = $request->query('subject_id');

        $query = Teacher::with(['subjects', 'classes.major', 'modules.schoolClass'])
            ->withCount([
                'modules',
                'modules as published_modules_count' => fn($q) => $q->where('status', 'published'),
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        if ($subjectId && $subjectId !== 'all') {
            $query->whereHas('subjects', function ($q) use ($subjectId) {
                $q->where('subjects.id', $subjectId);
            });
        }

        $teachers = $query->latest('updated_at')->paginate(12)->withQueryString();

        // Data pendukung
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::with('major')->orderBy('grade')->orderBy('major_id')->orderBy('section')->get();
        $totalTeachers = Teacher::count();
        $assignedTeachersCount = Teacher::has('subjects')->count();
        $unassignedTeachersCount = Teacher::doesntHave('subjects')->count();

        $stats = [
            'total'      => $totalTeachers,
            'assigned'   => $assignedTeachersCount,
            'unassigned' => $unassignedTeachersCount,
        ];

        return view('pages.admin.teachers.index', compact(
            'teachers',
            'subjects',
            'classes',
            'stats',
            'search',
            'subjectId'
        ));
    }

    /**
     * Menyimpan data pendaftaran guru baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'identity_number' => ['required', 'string', 'max:100', 'unique:teachers,identity_number'],
            'password'        => ['required', 'string', 'min:6'],
            'subject_ids'     => ['nullable', 'array'],
            'subject_ids.*'   => ['exists:subjects,id'],
            'class_ids'       => ['nullable', 'array'],
            'class_ids.*'     => ['exists:classes,id'],
        ], [
            'name.required'            => 'Nama lengkap guru wajib diisi.',
            'identity_number.required' => 'NIP / NUPTK / Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Identitas ini sudah terdaftar untuk guru lain.',
            'password.required'        => 'Password akun guru wajib diisi.',
            'password.min'             => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $teacher = Teacher::create([
            'name'            => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'password'        => Hash::make($validated['password']),
        ]);

        if (!empty($validated['subject_ids'])) {
            $teacher->subjects()->sync($validated['subject_ids']);
        }

        if (!empty($validated['class_ids'])) {
            $teacher->classes()->sync($validated['class_ids']);
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', "Akun guru {$teacher->name} (NIP: {$teacher->identity_number}) berhasil didaftarkan.");
    }

    /**
     * Memperbarui data guru.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'identity_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('teachers', 'identity_number')->ignore($teacher->id),
            ],
            'password'        => ['nullable', 'string', 'min:6'],
            'subject_ids'     => ['nullable', 'array'],
            'subject_ids.*'   => ['exists:subjects,id'],
            'class_ids'       => ['nullable', 'array'],
            'class_ids.*'     => ['exists:classes,id'],
        ], [
            'name.required'            => 'Nama lengkap guru wajib diisi.',
            'identity_number.required' => 'NIP / Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Identitas ini sudah terdaftar untuk guru lain.',
            'password.min'             => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $teacher->name = $validated['name'];
        $teacher->identity_number = $validated['identity_number'];

        if (!empty($validated['password'])) {
            $teacher->password = Hash::make($validated['password']);
        }

        $teacher->save();

        $teacher->subjects()->sync($validated['subject_ids'] ?? []);
        $teacher->classes()->sync($validated['class_ids'] ?? []);

        return redirect()->route('admin.teachers.index')
            ->with('success', "Data guru {$teacher->name} berhasil diperbarui.");
    }

    /**
     * Menghapus akun guru.
     */
    public function destroy(Teacher $teacher)
    {
        $name = $teacher->name;
        $nip = $teacher->identity_number;

        // Detach subjects & classes first
        $teacher->subjects()->detach();
        $teacher->classes()->detach();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', "Akun guru {$name} (NIP: {$nip}) berhasil dihapus dari Master Data.");
    }
}

