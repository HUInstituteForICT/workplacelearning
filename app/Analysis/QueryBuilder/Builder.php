<?php

declare(strict_types=1);

namespace App\Analysis\QueryBuilder;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Builder provides the query builder functionality.
 * It can be used to generate a query and get the sql query or data from it.
 */
class Builder
{
    private $query;

    private function build($model, $relations, $selectData, $filterData, $sort = null, $limit = null): void
    {
        $select = [];
        $filters = [];
        $groupBy = [];

        $modelString = 'App\\'.$model;
        $mainModel = new $modelString();

        $this->query = \DB::connection('dashboard')->table($mainModel->getTable());

        //Select fields
        foreach ($selectData as $data) {
            $tableString = 'App\\'.$data['table'];
            $tableModel = (new $tableString())->getTable();

            $select[] = $this->getFunctionNames($data['type'], $tableModel, $data['column']);
        }

        $this->query->select($select);

        //Where & GroupBy fields
        foreach ($filterData as $filter) {
            $tableString = 'App\\'.$filter['table'];
            $tableModel = (new $tableString())->getTable();

            switch ($filter['type']) {
                case 'group':
                    $groupBy[] = $tableModel.'.'.$filter['column'];
                    break;

                case 'equals':
                    $filters[] = [$tableModel.'.'.$filter['column'], '=', $filter['value']];
                    break;

                case 'largerthan':
                    $filters[] = [$tableModel.'.'.$filter['column'], '>', $filter['value']];
                    break;

                case 'smallerthan':
                    $filters[] = [$tableModel.'.'.$filter['column'], '<', $filter['value']];
                    break;
            }
        }

        foreach ($filters as $filter) {
            $this->query->where($filter[0], $filter[1], $filter[2]);
        }

        if (!empty($groupBy)) {
            $this->query->groupBy($groupBy);
        }

        //Joins
        foreach ($relations as $r) {
            $join = $mainModel->$r()->getRelated();

            if ($mainModel->$r() instanceof BelongsTo) {
                $this->query->join($join->getTable(),
                    $join->getTable().'.'.$join->getKeyName(),
                    '=',
                    $mainModel->$r()->getQualifiedForeignKey());
            } else {
                $this->query->join($join->getTable(),
                    $mainModel->$r()->getQualifiedForeignPivotKeyName(),
                    '=',
                    $mainModel->$r()->getQualifiedForeignPivotKeyName());
            }
        }

        if ($sort != null) {
            foreach ($sort as $s) {
                $modelString = 'App\\'.$s['table'];
                $table = new $modelString();

                $this->query->orderBy($this->getFunctionNames($s['type'], $table->getTable(), $s['column']), $s['order']);
            }
        }

        if ($limit != null) {
            $this->query->limit($limit);
        }
    }

    private function getFunctionNames($type, $table, $column)
    {
        $string = '';

        switch ($type) {
            case 'data':
                $string = $table.'.'.$column;
                break;

            case 'sum':
                $string = \DB::raw('SUM('.$table.'.'.$column.')');
                break;

            case 'count':
                $string = \DB::raw('COUNT('.$table.'.'.$column.')');
                break;

            case 'avg':
                $string = \DB::raw('AVG('.$table.'.'.$column.')');
                break;
        }

        return $string;
    }

    public function getData($model, $relations, $select, $filters, $sort, $limit = null)
    {
        $this->build($model, $relations, $select, $filters, $sort, $limit);

        try {
            return $this->query->get();
        } catch (\Illuminate\Database\QueryException $e) {
            return collect(['error' => __('querybuilder.query-error')]);
        }
    }

    public function getQuery($model, $relations, $select, $filters, $sort, $limit = null)
    {
        $this->build($model, $relations, $select, $filters, $sort, $limit);

        $query = $this->query->toSQL();
        $bindings = $this->query->getBindings();

        return vsprintf(str_replace('?', '%s', $query), $bindings);
    }

    public function getSelectFields($selectData)
    {
        $select = [];
        foreach ($selectData as $data) {
            $tableString = 'App\\'.$data['table'];
            $tableModel = (new $tableString())->getTable();

            $select[] = $this->getFunctionNames($data['type'], $tableModel, $data['column']);
        }

        return $select;
    }
}
