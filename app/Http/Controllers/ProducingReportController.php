<?php
/**
 * This file (ProducingReportController.php) was created on 05/15/2016 at 13:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\LearningActivityProducing;
use IntlDateFormatter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\URL;

class ProducingReportController extends Controller
{
    protected $pdf;
    private $html;
    // This array holds the view content that needs to be passed to the view (eg charts, images, text, etc)
    private $viewdata = [
    ];

    public function export(Request $request){
        if(Auth::user()->getCurrentWorkplaceLearningPeriod() == null || Auth::user()->getCurrentWorkplaceLearningPeriod()->getNumLoggedHours() == 0){
            return redirect()->route('progress-producing', ['page' => 1])->withErrors(['Je kan nog geen weekstaten exporteren. Je hebt geen actieve stage, of nog geen uren geregistreerd.']);
        }

        // Render the HTML as a view
        $formatter = new IntlDateFormatter(
            (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
            IntlDateFormatter::GREGORIAN,
            IntlDateFormatter::NONE,
            NULL,
            NULL,
            "EEE"
        );
        $lap_array = $this->getWerkzaamheden();
        $view = view('templates.weekstaten')
            ->with('student',       Auth::user())
            ->with('stage',         Auth::user()->getCurrentWorkplace())
            ->with('stageperiode',  Auth::user()->getCurrentWorkplaceLearningPeriod())
            ->with('date_loop', date('d-m-Y', strtotime('monday this week', strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->startdate))))
            ->with('datefmt', $formatter)
            ->with('lap_array', $lap_array);
        $this->html = $view->render();

        // Load the filled HTML into the PDF and stream it to the user
        $this->pdf->loadHTML($this->html);
        return $this->pdf->stream(Auth::user()->studentnr." ".
            Auth::user()->getInitials()." ".Auth::user()->lastname.
            " - ".Auth::user()->getCurrentWorkplace()->wp_name.".pdf");
    }

    public function show(){
        if(Auth::user()->getInternshipPeriods() != null){
            return view('pages.report');
        }
        // The user cannot view this page as they do not have any interships
        return redirect()->route('home')->withErrors(['Je hebt geen actieve stage, en kan deze pagina niet inzien. Ga naar profiel om een stage toe te voegen of te activeren.']);
    }

    private function getWerkzaamheden(){
        $dataset = array();

        $allWerkzaamheden = DB::table('learningactivityproducing')
            ->select(DB::raw("lap_id,
                              wplp_id,
                              date,
                              duration,
                              description,
                              difficulty_label,
                              status_label           
            "))
            ->join('status', 'status.status_id', '=', 'learningactivityproducing.status_id')
            ->join('difficulty', 'difficulty.difficulty_id', '=', 'learningactivityproducing.difficulty_id')
            ->where('wplp_id', Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
            ->whereDate('date', '>=', Auth::user()->getCurrentWorkplaceLearningPeriod()->startdate)
            ->whereDate('date', '<=', Auth::user()->getCurrentWorkplaceLearningPeriod()->enddate)
            ->orderBy('date', 'asc')
            ->orderBy('lap_id', 'asc')
            ->get();

        foreach($allWerkzaamheden as $lap){

            $dataset["".date('d-m-Y', strtotime($lap->date))][$lap->lap_id] = array(
                'date'          => $lap->date,
                'duration'      => $lap->duration,
                'description'   => $lap->description,
                'difficulty'    => $lap->difficulty_label,
                'status'        => $lap->status_label,
            );
        }
        return $dataset;
    }

    public function __construct(){
        $this->pdf = App::make('dompdf.wrapper');
    }
}