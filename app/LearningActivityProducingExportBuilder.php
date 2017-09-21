<?php


namespace App;


use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class LearningActivityProducingExportBuilder
{

    private $learningActivityProducingCollection;

    public function __construct(Collection $learningActivityProducingCollection)
    {
        $this->learningActivityProducingCollection= $learningActivityProducingCollection;
    }



    public function getJson()
    {
        $jsonArray = [];
        $this->learningActivityProducingCollection->each(function(LearningActivityProducing $activity) use (&$jsonArray) {
            $jsonArray[] = [
                "id" => $activity->lap_id,
                "date" => $activity->date,
                "duration" => $activity->getDurationString(),
                "description" => $activity->description,
                "resourceDetail" => $activity->getResourceDetail(),
                "category" => $activity->getCategory(),
                "difficulty" => $activity->getDifficulty(),
                "status" => $activity->getStatus(),
                "url" => route('process-producing-edit', ['id' => $activity->lap_id])
            ];
        });

        return json_encode($jsonArray);
    }

    public function getFieldLanguageMapping(Translator $translator) {
        $mapping = [];
         collect([
             "id",
             "date",
             "duration",
             "description",
             "resourceDetail",
             "category",
             "difficulty",
             "status"
        ])->each(function($field) use(&$mapping, $translator) { $mapping[$field] = $translator->get('process_export.' . $field); });
         return $mapping;
    }
}