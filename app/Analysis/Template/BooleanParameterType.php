<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:29.
 */

namespace App\Analysis\Template;

class BooleanParameterType extends ParameterType
{
    public function __construct()
    {
        parent::__construct('Boolean', 0);
    }

    // returns null if not true or false
    public function isOfType(array $types)
    {
        if (is_bool($types[0])) {
            return true;
        }
        $boolStr = strtolower($types[0]);

        return $boolStr == 'true' || $boolStr == 'false';
    }

    public function getErrorMsg()
    {
        return __('template.error.boolean');
    }
}
