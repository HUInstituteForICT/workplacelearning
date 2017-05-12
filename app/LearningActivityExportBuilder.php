<?php


namespace App;


use Illuminate\Support\Collection;

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
                "date" => $activity->date,
                "situation" => $activity->situation,
                "timeslot" => $activity->getTimeslot(),
                "resourcePerson" => $activity->getResourcePerson(),
                "resourceMaterial" => $activity->getResourceMaterial(),
                "lessonsLearned" => $activity->lessonslearned,
                "learningGoal" => $activity->getLearningGoal(),
                "competency" => $activity->getCompetencies()->competence_label
            ];
        });

        return json_encode($jsonArray);
    }
}