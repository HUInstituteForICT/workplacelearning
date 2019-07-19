<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class TxtExport extends Mailable
{
    use Queueable, SerializesModels;

    protected $txt;
    private $comment;

    /**
     * Create a new message instance.
     *
     * @param $txt
     */
    public function __construct($txt, string $comment)
    {
        $this->txt = $txt;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@werkplekleren.hu.nl')
            ->subject(__('process_export.mail-subject'))
            ->attachData($this->txt, 'leermomenten-export.txt', ['mime' => 'text/plain'])
            ->text('mail.text-export', ['student' => Auth::user(), 'comment' => $this->comment]);
    }
}
