<?php


namespace App\Tips;


interface EvaluatedTip
{
    public function getTip(): Tip;

    public function getTipText(): string;

    public function isPassing(): bool;
}