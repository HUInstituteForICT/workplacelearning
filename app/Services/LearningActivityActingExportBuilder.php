<?php

namespace App\Services;

use App\LearningActivityActing;
use Carbon\Carbon;
use Illuminate\Translation\Translator;

class LearningActivityActingExportBuilder
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getJson(array $learningActivities, ?int $limit): string
    {
        $jsonArray = [];

        $collection = collect($learningActivities);
        if ($limit !== null) {
            $collection = $collection->take($limit);
        }

        $collection->each(function (LearningActivityActing $activity) use (&$jsonArray): void {
            $jsonArray[] = [
                'id'                      => $activity->laa_id,
                'date'                    => Carbon::createFromFormat('Y-m-d', $activity->date)->format('d-m-Y'),
                'situation'               => $activity->situation,
                'timeslot'                => $activity->timeslot->localizedLabel(),
                'resourcePerson'          => $activity->resourcePerson->localizedLabel(),
                'resourceMaterial'        => __($activity->resourceMaterial ? $activity->resourceMaterial->rm_label : 'activity.none'),
                'learningGoal'            => __($activity->learningGoal->learninggoal_label),
                'competence'              => $activity->competence->first()->localizedLabel(),
                'learningGoalDescription' => $activity->learningGoal->description,
                'lessonsLearned'          => $activity->lessonslearned,
                'supportWp'               => $activity->support_wp ?? '',
                'supportEd'               => $activity->support_ed ?? '',
                'url'                     => route('process-acting-edit', ['id' => $activity->laa_id]),
                'evidence'                => $activity->evidence_filename === null ? '-' :
                    route('evidence-download', ['id' => $activity->laa_id, 'diskFileName' => $activity->evidence_disk_filename]),
            ];
        });

        return json_encode($jsonArray);
    }

    public function getFieldLanguageMapping(): array
    {
        $mapping = [];
        collect([
            'date',
            'situation',
            'timeslot',
            'resourcePerson',
            'resourceMaterial',
            'lessonsLearned',
            'learningGoal',
            'learningGoalDescription',
            'supportWp',
            'supportEd',
            'competence',
            'evidence',
        ])->each(function ($field) use (&$mapping): void {
            $mapping[$field] = $this->translator->get('process_export.'.$field);
        });

        return $mapping;
    }
}
