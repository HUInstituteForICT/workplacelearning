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

    private function build($model, $relations, $selectData, $filterData, $groupBy, $limit = null) {

        $hasCount = true;

        $select = [];
        $filters = [];
        $groupBy = [];

        $modelString = 'App\\' . $model;
        $mainModel = new $modelString();

        $this->query = \DB::connection("dashboard")->table($mainModel->getTable());

        foreach($selectData as $data) {

            switch($data['type']) {

                case "data":
                    $select[] = $data['table'].'.'.$data['column'];
                    break;

                case "sum":
                    $select[] = \DB::raw('SUM('.$data['table'].'.'.$data['column'].') as '.$data['column']);
                    break;

                case "count":
                    $select[] = \DB::raw('COUNT('.$data['table'].'.'.$data['column'].') as amount_of_'.$data['column']);
                    break;
            }
        }

        foreach($filterData as $filter) {

            switch($filter['type']) {

                case "limit":
                    $limit = $filter['value'];
                    break;

                case "group":
                    $groupBy[] = $filter['table'].'.'.$filter['column'];
                    break;

                case "equals":
                    $filters[] = [$filter['table'].'.'.$filter['column'], '=', $filter['value']];
                    break;
            }
        }

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
        } /*else if(empty($groupBy) && $hasCount) {

            $this->query->groupBy($mainModel->getTable().'.'.$mainModel->getKeyName());
        }*/
        $this->query->select($select);

        foreach($filters as $filter) {

            $this->query->where($filter[0], $filter[1], $filter[2]);
        }

        if($limit != null) {

            $this->query->limit($limit);
        }
    }

    public function getData($model, $relations, $select, $filters, $groupBy, $limit = null) {

        $this->build($model, $relations, $select, $filters, $groupBy, $limit);

        return $this->query->get();
    }

    public function getQuery($model, $relations, $select, $filters, $groupBy, $limit = null) {

        $this->build($model, $relations, $select, $filters, $groupBy, $limit);

        return $this->query->toSQL();
    }
}