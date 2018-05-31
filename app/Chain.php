<?php


namespace App;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property LearningActivityProducing[] $activities
 * @property int $status
 */
class Chain extends Model
{
    const STATUS_BUSY = 0;
    const STATUS_FINISHED = 1;

    protected function activities(): HasMany {
        return $this->hasMany(LearningActivityProducing::class, 'chain_id', 'id')->orderBy('date', 'ASC');
    }



}