<?php


use App\Tips\ActingCollector;
use App\WorkplaceLearningPeriod;
use Tests\TestCase;


class ActingCollectorTest extends TestCase
{
    public function testLearningActivityActing()
    {
        $wplp = WorkplaceLearningPeriod::create(["student_id" => 1, "wp_id" => 1, "startdate" => 1, "enddate" => 1, "nrofdays" => 1, "description" => "1" ]);
        $learningActivityActing = \App\LearningActivityActing::create(["wplp_id" => $wplp->wplp_id, "date" => "2017-01-01", ""]);
    }
}
