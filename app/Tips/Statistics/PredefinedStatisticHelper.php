<?php


namespace App\Tips\Statistics;


use App\Tips\ActingPredefinedStatisticCollector;
use App\Tips\CollectorDataAggregator;
use App\Tips\ProducingPredefinedStatisticCollector;
use App\WorkplaceLearningPeriod;

class PredefinedStatisticHelper
{
    public static function getActingData() {
        return (new CollectorDataAggregator(new ActingPredefinedStatisticCollector(null, null,
            new WorkplaceLearningPeriod)))
            ->getInformation();
    }

    public static function getProducingData() {
        return (new CollectorDataAggregator(new ProducingPredefinedStatisticCollector(null, null,
            new WorkplaceLearningPeriod)))
            ->getInformation();
    }

    public static function isActingMethod($method) {
        $x = (bool) collect(self::getActingData())->first(function(array $predefinedStatisticData) use($method) {
            return $predefinedStatisticData['method'] === $method;
        });

        return $x;
    }

    public static function isProducingMethod($method) {
        $x = (bool) collect(self::getProducingData())->first(function(array $predefinedStatisticData) use($method) {
            $x = $predefinedStatisticData['method'] === $method;
            return $x;
        });

        return $x;
    }
}