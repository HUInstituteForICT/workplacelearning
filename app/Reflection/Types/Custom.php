<?php


namespace App\Reflection\Types;


use App\Interfaces\ReflectionType;

class Custom implements ReflectionType
{

    /**
     * Get the fields of the reflection type and their default values
     */
    public function getFields(): array
    {
        return ['reflection' => ''];
    }
}