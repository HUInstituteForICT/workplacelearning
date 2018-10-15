<?php

namespace App\Repository\Eloquent;

use Spatie\TranslationLoader\LanguageLine;

class LanguageLineRepository
{
    public function get(int $id): LanguageLine
    {
        return LanguageLine::findOrFail($id);
    }

    public function save(LanguageLine $languageLine): bool
    {
        return $languageLine->save();
    }

    public function getLanguageLineForEntityByKey(string $key): ?LanguageLine
    {
        return LanguageLine::where('key', '=', $key)->first();
    }
}
