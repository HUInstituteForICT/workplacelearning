<?php

namespace App\Analysis\Producing;

use App\LearningActivityProducing;
use App\Services\CurrentPeriodResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ProducingAnalysisCollector gives access to raw data about user's activities.
 */
class ProducingAnalysisCollector
{

    /**
     * @var CurrentPeriodResolver
     */
    private $currentPeriodResolver;

    public function __construct(CurrentPeriodResolver $currentPeriodResolver)
    {
        $this->currentPeriodResolver = $currentPeriodResolver;
    }

    /**
     * Get the hours the user spent working alone.
     *
     * @param $year
     * @param $month
     */
    public function getNumHoursAlone($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->whereNull('res_person_id')
            ->whereNull('res_material_id');

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Limit a Collection from a certain date and +1 month.
     *
     * @param $collection
     * @param $year
     * @param $month
     */
    public function limitCollectionByDate($collection, $year, $month)
    {
        if ($year !== 'all' && $month !== 'all') {
            $dtime = mktime(0, 0, 0, (int)$month, 1, (int)$year);
            $collection->whereDate('date', '>=', date('Y-m-d', $dtime))
                ->whereDate('date', '<=', date('Y-m-d', strtotime('+1 month', $dtime)));
        }

        return $collection;
    }

    /**
     * Get the number tasks of the user.
     *
     * @param $year
     * @param $month
     */
    public function getNumTotalTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->count();
    }

