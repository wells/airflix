<?php

namespace Airflix;

use GuzzleHttp\Client;
use Storage;

class TmdbImageClient implements Contracts\TmdbImageClient
{
    /**
     * Stores the HTTP Client
     *
     * @var GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Create a new TmdbImageClient instance.
     */
    public function __construct()
    {
        $this->httpClient = new Client([
            'timeout'  => 2.0,
        ]);
    }

    /**
     * Download an image from the tmdb API.
     *
     * @param  string $fileName
     * @param  string $folder
     *
     * @return bool
     */
    public function download($fileName, $folder)
    {
        $filePath = 'images/'.$folder.$fileName;

        $storage = Storage::disk('app');

        // The image was already downloaded
        if (!$fileName || $storage->exists($filePath)) {
            return false;
        }

        $url = config('airflix.tmdb.images').$fileName;
        
        $response = $this->httpClient
            ->get($url);

        $storage->put($filePath, $response->getBody());

        return true;
    }
}
