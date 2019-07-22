<?php

namespace App\Traits;

trait TranslatableEntity
{
    public function localizedLabel(): string
    {
        // LanguageLine package overrides translator, fetches translations from DB, where translation key is either present or not
        // If it is not, the translation key is returned
        // If it is we can get a translation for current locale of request
        $translation = __($this->getTranslationKey(), []);

        // If there is no translation, return the default label value for the Entity
        if ($translation === $this->getTranslationKey() && method_exists($this, 'getLabel')) {
            return $this->getLabel();
        }

        return $translation;
    }

    public function getTranslationKey(): string
    {
        if (!isset($this->primaryKey)) {
            throw new \RuntimeException('Expected that primaryKey was set for model');
        }

        return 'entity.'.$this->uniqueSlug();
    }

    public function uniqueSlug(): string
    {
        return static::class.'-'.$this->{$this->primaryKey};
    }
}
