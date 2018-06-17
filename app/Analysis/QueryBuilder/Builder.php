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

    private function build($model, $relations, $selectData, $filterData, $sort = null, $limit = null) {

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
                    $select[] = \DB::raw('SUM('.$tableModel.'.'.$data['column'].')');
                    break;

                case "count":
                    $select[] = \DB::raw('COUNT('.$tableModel.'.'.$data['column'].')');
                    break;

                case "avg":
                    $select[] = \DB::raw('AVG('.$tableModel.'.'.$data['column'].')');
                    break;
            }
        }

        foreach($filterData as $filter) {

            $tableString = 'App\\' . $filter['table'];
            $tableModel = (new $tableString())->getTable();

            switch($filter['type']) {

                case "group":
                    $groupBy[] = $tableModel.'.'.$filter['column'];
                    break;

                case "equals":
                    $filters[] = [$tableModel.'.'.$filter['column'], '=', $filter['value']];
                    break;

                case "largerthan":
                    $filters[] = [$tableModel.'.'.$filter['column'], '>', $filter['value']];
                    break;

                case "smallerthan":
                    $filters[] = [$tableModel.'.'.$filter['column'], '<', $filter['value']];
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

        if($sort != null) {

            foreach($sort as $s) {

                switch($s['type']) {

                        case "data":
                            $this->query->orderBy($s['table'].'.'.$s['column'], $s['order']);
                            break;

                        case "sum":
                            $this->query->orderBy(\DB::raw('SUM('.$s['table'].'.'.$s['column'].')'), $s['order']);
                            break;

                        case "count":
                            $this->query->orderBy(\DB::raw('COUNT('.$s['table'].'.'.$s['column'].')'), $s['order']);

                            break;

                        case "avg":
                            $this->query->orderBy(\DB::raw('AVG('.$s['table'].'.'.$s['column'].')'), $s['order']);
                            break;
                    }

            }
        }

        if($limit != null) {

            $this->query->limit($limit);
        }
    }

    public function getData($model, $relations, $select, $filters, $sort, $limit = null) {

        $this->build($model, $relations, $select, $filters, $sort, $limit);

        return $this->query->get();
    }

    public function getQuery($model, $relations, $select, $filters, $sort, $limit = null) {

        $this->build($model, $relations, $select, $filters, $sort, $limit);

        $query = $this->query->toSQL();
        $bindings = $this->query->getBindings();

        return vsprintf(str_replace("?", "%s", $query), $bindings);
    }
}