<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: sivar
 * Date: 25/05/2018
 * Time: 17:01.
 */

namespace App\Analysis\Template;

abstract class ParameterType
{
    private $name;
    private $amountOfValues;

    public function __construct($name, $amountOfValues)
    {
        $this->name = $name;
        $this->amountOfValues = $amountOfValues;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAmountOfValues()
    {
        return $this->amountOfValues;
    }

    abstract public function isOfType(array $types);

    abstract public function getErrorMsg();
}
