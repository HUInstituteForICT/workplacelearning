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


    /**
     * Create a new message instance.
     *
     * @param $txt
     */
    public function __construct($txt)
    {
        $this->txt = $txt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("noreply@werkplekleren.hu.nl")
            ->subject("Leermomenten export gedeeld met u")
            ->attachData($this->txt, 'leermomenten-export.txt', ["mime" => "text/plain"])
            ->text('mail.text-export', ["student" => Auth::user()]);
    }
}
