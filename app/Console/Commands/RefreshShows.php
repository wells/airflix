<?php

namespace App\Console\Commands;

use Airflix\Contracts\Genres;
use Airflix\Contracts\Shows;
use Illuminate\Console\Command;

class RefreshShows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airflix:shows 
        {--new : Only refresh new folders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the tv shows table from themoviedb.org';

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

        $totalShows = $this->shows()
            ->refreshShows($onlyNewFolders, $this->output);

        $this->line(
            '<info>Refreshed:</info> '.
            $totalShows.' tv shows '.
            'loaded from themoviedb.org'
        );

        $totalGenres = $this->genres()
            ->refreshTotalShows();

        $this->line(
            '<info>Updated:</info> '.
            $totalGenres.' genres '.
            'with show totals'
        );
    }

    /**
     * Inject the genres resource.
     *
     * @return \Airflix\Contracts\Genres
     */
    protected function genres() {
        return app(Genres::class);
    }

    /**
     * Inject the shows resource.
     *
     * @return \Airflix\Contracts\Shows
     */
    protected function shows() {
        return app(Shows::class);
    }
}
