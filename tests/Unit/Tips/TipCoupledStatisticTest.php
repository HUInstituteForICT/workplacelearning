<?php


use App\Tips\Statistic;

use App\Tips\TipCoupledStatistic;


class TipCoupledStatisticTest extends \Tests\TestCase
{
    public function testTipRelation() {
        /** @var \App\Tips\Tip $tip */
        $tip = factory(\App\Tips\Tip::class)->create();
        /** @var Statistic $statistic */
        $statistic = factory(Statistic::class)->create();

        $tip->statistics()->attach($statistic,
            [
                'comparison_operator' => TipCoupledStatistic::COMPARISON_OPERATOR_GREATER_THAN,
                'threshold' => 0.5,
                'multiplyBy100' => false,
            ]);

        $tip->save();

        $count = (int) DB::select('SELECT COUNT(*) as count FROM tip_coupled_statistic')[0]->count;

        $this->assertTrue($count === 1, "TipCoupledStatistic relation inserting");

        $this->assertInstanceOf(TipCoupledStatistic::class, $tip->statistics->first()->pivot);


    }
}
