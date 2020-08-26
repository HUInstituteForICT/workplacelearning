<?php

declare(strict_types=1);

namespace App\Rules;

use App\Repository\Eloquent\ResourceMaterialRepository;
use App\Repository\Eloquent\ResourcePersonRepository;
use App\Student;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ResourcePersonNotNull implements Rule
{
    /**
     * @var array
     */
    private $persistedResourcePersons;

    /**
     * @var ResourcePersonRepository
     */
    private $resourcePersonRepository;

    /**
     * @var ResourceMaterialRepository
     */
    private $resourceMaterialRepository;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(ResourcePersonRepository $resourcePersonRepository, ResourceMaterialRepository $resourceMaterialRepository)
    {
        $this->resourcePersonRepository = $resourcePersonRepository;
        $this->resourceMaterialRepository = $resourceMaterialRepository;
        $this->persistedResourcePersons = $this->resourcePersonRepository->resourcePersonsAvailableForStudent(Student::findOrFail(Auth::user()->getAuthIdentifier()));
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtolower($value);

        if (substr($value, 0, 7) === 'persoon' && trim((explode('-', $value)[1])) !== '') {
            return true;
        }
        elseif($value === 'internetbron' || $value === 'boek/artikel' || $value === 'alleen') {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '"Werken/leren met" is niet ingevuld';
    }
}
