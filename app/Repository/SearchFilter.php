<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
    public const TYPE_TEXT = 'text';
    public const TYPE_SELECT = 'select';

    /** @var string */
    private $property;

    /** @var string */
    private $fieldType;

    /** @var ?string */
    private $translationKey;

    /** @var ?array */
    private $options;

    /** @var ?callable */
    private $filterClosure;

    public function __construct(
        string $property,
        string $fieldType = self::TYPE_TEXT,
        ?string $translationKey = null,
        ?array $options = null,
        ?callable $filterClosure = null
    ) {
        $this->property = $property;
        $this->fieldType = $fieldType;
        $this->translationKey = $translationKey;
        $this->options = $options;
        $this->filterClosure = $filterClosure;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getTranslationKey(): string
    {
        return $this->translationKey ?? $this->property;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function applyFilter(Builder $builder, $value): void
    {
        $callable = $this->filterClosure ?? $this->genericFilterClosures()[$this->fieldType];
        $callable($builder, $value);
    }

    private function genericFilterClosures(): array
    {
        return [
            self::TYPE_TEXT   => function (Builder $builder, ?string $value): void {
                if (!$value || empty($value)) {
                    return;
                }

                $builder->where($this->property, 'like', '%'.$value.'%');
            },
            self::TYPE_SELECT => function (Builder $builder, ?string $value): void {
                // Try to account for "no selection"
                if (!$value || (int) $value === -1) {
                    return;
                }
                $builder->where($this->property, $value);
            },
        ];
    }

    public function isTextType(): bool
    {
        return $this->fieldType === self::TYPE_TEXT;
    }

    public function isSelectType(): bool
    {
        return $this->fieldType === self::TYPE_SELECT;
    }
}
