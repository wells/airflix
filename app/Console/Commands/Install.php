<?php

namespace App\Console\Commands;

use Airflix\Contracts\Settings;
use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airflix:install 
        {--movies= : Folder path to your movies} 
        {--shows= : Folder path to your shows} 
        {--tmdb= : API key for themoviedb.org}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Airflix';

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
        $this->info("     _    ___ ____  _____ _     _____  __ ");
        $this->info("    / \  |_ _|  _ \|  ___| |   |_ _\ \/ / ");
        $this->info("   / _ \  | || |_) | |_  | |    | | \  /  ");
        $this->info("  / ___ \ | ||  _ <|  _| | |___ | | /  \  ");
        $this->info(" /_/   \_\___|_| \_\_|   |_____|___/_/\_\ ");

        $this->line('');

        if ($this->confirm('Do you wish to install a fresh database?')) {
            $this->call('migrate:refresh');
        }

        $this->line('');

        $moviesPath = $this->option('movies');

        if (!$moviesPath && !is_link(public_path('downloads/movies'))) {
            $moviesPath = $this->ask(
                'What is the folder path to your movies?'
            );
        }

        if ($moviesPath) {
            $this->call('airflix:folders', [
                '--movies' => $moviesPath,
                '--refresh' => false
            ]);
        }
        
        $showsPath = $this->option('shows');

        if (!$showsPath && !is_link(public_path('downloads/episodes'))) {
            $showsPath = $this->ask(
                'What is the folder path to your TV Shows?'
            );
        }

        if ($showsPath) {
            $this->call('airflix:folders', [
                '--shows' => $showsPath,
                '--refresh' => false
            ]);
        }

        if ($showsPath || $moviesPath) {
            $this->line('');
        }

        $tmdbApiKey = $this->option('tmdb');

        if (!$tmdbApiKey && config('tmdb.api_key') == 'ApplyForAnApiKey') {
            $tmdbApiKey = $this->ask(
                'What is your themoviedb.org API key?'
            );
        }

        if ($tmdbApiKey) {
            $this->call('airflix:keys', [
                '--tmdb' => $tmdbApiKey,
            ]);
            $this->line('');
        }

        if (config('tmdb.api_key') != 'ApplyForAnApiKey') {
            $this->call('airflix:genres');
            $this->call('airflix:movies');
            $this->call('airflix:shows');
        }

        $this->line('');

        $this->line(
            '<info>Success:</info> '.
            'Airflix has been setup'
        );
    }
}
