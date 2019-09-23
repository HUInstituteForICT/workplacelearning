<?php

declare(strict_types=1);

namespace App\Analysis\QueryBuilder;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Models provides functionality to get a list of models.
 */
class Models
{
    private static $models = [
        'AccessLog',
        'Category',
        'Cohort',
        'Competence',
        'Difficulty',
        'EducationProgram',
        'LearningActivityActing',
        'LearningActivityProducing',
        'LearningGoal',
        'ResourceMaterial',
        'ResourcePerson',
        'Status',
        'Timeslot',
        'WorkplaceLearningPeriod',
    ];

    public function getAll(): array
    {
        return self::$models;
    }

    public function getColumns($string): array
    {
        $modelString = 'App\\'.$string;

        /** @var Model $model */
        $model = new $modelString();

        return \DB::connection('dashboard')->getSchemaBuilder()->getColumnListing($model->getTable());
    }

    public function getRelations($string): array
    {
        $modelString = 'App\\'.$string;
        $model = new $modelString();

        $relationships = [];

        if (method_exists($model, 'getRelationships')) {
            $tmpRelations = $model->getRelationships();

            foreach ($tmpRelations as $relation) {
                $className = class_basename(\get_class($model->$relation()->getRelated()));
                if ($className !== $string && \in_array($className, self::$models, true)) {
                    $relationships[$relation] = __('querybuilder.'.$className);
                }
            }
        }

        return $relationships;
    }
}
