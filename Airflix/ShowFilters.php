<?php

namespace Airflix;

class ShowFilters extends QueryFilters
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
        'first_air_date',
        '-first_air_date',
        'last_air_date',
        '-last_air_date',
        'name',
        '-name',
    ];

    /**
     * Filter by genre.
     *
     * @param  string $uuid
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
     * @param  string $keywords
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
