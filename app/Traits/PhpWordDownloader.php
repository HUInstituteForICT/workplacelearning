<?php

namespace App\Traits;

use PhpOffice\PhpWord\PhpWord;

trait PhpWordDownloader
{
    private function downloadDocument(PhpWord $document, string $filename, string $format = 'Word2007'): void
    {
        ob_start();
        $document->save($filename, 'Word2007', true);
        exit;
    }
}
