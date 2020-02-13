<?php

declare(strict_types=1);

namespace App\Services;

use App\Competence;
use App\Evidence;
use App\LearningActivityActing;
use App\Reflection\Models\ActivityReflectionField;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

class ActingActivityExporter
{
    /**
     * @var CurrentUserResolver
     */
    private $userResolver;

    public function __construct(CurrentUserResolver $userResolver)
    {
        $this->userResolver = $userResolver;
    }

    /**
     * @param LearningActivityActing[] $activities
     */
    public function export(array $activities, $includeReflections = true): PhpWord
    {
        $document = $this->getNewDocument();
        $section = $document->addSection();

        $student = $this->userResolver->getCurrentUser();

        $section->addImage(public_path('assets/img/hu-logo-medium.png'), [
            'width'     => 5 * 28,
            'height'    => 1.63 * 28,
            'alignment' => Jc::END,
        ]);

        $section->addText(__('export_laa.learningactivities').' '.__('export_laa.of').' '.$student->firstname.' '.$student->lastname,
            ['bold' => true, 'font' => 'Arial', 'size' => 14]);
        $section->addText(__('export_laa.workplace').': '.$student->getCurrentWorkplace()->wp_name,
            ['bold' => true, 'font' => 'Arial', 'size' => 12]);

        $section->addTextBreak(5);
        $section->addText(__('export_laa.learningactivities'), ['bold' => true, 'size' => 14, 'font' => 'Arial']);
        $section->addTOC(['font' => 'Arial', 'size' => 14], null, 1, 1);

        array_walk($activities, [$this, 'addActivityToSection'], ['section' => $section, 'includeReflections' => $includeReflections]);

        return $document;
    }

    /**
     * Here we add each activity to the document
     * It is all done in this function because splitting it doesn't make much sense for this.
     */
    private function addActivityToSection(LearningActivityActing $activity, int $key, $arguments): void
    {
        /** @var Section $section */
        $section = $arguments['section'];

        /** @var bool $includeReflections */
        $includeReflections = $arguments['includeReflections'];

        $section->addPageBreak();

        // Add title
        $section->addTitle("{$activity->date->format('d-m-Y')} - {$activity->timeslot->localizedLabel()}");

        // Add subtitle
        $personText = $activity->resourcePerson->isAlone() ? __('export_laa.alone') : __('export_laa.with',
            ['person' => $activity->resourcePerson->localizedLabel()]);
        $section->addTitle($personText, 2);
        $section->addLine(['weight' => 1, 'color' => '#00A1E1', 'width' => 612, 'height' => 1]);

        // Add General area
        $section->addTextBreak(2);
        $section->addTitle(__('export_laa.general'), 3);

        // Add theory
        $section->addTextBreak();
        $theoryRun = $section->addTextRun();
        $theoryRun->addText(__('export_laa.theory').': ', ['bold' => true]);
        if ($activity->resourceMaterial) {
            $theoryRun->addText($activity->resourceMaterial->rm_label);
            // Add resource material detail if it exists
            if ($activity->res_material_detail) {
                // If it is an URL, make it clickable and only shown domain
                if (filter_var($activity->res_material_detail, FILTER_VALIDATE_URL)) {
                    $domain = parse_url($activity->res_material_detail, PHP_URL_HOST);
                    $theoryRun->addText(' - ');
                    $theoryRun->addLink($activity->res_material_detail, $domain,
                        ['color' => '0000FF', 'underline' => Font::UNDERLINE_SINGLE]);
                } else {
                    $theoryRun->addText(" ({$activity->res_material_detail})");
                }
            }
        } else {
            // If no theory used state that
            $theoryRun->addText(__('export_laa.none'));
        }

        // Add learning goal
        $section->addTextBreak();
        $learningGoalRun = $section->addTextRun();
        $learningGoalRun->addText(__('export_laa.learninggoal').': ', ['bold' => true]);
        $learningGoalRun->addText($activity->learningGoal->learninggoal_label.' - '.$activity->learningGoal->description);

        // Add competences
        $section->addTextBreak();
        $competenceRun = $section->addTextRun();
        $competenceRun->addText(trans_choice('export_laa.competence', $activity->competence->count()).': ',
            ['bold' => true]);

        $labels = array_map(static function (Competence $competence): string {
            return $competence->localizedLabel();
        }, $activity->competence->all());
        $competenceRun->addText(implode(', ', $labels));

        $section->addTextBreak();
        $section->addText(__('export_laa.situation').':', ['bold' => true]);
        $section->addText($activity->situation);

        // Add short reflection if exists
        if ($activity->lessonslearned || $activity->support_wp || $activity->support_ed) {
            $section->addTextBreak(2);
            $section->addTitle(__('export_laa.short-reflection'), 3);

            if ($activity->lessonslearned) {
                $section->addTextBreak();
                $section->addText(__('export_laa.lessons-learned').':', ['bold' => true]);
                $section->addText($activity->lessonslearned);
            }

            if ($activity->support_wp) {
                $section->addTextBreak();
                $section->addText(__('export_laa.support-wp').':', ['bold' => true]);
                $section->addText($activity->support_wp);
            }

            if ($activity->support_ed) {
                $section->addTextBreak();
                $section->addText(__('export_laa.support-ed').':', ['bold' => true]);
                $section->addText($activity->support_ed);
            }
        }

        // Misc section (e.g. evidence pieces)
        $section->addTextBreak(2);
        $section->addTitle(__('export_laa.miscellaneous'), 3);

        // Add evidence
        $section->addTextBreak();
        $section->addText(__('export_laa.evidence').':', ['bold' => true]);

        $evidence = $activity->evidence->all();
        if (count($evidence) === 0) {
            $section->addText(__('export_laa.none'));
        }
        array_walk($evidence, static function (Evidence $evidence) use ($section): void {
            $listItemRun = $section->addListItemRun();
            $url = route('evidence-download', ['learningActivity' => $evidence->id, 'diskFileName' => $evidence->disk_filename]);
            $listItemRun->addLink($url, $evidence->filename, ['color' => '0000FF', 'underline' => Font::UNDERLINE_SINGLE]);
        });

        // Full reflection if exists
        if ($activity->reflection && $includeReflections) {
            $section->addTextBreak(2);
            $section->addLine(['weight' => 1, 'color' => '#00A1E1', 'width' => 612, 'height' => 1]);

            $reflection = $activity->reflection;

            // Misc section (e.g. evidence pieces)
            $section->addTextBreak(2);
            $section->addTitle(__('export_laa.full-reflection', ['type' => $reflection->reflection_type]), 3);

            $section->addTextBreak();

            /** @var ActivityReflectionField $field */
            foreach ($reflection->fields as $field) {
                $section->addText(ucfirst(__('reflection.fields.'.strtolower($reflection->reflection_type).'.'.$field->name)), ['bold' => true]);

                foreach(explode("\n", $field->value) as $line) {
                    $section->addTextBreak();
                    $section->addText($line);
                }

                $section->addTextBreak(2);
            }
        }
    }

    private function getNewDocument(): PhpWord
    {
        $document = new PhpWord();
        Settings::setOutputEscapingEnabled(true);
        $document->addTitleStyle(1, ['name' => 'Arial', 'size' => 18, 'bold' => true]);
        $document->addTitleStyle(2, ['name' => 'Arial', 'size' => 14, 'bold' => true]);
        $document->addTitleStyle(3, ['name' => 'Arial', 'size' => 12, 'bold' => true]);

        return $document;
    }
}
