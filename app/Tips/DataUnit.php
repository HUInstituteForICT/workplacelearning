<?php


namespace App\Tips;

/**
 * Class DataUnit
 * @package App\Tips
 */
class DataUnit
{
    /** @var string $method Method that will be called on the data collector */
    private $method;

    /** @var string $value Optional value that will be filtered with */
    private $value;

    public function __construct($method, $value)
    {
        $this->method = $method;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }


}