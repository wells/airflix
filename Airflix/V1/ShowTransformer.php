<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class ShowTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\ShowTransformer
{
    public $resourceType = 'shows';

    public $resourceVersion = 1.0;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'backdrops',
        'episodes',
        'genres',
        'posters',
        'results',
        'seasons',
        'views',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\Show $show
     *
     * @return array
     */
    public function transform($show)
    {
        return [
            'id' => $show->uuid,
            'tmdb_show_id' => $show->tmdb_show_id,
            'name' => $show->name,
            'original_name' => $show->original_name,
            'folder_name' => $show->folder_name,
            'overview' => $show->overview,
            'home_page' => $show->homepage,
            'poster_url' => $show->poster_url,
            'backdrop_url' => $show->backdrop_url,
            'number_of_seasons' => (int) $show->number_of_seasons,
            'number_of_episodes' => (int) $show->number_of_episodes,
            'average_runtime' => (int) $show->average_runtime,
            'popularity' => (double) $show->popularity,
            'first_air_date'    => $show->first_air_date ? 
                $show->first_air_date->toIso8601String() : null,
            'last_air_date'    => $show->last_air_date ? 
                $show->last_air_date->toIso8601String() : null,
            'total_views' => (int) $show->total_views,
            'tmdb_url' => config('airflix.urls.tmdb.show').
                $show->tmdb_show_id,
            'imdb_url' => $show->imdb_id ? 
                config('airflix.urls.imdb').$show->imdb_id : null,
            'tvdb_url' => $show->tvdb_id != 0 ? 
                config('airflix.urls.tvdb').$show->tvdb_id : null,
        ];
    }

    /**
     * Include backdrops
     *
     * @param  \Airflix\Show $show
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeBackdrops($show)
    {
        $backdrops = $this->showImages()
            ->getBackdrops($show);

        $transformer = app(
            Contracts\TmdbImageTransformer::class
        );

        return $this->collection(
            $backdrops, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include episodes
     *
     * @param  \Airflix\Show $show
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeEpisodes($show)
    {
        $episodes = $show->episodes;

        $transformer = app(
            Contracts\EpisodeTransformer::class
        );

        return $this->collection(
            $episodes, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include genres
     *
     * @param  \Airflix\Show $show
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeGenres($show)
    {
        $genres = $show->genres;

        $transformer = app(
            Contracts\GenreTransformer::class
        );

        return $this->collection(
            $genres, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include posters
     *
     * @param  \Airflix\Show $show
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosters($show)
    {
        $posters = $this->showImages()
            ->getPosters($show);

        $transformer = app(
            Contracts\TmdbImageTransformer::class
        );

        return $this->collection(
            $posters, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include results
     *
     * @param  \Airflix\Show $show
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeResults($show)
    {
        $results = $this->showResults()
            ->get($show);

        $transformer = app(
            Contracts\TmdbShowResultTransformer::class
        );

        return $this->collection(
            $results, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include Season
     *
     * @param  \Airflix\Show $show
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSeasons($show)
    {
        $seasons = $show->seasons;

        $transformer = app(
            Contracts\SeasonTransformer::class
        );

        return $this->collection(
            $seasons, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include views
     *
     * @param  \Airflix\Show $show
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeViews($show)
    {
        $views = $show->monthlyViews();

        $transformer = app(
            Contracts\ShowViewMonthlyTransformer::class
        );

        return $this->collection(
            $views, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Inject the show images resource.
     *
     * @return \Airflix\Contracts\ShowImages
     */
    protected function showImages()
    {
        return app(
            Contracts\ShowImages::class
        );
    }

    /**
     * Inject the show results resource.
     *
     * @return \Airflix\Contracts\ShowResults
     */
    protected function showResults()
    {
        return app(
            Contracts\ShowResults::class
        );
    }
}
