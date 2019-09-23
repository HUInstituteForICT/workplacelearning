<?php

declare(strict_types=1);

namespace App\Tips\Statistics;

/**
 * Used because sometimes student doesn't have a valid result for calculating, thus return a fake result.
 */
class InvalidStatisticResult implements Resultable
{
    public function getResultString(): string
    {
        return '';
    }

    public function doThresholdComparison(float $threshold, int $operator): void
    {
    }

    public function hasPassed(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return '';
    }
}
