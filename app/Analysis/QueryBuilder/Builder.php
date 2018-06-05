<?php

namespace App\Analysis\QueryBuilder;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Builder provides the query builder functionality.
 * It can be used to generate a query and get the sql query or data from it.
 * @package App\Analysis\QueryBuilder
 *
 */
class Builder
{
    private $query;

    private function build($model, $relations, $select, $filters, $groupBy) {

        $hasCount = true;

        $modelString = 'App\\' . $model;
        $mainModel = new $modelString();

        $this->query = \DB::connection("dashboard")->table($mainModel->getTable());

        foreach($relations as $r) {

            $name = 'App\\'.$r;
            $join = new $name;

            if($mainModel->$r() instanceof BelongsTo) {

                $this->query->join($join->getTable(),
                    $join->getTable().'.'.$join->getKeyName(),
                    '=',
                    $mainModel->$r()->getQualifiedForeignKey());
            } else {

                $this->query->join($join->getTable(),
                    $join->getTable() . '.' . $join->getKeyName(),
                    '=',
                    $mainModel->$r()->getQualifiedParentKeyName());
            }
        }

        if(!empty($groupBy)) {

            $this->query->groupBy($groupBy);
        } else if(empty($groupBy) && $hasCount) {

            $this->query->groupBy($mainModel->getTable().'.'.$mainModel->getKeyName());
        }
        $this->query->select($select);

        foreach($filters as $filter) {

            $this->query->where($filter[0], $filter[1], $filter[2]);
        }
    }

    public function getData($model, $relations, $select, $filters, $groupBy) {

        $this->build($model, $relations, $select, $filters, $groupBy);

        return $this->query->get();
    }

    public function getQuery($model, $relations, $select, $filters, $groupBy) {

        $this->build($model, $relations, $select, $filters, $groupBy);

        return $this->query->toSQL();
    }
}