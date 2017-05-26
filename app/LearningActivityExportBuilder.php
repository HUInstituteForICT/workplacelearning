<?php


namespace App;


use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class LearningActivityExportBuilder
{

    private $learningActivityActingCollection;

    public function __construct(Collection $learningActivityActingCollection)
    {
        $this->learningActivityActingCollection = $learningActivityActingCollection;
    }



    public function getJson() : string
    {
        $jsonArray = [];
        $this->learningActivityActingCollection->each(function(LearningActivityActing $activity) use (&$jsonArray) {
            $jsonArray[] = [
                "id" => $activity->laa_id,
                "date" => $activity->date,
                "situation" => $activity->situation,
                "timeslot" => $activity->getTimeslot(),
                "resourcePerson" => $activity->getResourcePerson(),
                "resourceMaterial" => $activity->getResourceMaterial(),
                "lessonsLearned" => $activity->lessonslearned,
                "learningGoal" => $activity->getLearningGoal(),
                "competence" => $activity->getCompetencies()->competence_label,
                "url" => route('process-acting-edit', ['id' => $activity->laa_id])
            ];
        });

        return json_encode($jsonArray);
    }

    public function getFieldLanguageMapping(Translator $translator) {
        $mapping = [];
         collect([
            'date',
            'situation',
            'timeslot',
            'resourcePerson',
            'resourceMaterial',
            'lessonsLearned',
            'learningGoal',
            'competence'
        ])->each(function($field) use(&$mapping, $translator) { $mapping[$field] = $translator->get('process_export.' . $field); });
         return $mapping;
    }
}