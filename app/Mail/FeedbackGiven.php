<?php

declare(strict_types=1);

namespace App\Mail;

use App\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackGiven extends Mailable
{
    use Queueable;
    use SerializesModels;

    /** @var Student $user */
    private $user;
    /**
     * @var string
     */
    private $description;

    public function __construct(Student $user, string $subject, string $description)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->description = $description;
    }

    /**
     * @return $this
     */
    public function build(): self
    {
        $this->subject('Tip/Bug ingezonden!');
        $this->from('debug@werkplekleren.hu.nl', 'Werkplekleren @ Hogeschool Utrecht');
        $this->cc('esther.vanderstappen@hu.nl');
        $this->replyTo($this->user->email);

        return $this->view('templates.bugreport-email')
            ->with(
                [
                    'student_name'  => $this->user->getInitials().' '.$this->user->lastname.' ('.$this->user->firstname.')',
                    'student_email' => $this->user->email,
                    'education'     => $this->user->educationProgram,
                    'subject'       => $this->subject,
                    'content'       => $this->description,
                ]
            );
    }
}
