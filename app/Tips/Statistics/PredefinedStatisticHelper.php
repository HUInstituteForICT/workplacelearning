<?php


namespace App\Tips\Statistics;


use App\Tips\CollectorDataAggregator;
use App\Tips\DataCollectors\PredefinedStatisticCollector;
use App\WorkplaceLearningPeriod;

class PredefinedStatisticHelper
{
    private static $cache = null;

    public static function getData()
    {
        if (self::$cache === null) {
            self::$cache = (new CollectorDataAggregator(new PredefinedStatisticCollector(null, null,
                new WorkplaceLearningPeriod)))
                ->getInformation(false);
        }

        return self::$cache;
    }

    public static function isActingMethod($method)
    {
        $x = (bool)collect(self::getData())->first(function (array $predefinedStatisticData) use ($method) {
            return $predefinedStatisticData['method'] === $method && $predefinedStatisticData['epType'] === 'Acting';
        });

        return $x;
    }

    public static function isProducingMethod($method)
    {
        $x = (bool)collect(self::getData())->first(function (array $predefinedStatisticData) use ($method) {
            $x = $predefinedStatisticData['method'] === $method && $predefinedStatisticData['epType'] === 'Producing';

            return $x;
        });

        return $x;
    }
}