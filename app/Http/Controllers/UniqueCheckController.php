<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UniqueCheckController extends Controller
{
    /**
     * Memeriksa ketersediaan data unik untuk validasi formulir pendaftaran.
     */
    public function check(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $value = trim((string) $request->query('value'));
        $ignoreId = $request->query('ignore_id');

        $exists = false;
        $message = 'tidak bisa submit karena data ini sudah digunakan';

        if ($type === 'class_exists') {
            $grade = trim((string) $request->query('grade'));
            $majorId = trim((string) $request->query('major_id'));
            $section = trim((string) $request->query('section'));

            if (!empty($grade) && !empty($majorId) && !empty($section)) {
                $query = SchoolClass::where('grade', $grade)
                    ->where('major_id', $majorId)
                    ->where('section', $section);

                if (!empty($ignoreId)) {
                    $query->where('id', '!=', $ignoreId);
                }

                $exists = $query->exists();
            }
        } elseif (!empty($value)) {
            switch ($type) {
                case 'student_nisn':
                    $query = Student::where('identity_number', $value);
                    if (!empty($ignoreId)) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    $exists = $query->exists();
                    break;

                case 'teacher_nip':
                    $query = Teacher::where('identity_number', $value);
                    if (!empty($ignoreId)) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    $exists = $query->exists();
                    break;

                case 'teacher_email':
                    $valueLower = strtolower($value);
                    $existsInTeacher = Teacher::where('email', $valueLower)
                        ->when(!empty($ignoreId), fn($q) => $q->where('id', '!=', $ignoreId))
                        ->exists();
                    $existsInAdmin = Admin::where('email', $valueLower)->exists();
                    $exists = $existsInTeacher || $existsInAdmin;
                    break;

                case 'admin_nip':
                    $query = Admin::where('identity_number', $value);
                    if (!empty($ignoreId)) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    $exists = $query->exists();
                    break;

                case 'admin_email':
                    $valueLower = strtolower($value);
                    $existsInAdmin = Admin::where('email', $valueLower)
                        ->when(!empty($ignoreId), fn($q) => $q->where('id', '!=', $ignoreId))
                        ->exists();
                    $existsInTeacher = Teacher::where('email', $valueLower)->exists();
                    $exists = $existsInAdmin || $existsInTeacher;
                    break;

                case 'subject_code':
                    $query = Subject::where('code', strtoupper($value));
                    if (!empty($ignoreId)) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    $exists = $query->exists();
                    break;

                case 'subject_name':
                    $query = Subject::where('name', $value);
                    if (!empty($ignoreId)) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    $exists = $query->exists();
                    break;

                case 'major_code':
                    $query = Major::where('code', strtoupper($value));
                    if (!empty($ignoreId)) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    $exists = $query->exists();
                    break;

                case 'major_name':
                    $query = Major::where('name', $value);
                    if (!empty($ignoreId)) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    $exists = $query->exists();
                    break;

                default:
                    return response()->json([
                        'error' => 'Tipe validasi tidak didukung.',
                    ], 400);
            }
        }

        return response()->json([
            'exists'  => $exists,
            'message' => $exists ? $message : '',
        ]);
    }
}
