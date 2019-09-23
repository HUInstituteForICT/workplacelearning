<?php

declare(strict_types=1);

namespace Unit\Tips;

use App\Tips\EvaluatedTip;
use App\Tips\Models\Moment;
use App\Tips\Models\Tip;
use App\Tips\PeriodMomentCalculator;
use App\Tips\Services\MomentTriggerEvaluator;
use Tests\TestCase;

class MomentTriggerEvaluatorTest extends TestCase
{
    /** @var EvaluatedTip */
    private $evaluatedTip;

    public function setUp(): void
    {
        parent::setUp();

        $moment = new Moment();
        $moment->rangeStart = 20;
        $moment->rangeEnd = 50;

        /** @var Tip|\PHPUnit_Framework_MockObject_MockObject $tip */
        $tip = $this->createMock(Tip::class);
        $tip->expects(self::any())->method('__get')->with('moments')->willReturn(collect([$moment]));

        $this->evaluatedTip = new EvaluatedTip($tip);
    }

    public function testEvaluate(): void
    {
        /** @var PeriodMomentCalculator|\PHPUnit_Framework_MockObject_MockObject $periodMomentCalculator */
        $periodMomentCalculator = $this->createMock(PeriodMomentCalculator::class);
        $periodMomentCalculator->expects(self::once())->method('getMomentAsPercentage')->willReturn('40');

        $momentTriggerEvaluator = new MomentTriggerEvaluator($periodMomentCalculator);

        $this->assertFalse($this->evaluatedTip->isPassing());

        $momentTriggerEvaluator->evaluate($this->evaluatedTip);

        $this->assertTrue($this->evaluatedTip->isPassing());
    }
}
