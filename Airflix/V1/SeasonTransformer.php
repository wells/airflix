<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class SeasonTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\SeasonTransformer
{
    public $resourceType = 'seasons';

    public $resourceVersion = 1.0;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'episodes',
        'show',
        'views',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\Season $season
     *
     * @return array
     */
    public function transform($season)
    {
        return [
            'id' => $season->uuid,
            'show_id' => $season->show_uuid,
            'season_number' => (int) $season->season_number,
            'name' => $season->name,
            'overview' => $season->overview,
            'poster_url' => $season->poster_url,
            'number_of_episodes' => (int) $season->episode_count,
            'air_date' => $season->air_date ? 
                $season->air_date->toIso8601String() : null,
            'total_views' => (int) $season->total_views,
        ];
    }

    /**
     * Include episodes
     *
     * @param  \Airflix\Season $season
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeEpisodes($season)
    {
        $episodes = $season->episodes;

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
     * Include show
     *
     * @param  \Airflix\Season $season
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeShow($season)
    {
        $show = $season->show;

        $transformer = app(
            Contracts\ShowTransformer::class
        );

        return $this->item(
            $show, 
            $transformer, 
            $transformer->resourceType
        );
    }

    /**
     * Include views
     *
     * @param  \Airflix\Season $season
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeViews($season)
    {
        $views = $season->monthlyViews();

        $transformer = app(
            Contracts\SeasonViewMonthlyTransformer::class
        );

        return $this->collection(
            $views, 
            $transformer, 
            $transformer->resourceType
        );
    }
}
