<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


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
