<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * App\Analysis.
 *
 * @property int                                                           $id
 * @property string                                                        $name           Analysis name
 * @property string                                                        $query          SQL query
 * @property int                                                           $cache_duration Duration in given time type
 * @property string                                                        $type_time
 * @property string                                                        $time_type
 * @property \Illuminate\Database\Eloquent\Collection|\App\AnalysisChart[] $charts
 * @property mixed                                                         $data
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis whereCacheDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis whereTypeTime($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Analysis query()
 */
class Analysis extends Model
{
    const CACHE_KEY = 'cache-analysis-';
    public $timestamps = false;
    protected $table = 'analyses';
    protected $fillable = array('name', 'query', 'type_time', 'cache_duration');

    public function charts(): HasMany
    {
        return $this->hasMany(AnalysisChart::class);
    }

    // Who named this? It now works both ways.
    public function getTimeTypeAttribute($value): string
    {
        return $this->type_time;
    }

    /**
     * Get cached data if any.
     */
    public function getDataAttribute()
    {
        if (!Cache::has(self::CACHE_KEY.$this->id)) {
            $this->refresh();
        }

        return Cache::get(self::CACHE_KEY.$this->id);
    }

    /**
     * Refresh the cached data, if any.
     */
    public function refresh(): void
    {
        if (Cache::has(self::CACHE_KEY.$this->id)) {
            Cache::forget(self::CACHE_KEY.$this->id);
        }

        $now = Carbon::now();
        $expiry = $now->copy();

        switch ($this->time_type) {
            case 'seconds':
                $expiry->addSeconds($this->cache_duration);
                break;
            case 'minutes':
                $expiry->addMinutes($this->cache_duration);
                break;
            case 'hours':
                $expiry->addHours($this->cache_duration);
                break;
            case 'days':
                $expiry->addDays($this->cache_duration);
                break;
            case 'weeks':
                $expiry->addWeeks($this->cache_duration);
                break;
            case 'months':
                $expiry->addMonths($this->cache_duration);
                break;
            case 'years':
                $expiry->addYears($this->cache_duration);
                break;
            default:
                $expiry->addSeconds($this->cache_duration);
                break;
        }

        Cache::put(self::CACHE_KEY.$this->id, [
            'id'   => $this->id,
            'data' => $this->execute(),
        ], $expiry);
    }

    /**
     * Return data from the query or an error.
     */
    public function execute(): ?array
    {
        $data = null;
        try {
            $query = $this->query;
            if (Str::contains($query, 'wplp_id') && !Str::contains($query, 'is_in_analytics')) {
                $query = Str::replaceFirst('WHERE', 'WHERE is_in_analytics = 1 AND', $query);
            }
            $data = DB::connection('dashboard')->select($query);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage(),
            ];
        }

        return $data;
    }
}
