<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Glide\Server;

class ImageDownloadController extends Controller
{
    /**
     * Get an image.
     *
     * @param  string $path
     * @param  \League\Glide\Server $server
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response 
     */
    public function show($path, Server $server, Request $request)
    {
        return $server->getImageResponse($path, $request->all());
    }
}
