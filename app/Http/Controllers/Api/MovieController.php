<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Airflix\Contracts\Movies;

class MovieController extends ApiController
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
     * Get a set of movies.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $relationships = ['genres',];

        $movies = $this->movies()
            ->index($relationships);

        $transformer = $this->movies()
            ->transformer();

        $this->apiResponse()
            ->fractal()
            ->parseIncludes($relationships);

        return $this->apiResponse()
            ->respondWithPaginator(
                $movies,
                $transformer
            );
    }

    /**
     * Get a movie.
     *
     * @param  string  $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $relationships = ['genres', 'views',];

        $includes = (array) array_filter(
            explode(',', $request->input('include')), 'strlen'
        );
        $includes = array_merge($includes, $relationships);

        $movie = $this->movies()
            ->get($id, $relationships);

        $transformer = $this->movies()
            ->transformer();

        $this->apiResponse()
            ->fractal()
            ->parseIncludes($includes);
        
        return $this->apiResponse()
            ->respondWithItem(
                $movie,
                $transformer
            );
    }

    /**
     * Patch a movie.
     *
     * @param  string  $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function patch($id, Request $request)
    {
        $this->validate($request, [
            'data.attributes.tmdb_movie_id' => 'sometimes|integer|not_in:0',
            'data.attributes.title' => 'sometimes|max:255',
            'data.attributes.poster_path' => 'sometimes|max:255',
            'data.attributes.backdrop_path' => 'sometimes|max:255',
        ]);

        $input = $request->input();
        $type = 'movies';

        // 403 - Unsupported request format
        if (! $this->validRequestStructure($input)) {
            return $this->apiResponse()
                ->errorForbidden(
                    'Unsupported request format (requires JSON-API).'
                );
        }

        // 409 - Incorrect id or type
        if (! $this->validRequestData($input, $id, $type)) {
            return $this->apiResponse()
                ->errorConflict(
                    'Bad request data (verify id and type).'
                );
        }

        $attributes = $request->input('data.attributes');

        $movie = $this->movies()
            ->patch($id, $attributes);

        $transformer = $this->movies()
            ->transformer();
        
        return $this->apiResponse()
            ->setStatusCode(201)
            ->respondWithItem(
                $movie,
                $transformer
            );
    }
}
