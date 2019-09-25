<?php

declare(strict_types=1);

namespace App\Traits;

use PhpOffice\PhpWord\PhpWord;

trait PhpWordDownloader
{
    private function downloadDocument(PhpWord $document, string $filename, string $format = 'Word2007'): void
    {
        ob_start();
        $document->save($filename, $format, true);
        exit;
    }
}
