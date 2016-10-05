<?php
/**
 * This file (ReportController.php) was created on 05/15/2016 at 13:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)
use IntlDateFormatter;
use App\Chart;
use App\InternshipPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\URL;

class ReportController extends Controller
{
    // The Internship period over which the report is being made
    protected $internshipPeriod = null;
    // report options array
    protected $options = [];
    // Default Options array
    protected $defaultOptions = [
        "dateRangeFrom" => null,
        "dateRangeTill" => null,
        "ext_opts"    => [
            "ShowPersonalData"      => false,
            "showRegisteredHours"   => true,
            "WorkBehavior"          => true,
            "ShowSuggestions"       => true,
            "ShowCooperations"      => true,
            "ShowSolutions"         => true,
            "DispTextOnly"          => false,
        ],
    ];
    protected $pdf;
    // This is the HTML Code loaded by the ReportController as a string.
    private $html;
    // This array holds the view content that needs to be passed to the view (eg charts, images, text, etc)
    private $viewdata = [
    ];

    public function create(Request $request){
        // Select the Internship Period
        $internshipperiodID = $request->input("internshipPeriodID");
        $this->internshipPeriod = InternshipPeriod::find($internshipperiodID)->where('student_id', '=', Auth::user()->stud_id)->first();

        // Return with error if user doesn't have internships or logged hours
        if(!$this->internshipPeriod || !$this->internshipPeriod->hasLoggedHours()) return view('pages.report')->with('warning', "Je hebt nog geen weekstaten ingevuld voor deze stage.");
        // Set the view data according to the input given by the user.
        $this->viewdata['student']['studentnummer'] = Auth::user()->getStudentnummerString();
        $this->viewdata['student']['voornaam']      = Auth::user()->voornaam;
        $this->viewdata['student']['achternaam']    = Auth::user()->achternaam;
        $this->viewdata['student']['initialen']     = Auth::user()->getInitials();
        // Internship Related data
        //$this->viewdata['internship']['companyName']= $this->internshipPeriod->internship()->bedrijfsnaam;
        $this->viewdata['internship']['startDate']  = $this->internshipPeriod->getStartDate();
        $this->viewdata['internship']['endDate']    = $this->internshipPeriod->getEndDate();
        // Report Related data
        $this->viewdata['report']['startDate']      = $request->input('dateRangeFrom');
        $this->viewdata['report']['endDate']        = $request->input('dateRangeTill');

        // Prepare queried data
        $this->prepareData();
        // Prepare the required charts data
        $this->prepareCharts();

        // Render the HTML as a view
        $view = view('templates.reportDEFAULT')->with('data', $this->viewdata);
        $this->html = $view->render();

        // Load the filled HTML into the PDF and stream it to the user
        $this->pdf = App::make('dompdf.wrapper');
        $this->pdf->loadHTML($this->html);
        return $this->pdf->stream();
    }

    private function prepareData(){
        $allWerkzaamheden = DB::table('werkzaamheden')
            ->select(DB::raw("wzh_id as id,
                              wzh_datum as datum,
                              wzh_aantaluren as uren, 
                              wzh_omschrijving as omschrijving,
                              swv_value as samenwerkingsverband,
                              mh_value as moeilijkheidsgraad,
                              st_value as status"))
            ->join('samenwerkingsverbanden', 'werkzaamheden.samenwerkingsverband_id', '=', 'swv_id')
            ->join('moeilijkheden', 'werkzaamheden.moeilijkheid_id', '=', 'mh_id')
            ->join('statussen', 'werkzaamheden.status_id', '=', 'st_id')
            ->where('student_stage_id', $this->internshipPeriod->stud_stid)
            ->whereDate('wzh_datum', '>=', $this->viewdata['report']['startDate'])
            ->whereDate('wzh_datum', '<=', $this->viewdata['report']['endDate'])
            ->orderBy('datum', 'asc')->get();
        foreach($allWerkzaamheden as $wzh){
            $this->viewdata['werkzaamheden'][$wzh->id] = array(
                'date'          => $wzh->datum,
                'hours'         => $wzh->uren,
                'description'   => $wzh->omschrijving,
                'cooperation'   => $wzh->samenwerkingsverband,
                'difficulty'    => $wzh->moeilijkheidsgraad,
                'status'        => $wzh->status,
            );
        }
        var_dump($allWerkzaamheden);
        die();
    }

    private function prepareCharts(){
        // First, use query builder to fetch the tasks grouped by month and category.
        $eloSet = DB::table('werkzaamheden')
            ->select(DB::raw('MONTH(wzh_datum) as maandnr, SUM(wzh_aantaluren) as uren, cg_value as categorie'))
            ->join('categorieen', 'werkzaamheden.categorie_id', '=', 'categorieen.cg_id')
            ->where('student_stage_id', $this->internshipPeriod->stud_stid)
            ->whereDate('wzh_datum', '>=', $this->viewdata['report']['startDate'])
            ->whereDate('wzh_datum', '<=', $this->viewdata['report']['endDate'])
            ->groupBy('categorie_id')
            ->groupBy(DB::raw('MONTH(wzh_datum)'))
            ->orderBy('maandnr', 'asc')->get();
        
        // Convert the dataset to a usable array (EG $monthnumber = array('categorie' => 'aantal besteedde uren' ...)
        $dataset = array();
        foreach($eloSet as $row){
            $dataset[$row->maandnr][$row->categorie] = $row->uren;
        }

        // Repeat this process for all tasks, inject the total amount into the dataset
        $overallData = DB::table('werkzaamheden')
            ->select(DB::raw('SUM(wzh_aantaluren) as uren, cg_value as categorie'))
            ->join('categorieen', 'werkzaamheden.categorie_id', '=', 'categorieen.cg_id')
            ->where('student_stage_id', $this->internshipPeriod->stud_stid)
            ->whereDate('wzh_datum', '>=', $this->viewdata['report']['startDate'])
            ->whereDate('wzh_datum', '<=', $this->viewdata['report']['endDate'])
            ->groupBy('categorie_id')
            ->get();
        foreach($overallData as $row){
            $dataset["Globaal"][$row->categorie] = $row->uren;
        }

        // Create new Chart objects for each row and save to DB
        foreach($dataset as $month => $values){
            $chart = new Chart;
            // Put the month name as title
            if($month !== "Globaal") {
                $fmt = new IntlDateFormatter(
                    (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
                    IntlDateFormatter::GREGORIAN,
                    IntlDateFormatter::NONE,
                    NULL,
                    NULL,
                    "MMMM"
                );
                $chart->title = "Verdeling Werktijd (".ucwords($fmt->format(mktime(0, 0, 0, $month, 1, 1970))).")";
            } else {
                $chart->title = "Verdeling Werktijd (Totaal)";
            }
            $chart->student_id = Auth::user()->stud_id;
            $chart->setDataset($values);
            $chart->setDate("now");
            $chart->save();
            // Now, create a URL to the chart so it can be referenced
            $this->viewdata['taskChartPerMonth'][$month] = URL::to('/chart/generate/'.$chart->id);
        }
    }

    public function reportHours(){
        // First, verify there are records for the user
        if(!Auth::user()->getCurrentInternshipPeriod()->hasLoggedHours()){
            return; // No data
        }
       
        // Get the month in which the latest (current) internship started.
        $month_start = Auth::user()->getCurrentInternship()->getStartMonth();
    }

    public function show(){
        if(Auth::user()->getInternshipPeriods() != null){
            return view('pages.report');
        }
        // The user cannot view this page as they do not have any interships
        return redirect('home')->withErrors(['Je hebt geen actieve stage, en kan deze pagina niet inzien. Ga naar profiel om een stage toe te voegen of te activeren.']);
    }

    public function __construct(){
        $this->middleware('auth');
    }
}