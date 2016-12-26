<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class EpisodeTransformer
    extends Fractal\TransformerAbstract
    implements Contracts\EpisodeTransformer
{
    public $resourceType = 'episodes';

    public $resourceVersion = 1.0;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'show',
        'season',
        'views',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\Episode $episode
     *
     * @return array
     */
    public function transform($episode)
    {
        return [
            'id' => $episode->uuid,
            'show_id' => $episode->show_uuid,
            'season_id' => $episode->season_uuid,
            'season' => (int) $episode->season_number,
            'episode' => (int) $episode->episode_number,
            'name' => $episode->name,
            'overview' => $episode->overview,
            'still_url' => $episode->still_url,
            'air_date' => $episode->air_date ?
                $episode->air_date->toIso8601String() : null,
            'total_views' => (int) $episode->total_views,
            'has_file' => (bool) $episode->has_file,
        ];
    }

    /**
     * Include show
     *
     * @param  \Airflix\Episode $episode
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeShow($episode)
    {
        $show = $episode->show;

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
     * Include season
     *
     * @param  \Airflix\Episode $episode
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeSeason($episode)
    {
        $season = $episode->season;

        $transformer = app(
            Contracts\SeasonTransformer::class
        );

        return $this->item(
            $season,
            $transformer,
            $transformer->resourceType
        );
    }

    /**
     * Include views
     *
     * @param  \Airflix\Episode $episode
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeViews($episode)
    {
        $views = $episode->monthlyViews();

        $transformer = app(
            Contracts\EpisodeViewMonthlyTransformer::class
        );

        return $this->collection(
            $views,
            $transformer,
            $transformer->resourceType
        );
    }
}
