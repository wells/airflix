<?php

namespace Airflix\V1;

use League\Fractal;
use Airflix\Contracts;

class EpisodeViewMonthlyTransformer 
    extends Fractal\TransformerAbstract
    implements Contracts\EpisodeViewMonthlyTransformer
{
    public $resourceType = 'episode-views';

    public $resourceVersion = 1.0;

    /**
     * Turn this item object into a generic array
     *
     * @param  \Airflix\EpisodeView $view
     *
     * @return array
     */
    public function transform($view)
    {
        return [
            'id' => $view->get('id'),
            'episode_id' => $view->get('episode_uuid'),
            'total' => $view->get('total'),
            'label' => $view->get('label'),
        ];
    }
}
