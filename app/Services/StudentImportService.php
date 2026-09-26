<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentImportService
{
    /**
     * Menghasilkan file template Excel (.xlsx) dengan panduan dan daftar referensi kode kelas.
     *
     * @return string Binary spreadsheet data
     */
    public function generateTemplate(): string
    {
        $spreadsheet = new Spreadsheet();

        // ══════════════════════════════════════════════════════════════
        // SHEET 1: TEMPLATE PENGISIAN SISWA
        // ══════════════════════════════════════════════════════════════
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Data Siswa');

        // Judul & Banner
        $sheet1->mergeCells('A1:D1');
        $sheet1->setCellValue('A1', 'FORMAT IMPORT DATA PESERTA DIDIK — SMKN 3 YOGYAKARTA');
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E293B'));
        $sheet1->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet1->mergeCells('A2:D2');
        $sheet1->setCellValue('A2', 'Catatan: Kode Kelas dapat dilihat pada Sheet "Referensi Kelas". Kolom Password bersifat opsional (default: password).');
        $sheet1->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        // Table Header di Baris 4
        $headers = [
            'A4' => 'NISN / No. Induk',
            'B4' => 'Nama Lengkap Siswa',
            'C4' => 'Kode Kelas',
            'D4' => 'Password (Opsional)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet1->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 10,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'CBD5E1'],
                ],
            ],
        ];
        $sheet1->getStyle('A4:D4')->applyFromArray($headerStyle);
        $sheet1->getRowDimension(4)->setRowHeight(26);

        // Ambil sampel kelas pertama untuk contoh
        $firstClass = SchoolClass::with('major')->first();
        $sampleCode = $firstClass ? $firstClass->code : 'A8X2P9';

        // Baris Sampel Data
        $sampleData = [
            [
                'nisn'     => '0081234567',
                'name'     => 'Budi Santoso',
                'code'     => $sampleCode,
                'password' => 'siswa123',
            ],
            [
                'nisn'     => '0081234568',
                'name'     => 'Siti Rahmawati',
                'code'     => $sampleCode,
                'password' => '', // Kosong = default 'password'
            ],
        ];

        $row = 5;
        foreach ($sampleData as $data) {
            $sheet1->setCellValueExplicit('A' . $row, $data['nisn'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet1->setCellValue('B' . $row, $data['name']);
            $sheet1->setCellValueExplicit('C' . $row, $data['code'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet1->setCellValue('D' . $row, $data['password']);

            $sheet1->getStyle("A{$row}:D{$row}")->applyFromArray([
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ]);
            $sheet1->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        // Auto-width sheet 1
        foreach (['A', 'B', 'C', 'D'] as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // ══════════════════════════════════════════════════════════════
        // SHEET 2: REFERENSI ROMBEL & KODE KELAS
        // ══════════════════════════════════════════════════════════════
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Referensi Kelas');

        $sheet2->mergeCells('A1:E1');
        $sheet2->setCellValue('A1', 'DAFTAR KODE KELAS RESMI SMKN 3 YOGYAKARTA');
        $sheet2->getStyle('A1')->getFont()->setBold(true)->setSize(12)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E293B'));

        $sheet2->mergeCells('A2:E2');
        $sheet2->setCellValue('A2', 'Salin Kode Kelas di bawah ke Kolom "Kode Kelas" pada Sheet "Data Siswa".');
        $sheet2->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        $sheet2Headers = [
            'A4' => 'Kode Kelas (Salin Ini)',
            'B4' => 'Tingkat',
            'C4' => 'Jurusan / Keahlian',
            'D4' => 'Rombel',
            'E4' => 'Nama Rombel Lengkap',
        ];

        foreach ($sheet2Headers as $cell => $text) {
            $sheet2->setCellValue($cell, $text);
        }

        $sheet2HeaderStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 10,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A'], // Slate-900
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'CBD5E1'],
                ],
            ],
        ];
        $sheet2->getStyle('A4:E4')->applyFromArray($sheet2HeaderStyle);
        $sheet2->getRowDimension(4)->setRowHeight(24);

        $classes = SchoolClass::with('major')->orderBy('grade')->orderBy('major_id')->orderBy('section')->get();
        $cRow = 5;
        foreach ($classes as $c) {
            $sheet2->setCellValueExplicit('A' . $cRow, $c->code, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet2->setCellValue('B' . $cRow, $c->grade);
            $sheet2->setCellValue('C' . $cRow, $c->major ? $c->major->name : $c->major_name);
            $sheet2->setCellValue('D' . $cRow, $c->section);
            $sheet2->setCellValue('E' . $cRow, $c->full_name);

            $sheet2->getStyle("A{$cRow}:E{$cRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ]);
            $sheet2->getStyle("A{$cRow}")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF4F46E5'));
            $sheet2->getStyle("A{$cRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle("B{$cRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle("D{$cRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $cRow++;
        }

        foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // Set active sheet kembali ke Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }

    /**
     * Memproses file upload Excel / CSV dan membuat data siswa beserta sinkronisasi mapel kelas.
     *
     * @param UploadedFile $file
     * @param int|null $defaultClassId Jika import dipicu dari halaman kelas tertentu
     * @return array Hasil ringkasan import
     */
    public function import(UploadedFile $file, ?int $defaultClassId = null): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $importedCount = 0;
        $errors = [];
        $processedNisns = [];
        $classesBreakdown = [];

        // Cache rombel kelas untuk pencarian cepat
        $classesByCode = SchoolClass::with(['major', 'modules'])->get()->keyBy(fn($c) => strtoupper(trim($c->code)));
        $classesById = SchoolClass::with(['major', 'modules'])->get()->keyBy('id');

        $defaultClass = $defaultClassId ? $classesById->get($defaultClassId) : null;

        // Cari baris awal data (biasanya baris 5, atau cari baris setelah header 'nisn')
        $startRow = 5;
        foreach ($rows as $rowIndex => $cols) {
            $colA = strtolower(trim((string)($cols['A'] ?? '')));
            if (str_contains($colA, 'nisn') || str_contains($colA, 'nomor induk')) {
                $startRow = $rowIndex + 1;
                break;
            }
        }

        foreach ($rows as $rowIndex => $cols) {
            if ($rowIndex < $startRow) {
                continue;
            }

            $rawNisn = trim((string)($cols['A'] ?? ''));
            $rawName = trim((string)($cols['B'] ?? ''));
            $rawClass = trim((string)($cols['C'] ?? ''));
            $rawPass = trim((string)($cols['D'] ?? ''));

            // Abaikan jika seluruh kolom kosong
            if ($rawNisn === '' && $rawName === '' && $rawClass === '') {
                continue;
            }

            // 1. Validasi NISN
            if ($rawNisn === '') {
                $errors[] = [
                    'row'    => $rowIndex,
                    'nisn'   => '-',
                    'name'   => $rawName ?: '-',
                    'reason' => 'NISN tidak boleh kosong.',
                ];
                continue;
            }

            if (in_array($rawNisn, $processedNisns)) {
                $errors[] = [
                    'row'    => $rowIndex,
                    'nisn'   => $rawNisn,
                    'name'   => $rawName,
                    'reason' => "NISN {$rawNisn} duplikat di dalam file import ini.",
                ];
                continue;
            }

            if (Student::where('identity_number', $rawNisn)->exists()) {
                $existing = Student::where('identity_number', $rawNisn)->first();
                $errors[] = [
                    'row'    => $rowIndex,
                    'nisn'   => $rawNisn,
                    'name'   => $rawName,
                    'reason' => "NISN {$rawNisn} sudah terdaftar di sistem atas nama '{$existing->name}'.",
                ];
                continue;
            }

            // 2. Validasi Nama
            if ($rawName === '') {
                $errors[] = [
                    'row'    => $rowIndex,
                    'nisn'   => $rawNisn,
                    'name'   => '-',
                    'reason' => 'Nama lengkap siswa wajib diisi.',
                ];
                continue;
            }

            // 3. Menentukan Rombel Kelas
            $targetClass = null;
            if ($rawClass !== '') {
                $upperCode = strtoupper($rawClass);
                if ($classesByCode->has($upperCode)) {
                    $targetClass = $classesByCode->get($upperCode);
                } else {
                    // Cari berdasarkan short_name atau full_name
                    $targetClass = $classesById->first(function ($c) use ($rawClass) {
                        return strcasecmp($c->full_name, $rawClass) === 0
                            || strcasecmp($c->short_name, $rawClass) === 0
                            || strcasecmp("{$c->grade} {$c->major_name} {$c->section}", $rawClass) === 0;
                    });
                }
            }

            // Fallback ke default class jika disediakan
            if (!$targetClass && $defaultClass) {
                $targetClass = $defaultClass;
            }

            if (!$targetClass) {
                $errors[] = [
                    'row'    => $rowIndex,
                    'nisn'   => $rawNisn,
                    'name'   => $rawName,
                    'reason' => "Kode kelas '{$rawClass}' tidak ditemukan di sistem.",
                ];
                continue;
            }

            // 4. Password
            $password = $rawPass !== '' ? $rawPass : 'password';

            // 5. Buat Siswa & Sinkronkan ke Kelas
            $student = Student::create([
                'name'            => $rawName,
                'identity_number' => $rawNisn,
                'class_id'        => $targetClass->id,
                'password'        => Hash::make($password),
            ]);

            // Hubungkan pivot class_student & otomatis sinkron mapel kelas!
            $student->joinClass($targetClass);

            $processedNisns[] = $rawNisn;
            $importedCount++;

            $className = $targetClass->full_name;
            $classesBreakdown[$className] = ($classesBreakdown[$className] ?? 0) + 1;
        }

        return [
            'success'           => $importedCount > 0,
            'imported_count'    => $importedCount,
            'skipped_count'     => count($errors),
            'classes_breakdown' => $classesBreakdown,
            'errors'            => $errors,
        ];
    }
}
