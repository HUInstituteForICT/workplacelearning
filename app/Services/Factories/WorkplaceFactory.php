<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Repository\Eloquent\WorkplaceRepository;
use App\Workplace;

class WorkplaceFactory
{
    /**
     * @var WorkplaceRepository
     */
    private $workplaceRepository;

    public function __construct(WorkplaceRepository $workplaceRepository)
    {
        $this->workplaceRepository = $workplaceRepository;
    }

    public function createWorkplace(array $data): Workplace
    {
        $workplace = new Workplace();

        $workplace->wp_name = $data['companyName'];
        $workplace->street = $data['companyStreet'];
        $workplace->housenr = $data['companyHousenr'];
        $workplace->postalcode = $data['companyPostalcode'];
        $workplace->town = $data['companyLocation'];
        $workplace->country = $data['companyCountry'];
        $workplace->contact_name = $data['contactPerson'];
        $workplace->contact_email = $data['contactEmail'];
        $workplace->contact_phone = $data['contactPhone'];
        $workplace->numberofemployees = 0;

        $this->workplaceRepository->save($workplace);

        return $workplace;
    }
}
