<?php

namespace App\Http\Controllers\Acting;

use App\Services\CurrentUserResolver;
use Illuminate\Http\Request;

class StoreReflectionUserSettings
{
    /**
     * @var CurrentUserResolver
     */
    private $userResolver;

    public function __construct(CurrentUserResolver $userResolver)
    {
        $this->userResolver = $userResolver;
    }

    public function __invoke(Request $request)
    {
        $reflectionSettings = $request->get('reflectionSettings');
        $student = $this->userResolver->getCurrentUser();

        $student->setUserSetting('shortReflection', $reflectionSettings['shortReflection']);
        $student->setUserSetting('fullReflection', $reflectionSettings['fullReflection']);

        return response(['status' => 'success', 'message' => 'Update reflection preferences']);
    }
}
