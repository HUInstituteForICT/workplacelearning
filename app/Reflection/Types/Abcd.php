<?php


namespace App\Reflection\Types;


use App\Reflection\Interfaces\ReflectionType;

class Abcd implements ReflectionType
{
    use TypeFieldsTrait;

    private static $translationNamespace = 'abcd';

    /**
     * Get the fields of the reflection type and their default values
     */
    public function getFields(): array
    {
        // Get an array of the translation keys
        return $this->translationKeysFromFields(['cause', 'importance', 'conclusion', 'todo']);
    }
}