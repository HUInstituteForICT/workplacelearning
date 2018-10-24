<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class LearningActivityActingExportBuilder
{
    private $learningActivityActingCollection;

    public function __construct(Collection $learningActivityActingCollection)
    {
        $this->learningActivityActingCollection = $learningActivityActingCollection;
    }

    public function getJson()
    {
        $jsonArray = [];
        $this->learningActivityActingCollection->each(function (LearningActivityActing $activity) use (&$jsonArray): void {
            $jsonArray[] = [
                'id' => $activity->laa_id,
                'date' => Carbon::createFromFormat('Y-m-d', $activity->date)->format('d-m-Y'),
                'situation' => $activity->situation,
                'timeslot' => $activity->timeslot->localizedLabel(),
                'resourcePerson' => $activity->resourcePerson->localizedLabel(),
                'resourceMaterial' => __($activity->resourceMaterial ? $activity->resourceMaterial->rm_label : 'activity.none'),
                'learningGoal' => __($activity->learningGoal->learninggoal_label),
                'competence' => $activity->competence->map(function (Competence $competence) {
                    return $competence->localizedLabel();
                })->all(),
                'learningGoalDescription' => $activity->learningGoal->description,
                'lessonsLearned' => $activity->lessonslearned,
                'supportWp' => $activity->support_wp ?? '',
                'supportEd' => $activity->support_ed ?? '',
                'url' => route('process-acting-edit', ['id' => $activity->laa_id]),
                'evidence' => $activity->evidence->map(function (Evidence $evidence) {
                    return [
                        'name' => $evidence->filename,
                        'url' => route('evidence-download',
                            ['evidence' => $evidence, 'diskFileName' => $evidence->disk_filename]),
                    ];
                })->all(),
            ];
        });

        return json_encode($jsonArray);
    }

    public function getFieldLanguageMapping(Translator $translator): array
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
        ])->each(function ($field) use (&$mapping, $translator): void { $mapping[$field] = $translator->get('process_export.'.$field); });

        return $mapping;
    }
}
