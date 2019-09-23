<?php

namespace App\Tips\Statistics\Predefined;

use App\ResourcePerson;
use App\Tips\Statistics\InvalidStatisticResult;
use App\Tips\Statistics\Resultable;
use App\Tips\Statistics\StatisticResult;
use App\Tips\Traits\LearningPeriodAwareTrait;
use App\Tips\Traits\PeriodFilterTrait;

class PersonWithEasiestDifficulty implements PredefinedStatisticInterface
{
    use LearningPeriodAwareTrait;
    use PeriodFilterTrait;

    public function getName(): string
    {
        return 'Person easiest to work with';
    }

    public function calculate(): Resultable
    {
        $result = $this->wherePeriod($this->learningPeriod->learningActivityProducing()
            ->selectRaw('learningactivityproducing.res_person_id, ROUND(AVG(learningactivityproducing.difficulty_id) * 3.33,1) as person_difficulty')
            ->groupBy('learningactivityproducing.res_person_id')
            ->rightJoin('resourceperson', 'learningactivityproducing.res_person_id', '=', 'rp_id')
            ->whereNotIn('person_label', ['Alleen', 'alleen', 'None', 'none'])
            ->orderBy('person_difficulty')->limit(1)->getBaseQuery()
        )->first();

        if ($result !== null && !empty($result->res_person_id) && !empty($result->person_difficulty)) {
            /** @var ResourcePerson $person */
            $person = (new ResourcePerson())->find($result->res_person_id);
        } else {
            return new InvalidStatisticResult();
        }

        return new StatisticResult($result->person_difficulty, $person->localizedLabel());
    }

    public function getResultDescription(): string
    {
        return 'The found person\'s name';
    }

    public function getEducationProgramType(): string
    {
        return self::PRODUCING_TYPE;
    }
}
