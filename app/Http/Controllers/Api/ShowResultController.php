<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Airflix\Contracts\Shows;
use Airflix\Contracts\ShowResults;

class ShowResultController extends ApiController
{
    /**
     * Inject the tv shows resource.
     *
     * @return \Airflix\Contracts\Shows
     */
    protected function shows()
    {
        return app(Shows::class);
    }

    /**
     * Inject the tv show results resource.
     *
     * @return \Airflix\Contracts\ShowResults
     */
    protected function showResults()
    {
        return app(ShowResults::class);
    }

    /**
     * Get a paginated set of tv show results from the tmdb API.
     *
     * @param  string $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $show = $this->shows()
            ->get($id);

        $currentPage = $request->query('page', 1);

        $url = $request->url();

        $results = $this->showResults()
            ->get($show, $currentPage, $url);

        $transformer = $this->showResults()
            ->transformer();

        return $this->apiResponse()
            ->respondWithPaginator(
                $results, 
                $transformer
            );
    }
}
