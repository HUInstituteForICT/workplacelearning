<?php

namespace App\Services;

use App\Category;
use App\Competence;
use App\ResourcePerson;
use App\Timeslot;
use App\Traits\TranslatableEntity;
use Spatie\TranslationLoader\LanguageLine;

class EntityTranslationManager
{
    private const entityTypes = [
        'competence' => Competence::class,
        'timeslot' => Timeslot::class,
        'resourcePerson' => ResourcePerson::class,
        'category' => Category::class,
    ];

    public function syncForEntity($entity, array $translations): LanguageLine
    {
        /** @var TranslatableEntity $entity */
        $languageLine = $this->getLanguageLineForEntityByKey($entity->uniqueSlug());

        if ($languageLine === null) {
            $languageLine = (new LanguageLine())->create([
                'group' => 'entity',
                'key' => $entity->uniqueSlug(),
                'text' => $translations,
            ]);
        } else {
            $languageLine->update(['text' => $translations]);
        }

        return $languageLine;
    }

    public function getTranslationsForEntity(string $entityType, int $id): array
    {
        $translationKey = self::entityTypes[$entityType].'-'.$id;

        return $this->getLanguageLineForEntityByKey($translationKey)->text ?? [];
    }

    private function getLanguageLineForEntityByKey(string $key): ?LanguageLine
    {
        return LanguageLine::where('key', '=', $key)->first();
    }
}
