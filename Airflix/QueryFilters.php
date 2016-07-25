<?php

namespace Airflix;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilters
{
    /**
     * The request object.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The query builder instance.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * The default column to sort by.
     *
     * @var string
     */
    protected $sortDefault;

    /**
     * The valid columns to sort by.
     *
     * @var array
     */
    protected $sortColumns = [];

    /**
     * Create a new QueryFilters instance.
     *
     * @param  \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters to the query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $query)
    {
        $this->query = $query;

        foreach ($this->parameters() as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }

            if (strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->query;
    }

    /**
     * Get all request parameters.
     *
     * @return array
     */
    public function parameters()
    {
        $parameters = $this->request
            ->except(['page',]);

        // Always call the sort method
        if(!isset($parameters['sort'])) {
            $parameters['sort'] = null;
        }

        return $parameters;
    }

    /**
     * Filter to sort results by column(s).
     *
     * @param  string|null $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sort($sort = null)
    {
        $sortColumns = (array) array_filter(
            explode(',', $sort), 'strlen'
        );

        // Only allow valid column names
        $sortColumns = array_intersect(
            $sortColumns, $this->sortColumns
        );

        // If nothing valid, we need a default sort
        if(!count($sortColumns) && $this->sortDefault) {
            $sortColumns[] = $this->sortDefault;
        }

        foreach ($sortColumns as $column) {
            $direction = 'asc';

            if (starts_with($column, '-')) {
                $direction = 'desc';
            }

            $column = preg_replace("/[^a-z_]/", "", $column);

            $this->query
                ->orderBy($column, $direction);
        }

        return $this->query;
    }
}
