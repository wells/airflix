<?php

namespace App\Console\Commands;

use Airflix\Contracts\Genres;
use Airflix\Contracts\Movies;
use Illuminate\Console\Command;

class RefreshMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airflix:movies 
        {--new : Only refresh new folders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the movies table from themoviedb.org';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $onlyNewFolders = $this->option('new');

        $totalMovies = $this->movies()
            ->refreshMovies($onlyNewFolders, $this->output);

        $this->line(
            '<info>Refreshed:</info> '.
            $totalMovies.' movies '.
            'loaded from themoviedb.org'
        );

        $totalGenres = $this->genres()
            ->refreshTotalMovies();

        $this->line(
            '<info>Updated:</info> '.
            $totalGenres.' genres '.
            'with movie totals'
        );
    }

    /**
     * Inject the genres resource.
     *
     * @return \Airflix\Contracts\Genres
     */
    protected function genres() {
        return app()->make(Genres::class);
    }

    /**
     * Inject the movies resource.
     *
     * @return \Airflix\Contracts\Movies
     */
    protected function movies() {
        return app()->make(Movies::class);
    }
}
