<?php

declare(strict_types=1);

namespace App\Tips\Statistics\Filters;

use Illuminate\Database\Query\Builder;

class ResourceMaterialFilter implements Filter
{
    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder): void
    {
        if (empty($this->parameters['rm_label'])) {
            return;
        }

        $labels = array_map('trim', explode('||', $this->parameters['rm_label']));

        $builder
            ->leftJoin('resourcematerial', 'res_material_id', '=', 'rm_id');

        $this->applyOptionalNullFilter($builder, $labels);
    }

    /**
     * Checks whether the parameters contain values that require a OR WHERE IS NULL clause in the query.
     * If they are present they are applied in a correct manner, if not, a normal WHERE IN clause is applied.
     */
    private function applyOptionalNullFilter(Builder $builder, array $labels): void
    {
        $labelsInLower = collect($labels)->map(function ($label) {
            return strtolower($label);
        });

        // contains() only allows single key, not array of keys :(
        if ($labelsInLower->contains('geen') || $labelsInLower->contains('none')) {
            $builder->where(function (Builder $query) use ($labels): void {
                $query->whereIn('rm_label', $labels)
                    ->orWhereNull('res_material_id');
            });
        } else {
            $builder->where(function (Builder $builder) use ($labels) {
                $builder->whereIn('rm_label', $labels);
                $this->applyWildcard($builder, $labels);
            });
        }
    }

    private function applyWildcard(Builder $builder, array $labels): void
    {
        array_map(function (string $label) use ($builder) {
            if (strpos($label, '*') !== false) {
                $wildcardLabel = str_replace('*', '%', $label);
                $builder->orWhere('rm_label', 'LIKE', $wildcardLabel);
            }
        }, $labels);
    }
}
