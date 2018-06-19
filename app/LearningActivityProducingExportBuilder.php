<?php


namespace App;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
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
                'id'             => $activity->lap_id,
                'date'           => Carbon::createFromFormat('Y-m-d', $activity->date)->format('d-m-Y'),
                'duration'       => $activity->getDurationString(),
                'description'    => $activity->description,
                'resourceDetail' => $activity->getResourceDetail(),
                'category'       => $activity->getCategory(),
                'difficulty'     => Lang::get('general.' . strtolower($activity->getDifficulty())),
                'status'         => $activity->getStatus(),
                'url'            => route('process-producing-edit', ['id' => $activity->lap_id]),
                'chain'          => $activity->chain->name ?? '-'
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