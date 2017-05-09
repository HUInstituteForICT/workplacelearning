<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name Analysis name
 * @property string query SQL query
 * @property string time_type cache time type
 * @property integer cache_duration Duration in given time type
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

    /**
     * Refresh the cached data, if any
     */
    public function refresh()
    {
        if (\Cache::has(Analysis::CACHE_KEY . $this->id))
            \Cache::forget(Analysis::CACHE_KEY . $this->id);

        $type = $this->time_type;
        $expiry = null;
        $now = Carbon::now();

        switch ($type){
            case "seconds":
                $expiry = $now->addSeconds($this->cache_duration);
                break;
            case "minutes":
                $expiry = $now->addMinutes($this->cache_duration);
                break;
            case "hours":
                $expiry = $now->addHours($this->cache_duration);
                break;
            case "days":
                $expiry = $now->addDays($this->cache_duration);
                break;
            case "weeks":
                $expiry = $now->addWeeks($this->cache_duration);
                break;
            case "months":
                $expiry = $now->addMonths($this->cache_duration);
                break;
            case "years":
                $expiry = $now->addYears($this->cache_duration);
                break;
            default:
                $expiry = $now->addSeconds($this->cache_duration);
                break;
        }


        \Cache::put(Analysis::CACHE_KEY . $this->id, [
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
