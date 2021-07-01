<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class column_data
 * @package App
 *
 * @property int $column_data_id
 * @property int $column_id
 * @property string $data_type
 * @property string $data_as_string
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column_data whereColumn_data_id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column_data whereColumn_id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column_data whereData_type($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column_data whereData_as_string($value)
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column_data newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column_data newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column_data query()
 */
class column_data extends Model
{
    protected $table = 'column_data';

    //public $timestamps = false;

    protected $primaryKey = 'column_data_id';

    // Default
    protected $fillable = [
        'column_data_id',
        'column_id',
        'data_type',
        'data_as_string'
    ];

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'column_id', 'column_id');
    }


    // Relations for query builder
    public function getRelationships(): array
    {
        return [
            'column',
        ];
    }
}
