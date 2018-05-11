<?php


use App\Tips\Statistics\CustomStatistic;
use App\Tips\Tip;
use App\Tips\TipCoupledStatistic;


class TipCoupledStatisticTest extends \Tests\TestCase
{
    public function testTipRelation() {
        /** @var \App\Tips\Tip $tip */
        $tip = factory(\App\Tips\Tip::class)->create();
        /** @var CustomStatistic $statistic */
        $statistic = factory(CustomStatistic::class)->create();



        $tipCoupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold' => 0.5,
        ]);

        $tip->coupledStatistics()->save($tipCoupledStatistic);

        $tip->save();

        $count = (int) DB::select('SELECT COUNT(*) as count FROM tip_coupled_statistic')[0]->count;

        $this->assertTrue($count === 1, "TipCoupledStatistic relation inserting");

        $this->assertInstanceOf(TipCoupledStatistic::class, $tip->coupledStatistics->first());


    }

    public function testTipServiceCoupling() {
        $tipService = new \App\Tips\TipService();

        /** @var Tip $tip */
        $tip = factory(Tip::class)->create();
        /** @var CustomStatistic $statistic */
        $statistic = factory(CustomStatistic::class)->create();

        $tipCoupledStatistic = new TipCoupledStatistic([
            'statistic_id'        => $statistic->id,
            'tip_id'              => $tip->id,
            'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
            'threshold' => 0.5,
        ]);

        $tip->coupledStatistics()->save($tipCoupledStatistic);
        $this->assertInstanceOf(TipCoupledStatistic::class, $tip->coupledStatistics->first());
        $this->assertCount(1, $tip->coupledStatistics);
    }
}
