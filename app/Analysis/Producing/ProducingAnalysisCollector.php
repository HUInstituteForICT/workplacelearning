<?php


namespace App\Analysis\Producing;


use App\LearningActivityProducing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ProducingAnalysisCollector gives access to raw data about user's activities
 * @package App\Analysis\Producing
 */
class ProducingAnalysisCollector
{
    /**
     * Get the hours the user spent working alone
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getNumHoursAlone($year, $month){
        $lap_collection = LearningActivityProducing::where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->whereNull('res_person_id')
            ->whereNull('res_material_id');

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Get the number of difficult tasks of the user
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getNumDifficultTasksByDate($year, $month){
        $lap_collection = LearningActivityProducing::where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 3);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->count();
    }

    /**
     * Get the hours of difficult tasks of the user
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getHoursDifficultTasksByDate($year, $month){
        $lap_collection = LearningActivityProducing::where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->where('difficulty_id', 3);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Limit a Collection from a certain date and +1 month
     *
     * @param $collection
     * @param $year
     * @param $month
     * @return mixed
     */
    public function LimitCollectionByDate($collection, $year, $month){
        if($year != "all" && $month != "all"){
            $dtime = mktime(0,0,0,intval($month),1,intval($year));
            $collection->whereDate('date', '>=', date('Y-m-d', $dtime))
                ->whereDate('date', '<=', date('Y-m-d', strtotime("+1 month", $dtime)));
        }

        return $collection;
    }

    /**
     * Get all task chains of the user within a certain date range
     *
     * @param int $amount
     * @param $year
     * @param $month
     * @return array
     */
    public function getTaskChainsByDate($amount = 50, $year, $month){
        // First, fetch the tasks that "start" the chain.
        $lap_start = LearningActivityProducing::where('prev_lap_id', NULL)
            ->where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);
        /*->whereIn('lap_id', function($query){
            $query->select('prev_lap_id')
                    ->from('learningactivityproducing');
        });*/ // Disabled for now, enable this to only show task chains and hide single tasks in the analysis.
        $lap_start = $this->limitCollectionByDate($lap_start, $year, $month)->orderBy('date', 'desc')->take($amount)->get();

        // Iterate over the array and add tasks that follow.
        $task_chains = array();
        foreach($lap_start as $w){
            $arr_key = count($task_chains);
            $nw = $w->getNextLearningActivity();
            $task_chains[$arr_key][] = $w;
            if(is_null($nw)) continue;
            $task_chains[$arr_key][] = $nw;
            while(($nw = $nw->getNextLearningActivity()) != NULL){
                $task_chains[$arr_key][] = $nw;
            }
        }
        // Workaround: Get end dates and reverse from there, then array unique the duplicates
        $lap_end = LearningActivityProducing::whereNotNull('prev_lap_id')
            ->where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->whereNotIn('lap_id', function ($query) {
                $query->select('prev_lap_id')
                    ->from('learningactivityproducing')
                    ->whereNotNull('prev_lap_id');
            });

        $lap_end = $this->LimitCollectionByDate($lap_end, $year, $month)->orderBy('date', 'desc')->take($amount)->get();
        foreach($lap_end as $w){
            $arr_key = count($task_chains);
            $pw = $w->getPrevousLearningActivity();
            $task_chains[$arr_key][] = $w;
            if(is_null($pw)) continue;
            array_unshift($task_chains[$arr_key], $pw);
            while(($pw = $pw->getPrevousLearningActivity()) != NULL){
                array_unshift($task_chains[$arr_key], $pw);
            }
        }

        $task_chains = array_unique($task_chains, SORT_REGULAR);

        return $task_chains;
    }

    /**
     * Get the average difficulty of tasks by date
     *
     * @param $year
     * @param $month
     * @return float|int
     */
    public function getAverageDifficultyByDate($year, $month){
        $lap_collection = LearningActivityProducing::where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);
        $lap_collection = $this->limitCollectionByDate($lap_collection, $year, $month);

        return ($lap_collection->count() == 0) ? 0 : ($lap_collection->sum('difficulty_id')/$lap_collection->count())*3.33;
    }

    /**
     * Get the category by difficulties filtered by date range
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getCategoryDifficultyByDate($year, $month){
        $result = DB::table('learningactivityproducing')
            ->select(DB::raw('category_label as name, (AVG(learningactivityproducing.difficulty_id)*3.33) as difficulty'))
            ->join('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->where('learningactivityproducing.wplp_id', '=', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);
        $result = $this->LimitCollectionByDate($result, $year, $month);
        $result = $result->groupBy('learningactivityproducing.category_id')->orderBy('difficulty', 'desc')->get();

        return $result;
    }

    /**
     * Get the most occurring category filtered by date range
     *
     * @param $year
     * @param $month
     * @return $this|mixed
     */
    public function getMostOccuringCategoryByDate($year, $month){
        $result = DB::table('learningactivityproducing')
            ->select(DB::raw('category_label as name, COUNT(learningactivityproducing.category_id) AS count, SUM(duration) as aantaluren'))
            ->join('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->where('learningactivityproducing.wplp_id', '=',
                Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);
        $result = $this->LimitCollectionByDate($result, $year, $month);
        $result = $result->groupBy('learningactivityproducing.category_id')->orderBy('count', 'desc')->first();

        return $result;
    }

    /**
     * Get the number of hours of activities filtered by date range
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getNumHoursByDate($year, $month){
        $lap_collection = LearningActivityProducing::where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->sum('duration');
    }

    /**
     * Get the amount of full working days (>7.5 hours)
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getFullWorkingDays($year, $month){
        // Retrieve the number of days the student worked at least 7.5 hours
        $result = LearningActivityProducing::where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->groupBy('date')
            ->havingRaw('SUM(duration)>=7.5');

        return $result->get()->count();
    }

    /**
     * Get the number of tasks filtered by date range
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getNumTasksByDate($year, $month){
        $lap_collection = LearningActivityProducing::where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);

        return $this->limitCollectionByDate($lap_collection, $year, $month)->count('duration');
    }

    /**
     * Get the number of hours in a category filtered by date range
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function getNumHoursCategory($year, $month) {
        $result = DB::table('learningactivityproducing')
            ->select(DB::raw('category_label as name, SUM(duration) as totalhours'))
            ->join('category', 'learningactivityproducing.category_id', '=', 'category.category_id')
            ->where('learningactivityproducing.wplp_id', '=',
                Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id);
        $result = $this->LimitCollectionByDate($result, $year, $month);

        return $result->groupBy('learningactivityproducing.category_id')->get();
    }
}