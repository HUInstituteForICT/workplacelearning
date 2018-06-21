<?php


namespace App\Analysis\QueryBuilder;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Models provides functionality to get a list of models
 * @package App\Analysis\QueryBuilder
 *
 */
class Models
{

    private $models = [
        "AccessLog",
        "Category",
        "Cohort",
        "Competence",
        "Difficulty",
        "EducationProgram",
        "LearningActivityActing",
        "LearningActivityProducing",
        "LearningGoal",
        "ResourceMaterial",
        "ResourcePerson",
        "Status",
        "Timeslot",
        "WorkplaceLearningPeriod"
    ];

    public function getAll() {

        return $this->models;
    }

    public function getColumns($string) {

        $modelString = 'App\\' . $string;
        $model = new $modelString();

        return \DB::connection('dashboard')->getSchemaBuilder()->getColumnListing($model->getTable());
    }

    public function getRelations($string) {

        $modelString = 'App\\' . $string;
        $model = new $modelString();

        $relationships = [];

        if(method_exists($model, 'getRelationships')) {

            $tmpRelations = $model->getRelationships();

            foreach($tmpRelations as $relation) {
                $className = class_basename(get_class($model->$relation()->getRelated()));
                if(in_array($className, $this->models) && $className != $string) {

                    $relationships[$relation] = \Lang::get('querybuilder.'.$className);
                }
            }

        }

        return $relationships;
    }
}