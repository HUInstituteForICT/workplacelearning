<?php


namespace App\Tips\DataCollectors;


use App\EducationProgramType;
use App\WorkplaceLearningPeriod;

class CollectorFactory
{
    public function buildCollector(EducationProgramType $educationProgramType) {
        switch(strtolower($educationProgramType->eptype_name)) {
            case "acting":
                return new ActingCollector(null, null, new WorkplaceLearningPeriod);
            case "producing":
                return new ProducingCollector(null, null, new WorkplaceLearningPeriod);
            default:
                throw new \Exception("Unable to build a Collector based on education program type {$educationProgramType->eptype_name}");
        }
    }
}