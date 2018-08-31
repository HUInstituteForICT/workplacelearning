<?php

namespace App\Tips\DataCollectors;

/**
 * @Annotation
 */
class DataUnitAnnotation
{
    public $name;
    public $method;
    public $hasParameters = false;
    public $parameterName = null;
    public $type;
    public $valueParameterDescription = '';
    public $epType = '';
}
