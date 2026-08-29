<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Menampilkan direktori kartu Rombel Kelas pada Master Data Siswa.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $grade = $request->query('grade');
        $majorId = $request->query('major_id');

        $query = SchoolClass::with(['major', 'students.subjects'])
            ->withCount(['students', 'modules']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('grade', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%")
                  ->orWhere('major_name', 'like', "%{$search}%")
                  ->orWhereHas('major', function ($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        if ($grade && $grade !== 'all') {
            $query->where('grade', $grade);
        }

        if ($majorId && $majorId !== 'all') {
            $query->where('major_id', $majorId);
        }

        $classesList = $query->orderBy('grade')->orderBy('section')->get();

        // Data pendukung untuk modal registrasi cepat & filter
        $classes = SchoolClass::with('major')->orderBy('grade')->orderBy('major_name')->get();
        $majors = Major::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $totalStudents = Student::count();
        $totalClasses = SchoolClass::count();
        $assignedStudentsCount = Student::has('subjects')->count();

        $stats = [
            'total_students'      => $totalStudents,
            'total_classes'       => $totalClasses,
            'assigned_students'   => $assignedStudentsCount,
            'unassigned_students' => $totalStudents - $assignedStudentsCount,
            'total_majors'        => $majors->count(),
        ];

        return view('pages.admin.students.index', compact(
            'classesList',
            'classes',
            'majors',
            'subjects',
            'stats',
            'search',
            'grade',
            'majorId'
        ));
    }

    /**
     * Menampilkan daftar seluruh siswa yang berada pada rombel kelas tertentu.
     */
    public function showClass(Request $request, SchoolClass $class)
    {
        $class->loadMissing(['major', 'modules.subject']);

        $search = $request->query('search');
        $subjectId = $request->query('subject_id');

        $query = Student::where('class_id', $class->id)
            ->with(['schoolClass.major', 'subjects'])
            ->withCount(['studentResults', 'jobSheetSubmissions', 'lkpdSubmissions']);

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

        $students = $query->latest('updated_at')->paginate(20)->withQueryString();

        // Data pendukung
        $classes = SchoolClass::with('major')->orderBy('grade')->orderBy('major_name')->get();
        $subjects = Subject::orderBy('name')->get();

        $classTotalStudents = $class->students()->count();
        $classAssignedStudents = $class->students()->has('subjects')->count();

        $classStats = [
            'total_students'      => $classTotalStudents,
            'assigned_students'   => $classAssignedStudents,
            'unassigned_students' => $classTotalStudents - $classAssignedStudents,
            'total_modules'       => $class->modules()->where('status', 'published')->count(),
        ];

        return view('pages.admin.students.class', compact(
            'class',
            'students',
            'classes',
            'subjects',
            'classStats',
            'search',
            'subjectId'
        ));
    }

    /**
     * Menyimpan data pendaftaran siswa baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'identity_number' => ['required', 'string', 'max:100', 'unique:students,identity_number'],
            'class_id'        => ['nullable', 'exists:classes,id'],
            'password'        => ['required', 'string', 'min:6'],
            'subject_ids'     => ['nullable', 'array'],
            'subject_ids.*'   => ['exists:subjects,id'],
        ], [
            'name.required'            => 'Nama lengkap siswa wajib diisi.',
            'identity_number.required' => 'NISN / No. Induk Siswa wajib diisi.',
            'identity_number.unique'   => 'NISN / Identitas ini sudah terdaftar untuk siswa lain.',
            'class_id.exists'          => 'Kelas yang dipilih tidak valid.',
            'password.required'        => 'Password akun siswa wajib diisi.',
            'password.min'             => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $student = Student::create([
            'name'            => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'class_id'        => $validated['class_id'] ?? null,
            'password'        => Hash::make($validated['password']),
        ]);

        if (!empty($validated['subject_ids'])) {
            $student->subjects()->sync($validated['subject_ids']);
        }

        if ($student->class_id) {
            return redirect()->route('admin.students.class', $student->class_id)
                ->with('success', "Akun siswa {$student->name} (NISN: {$student->identity_number}) berhasil didaftarkan ke {$student->schoolClass?->full_name}.");
        }

        return redirect()->route('admin.students.index')
            ->with('success', "Akun siswa {$student->name} (NISN: {$student->identity_number}) berhasil didaftarkan.");
    }

    /**
     * Memperbarui data siswa.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'identity_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('students', 'identity_number')->ignore($student->id),
            ],
            'class_id'        => ['nullable', 'exists:classes,id'],
            'password'        => ['nullable', 'string', 'min:6'],
            'subject_ids'     => ['nullable', 'array'],
            'subject_ids.*'   => ['exists:subjects,id'],
        ], [
            'name.required'            => 'Nama lengkap siswa wajib diisi.',
            'identity_number.required' => 'NISN / Identitas wajib diisi.',
            'identity_number.unique'   => 'NISN / Identitas ini sudah terdaftar untuk siswa lain.',
            'class_id.exists'          => 'Kelas yang dipilih tidak valid.',
            'password.min'             => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $student->name = $validated['name'];
        $student->identity_number = $validated['identity_number'];
        $student->class_id = $validated['class_id'] ?? null;

        if (!empty($validated['password'])) {
            $student->password = Hash::make($validated['password']);
        }

        $student->save();

        $student->subjects()->sync($validated['subject_ids'] ?? []);

        return redirect()->route('admin.students.class', $student->class_id)
            ->with('success', "Data siswa {$student->name} berhasil diperbarui.");
    }

    /**
     * Menghapus akun siswa.
     */
    public function destroy(Student $student)
    {
        $name = $student->name;
        $nisn = $student->identity_number;
        $classId = $student->class_id;

        // Detach subjects first
        $student->subjects()->detach();
        $student->delete();

        return redirect()->route('admin.students.class', $classId)
            ->with('success', "Akun siswa {$name} (NISN: {$nisn}) berhasil dihapus dari Master Data.");
    }
}
