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

    public function wordExport(Request $request)
    {
        $student = Auth::user();
        $wp = $student->getCurrentWorkplace();

        $bold = tap(new Font())->setBold(true);

        $w = new PhpWord();
        $page = $w->addSection();
        $page->addText(Lang::get('process_export.wordexport.by-intern'), $bold);
        $internTable = $page->addTable("");

        $internTable->addRow();
        // Intern name
        $internTable->addCell(2000)->addText(Lang::get('process_export.wordexport.intern-name').": ");
        $internTable->addCell(2500)->addText("{$student->firstname} {$student->lastname}");
        // Organisation
        $internTable->addCell(2000)->addText(Lang::get('process_export.wordexport.organisation').": ");
        $internTable->addCell(2000)->addText($wp->wp_name);
        $internTable->addRow();
        // Student nr
        $internTable->addCell(2000)->addText(Lang::get('process_export.wordexport.studentnr').": ");
        $internTable->addCell()->addText($student->studentnr);
        // Address org
        $internTable->addCell()->addText(Lang::get('process_export.wordexport.address').": ");
        $internTable->addCell()->addText("{$wp->street} {$wp->housenr}, {$wp->postalcode}, {$wp->town}");
        $internTable->addRow();
        // total days
        $internTable->addCell()->addText(Lang::get('process_export.wordexport.total-days').": ");
        $internTable->addCell()->addText("");
        $internTable->addRow();
        // Mentor
        $internTable->addCell()->addText(Lang::get('process_export.wordexport.mentor').": ");
        $internTable->addCell()->addText("");


        $page->addText("\n\n" . Lang::get('process_export.wordexport.by-workplace'), $bold);
        $orgTable = $page->addTable();
        $orgTable->addRow(900);

        // name contact
        $orgTable->addCell(2000)->addText(Lang::get('process_export.wordexport.name').": ");
        $orgTable->addCell(2500)->addText($wp->contact_name);
        // Date
        $date = Carbon::now();
        $orgTable->addCell(2000)->addText(Lang::get('process_export.wordexport.date').": ");
        $orgTable->addCell(2000)->addText($date->format('d-m-Y'));
        $orgTable->addRow(1200);
        $confirmCell = $orgTable->addCell();
        $confirmCell->addText(Lang::get('process_export.wordexport.confirmation'));
        $confirmCell->getStyle()->setGridSpan(4);

        // Signature
        $orgTable->addRow(1200);
        $orgTable->addCell()->addText(Lang::get('process_export.wordexport.signature').":");
        $orgTable->addCell()->getStyle()->setGridSpan(3);

        // Remarks
        $orgTable->addRow(1200);
        $remarksCell = $orgTable->addCell();
        $remarksCell->addText(Lang::get('process_export.wordexport.remarks').":");
        $remarksCell->getStyle()->setGridSpan(4);

        $activityPage = $page;

        $tableStyle = [
            'borderColor' => '006699',
            'borderSize'  => 6,
            'cellMargin'  => 50,
        ];
        $firstRowStyle = ['bgColor' => '66BBFF'];
        $w->addTableStyle('table', $tableStyle, $firstRowStyle);
        $lap_array = $this->getWerkzaamheden(Carbon::createFromTimestamp($request->get('startDate')), Carbon::createFromTimestamp($request->get('endDate')));

        $wplp = $student->getCurrentWorkplaceLearningPeriod();
        $date_loop = date('Y-m-d',
            strtotime('monday this week', $request->get('startDate')));
        $datefmt = $formatter = new IntlDateFormatter(
            App::getLocale(),
            IntlDateFormatter::GREGORIAN,
            IntlDateFormatter::NONE,
            null,
            null,
            "EEE"
        );


        while (strtotime($date_loop) < $request->get('endDate') && strtotime($date_loop) < time()) {
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
                $table->addCell(2000)->addText(Carbon::createFromTimestamp(strtotime($date_loop))->format("d-m-Y"));

                $hrs = 0;
                if (array_key_exists("" . date('d-m-Y', strtotime($date_loop)), $lap_array)) {
                    $textEntries = [];
                    foreach ($lap_array["" . date('d-m-Y', strtotime($date_loop))] as $lap) {
                        $hrs += $lap['duration'];
                        $textEntries[] = "- {$lap['description']}";

                    }
                    $table->addCell(8000)->addText(implode("\n", $textEntries));
                } else {
                    $table->addCell(8000)->addText(Lang::get('process_export.wordexport.absent'));
                }

                $days_this_week += ($hrs >= 7.5) ? 1 : 0;
                $date_loop = date('d-m-Y', strtotime("+1 day", strtotime($date_loop)));
            }

            $weekMetaTable = $activityPage->addTable();
            $weekMetaTable->addRow();
            $totalDaysCell = $weekMetaTable->addCell(4000);
            $totalDaysCell->addText(Lang::get('process_export.wordexport.days-worked').": ");
            $totalDaysCell->getStyle()->setGridSpan(1);
            $weekMetaTable->addCell(8000)->addText(($days_this_week . (($days_this_week == 1) ? " ".Lang::get('process_export.wordexport.day') : " ".Lang::get('process_export.wordexport.days'))));

            $weekMetaTable->addRow();
            $absenceCell = $weekMetaTable->addCell(4000);
            $absenceCell->addText(Lang::get('process_export.wordexport.absence-reason').": ");
            $absenceCell->getStyle()->setGridSpan(1);
            $weekMetaTable->addCell(8000)->addText("");

            $weekMetaTable->addRow(1200);
            $weekRemarksCell = $weekMetaTable->addCell(4000);
            $weekRemarksCell->addText(Lang::get('process_export.wordexport.remarks-week').": ");
            $weekRemarksCell->getStyle()->setGridSpan(1);
            $weekMetaTable->addCell(8000)->addText("");

            $date_loop = date('d-m-Y', strtotime("+2 days", strtotime($date_loop)));


        }

        $fileName = Auth::user()->studentnr." ".
            Auth::user()->getInitials()." ".Auth::user()->lastname.
            " - ".Auth::user()->getCurrentWorkplace()->wp_name;

        $w->save("{$fileName}.docx", "Word2007", true);
    }

    private function getWerkzaamheden(Carbon $startDate, Carbon $endDate)
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
            $activityDate = Carbon::createFromTimestamp(strtotime($lap->date));
            if($activityDate->lessThan($startDate) || $activityDate->greaterThan($endDate) ) {
                continue;
            }
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
