<?php


namespace App\Tips;


use App\LearningActivityActing;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class ActingCollector extends AbstractCollector
{
    public static $dataUnitToMethodMapping = [
        "totalLearningActivities" => "totalLearningActivities",
        "activitiesWithRP" => "activitiesWithResourcePerson"
    ];



    public function totalLearningActivities()
    {
        return $this->wherePeriod($this->learningPeriod->learningActivityActing()->getBaseQuery())->count();
    }

    public function activitiesWithResourcePerson($column, $value)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()->getBaseQuery()->leftJoin('resourceperson', 'res_person_id', '=', 'rp_id')->where($column, '=', $value)
        )->count();

    }

}