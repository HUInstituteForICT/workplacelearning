<?php

declare(strict_types=1);

namespace App\Reflection\Types;

trait TypeFieldsTrait
{
    protected function translationKeysFromFields(array $fields): array
    {
        return array_reduce($fields, static function (array $carry, string $field) {
            $translationKeyField = str_replace([' ', '.', '   '], '-', $field);
            $transNamespace = self::$translationNamespace;
            $carry[$field] = "reflection.types.{$transNamespace}.{$translationKeyField}";

            return $carry;
        }, []);
    }
}
