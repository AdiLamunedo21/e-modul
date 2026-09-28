<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class PptConverterService
{
    /**
     * Konversi file PPT / PPTX ke PDF resolusi tinggi menggunakan PowerPoint COM.
     * Mengembalikan relative path PDF di disk 'public', atau null jika gagal.
     */
    public static function convertToPdf(?string $storageRelativePath): ?string
    {
        if (empty($storageRelativePath)) {
            return null;
        }

        if (!Storage::disk('public')->exists($storageRelativePath)) {
            return null;
        }

        $ext = strtolower(pathinfo($storageRelativePath, PATHINFO_EXTENSION));

        // Jika sudah PDF, kembalikan langsung
        if ($ext === 'pdf') {
            return $storageRelativePath;
        }

        if (!in_array($ext, ['ppt', 'pptx'])) {
            return null;
        }

        $dir = pathinfo($storageRelativePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storageRelativePath, PATHINFO_FILENAME);
        $targetPdfPath = ($dir === '.' ? '' : $dir . '/') . $filename . '.pdf';

        $scriptPath = app_path('Services/convert_ppt_to_pdf.ps1');
        if (!file_exists($scriptPath)) {
            Log::warning("PptConverterService: PowerShell script not found at {$scriptPath}");
            return null;
        }

        $absPptx = Storage::disk('public')->path($storageRelativePath);
        $absPdf  = Storage::disk('public')->path($targetPdfPath);

        try {
            $process = new Process([
                'powershell',
                '-ExecutionPolicy', 'Bypass',
                '-File', $scriptPath,
                '-pptxPath', $absPptx,
                '-pdfPath', $absPdf,
            ]);

            $process->setTimeout(120);
            $process->run();

            if ($process->isSuccessful() && Storage::disk('public')->exists($targetPdfPath)) {
                return $targetPdfPath;
            }

            Log::warning("PptConverterService failed to convert {$storageRelativePath}: " . $process->getErrorOutput());
            return null;
        } catch (\Throwable $e) {
            Log::error("PptConverterService exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Dapatkan path PDF untuk pratinjau visual.
     * Jika belum ada PDF dan file berformat PPT/PPTX, otomatis picu konversi.
     */
    public static function getPdfPreviewPath(?string $storageRelativePath, ?string $explicitPdfPreview = null): ?string
    {
        if (!empty($explicitPdfPreview) && Storage::disk('public')->exists($explicitPdfPreview)) {
            return $explicitPdfPreview;
        }

        if (empty($storageRelativePath) || !Storage::disk('public')->exists($storageRelativePath)) {
            return null;
        }

        $ext = strtolower(pathinfo($storageRelativePath, PATHINFO_EXTENSION));

        if ($ext === 'pdf') {
            return $storageRelativePath;
        }

        if (in_array($ext, ['ppt', 'pptx'])) {
            $dir = pathinfo($storageRelativePath, PATHINFO_DIRNAME);
            $filename = pathinfo($storageRelativePath, PATHINFO_FILENAME);
            $siblingPdf = ($dir === '.' ? '' : $dir . '/') . $filename . '.pdf';

            if (Storage::disk('public')->exists($siblingPdf)) {
                return $siblingPdf;
            }

            // Jika belum ada, otomatis konversi sekarang
            return self::convertToPdf($storageRelativePath);
        }

        return null;
    }

    /**
     * Hapus berkas preview PDF pendamping jika ada saat file materi dihapus.
     */
    public static function deletePreviews(?string $storageRelativePath): void
    {
        if (empty($storageRelativePath)) {
            return;
        }

        $ext = strtolower(pathinfo($storageRelativePath, PATHINFO_EXTENSION));
        if (in_array($ext, ['ppt', 'pptx'])) {
            $dir = pathinfo($storageRelativePath, PATHINFO_DIRNAME);
            $filename = pathinfo($storageRelativePath, PATHINFO_FILENAME);
            $siblingPdf = ($dir === '.' ? '' : $dir . '/') . $filename . '.pdf';

            if (Storage::disk('public')->exists($siblingPdf)) {
                Storage::disk('public')->delete($siblingPdf);
            }
        }
    }
}
