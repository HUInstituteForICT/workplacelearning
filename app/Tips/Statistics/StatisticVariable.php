<?php


namespace App\Tips\Statistics;


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
        'acting' => [
            [
                'class'      => ResourcePersonFilter::class,
                'name' => 'Resource person',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'person_label']
                ],
            ],
            [
                'class' => TimeslotFilter::class,
                'name' => 'Timeslot',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'timeslot_text']
                ]
            ]
        ],
        'producing' => [

        ]
    ];

    protected $casts = [
        'filters' => 'array',
    ];


}