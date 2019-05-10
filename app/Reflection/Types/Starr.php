<?php


namespace App\Reflection\Types;


use App\Interfaces\ReflectionType;

class Starr implements ReflectionType
{

    /**
     * Get the fields of the reflection type and their default values
     */
    public function getFields(): array
    {
        return [
            'situation' => <<<TXT
What was the situation?

- What happened?


- Who were involved?


- Where did it happen?


- When did it happen?

TXT
            ,

        ];
    }
}