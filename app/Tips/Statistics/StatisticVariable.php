<?php


namespace App\Tips\Statistics;


use App\Tips\DataCollectors\Collector;
use App\Tips\DataCollectors\DataCollectorContainer;
use App\Tips\Statistics\Filters\CategoryFilter;
use App\Tips\Statistics\Filters\DifficultyFilter;
use App\Tips\Statistics\Filters\ResourcePersonFilter;
use App\Tips\Statistics\Filters\TimeslotFilter;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property array $filters
 * @property string $type
 * @property string $selectType
 */
class StatisticVariable extends Model
{
    public $timestamps = false;

    public static $availableFilters = [
        'acting'    => [
            [
                'class'      => ResourcePersonFilter::class,
                'name'       => 'Resource person',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'person_label']
                ],
            ],
            [
                'class'      => TimeslotFilter::class,
                'name'       => 'Timeslot',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'timeslot_text']
                ]
            ]
        ],
        'producing' => [
            [
                'class'      => ResourcePersonFilter::class,
                'name'       => 'Resource person',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'person_label']
                ],
            ],
            [
                'class'      => CategoryFilter::class,
                'name'       => 'Category',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'category_label']
                ],
            ],
            [
                'class'      => DifficultyFilter::class,
                'name'       => 'Difficulty',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'difficulty_label']
                ],
            ],
        ]
    ];

    protected $casts = [
        'filters' => 'array',
    ];

    /**
     * @var Collector $collector
     */
    private $collector;


    public function getValue()
    {
        return $this->collector->getValueForVariable($this);
    }


    public function setCollector(Collector $collector)
    {
        $this->collector = $collector;
    }


}