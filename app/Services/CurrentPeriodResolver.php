<?php

declare(strict_types=1);

namespace App\Services;

use App\WorkplaceLearningPeriod;

class CurrentPeriodResolver
{
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function getPeriod(): WorkplaceLearningPeriod
    {
        $student = $this->currentUserResolver->getCurrentUser();

        if (!$student->hasCurrentWorkplaceLearningPeriod()) {
            throw new \RuntimeException('Expected student to have a active period, but student does not. Is '.self::class.'#getPeriod called too soon? e.g. in middleware');
        }

        return $this->currentUserResolver->getCurrentUser()->getCurrentWorkplaceLearningPeriod();
    }
}
