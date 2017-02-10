<?php

namespace App\Services;


use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract as EloquentRepositoryAbstract;

/**
 * All repository classes that need to use jqgrid whsould extend this
 * class instead of EloquentRepositoryAbstract to fix issues regarding
 * filtering of fields from related tables
 *
 * @author Jeevan N
 */
class LaravelJqgridRepositoryService extends EloquentRepositoryAbstract
{
    /**
     * Get count of all filters rows
     * @param array $filters
     * @return int
     */
    public function getTotalNumberOfRows(array $filters = array())
    {
        $q = $this->applyFilterToQuery($this->Database, $filters);

        return count($q->get());
    }
    
    /**
     * Get all filtered data
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @param string $sord
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    /** @noinspection PhpHierarchyChecksInspection */
    public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array(), $nodeId, $nodeLevel, $exporting)
    {
        if (!is_null($orderBy) || !is_null($sord)) {
            $this->orderBy = array(array($orderBy, $sord));
        }
        if ($limit == 0) {
            $limit = 1;
        }
        $orderByRaw = array();
        foreach ($this->orderBy as $orderBy) {
            array_push($orderByRaw, implode(' ', $orderBy));
        }
        $orderByRaw = implode(',', $orderByRaw);
        $q = $this->Database;
        $q = $this->applyFilterToQuery($q, $filters);
        $rows = $q->take($limit)
                ->skip($offset)
                ->orderByRaw($orderByRaw)
                ->get($this->visibleColumns);
        if (!is_array($rows)) {
            $rows = $rows->toArray();
        }
        foreach ($rows as &$row) {
            $row = (array) $row;
        }

        return $rows;
    }
    
    /**
     * Append filter queries to query object
     * @param \Illuminate\Database\Query $q
     * @param array $filters
     * @return \Illuminate\Database\Query
     */
    private function applyFilterToQuery($q, $filters)
    {
        foreach ($filters as $filter) {
            if ($filter['op'] == 'is in') {
                $q->whereIn($filter['field'], explode(',', $filter['data']));
                continue;
            }
            if ($filter['op'] == 'is not in') {
                $q->whereNotIn($filter['field'], explode(',', $filter['data']));
                continue;
            }
            $q->having($filter['field'], $filter['op'], $filter['data']);
        }
        return $q;
    }
}
