<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootCarbonSerialization();
        $this->bootHorizonAuth();
    }

    /**
     * Set Carbon JSON serialization to ISO-8601 format (aka ATOM)
     *
     * @return void
     */
    protected function bootCarbonSerialization()
    {
        // DateTime::ATOM is the actual compatible format with ISO-8601 in php
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format(DateTime::ATOM);
        });
    }

    /**
     * Enable Horizon dashboard outside of local environment
     *
     * @return void
     */
    protected function bootHorizonAuth()
    {
        Horizon::auth(function ($request) {
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind Glide server as a singleton
        $this->app->singleton('League\Glide\Server',
            function ($app) {
                $fileSystem = \Storage::disk('app');

                return ServerFactory::create([
                    'source' => $fileSystem->getDriver(),
                    'cache' => $fileSystem->getDriver(),
                    'source_path_prefix' => 'images',
                    'cache_path_prefix' => 'images/.cache',
                    'base_url' => 'images',
                    'response' => new LaravelResponseFactory(),
                ]);
            });

        $namespace = 'Airflix\\';

        // Bind resources to their interfaces
        $resources = collect([
            'ApiResponse',
            'EpisodeViews',
            'Episodes',
            'Genres',
            'MovieImages',
            'MovieResults',
            'MovieViews',
            'Movies',
            'Seasons',
            'Settings',
            'ShowImages',
            'ShowResults',
            'Shows',
            'TmdbImageClient',
        ]);

        $resources->each(function($resource) use ($namespace) {
            $abstract = $namespace.'Contracts\\'.$resource;
            $concrete = $namespace.$resource;

            $this->app
                ->singleton($abstract, $concrete);
        });

        // Bind versioned API transformers to their interfaces
        $transformers = collect([
            'EpisodeTransformer',
            'EpisodeViewMonthlyTransformer',
            'GenreTransformer',
            'MovieTransformer',
            'MovieViewMonthlyTransformer',
            'SeasonTransformer',
            'SeasonViewMonthlyTransformer',
            'ShowTransformer',
            'ShowViewMonthlyTransformer',
            'TmdbImageTransformer',
            'TmdbMovieResultTransformer',
            'TmdbShowResultTransformer',
        ]);

        $transformers->each(function($resource) use ($namespace) {
            $abstract = $namespace.'Contracts\\'.$resource;
            $concrete = $this->mapVersion($namespace, $resource);

            $this->app
                ->singleton($abstract, $concrete);
        });
    }

    /**
     * Generate a versioned concrete resource.
     *
     * @param  string $namespace
     * @param  string $resource
     *
     * @return string
     */
    protected function mapVersion($namespace, $resource)
    {
        // Retrieve the Accept header from the Request
        $mimeType = request()->server('HTTP_ACCEPT', '*/*');

        // Try to load the requested version, if provided
        $version = $this->parseMimeType($mimeType);

        $classPath = $version ?
            $this->formatPath($namespace, $version, $resource) : null;

        // Requested version exists for this resource
        if ($classPath && class_exists($classPath)) {
            return $classPath;
        }

        // Map the latest version of the resource
        $versions = (array) config('airflix.api.versions', [1.0, ]);

        // Reverse sort of the API versions
        rsort($versions);

        // Find the latest version of this resource
        foreach ($versions as $version) {
            // Convert to underscore format (i.e. V1, V1_1, etc)
            $version = $this->formatVersion($version);

            $classPath = $this->formatPath(
                $namespace, $version, $resource
            );

            // Return the latest existing version
            if (class_exists($classPath)) {
                break;
            }
        }

        return $classPath;
    }

    /**
     * Parse the provided mimetype for a requested API version.
     *
     * @param  string $mimeType
     *
     * @return string
     */
    protected function parseMimeType($mimeType)
    {
        // Break up the mime type by semicolon
        $mimeParts = collect(
            (array) array_filter(
                explode(';', $mimeType), 'strlen'
            )
        );

        return $mimeParts->reduce(function($carry, $part) {
            if (starts_with(trim($part), 'version=')) {
                // Remove version= from string
                $version = str_replace('version=', '', trim($part));

                // Convert to underscore format (i.e. V1, V1_1, etc)
                $version = $this->formatVersion($version);

                return $version;
            }

            return $carry;
        });
    }

    /**
     * Format a versioned namespace path.
     *
     * @param  string $namespace
     * @param  string $version
     * @param  string $resource
     *
     * @return string
     */
    protected function formatPath($namespace, $version, $resource) {
        return $namespace.'V'.$version.'\\'.$resource;
    }

    /**
     * Format a version number to a valid PHP namespace (i.e. 1_1)
     *
     * @param  mixed $version
     *
     * @return string
     */
    protected function formatVersion($version)
    {
        return str_replace('.', '_', (double) $version);
    }
}
