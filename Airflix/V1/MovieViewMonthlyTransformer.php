<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class MovieViewMonthlyTransformer
    extends Fractal\TransformerAbstract
    implements Contracts\MovieViewMonthlyTransformer
{
    public $resourceType = 'movie-views';

    public $resourceVersion = 1.0;

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\MovieView $view
     *
     * @return array
     */
    public function transform($view)
    {
        return [
            'id' => $view->get('id'),
            'movie_id' => $view->get('movie_uuid'),
            'total' => $view->get('total'),
            'label' => $view->get('label'),
        ];
    }
}
