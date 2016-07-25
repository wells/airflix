<?php

namespace Airflix;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use Filterable, Uuid;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'air_date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tmdb_show_id',
        'tmdb_season_id',
        'season_number',
        'name',
        'overview',
        'poster_path',
        'episode_count',
        'air_date',
    ];

    /**
     * Get the episodes for the season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function episodes()
    {
        return $this->hasMany(Episode::class)
            ->orderBy('season_number', 'ASC')
            ->orderBy('episode_number', 'ASC');
    }

    /**
     * Get the tv show for the season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    /**
     * Get the views for the season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function views()
    {
        $lastYear = Carbon::now()
            ->addMonth()
            ->startOfMonth()
            ->subYear();

        return $this->hasMany(EpisodeView::class)
            ->where('created_at', '>=', $lastYear)
            ->orderBy('created_at', 'DESC');
    }

    /**
     * Get the monthly views for the tv show.
     *
     * @return \Illuminate\Support\Collection
     */
    public function monthlyViews()
    {
        // Group and map views to a collection of totals
        return $this->views
            ->groupBy(function($view) {
                return $view->created_at->format('m/y');
            })
            ->map(function ($views) {
                $view = $views->first();

                return collect([
                    'id' => $view->created_at->format('m-y-').
                            $view->season_uuid,
                    'season_uuid' => $view->season_uuid,
                    'total' => $views->count(),
                    'label' => $view->created_at->format('m/y'),
                ]);
            });
    }

    /**
     * Get the season's poster URL.
     *
     * @return string
     */
    public function getPosterUrlAttribute()
    {
        return $this->poster_path ?
            asset(
                '/images/seasons'.$this->poster_path.
                '?w='.config('airflix.tmdb.size.seasons', 320) 
            ) : null;
    }
}
