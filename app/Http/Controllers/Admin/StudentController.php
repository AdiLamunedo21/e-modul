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
        $classSubjects = $class->getAvailableSubjects();

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
            'classSubjects',
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

        if ($student->class_id) {
            $schoolClass = SchoolClass::find($student->class_id);
            if ($schoolClass) {
                $student->classes()->syncWithoutDetaching([$schoolClass->id]);
                if (!empty($validated['subject_ids'])) {
                    $student->subjects()->sync($validated['subject_ids']);
                } else {
                    $student->syncSubjectsFromClass($schoolClass);
                }
            }
        } elseif (!empty($validated['subject_ids'])) {
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

        $previousClassId = $student->class_id;
        $student->name = $validated['name'];
        $student->identity_number = $validated['identity_number'];
        $student->class_id = $validated['class_id'] ?? null;

        if (!empty($validated['password'])) {
            $student->password = Hash::make($validated['password']);
        }

        $student->save();

        if ($student->class_id) {
            $schoolClass = SchoolClass::find($student->class_id);
            if ($schoolClass) {
                $student->classes()->syncWithoutDetaching([$schoolClass->id]);
                if (isset($validated['subject_ids']) && !empty($validated['subject_ids'])) {
                    $student->subjects()->sync($validated['subject_ids']);
                } else {
                    $student->syncSubjectsFromClass($schoolClass);
                }
            }
        } elseif (isset($validated['subject_ids'])) {
            $student->subjects()->sync($validated['subject_ids']);
        }

        return redirect()->route('admin.students.class', $student->class_id)
            ->with('success', "Data siswa {$student->name} berhasil diperbarui.");
    }

    /**
     * Menyinkronkan seluruh siswa di suatu kelas dengan mata pelajaran aktif kelas tersebut secara massal (1-klik).
     */
    public function syncClassSubjects(Request $request, SchoolClass $class)
    {
        $syncedCount = $class->syncAllStudentsSubjects();

        if ($syncedCount === 0) {
            return redirect()->route('admin.students.class', $class->id)
                ->with('info', "Belum ada siswa terdaftar atau belum ada mata pelajaran di {$class->full_name} untuk disinkronkan.");
        }

        return redirect()->route('admin.students.class', $class->id)
            ->with('success', "Berhasil menyinkronkan seluruh mata pelajaran untuk {$syncedCount} siswa di {$class->full_name}!");
    }

    /**
     * Mengunduh file template Excel (.xlsx) untuk import massal data siswa.
     */
    public function downloadImportTemplate(\App\Services\StudentImportService $importService)
    {
        $binary = $importService->generateTemplate();
        $filename = 'Template_Import_Siswa_SMKN3.xlsx';

        return response()->streamDownload(function () use ($binary) {
            echo $binary;
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Memproses import massal data siswa dari file Excel / CSV.
     */
    public function import(Request $request, \App\Services\StudentImportService $importService)
    {
        $request->validate([
            'file'     => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'],
            'class_id' => ['nullable', 'exists:classes,id'],
        ], [
            'file.required' => 'File spreadsheet Excel / CSV wajib diunggah.',
            'file.mimes'    => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max'      => 'Ukuran file maksimal adalah 10 MB.',
        ]);

        $result = $importService->import($request->file('file'), $request->class_id);

        if ($result['imported_count'] === 0 && $result['skipped_count'] === 0) {
            return back()->with('info', 'File tidak memuat baris data siswa yang valid.');
        }

        $message = "Berhasil mengimpor {$result['imported_count']} data siswa!";
        if (!empty($result['classes_breakdown'])) {
            $breakdownStr = collect($result['classes_breakdown'])
                ->map(fn($count, $cls) => "{$cls}: {$count} siswa")
                ->implode(', ');
            $message .= " ({$breakdownStr})";
        }

        $redirect = $request->class_id
            ? redirect()->route('admin.students.class', $request->class_id)
            : redirect()->route('admin.students.index');

        if ($result['skipped_count'] > 0) {
            return $redirect
                ->with('success', $message)
                ->with('import_errors', $result['errors']);
        }

        return $redirect->with('success', $message);
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

