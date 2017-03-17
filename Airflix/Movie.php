<?php

namespace Airflix;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Movie extends Model
{
    use Filterable, Uuid;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'release_date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tmdb_movie_id',
        'original_title',
        'folder_name',
        'folder_path',
        'overview',
        'homepage',
        'runtime',
        'popularity',
        'release_date',
        'budget',
        'revenue',
        'imdb_id',
    ];

    /**
     * Get the genres for the movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class)
            ->orderBy('name', 'ASC');
    }

    /**
     * Get the views for the movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function views()
    {
        $lastYear = Carbon::now()
            ->addMonth()
            ->startOfMonth()
            ->subYear();

        return $this->hasMany(MovieView::class)
            ->where('created_at', '>=', $lastYear);
    }

    /**
     * Get the monthly views for the movie.
     *
     * @return \Illuminate\Support\Collection
     */
    public function monthlyViews()
    {
        // Group and map views to a collection of totals
        return $this->views
            ->groupBy(function ($view) {
                return $view->created_at->format('m/y');
            })
            ->map(function ($views) {
                $view = $views->first();

                return collect([
                    'id' => $view->created_at->format('m-y-').
                            $view->movie_uuid,
                    'movie_uuid' => $view->movie_uuid,
                    'total' => $views->count(),
                    'label' => $view->created_at->format('m/y'),
                ]);
            });
    }

    /**
     * Get the movie's backdrop URL.
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
     * Get the movie's file name.
     *
     * @return string
     */
    public function getFileNameAttribute()
    {
        return $this->folder_name.'.'.
            $this->getFileExtension();
    }

    /**
     * Get the movie's file path.
     *
     * @return string
     */
    public function getFilePathAttribute()
    {
        return '/downloads/movies/'.
            $this->folder_name.'/'.
            $this->file_name;
    }

    /**
     * Get the movie's file path.
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
        $filePath = '/downloads/movies/'.
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
     * Get the movie's poster URl.
     *
     * @return string
     */
    public function getPosterUrlAttribute()
    {
        return $this->poster_path ?
            asset(
                'images/posters'.$this->poster_path.
                '?w='.config('airflix.tmdb.size.posters', 480)
            ) : asset('images/no-poster.png');
    }
}
