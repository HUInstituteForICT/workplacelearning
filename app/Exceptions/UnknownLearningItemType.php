<?php
declare(strict_types=1);

namespace App\Exceptions;


class UnknownLearningItemType extends \Exception
{
    protected $message = 'Did you pass a saved learning item type?';
}
