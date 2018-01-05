<?php


namespace App\Tips\DataCollectors;


use App\Tips\DataUnit;
use App\Tips\DataUnitAnnotation;
use Doctrine\Common\Annotations\AnnotationReader;

class DataCollectorContainer
{
    /** @var CollectorInterface $collector */
    private $collector;


    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    public function getDataUnit(DataUnit $dataUnit) {

        if(!method_exists($this->collector, $dataUnit->getMethod())) {
            $collectorClass = get_class($this->collector);
            throw new \Exception("Method \"{$dataUnit->getMethod()}\" does not exist on {$collectorClass}");
        }

        if($this->hasParameters($this->collector, $dataUnit->getMethod())) {
            return $this->collector->{$dataUnit->getMethod()}($dataUnit->getValue());
        }

        return $this->collector->{$dataUnit->getMethod()}();
    }

    /**
     * Check if the method of the collector should receive parameters
     *
     * @param CollectorInterface $collector
     * @param string $method
     * @return boolean
     */
    private function hasParameters(CollectorInterface $collector, $method) {
        $annotationReader = new AnnotationReader();
        $collectorClass = get_class($collector);

        $reflectionMethod = new \ReflectionMethod($collectorClass, $method);
        /** @var DataUnitAnnotation $dataUnitAnnotation */
        $dataUnitAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod, DataUnitAnnotation::class);

        return $dataUnitAnnotation->hasParameters;
    }
}