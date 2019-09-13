<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\PhpWord;

class ActingActivitiesWordExportMail extends Mailable
{
    use Queueable, SerializesModels;

    private $comment;
    /**
     * @var PhpWord
     */
    private $document;

    public function __construct(?string $comment, PhpWord $document)
    {
        $this->comment = $comment;
        $this->document = $document;
    }

    public function build(): self
    {
        $fileName = md5(time());
        $filePath = storage_path('app/word-exports/' . $fileName . '.docx');
        $this->document->save($filePath);

        return $this->from('noreply@werkplekleren.hu.nl')
            ->subject(__('process_export.mail-subject'))
            ->attachFromStorageDisk('local', 'word-exports/' . $fileName.'.docx', 'export_' . strtolower(__('export_laa.learningactivities')) . '.docx')
            ->text('mail.text-export', ['student' => Auth::user(), 'comment' => $this->comment ?? '']);
    }
}
