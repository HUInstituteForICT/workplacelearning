<?php


namespace App\Tips;


use App\Tips\Models\Tip;

class EvaluatedTip implements EvaluatedTipInterface
{

    /**
     * @var Tip
     */
    private $tip;
    /**
     * @var TextParameter[]|array
     */
    private $textParameters = [];

    /**
     * @var bool[]|array
     */
    private $evaluationResults = [];

    public function __construct(Tip $tip)
    {
        $this->tip = $tip;
    }

    public function getTip(): Tip
    {
        return $this->tip;
    }

    public function getTipText(): string
    {
        $tipText = $this->tip->tipText;
        array_walk($this->textParameters, function (TextParameter $textParameter) use (&$tipText) {
            $tipText = $textParameter->apply($tipText);
        });

        return $tipText;
    }

    public function isPassing(): bool
    {
        if(\count($this->evaluationResults) === 0) {
            return false;
        }

        return collect($this->evaluationResults)->every(function (bool $result) {
            return $result === true;
        });
    }

    public function addTextParameter(TextParameter $textParameter): void
    {
        $this->textParameters[] = $textParameter;
    }

    public function addEvaluationResult(bool $passed): void
    {
        $this->evaluationResults[] = $passed;
    }
}