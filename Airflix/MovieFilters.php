<?php

namespace Airflix;

class MovieFilters extends QueryFilters
{
    /**
     * The default column to sort by.
     *
     * @var string
     */
    protected $sortDefault = 'title';

    /**
     * The valid columns to sort by.
     *
     * @var array
     */
    protected $sortColumns = [
        'release_date',
        '-release_date',
        'title',
        '-title',
    ];

    /**
     * Filter by genre.
     *
     * @param  string|null $uuid
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function genre($uuid = null)
    {
        if(! $uuid) {
            return $this->query;
        }

        return $this->query
            ->whereHas('genres',
                function ($query) use ($uuid) {
                    $query->where('uuid', $uuid);
                });
    }

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
                $query->where('title', 'like',
                    '%'.$keywords.'%');
            });
    }
}
