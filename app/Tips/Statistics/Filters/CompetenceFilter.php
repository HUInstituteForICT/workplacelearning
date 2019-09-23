<?php

declare(strict_types=1);

namespace App\Tips\Statistics\Filters;

use Illuminate\Database\Query\Builder;

class CompetenceFilter implements Filter
{
    private $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function filter(Builder $builder): void
    {
        if (empty($this->parameters['competence_label'])) {
            return;
        }

        $competenceLabels = array_map('trim', explode('||', $this->parameters['competence_label']));

        $builder
            ->leftJoin('activityforcompetence', 'activityforcompetence.learningactivity_id', '=', 'learningactivityacting.laa_id')
            ->leftJoin('competence', 'competence.competence_id', '=', 'activityforcompetence.competence_id')
            ->where(function (Builder $builder) use ($competenceLabels) {
                $builder->whereIn('competence.competence_label', $competenceLabels);

                array_map(function (string $competence) use ($builder) {
                    if (strpos($competence, '*') !== false) {
                        $wildcardLabel = str_replace('*', '%', $competence);
                        $builder->orWhere('competence.competence_label', 'LIKE', $wildcardLabel);
                    }
                }, $competenceLabels);
            });
    }
}
