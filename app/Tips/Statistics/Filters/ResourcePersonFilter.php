<?php


namespace App\Tips\Statistics\Filters;

use App\LearningActivityProducing;
use Illuminate\Database\Query\Builder;

class ResourcePersonFilter implements Filter
{
    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder)
    {
        if (empty($this->parameters['person_label'])) {
            return;
        }

        $labels = array_map('trim', explode('||', $this->parameters['person_label']));

        $builder
            ->leftJoin('resourceperson', 'res_person_id', '=', 'rp_id')
            ->whereIn('person_label', $labels);

        // Because a LAP will not use a ResourcePerson model when the RP is alone we need to filter on null instead
        if (str_contains($builder->from, (new LearningActivityProducing())->getTable())) {
            $this->applyNullFilter($builder, $labels);
        }
    }

    private function applyNullFilter(Builder $builder, array $labels)
    {
        if (collect($labels)->map('strtolower')->contains('alleen', 'alone')) {
            $builder->orWhereNull('res_person_id');
        }
    }
}
