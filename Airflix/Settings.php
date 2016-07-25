<?php

namespace Airflix;

use Storage;

class Settings implements Contracts\Settings
{
    protected $moviesPath;
    protected $showsPath;

    /**
     * Create a new Settings instance.
     */
    public function __construct()
    {
        $this->moviesPath = public_path('downloads/movies');
        $this->showsPath = public_path('downloads/episodes');
    }

    /**
     * Inject the episodes resource.
     *
     * @return \Airflix\Contracts\Episodes
     */
    public function episodes()
    {
        return app()->make(
            Contracts\Episodes::class
        );
    }

    /**
     * Inject the episode views resource.
     *
     * @return \Airflix\Contracts\EpisodeViews
     */
    public function episodeViews()
    {
        return app()->make(
            Contracts\EpisodeViews::class
        );
    }

    /**
     * Inject the movies resource.
     *
     * @return \Airflix\Contracts\Movies
     */
    public function movies()
    {
        return app()->make(
            Contracts\Movies::class
        );
    }

    /**
     * Inject the movie views resource.
     *
     * @return \Airflix\Contracts\Movies
     */
    public function movieViews()
    {
        return app()->make(
            Contracts\MovieViews::class
        );
    }

    /**
     * Inject the seasons resource.
     *
     * @return \Airflix\Contracts\Seasons
     */
    public function seasons()
    {
        return app()->make(
            Contracts\Seasons::class
        );
    }

    /**
     * Inject the shows resource.
     *
     * @return \Airflix\Contracts\Shows
     */
    public function shows()
    {
        return app()->make(
            Contracts\Shows::class
        );
    }

    /**
     * Get the settings.
     *
     * @return array
     */
    public function get()
    {
        return [
            'data' => [
                'id' => 0,
                'type' => 'settings',
                'attributes' => [
                    'movies_folder' => $this->getMoviesFolderPath(),
                    'shows_folder' => $this->getShowsFolderPath(),
                ],
            ],
        ];
    }

    /**
     * Get the movies folder path stored on the symlink.
     *
     * @return string
     */
    public function getMoviesFolderPath()
    {
        return is_link($this->moviesPath) ? readlink($this->moviesPath) : null;
    }

    /**
     * Get the shows folder path stored on the symlink.
     *
     * @return string
     */
    public function getShowsFolderPath()
    {
        return is_link($this->showsPath) ? readlink($this->showsPath) : null;
    }

    /**
     * Set the symlink for movies if different.
     *
     * @param  string $folderPath
     * 
     * @return bool
     */
    public function setMoviesFolderPath($folderPath)
    {
        $pathChanged = $this->setFolderPath($folderPath, $this->moviesPath);

        if ($pathChanged) {
            $this->movieViews()
                ->unlink();
            $this->movies()
                ->truncate();
        }

        return $pathChanged;
    }

    /**
     * Set the symlink for shows if different.
     *
     * @param  string $folderPath
     * 
     * @return bool
     */
    public function setShowsFolderPath($folderPath)
    {
        $pathChanged = $this->setFolderPath($folderPath, $this->showsPath);

        if ($pathChanged) {
            $this->episodeViews()
                ->unlink();
            $this->episodes()
                ->truncate();
            $this->seasons()
                ->truncate();
            $this->shows()
                ->truncate();
        }

        return $pathChanged;
    }

    /**
     * Set the symlink for a folder path if different.
     *
     * @param  string $folderPath
     * @param  string $publicPath
     * 
     * @return bool
     */
    protected function setFolderPath($folderPath, $publicPath)
    {
        if ($this->isSamePath($folderPath, $publicPath)) {
            return false;
        }

        if (is_link($publicPath)) {
            unlink($publicPath);
        }

        return symlink($folderPath, $publicPath);
    }

    /**
     * Determine if a folder path is the same as the symlink path.
     *
     * @param  string $folderPath
     * @param  string $publicPath
     *
     * @return bool
     */
    protected function isSamePath($folderPath, $publicPath)
    {
        return is_link($publicPath) &&
            trim($folderPath, '\\/') === trim(readlink($publicPath), '\\/');
    }
}
