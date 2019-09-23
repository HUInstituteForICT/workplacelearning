<?php

namespace App\Reflection\Types;

use App\Reflection\Interfaces\ReflectionType;

class Starr implements ReflectionType
{
    use TypeFieldsTrait;

    private static $translationNamespace = 'starr';

    /**
     * Get the fields of the reflection type and their default values.
     */
    public function getFields(): array
    {
        // Get an array of the translation keys
        return $this->translationKeysFromFields(['situation', 'task', 'action', 'result', 'reflection']);
    }
}
