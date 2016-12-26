<?php

namespace App\Console\Commands;

use Airflix\Contracts\Settings;
use Illuminate\Console\Command;

class SetFolderPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airflix:folders 
        {--movies= : Folder path to your movies} 
        {--shows= : Folder path to your shows} 
        {--refresh : Refresh after changing paths}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the Airflix folder paths';

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
        $moviesPath = $this->option('movies');
        $showsPath = $this->option('shows');
        $refresh = $this->option('refresh');
        $pathChanged = false;

        if($moviesPath && is_dir($moviesPath)) {
            $moviesPathChanged = $this->settings()
                ->setMoviesFolderPath($moviesPath);

            $this->line($moviesPathChanged ?
                '<info>Updated:</info> '.
                'Movies path ['.$moviesPath.'] set successfully.' :
                '<comment>No Change:</comment> '.
                'Movies path ['.$moviesPath.'] already set.'
            );

            $pathChanged = $pathChanged || $moviesPathChanged;
        }

        if($showsPath && is_dir($showsPath)) {
            $showsPathChanged = $this->settings()
                ->setShowsFolderPath($showsPath);

            $this->line($showsPathChanged ?
                '<info>Updated:</info> '.
                'Shows path ['.$showsPath.'] set successfully.' :
                '<comment>No Change:</comment> '.
                'Shows path ['.$showsPath.'] already set.'
            ); 

            $pathChanged = $pathChanged || $showsPathChanged;
        }

        if(!$moviesPath && !$showsPath) {
            $this->line(
                '<error>Error:</error> '.
                'Please provide at least one path option (--movies or --shows).'
            );
            return 1;
        }

        if($refresh) {
            $this->call('airflix:genres');
            $this->call('airflix:movies');
            $this->call('airflix:shows');
        }

        if($pathChanged) {
            return 255;
        }
    }

    /**
     * Inject the settings resource.
     *
     * @return \Airflix\Contracts\Settings
     */
    protected function settings() {
        return app(Settings::class);
    }
}
