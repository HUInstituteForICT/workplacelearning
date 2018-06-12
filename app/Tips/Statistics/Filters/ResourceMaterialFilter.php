<?php


namespace App\Tips\Statistics\Filters;

use Illuminate\Database\Query\Builder;

class ResourceMaterialFilter implements Filter
{
    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder)
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
     * If they are present they are applied in a correct manner, if not, a normal WHERE IN clause is applied
     */
    private function applyOptionalNullFilter(Builder $builder, array $labels)
    {
        $labelsInLower = collect($labels)->map(function ($label) {
            return strtolower($label);
        });

        // contains() only allows single key, not array of keys :(
        if ($labelsInLower->contains('geen') || $labelsInLower->contains('none')) {
            $builder->where(function (Builder $query) use ($labels) {
                $query->whereIn('rm_label', $labels)
                    ->orWhereNull('res_material_id');
            });
        } else {
            $builder->whereIn('rm_label', $labels);
        }
    }
}
