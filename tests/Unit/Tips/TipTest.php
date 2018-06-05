<?php


use App\Student;
use App\Tips\Tip;
use App\Tips\TipCoupledStatistic;
use App\Tips\TipManager;


class TipTest extends \Tests\TestCase
{

    public function testTipLike() {
        $tip = factory(Tip::class)->create();
        $student = factory(Student::class)->create();

        $tipService = new TipManager();
        $result = $tipService->likeTip($tip, 1, $student);

        $this->assertTrue($result);

        $result = $tipService->likeTip($tip, 1, $student);

        $this->assertFalse($result);
    }

}
