<?php

namespace App\Exports;

use App\Models\Module;
use App\Models\Student;
use App\Models\StudentResult;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * =============================================================================
 * EXPORT CLASS: ModuleGradesExport
 * =============================================================================
 * PENGHASIL REKAPITULASI LAPORAN NILAI SPREADSHEET (.XLSX):
 * -----------------------------------------------------------------------------
 * Kelas ini bertugas menghasilkan berkas Microsoft Excel (.xlsx) siap cetak dan
 * siap olah untuk rekapitulasi nilai modul pembelajaran siswa di SMK Negeri 3 Yogyakarta.
 * 
 * FITUR ADAPTIF:
 * - Struktur kolom penilaian menyesuaikan secara dinamis dengan komponen aktif pada modul:
 *   * Pre-test (has_pre_test)
 *   * Ringkasan Video YouTube (has_video)
 *   * Praktik Interaktif Embed (has_embed)
 *   * Job Sheet Praktikum PDF (has_job_sheet)
 *   * Tugas LKPD (has_lkpd)
 *   * Post-test Akhir (has_post_test)
 * - Formula Excel otomatis untuk Nilai Rata-rata, Nilai Maksimum, Nilai Minimum, dan Ketuntasan.
 * - Format sel, penomoran NISN sebagai teks (mencegah angka nol terpotong), styling institusional,
 *   dan penyesuaian lebar kolom otomatis.
 * =============================================================================
 */
