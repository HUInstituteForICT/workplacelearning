<?php


namespace App\Tips;


use App\WorkplaceLearningPeriod;

interface CollectorInterface
{
    public function __construct($year, $month, WorkplaceLearningPeriod $learningPeriod);
}