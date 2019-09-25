<?php

declare(strict_types=1);

namespace App\Services;

use App\Category;
use App\Chain;
use App\ChainManager;
use App\Difficulty;
use App\LearningActivityProducing;
use App\ResourceMaterial;
use App\Status;
use Carbon\Carbon;

class LAPUpdater
{
    /**
     * @var ChainManager
     */
    private $chainManager;

    public function __construct(ChainManager $chainManager)
    {
        $this->chainManager = $chainManager;
    }

    public function update(LearningActivityProducing $learningActivityProducing, array $data): bool
    {
        $learningActivityProducing->date = Carbon::parse($data['datum'])->format('Y-m-d');
        $learningActivityProducing->description = $data['omschrijving'];
        $learningActivityProducing->duration = $data['aantaluren'] !== 'x' ? $data['aantaluren'] : round(
            ((int) $data['aantaluren_custom']) / 60,
            2
        );

        switch ($data['resource']) {
            case 'persoon':
                $learningActivityProducing->resourcePerson()->associate($data['personsource']);
                $learningActivityProducing->resourceMaterial()->dissociate();
                $learningActivityProducing->res_material_detail = null;
                break;
            case 'internet':
                $learningActivityProducing->resourceMaterial()->associate((new ResourceMaterial())->find(1));
                $learningActivityProducing->res_material_detail = $data['internetsource'];
                $learningActivityProducing->resourcePerson()->dissociate();
                break;
            case 'boek':
                $learningActivityProducing->resourceMaterial()->associate((new ResourceMaterial())->find(2));
                $learningActivityProducing->res_material_detail = $data['booksource'];
                $learningActivityProducing->resourcePerson()->dissociate();
                break;
            case 'alleen':
                $learningActivityProducing->resourcePerson()->dissociate();
                $learningActivityProducing->resourceMaterial()->dissociate();
                $learningActivityProducing->res_material_detail = null;
                break;
        }

        $learningActivityProducing->category()->associate((new Category())->find($data['category_id']));
        $learningActivityProducing->difficulty()->associate((new Difficulty())->find($data['moeilijkheid']));
        $learningActivityProducing->status()->associate((new Status())->find($data['status']));

        $chainId = $data['chain_id'] ?? null;

        if ($chainId !== null) {
            if (((int) $chainId) === -1) {
                $learningActivityProducing->chain_id = null;
            } elseif (((int) $chainId) !== -1) {
                $chain = (new Chain())->find($chainId);
                if ($chain->status !== Chain::STATUS_FINISHED) {
                    $this->chainManager->attachActivity($learningActivityProducing, $chain);
                }
            }
        }

        return $learningActivityProducing->save();
    }
}
