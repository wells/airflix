<?php

namespace Airflix;

use Illuminate\Pagination\LengthAwarePaginator;
use Tmdb;

class MovieResults implements Contracts\MovieResults
{
    use Retriable;

    /**
     * Inject the tmdb movie result transformer.
     *
     * @return \Airflix\Contracts\TmdbMovieResultTransformer
     */
    public function transformer()
    {
        return app(
            Contracts\TmdbMovieResultTransformer::class
        );
    }

    /**
     * Get paginated tmdb movie results.
     *
     * @param  \Airflix\Movie $movie
     * @param  integer $currentPage
     * @param  string $url
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function get($movie, $currentPage = 1, $url = null)
    {
        $perPage = config('airflix.tmdb.per_page', 20);

        $search = $this->retry(3,
            function () use ($movie, $currentPage) {
                return Tmdb::getSearchApi()
                    ->searchMovies($movie->folder_name, [
                        'page' => $currentPage,
                    ]);
            }, function () { 
                sleep(config('airflix.tmdb.throttle_seconds')); 
            });
        
        $items = $search['results'];
        $total = $search['total_results'];

        $paginator = new LengthAwarePaginator(
            $items, $total, $perPage, $currentPage
        );
        $paginator->setPath($url);

        return $paginator;
    }
}
