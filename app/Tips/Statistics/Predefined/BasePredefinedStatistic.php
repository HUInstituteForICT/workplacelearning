<?php


namespace App\Tips\Statistics\Predefined;


use App\Tips\Interfaces\LearningPeriodAwareInterface;
use App\Tips\Traits\LearningPeriodAwareTrait;
use App\Tips\Traits\PeriodFilterTrait;

abstract class BasePredefinedStatistic implements PredefinedStatisticInterface, LearningPeriodAwareInterface
{
    use LearningPeriodAwareTrait;
    use PeriodFilterTrait;
}