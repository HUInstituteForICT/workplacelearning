<?php
declare(strict_types=1);

namespace App\Notifications;


use App\FolderComment;
use App\SavedLearningItem;
use App\Services\SLIDescriptionGenerator;
use Illuminate\Notifications\Notification;

class FolderFeedbackGiven extends Notification
{

    /** @var FolderComment */
    private $comment;

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
            'html' => view('mail.includes.student_sli_part', [
                'name'         => $this->comment->folder->student->getName(),
                'teacherName'  => $this->comment->author->getName(),
                'workplace'    => $this->comment->folder->student->getCurrentWorkplace()->wp_name,
                'descriptions' => $sliDescriptions,
                'question'     => $this->comment->folder->folderComments->first()->text,
                'answer'       => $this->comment->text,
            ])->render(),
        ];
    }

    public function via(): array
    {
        return ['database'];
    }

}
