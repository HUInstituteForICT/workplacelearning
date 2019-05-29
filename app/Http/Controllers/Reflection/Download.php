<?php


namespace App\Http\Controllers\Reflection;


use App\Reflection\Models\ActivityReflection;
use App\Reflection\Services\Exporter;


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
        ob_start();
        $document = $this->exporter->exportReflections([$activityReflection]);
        $document->save(strtolower(__('reflection.reflection')) . '.docx', 'Word2007', true);
        // We need to quit Laravel otherwise the docx will get corrupted
        exit;
    }
}