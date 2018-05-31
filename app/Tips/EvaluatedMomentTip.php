<?php


namespace App\Tips;


class EvaluatedMomentTip implements EvaluatedTip
{
    /**
     * @var Tip
     */
    private $tip;
    /**
     * @var bool
     */
    private $passes;
    /**
     * @var int
     */
    private $daysPercentage;

    public function __construct(Tip $tip, string $daysPercentage, bool $passes)
    {
        $this->tip = $tip;
        $this->daysPercentage = $daysPercentage;
        $this->passes = $passes;
    }

    public function getTip(): Tip
    {
        return $this->tip;
    }

    public function isPassing(): bool
    {
        return $this->passes;
    }

    public function getTipText(): string
    {
        $tip = $this->tip;

        $tipText = $tip->tipText;

        return str_replace(':days-percentage', $this->daysPercentage . '%', $tipText);
    }

}