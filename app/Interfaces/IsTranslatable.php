<?php

declare(strict_types=1);

namespace App\Interfaces;

interface IsTranslatable
{
    public function getTranslationKey(): string;

    public function uniqueSlug(): string;
}
