<?php

namespace Airflix;

use Tmdb;

class Genres implements Contracts\Genres
{
    use Retriable;

    /**
     * Inject the genre filters.
     *
     * @return \Airflix\GenreFilters
     */
    public function filters()
    {
        return app()->make(GenreFilters::class);
    }

    /**
     * Inject the genre transformer.
     *
     * @return \Airflix\Contracts\GenreTransformer
     */
    public function transformer()
    {
        return app()->make(
            Contracts\GenreTransformer::class
        );
    }

    /**
     * Get a set of genres.
     *
     * @param  array $relationships
     * @param  bool $pagination
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return mixed
     */
    public function index($relationships = [], $pagination = true, $query = null)
    {
        if (!$query) {
            $query = new Genre;
        }

        $results = $query->with($relationships)
            ->filter($this->filters());

        if ($pagination) {
            $results = $results->paginate(config('airflix.per_page', 100))
                ->appends($this->filters()->parameters());
        } else {
            $results = $results->get();
        }

        return $results;
    }

    /**
     * Get the genre ids from the provided tmdb genre ids.
     *
     * @param  array $tmdbIds
     *
     * @return array
     */
    public function getIds($tmdbIds)
    {
        return Genre::whereIn('tmdb_genre_id', $tmdbIds)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Update genres with data from the tmdb API.
     *
     * @param  \Symfony\Component\Console\Output\ConsoleOutput $output
     *
     * @return integer
     */
    public function refreshGenres($output = null)
    {
        $genres = $this->retry(3, function () {
                return collect(
                        Tmdb::getGenresApi()
                            ->getGenres()['genres']
                    )->unique('id');
            }, function () {
                sleep(config('airflix.tmdb.throttle_seconds'));
            });

        $bar = null;

        // Create the progress bar
        if($output) {
            $bar = $output->createProgressBar($genres->count());
            $bar->setFormat('verbose');
        }

        foreach ($genres as $genre) {
            Genre::firstOrCreate([
                'tmdb_genre_id' => $genre['id'],
                'name' => $genre['name'],
            ]);

            // Advance the progress bar
            if($bar) {
                $bar->advance();
            }
        }

        Genre::whereNotIn('tmdb_genre_id', $genres->pluck('id'))
            ->delete();

        // Finish and clear the progress bar
        if($bar) {
            $bar->finish();
            $bar->clear();
        }

        return Genre::all()->count();
    }

    /**
     * Update genres with total movies count.
     *
     * @return integer
     */
    public function refreshTotalMovies()
    {
        Genre::withCount(['movies'])->
            chunk(100, function ($genres) {
                foreach ($genres as $genre) {
                    $genre->total_movies = $genre->movies_count;
                    $genre->save();
                }
            });

        return Genre::all()->count();
    }

    /**
     * Update genres with total shows count.
     *
     * @return integer
     */
    public function refreshTotalShows()
    {
        Genre::withCount(['shows'])->
            chunk(100, function ($genres) {
                foreach ($genres as $genre) {
                    $genre->total_shows = $genre->shows_count;
                    $genre->save();
                }
            });

        return Genre::all()->count();
    }
}
