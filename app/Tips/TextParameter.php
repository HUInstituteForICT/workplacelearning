<?php


namespace App\Tips;


class TextParameter
{
    /**
     * @var string
     */
    private $placeholder;
    /**
     * @var int|float|string
     */
    private $value;

    public function __construct(string $placeholder, $value)
    {

        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function apply(string $tipText)
    {
        return str_replace($this->placeholder, $this->value, $tipText);
    }
}