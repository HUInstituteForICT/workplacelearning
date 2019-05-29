<?php


namespace App\Reflection\Types;


use App\Reflection\Interfaces\ReflectionType;

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

            'task' => <<<TXT
General
- What was your task?


- What was your role?


- What was expected of you?


Personal
- What did you want to achieve?


- Wat did you expect of yourself in that situation?


- What did you think you had to do?

TXT
            ,'action' => <<<TXT
- What did you say or do?


- What was your approach?


- And then?


- How did other respond to that?


- What did you then say or do?


- And then?

TXT

            ,'result' => <<<TXT
- What came of it?


- How did it end?


- What was the result of your actions?


- How did others react?
TXT

            ,'reflection' => <<<TXT
- How do you think you did?


- Were you satisfied with the result?


- What would you do differently next time?


- What do you need for that?
TXT
            ,
        ];
    }
}