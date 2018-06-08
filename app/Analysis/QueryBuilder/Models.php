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
        "Analysis",
        "Category",
        "Chart",
        "Competence",
        "Deadline",
        "Difficulty",
        "EducationProgram",
        "EducationProgramType",
        "LearningActivityActing",
        "LearningActivityProducing",
        "LearningGoal",
        "Parameter",
        "ResourceMaterial",
        "Status",
        "UserSetting",
        "Workplace",
        "WorkplaceLearningPeriod"
    ];

    public function getAll() {

        return $this->models;
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

                    $relationships[] = $className;
                }
            }

        }

        return $relationships;
    }
}