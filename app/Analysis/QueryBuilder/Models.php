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
        "Category" => \App\Category::class,
        "Cohort" => \App\Cohort::class

    ];

    public function getAll() {

        return $this->models;
    }

    public function getRelations($string) {

        $modelString = 'App\\' . $string;
        $model = new $modelString();

        $relationships = [];

        foreach((new \ReflectionClass($model))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->class != get_class($model) ||
                !empty($method->getParameters()) ||
                $method->getName() == __FUNCTION__) {
                continue;
            }

            try {
                $return = $method->invoke($model);
                echo(get_class( $return));
                if ($return instanceof Relation) {
                    $relationships[$method->getName()] = [
                        'type' => (new \ReflectionClass($return))->getShortName(),
                        'model' => (new \ReflectionClass($return->getRelated()))->getName()
                    ];
                }
            } catch(ErrorException $e) {}
        }

        return $relationships;
    }
}