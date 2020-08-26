<?php
declare(strict_types=1);

namespace App\Notifications;


use Illuminate\Database\Eloquent\Collection;

class NotificationRouter
{
    private $teacherDigestSender;

    private $studentDigestSender;

    private $inactiveStudentSender;

    public function __construct(TeacherDigestSender $teacherDigestSender, StudentDigestSender $studentDigestSender, TeacherInactiveStudentSender $inactiveStudentSender)
    {
        $this->teacherDigestSender = $teacherDigestSender;
        $this->studentDigestSender = $studentDigestSender;
        $this->inactiveStudentSender = $inactiveStudentSender;
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
                $this->inactiveStudentSender->sendNotifications($notifications, $user);
            }

            if ($user->isStudent()) {
                // filtered within call
                $this->studentDigestSender->sendNotifications($notifications, $user);
            }

        }
    }
}
