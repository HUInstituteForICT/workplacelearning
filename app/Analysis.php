<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name Analysis name
 * @property string query SQL query
 * @property integer cache_duration Duration in given time type
 * @property string type_time
 * @property string time_type
 */
class Analysis extends Model
{
    const CACHE_KEY = 'cache-analysis-';
    public $timestamps = false;
    protected $table = 'analyses';
    protected $fillable = array('name', 'query', 'type_time', 'cache_duration');

    public function charts()
    {
        return $this->hasMany('App\AnalysisChart');
    }

    // Who named this? It now works both ways.
    public function getTimeTypeAttribute($value)
    {
        return $this->type_time;
    }

    /**
     * Get cached data if any
     * @param $value
     * @return mixed
     */
    public function getDataAttribute($value)
    {
        if (!\Cache::has(self::CACHE_KEY . $this->id))
            $this->refresh();
        return \Cache::get(self::CACHE_KEY . $this->id);
    }

    /**
     * Refresh the cached data, if any
     */
    public function refresh()
    {
        if (\Cache::has(self::CACHE_KEY . $this->id))
            \Cache::forget(self::CACHE_KEY . $this->id);

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

        \Cache::put(self::CACHE_KEY . $this->id, [
            'id' => $this->id,
            'data' => $this->execute()
        ], $expiry);
    }

    /**
     * Return data from the query or an error
     * @return array|null
     */
    public function execute()
    {
        $data = null;
        try {
            $data = \DB::connection('dashboard')->select($this->query);
        } catch (\Exception $e) {
            $data = [
                'error' => $e->getMessage()
            ];
        }
        return $data;
    }
}
