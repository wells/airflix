<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class TmdbMovieResultTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\TmdbMovieResultTransformer
{
    public $resourceType = 'movie-results';

    public $resourceVersion = 1.0;

    /**
     * Turn this item object into a generic array
     *
     * @param  array $result
     *
     * @return array
     */
    public function transform($result)
    {
        return [
            'id' => $result['id'],
            'title' => $result['title'],
            'original_title' => $result['original_title'],
            'poster_path' => $result['poster_path'],
            'poster_url' => $result['poster_path'] ?
                config('airflix.tmdb.previews').$result['poster_path'] :
                asset('images/no-poster.png'),
            'release_date' => $result['release_date'],
        ];
    }
}
