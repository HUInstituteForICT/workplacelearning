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

    public function filter(Builder $builder): void
    {
        if (empty($this->parameters['person_label'])) {
            return;
        }

        $labels = array_map('trim', explode('||', $this->parameters['person_label']));

        $builder
            ->leftJoin('resourceperson', 'res_person_id', '=', 'rp_id');

        // Because a LAP will not use a ResourcePerson model when the RP is alone we need to filter on null instead
        if (str_contains($builder->from, (new LearningActivityProducing())->getTable())) {
            $this->applyNullFilter($builder, $labels);
        } else {
            $builder->whereIn('person_label', $labels);
        }
    }

    private function applyNullFilter(Builder $builder, array $labels): void
    {
        $labelsInLower = collect($labels)->map(function ($label) {
            return strtolower($label);
        });

        // contains() only allows single key, not array of keys :(
        if ($labelsInLower->contains('alleen') || $labelsInLower->contains('alone')) {
            $builder->where(function (Builder $query) use ($labels): void {
                $query->whereIn('person_label', $labels) // Where the label is Alleen / Alone etc.
                    ->orWhere(function (Builder $query): void { // Or where res_person_id is null AND material is also null, because a book could've been used
                        $query->whereNull('res_person_id')
                            ->whereNull('res_material_id');
                    });
            });
        } else {
            $builder->whereIn('person_label', $labels);
        }
    }
}
