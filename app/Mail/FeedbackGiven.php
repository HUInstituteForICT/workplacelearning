<?php

namespace App\Mail;

use App\EducationProgram;
use App\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackGiven extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Request $request */
    private $request;

    /** @var Student $user */
    private $user;

    /**
     * @param Request $request
     * @param Student $user
     */
    public function __construct(Request $request, Student $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * @return $this
     */
    public function build()
    {
        $this->subject('Tip/Bug ingezonden!');
        $this->from('debug@werkplekleren.hu.nl', 'Werkplekleren @ Hogeschool Utrecht');
        $this->cc('esther.vanderstappen@hu.nl');
        $this->cc('rogier@inesta.nl');
        $this->cc('dylan.vangils@student.hu.nl');
        $this->replyTo($this->user->email);

        return $this->view('templates.bugreport-email')
            ->with(
                [
                    'student_name'  => $this->user->getInitials()." ".$this->user->lastname." (".$this->user->firstname.")",
                    'student_email' => $this->user->email,
                    'education'     => EducationProgram::find($this->user->ep_id),
                    'subject'       => $this->request->get('onderwerp'),
                    'content'       => $this->request->get('uitleg'),
                ]
            );
    }
}
