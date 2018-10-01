<?php

namespace App\Services\Factories;

use App\LearningActivityProducing;
use App\Repository\Eloquent\LearningActivityProducingRepository;
use App\Services\CurrentPeriodResolver;
use Carbon\Carbon;

class LAPFactory
{
    /**
     * @var CurrentPeriodResolver
     */
    private $currentPeriodResolver;
    /**
     * @var LearningActivityProducingRepository
     */
    private $learningActivityProducingRepository;

    public function __construct(
        CurrentPeriodResolver $currentPeriodResolver,
        LearningActivityProducingRepository $learningActivityProducingRepository
    ) {
        $this->currentPeriodResolver = $currentPeriodResolver;
        $this->learningActivityProducingRepository = $learningActivityProducingRepository;
    }

    public function createLAP(array $data): LearningActivityProducing
    {
        $currentPeriod = $this->currentPeriodResolver->getPeriod();

        $learningActivityProducing = new LearningActivityProducing();
        $learningActivityProducing->description = $data['omschrijving'];
        $learningActivityProducing->duration = $data['aantaluren'] !== 'x' ?
            $data['aantaluren'] :
            round(((int) $data['aantaluren_custom']) / 60, 2);
        $learningActivityProducing->date = Carbon::parse($data['datum'])->format('Y-m-d');

        // Set relations
        $learningActivityProducing->workplaceLearningPeriod()->associate($currentPeriod);
        $learningActivityProducing->category()->associate($data['category_id']);
        $learningActivityProducing->difficulty()->associate($data['moeilijkheid']);
        $learningActivityProducing->status()->associate($data['status']);
        $learningActivityProducing->chain()->associate($data['chain_id']);

        // Attach the resource used
        switch ($data['resource']) {
            case 'persoon':
                $learningActivityProducing->resourcePerson()->associate($data['resource_person_id']);
                break;
            case 'internet':
                $learningActivityProducing->resourceMaterial()->associate(1); // 1 is default Internet ResourceMaterial id
                $learningActivityProducing->res_material_detail = $data['internetsource'];
                break;
            case 'boek':
                $learningActivityProducing->resourceMaterial()->associate(2); // 2 is default Book ResourceMaterial id
                $learningActivityProducing->res_material_detail = $data['booksource'];
                break;
        }

        $this->learningActivityProducingRepository->save($learningActivityProducing);

        return $learningActivityProducing;
    }
}
