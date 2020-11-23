<?php
declare(strict_types=1);

namespace App\Notifications;


use App\Mail\InactiveStudent;
use App\Mail\TeacherDigest;
use App\Student;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Notifications\DatabaseNotification;

class TeacherInactiveStudentSender
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @param array<DatabaseNotification> $notifications
     */
    public function sendNotifications(array $notifications, Student $teacher): void
    {
        $usableNotifications = array_filter($notifications, static function (DatabaseNotification $notification) {
            return $notification->type === NoActivityOfStudent::class;
        });

        if( count($usableNotifications) > 0 ) {

            $this->mailer->to($teacher->email)->send(new InactiveStudent($teacher, $usableNotifications));

            foreach ($usableNotifications as $notification) {
                $notification->markAsRead();
            }
        
        }
    }
}
