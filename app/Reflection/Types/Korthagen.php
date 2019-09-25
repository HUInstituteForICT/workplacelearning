<?php

declare(strict_types=1);

namespace App\Reflection\Types;

use App\Reflection\Interfaces\ReflectionType;

class Korthagen implements ReflectionType
{
    private static $translationNamespace = 'korthagen';

    use TypeFieldsTrait;

    /**
     * Get the fields of the reflection type and their default values.
     */
    public function getFields(): array
    {
        return $this->translationKeysFromFields(['fase1', 'fase2', 'fase3', 'fase4']);
    }
}
