<?php


namespace App\Tips\Statistics;


interface Resultable
{
    public function getResultString(): string;

    public function doThresholdComparison(int $threshold, int $operator);

    public function hasPassed(): bool;

    public function getName(): string;
}