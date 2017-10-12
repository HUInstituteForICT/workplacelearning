<?php

namespace App\Analysis\Acting;

use App\ResourceMaterial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use stdClass;

/**
 * Class ActingAnalysisCollector fetches data for the analysis, caches it in object
 * @package App\Analysis\Acting
 */
class ActingAnalysisCollector
{
    private $learningActivities;
    private $timeslots;
    private $learningGoals;
    private $competencies;
    private $resourcePersons;
    private $resourceMaterials;


    /**
     * Get all learning activities of the user
     *
     * @return Collection
     */
    public function getLearningActivities() {
        if($this->learningActivities === null) {
            $this->learningActivities = Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityActing()->get();
        }
        return $this->learningActivities;
    }

    /**
     * Get all the timeslots of the user's education program
     *
     * @return Collection
     */
    public function getTimeslots() {
        if($this->timeslots === null) {
            $this->timeslots = Auth::user()->currentCohort()->timeslots()->get()->merge(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getTimeslots()
            );
        }
        return $this->timeslots;
    }

    /**
     * Get all the learning goals for the user's interning place
     *
     * @return Collection
     */
    public function getLearningGoals() {
        if($this->learningGoals === null) {
            $this->learningGoals = Auth::user()->getCurrentWorkplaceLearningPeriod()->getLearningGoals();
        }
        return $this->learningGoals;
    }

    /**
     * Get all the competencies of the user's education program
     *
     * @return Collection
     */
    public function getCompetencies() {
        if($this->competencies === null) {
            $this->competencies = Auth::user()->currentCohort()->competencies()->get();
        }
        return $this->competencies;
    }

    /**
     * Get all the resource persons of the user's education program & internship
     *
     * @return mixed
     */
    public function getResourcePersons() {
        if($this->resourcePersons === null) {
            $this->resourcePersons = Auth::user()->currentCohort()->resourcePersons()->get()->merge(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
            );
        }
        return $this->resourcePersons;
    }

    /**
     * Get all the resource materials of the user's internship
     *
     * @return mixed
     */
    public function getResourceMaterials() {
        if($this->resourceMaterials === null) {
            $this->resourceMaterials = Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials();

            // "None" doesn't exist as a material, so stub it
            $noneMaterial = new ResourceMaterial();
            $noneMaterial->rm_id = null;
            $noneMaterial->rm_label = "Geen";

            $this->resourceMaterials->add($noneMaterial);
        }

        return $this->resourceMaterials;
    }

}