<?php
declare(strict_types=1);

namespace App\Notifications;


use Illuminate\Database\Eloquent\Collection;

class NotificationRouter
{
    private $teacherDigestSender;

    private $studentDigestSender;

    public function __construct(TeacherDigestSender $teacherDigestSender, StudentDigestSender $studentDigestSender)
    {
        $this->teacherDigestSender = $teacherDigestSender;
        $this->studentDigestSender = $studentDigestSender;
    }

    public function routeForUsers(Collection $users): void
    {
        foreach ($users as $user) {
            $notifications = $user->unreadNotifications->all();

            if (count($notifications) === 0) {
                continue;
            }

            if ($user->isTeacher()) {
                // filtered within call
                $this->teacherDigestSender->sendNotifications($notifications, $user);
            }

            if ($user->isStudent()) {
                // filtered within call
                $this->studentDigestSender->sendNotifications($notifications, $user);
            }

        }
    }
}
