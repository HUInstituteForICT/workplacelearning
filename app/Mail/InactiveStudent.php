<?php
declare(strict_types=1);

namespace App\Mail;


use App\Student;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\DatabaseNotification;

class InactiveStudent extends Mailable
{
    private $teacher;

    private $notifications;

    /**
     * @param array<DatabaseNotification> $notifications
     */
    public function __construct(Student $teacher, array $notifications)
    {
        $this->teacher = $teacher;
        $this->notifications = $notifications;
    }

    public function build(): self
    {
        $this->subject('Inactieve studenten Werkplekleren');
        $this->from('noreply@werkplekleren.hu.nl');


        $this->view('mail.inactive_students', [
            'name'                  => $this->teacher->getName(),
            'renderedNotifications' => array_map(function (DatabaseNotification $notification): string {
                return $notification->data['html'];
            }, $this->notifications),
        ]);

        return $this;
    }


}
