<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use ZipArchive;
use DOMDocument;

class PptxParserService
{
    /**
     * Ekstrak teks dan struktur slide dari berkas PowerPoint (.pptx).
     *
     * @param string|null $storageRelativePath Jalur relatif dalam disk 'public'
     * @return array Daftar slide terstruktur ['number' => int, 'title' => string, 'lines' => array]
     */
    public static function parse(?string $storageRelativePath): array
    {
        if (empty($storageRelativePath)) {
            return [];
        }

        if (!Storage::disk('public')->exists($storageRelativePath)) {
            return [];
        }

        $fullPath = Storage::disk('public')->path($storageRelativePath);
        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        // Format .ppt (PowerPoint 97-2003 binary) bukan ZIP, tidak dapat di-parse dengan ZipArchive
        if ($ext !== 'pptx') {
            return [];
        }

        $zip = new ZipArchive();
        if ($zip->open($fullPath) !== true) {
            return [];
        }

        $slideFiles = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (preg_match('#^ppt/slides/slide(\d+)\.xml$#', $name, $matches)) {
                $slideFiles[(int) $matches[1]] = $name;
            }
        }

        ksort($slideFiles);

        $slides = [];
        foreach ($slideFiles as $slideNum => $slideFile) {
            $xml = $zip->getFromName($slideFile);
            if (!$xml) {
                continue;
            }

            $dom = new DOMDocument();
            @$dom->loadXML($xml);

            $paragraphs = $dom->getElementsByTagNameNS('http://schemas.openxmlformats.org/drawingml/2006/main', 'p');
            $lines = [];

            foreach ($paragraphs as $p) {
                $txt = trim($p->textContent);
                if (!empty($txt)) {
                    // Bersihkan spasi berlebih
                    $cleanTxt = preg_replace('/\s+/', ' ', $txt);
                    $lines[] = $cleanTxt;
                }
            }

            if (!empty($lines)) {
                $title = $lines[0];
                $bodyLines = array_slice($lines, 1);

                $slides[] = [
                    'number'    => count($slides) + 1,
                    'orig_num'  => $slideNum,
                    'title'     => $title,
                    'lines'     => $bodyLines,
                ];
            }
        }

        $zip->close();

        return $slides;
    }
}
