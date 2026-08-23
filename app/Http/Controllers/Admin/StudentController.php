<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Menampilkan daftar Master Data Siswa.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $classId = $request->query('class_id');

        $query = Student::with('schoolClass')
            ->withCount(['studentResults', 'jobSheetSubmissions', 'lkpdSubmissions']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        if ($classId && $classId !== 'all') {
            $query->where('class_id', $classId);
        }

        $students = $query->latest('updated_at')->paginate(15)->withQueryString();

        // Data pendukung
        $classes = SchoolClass::orderBy('grade')->orderBy('major_name')->get();
        $totalStudents = Student::count();
        $totalClasses = SchoolClass::count();

        $stats = [
            'total'   => $totalStudents,
            'classes' => $totalClasses,
        ];

        return view('pages.admin.students.index', compact(
            'students',
            'classes',
            'stats',
            'search',
            'classId'
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
            'class_id'        => ['required', 'exists:classes,id'],
            'password'        => ['required', 'string', 'min:6'],
        ], [
            'name.required'            => 'Nama lengkap siswa wajib diisi.',
            'identity_number.required' => 'NISN / No. Induk Siswa wajib diisi.',
            'identity_number.unique'   => 'NISN / Identitas ini sudah terdaftar untuk siswa lain.',
            'class_id.required'        => 'Rombel Kelas wajib dipilih.',
            'class_id.exists'          => 'Kelas yang dipilih tidak valid.',
            'password.required'        => 'Password akun siswa wajib diisi.',
            'password.min'             => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $student = Student::create([
            'name'            => $validated['name'],
            'identity_number' => $validated['identity_number'],
            'class_id'        => $validated['class_id'],
            'password'        => Hash::make($validated['password']),
        ]);

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
            'class_id'        => ['required', 'exists:classes,id'],
            'password'        => ['nullable', 'string', 'min:6'],
        ], [
            'name.required'            => 'Nama lengkap siswa wajib diisi.',
            'identity_number.required' => 'NISN / Identitas wajib diisi.',
            'identity_number.unique'   => 'NISN / Identitas ini sudah terdaftar untuk siswa lain.',
            'class_id.required'        => 'Rombel Kelas wajib dipilih.',
            'password.min'             => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $student->name = $validated['name'];
        $student->identity_number = $validated['identity_number'];
        $student->class_id = $validated['class_id'];

        if (!empty($validated['password'])) {
            $student->password = Hash::make($validated['password']);
        }

        $student->save();

        return redirect()->route('admin.students.index')
            ->with('success', "Data siswa {$student->name} berhasil diperbarui.");
    }

    /**
     * Menghapus akun siswa.
     */
    public function destroy(Student $student)
    {
        $name = $student->name;
        $nisn = $student->identity_number;

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', "Akun siswa {$name} (NISN: {$nisn}) berhasil dihapus dari Master Data.");
    }
}
