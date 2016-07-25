<?php

// API Routes
Route::group([
    'middleware' => ['api'], 
    'prefix' => 'api', 
    'namespace' => 'Api',
], function () {

    // Genres
    Route::get('genres', [
        'as' => 'genres',
        'uses' => 'GenreController@index'
    ]);

    // Movies
    Route::get('movies', [
        'as' => 'movies',
        'uses' => 'MovieController@index'
    ]);
    Route::get('movies/{id}', [
        'as' => 'movies.show',
        'uses' => 'MovieController@show'
    ]);
    Route::patch('movies/{id}', [
        'uses' => 'MovieController@patch'
    ]);

    // Movie Backdrops (from tmdb)
    Route::get('movies/{id}/backdrops', [
        'as' => 'movies.backdrops',
        'uses' => 'MovieBackdropController@show'
    ]);

    // Movie Posters (from tmdb)
    Route::get('movies/{id}/posters', [
        'as' => 'movies.posters',
        'uses' => 'MoviePosterController@show'
    ]);

    // Movie Results (from tmdb)
    Route::get('movies/{id}/results', [
        'as' => 'movies.results',
        'uses' => 'MovieResultController@show'
    ]);

    // Settings
    Route::get('settings', [
        'as' => 'settings',
        'uses' => 'Settings\SettingsController@index'
    ]);
    Route::patch('settings', [
        'as' => 'settings.patch',
        'uses' => 'Settings\SettingsController@patch'
    ]);
    Route::delete('settings/history', [
        'as' => 'settings.history.delete',
        'uses' => 'Settings\HistoryController@delete'
    ]);
    Route::patch('settings/folders', [
        'as' => 'settings.folders.patch',
        'uses' => 'Settings\FoldersController@patch'
    ]);

    // Shows
    Route::get('shows', [
        'as' => 'shows',
        'uses' => 'ShowController@index'
    ]);
    Route::get('shows/{id}', [
        'as' => 'shows',
        'uses' => 'ShowController@show'
    ]);
    Route::patch('shows/{id}', [
        'uses' => 'ShowController@patch'
    ]);

    // Show Backdrops
    Route::get('shows/{id}/backdrops', [
        'as' => 'shows.backdrops',
        'uses' => 'ShowBackdropController@show'
    ]);

    // Show Posters
    Route::get('shows/{id}/posters', [
        'as' => 'shows.posters',
        'uses' => 'ShowPosterController@show'
    ]);

    // Show Results
    Route::get('shows/{id}/results', [
        'as' => 'shows.results',
        'uses' => 'ShowResultController@show'
    ]);

    // Show Season
    Route::get('seasons/{id}', [
        'as' => 'seasons',
        'uses' => 'SeasonController@show'
    ]);

    // Show Episode
    Route::get('episodes/{id}', [
        'as' => 'episodes',
        'uses' => 'EpisodeController@show'
    ]);

});

// Web Routes
Route::group([
    'middleware' => ['web'],
], function () {

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

    // Load the SPA
    Route::get('{path?}', [
        'as' => 'home',
        'uses' => 'HomeController@index'
    ])->where('path', '.+');

});
