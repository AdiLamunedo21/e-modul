<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Module;
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

        $query = Teacher::with(['subjects', 'classes.major', 'modules.schoolClass.major', 'modules.subject'])
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
        $existingAdminNips = Admin::pluck('identity_number')->toArray();

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
            'subjectId',
            'existingAdminNips'
        ));
    }

    /**
     * Menampilkan halaman detail profil guru beserta seluruh modul yang telah dibuat.
     */
    public function show(Request $request, Teacher $teacher)
    {
        $teacher->load(['subjects', 'classes.major']);

        $search = $request->query('search');
        $status = $request->query('status');
        $subjectId = $request->query('subject_id');

        $modulesQuery = $teacher->modules()->with(['schoolClass.major', 'subject']);

        if ($search) {
            $modulesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  })
                  ->orWhereHas('schoolClass', function ($cq) use ($search) {
                      $cq->where('grade', 'like', "%{$search}%")
                         ->orWhere('section', 'like', "%{$search}%")
                         ->orWhere('major_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status && $status !== 'all') {
            $modulesQuery->where('status', $status);
        }

        if ($subjectId && $subjectId !== 'all') {
            $modulesQuery->where('subject_id', $subjectId);
        }

        $modules = $modulesQuery->latest('updated_at')->paginate(9)->withQueryString();

        $stats = [
            'total_modules'     => $teacher->modules()->count(),
            'published_modules' => $teacher->modules()->where('status', 'published')->count(),
            'draft_modules'     => $teacher->modules()->where('status', 'draft')->count(),
            'closed_modules'    => $teacher->modules()->where('status', 'closed')->count(),
            'shared_modules'    => $teacher->modules()->where('is_shared', true)->count(),
        ];

        $teacherSubjects = $teacher->subjects;
        $allClasses = SchoolClass::with('major')->orderBy('grade')->orderBy('major_id')->orderBy('section')->get();
        $allSubjects = Subject::orderBy('name')->get();

        return view('pages.admin.teachers.show', compact(
            'teacher',
            'modules',
            'stats',
            'search',
            'status',
            'subjectId',
            'teacherSubjects',
            'allClasses',
            'allSubjects'
        ));
    }

    /**
     * Menampilkan isi lengkap E-Modul guru untuk supervisi Admin Master.
     */
    public function showModule(Teacher $teacher, Module $module)
    {
        if ($module->teacher_id !== $teacher->id) {
            abort(404, 'Modul pembelajaran ini tidak terdaftar pada profil guru ini.');
        }

        request()->merge(['from' => 'teacher']);
        return app(\App\Http\Controllers\Admin\ModuleLibraryController::class)->show($module);
    }

    /**
     * Menyimpan data pendaftaran guru baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['nullable', 'string', 'email', 'max:255', 'unique:teachers,email'],
            'identity_number' => ['required', 'string', 'max:100', 'unique:teachers,identity_number'],
            'password'        => ['required', 'string', 'min:6'],
            'subject_ids'     => ['nullable', 'array'],
            'subject_ids.*'   => ['exists:subjects,id'],
            'class_ids'       => ['nullable', 'array'],
            'class_ids.*'     => ['exists:classes,id'],
        ];

        if ($request->has('email')) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:teachers,email'];
        }

        $validated = $request->validate($rules, [
            'name.required'            => 'Nama lengkap guru wajib diisi.',
            'email.required'           => 'Email guru wajib diisi.',
            'email.email'              => 'Format email tidak valid (contoh: namaSingkat@gmail.com).',
            'email.unique'             => 'Email ini sudah terdaftar untuk guru lain.',
            'identity_number.required' => 'NIP / NUPTK / Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Identitas ini sudah terdaftar untuk guru lain.',
            'password.required'        => 'Password akun guru wajib diisi.',
            'password.min'             => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $email = $validated['email'] ?? null;
        if (empty($email)) {
            $clean = preg_replace('/^(Drs\.|Dr\.|Ir\.|Prof\.|H\.|Hj\.|Ust\.)\s+/i', '', trim($validated['name']));
            $parts = explode(',', $clean);
            $words = preg_split('/\s+/', trim($parts[0]));
            $firstWord = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $words[0] ?? 'guru'));
            $email = ($firstWord ?: 'guru') . rand(100, 9999) . '@gmail.com';
        } else {
            $email = strtolower(trim($email));
        }

        $teacher = Teacher::create([
            'name'            => $validated['name'],
            'email'           => $email,
            'identity_number' => $validated['identity_number'],
            'password'        => Hash::make($validated['password']),
        ]);

        if (!empty($validated['subject_ids'])) {
            $teacher->subjects()->sync($validated['subject_ids']);
        }

        if (!empty($validated['class_ids'])) {
            $teacher->classes()->sync($validated['class_ids']);
            $classes = SchoolClass::whereIn('id', $validated['class_ids'])->get();
            foreach ($classes as $cls) {
                $cls->syncAllStudentsSubjects();
            }
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', "Akun guru {$teacher->name} (Email: {$teacher->email} | NIP: {$teacher->identity_number}) berhasil didaftarkan.");
    }

    /**
     * Memperbarui data guru.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $emailRule = [
            'nullable',
            'string',
            'email',
            'max:255',
            Rule::unique('teachers', 'email')->ignore($teacher->id),
        ];

        if ($request->has('email')) {
            $emailRule = [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('teachers', 'email')->ignore($teacher->id),
            ];
        }

        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => $emailRule,
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
            'email.required'           => 'Email guru wajib diisi.',
            'email.email'              => 'Format email tidak valid (contoh: namaSingkat@gmail.com).',
            'email.unique'             => 'Email ini sudah digunakan oleh guru lain.',
            'identity_number.required' => 'NIP / Identitas wajib diisi.',
            'identity_number.unique'   => 'NIP / Identitas ini sudah terdaftar untuk guru lain.',
            'password.min'             => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $teacher->name = $validated['name'];
        if (!empty($validated['email'])) {
            $teacher->email = strtolower(trim($validated['email']));
        }
        $teacher->identity_number = $validated['identity_number'];

        if (!empty($validated['password'])) {
            $teacher->password = Hash::make($validated['password']);
        }

        $teacher->save();

        // Gabungkan mata pelajaran yang dipilih admin dengan mata pelajaran modul aktif milik guru
        // agar data plotting tidak hilang tanpa sengaja
        $submittedSubjectIds = array_map('intval', $validated['subject_ids'] ?? []);
        $moduleSubjectIds = $teacher->modules()->whereNotNull('subject_id')->pluck('subject_id')->map(fn($id) => (int)$id)->toArray();
        $finalSubjectIds = array_values(array_unique(array_merge($submittedSubjectIds, $moduleSubjectIds)));
        $teacher->subjects()->sync($finalSubjectIds);

        // Penugasan kelas didik (tanggung jawab mengajar): murni mengikuti pilihan Admin
        // agar Admin leluasa menambah maupun mengurangi rombel binaan guru
        $submittedClassIds = array_map('intval', $validated['class_ids'] ?? []);
        $teacher->classes()->sync($submittedClassIds);

        if (!empty($submittedClassIds)) {
            $classes = SchoolClass::whereIn('id', $submittedClassIds)->get();
            foreach ($classes as $cls) {
                $cls->syncAllStudentsSubjects();
            }
        }

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

    /**
     * Memberikan akses Administrator kepada Guru (menjadikan admin dengan NIP dan password yang sama).
     */
    public function makeAdmin(Teacher $teacher)
    {
        $exists = Admin::where('identity_number', $teacher->identity_number)
            ->when(!empty($teacher->email), fn($q) => $q->orWhere('email', $teacher->email))
            ->exists();

        if ($exists) {
            return back()->with('info', "Guru {$teacher->name} sudah terdaftar sebagai Administrator.");
        }

        $email = !empty($teacher->email)
            ? $teacher->email
            : (strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $teacher->name)[0] ?? 'admin')) . rand(100, 9999) . '@gmail.com');

        $admin = Admin::create([
            'name'            => $teacher->name,
            'email'           => $email,
            'identity_number' => $teacher->identity_number,
            'password'        => $teacher->password, // gunakan hash password yang sama
        ]);

        return back()->with('success', "Hak akses Administrator berhasil diberikan kepada guru {$teacher->name} (Email: {$admin->email} | NIP: {$teacher->identity_number}). Akun kini dapat login ke Admin Panel atau alih peran.");
    }
}
