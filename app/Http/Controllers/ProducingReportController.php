<?php
/**
 * This file (ProducingReportController.php) was created on 05/15/2016 at 13:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use IntlDateFormatter;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;

class ProducingReportController extends Controller
{
    protected $pdf;
    private $html;
    // This array holds the view content that needs to be passed to the view (eg charts, images, text, etc)
    private $viewdata = [
    ];

    public function wordExport()
    {
        $student = Auth::user();
        $wp = $student->getCurrentWorkplace();

        $bold = tap(new Font())->setBold(true);

        $w = new PhpWord();
        $page = $w->addSection();
        $page->addText("In te vullen door de stagiair", $bold);
        $page->addText("Naam Stagiair: {$student->firstname} {$student->lastname} \t\t Stageverlenende organisatie: {$wp->wp_name}");
        $page->addText("Studentnummer: {$student->studentnr} \t\t Adres: {$wp->street} {$wp->housenr}, {$wp->postalcode}, {$wp->town}");
        $page->addText("\n\nTotaal aantal dagen stage gelopen: \t");
        $page->addText("Stagedocent: \t");

        $page->addText("\n\nIn te vullen door stageplek", $bold);
        $date = Carbon::now();
        $page->addText("Naam: {$wp->contact_name} \t\t Datum: {$date->format('d-m-Y')}");
        $page->addText("\nBevestiging, namens het bedrijf, dat het aantal dagen dat stage is gelopen, hierboven naar waarheid is ingevuld.");
        $page->addText("\n\nHandtekening:");

        $page->addText("\n\nOpmerkingen van de stageverlenende organisatie:\n\n\n\n\n");

        $activityPage = $page;

        $tableStyle = [
            'borderColor' => '006699',
            'borderSize'  => 6,
            'cellMargin'  => 50,
        ];
        $firstRowStyle = ['bgColor' => '66BBFF'];
        $w->addTableStyle('table', $tableStyle, $firstRowStyle);
        $lap_array = $this->getWerkzaamheden();

        $wplp = $student->getCurrentWorkplaceLearningPeriod();
        $date_loop = date('Y-m-d',
            strtotime('monday this week', strtotime(Auth::user()->getCurrentWorkplaceLearningPeriod()->startdate)));
        $datefmt = $formatter = new IntlDateFormatter(
            (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
            IntlDateFormatter::GREGORIAN,
            IntlDateFormatter::NONE,
            null,
            null,
            "EEE"
        );


        while (strtotime($date_loop) < strtotime($wplp->enddate) && strtotime($date_loop) < time()) {
            $table = $activityPage->addTable('table');
            $table->addRow();
            $table->addCell(2000)->addText("Week " . date('W' , strtotime($date_loop)), $bold);
            $table->addCell(2000)->addText("Datum", $bold);
            $table->addCell(8000)->addText("Werkzaamheden", $bold);
            $weekno = 1;

            $days_this_week = 0;
            for ($i = 1; $i <= 5; $i++) {

                $table->addRow();
                $table->addCell(2000)->addText(ucwords($datefmt->format(strtotime($date_loop))));
                $table->addCell(2000)->addText($date_loop);

                $hrs = 0;
                if (array_key_exists("" . date('d-m-Y', strtotime($date_loop)), $lap_array)) {
                    foreach ($lap_array["" . date('d-m-Y', strtotime($date_loop))] as $lap) {
                        $hrs += $lap['duration'];
                        $table->addCell(8000)->addText("- {$lap['description']}");
                    }
                } else {
                    $table->addCell(8000)->addText("Absent");
                }

                $days_this_week += ($hrs >= 7.5) ? 1 : 0;
                $date_loop = date('d-m-Y', strtotime("+1 day", strtotime($date_loop)));
            }
            $activityPage->addText("Aantal dagen gewerkt (7,5 uur of meer): " . ($days_this_week . (($days_this_week == 1) ? " dag" : " dagen")));
            $activityPage->addText("Reden eventuele absentie: \n\n");
            $activityPage->addText("Opmerkingen: \n\n\n\n");

            $date_loop = date('d-m-Y', strtotime("+2 days", strtotime($date_loop)));


        }

        $fileName = Auth::user()->studentnr." ".
            Auth::user()->getInitials()." ".Auth::user()->lastname.
            " - ".Auth::user()->getCurrentWorkplace()->wp_name;

        $w->save("{$fileName}.docx", "Word2007", true);
    }

    private function getWerkzaamheden()
    {
        $dataset = [];
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
        foreach ($allWerkzaamheden as $lap) {
            $dataset["".date('d-m-Y', strtotime($lap->date))][$lap->lap_id] = [
                'date'          => $lap->date,
                'duration'      => $lap->duration,
                'description'   => $lap->description,
                'difficulty'    => $lap->difficulty_label,
                'status'        => $lap->status_label,
            ];
        }
        return $dataset;
    }
}
