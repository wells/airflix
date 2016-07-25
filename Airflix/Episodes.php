<?php

namespace Airflix;

use Carbon\Carbon;
use Tmdb;

class Episodes implements Contracts\Episodes
{
    /**
     * Inject the tmdb image client.
     *
     * @return \Airflix\Contracts\TmdbImageClient
     */
    public function imageClient()
    {
        return app()->make(
            Contracts\TmdbImageClient::class
        );
    }

    /**
     * Inject the episode transformer.
     *
     * @return \Airflix\Contracts\EpisodeTransformer
     */
    public function transformer()
    {
        return app()->make(
            Contracts\EpisodeTransformer::class
        );
    }

    /**
     * Get an episode.
     *
     * @param  string $id
     * @param  array $relationships
     * @param  \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Airflix\Episode
     */
    public function get($id, $relationships = [], $query = null)
    {
        if (!$query) {
            $query = new Episode;
        }

        return $query->with($relationships)
            ->where('uuid', $id)
            ->where('air_date', '<=', Carbon::now())
            ->firstOrFail();
    }

    /**
     * Update the total views of an episode.
     *
     * @param  \Airflix\Episode $episode
     *
     * @return \Airflix\Episode
     */
    public function updateTotalViews($episode)
    {
        $episode->total_views = $episode->views()->count();

        $episode->save();

        return $episode;
    }

    /**
     * Update the episode with data from the tmdb API.
     *
     * @param  array $result
     * @param  \Airflix\Show $show
     * @param  \Airflix\Season $season
     *
     * @return \Airflix\Episode
     */
    public function refreshEpisode($result, $show, $season)
    {
        $episode = Episode::firstOrNew([
            'tmdb_episode_id' => $result['id'],
        ]);

        $episode->show_id = $show->id;
        $episode->season_id = $season->id;
        $episode->show_uuid = $show->uuid;
        $episode->season_uuid = $season->uuid;

        $episode->save();

        $episode->update(array_merge($result, [
            'tmdb_season_id' => $season->tmdb_season_id,
            'tmdb_show_id' => $show->tmdb_show_id,
        ]));

        // Update total Episode Views
        $this->updateTotalViews($episode);

        $this->imageClient()
            ->download($episode->still_path, 'episodes');

        return $episode;
    }

    /**
     * Truncate the episodes table.
     * 
     * @return void
     */
    public function truncate()
    {
        return Episode::truncate();
    }
}
