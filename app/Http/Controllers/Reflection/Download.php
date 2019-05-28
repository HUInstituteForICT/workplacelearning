<?php


namespace App\Http\Controllers\Reflection;


use App\ActivityReflection;
use App\Services\Reflection\Exporter;


class Download
{

    /**
     * @var Exporter
     */
    private $exporter;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }


    public function __invoke(ActivityReflection $activityReflection)
    {
        $document = $this->exporter->exportReflections([$activityReflection]);
        $document->save(strtolower(__('reflection.reflection')) . '.docx', 'Word2007', true);
        // We need to quit Laravel otherwise the docx will get corrupted
        exit;
    }
}