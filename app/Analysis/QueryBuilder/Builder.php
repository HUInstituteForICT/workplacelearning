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

        $select = [];
        $filters = [];
        $groupBy = [];

        $modelString = 'App\\' . $model;
        $mainModel = new $modelString();

        $this->query = \DB::connection("dashboard")->table($mainModel->getTable());

        foreach($selectData as $data) {

            $tableString = 'App\\' . $data['table'];
            $tableModel = (new $tableString())->getTable();

            switch($data['type']) {

                case "data":
                    $select[] = $tableModel.'.'.$data['column'];

                    break;

                case "sum":
                    $select[] = \DB::raw('SUM('.$tableModel.'.'.$data['column'].') as '.$data['column']);
                    break;

                case "count":
                    $select[] = \DB::raw('COUNT('.$tableModel.'.'.$data['column'].') as amount_of_'.$data['column']);
                    break;
            }
        }

        foreach($filterData as $filter) {

            $tableString = 'App\\' . $filter['table'];
            $tableModel = (new $tableString())->getTable();

            switch($filter['type']) {

                case "limit":
                    $limit = $filter['value'];
                    break;

                case "group":
                    $groupBy[] = $tableModel.'.'.$filter['column'];
                    break;

                case "equals":
                    $filters[] = [$tableModel.'.'.$filter['column'], '=', $filter['value']];
                    break;
            }
        }

        foreach($relations as $r) {

            $join = $mainModel->$r()->getRelated();

            if($mainModel->$r() instanceof BelongsTo) {

                $this->query->join($join->getTable(),
                    $join->getTable().'.'.$join->getKeyName(),
                    '=',
                    $mainModel->$r()->getQualifiedForeignKey());
            } else {

                $this->query->join($join->getTable(),
                    $mainModel->$r()->getQualifiedForeignKeyName(),
                    '=',
                    $mainModel->$r()->getQualifiedParentKeyName());
            }
        }

        if(!empty($groupBy)) {

            $this->query->groupBy($groupBy);
        }

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

        $query = $this->query->toSQL();
        $bindings = $this->query->getBindings();

        return vsprintf(str_replace("?", "%s", $query), $bindings);
    }
}