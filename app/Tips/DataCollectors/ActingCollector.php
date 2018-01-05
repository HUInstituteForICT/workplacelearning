<?php


namespace App\Tips\DataCollectors;


class ActingCollector extends AbstractCollector
{

    /**
     * @DataUnitAnnotation(name="Total learning activities", method="totalLearningActivities")
     * @return int
     */
    public function totalLearningActivities()
    {
        return $this->wherePeriod($this->learningPeriod->learningActivityActing()->getBaseQuery())->count();
    }

    /**
     * @DataUnitAnnotation(name="Activities with certain resource person", method="activitiesWithResourcePerson", hasParameters=true, parameterName="Person label")
     * @param mixed $personLabel
     * @return int
     */
    public function activitiesWithResourcePerson($personLabel)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()->getBaseQuery()->leftJoin('resourceperson', 'res_person_id',
                '=', 'rp_id')->where('person_label', '=', $personLabel)
        )->count();
    }

    /**
     * @DataUnitAnnotation(name="Activities with certain category", method="activitiesWithTimeslot", hasParameters=true, parameterName="Category/Timeslot label")
     * @param string $timeslotText timeslot name/text
     * @return int
     */
    public function activitiesWithTimeslot($timeslotText)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()->getBaseQuery()->leftJoin('timeslot', 'learningactivityacting.timeslot_id', '=', 'timeslot.timeslot_id')
            ->where('timeslot_text', '=', $timeslotText)
        )->count();
    }

    /**
     * @DataUnitAnnotation(name="Activities with certain category", method="activitiesWithTimeslot", hasParameters=true, parameterName="Category/Timeslot label")
     * @param string $timeslotText timeslot name/text
     * @return int
     */
    public function activitiesWithLearningQuestion($timeslotText)
    {
        return $this->wherePeriod(
            $this->learningPeriod->learningActivityActing()->getBaseQuery()->leftJoin('timeslot', 'learningactivityacting.timeslot_id', '=', 'timeslot.timeslot_id')
                ->where('timeslot_text', '=', $timeslotText)
        )->count();
    }

}