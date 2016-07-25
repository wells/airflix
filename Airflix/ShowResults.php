<?php

namespace Airflix;

use Illuminate\Pagination\LengthAwarePaginator;
use Tmdb;

class ShowResults implements Contracts\ShowResults
{
    use Retriable;

    /**
     * Inject the tmdb show result transformer.
     *
     * @return \Airflix\Contracts\TmdbShowResultTransformer
     */
    public function transformer()
    {
        return app()->make(
            Contracts\TmdbShowResultTransformer::class
        );
    }

    /**
     * Get paginated tmdb show results.
     *
     * @param  \Airflix\Show $show
     * @param  integer $currentPage
     * @param  string $url
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function get($show, $currentPage = 1, $url = '')
    {
        $perPage = config('airflix.tmdb.per_page', 20);

        $search = $this->retry(3, 
            function () use ($show, $currentPage) {
                return Tmdb::getSearchApi()
                    ->searchTv($show->folder_name, [
                        'page' => $currentPage,
                    ]);
            }, function () { 
                sleep(config('airflix.tmdb.throttle_seconds')); 
            });
        
        $items = $search['results'];
        $total = $search['total_results'];

        $paginator = new LengthAwarePaginator(
            $items, $total, $perPage, $currentPage
        );
        $paginator->setPath($url);

        return $paginator;
    }
}
