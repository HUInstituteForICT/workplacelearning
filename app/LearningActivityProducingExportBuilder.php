<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class LearningActivityProducingExportBuilder
{
    private $learningActivityProducingCollection;
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Collection $learningActivityProducingCollection, Translator $translator)
    {
        $this->learningActivityProducingCollection = $learningActivityProducingCollection;
        $this->translator = $translator;
    }

    public function getJson()
    {
        $jsonArray = [];
        $this->learningActivityProducingCollection->each(function (LearningActivityProducing $activity) use (&$jsonArray) {
            $jsonArray[] = [
                'id' => $activity->lap_id,
                'date' => Carbon::createFromFormat('Y-m-d', $activity->date)->format('d-m-Y'),
                'duration' => $this->formatDuration($activity->duration),
                'description' => $activity->description,
                'resourceDetail' => $this->formatResourceDetail($activity),
                'category' => $this->translator->get($activity->category->category_label),
                'difficulty' => $this->translator->get('general.'.strtolower($activity->difficulty->difficulty_label)),
                'status' => $this->translator->get('general.'.strtolower($activity->status->status_label)),
                'url' => route('process-producing-edit', ['id' => $activity->lap_id]),
                'chain' => $activity->chain->name ?? '-',
                'feedback' => $activity->feedback->fb_id ?? null,
            ];
        });

        return json_encode($jsonArray);
    }

    public function getFieldLanguageMapping(Translator $translator): array
    {
        $mapping = [];
        collect([
             'id',
             'date',
             'duration',
             'description',
             'resourceDetail',
             'category',
             'difficulty',
             'status',
             'chain',
        ])->each(function ($field) use (&$mapping, $translator) { $mapping[$field] = $translator->get('process_export.'.$field); });

        return $mapping;
    }

    private function formatDuration(float $duration): string
    {
        switch ($duration) {
            case 0.25:
                return '15 min';
            case 0.5:
                return '30 min';
            case 0.75:
                return '45 min';
            case $duration < 1:
                return round($duration * 60).' min';
            default:
                return $duration.' '.$this->translator->get('general.hour');
        }
    }

    private function formatResourceDetail(LearningActivityProducing $learningActivityProducing): string
    {
        if ($learningActivityProducing->resourceMaterial) {
            return $this->translator->get($learningActivityProducing->resourceMaterial->rm_label).': '.$learningActivityProducing->res_material_detail;
        }

        if ($learningActivityProducing->resourcePerson) {
            return $this->translator->get('activity.producing.person').': '.__($learningActivityProducing->resourcePerson->person_label);
        }

        return $this->translator->get('activity.alone');
    }
}
