<?php


namespace App\Tips;


use Doctrine\Common\Annotations\AnnotationReader;

class CollectorDataAggregator
{

    /** @var CollectorInterface $collector */
    private $collector;

    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Get the information of all DataUnit methods of a collector
     * Used for displaying
     * @return array
     */
    public function getInformation() {
        $methods = $this->methodsForObject($this->collector);

        $information = [];

        foreach($methods as $method) {
            if(in_array($method, ["__construct"])) {
                continue;
            }

            $methodInformation = $this->informationForMethod($this->collector, $method);
            $methodInformation->type = (new CollectedDataStatisticVariable())->getType();

            $information[] = (array) $methodInformation;
        }

        return $information;
    }

    /**
     * Get the methods for a collector
     *
     * @param CollectorInterface $collector
     * @return array
     */
    private function methodsForObject(CollectorInterface $collector)
    {
        $className = get_class($collector);
        return get_class_methods($className);
    }

    /**
     * Get the DataUnitAnnotation information for a method in a collector
     *
     * @param CollectorInterface $collector
     * @param string $method Method to get annotations for
     * @return DataUnitAnnotation
     */
    private function informationForMethod(CollectorInterface $collector, $method)
    {
        $annotationReader = new AnnotationReader();
        $collectorClass = get_class($collector);
        $reflectionMethod = new \ReflectionMethod($collectorClass, $method);

        /** @var DataUnitAnnotation $dataUnitAnnotation */
        $dataUnitAnnotation = $annotationReader->getMethodAnnotation($reflectionMethod, DataUnitAnnotation::class);
        return $dataUnitAnnotation;
    }

}