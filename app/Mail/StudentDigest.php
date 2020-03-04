<?php
declare(strict_types=1);

namespace App\Mail;


use App\Student;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\DatabaseNotification;

class StudentDigest extends Mailable
{
    private $student;

    private $notifications;

    /**
     * @param array<DatabaseNotification> $notifications
     */
    public function __construct(Student $student, array $notifications)
    {
        $this->student = $student;
        $this->notifications = $notifications;
    }

    public function build(): self
    {
        $this->subject('Notificatie Werkplekleren');
        $this->from('noreply@werkplekleren.hu.nl');


        $this->view('mail.student_digest', [
            'name'                  => $this->student->getName(),
            'renderedNotifications' => array_map(function (DatabaseNotification $notification): string {
                return $notification->data['html'];
            }, $this->notifications),
        ]);

        return $this;
    }


}
