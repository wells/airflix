<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class GenreTransformer
    extends Fractal\TransformerAbstract
    implements Contracts\GenreTransformer
{
    public $resourceType = 'genres';

    public $resourceVersion = 1.0;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'movies',
        'shows',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\Genre $genre
     *
     * @return array
     */
    public function transform($genre)
    {
        return [
            'id' => $genre->uuid,
            'name' => $genre->name,
            'total_movies' => $genre->total_movies,
            'total_shows' => $genre->total_shows,
        ];
    }

    /**
     * Include movies
     *
     * @param  \Airflix\Genre $genre
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMovies($genre)
    {
        $movies = $genre->movies;

        $transformer = app(
            Contracts\MovieTransformer::class
        );

        return $this->collection(
            $movies, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include shows
     *
     * @param  \Airflix\Genre $genre
     *
     * @return \League\Fractal\Resource\Shows
     */
    public function includeShows($genre)
    {
        $shows = $genre->shows;

        $transformer = app(
            Contracts\ShowTransformer::class
        );

        return $this->collection(
            $shows, 
            $transformer, 
            $transformer->resourceType
        );
    }
}
