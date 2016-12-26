<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Airflix\Contracts\Seasons;

class SeasonController extends ApiController
{
    /**
     * Inject the seasons resource.
     *
     * @return \Airflix\Contracts\Seasons
     */
    protected function seasons()
    {
        return app(Seasons::class);
    }

    /**
     * Get a season.
     *
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relationships = [
            'episodes', 
            'show', 
            'show.genres',
            'views',
        ];

        $season = $this->seasons()
            ->get($id, $relationships);

        $transformer = $this->seasons()
            ->transformer();

        $this->apiResponse()
            ->fractal()
            ->parseIncludes($relationships);
        
        return $this->apiResponse()
            ->respondWithItem(
                $season, 
                $transformer
            );
    }
}
