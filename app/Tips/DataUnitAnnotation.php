<?php


namespace App\Tips;


/**
 * @Annotation
 */
class DataUnitAnnotation
{
    public $name;
    public $method;
    public $hasParameters = false;
    public $parameterName = null;
}