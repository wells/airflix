<?php

namespace App\Console\Commands;

use Airflix\Contracts\Settings;
use Illuminate\Console\Command;

class SetAPIKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airflix:keys 
        {--tmdb= : API key for themoviedb.org}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the API keys for Airflix';

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
        $tmdbApiKey = $this->option('tmdb');

        if (!$tmdbApiKey && config('tmdb.api_key') == 'ApplyForAnApiKey') {
            $tmdbApiKey = $this->ask(
                'What is your themoviedb.org API key?'
            );
        }

        if($tmdbApiKey) {
            $this->setKeyInEnvironmentFile(
                $tmdbApiKey, 
                $this->laravel['config']['tmdb.api_key'], 
                'TMDB_API_KEY'
            );

            $this->laravel['config']['tmdb.api_key'] = $tmdbApiKey;

            $this->info('TMDB API key ['.$tmdbApiKey.'] set successfully.');
        }
    }
    
    /**
     * Set a variable key in the environment file.
     *
     * @param  string $new
     * @param  string $current
     * @param  string $envKey
     */
    protected function setKeyInEnvironmentFile($new, $current, $envKey) {
        file_put_contents(
            $this->laravel->environmentFilePath(), 
            str_replace(
                $envKey.'='.$current,
                $envKey.'='.$new,
                file_get_contents($this->laravel->environmentFilePath())
            )
        );
    }
}
