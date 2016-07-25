<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class ShowViewMonthlyTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\ShowViewMonthlyTransformer
{
    public $resourceType = 'show-views';

    public $resourceVersion = 1.0;

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\ShowView $view
     *
     * @return array
     */
    public function transform($view)
    {
        return [
            'id' => $view->get('id'),
            'show_id' => $view->get('show_uuid'),
            'total' => $view->get('total'),
            'label' => $view->get('label'),
        ];
    }
}
