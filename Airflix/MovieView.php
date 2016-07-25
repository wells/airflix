<?php

namespace Airflix;

use Illuminate\Database\Eloquent\Model;

class MovieView extends Model
{
    use Filterable, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'movie_id',
        'movie_uuid',
        'tmdb_movie_id',
    ];
}
