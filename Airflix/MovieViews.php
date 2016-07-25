<?php

namespace Airflix;

use Carbon\Carbon;

class MovieViews implements Contracts\MovieViews
{
    /**
     * Inject the movies resource.
     *
     * @return \Airflix\Contracts\Movies
     */
    public function movies()
    {
        return app()->make(
            Contracts\Movies::class
        );
    }

    /**
     * Inject the movie views transformer.
     *
     * @return \Airflix\Contracts\MovieViewsTransformer
     */
    public function transformer()
    {
        return app()->make(
            Contracts\MovieViewsTransformer::class
        );
    }

    /**
     * Link movie views to a movie.
     *
     * @param  \Airflix\Movie $movie
     * @param  integer $tmdbMovieId
     *
     * @return \Illuminate\Support\Collection
     */
    public function link($movie, $tmdbMovieId)
    {
        return MovieView::where('tmdb_movie_id', $tmdbMovieId)
            ->where('movie_id', 0)
            ->update([
                'movie_id' => $movie->id,
                'movie_uuid' => $movie->uuid,
            ]);
    }

    /**
     * Unlink all movie views from their movies.
     *
     * @return void
     */
    public function unlink()
    {
        return MovieView::where('movie_id', '<>', 0)
            ->update([
                'movie_id' => 0,
                'movie_uuid' => null,
            ]);
    }

    /**
     * Create a movie view within time limit.
     *
     * @param  \Airflix\Movie $movie
     * @param  integer $timeLimit
     *
     * @return \Airflix\MovieView
     */
    public function watch($movie, $timeLimit = null)
    {
        $timeLimit = $timeLimit ?:
            Carbon::now()
                ->subMinutes($movie->runtime + 60);

        $view = MovieView::where('created_at', '>=', $timeLimit)
            ->firstOrNew([
                'movie_id' => $movie->id,
                'movie_uuid' => $movie->uuid,
                'tmdb_movie_id' => $movie->tmdb_movie_id,
            ]);

        $view->save();

        $this->movies()
            ->updateTotalViews($movie);

        return $view;
    }

    /**
     * Delete movie views, either from today or all time.
     *
     * @param  bool $clearToday
     *
     * @return bool
     */
    public function clearHistory($clearToday = false)
    {
        if($clearToday) {
            $startOfDay = Carbon::now()->startOfDay();
            
            return MovieView::where('created_at', '>=', $startOfDay)
                ->delete();
        }

        return MovieView::truncate();
    }
}
