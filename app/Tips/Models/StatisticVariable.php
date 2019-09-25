<?php

declare(strict_types=1);

namespace App\Tips\Models;

use App\Tips\Statistics\Filters\CategoryFilter;
use App\Tips\Statistics\Filters\CompetenceFilter;
use App\Tips\Statistics\Filters\DifficultyFilter;
use App\Tips\Statistics\Filters\ResourceMaterialFilter;
use App\Tips\Statistics\Filters\ResourcePersonFilter;
use App\Tips\Statistics\Filters\TimeslotFilter;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Tips\Models\StatisticVariable.
 *
 * @property int             $id
 * @property array           $filters
 * @property CustomStatistic $statistic
 * @property string          $type
 * @property string          $selectType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StatisticVariable whereFilters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StatisticVariable whereId($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StatisticVariable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StatisticVariable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tips\Models\StatisticVariable query()
 */
class StatisticVariable extends Model
{
    public $timestamps = false;

    public static $availableFilters = [
        'acting' => [
            [
                'class'      => ResourcePersonFilter::class,
                'name'       => 'Resource person',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'person_label'],
                ],
            ],
            [
                'class'      => TimeslotFilter::class,
                'name'       => 'Timeslot/Category',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'timeslot_text'],
                ],
            ],
            [
                'class'      => ResourceMaterialFilter::class,
                'name'       => 'Theory / resource material',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'rm_label'],
                ],
            ],
            [
                'class'      => CompetenceFilter::class,
                'name'       => 'Competence',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'competence_label'],
                ],
            ],
        ],
        'producing' => [
            [
                'class'      => ResourcePersonFilter::class,
                'name'       => 'Resource person',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'person_label'],
                ],
            ],
            [
                'class'      => CategoryFilter::class,
                'name'       => 'Category',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'category_label'],
                ],
            ],
            [
                'class'      => DifficultyFilter::class,
                'name'       => 'Difficulty',
                'parameters' => [
                    ['name' => 'Label', 'propertyName' => 'difficulty_label'],
                ],
            ],
        ],
    ];

    protected $casts = [
        'filters' => 'array',
    ];
}
