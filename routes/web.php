<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Glide images
Route::get('images/{path}', [
    'as' => 'download.images',
    'uses' => 'ImageDownloadController@show'
])->where('path', '.+');

// Episode Downloads
Route::get('downloads/episodes/{id}', [
    'as' => 'download.episodes',
    'uses' => 'EpisodeDownloadController@show'
]);

// Movie Downloads
Route::get('downloads/movies/{id}', [
    'as' => 'download.movies',
    'uses' => 'MovieDownloadController@show'
]);

// Load the SPA from any route that does not start with __
Route::get('{path?}', [
    'as' => 'home',
    'uses' => 'HomeController@index'
])->where('path', '^(?!__).+');
