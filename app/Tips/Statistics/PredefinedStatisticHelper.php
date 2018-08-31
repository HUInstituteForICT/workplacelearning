<?php

namespace App\Tips\Statistics;

use App\Tips\DataCollectors\CollectorDataAggregator;
use App\Tips\DataCollectors\PredefinedStatisticCollector;
use App\WorkplaceLearningPeriod;

class PredefinedStatisticHelper
{
    private static $cache;

    public static function getData()
    {
        if (null === self::$cache) {
            self::$cache = (new CollectorDataAggregator(new PredefinedStatisticCollector(null, null,
                new WorkplaceLearningPeriod())))
                ->getInformation(false);
        }

        return self::$cache;
    }

    public static function isActingMethod($method)
    {
        $x = (bool) collect(self::getData())->first(function (array $predefinedStatisticData) use ($method) {
            return $predefinedStatisticData['method'] === $method && 'Acting' === $predefinedStatisticData['epType'];
        });

        return $x;
    }

    public static function isProducingMethod($method)
    {
        $x = (bool) collect(self::getData())->first(function (array $predefinedStatisticData) use ($method) {
            return $predefinedStatisticData['method'] === $method && 'Producing' === $predefinedStatisticData['epType'];
        });

        return $x;
    }
}
