<?php

namespace Airflix;

use Carbon\Carbon;
use Tmdb;

class Seasons implements Contracts\Seasons
{
    use Retriable;

    /**
     * Inject the episodes resource.
     *
     * @return \Airflix\Contracts\Episodes
     */
    public function episodes()
    {
        return app(
            Contracts\Episodes::class
        );
    }

    /**
     * Inject the tmdb image client.
     *
     * @return \Airflix\Contracts\TmdbImageClient
     */
    public function imageClient()
    {
        return app(
            Contracts\TmdbImageClient::class
        );
    }

    /**
     * Inject the season transformer.
     *
     * @return \Airflix\Contracts\SeasonTransformer
     */
    public function transformer()
    {
        return app(
            Contracts\SeasonTransformer::class
        );
    }

    /**
     * Get a season.
     *
     * @param  string $id
     * @param  array $relationships
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Airflix\Season
     */
    public function get($id, $relationships = [], $query = null)
    {
        if (!$query) {
            $query = new Season;
        }

        return $query->with($relationships)
            ->where('uuid', $id)
            ->where('air_date', '<=', Carbon::now())
            ->firstOrFail();
    }

    /**
     * Update the total views of a season.
     *
     * @param  \Airflix\Season $season
     *
     * @return \Airflix\Season
     */
    public function updateTotalViews($season)
    {
        $season->total_views = $season->views()->count();

        $season->save();

        return $season;
    }

    /**
     * Update the season with data from the tmdb API.
     *
     * @param  integer $seasonNumber
     * @param  \Airflix\Show $show
     *
     * @return \Airflix\Season
     */
    public function refreshSeason($seasonNumber, $show)
    {
        $result = $this->retry(3, 
            function () use ($show, $seasonNumber) {
                return Tmdb::getTvSeasonApi()
                    ->getSeason($show->tmdb_show_id, $seasonNumber);
            }, function () { 
                sleep(config('airflix.tmdb.throttle_seconds')); 
            });

        $season = Season::firstOrNew([
            'tmdb_season_id' => $result['id'],
        ]);

        $season->show_id = $show->id;
        $season->show_uuid = $show->uuid;

        $season->save();

        $season->update(array_merge($result, [
            'tmdb_show_id' => $show->tmdb_show_id,
            'episode_count' => count($result['episodes']),
        ]));

        $season->name = $season->season_number == 0 ?
            'Extras' : 'Season '.$season->season_number;

        $season->save();

        // Update total Episode Views
        $this->updateTotalViews($season);

        $this->imageClient()
            ->download($season->poster_path, 'seasons');

        foreach ($result['episodes'] as $episode) {
            // Reduce number of API calls and pass the Episode data
            $episode = $this->episodes()
                ->refreshEpisode($episode, $show, $season);
        }

        return $season;
    }

    /**
     * Truncate the seasons table.
     * 
     * @return bool
     */
    public function truncate()
    {
        return Season::truncate();
    }
}
