<?php

namespace Airflix;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Episode extends Model
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
        'tmdb_episode_id',
        'season_number',
        'episode_number',
        'name',
        'overview',
        'still_path',
        'air_date',
    ];

    /**
     * Get the tv show for the episode.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    /**
     * Get the season for the episode.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * Get the views for the episode.
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
                            $view->episode_uuid,
                    'episode_uuid' => $view->episode_uuid,
                    'total' => $views->count(),
                    'label' => $view->created_at->format('m/y'),
                ]);
            });
    }

    /**
     * Get the episode's file name.
     *
     * @return string
     */
    public function getFileNameAttribute()
    {
        return 'S'.sprintf('%02d', $this->season_number).
            'E'.sprintf('%02d', $this->episode_number).'.'.
            $this->getFileExtension();
    }

    /**
     * Get the episode's file path.
     *
     * @return string
     */
    public function getFilePathAttribute()
    {
        return '/downloads/episodes/'.
            $this->show->folder_name.'/'.
            $this->file_name;
    }

    /**
     * Get the episode's file path.
     *
     * @return bool
     */
    public function getHasFileAttribute()
    {
        return Storage::disk('public')
            ->exists($this->file_path);
    }

    /**
     * Get the movie's file extension.
     *
     * @return string
     */
    protected function getFileExtension()
    {
        $filePath = '/downloads/episodes/'.
                $this->folder_name.'/'.
                $this->folder_name.'.';

        foreach(config('airflix.extensions.video') as $extension)
        {
            if(Storage::disk('public')->exists($filePath.$extension)) {
                return $extension;
            }
        }

        return 'm4v';
    }

    /**
     * Get the episode's still path.
     *
     * @return string
     */
    public function getStillUrlAttribute()
    {
        return $this->still_path ?
            asset(
                'images/episodes'.$this->still_path.
                '?w='.config('airflix.tmdb.size.episodes', 480)
            ) : null;
    }
}
