<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:27.
 */

namespace App\Analysis\Template;

class NumberParameterType extends ParameterType
{
    public function __construct()
    {
        parent::__construct('Number', 0);
    }

    public function isOfType(array $types)
    {
        return is_numeric($types[0]);
    }

    public function getErrorMsg()
    {
        return __('template.error.number');
    }
}
