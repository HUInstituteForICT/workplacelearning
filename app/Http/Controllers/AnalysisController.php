<?php
/**
 * This file (AnalysisController.php) was created on 08/31/2016 at 14:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Werkzaamheid;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller {

    public function showChoiceScreen(){
        if(Auth::user()->getCurrentInternshipPeriod() == null) return redirect('home')->withErrors(["Je kan deze pagina niet bekijken zonder actieve stage."]);
        if(!Auth::user()->getCurrentInternshipPeriod()->hasLoggedHours()) return redirect('home')->withErrors(["Je hebt nog geen uren geregistreerd voor deze stage."]);

        return view('pages.analysis.choice')
                ->with('numhours', $this->getNumHoursByDate("all", "all"));
    }

    public function showDetail(Request $r, $year, $month){
        // If no data or not enough data, redirect to analysis choice page
        if(Auth::user()->getCurrentInternshipPeriod() == null) return redirect('analysis')->with('error', 'Je hebt geen actieve stage ingesteld!');

        if(($year != "all" && $month != "all")
            && (0 == preg_match('/^(201)([0-9]{1})$/', $year) || 0 == preg_match('/^([0-9]{2})$/', $month))
        ) return redirect('analysis');

        $task_chains = $this->getTaskChainsByDate(25, $year, $month);
        if(count($task_chains) == 0) return redirect('analyse')->withErrors(['Je hebt geen activiteiten ingevuld voor deze maand.']);

        // Analysis array
        $a = array();
        $a['avg_difficulty']        = $this->getAverageDifficultyByDate($year, $month);
        $a['num_difficult_wzh']     = $this->getNumDifficultTasksByDate($year, $month);
        $a['hours_difficult_wzh']   = $this->getHoursDifficultTasksByDate($year, $month);
        $a['most_occuring_category']= $this->getMostOccuringCategoryByDate($year, $month);
        $a['category_difficulty']   = $this->getCategoryDifficultyByDate($year, $month);
        $a['num_hours_alone']       = $this->getNumHoursAlone($year, $month);
        $a['category_difficulty']   = $this->getCategoryDifficultyByDate($year, $month);
        $a['num_hours']             = $this->getNumHoursByDate($year, $month);
        $a['num_wzh']               = $this->getNumTasksByDate($year, $month);
        $a['num_hours_category']    = $this->getNumHoursCategory($year, $month);

        return view('pages.analysis.detail')
                ->with('analysis', $a)
                ->with('chains', $task_chains)
                ->with('monthno', $month);
    }

    public function getNumHoursAlone($year, $month){
        $wzh_collection = Werkzaamheid::where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid)
            ->where('lerenmet', 'alleen');
        return $this->limitCollectionByDate($wzh_collection, $year, $month)->sum('wzh_aantaluren');
    }

    public function getNumDifficultTasksByDate($year, $month){
        $wzh_collection = Werkzaamheid::where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid)
                            ->where('moeilijkheid_id', 3);
        return $this->limitCollectionByDate($wzh_collection, $year, $month)->count();
    }

    public function getHoursDifficultTasksByDate($year, $month){
        $wzh_collection = Werkzaamheid::where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid)
            ->where('moeilijkheid_id', 3);
        return $this->limitCollectionByDate($wzh_collection, $year, $month)->sum('wzh_aantaluren');
    }

    public function LimitCollectionByDate($collection, $year, $month){
        if($year != "all" && $month != "all"){
            $dtime = mktime(0,0,0,intval($month),1,intval($year));
            $collection->whereDate('wzh_datum', '>=', date('Y-m-d', $dtime))
                ->whereDate('wzh_datum', '<=', date('Y-m-d', strtotime("+1 month", $dtime)));
        }
        return $collection;
    }

    public function getTaskChainsByDate($amt = 50, $year, $month){
        // First, fetch the tasks that "start" the chain.
        $wzh_start = Werkzaamheid::where('prev_wzh_id', NULL)
                        ->where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid);
                        /*->whereIn('wzh_id', function($query){
                            $query->select('prev_wzh_id')
                                    ->from('werkzaamheden');
                        });*/ // Disabled for now, enable this to only show task chains and hide single tasks in the analysis.
        $wzh_start = $this->limitCollectionByDate($wzh_start, $year, $month)->orderBy('wzh_datum', 'desc')->take($amt)->get();

        // Iterate over the array and add tasks that follow.
        $task_chains = array();
        foreach($wzh_start as $w){
            $arr_key = count($task_chains);
            $nw = $w->getNextWerkzaamheid();
            $task_chains[$arr_key][] = $w;
            if(is_null($nw)) continue;
            $task_chains[$arr_key][] = $nw;
            while(($nw = $nw->getNextWerkzaamheid()) != NULL){
                $task_chains[$arr_key][] = $nw;
            }
        }
        // Workaround: Get end dates and reverse from there, then array unique the duplicates
        $wzh_end = Werkzaamheid::whereNotNull('prev_wzh_id')
                        ->where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid)
                        ->whereNotIn('wzh_id', function($query){
                           $query->select('prev_wzh_id')
                                    ->from('werkzaamheden')
                                    ->whereNotNull('prev_wzh_id');
                        });
        $wzh_end = $this->LimitCollectionByDate($wzh_end, $year, $month)->orderBy('wzh_datum', 'desc')->take($amt)->get();
        foreach($wzh_end as $w){
            $arr_key = count($task_chains);
            $pw = $w->getPreviousWerkzaamheid();
            $task_chains[$arr_key][] = $w;
            if(is_null($pw)) continue;
            array_unshift($task_chains[$arr_key], $pw);
            while(($pw = $pw->getPreviousWerkzaamheid()) != NULL){
                array_unshift($task_chains[$arr_key], $pw);
            }
        }
        $task_chains = array_unique($task_chains, SORT_REGULAR);
        return $task_chains;
    }

    public function getAverageDifficultyByDate($year, $month){
        $wzh_collection = Werkzaamheid::where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid);
        $wzh_collection = $this->limitCollectionByDate($wzh_collection, $year, $month);
        return ($wzh_collection->count() == 0) ? 0 : ($wzh_collection->sum('moeilijkheid_id')/$wzh_collection->count())*3.33;
    }

    public function getCategoryDifficultyByDate($year, $month){
        $result = DB::table('werkzaamheden')
            ->select(DB::raw('cg_value as name, (AVG(moeilijkheid_id)*3.33) as difficulty'))
            ->join('categorieen', 'werkzaamheden.categorie_id', '=', 'categorieen.cg_id')
            ->where('student_stage_id', '=', Auth::user()->getCurrentInternshipPeriod()->stud_stid);
        $result = $this->LimitCollectionByDate($result, $year, $month);
        $result = $result->groupBy('categorie_id')->orderBy('difficulty', 'desc')->get();
        return $result;
    }

    public function getMostOccuringCategoryByDate($year, $month){
        $result = DB::table('werkzaamheden')
                    ->select(DB::raw('cg_value as name, COUNT(cg_id) AS count, SUM(wzh_aantaluren) as aantaluren'))
                    ->join('categorieen', 'werkzaamheden.categorie_id', '=', 'categorieen.cg_id')
                    ->where('student_stage_id', '=', Auth::user()->getCurrentInternshipPeriod()->stud_stid);
        $result = $this->LimitCollectionByDate($result, $year, $month);
        $result = $result->groupBy('categorie_id')->orderBy('count', 'desc')->first();
        return $result;
    }

    public function getNumHoursByDate($year, $month){
        $wzh_collection = Werkzaamheid::where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid);
        return $this->limitCollectionByDate($wzh_collection, $year, $month)->sum('wzh_aantaluren');
    }

    public function getNumTasksByDate($year, $month){
        $wzh_collection = Werkzaamheid::where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid);
        return $this->limitCollectionByDate($wzh_collection, $year, $month)->count('wzh_aantaluren');
    }

    public function getNumHoursCategory($year, $month) {
        $result = DB::table('werkzaamheden')
                    ->select(DB::raw('cg_value as name, SUM(wzh_aantaluren) as totalhours'))
                    ->join('categorieen', 'werkzaamheden.categorie_id', '=', 'categorieen.cg_id')
                    ->where('student_stage_id', '=', Auth::user()->getCurrentInternshipPeriod()->stud_stid);
        $result = $this->LimitCollectionByDate($result, $year, $month);

        return $result->groupBy('categorie_id')->get();
    }

    public function __construct(){
        $this->middleware('auth');
    }
}
