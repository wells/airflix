<?php

namespace Airflix;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Filter a result set.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Airflix\QueryFilters $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}