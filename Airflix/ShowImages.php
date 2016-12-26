<?php

namespace Airflix;

use Tmdb;

class ShowImages implements Contracts\ShowImages
{
    use Retriable;

    /**
     * Inject the tmdb image transformer.
     *
     * @return \Airflix\Contracts\TmdbImageTransformer
     */
    public function transformer()
    {
        return app(
            Contracts\TmdbImageTransformer::class
        );
    }

    /**
     * Get tmdb show images.
     *
     * @param  \Airflix\Show $show
     *
     * @return array
     */
    protected function get($show)
    {
        return $this->retry(3, 
            function () use ($show) {
                return Tmdb::getTvApi()
                    ->getImages($show->tmdb_show_id, [
                        'include_image_language' => 
                            config('airflix.tmdb.languages'),
                    ]);
            }, function () { 
                sleep(config('airflix.tmdb.throttle_seconds')); 
            });
    }

    /**
     * Get tmdb show backdrops.
     *
     * @param  \Airflix\Show $show
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBackdrops($show)
    {
        if ($show->tmdb_show_id == 0) {
            return [];
        }

        $images = $this->get($show);

        // Remove any duplicates with the keyBy collection method
        return collect($images['backdrops'])
            ->keyBy('file_path')
            ->all();
    }

    /**
     * Get tmdb show posters.
     *
     * @param  \Airflix\Show $show
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPosters($show)
    {
        if ($show->tmdb_show_id == 0) {
            return [];
        }

        $images = $this->get($show);

        // Remove any duplicates with the keyBy collection method
        return collect($images['posters'])
            ->keyBy('file_path')
            ->all();
    }
}
