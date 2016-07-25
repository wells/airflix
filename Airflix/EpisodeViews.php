<?php

namespace Airflix;

use Carbon\Carbon;

class EpisodeViews implements Contracts\EpisodeViews
{
    /**
     * Inject the episodes resource.
     *
     * @return \Airflix\Contracts\Episodes
     */
    public function episodes()
    {
        return app()->make(
            Contracts\Episodes::class
        );
    }

    /**
     * Inject the seasons resource.
     *
     * @return \Airflix\Contracts\Seasons
     */
    public function seasons()
    {
        return app()->make(
            Contracts\Seasons::class
        );
    }

    /**
     * Inject the tv shows resource.
     *
     * @return \Airflix\Contracts\Shows
     */
    public function shows()
    {
        return app()->make(
            Contracts\Shows::class
        );
    }

    /**
     * Inject the episode views transformer.
     *
     * @return \Airflix\Contracts\EpisodeViewMonthlyTransformer
     */
    public function transformer()
    {
        return app()->make(
            Contracts\EpisodeViewMonthlyTransformer::class
        );
    }

    /**
     * Link episode views to a tv show.
     *
     * @param  \Airflix\Show $show
     * @param  integer $tmdbShowId
     *
     * @return \Illuminate\Support\Collection
     */
    public function link($show, $tmdbShowId)
    {
        return EpisodeView::where('tmdb_show_id', $tmdbShowId)
            ->where('show_id', 0)
            ->update([
                'show_id' => $show->id,
                'show_uuid' => $show->uuid,
            ]);
    }

    /**
     * Unlink all episode views from their tv shows.
     *
     * @return bool
     */
    public function unlink()
    {
        return EpisodeView::where('show_id', '<>', 0)
            ->update([
                'show_id' => 0,
                'show_uuid' => null,
            ]);
    }

    /**
     * Create an episode view within time limit.
     *
     * @param  \Airflix\Episode $episode
     * @param  integer $timeLimit
     *
     * @return \Airflix\EpisodeView
     */
    public function watch($episode, $timeLimit = null)
    {
        $timeLimit = $timeLimit ?:
            Carbon::now()
                ->subMinutes($episode->show->average_runtime + 60);

        $view = EpisodeView::where('created_at', '>=', $timeLimit)
            ->firstOrNew([
                'show_id' => $episode->show_id,
                'season_id' => $episode->season_id,
                'episode_id' => $episode->id,
                'show_uuid' => $episode->show_uuid,
                'season_uuid' => $episode->season_uuid,
                'episode_uuid' => $episode->uuid,
                'tmdb_show_id' => $episode->tmdb_show_id,
            ]);

        $view->save();

        $episode->load(['show', 'season', ]);

        $this->episodes()
            ->updateTotalViews($episode);

        $this->seasons()
            ->updateTotalViews($episode->season);

        $this->shows()
            ->updateTotalViews($episode->show);

        return $view;
    }

    /**
     * Delete episode views, either from today or all time.
     *
     * @param  bool $clearToday
     *
     * @return bool
     */
    public function clearHistory($clearToday = false)
    {
        if($clearToday) {
            $startOfDay = Carbon::now()->startOfDay();

            return EpisodeView::where('created_at', '>=', $startOfDay)
                ->delete();
        }

        return EpisodeView::truncate();
    }
}
