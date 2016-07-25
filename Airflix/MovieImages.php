<?php

namespace Airflix;

use Tmdb;

class MovieImages implements Contracts\MovieImages
{
    use Retriable;

    /**
     * Inject the tmdb image transformer.
     *
     * @return \Airflix\Contracts\TmdbImageTransformer
     */
    public function transformer()
    {
        return app()->make(
            Contracts\TmdbImageTransformer::class
        );
    }

    /**
     * Get tmdb movie images.
     *
     * @param  \Airflix\Movie $movie
     *
     * @return array
     */
    protected function get($movie)
    {
        return $this->retry(3, 
            function () use ($movie) {
                return Tmdb::getMoviesApi()
                    ->getImages($movie->tmdb_movie_id, [
                        'include_image_language' => 
                            config('airflix.tmdb.languages'),
                    ]);
            }, function () { 
                sleep(config('airflix.tmdb.throttle_seconds')); 
            });
    }

    /**
     * Get tmdb movie backdrops.
     *
     * @param  \Airflix\Movie $movie
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBackdrops($movie)
    {
        if ($movie->tmdb_movie_id == 0) {
            return [];
        }

        $images = $this->get($movie);

        // Remove any duplicates with the keyBy collection method
        return collect($images['backdrops'])
            ->keyBy('file_path')
            ->all();
    }

    /**
     * Get tmdb movie posters.
     *
     * @param  \Airflix\Movie $movie
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPosters($movie)
    {
        if ($movie->tmdb_movie_id == 0) {
            return [];
        }

        $images = $this->get($movie);

        // Remove any duplicates with the keyBy collection method
        return collect($images['posters'])
            ->keyBy('file_path')
            ->all();
    }
}
