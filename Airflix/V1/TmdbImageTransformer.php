<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class TmdbImageTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\TmdbImageTransformer
{
    public $resourceType = 'images';

    public $resourceVersion = 1.0;

    /**
     * Turn this item object into a generic array
     *
     * @param  array $image
     *
     * @return array
     */
    public function transform($image)
    {
        return [
            'id' => trim($image['file_path'], '/'),
            'file_path' => $image['file_path'],
            'file_url' => config('airflix.tmdb.previews').
                $image['file_path'],
            'iso_639_1' => $image['iso_639_1'],
            'aspect_ratio' => (double) $image['aspect_ratio'],
            'width' => (int) $image['width'],
            'height' => (int) $image['height'],
            'vote_average' => (double) $image['vote_average'],
            'vote_count' => (int) $image['vote_count'],
        ];
    }
}
