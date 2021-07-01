<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class fieldtype extends Model
{
    protected $table = 'fieldtype';

    //public $timestamps = false;

    protected $primaryKey = 'fieldtype_id';

    // Default
    protected $fillable = [
        'fieldtype_id',
        'fieldtype'
    ];

    public function column(): HasMany
    {
        return $this->hasMany(Column::class, 'fieldtype_id', 'fieldtype_id');
    }


    // Relations for query builder
    public function getRelationships(): array
    {
        return [
            'column',
        ];
    }
}
