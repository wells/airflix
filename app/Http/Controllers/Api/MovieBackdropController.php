<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Airflix\Contracts\Movies;
use Airflix\Contracts\MovieImages;

class MovieBackdropController extends ApiController
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
     * Inject the movie images resource.
     *
     * @return \Airflix\Contracts\MovieImages
     */
    protected function movieImages()
    {
        return app()->make(MovieImages::class);
    }

    /**
     * Get a collection of movie backdrops from the tmdb API.
     *
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = $this->movies()
            ->get($id);

        $backdrops = $this->movieImages()
            ->getBackdrops($movie);

        $transformer = $this->movieImages()
            ->transformer();

        return $this->apiResponse()
            ->respondWithCollection(
                $backdrops, 
                $transformer
            );
    }
}
