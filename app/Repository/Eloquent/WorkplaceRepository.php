<?php

declare(strict_types=1);

namespace App\Repository\Eloquent;

use App\Workplace;

class WorkplaceRepository
{
    public function get(int $id): Workplace
    {
        return Workplace::findOrFail($id);
    }
    
    public function getAll()
    {
        return Workplace::all();
    }

    public function save(Workplace $workplace): bool
    {
        return $workplace->save();
    }

    public function update(Workplace $workplace, array $data): bool
    {
        $workplace->wp_name = $data['companyName'];
        $workplace->street = $data['companyStreet'];
        $workplace->housenr = $data['companyHousenr'];
        $workplace->postalcode = $data['companyPostalcode'];
        $workplace->town = $data['companyLocation'];
        $workplace->country = $data['companyCountry'];
        $workplace->contact_name = $data['contactPerson'];
        $workplace->contact_email = $data['contactEmail'];
        $workplace->contact_phone = $data['contactPhone'];

        return $workplace->save();
    }
}