class ModuleGradesExport
{
    protected Module $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Membangun objek Spreadsheet PhpSpreadsheet yang lengkap dengan data dan format.
     */
    public function generateSpreadsheet(): Spreadsheet
    {
        $module = $this->module;
        $module->loadMissing([
            'teacher',
            'schoolClass.students',
            'studentResults',
            'videoSummaries',
            'embedSubmissions',
            'jobSheets.submissions',
            'lkpds.submissions',
        ]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Nilai');

        // Aktifkan gridlines default
        $sheet->setShowGridLines(true);

        $activeComponents = $module->activeGradedComponents();
        $students = $module->schoolClass ? $module->schoolClass->students->sortBy('name') : collect();
        $kktp = (int) ($module->postTestKktp() ?: 75);

        // ── 1. HEADER INSTITUSIONAL & METADATA ──────────────────────────────────
        // 4 Kolom Dasar (No, NISN, Nama, Kelas) + Jumlah Komponen Aktif + 3 Kolom Penutup (Nilai Akhir, Status, Keterangan)
        $totalColumnsCount = 4 + count($activeComponents) + 3;
        $lastColumnLetter = Coordinate::stringFromColumnIndex($totalColumnsCount);

        // Judul Laporan Utama
        $sheet->mergeCells("A1:{$lastColumnLetter}1");
        $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI HASIL BELAJAR SISWA');
        $sheet->getStyle('A1')->getFont()->setName('Segoe UI')->setSize(15)->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A1:{$lastColumnLetter}1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0F766E'); // Emerald / Teal

        // Subjudul Sekolah
        $sheet->mergeCells("A2:{$lastColumnLetter}2");
        $sheet->setCellValue('A2', 'SMK NEGERI 3 YOGYAKARTA — SISTEM E-MODUL INTERAKTIF');
        $sheet->getStyle('A2')->getFont()->setName('Segoe UI')->setSize(10)->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A2:{$lastColumnLetter}2")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('115E59');
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Metadata Modul (Baris 4 - 6)
        $sheet->setCellValue('A4', 'Judul Modul');
        $sheet->setCellValue('B4', ': ' . $module->title);
        $sheet->setCellValue('A5', 'Target Kelas');
        $sheet->setCellValue('B5', ': ' . ($module->schoolClass ? $module->schoolClass->full_name : 'Semua Kelas'));
        $sheet->setCellValue('A6', 'Guru Pengampu');
        $sheet->setCellValue('B6', ': ' . ($module->teacher ? $module->teacher->name . ' (NIP: ' . ($module->teacher->identity_number ?? '-') . ')' : '-'));

        $midColIndex = max(4, (int) ceil($totalColumnsCount / 2) + 1);
        $midColLetter = Coordinate::stringFromColumnIndex($midColIndex);
        $midColLetterVal = Coordinate::stringFromColumnIndex($midColIndex + 1);

        $sheet->setCellValue("{$midColLetter}4", 'Batas Nilai Kelulusan');
        $sheet->setCellValue("{$midColLetterVal}4", ': ' . $kktp . ' Poin');
        $sheet->setCellValue("{$midColLetter}5", 'Status Modul');
        $sheet->setCellValue("{$midColLetterVal}5", ': ' . ucfirst($module->status));
        $sheet->setCellValue("{$midColLetter}6", 'Tanggal Ekspor');
        $sheet->setCellValue("{$midColLetterVal}6", ': ' . now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') . ' WIB');

        $sheet->getStyle('A4:A6')->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle("{$midColLetter}4:{$midColLetter}6")->getFont()->setBold(true)->setSize(10);

        // ── 2. HEADER TABEL DATA ───────────────────────────────────────────────
        $tableHeaderRow = 8;
        $currentCol = 1;

        $sheet->setCellValue('A' . $tableHeaderRow, 'NO');
        $sheet->setCellValue('B' . $tableHeaderRow, 'NISN');
        $sheet->setCellValue('C' . $tableHeaderRow, 'NAMA LENGKAP SISWA');
        $sheet->setCellValue('D' . $tableHeaderRow, 'KELAS');
        $currentCol = 5;

        // Simpan pemetaan kolom komponen dinamis untuk perhitungan
        $componentColumns = [];
        foreach ($activeComponents as $compKey => $compConfig) {
            $colLetter = Coordinate::stringFromColumnIndex($currentCol);
            $sheet->setCellValue("{$colLetter}{$tableHeaderRow}", strtoupper($compConfig['name']));
            $componentColumns[$compKey] = [
                'col_index'  => $currentCol,
                'col_letter' => $colLetter,
                'config'     => $compConfig,
            ];
            $currentCol++;
        }

        $summativeColIndex = $currentCol;
        $summativeColLetter = Coordinate::stringFromColumnIndex($summativeColIndex);
        $sheet->setCellValue("{$summativeColLetter}{$tableHeaderRow}", 'NILAI AKHIR');
        $currentCol++;

        $statusColIndex = $currentCol;
        $statusColLetter = Coordinate::stringFromColumnIndex($statusColIndex);
        $sheet->setCellValue("{$statusColLetter}{$tableHeaderRow}", 'STATUS PENILAIAN');
        $currentCol++;

        $kktpColIndex = $currentCol;
        $kktpColLetter = Coordinate::stringFromColumnIndex($kktpColIndex);
        $sheet->setCellValue("{$kktpColLetter}{$tableHeaderRow}", 'KETERANGAN NILAI');

        $sheet->getRowDimension($tableHeaderRow)->setRowHeight(28);

        // Styling Header Tabel
        $headerRange = "A{$tableHeaderRow}:{$lastColumnLetter}{$tableHeaderRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(10)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1E293B'); // Slate 800
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        // ── 3. ISI DATA SISWA (ROWS) ──────────────────────────────────────────
        $dataStartRow = 9;
        $currentRow = $dataStartRow;
        $no = 1;

        foreach ($students as $student) {
            $result = $module->studentResults->firstWhere('student_id', $student->id);
            $videoSummary = $module->has_video ? $module->videoSummaries->firstWhere('student_id', $student->id) : null;
            $embedSub = $module->has_embed ? $module->embedSubmissions->firstWhere('student_id', $student->id) : null;
            
            $jobSheetSub = null;
            if ($module->has_job_sheet) {
                $jobSheet = $module->jobSheets->first();
                if ($jobSheet) {
                    $jobSheetSub = $jobSheet->submissions->firstWhere('student_id', $student->id);
                }
            }

            $lkpdSub = null;
            if ($module->has_lkpd) {
                $lkpd = $module->lkpds->first();
                if ($lkpd) {
                    $lkpdSub = $lkpd->submissions->firstWhere('student_id', $student->id);
                }
            }

            // No
            $sheet->setCellValueExplicit("A{$currentRow}", $no++, DataType::TYPE_NUMERIC);
            // NISN (Sebagai text string agar awalan 0 tidak hilang)
            $sheet->setCellValueExplicit("B{$currentRow}", (string) $student->identity_number, DataType::TYPE_STRING);
            // Nama Siswa
            $sheet->setCellValue("C{$currentRow}", $student->name);
            // Kelas
            $sheet->setCellValue("D{$currentRow}", $student->schoolClass ? $student->schoolClass->full_name : ($module->schoolClass ? $module->schoolClass->full_name : '-'));

            // Isi nilai komponen dinamis
            $componentScores = [];
            foreach ($componentColumns as $compKey => $meta) {
                $colLetter = $meta['col_letter'];
                $val = null;

                switch ($compKey) {
                    case 'pre_test':
                        $val = $result?->pre_test_score;
                        break;
                    case 'video':
                        $val = $result?->video_score ?? $videoSummary?->manual_score;
                        break;
                    case 'embed':
                        $val = $result?->embed_score ?? $embedSub?->manual_score;
                        break;
                    case 'job_sheet':
                        $val = $result?->job_sheet_score ?? $jobSheetSub?->manual_score;
                        break;
                    case 'lkpd':
                        $val = $result?->lkpd_score ?? $lkpdSub?->manual_score;
                        break;
                    case 'post_test':
                        $val = $result?->post_test_score;
                        break;
                }

                if ($val !== null && $val !== '') {
                    $scoreInt = (int) $val;
                    $sheet->setCellValueExplicit("{$colLetter}{$currentRow}", $scoreInt, DataType::TYPE_NUMERIC);
                    $componentScores[] = $scoreInt;
                } else {
                    $sheet->setCellValue("{$colLetter}{$currentRow}", '-');
                }
            }

            // Nilai Akhir Sumatif
            $finalScore = 0;
            if ($result && $result->summative_score !== null) {
                $finalScore = (int) $result->summative_score;
            } elseif (count($componentScores) > 0) {
                $finalScore = (int) round(array_sum($componentScores) / count($componentScores));
            }

            $hasSubmittedAny = $result !== null || $videoSummary !== null || $embedSub !== null || $jobSheetSub !== null || $lkpdSub !== null;

            if ($hasSubmittedAny) {
                $sheet->setCellValueExplicit("{$summativeColLetter}{$currentRow}", $finalScore, DataType::TYPE_NUMERIC);
            } else {
                $sheet->setCellValue("{$summativeColLetter}{$currentRow}", '-');
            }

            // Status Penilaian
            $statusLabel = 'Belum Mengumpulkan';
            if ($result && $result->grading_status === 'graded') {
                $statusLabel = 'Selesai Dinilai';
            } elseif ($hasSubmittedAny) {
                $statusLabel = 'Menunggu Penilaian';
            }
            $sheet->setCellValue("{$statusColLetter}{$currentRow}", $statusLabel);

            // Keterangan KKTP
            $kktpLabel = '-';
            if ($hasSubmittedAny) {
                $kktpLabel = ($finalScore >= $kktp) ? 'Tuntas' : 'Belum Tuntas (Remedial)';
            }
            $sheet->setCellValue("{$kktpColLetter}{$currentRow}", $kktpLabel);

            // Zebra striping untuk baris genap
            if ($no % 2 === 1) {
                $sheet->getStyle("A{$currentRow}:{$lastColumnLetter}{$currentRow}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $sheet->getRowDimension($currentRow)->setRowHeight(22);
            $currentRow++;
        }

        $dataEndRow = max($dataStartRow, $currentRow - 1);

        // ── 4. STYLING TABEL DATA ──────────────────────────────────────────────
        if ($dataEndRow >= $dataStartRow) {
            $dataRange = "A{$dataStartRow}:{$lastColumnLetter}{$dataEndRow}";
            
            // Border tipis ke seluruh tabel data
            $sheet->getStyle("A{$tableHeaderRow}:{$lastColumnLetter}{$dataEndRow}")->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');

            // Alignment
            $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$dataStartRow}:B{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$dataStartRow}:D{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$dataStartRow}:C{$dataEndRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            // Center komponen nilai, summative score, status, dan keterangan KKTP
            foreach ($componentColumns as $meta) {
                $sheet->getStyle("{$meta['col_letter']}{$dataStartRow}:{$meta['col_letter']}{$dataEndRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
            $sheet->getStyle("{$summativeColLetter}{$dataStartRow}:{$summativeColLetter}{$dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("{$summativeColLetter}{$dataStartRow}:{$summativeColLetter}{$dataEndRow}")
                ->getFont()->setBold(true);

            $sheet->getStyle("{$statusColLetter}{$dataStartRow}:{$statusColLetter}{$dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("{$kktpColLetter}{$dataStartRow}:{$kktpColLetter}{$dataEndRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // ── 5. STATISTIK & RANGKUMAN KELAS (SUMMARY ROWS) ──────────────────────
        $summaryStartRow = $dataEndRow + 2;

        $sheet->setCellValue("B{$summaryStartRow}", 'RANGKUMAN STATISTIK KELAS');
        $sheet->getStyle("B{$summaryStartRow}")->getFont()->setBold(true)->setSize(10)->getColor()->setRGB('0F766E');

        $r1 = $summaryStartRow + 1;
        $r2 = $summaryStartRow + 2;
        $r3 = $summaryStartRow + 3;
        $r4 = $summaryStartRow + 4;
        $r5 = $summaryStartRow + 5;

        $sheet->setCellValue("B{$r1}", 'Rata-rata Nilai Kelas (Sumatif)');
        $sheet->setCellValue("C{$r1}", "=IFERROR(ROUND(AVERAGE({$summativeColLetter}{$dataStartRow}:{$summativeColLetter}{$dataEndRow}), 1), 0)");

        $sheet->setCellValue("B{$r2}", 'Nilai Tertinggi (Maksimum)');
        $sheet->setCellValue("C{$r2}", "=IFERROR(MAX({$summativeColLetter}{$dataStartRow}:{$summativeColLetter}{$dataEndRow}), 0)");

        $sheet->setCellValue("B{$r3}", 'Nilai Terendah (Minimum)');
        $sheet->setCellValue("C{$r3}", "=IFERROR(MIN({$summativeColLetter}{$dataStartRow}:{$summativeColLetter}{$dataEndRow}), 0)");

        $sheet->setCellValue("B{$r4}", 'Jumlah Siswa Tuntas (≥ Batas Nilai)');
        $sheet->setCellValue("C{$r4}", "=COUNTIF({$kktpColLetter}{$dataStartRow}:{$kktpColLetter}{$dataEndRow}, \"Tuntas\")");

        $sheet->setCellValue("B{$r5}", 'Jumlah Siswa Perlu Remedial');
        $sheet->setCellValue("C{$r5}", "=COUNTIF({$kktpColLetter}{$dataStartRow}:{$kktpColLetter}{$dataEndRow}, \"Belum Tuntas*\")");

        $sheet->getStyle("B{$r1}:B{$r5}")->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle("C{$r1}:C{$r5}")->getFont()->setBold(true)->setSize(9);
        $sheet->getStyle("C{$r1}:C{$r5}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("B{$r1}:C{$r5}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
        $sheet->getStyle("B{$r1}:C{$r5}")->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');

        // ── 6. PENYESUAIAN LEBAR KOLOM OTOMATIS ────────────────────────────────
        for ($col = 1; $col <= $totalColumnsCount; $col++) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Berikan padding minimum pada kolom tertentu agar nyaman dibaca
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(32);
        $sheet->getColumnDimension('D')->setWidth(18);

        return $spreadsheet;
    }

    /**
     * Menghasilkan nama file yang bersih untuk diunduh.
     */
    public function getFilename(): string
    {
        $slug = Str::slug($this->module->title, '_');
        $class = $this->module->schoolClass ? Str::slug($this->module->schoolClass->full_name, '_') : 'semua_kelas';
        $date = now()->format('Ymd_His');

        return "Rekap_Nilai_{$slug}_{$class}_{$date}.xlsx";
    }

    /**
     * Mengembalikan response streamed untuk download browser.
     */
    public function download(): StreamedResponse
    {
        $spreadsheet = $this->generateSpreadsheet();
        $filename = $this->getFilename();

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
