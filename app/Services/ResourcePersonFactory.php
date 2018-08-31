<?php

namespace App\Services;

use App\ResourcePerson;
use Illuminate\Support\Facades\Auth;

class ResourcePersonFactory
{
    public function createResourcePerson(string $label): ResourcePerson
    {
        $resourcePerson = new ResourcePerson();
        $resourcePerson->person_label = $label;
        $resourcePerson->wplp_id = Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id;
        $resourcePerson->ep_id = Auth::user()->getEducationProgram()->ep_id; //deprecated, not necessary, bound to wplp..?
        $resourcePerson->save();

        return $resourcePerson;
    }
}
