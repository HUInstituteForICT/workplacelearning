<?php

namespace App\Reflection\Types;

use App\Reflection\Interfaces\ReflectionType;

class Pdca implements ReflectionType
{
    use TypeFieldsTrait;

    private static $translationNamespace = 'pdca';

    /**
     * Get the fields of the reflection type and their default values.
     */
    public function getFields(): array
    {
        // Get an array of the translation keys
        return $this->translationKeysFromFields(['plan', 'do', 'check', 'act']);
    }
}
