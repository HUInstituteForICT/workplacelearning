<?php


namespace App\Http\Controllers\Reflection;


use App\Reflection\Interfaces\ReflectionType;
use App\Reflection\Types\Abcd;
use App\Reflection\Types\Custom;
use App\Reflection\Types\Korthagen;
use App\Reflection\Types\Starr;

class RenderCreateForm
{

    private const typeClasses = [
        'STARR'     => Starr::class,
        'CUSTOM'    => Custom::class,
        'KORTHAGEN' => Korthagen::class,
        'ABCD'      => Abcd::class,
    ];


    public function __invoke(string $type)
    {
        /** @var ReflectionType $typeInstance */
        $className = self::typeClasses[$type];
        $typeInstance = new $className;
        $fields = $typeInstance->getFields();

        return view('pages.acting.reflection-form', ['fields' => $fields, 'type' => $type]);
    }
}