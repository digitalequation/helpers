<?php

namespace DigitalEquation\Helpers\Traits;

use DigitalEquation\Helpers\Exceptions\TablePaginateException;
use DigitalEquation\Helpers\Paginate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait TablePaginate
{
    /**
     * Wrapper for filtering and paginating a model query using React material data table pagination format
     *
     * @param Builder $query
     *
     * @param Request $request request containing filter / pagination data
     * @param integer $defaultRowsPerPage default rows per page when not provided in request
     * @param string $defaultSortColumn default column for initial sorting
     * @param string $defaultSortOrder default order for sorting
     *
     * @return mixed
     * @throws TablePaginateException
     */
    public function scopeTablePaginate($query, Request $request, $defaultRowsPerPage = 10, $defaultSortColumn = 'id', $defaultSortOrder = 'desc')
    {
        $driver = Paginate::getDriver();

        if ($driver === 'react') {
            return $this->reactDriver($query, $request, $defaultRowsPerPage, $defaultSortColumn, $defaultSortOrder);
        }

        if ($driver === 'vue') {
            return $this->vueDriver($query, $request, $defaultRowsPerPage, $defaultSortColumn, $defaultSortOrder);
        }

        throw new TablePaginateException('Table Paginate expects "react" or "vue" as the driver.');
    }

    /**
     * React Driver for table pagination.
     *
     * @param Builder $query
     *
     * @param Request $request request containing filter / pagination data
     * @param integer $defaultRowsPerPage default rows per page when not provided in request
     * @param string $defaultSortColumn default column for initial sorting
     * @param string $defaultSortOrder default order for sorting
     *
     * @return mixed
     */
    private function reactDriver($query, Request $request, $defaultRowsPerPage, $defaultSortColumn, $defaultSortOrder)
    {
        $rowsPerPage = $request->filled('pageSize') ? (int)$request->pageSize : $defaultRowsPerPage;
        $sortOrder = $request->filled('orderDirection') ? $request->orderDirection : $defaultSortOrder;

        // Order column
        if ($request->filled('orderBy')) {
            $col = json_decode($request->orderBy);
            $sortBy = $col->field;
        } else {
            $sortBy = $defaultSortColumn;
        }

        // Filters
        if ($request->has('filters')) {
            foreach ($request->filters as $filter) {
                $filter = json_decode($filter, true);
                $query =
                    is_array($filter['value']) ? $query->whereIn($filter['column']['field'], $filter['value']) :
                        $query->where($filter['column']['field'], $filter['value']);
            }
        }

        // Search
        if ($request->filled('search') && is_array($this->searchable)) {
            $search = $request->search;
            $searchable = $this->searchable;

            $query = $query->where(function ($q) use ($search, $searchable) {
                foreach ($searchable as $col) {
                    $q = $q->orWhere($col, 'LIKE', sprintf('%%%s%%', $search));
                }

                return $q;
            });
        }

        // Sorting
        $query = $query->orderBy($sortBy, $sortOrder);

        // Pagination
        return $rowsPerPage ? $query->paginate($rowsPerPage) : $query->get();
    }

    /**
     * Vue Driver for table pagination.
     *
     * @param Builder $query
     *
     * @param Request $request request containing filter / pagination data
     * @param integer $defaultRowsPerPage default rows per page when not provided in request
     * @param string $defaultSortColumn default column for initial sorting
     * @param string $defaultSortOrder default order for sorting
     *
     * @return mixed
     */
    private function vueDriver($query, Request $request, $defaultRowsPerPage, $defaultSortColumn, $defaultSortOrder)
    {
        $rowsPerPage = $request->has('rowsPerPage') ? $request->rowsPerPage : $defaultRowsPerPage;
        $sortBy = $request->has('sortBy') ? $request->sortBy : $defaultSortColumn;
        $sortOrder =
            $request->has(['sortBy', 'descending']) ? ($request->descending === 'false' ? 'ASC' : 'DESC') :
                $defaultSortOrder;

        // Filters
        if ($request->has('filters')) {
            $request->filters = json_decode($request->filters, true);
            foreach ($request->filters as $column => $value) {
                if ($column === 'withTrashed') {
                    $query = $query->withTrashed();
                } else {
                    $query = is_array($value) ? $query->whereIn($column, $value) : $query->where($column, $value);
                }

            }
        }

        // Search
        if ($request->filled('search') && is_array($this->searchable)) {
            $search = $request->search;
            $searchable = $this->searchable;

            $query = $query->where(function ($q) use ($search, $searchable) {
                foreach ($searchable as $column) {
                    $q = $q->orWhere($column, 'LIKE', sprintf('%%%s%%', $search));
                }

                if (is_array($this->searchable_relationships)) {
                    foreach ($this->searchable_relationships as $relationship => $columns) {
                        foreach ($columns as $column) {
                            $q = $q->orWhereHas($relationship, function ($q) use ($column, $search) {
                                $q->where($column, 'LIKE', sprintf('%%%s%%', $search));
                            });
                        }
                    }
                }

                return $q;
            });
        }

        // Sorting
        $query = $query->orderBy($sortBy, $sortOrder);

        // Pagination
        return $rowsPerPage ? $query->paginate($rowsPerPage) : $query->get();
    }
}