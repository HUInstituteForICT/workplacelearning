<?php

declare(strict_types=1);

namespace App\Services;

use App\Category;
use App\Competence;
use App\Repository\Eloquent\LanguageLineRepository;
use App\ResourcePerson;
use App\Timeslot;
use Spatie\TranslationLoader\LanguageLine;

class EntityTranslationManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider translationsDataProvider
     */
    public function testGetTranslationsForEntity(string $entityType, int $id, string $className): void
    {
        $repository = $this->createMock(LanguageLineRepository::class);

        $repository->expects(self::once())->method('getLanguageLineForEntityByKey')->with($className.'-'.$id)->willReturn(new LanguageLine(['text' => ['en' => $entityType]]));

        $entityTranslationManager = new EntityTranslationManager($repository);

        $translation = $entityTranslationManager->getTranslationsForEntity($entityType, $id);
        $this->assertSame(['en' => $entityType], $translation);
    }

    public function translationsDataProvider(): array
    {
        return [
            ['competence', 1, Competence::class],
            ['timeslot', 1, Timeslot::class],
            ['resourcePerson', 1, ResourcePerson::class],
            ['category', 1, Category::class],
        ];
    }

    public function testSyncForEntity(): void
    {
        $entity = $this->createMock(Category::class);
        $entity->expects(self::once())->method('uniqueSlug')->willReturn('category1');

        $translations = ['en' => 'test translation'];

        $languageLine = $this->createMock(LanguageLine::class);

        $repository = $this->createMock(LanguageLineRepository::class);

        $repository->expects(self::once())->method('getLanguageLineForEntityByKey')->with('category1')
            ->willReturn($languageLine);

        $repository->expects(self::once())->method('save')->with($languageLine)->willReturn(true);

        $entityTranslationManager = new EntityTranslationManager($repository);
        $result = $entityTranslationManager->syncForEntity($entity, $translations);

        $this->assertSame($languageLine, $result);
    }
}
