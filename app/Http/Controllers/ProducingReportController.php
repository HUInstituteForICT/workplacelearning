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
use Illuminate\Support\Facades\Lang;
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
        $page->addText(Lang::get('process_export.wordexport.by-intern'), $bold);
        $page->addText(Lang::get('process_export.wordexport.intern-name').": {$student->firstname} {$student->lastname} \t\t ".Lang::get('process_export.wordexport.organisation').": {$wp->wp_name}");
        $page->addText(Lang::get('process_export.wordexport.studentnr').": {$student->studentnr} \t\t ".Lang::get('process_export.wordexport.address').": {$wp->street} {$wp->housenr}, {$wp->postalcode}, {$wp->town}");
        $page->addText("\n\n".Lang::get('process_export.wordexport.total-days').": \t");
        $page->addText(Lang::get('process_export.wordexport.mentor').": \t");

        $page->addText("\n\n" . Lang::get('process_export.wordexport.by-workplace'), $bold);
        $date = Carbon::now();
        $page->addText(Lang::get('process_export.wordexport.name').": {$wp->contact_name} \t\t ".Lang::get('process_export.wordexport.date').": {$date->format('d-m-Y')}");
        $page->addText("\n".Lang::get('process_export.wordexport.confirmation'));
        $page->addText("\n\n".Lang::get('process_export.wordexport.signature').":");

        $page->addText("\n\n" .Lang::get('process_export.wordexport.remarks').":\n\n\n\n\n");

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
            LaravelLocalization::getCurrentLocaleRegional(),
            IntlDateFormatter::GREGORIAN,
            IntlDateFormatter::NONE,
            null,
            null,
            "EEE"
        );


        while (strtotime($date_loop) < strtotime($wplp->enddate) && strtotime($date_loop) < time()) {
            $table = $activityPage->addTable('table');
            $table->addRow();
            $table->addCell(2000)->addText(Lang::get('process_export.wordexport.week')." " . date('W' , strtotime($date_loop)), $bold);
            $table->addCell(2000)->addText(Lang::get('process_export.wordexport.date'), $bold);
            $table->addCell(8000)->addText(Lang::get('process_export.wordexport.activities'), $bold);
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
                    $table->addCell(8000)->addText(Lang::get('process_export.wordexport.absent'));
                }

                $days_this_week += ($hrs >= 7.5) ? 1 : 0;
                $date_loop = date('d-m-Y', strtotime("+1 day", strtotime($date_loop)));
            }
            $activityPage->addText(Lang::get('process_export.wordexport.days-worked').": " . ($days_this_week . (($days_this_week == 1) ? " ".Lang::get('process_export.wordexport.day') : " ".Lang::get('process_export.wordexport.days'))));
            $activityPage->addText(Lang::get('process_export.wordexport.absence-reason').": \n\n");
            $activityPage->addText(Lang::get('process_export.wordexport.remarks-week').": \n\n\n\n");

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
