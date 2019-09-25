<?php

declare(strict_types=1);

namespace App\Services;

use App\Category;
use App\Competence;
use App\Repository\Eloquent\LanguageLineRepository;
use App\ResourcePerson;
use App\Timeslot;
use App\Tips\Models\Tip;
use App\Traits\TranslatableEntity;
use Spatie\TranslationLoader\LanguageLine;

class EntityTranslationManager
{
    private const entityTypes = [
        'competence'     => Competence::class,
        'timeslot'       => Timeslot::class,
        'resourcePerson' => ResourcePerson::class,
        'category'       => Category::class,
        'tip'            => Tip::class,
    ];

    /**
     * @var LanguageLineRepository
     */
    private $languageLineRepository;

    public function __construct(LanguageLineRepository $languageLineRepository)
    {
        $this->languageLineRepository = $languageLineRepository;
    }

    public function syncForEntity($entity, array $translations): LanguageLine
    {
        /** @var TranslatableEntity $entity */
        $languageLine = $this->languageLineRepository->getLanguageLineForEntityByKey($entity->uniqueSlug());

        if ($languageLine === null) {
            $languageLine = new LanguageLine();
            $languageLine->fill([
                'group' => 'entity',
                'key'   => $entity->uniqueSlug(),
                'text'  => $translations,
            ]);
        } else {
            $languageLine->text = $translations;
        }

        $this->languageLineRepository->save($languageLine);

        return $languageLine;
    }

    public function getTranslationsForEntity(string $entityType, int $id): array
    {
        $translationKey = self::entityTypes[$entityType].'-'.$id;

        return $this->languageLineRepository->getLanguageLineForEntityByKey($translationKey)->text ?? [];
    }
}
