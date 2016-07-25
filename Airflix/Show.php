<?php

namespace Airflix;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use Filterable, Uuid;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'first_air_date',
        'last_air_date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tmdb_show_id',
        'original_name',
        'folder_name',
        'folder_path',
        'overview',
        'homepage',
        'number_of_episodes',
        'number_of_seasons',
        'popularity',
        'first_air_date',
        'last_air_date',
        'imdb_id',
        'tvdb_id',
        'tvrage_id',
    ];

    /**
     * Get the episodes for the tv show.
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
     * Get the genres for the tv show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class)
            ->orderBy('name', 'ASC');
    }

    /**
     * Get the seasons for the tv show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function seasons()
    {
        return $this->hasMany(Season::class)
            ->has('episodes')
            ->where('air_date', '<=', Carbon::now())
            ->orderBy('season_number', 'ASC');
    }

    /**
     * Get the views for the tv show.
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
            ->where('created_at', '>=', $lastYear);
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
                            $view->show_uuid,
                    'show_uuid' => $view->show_uuid,
                    'total' => $views->count(),
                    'label' => $view->created_at->format('m/y'),
                ]);
            });
    }

    /**
     * Get the tv show's backdrop URL.
     *
     * @return string
     */
    public function getBackdropUrlAttribute()
    {
        return $this->backdrop_path ?
            asset(
                'images/backdrops'.$this->backdrop_path.
                '?w='.config('airflix.tmdb.size.backdrops', 1024) 
            ) : asset('images/no-backdrop.png');
    }

    /**
     * Get the tv show's poster URL.
     *
     * @return string
     */
    public function getPosterUrlAttribute()
    {
        return $this->poster_path ?
            asset(
                'images/posters'.$this->poster_path.
                '?w='.config('airflix.tmdb.size.posters', 1024) 
            ) : asset('images/no-poster.png');
    }
}
