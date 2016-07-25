<?php

namespace Airflix;

class GenreFilters extends QueryFilters
{
    /**
     * The default column to sort by.
     *
     * @var string
     */
    protected $sortDefault = 'name';

    /**
     * The valid columns to sort by.
     *
     * @var array
     */
    protected $sortColumns = [
        'name',
        '-name',
        'total_movies',
        '-total_movies',
        'total_shows',
        '-total_shows',
    ];

    /**
     * Filter by keywords.
     *
     * @param  string|null $keywords
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function keywords($keywords = null)
    {
        if(! $keywords) {
            return $this->query;
        }

        return $this->query
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'like',
                    '%'.$keywords.'%');
            });
    }
}
