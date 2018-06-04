<?php
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:29
 */

namespace App\Analysis\Template;


class BooleanParameterType extends ParameterType
{
    public function __construct()
    {
        parent::__construct("Boolean", 0);
    }

    public function isOfType(array $types)
    {
        return is_bool($types[0]);
    }

}
