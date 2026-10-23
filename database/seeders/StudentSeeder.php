<?php

namespace Database\Seeders;

use App\Models\EmbedSubmission;
use App\Models\JobSheetSubmission;
use App\Models\Major;
use App\Models\Module;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\Teacher;
use App\Models\VideoSummary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ── 1. IDENTIFIKASI GURU ADI CHANDRA WINATA ───────────────────────────
        $teacher = Teacher::where('identity_number', 'Nim25105260007')
            ->orWhere('email', 'adikun879@gmail.com')
            ->orWhere('name', 'like', '%Adi Chandra%')
            ->first();

        if (!$teacher) {
            $teacher = Teacher::create([
                'name'            => 'Adi Chandra W PPG',
                'identity_number' => 'Nim25105260007',
                'email'           => 'adikun879@gmail.com',
                'password'        => Hash::make('rima4693'),
            ]);
        }

        // Pastikan Mapel Informatika tersinkron ke guru
        $subjectInf = Subject::where('code', 'INF')->first() ?? Subject::first();
        if ($subjectInf && $teacher) {
            $teacher->subjects()->syncWithoutDetaching([$subjectInf->id]);
        }

        // ── 2. IDENTIFIKASI ATAU BUAT KELAS X TL 3 & X TO 2 ────────────────────
        $majorTl = Major::where('code', 'TL')->first();
        $classTl3 = SchoolClass::where('grade', 'X')
            ->where(function ($q) use ($majorTl) {
                $q->where('major_name', 'TL');
                if ($majorTl) {
                    $q->orWhere('major_id', $majorTl->id);
                }
            })
            ->where('section', '3')
            ->first();

        if (!$classTl3) {
            $classTl3 = SchoolClass::create([
                'major_id'   => $majorTl?->id,
                'grade'      => 'X',
                'section'    => '3',
                'major_name' => 'TL',
            ]);
        }

        $majorTo = Major::where('code', 'TO')->first();
        $classTo2 = SchoolClass::where('grade', 'X')
            ->where(function ($q) use ($majorTo) {
                $q->where('major_name', 'TO');
                if ($majorTo) {
                    $q->orWhere('major_id', $majorTo->id);
                }
            })
            ->where('section', '2')
            ->first();

        if (!$classTo2) {
            $classTo2 = SchoolClass::create([
                'major_id'   => $majorTo?->id,
                'grade'      => 'X',
                'section'    => '2',
                'major_name' => 'TO',
            ]);
        }

        // Hubungkan kedua kelas ini ke Guru Adi Chandra Winata
        $teacher->classes()->syncWithoutDetaching([$classTl3->id, $classTo2->id]);

        // ── 3. DATA RESMI SISWA KELAS X TL 3 ──────────────────────────────────
        $studentsTl3 = [
            ['no' => 1,  'nisn' => '0117252156', 'name' => 'ADEEVA NADINE DZAKIYAH'],
            ['no' => 2,  'nisn' => '3104038634', 'name' => 'ADILUHUNG SAKHA AL-FARISI'],
            ['no' => 3,  'nisn' => '0104144931', 'name' => 'AGIL GILANG PUTRA ATMAJA'],
            ['no' => 4,  'nisn' => '0105134433', 'name' => 'AGIL PRATIWI'],
            ['no' => 5,  'nisn' => '0115078014', 'name' => 'AMADEO VINCENTIO SUTANTO'],
            ['no' => 6,  'nisn' => '0106362129', 'name' => 'ANINDIA MAHES WARI'],
            ['no' => 7,  'nisn' => '0114470079', 'name' => 'ARLIN AULIA'],
            ['no' => 8,  'nisn' => '0109182217', 'name' => 'B. GYAN GAVRILA SESA'],
            ['no' => 9,  'nisn' => '0105633440', 'name' => 'BIMO SURYA ADIYATMA'],
            ['no' => 10, 'nisn' => '0106902702', 'name' => 'BIMO WAHYU PAMUNGKAS'],
            ['no' => 11, 'nisn' => '0114324088', 'name' => 'UTAMA'],
            ['no' => 12, 'nisn' => '0108764356', 'name' => 'DANENDRA BIMA APRIYUDHA'],
            ['no' => 13, 'nisn' => '0108393274', 'name' => 'DANISWARA ADITYA RAMANDIKA'],
            ['no' => 14, 'nisn' => '0115972467', 'name' => 'DENAYA LINTANG AYU'],
            ['no' => 15, 'nisn' => '0103195232', 'name' => 'FATHUR RIZIEQ'],
            ['no' => 16, 'nisn' => '0099904974', 'name' => 'FAWWAS ABRAR'],
            ['no' => 17, 'nisn' => '0116118049', 'name' => 'FEBRIAN RIZKI PUTRA PRATAMA'],
            ['no' => 18, 'nisn' => '0106334571', 'name' => 'FERDINANDUS DIMAS KAZIO'],
            ['no' => 19, 'nisn' => '0102862772', 'name' => 'FERDY TRI BYANTARA'],
            ['no' => 20, 'nisn' => '0108229449', 'name' => 'GALIH PANGESTU'],
            ['no' => 21, 'nisn' => '0119338585', 'name' => 'HAFIDZ SETYA ARIFIN FIRDAUS'],
            ['no' => 22, 'nisn' => '0102207008', 'name' => 'REMINDAUW'],
            ['no' => 23, 'nisn' => '0106078170', 'name' => 'JOANNES SETA BINTANG NUGRAHA'],
            ['no' => 24, 'nisn' => '0118614788', 'name' => 'KAYLA ISTI NURHANIFAH'],
            ['no' => 25, 'nisn' => '0102480293', 'name' => 'MIKAEL RAPA PERIRA'],
            ['no' => 26, 'nisn' => '0117172490', 'name' => 'NICOLAUS MARCELLINO CONDRO DWI SASONGKO'],
            ['no' => 27, 'nisn' => '0105046696', 'name' => 'RAFAEL DEVANO CHRISTIAN PRADITYA'],
            ['no' => 28, 'nisn' => '0112288280', 'name' => 'RAIHANA NABILA ANDJAZAHRA'],
            ['no' => 29, 'nisn' => '0101542255', 'name' => 'RIKHA DWI FATMASARI'],
            ['no' => 30, 'nisn' => '0105857399', 'name' => 'SANDY BAGUS HARYONO'],
            ['no' => 31, 'nisn' => '0105043219', 'name' => 'SATRIO WICAKSONO'],
            ['no' => 32, 'nisn' => '0104699960', 'name' => 'SEVOKTA YOGA PRASETYA'],
            ['no' => 33, 'nisn' => '3108503168', 'name' => 'VICKO REHANDHIKA REFAREL'],
            ['no' => 34, 'nisn' => '3108374469', 'name' => 'WAKHID PUTRA RAMDHANI'],
            ['no' => 35, 'nisn' => '0105131165', 'name' => 'WILDAN NUR YAFFI'],
            ['no' => 36, 'nisn' => '0102817167', 'name' => 'ZACKY RIZKY MAULANA'],
        ];

        // ── 4. DATA RESMI SISWA KELAS X TO 2 ──────────────────────────────────
        $studentsTo2 = [
            ['no' => 1,  'nisn' => '0109049076', 'name' => 'JAYA HADI KUSUMA WINARNO'],
            ['no' => 2,  'nisn' => '0114425473', 'name' => 'JOVINNO FABIAN HASYA ATMAJA'],
            ['no' => 3,  'nisn' => '0109066945', 'name' => 'LUCKY SATRIA PRATAMA'],
            ['no' => 4,  'nisn' => '0103267359', 'name' => 'MANGGALA AKBAR MATARAM'],
            ['no' => 5,  'nisn' => '0101416491', 'name' => 'MAULANA RIFAT SAPUTRA'],
            ['no' => 6,  'nisn' => '0101875044', 'name' => 'MUHAMMAD ADNAN ROBI VIQULBI'],
            ['no' => 7,  'nisn' => '0102781550', 'name' => 'MUHAMMAD ADZKA ZUHDI NAUFAL'],
            ['no' => 8,  'nisn' => '0114663933', 'name' => 'MUHAMMAD AFFISNAN GILANG ARIPUTRA'],
            ['no' => 9,  'nisn' => '0104560412', 'name' => 'MUHAMMAD AFIFF FIRDAWS'],
            ['no' => 10, 'nisn' => '0109817371', 'name' => 'MUHAMMAD AGUS ZAINUR ROSYID'],
            ['no' => 11, 'nisn' => '0106932987', 'name' => 'MUHAMMAD AHSAN ARWANI'],
            ['no' => 12, 'nisn' => '0111814362', 'name' => 'MUHAMMAD FADHIL GESFITO'],
            ['no' => 13, 'nisn' => '0094563064', 'name' => 'MUHAMMAD FAREL BUDI PURNOMO'],
            ['no' => 14, 'nisn' => '0104493692', 'name' => 'MUHAMMAD FATHI FADHLULLAH'],
            ['no' => 15, 'nisn' => '0108678764', 'name' => 'MUHAMMAD HAFIZ RIDHO PRIANSYAH'],
            ['no' => 16, 'nisn' => '0109745047', 'name' => 'MUHAMMAD LINGGA ALFARRABI'],
            ['no' => 17, 'nisn' => '0087077219', 'name' => 'MUHAMMAD RAFLI ABDUL AZIZ'],
            ['no' => 18, 'nisn' => '0093524212', 'name' => 'MUHAMMAD RICO ATHA WICAKSONO'],
            ['no' => 19, 'nisn' => '0103246848', 'name' => 'MUHAMMAD RIDWAN ABDULKHALIM'],
            ['no' => 20, 'nisn' => '0115341386', 'name' => 'MUHAMMAD RIZKY PUTRA PRATAMA'],
            ['no' => 21, 'nisn' => '0105877154', 'name' => 'MUHAMMAD SULTHON KHOLILULLOH ESA PUTRA'],
            ['no' => 22, 'nisn' => '0117940664', 'name' => "MUHAMMAD ZHAFRON ARIA' LIE PUTRA"],
            ['no' => 23, 'nisn' => '0109619475', 'name' => 'NAUFAL DWI ARDIANSYAH'],
            ['no' => 24, 'nisn' => '0107324725', 'name' => 'NAUFAL WAHYU ARYADI ATHARULLAH'],
            ['no' => 25, 'nisn' => '0108838955', 'name' => 'NAUVENDI BAGUS PRATAMA'],
            ['no' => 26, 'nisn' => '0117636340', 'name' => 'NAVAS ALLSYAF KAFARO HARPAR'],
            ['no' => 27, 'nisn' => '0103136386', 'name' => 'NOVEM FADHIL ROHMATULLAH'],
            ['no' => 28, 'nisn' => '0108410336', 'name' => 'NUR DWI WICAKSONO'],
            ['no' => 29, 'nisn' => '0107928858', 'name' => 'PANDU WICAKSONO'],
            ['no' => 30, 'nisn' => '0108273120', 'name' => 'PANJI NUR ARIFIN'],
            ['no' => 31, 'nisn' => '0102688864', 'name' => 'PASHA FAHRI RAMADHAN'],
            ['no' => 32, 'nisn' => '0103972720', 'name' => 'PASHA HABIL AGUSTI RIHANTORO'],
            ['no' => 33, 'nisn' => '0115060621', 'name' => 'PRADHITA RICKY PUTRA'],
            ['no' => 34, 'nisn' => '0109557565', 'name' => 'RADIT SANJAYA PRASETYO'],
            ['no' => 35, 'nisn' => '0102178086', 'name' => 'RADITHYA YUDISTIRA GUNAWAN'],
            ['no' => 36, 'nisn' => '0103118044', 'name' => 'RAFAEL LINTANG PRATAMA'],
        ];

        $studentPassword = Hash::make('password');

        // Helper closure untuk memasukkan data siswa jika belum ada
        $insertStudents = function (array $studentsList, SchoolClass $schoolClass) use ($studentPassword): array {
            $inserted = [];

            foreach ($studentsList as $data) {
                $cleanName = trim($data['name']);
                $cleanNisn = trim((string) $data['nisn']);

                // Sesuai instruksi: "jangan lupa untuk jangan menambahkan siswa yang namaya sudah ada di database"
                // Periksa apakah nama sudah ada (case-insensitive & trimmed)
                $existsByName = Student::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($cleanName)])->exists();

                // Periksa juga berdasarkan NISN (identity_number)
                $existsByNisn = Student::where('identity_number', $cleanNisn)->exists();

                if ($existsByName || $existsByNisn) {
                    // Jangan ubah data yang sudah ada, jangan tambahkan duplikat
                    continue;
                }

                // Buat data siswa baru dengan password default 'password'
                $student = Student::create([
                    'name'            => $cleanName,
                    'identity_number' => $cleanNisn,
                    'class_id'        => $schoolClass->id,
                    'password'        => $studentPassword,
                ]);

                // Daftarkan siswa ke rombel kelas di pivot class_student
                $student->classes()->syncWithoutDetaching([$schoolClass->id]);

                // Sinkronkan mata pelajaran
                $student->syncSubjectsFromClass($schoolClass);

                $inserted[] = $student;
            }

            return $inserted;
        };

        $insertedTl3 = $insertStudents($studentsTl3, $classTl3);
        $insertedTo2 = $insertStudents($studentsTo2, $classTo2);

        // ── 5. SKENARIO NILAI RANDOM ──────────────────────────────────────────
        // Ambil modul-modul guru Adi Chandra atau yang ditugaskan ke kelas X TL 3 & X TO 2
        $modules = Module::where(function ($q) use ($teacher, $classTl3, $classTo2) {
            if ($teacher) {
                $q->where('teacher_id', $teacher->id);
            }
            $q->orWhereIn('class_id', array_filter([$classTl3?->id, $classTo2?->id]));
        })->get();

        // Jika modul spesifik belum ada, cari modul yang terbit di sistem
        if ($modules->isEmpty()) {
            $modules = Module::where('status', 'published')->get();
        }

        // Ambil seluruh siswa dari kedua kelas ini (prioritas yang baru dimasukkan atau belum memiliki nilai)
        $allTargetStudents = Student::whereIn('id', array_merge(
            array_map(fn($s) => $s->id, $insertedTl3),
            array_map(fn($s) => $s->id, $insertedTo2),
            $classTl3->students()->pluck('students.id')->toArray(),
            $classTo2->students()->pluck('students.id')->toArray()
        ))->get()->unique('id');

        foreach ($modules as $module) {
            foreach ($allTargetStudents as $student) {
                // Sesuai instruksi: "jangan ubah yang sudah ada"
                $existingResult = StudentResult::where('student_id', $student->id)
                    ->where('module_id', $module->id)
                    ->first();

                if ($existingResult) {
                    continue; // Lewati jika sudah ada nilai sebelumnya
                }

                // Hasilkan skenario nilai random yang realistis dan proporsional (rentang 72 - 98)
                $preScore = rand(70, 95);
                $videoScore = $module->has_video ? rand(75, 96) : null;
                $embedScore = $module->has_embed ? rand(78, 98) : null;
                $jobSheetScore = $module->has_job_sheet ? rand(75, 95) : null;
                $lkpdScore = $module->has_lkpd ? rand(78, 96) : null;
                $postScore = $module->has_post_test ? rand(75, 100) : null;

                // Komponen penilaian sumatif akhir
                $gradedScores = array_filter([$videoScore, $embedScore, $jobSheetScore, $lkpdScore, $postScore], fn($s) => $s !== null);
                $summative = !empty($gradedScores)
                    ? (int) round(array_sum($gradedScores) / count($gradedScores))
                    : rand(75, 95);

                $readComponents = ['informasi_umum', 'materi'];
                if ($module->has_video) $readComponents[] = 'video';
                if ($module->has_embed) $readComponents[] = 'embed';
                if ($module->has_job_sheet) $readComponents[] = 'job_sheet';
                if ($module->has_lkpd) $readComponents[] = 'lkpd';
                if ($module->has_post_test) $readComponents[] = 'post_test';

                $testAttempts = [];
                if ($preScore !== null) {
                    $testAttempts['pre_test'] = [
                        [
                            'attempt'       => 1,
                            'score'         => $preScore,
                            'correct_count' => (int) round(($preScore / 100) * 15),
                            'total'         => 15,
                            'timestamp'     => now()->subDays(rand(3, 7))->toIso8601String(),
                            'is_initial'    => true,
                        ]
                    ];
                }
                if ($postScore !== null) {
                    $testAttempts['post_test'] = [
                        [
                            'attempt'       => 1,
                            'score'         => $postScore,
                            'correct_count' => (int) round(($postScore / 100) * 15),
                            'total'         => 15,
                            'timestamp'     => now()->subDays(rand(0, 2))->toIso8601String(),
                            'is_initial'    => true,
                        ]
                    ];
                }

                StudentResult::create([
                    'student_id'      => $student->id,
                    'module_id'       => $module->id,
                    'pre_test_score'  => $preScore,
                    'video_score'     => $videoScore,
                    'embed_score'     => $embedScore,
                    'job_sheet_score' => $jobSheetScore,
                    'lkpd_score'      => $lkpdScore,
                    'post_test_score' => $postScore,
                    'summative_score' => $summative,
                    'grading_status'  => 'graded',
                    'read_components' => $readComponents,
                    'test_attempts'   => $testAttempts,
                ]);

                // Tambahkan submission contoh jika komponen modul bersangkutan aktif
                if ($module->has_video) {
                    VideoSummary::firstOrCreate(
                        ['module_id' => $module->id, 'student_id' => $student->id],
                        [
                            'summary_text' => 'Ringkasan materi video: siswa telah menyimak materi pembelajaran secara saksama dan memahami konsep dasarnya.',
                            'manual_score' => $videoScore,
                        ]
                    );
                }

                if ($module->has_embed) {
                    EmbedSubmission::firstOrCreate(
                        ['module_id' => $module->id, 'student_id' => $student->id],
                        [
                            'screenshot_path' => 'embed_submissions/sample_terminal.png',
                            'manual_score'    => $embedScore,
                        ]
                    );
                }

                if ($module->has_job_sheet) {
                    $jobSheet = $module->jobSheets()->first();
                    if ($jobSheet) {
                        JobSheetSubmission::firstOrCreate(
                            ['job_sheet_id' => $jobSheet->id, 'student_id' => $student->id],
                            [
                                'uploaded_file_path' => 'job_sheet_submissions/laporan_praktik.pdf',
                                'manual_score'       => $jobSheetScore,
                            ]
                        );
                    }
                }

                if ($module->has_lkpd) {
                    $lkpd = $module->lkpds()->first();
                    if ($lkpd) {
                        Submission::firstOrCreate(
                            ['lkpd_id' => $lkpd->id, 'student_id' => $student->id],
                            [
                                'uploaded_file_path' => 'lkpd_submissions/jawaban_lkpd.pdf',
                                'manual_score'       => $lkpdScore,
                            ]
                        );
                    }
                }
            }
        }
    }
}
