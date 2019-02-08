<?php


namespace App\Tips\Statistics\Predefined;


use App\Tips\Statistics\Resultable;

interface PredefinedStatisticInterface
{
    public const ACTING_TYPE = 'Acting';
    public const PRODUCING_TYPE = 'Producing';

    public function getName(): string;
    public function getResultDescription(): string;
    public function calculate(): Resultable;
    public function getEducationProgramType(): string;

}