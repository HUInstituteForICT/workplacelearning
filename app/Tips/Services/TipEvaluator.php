<?php

declare(strict_types=1);

namespace App\Tips\Services;

use App\Tips\EvaluatedTip;
use App\Student;
use App\Tips\EvaluatedTipInterface;
use App\Tips\Models\Tip;

class TipEvaluator
{
    /**
     * @var MomentTriggerEvaluator
     */
    private $momentTipEvaluator;
    /**
     * @var StatisticTriggerEvaluator
     */
    private $statisticTipEvaluator;

    public function __construct(StatisticTriggerEvaluator $statisticTipEvaluator, MomentTriggerEvaluator $momentTipEvaluator)
    {
        $this->momentTipEvaluator = $momentTipEvaluator;
        $this->statisticTipEvaluator = $statisticTipEvaluator;
    }

    public function evaluate(Tip $tip): EvaluatedTipInterface
    {
        $evaluatedTip = new EvaluatedTip($tip);

        if (!$tip->showInAnalysis) {
            return $evaluatedTip;
        }

        // Visit all types of triggers so they can do their stuff
        $this->statisticTipEvaluator->evaluate($evaluatedTip);
        $this->momentTipEvaluator->evaluate($evaluatedTip);

        return $evaluatedTip;
    }

    public function evaluateForChosenStudent(Tip $tip, Student $student): EvaluatedTipInterface
    {
        $evaluatedTip = new EvaluatedTip($tip);

        if (!$tip->showInAnalysis) {
            return $evaluatedTip;
        }

        // Visit all types of triggers so they can do their stuff
        $this->statisticTipEvaluator->evaluate($evaluatedTip);
        $this->momentTipEvaluator->evaluateForChosenStudent($evaluatedTip, $student);

        return $evaluatedTip;
    }
}
