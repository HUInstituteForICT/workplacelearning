<?php

namespace App\Interfaces;

interface IsTranslatable
{
    public function getTranslationKey(): string;

    public function uniqueSlug(): string;
}
