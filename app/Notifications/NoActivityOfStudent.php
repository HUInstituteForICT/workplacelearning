<?php
declare(strict_types=1);

namespace App\Notifications;


use App\FolderComment;
use App\SavedLearningItem;
use App\Services\SLIDescriptionGenerator;
use App\Student;
use Illuminate\Notifications\Notification;

class NoActivityOfStudent extends Notification
{

    /** @var Student */
    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function toArray(): array
    {
        return [
            'html' => view('mail.includes.teacher_no_activity_part', [
                'student' => $this->student
            ])->render(),
        ];
    }

    public function via(): array
    {
        return ['database'];
    }
}
