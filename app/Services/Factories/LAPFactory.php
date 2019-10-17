<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Category;
use App\Chain;
use App\ChainManager;
use App\Difficulty;
use App\LearningActivityProducing;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LAPFactory
{
    /**
     * @var ChainManager
     */
    private $chainFactory;
    private $data;
    /**
     * @var ResourcePersonFactory
     */
    private $resourcePersonFactory;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    public function __construct(
        ChainFactory $chainFactory,
        ResourcePersonFactory $resourcePersonFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->chainFactory = $chainFactory;
        $this->resourcePersonFactory = $resourcePersonFactory;
        $this->categoryFactory = $categoryFactory;
    }

    public function createLAP(array $data): LearningActivityProducing
    {
        $this->data = $data;
        if ($data['resource'] === 'new') {
            $data['resource'] = 'other';
        }

        $category = $this->getCategory($data['category_id']);

        $learningActivityProducing = new LearningActivityProducing();
        $learningActivityProducing->description = $data['omschrijving'];
        $learningActivityProducing->duration = $data['aantaluren'] !== 'x' ?
            $data['aantaluren'] :
            round(((int) $data['aantaluren_custom']) / 60, 2);
        $learningActivityProducing->date = Carbon::parse($data['datum'])->format('Y-m-d');

        $learningActivityProducing->extrafeedback = $data['extrafeedback'];

        // Set relations
        $learningActivityProducing->workplaceLearningPeriod()->associate(Auth::user()->getCurrentWorkplaceLearningPeriod());
        $learningActivityProducing->category()->associate($category);
        $learningActivityProducing->difficulty()->associate(Difficulty::findOrFail($data['moeilijkheid']));
        $learningActivityProducing->status()->associate(Status::findOrFail($data['status']));

        $chainId = ((int) $data['chain_id']);
        if ($chainId !== -1) {
            $chain = Chain::find($data['chain_id']);
            $learningActivityProducing->chain()->associate($chain);
        } elseif ($chainId === -1 && $learningActivityProducing->status->isBusy()) {
            $chain = $this->chainFactory->createChain([
                'name'    => __('New chain').' - '.substr($learningActivityProducing->description, 0, 15),
                'wplp_id' => Auth::user()->getCurrentWorkplaceLearningPeriod(),
            ]);
            $learningActivityProducing->chain()->associate($chain);
        }

        // Attach the resource used
        switch ($data['resource']) {
            case 'persoon':
                $learningActivityProducing->resourcePerson()->associate($this->getResourcePerson());
                break;
            case 'internet':
                $learningActivityProducing->resourceMaterial()->associate(ResourceMaterial::findOrFail(1));
                $learningActivityProducing->res_material_detail = $data['internetsource'];
                break;
            case 'boek':
                $learningActivityProducing->resourceMaterial()->associate(ResourceMaterial::findOrFail(2));
                $learningActivityProducing->res_material_detail = $data['booksource'];
                break;
        }

        $learningActivityProducing->save();

        return $learningActivityProducing;
    }

    private function getCategory($id): Category
    {
        if ($id === 'new') {
            return $this->categoryFactory->createCategory($this->data['newcat']);
        }

        return Category::find($id);
    }

    private function getResourcePerson(): ResourcePerson
    {
        return (new ResourcePerson())->find($this->data['resource_person_id']);
    }
}
