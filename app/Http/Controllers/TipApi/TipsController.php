<?php

declare(strict_types=1);

namespace App\Http\Controllers\TipApi;

use App\Cohort;
use App\EducationProgram;
use App\EducationProgramType;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipUpdateRequest;
use App\Services\EntityTranslationManager;
use App\Tips\Models\CustomStatistic;
use App\Tips\Models\StatisticVariable;
use App\Tips\Models\Tip;
use App\Tips\Services\TipManager;
use App\Tips\Statistics\Predefined\PredefinedStatisticInterface;
use App\Tips\Statistics\Predefined\PredefinedStatisticsProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TipsController extends Controller
{
    private function getStatistics()
    {
        $statistics = array_merge(
            CustomStatistic::with('statisticVariableOne',
                'statisticVariableTwo')->get()->toArray()
        );

        // Add predefined statistics to the collection because they can also be chosen but aren't in the DB

        $predefined = array_map(function (string $className) {
            /** @var PredefinedStatisticInterface $predefinedStatistic */
            $predefinedStatistic = new $className();

            $id = $predefinedStatistic->getEducationProgramType() === PredefinedStatisticInterface::PRODUCING_TYPE ?
                'p-p-'.md5($predefinedStatistic->getName()) :
                'p-a-'.md5($predefinedStatistic->getName());

            return [
                'name'                   => $predefinedStatistic->getName(),
                'className'              => get_class($predefinedStatistic),
                'epType'                 => $predefinedStatistic->getEducationProgramType(),
                'id'                     => $id,
                'education_program_type' => strtolower($predefinedStatistic->getEducationProgramType()),
                'type'                   => 'predefinedstatistic',
            ];
        }, PredefinedStatisticsProvider::getPredefinedStatisticClassNames());

        return array_merge($statistics, $predefined);
    }

    /**
     * Display a listing of the resource.
     *
     *
     * @throws \Exception
     */
    public function index(): array
    {
        $tips = Tip::with('coupledStatistics', 'enabledCohorts', 'likes', 'studentTipViews', 'moments')->get();
        $statistics = $this->getStatistics();

        return [
            'educationProgramTypes' => EducationProgramType::all(),
            'tips'                  => $tips,
            'cohorts'               => Cohort::all(),
            'educationPrograms'     => (new EducationProgram())->orderBy('ep_name', 'ASC')->get(),
            'statistics'            => $statistics,
            'statisticVariables'    => StatisticVariable::$availableFilters,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return Collection|Model
     */
    public function store(Request $request, TipManager $service)
    {
        $tip = $service->createTip([
            'name'            => trans('tips.new'),
            'shownInAnalysis' => true,
        ]);
        $tip->save();

        return Tip::with('coupledStatistics', 'enabledCohorts', 'likes', 'studentTipViews',
            'moments')->findOrFail($tip->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     */
    public function update(TipUpdateRequest $request, $id, EntityTranslationManager $entityTranslationManager): Tip
    {
        /** @var Tip $tip */
        $tip = (new Tip())->with('coupledStatistics', 'enabledCohorts', 'likes', 'studentTipViews')->findOrFail($id);
        $tip->name = $request->input('tip.name');
        $tip->tipText = $request->input('tip.tipText');
        $tip->showInAnalysis = $request->input('tip.showInAnalysis') ?: false;
        if ($tip->trigger === 'moment') {
            $tip->rangeStart = (int) $request->input('tip.rangeStart');
            $tip->rangeEnd = (int) $request->input('tip.rangeEnd');
        }
        $tip->save();
        $tip->enabledCohorts()->sync($request->input('tip.enabled_cohorts'));

        if ($request->has('translations')) {
            $entityTranslationManager->syncForEntity($tip, $request->input('translations'));
        }

        return $tip;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Tip $tip */
        $tip = (new Tip())->findOrFail($id);
        $tip->coupledStatistics()->delete();
        $tip->enabledCohorts()->detach();
        $tip->likes()->delete();
        $tip->delete();

        return response()->json([], 200);
    }

    public function updateCohorts(Request $request, Tip $tip, TipManager $tipService)
    {
        $tip = $tipService->enableCohorts($tip, $request->all());

        return redirect()->route('tips.edit', ['id' => $tip->id]);
    }

    public function likeTip(Tip $tip, TipManager $tipService, Request $request)
    {
        $liked = $tipService->likeTip($tip, (int) $request->get('type', 1), $request->user());

        return response()->json(['status' => $liked ? 'success' : 'error']);
    }
}
