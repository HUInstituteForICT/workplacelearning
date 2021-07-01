<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
