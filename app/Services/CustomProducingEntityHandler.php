<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Factories\CategoryFactory;
use App\Services\Factories\ResourcePersonFactory;

class CustomProducingEntityHandler
{
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var ResourcePersonFactory
     */
    private $resourcePersonFactory;

    public function __construct(CategoryFactory $categoryFactory, ResourcePersonFactory $resourcePersonFactory)
    {
        $this->categoryFactory = $categoryFactory;
        $this->resourcePersonFactory = $resourcePersonFactory;
    }

    /**
     * If the student added new entities, create those and then set their ids so the factory can be kept simple.
     */
    public function process(array $data): array
    {
        if ($data['category_id'] === 'new') {
            $data['category_id'] = $this->categoryFactory->createCategory($data['newcat'])->category_id;
        }

        if ($data['personsource'] === 'new' && $data['resource'] === 'persoon') {
            $data['resource_person_id'] = $this->resourcePersonFactory->createResourcePerson($data['newswv'])->rp_id;
        } else {
            $data['resource_person_id'] = $data['personsource'];
        }

        if ((int) $data['chain_id'] === -1) {
            $data['chain_id'] = null;
        } else {
            $data['chain_id'] = (int) $data['chain_id'];
        }

        return $data;
    }
}
