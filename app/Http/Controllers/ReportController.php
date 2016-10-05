<?php
/**
 * This file (ReportController.php) was created on 05/15/2016 at 13:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

// Use the PHP native IntlDateFormatter (note: enable .dll in php.ini)
use App\Werkzaamheid;
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
    protected $pdf;
    private $html;
    // This array holds the view content that needs to be passed to the view (eg charts, images, text, etc)
    private $viewdata = [
    ];

    public function export(Request $request){
        if(Auth::user()->getCurrentInternshipPeriod() == null || Auth::user()->getCurrentInternshipPeriod()->getNumLoggedHours() == 0){
            return redirect('voortgang/1')->withErrors(['Je kan nog geen weekstaten exporteren. Je hebt geen actieve stage, of nog geen uren geregistreerd.']);
        }

        // Render the HTML as a view
        $fmt = new IntlDateFormatter(
            (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
            IntlDateFormatter::GREGORIAN,
            IntlDateFormatter::NONE,
            NULL,
            NULL,
            "EEE"
        );
        $wzh = $this->getWerkzaamheden();
        $view = view('templates.weekstaten')
            ->with('student',       Auth::user())
            ->with('stage',         Auth::user()->getCurrentInternship())
            ->with('stageperiode',  Auth::user()->getCurrentInternshipPeriod())
            ->with('date_loop', date('d-m-Y', strtotime('monday this week', strtotime(Auth::user()->getCurrentInternshipPeriod()->startdatum))))
            ->with('datefmt', $fmt)
            ->with('wzh_array', $wzh);
        $this->html = $view->render();

        // Load the filled HTML into the PDF and stream it to the user
        $this->pdf->loadHTML($this->html);
        return $this->pdf->stream(Auth::user()->studentnummer." ".
            Auth::user()->getInitials()." ".Auth::user()->achternaam.
            " - ".Auth::user()->getCurrentInternship()->bedrijfsnaam.".pdf");
    }

    public function show(){
        if(Auth::user()->getInternshipPeriods() != null){
            return view('pages.report');
        }
        // The user cannot view this page as they do not have any interships
        return redirect('home')->withErrors(['Je hebt geen actieve stage, en kan deze pagina niet inzien. Ga naar profiel om een stage toe te voegen of te activeren.']);
    }

    private function getWerkzaamheden(){
        $dataset = array();
        $allWerkzaamheden = DB::table('werkzaamheden')
            ->select(DB::raw("wzh_id as id,
                              wzh_datum as datum,
                              wzh_aantaluren as uren, 
                              wzh_omschrijving as omschrijving,
                              lerenmet,
                              lerenmetdetail,
                              mh_value as moeilijkheidsgraad, 
                              st_value as status
                              "))
            ->join('moeilijkheden', 'werkzaamheden.moeilijkheid_id', '=', 'mh_id')
            ->join('statussen', 'werkzaamheden.status_id', '=', 'st_id')
            ->where('student_stage_id', Auth::user()->getCurrentInternshipPeriod()->stud_stid)
            ->whereDate('wzh_datum', '>=', Auth::user()->getCurrentInternshipPeriod()->startdatum)
            ->whereDate('wzh_datum', '<=', Auth::user()->getCurrentInternshipPeriod()->einddatum)
            ->orderBy('datum', 'asc')
            ->orderBy('wzh_id', 'asc')
            ->get();
        foreach($allWerkzaamheden as $wzh){
            $dataset["".date('d-m-Y', strtotime($wzh->datum))][$wzh->id] = array(
                'date'          => $wzh->datum,
                'hours'         => $wzh->uren,
                'description'   => $wzh->omschrijving,
                'lerenmet'      => $wzh->lerenmet,
                'lerenmetdetail'=> $wzh->lerenmetdetail,
                'difficulty'    => $wzh->moeilijkheidsgraad,
                'status'        => $wzh->status,
            );
        }
        return $dataset;
    }

    public function __construct(){
        $this->middleware('auth');
        $this->pdf = App::make('dompdf.wrapper');
    }
}