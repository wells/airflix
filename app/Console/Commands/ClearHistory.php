<?php

namespace App\Console\Commands;

use Airflix\Contracts\EpisodeViews;
use Airflix\Contracts\MovieViews;
use Illuminate\Console\Command;

class ClearHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airflix:history 
        {--today : Clear today\'s history}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the movie_views and episode_views tables';

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
        $clearToday = $this->option('today');

        $this->movieViews()
            ->clearHistory($clearToday);

        $this->episodeViews()
            ->clearHistory($clearToday);

        $this->line('');

        $this->line(
            '<info>History:</info> '.
            ($clearToday ? 'Cleared today\'s history' : 'Cleared all history')
        );
    }

    /**
     * Inject the movie views resource.
     *
     * @return \Airflix\Contracts\MovieViews
     */
    protected function movieViews() {
        return app(MovieViews::class);
    }

    /**
     * Inject the episode views resource.
     *
     * @return \Airflix\Contracts\EpisodeViews
     */
    protected function episodeViews() {
        return app(EpisodeViews::class);
    }
}
