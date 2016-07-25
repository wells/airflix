<?php

namespace App\Console\Commands;

use Airflix\Contracts\Genres;
use Illuminate\Console\Command;

class RefreshGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airflix:genres';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the genres table from themoviedb.org';

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
        $totalGenres = $this->genres()
            ->refreshGenres($this->output);
        
        $this->line(
            '<info>Refreshed:</info> '.
            $totalGenres.' genres '.
            'loaded from themoviedb.org'
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
}