    /**
     * Get the number of easy tasks of the user.
     *
     * @param $year
     * @param $month
     */
    public function getNumEasyTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 1);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->count();
    }

    /**
     * Get the number of easy tasks of the user.
     *
     * @param $year
     * @param $month
     */
    public function getNumAverageTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 2);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->count();
    }

    /**
     * Get the number of difficult tasks of the user.
     *
     * @param $year
     * @param $month
     */
    public function getNumDifficultTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 3);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->count();
    }

    /**
     * Get the hours of easy tasks of the user.
     *
     * @param $year
     * @param $month
     */
    public function getHoursEasyTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 1);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Get the hours of average tasks of the user.
     *
     * @param $year
     * @param $month
     */
    public function getHoursAverageTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 2);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Get the hours of difficult tasks of the user.
     *
     * @param $year
     * @param $month
     */
    public function getHoursDifficultTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 3);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Get all task chains of the user within a certain date range.
     *
     * @param int $amount
     * @param     $year
     * @param     $month
     *
     * @return array
     */
    public function getTaskChainsByDate($amount = 50, $year, $month)
    {
        // First, fetch the tasks that "start" the chain.
        $lap_start = LearningActivityProducing::where('prev_lap_id', null)
            ->where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);

        /*->whereIn('lap_id', function($query){
            $query->select('prev_lap_id')
                    ->from('learningactivityproducing');
        });*/ // Disabled for now, enable this to only show task chains and hide single tasks in the analysis.
        $lap_start = $this->limitCollectionByDate($lap_start, $year, $month)->orderBy('date',
            'desc')->take($amount)->get();

        // Iterate over the array and add tasks that follow.
        $task_chains = [];
        foreach ($lap_start as $w) {
            $arr_key = count($task_chains);
            $nw = $w->getNextLearningActivity();
            $task_chains[$arr_key][] = $w;
            if (is_null($nw)) {
                continue;
            }
            $task_chains[$arr_key][] = $nw;
            while (null != ($nw = $nw->getNextLearningActivity())) {
                $task_chains[$arr_key][] = $nw;
            }
        }
        // Workaround: Get end dates and reverse from there, then array unique the duplicates
        $lap_end = LearningActivityProducing::whereNotNull('prev_lap_id')
            ->where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->whereNotIn('lap_id', function ($query): void {
                $query->select('prev_lap_id')
                    ->from('learningactivityproducing')
                    ->whereNotNull('prev_lap_id');
            });

        $lap_end = $this->limitCollectionByDate($lap_end, $year, $month)->orderBy('date', 'desc')->take($amount)->get();
        foreach ($lap_end as $w) {
            $arr_key = count($task_chains);
            $pw = $w->getPrevousLearningActivity();
            $task_chains[$arr_key][] = $w;
            if (is_null($pw)) {
                continue;
            }
            array_unshift($task_chains[$arr_key], $pw);
            while (null != ($pw = $pw->getPrevousLearningActivity())) {
                array_unshift($task_chains[$arr_key], $pw);
            }
        }

        $task_chains = array_unique($task_chains, SORT_REGULAR);

        return $task_chains;
    }

    /**
     * Get the average difficulty of tasks by date.
     *
     * @param $year
     * @param $month
     *
     * @return float|int
     */
    public function getAverageDifficultyByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);
        $lap_collection = $this->limitCollectionByDate($lap_collection, $year, $month);
        if ($lap_collection->count() === 0) {
            return 0;
        }

        return $lap_collection->sum('difficulty_id') / $lap_collection->count() * 3.33;
    }

    /**
     * Get the category by difficulties filtered by date range.
     *
     * @param $year
     * @param $month
     *
     * @return Collection
     */
    public function getCategoryDifficultyByDate($year, $month)
    {
        $result = DB::table('learningactivityproducing')
            ->select(DB::raw('category_label as name, (AVG(learningactivityproducing.difficulty_id)*3.33) as difficulty'))
            ->join('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->where('learningactivityproducing.wplp_id', '=',
                Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);
        $result = $this->limitCollectionByDate($result, $year, $month);
        $result = $result->groupBy('learningactivityproducing.category_id')->orderBy('difficulty', 'desc')->get();

        return $result;
    }

    /**
     * Get average difficulty with resource person by date range.
     *
     * @param $year
     * @param $month
     *
     * @return Collection
     */
    public function getResourcePersonDifficultyByDate($year, $month)
    {
        $result = DB::table('learningactivityproducing')
            ->select(DB::raw('person_label as name, COUNT(*) as difficult_activities'))
            ->join('resourceperson', 'learningactivityproducing.res_person_id', '=', 'resourceperson.rp_id')
            ->where('learningactivityproducing.wplp_id', '=',
                Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', '=', 3);
        $result = $this->limitCollectionByDate($result, $year, $month);

        $result = $result->groupBy('learningactivityproducing.res_person_id')->orderBy('difficult_activities', 'asc')
            ->limit(1)->get();

        return $result;
    }

    /**
     * Get the most occurring category filtered by date range.
     *
     * @param $year
     * @param $month
     *
     * @return $this|mixed
     */
    public function getMostOccuringCategoryByDate($year, $month)
    {
        $result = DB::table('learningactivityproducing')
            ->select(DB::raw('category_label as name, COUNT(learningactivityproducing.category_id) AS count, SUM(duration) as aantaluren'))
            ->join('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->where(
                'learningactivityproducing.wplp_id',
                '=',
                Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id
            );
        $result = $this->limitCollectionByDate($result, $year, $month);
        $result = $result->groupBy('learningactivityproducing.category_id')->orderBy('count', 'desc')->first();

        return $result;
    }

    /**
     * Get the number of hours of activities filtered by date range.
     *
     * @param $year
     * @param $month
     */
    public function getNumHoursByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Get the amount of full working days (>7.5 hours || wplp defined hours amount).
     *
     * @param $year
     * @param $month
     */
    public function getFullWorkingDays()
    {
        try {
            return $this->currentPeriodResolver->getPeriod()->getEffectiveDays();
        } catch (\RuntimeException $e) {
            return 0;
        }
    }

    /**
     * Get the number of tasks filtered by date range.
     *
     * @param $year
     * @param $month
     */
    public function getNumTasksByDate($year, $month)
    {
        $lap_collection = LearningActivityProducing::where('wplp_id',
            Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->count('duration');
    }

    /**
     * Get the number of hours in a category filtered by date range.
     *
     * @param $year
     * @param $month
     *
     * @return Collection
     */
    public function getNumHoursCategory($year, $month)
    {
        $result = DB::table('learningactivityproducing')
            ->select(DB::raw('category_label as name, SUM(duration) as totalhours'))
            ->join('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->where(
                'learningactivityproducing.wplp_id',
                '=',
                Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id
            );
        $result = $this->limitCollectionByDate($result, $year, $month);

        return $result->groupBy('learningactivityproducing.category_id')->get();
    }
}
