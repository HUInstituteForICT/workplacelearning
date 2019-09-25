<?php

declare(strict_types=1);

namespace App\Services;

use App\LearningActivityProducing;
use Illuminate\Translation\Translator;

class LearningActivityProducingExportBuilder
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getJson(array $learningActivities, ?int $limit = null): string
    {
        $jsonArray = [];

        $collection = collect($learningActivities);
        if ($limit !== null) {
            $collection = $collection->take($limit);
        }

        $collection->each(function (LearningActivityProducing $activity) use (&$jsonArray): void {
            $jsonArray[] = [
                'id'              => $activity->lap_id,
                'date'            => $activity->date->format('d-m-Y'),
                'duration'        => $this->formatDuration($activity->duration),
                'hours'           => $activity->duration,
                'description'     => $activity->description,
                'resourceDetail'  => $this->formatResourceDetail($activity),
                'category'        => $activity->category->localizedLabel(),
                'difficulty'      => $this->translator->get('general.'.strtolower($activity->difficulty->difficulty_label)),
                'difficultyValue' => $activity->difficulty->difficulty_id,
                'status'          => $this->translator->get('general.'.strtolower($activity->status->status_label)),
                'url'             => route('process-producing-edit', ['id' => $activity->lap_id]),
                'chain'           => $this->formatChain($activity),
                'feedback'        => $activity->feedback->fb_id ?? null,
            ];
        });

        return json_encode($jsonArray);
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
            return $this->translator->get('activity.producing.person').': '.__($learningActivityProducing->resourcePerson->localizedLabel());
        }

        return $this->translator->get('activity.alone');
    }

    private function formatChain(LearningActivityProducing $learningActivityProducing): string
    {
        if (!$learningActivityProducing->chain) {
            return '-';
        }
        $hours = strtolower($this->translator->get('activity.hours'));

        return $learningActivityProducing->chain->name." ({$learningActivityProducing->chain->hours()} {$hours})";
    }

    public function getFieldLanguageMapping(): array
    {
        $mapping = [];
        collect([
            'id',
            'date',
            'duration',
            'hours',
            'description',
            'resourceDetail',
            'category',
            'difficulty',
            'status',
            'chain',
        ])->each(function ($field) use (&$mapping): void {
            $mapping[$field] = $this->translator->get('process_export.'.$field);
        });
        $mapping['feedback'] = 'feedback';

        return $mapping;
    }
}
