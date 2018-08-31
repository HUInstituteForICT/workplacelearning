<?php

namespace App\Tips;

use App\Tips\Models\Tip;

interface EvaluatedTipInterface
{
    public function getTip(): Tip;

    public function getTipText(): string;

    public function isPassing(): bool;

    public function addTextParameter(TextParameter $textParameter): void;

    public function addEvaluationResult(bool $passed): void;
}
