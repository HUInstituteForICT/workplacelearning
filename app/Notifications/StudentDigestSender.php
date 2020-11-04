<?php
declare(strict_types=1);

namespace App\Notifications;


use App\Mail\StudentDigest;
use App\Mail\TeacherDigest;
use App\Student;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Notifications\DatabaseNotification;

class StudentDigestSender
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

        // Disable function usableNotifications because it fails. Not sure why it is needed!
        // $usableNotifications = array_filter($notifications, function (DatabaseNotification $notification) {
        //     return $notification->type === FolderFeedbackGiven::class;
        // });
        
        // foreach ($usableNotifications as $notification) {
        //     $notification->markAsRead();
        // }
        
        // Instead of checking if the notification is usable, use the original notification.
        $this->mailer->to($user->email)->send(new StudentDigest($user, $notifications));
        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

    }}
