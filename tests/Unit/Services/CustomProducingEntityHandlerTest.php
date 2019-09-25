<?php

declare(strict_types=1);

namespace App\Services;

use App\Category;
use App\ResourcePerson;
use App\Services\Factories\CategoryFactory;
use App\Services\Factories\ResourcePersonFactory;

class CustomProducingEntityHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider data
     */
    public function testProcess(array $testData): void
    {
        $category = $this->createMock(Category::class);
        $category->expects(self::once())->method('__get')->with('category_id')->willReturn(1);

        $categoryFactory = $this->createMock(CategoryFactory::class);
        $categoryFactory->expects(self::once())->method('createCategory')->with($testData['newcat'])->willReturn($category);

        $resourcePerson = $this->createMock(ResourcePerson::class);
        $resourcePerson->expects(self::once())->method('__get')->with('rp_id')->willReturn(1);

        $resourcePersonFactory = $this->createMock(ResourcePersonFactory::class);
        $resourcePersonFactory->expects(self::once())->method('createResourcePerson')->with($testData['newswv'])->willReturn($resourcePerson);

        $handler = new CustomProducingEntityHandler($categoryFactory, $resourcePersonFactory);

        $transformedData = $handler->process($testData);

        $this->assertSame(1, $transformedData['category_id']);
        $this->assertSame(1, $transformedData['resource_person_id']);

        if ($testData['chain_id'] === -1) {
            $this->assertNull($transformedData['chain_id']);
        } else {
            $this->assertSame($testData['chain_id'], $transformedData['chain_id']);
        }
    }

    public function data(): array
    {
        return [
            [
                [
                    'category_id'  => 'new',
                    'newcat'       => 'Some cat',
                    'personsource' => 'new',
                    'newswv'       => 'Some person name',
                    'chain_id'     => 1,
                ],
            ],
            [
                [
                    'category_id'  => 'new',
                    'newcat'       => 'Some cat',
                    'personsource' => 'new',
                    'newswv'       => 'Some person name',
                    'chain_id'     => -1,
                ],
            ],
        ];
    }
}
