<?php


namespace App\Http\Controllers\Reflection;


use App\Reflection\Models\ActivityReflection;
use App\Reflection\Repository\Eloquent\ActivityReflectionRepository;
use App\Services\CurrentUserResolver;
use App\Reflection\Services\Exporter;
use Illuminate\Http\Request;


class DownloadMultiple
{

    /**
     * @var Exporter
     */
    private $exporter;
    /**
     * @var ActivityReflectionRepository
     */
    private $activityReflectionRepository;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(
        Exporter $exporter,
        ActivityReflectionRepository $activityReflectionRepository,
        CurrentUserResolver $currentUserResolver
    ) {
        $this->exporter = $exporter;
        $this->activityReflectionRepository = $activityReflectionRepository;
        $this->currentUserResolver = $currentUserResolver;
    }


    public function __invoke(Request $request)
    {
        ob_start();
        $ids = $request->get('ids', []);
        $activityReflections = $this->activityReflectionRepository->getMany($ids);

        // Check if user can access all of these ids
        $user = $this->currentUserResolver->getCurrentUser();
        array_map(static function (ActivityReflection $activityReflection) use ($user) {
            abort_unless($user->can('view', $activityReflection), 403);
        }, $activityReflections);

        $document = $this->exporter->exportReflections($activityReflections);

        $document->save(strtolower(__('reflection.reflection')) . 's.docx', 'Word2007', true);
        // We need to quit Laravel otherwise the docx will get corrupted
        exit;
    }
}