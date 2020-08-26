<?php
declare(strict_types=1);

namespace App\Notifications;


use App\FolderComment;
use App\SavedLearningItem;
use App\Services\SLIDescriptionGenerator;
use Illuminate\Notifications\Notification;

class FolderSharedWithTeacher extends Notification
{

    /**
     * @var FolderComment
     */
    private $comment;

    /**
     * Pass in the comment given with the sharing, meaning the question of the student
     */
    public function __construct(FolderComment $comment)
    {
        $this->comment = $comment;
    }

    public function toArray(): array
    {
        $generator = app()->make(SLIDescriptionGenerator::class);
        $sliDescriptions = array_map(function (SavedLearningItem $learningItem) use ($generator): string {
            return $generator->getDescriptionForSLI($learningItem);
        }, $this->comment->folder->savedLearningItems->all());

        return [
            'html' => view('mail.includes.teacher_sli_part', [
                'name'         => $this->comment->author->firstname.' '.$this->comment->author->lastname,
                'workplace'    => $this->comment->author->getCurrentWorkplace()->wp_name,
                'descriptions' => $sliDescriptions,
                'question'     => $this->comment->text,
                'url'          => route('teacher-student-details', ['student' => $this->comment->folder->student], true),
            ])->render(),
        ];
    }

    public function via(): array
    {
        return ['database'];
    }
}
