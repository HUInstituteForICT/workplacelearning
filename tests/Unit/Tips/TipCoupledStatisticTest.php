<?php

declare(strict_types=1);

use App\Tips\Models\CustomStatistic;
use App\Tips\Models\Tip;
use App\Tips\Models\TipCoupledStatistic;

class TipCoupledStatisticTest extends \Tests\TestCase
{
    public function testTipRelation(): void
    {
        /** @var \App\Tips\Models\Tip $tip */
        $tip = factory(\App\Tips\Models\Tip::class)->create();
        /** @var CustomStatistic $statistic */
        $statistic = factory(CustomStatistic::class)->create();

        $tipCoupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold'           => 0.5,
        ]);

        $tip->coupledStatistics()->save($tipCoupledStatistic);

        $tip->save();

        $count = (int) DB::select('SELECT COUNT(*) as count FROM tip_coupled_statistic')[0]->count;

        $this->assertTrue($count === 1, 'TipCoupledStatistic relation inserting');

        $this->assertInstanceOf(TipCoupledStatistic::class, $tip->coupledStatistics->first());
    }

    public function testTipServiceCoupling(): void
    {
        $tipService = new \App\Tips\Services\TipManager();

        /** @var Tip $tip */
        $tip = factory(Tip::class)->create();
        /** @var CustomStatistic $statistic */
        $statistic = factory(CustomStatistic::class)->create();

        $tipCoupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold'           => 0.5,
        ]);

        $tip->coupledStatistics()->save($tipCoupledStatistic);
        $this->assertInstanceOf(TipCoupledStatistic::class, $tip->coupledStatistics->first());
        $this->assertCount(1, $tip->coupledStatistics);
    }
}
