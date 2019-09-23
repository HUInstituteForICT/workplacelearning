<?php

declare(strict_types=1);

namespace App\Tips\Services;

use App\Tips\EvaluatedTip;
use App\Tips\Models\Moment;
use App\Tips\PeriodMomentCalculator;
use App\Tips\TextParameter;

class MomentTriggerEvaluator
{
    /**
     * @var PeriodMomentCalculator
     */
    private $periodMomentCalculator;

    public function __construct(PeriodMomentCalculator $periodMomentCalculator)
    {
        $this->periodMomentCalculator = $periodMomentCalculator;
    }

    public function evaluate(EvaluatedTip $evaluatedTip): void
    {
        $tip = $evaluatedTip->getTip();

        // Do nothing if there are no moments
        if ($tip->moments->count() === 0) {
            return;
        }

        $percentage = $this->periodMomentCalculator->getMomentAsPercentage();

        $textParameter = new TextParameter(':days-percentage', $percentage.'%');

        $passes = $this->hasFittingMoment($tip->moments->all(), $percentage);

        $evaluatedTip->addTextParameter($textParameter);
        $evaluatedTip->addEvaluationResult($passes);
    }

    /**
     * @param Moment[]|array
     */
    private function hasFittingMoment(array $moments, float $percentage): bool
    {
        /** @var Moment $moment */
        foreach ($moments as $moment) {
            if ($moment->rangeStart <= $percentage && $percentage <= $moment->rangeEnd) {
                return true;
            }
        }

        return false;
    }
}
