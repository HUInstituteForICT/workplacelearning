<?php
declare(strict_types=1);

namespace App\Notifications;


use App\Mail\TeacherDigest;
use App\Student;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Notifications\DatabaseNotification;

class TeacherDigestSender
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @param array<DatabaseNotification> $notifications
     */
    public function sendNotifications(array $notifications, Student $user): void
    {

        // $usableNotifications = array_filter($notifications, function (DatabaseNotification $notification) {
        //     return $notification->type === FolderSharedWithTeacher::class;
        // });

        // foreach ($usableNotifications as $notification) {
        //     $notification->markAsRead();
        // }

        $this->mailer->to($user->email)->send(new TeacherDigest($user, $notifications));

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }
    }
}
