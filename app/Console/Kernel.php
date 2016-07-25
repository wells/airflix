<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ClearHistory::class,
        Commands\Install::class,
        Commands\RefreshGenres::class,
        Commands\RefreshMovies::class,
        Commands\RefreshShows::class,
        Commands\SetAPIKeys::class,
        Commands\SetFolderPaths::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tmdb:genres')
                 ->weekly();
        $schedule->command('tmdb:movies')
                 ->weekly();
        $schedule->command('tmdb:shows')
                 ->weekly();
    }
}
