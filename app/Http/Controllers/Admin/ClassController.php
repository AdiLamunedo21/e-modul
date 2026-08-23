<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    /**
     * Menampilkan daftar Master Data Kelas & Rombel.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $grade = $request->query('grade');
        $majorId = $request->query('major_id');

        $query = SchoolClass::with('major')->withCount(['students', 'modules']);

        if ($grade && $grade !== 'all') {
            $query->where('grade', $grade);
        }

        if ($majorId && $majorId !== 'all') {
            $query->where('major_id', $majorId);
        }

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

        $classes = $query->orderBy('grade')->orderBy('major_id')->orderBy('section')->paginate(12)->withQueryString();

        $majors = Major::orderBy('name')->get();

        $stats = [
            'total'          => SchoolClass::count(),
            'total_students' => \App\Models\Student::count(),
            'grade_x'        => SchoolClass::where('grade', 'X')->count(),
            'grade_xi'       => SchoolClass::where('grade', 'XI')->count(),
            'grade_xii'      => SchoolClass::where('grade', 'XII')->count(),
        ];

        return view('pages.admin.classes.index', compact('classes', 'majors', 'stats', 'search', 'grade', 'majorId'));
    }

    /**
     * Menyimpan rombongan belajar kelas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade'    => ['required', 'string', Rule::in(['X', 'XI', 'XII', 'XIII'])],
            'major_id' => ['required', 'exists:majors,id'],
            'section'  => ['required', 'string', 'max:10'],
        ], [
            'grade.required'    => 'Tingkat kelas wajib dipilih.',
            'major_id.required' => 'Jurusan / Konsentrasi Keahlian wajib dipilih.',
            'major_id.exists'   => 'Jurusan yang dipilih tidak valid.',
            'section.required'  => 'Nomor / Nama Rombel (Pararel) wajib diisi.',
        ]);

        $major = Major::findOrFail($validated['major_id']);

        $schoolClass = SchoolClass::create([
            'grade'      => $validated['grade'],
            'major_id'   => $major->id,
            'section'    => $validated['section'],
            'major_name' => $major->code,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', "Rombel {$schoolClass->full_name} berhasil ditambahkan ke Master Data.");
    }

    /**
     * Memperbarui data kelas rombel.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'grade'    => ['required', 'string', Rule::in(['X', 'XI', 'XII', 'XIII'])],
            'major_id' => ['required', 'exists:majors,id'],
            'section'  => ['required', 'string', 'max:10'],
        ], [
            'grade.required'    => 'Tingkat kelas wajib dipilih.',
            'major_id.required' => 'Jurusan / Konsentrasi Keahlian wajib dipilih.',
            'major_id.exists'   => 'Jurusan yang dipilih tidak valid.',
            'section.required'  => 'Nomor / Nama Rombel (Pararel) wajib diisi.',
        ]);

        $major = Major::findOrFail($validated['major_id']);

        $class->update([
            'grade'      => $validated['grade'],
            'major_id'   => $major->id,
            'section'    => $validated['section'],
            'major_name' => $major->code,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', "Data {$class->full_name} berhasil diperbarui.");
    }

    /**
     * Menghapus kelas rombel.
     */
    public function destroy(SchoolClass $class)
    {
        $name = $class->full_name;

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', "Rombel {$name} berhasil dihapus dari Master Data.");
    }
}
