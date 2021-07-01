<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Column
 * @package App
 *
 * @property int $column_id
 * @property int $gla_id
 * @property int $fieldtype_id
 * @property string $name
 * @property string $column_options
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column whereColumn_id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column whereGla_id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column whereFieldtype_id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column whereColumn_options($value)
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Column query()
 */

class Column extends Model
{

    protected $table = 'column';

    //public $timestamps = false;

    protected $primaryKey = 'column_id';

    // Default
    protected $fillable = [
        'column_id',
        'gla_id',
        'fieldtype_id',
        'name',
        'column_options'
    ];

    public function genericLearningActivity(): BelongsTo
    {
        return $this->belongsTo(GenericLearningActivity::class, 'gla_id', 'gla_id');
    }

    public function fieldType(): BelongsTo
    {
        return $this->belongsTo(Fieldtype::class, 'fieldtype_id', 'fieldtype');
    }

    public function column_data(): HasOne
    {
        return $this->hasOne(column_data::class, 'column_id', 'column_id');
    }

    // Relations for query builder
    public function getRelationships(): array
    {
        return [
            'column_data',
            'fieldType',
            'genericLearningActivity'
        ];
    }
}