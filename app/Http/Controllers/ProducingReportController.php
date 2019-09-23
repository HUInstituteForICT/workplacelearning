<?php

declare(strict_types=1);
/**
 * This file (ProducingReportController.php) was created on 05/15/2016 at 13:15.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Services\CurrentUserResolver;
use App\Traits\PhpWordDownloader;
use App\WorkplaceLearningPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use IntlDateFormatter;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;

class ProducingReportController extends Controller
{
    use PhpWordDownloader;

    protected $pdf;

    private $html;

    // This array holds the view content that needs to be passed to the view (eg charts, images, text, etc)
    private $viewdata = [
    ];

    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;

    public function __construct(CurrentUserResolver $currentUserResolver)
    {
        $this->currentUserResolver = $currentUserResolver;
    }

    public function wordExport(Request $request): void
    {
        $student = $this->currentUserResolver->getCurrentUser();
        $wp = $student->getCurrentWorkplace();
        $wplp = $student->getCurrentWorkplaceLearningPeriod();

        $bold = tap(new Font())->setBold(true);

        $document = new PhpWord();
        $page = $document->addSection();
        $page->addText(__('process_export.wordexport.by-intern'), $bold);
        $internTable = $page->addTable('');

        $internTable->addRow();
        // Intern name
        $internTable->addCell(2000)->addText(__('process_export.wordexport.intern-name').': ');
        $internTable->addCell(2500)->addText("{$student->firstname} {$student->lastname}");
        // Organisation
        $internTable->addCell(2000)->addText(__('process_export.wordexport.organisation').': ');
        $internTable->addCell(2000)->addText($wp->wp_name);
        $internTable->addRow();
        // Student nr
        $internTable->addCell(2000)->addText(__('process_export.wordexport.studentnr').': ');
        $internTable->addCell()->addText($student->studentnr);
        // Address org
        $internTable->addCell()->addText(__('process_export.wordexport.address').': ');
        $internTable->addCell()->addText("{$wp->street} {$wp->housenr}, {$wp->postalcode}, {$wp->town}");
        $internTable->addRow();
        // total days
        $internTable->addCell()->addText(__('process_export.wordexport.total-days').': ');
        $globalTotalDaysCell = $internTable->addCell();
        $internTable->addRow();
        // Mentor
        $internTable->addCell()->addText(__('process_export.wordexport.mentor').': ');
        $internTable->addCell()->addText('');

        $page->addText("\n\n".__('process_export.wordexport.by-workplace'), $bold);
        $orgTable = $page->addTable();
        $orgTable->addRow(900);

        // name contact
        $orgTable->addCell(2000)->addText(__('process_export.wordexport.name').': ');
        $orgTable->addCell(2500)->addText($wp->contact_name);
        // Date
        $date = Carbon::now();
        $orgTable->addCell(2000)->addText(__('process_export.wordexport.date').': ');
        $orgTable->addCell(2000)->addText($date->format('d-m-Y'));
        $orgTable->addRow(1200);
        $confirmCell = $orgTable->addCell();
        $confirmCell->addText(__('process_export.wordexport.confirmation'));
        $confirmCell->getStyle()->setGridSpan(4);

        // Signature
        $orgTable->addRow(1200);
        $orgTable->addCell()->addText(__('process_export.wordexport.signature').':');
        $orgTable->addCell()->getStyle()->setGridSpan(3);

        // Remarks
        $orgTable->addRow(1200);
        $remarksCell = $orgTable->addCell();
        $remarksCell->addText(__('process_export.wordexport.remarks').':');
        $remarksCell->getStyle()->setGridSpan(4);

        $activityPage = $page;

        $tableStyle = [
            'borderColor' => '006699',
            'borderSize'  => 6,
            'cellMargin'  => 50,
        ];
        $firstRowStyle = ['bgColor' => '66BBFF'];
        $document->addTableStyle('table', $tableStyle, $firstRowStyle);
        $lap_array = $this->getWerkzaamheden(Carbon::createFromTimestamp($request->get('startDate')),
            Carbon::createFromTimestamp($request->get('endDate')), $wplp);

        $date_loop = date('Y-m-d',
            strtotime('monday this week', $request->get('startDate')));
        $datefmt = new IntlDateFormatter(
            App::getLocale(),
            IntlDateFormatter::GREGORIAN,
            IntlDateFormatter::NONE,
            null,
            null,
            'EEE'
        );

        $daysWorkedTotal = 0;
        while (strtotime($date_loop) < $request->get('endDate') && strtotime($date_loop) < time()) {
            $table = $activityPage->addTable('table');
            $table->addRow();
            $table->addCell(2000)->addText(__('process_export.wordexport.week').' '.date('W',
                    strtotime($date_loop)), $bold);
            $table->addCell(2000)->addText(__('process_export.wordexport.date'), $bold);
            $table->addCell(8000)->addText(__('process_export.wordexport.activities'), $bold);
            $weekno = 1;

            $days_this_week = 0;
            for ($i = 1; $i <= 5; ++$i) {
                $table->addRow();
                $table->addCell(2000)->addText(ucwords($datefmt->format(strtotime($date_loop))));
                $table->addCell(2000)->addText(Carbon::createFromTimestamp(strtotime($date_loop))->format('d-m-Y'));

                $hrs = 0;
                if (array_key_exists(''.date('d-m-Y', strtotime($date_loop)), $lap_array)) {
                    $textEntries = [];
                    foreach ($lap_array[''.date('d-m-Y', strtotime($date_loop))] as $lap) {
                        $hrs += $lap['duration'];
                        $textEntries[] = '- '.htmlspecialchars($lap['description']);
                    }
                    $table->addCell(8000)->addText(implode("\n", $textEntries));
                } else {
                    $table->addCell(8000)->addText(__('process_export.wordexport.absent'));
                }

                $days_this_week += ($hrs >= Auth::user()->getCurrentWorkplaceLearningPeriod()->hours_per_day) ? 1 : 0;
                $date_loop = date('d-m-Y', strtotime('+1 day', strtotime($date_loop)));
            }
            $daysWorkedTotal += $days_this_week;

            $weekMetaTable = $activityPage->addTable();
            $weekMetaTable->addRow();
            $totalDaysCell = $weekMetaTable->addCell(4000);
            $totalDaysCell->addText(__('process_export.wordexport.days-worked',
                    ['hours' => Auth::user()->getCurrentWorkplaceLearningPeriod()->hours_per_day]).': ');
            $totalDaysCell->getStyle()->setGridSpan(1);
            $weekMetaTable->addCell(8000)->addText(($days_this_week.(($days_this_week == 1) ? ' '.__('process_export.wordexport.day') : ' '.__('process_export.wordexport.days'))));

            $weekMetaTable->addRow();
            $absenceCell = $weekMetaTable->addCell(4000);
            $absenceCell->addText(__('process_export.wordexport.absence-reason').': ');
            $absenceCell->getStyle()->setGridSpan(1);
            $weekMetaTable->addCell(8000)->addText('');

            $weekMetaTable->addRow(1200);
            $weekRemarksCell = $weekMetaTable->addCell(4000);
            $weekRemarksCell->addText(__('process_export.wordexport.remarks-week').': ');
            $weekRemarksCell->getStyle()->setGridSpan(1);
            $weekMetaTable->addCell(8000)->addText('');

            $date_loop = date('d-m-Y', strtotime('+2 days', strtotime($date_loop)));
        }

        // Now using ->effectiveDays() : https://github.com/HUInstituteForICT/workplacelearning/issues/108
        $daysWorked = $wplp->getEffectiveDays();
        $globalTotalDaysCell->addText($daysWorked.(($daysWorked === 1) ? ' '.__('process_export.wordexport.day') : ' '.__('process_export.wordexport.days')));

        $fileName = $student->studentnr.' '.$student->getInitials().' '.$student->lastname.' - '.$wp->wp_name;

        $this->downloadDocument($document, "{$fileName}.docx");
    }

    private function getWerkzaamheden(
        Carbon $startDate,
        Carbon $endDate,
        WorkplaceLearningPeriod $workplaceLearningPeriod
    ): array {
        $dataset = [];

        $activities = $workplaceLearningPeriod->learningActivityProducing()
            ->whereDate('date', '>=', $workplaceLearningPeriod->startdate)
            ->whereDate('date', '<=', $workplaceLearningPeriod->enddate)
            ->orderBy('date')
            ->orderBy('lap_id')
            ->get()->all();

        foreach ($activities as $activity) {
            $activityDate = Carbon::createFromTimestamp(strtotime($activity->date));
            if ($activityDate->lessThan($startDate) || $activityDate->greaterThan($endDate)) {
                continue;
            }

            $dataset[$activity->date->format('d-m-Y')][$activity->lap_id] = [
                'date'        => $activity->date,
                'duration'    => $activity->duration,
                'description' => $activity->description,
                'difficulty'  => $activity->difficulty_label,
                'status'      => $activity->status_label,
            ];
        }

        return $dataset;
    }
}
