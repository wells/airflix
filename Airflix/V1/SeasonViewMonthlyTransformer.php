<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class SeasonViewMonthlyTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\SeasonViewMonthlyTransformer
{
    public $resourceType = 'season-views';

    public $resourceVersion = 1.0;

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\SeasonView $view
     *
     * @return array
     */
    public function transform($view)
    {
        return [
            'id' => $view->get('id'),
            'season_id' => $view->get('season_uuid'),
            'total' => $view->get('total'),
            'label' => $view->get('label'),
        ];
    }
}
