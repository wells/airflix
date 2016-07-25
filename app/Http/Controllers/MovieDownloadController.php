<?php

namespace App\Http\Controllers;

use Airflix\Contracts\Movies;
use Airflix\Contracts\MovieViews;

class MovieDownloadController extends Controller
{
    /**
     * Inject the movies resource.
     *
     * @return \Airflix\Contracts\Movies
     */
    protected function movies()
    {
        return app()->make(Movies::class);
    }

    /**
     * Inject the movie views resource.
     *
     * @return \Airflix\Contracts\MovieViews
     */
    protected function views()
    {
        return app()->make(MovieViews::class);
    }

    /**
     * Get a movie file stream and mark as watched.
     *
     * @param  string $id
     *
     * @return \Illuminate\Http\RedirectResponse 
     */
    public function show($id)
    {
        $movie = $this->movies()
            ->get($id);

        if (! $movie->has_file) {
            return app()->abort(404);
        }

        $this->views()
            ->watch($movie);
        
        return redirect($movie->file_path);
    }
}
