<?php
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:17
 */

namespace App\Analysis\Template;


class TextParameterType extends ParameterType
{

    public function __construct()
    {
        parent::__construct("Text", 0);
    }

    public function isOfType(array $types)
    {
        return is_string($types[0]);
    }

}