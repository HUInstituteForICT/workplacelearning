<?php
declare(strict_types=1);

namespace App\Exceptions;


class UnlinkedInternshipException extends \Exception
{
    protected $message = 'This internship is not yet linked to a teacher.';
}
