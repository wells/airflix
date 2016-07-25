<?php

namespace Airflix;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use Filterable, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tmdb_genre_id',
        'name',
    ];

    /**
     * Get the movies for the genre.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function movies()
    {
        return $this->belongsToMany(
            Movie::class, 'genre_movie', 'genre_id', 'movie_id'
        );
    }

    /**
     * Get the tv shows for the genre.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function shows()
    {
        return $this->belongsToMany(
            Show::class, 'genre_show', 'genre_id', 'show_id'
        );
    }
}
