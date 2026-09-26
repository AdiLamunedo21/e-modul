<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GraduationReportService
{
    /**
     * Menghasilkan file Excel (.xlsx) rekapitulasi nilai dan status kelulusan siswa kelas XII.
     *
     * @param SchoolClass|null $class Kelas XII tertentu (jika null, seluruh kelas XII)
     * @return string Binary spreadsheet data
     */
    public function generateReport(?SchoolClass $class = null): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Kelulusan');

        // Judul Header
        $className = $class ? $class->full_name : 'SELURUH KELAS XII';
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', "REKAPITULASI KELULUSAN & NILAI AKADEMIK SISWA — {$className}");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF1E293B'));
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'SMKN 3 YOGYAKARTA — Dicetak pada: ' . now()->translatedFormat('d F Y, H:i'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        // Table Header di Baris 4
        $headers = [
            'A4' => 'No.',
            'B4' => 'NISN',
            'C4' => 'Nama Lengkap Siswa',
            'D4' => 'Kelas Rombel',
            'E4' => 'Program Keahlian',
            'F4' => 'Modul Diikuti',
            'G4' => 'Rata-rata Kuis',
            'H4' => 'Rata-rata Tugas',
            'I4' => 'Nilai Akhir',
            'J4' => 'Status Kelulusan',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'], // Slate-800
            ],
        ];
        $sheet->getStyle('A4:J4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // Ambil data siswa
        $query = Student::with(['schoolClass.major', 'studentResults', 'submissions', 'jobSheetSubmissions']);
        if ($class) {
            $query->where('class_id', $class->id);
        } else {
            $query->whereHas('schoolClass', function ($q) {
                $q->where('grade', 'XII');
            });
        }

        $students = $query->orderBy('name')->get();

        $row = 5;
        $no = 1;

        foreach ($students as $student) {
            // Hitung nilai kuis & tugas
            $results = $student->studentResults;
            $modulesCount = $results->count();

            $quizScores = [];
            foreach ($results as $res) {
                if ($res->post_test_score !== null) {
                    $quizScores[] = $res->post_test_score;
                } elseif ($res->pre_test_score !== null) {
                    $quizScores[] = $res->pre_test_score;
                }
            }
            $avgQuiz = count($quizScores) > 0 ? round(array_sum($quizScores) / count($quizScores), 1) : 0;

            $taskScores = [];
            foreach ($results as $res) {
                if ($res->summative_score !== null) {
                    $taskScores[] = $res->summative_score;
                }
            }
            $avgTask = count($taskScores) > 0 ? round(array_sum($taskScores) / count($taskScores), 1) : 0;

            // Nilai akhir rata-rata (jika ada nilai kuis dan tugas)
            $finalScore = ($avgQuiz > 0 || $avgTask > 0)
                ? round(($avgQuiz * 0.4) + ($avgTask * 0.6), 1)
                : 0;

            $sheet->setCellValue("A{$row}", $no);
            $sheet->setCellValueExplicit("B{$row}", (string) $student->identity_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("C{$row}", $student->name);
            $sheet->setCellValue("D{$row}", $student->schoolClass ? $student->schoolClass->short_name : '-');
            $sheet->setCellValue("E{$row}", $student->schoolClass && $student->schoolClass->major ? $student->schoolClass->major->name : ($student->schoolClass->major_name ?? '-'));
            $sheet->setCellValue("F{$row}", $modulesCount . ' Modul');
            $sheet->setCellValue("G{$row}", $avgQuiz);
            $sheet->setCellValue("H{$row}", $avgTask);
            $sheet->setCellValue("I{$row}", $finalScore);
            $sheet->setCellValue("J{$row}", 'LULUS');

            // Zebra striping
            if ($no % 2 === 0) {
                $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            // Alignments
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$row}:J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Warna hijau status lulus
            $sheet->getStyle("J{$row}")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF15803D'));

            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;
            $no++;
        }

        $lastRow = max(5, $row - 1);

        // Border styling
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
        $sheet->getStyle("A4:J{$lastRow}")->applyFromArray($borderStyle);

        // Auto-fit column widths
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Simpan ke binary buffer
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }
}
