<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class TmdbShowResultTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\TmdbShowResultTransformer
{
    public $resourceType = 'show-results';

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
            'name' => $result['name'],
            'original_name' => $result['original_name'],
            'poster_path' => $result['poster_path'],
            'poster_url' => $result['poster_path'] ?
                config('airflix.tmdb.previews').$result['poster_path'] :
                asset('images/no-poster.png'),
            'first_air_date' => $result['first_air_date'],
        ];
    }
}
