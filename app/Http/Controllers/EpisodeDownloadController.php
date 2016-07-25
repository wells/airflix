<?php

namespace App\Http\Controllers;

use Airflix\Contracts\Episodes;
use Airflix\Contracts\EpisodeViews;

class EpisodeDownloadController extends Controller
{
    /**
     * Inject the episodes resource.
     *
     * @return \Airflix\Contracts\Episodes
     */
    protected function episodes()
    {
        return app()->make(Episodes::class);
    }

    /**
     * Inject the episode views resource.
     *
     * @return \Airflix\Contracts\EpisodeViews
     */
    protected function views()
    {
        return app()->make(EpisodeViews::class);
    }

    /**
     * Get an episode file stream and mark as watched.
     *
     * @param  string $id
     *
     * @return \Illuminate\Http\RedirectResponse 
     */
    public function show($id)
    {
        $relationships = ['show',];

        $episode = $this->episodes()
            ->get($id, $relationships);

        if (! $episode->has_file) {
            return app()->abort(404);
        }

        $this->views()
            ->watch($episode);
        
        return redirect($episode->file_path);
    }
}
