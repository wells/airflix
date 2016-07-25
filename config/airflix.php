<?php

return [

    'api' => [
        'url' => env('APP_URL').'/api',
        'versions' => [
            1.0,
        ],
    ],

    'extensions' => [
        'video' => env('AIRFLIX_EXTENSIONS_VIDEO', 'm4v'),
    ],

    'per_page' => 100,

    'tmdb' => [
        'images' => 'https://image.tmdb.org/t/p/original',
        'previews' => 'https://image.tmdb.org/t/p/w300',
        'languages' => 'en,null', // maximum of 5
        'per_page' => 20,
        'size' => [
            'backdrops' => 1080,
            'posters' => 480,
            'seasons' => 320,
            'episodes' => 480,
        ],
        'throttle_seconds' => 10,
    ],

    'urls' => [
        'imdb' => 'http://www.imdb.com/title/',
        'tmdb' => [
            'movie' => 'https://www.themoviedb.org/movie/',
            'show' => 'https://www.themoviedb.org/tv/',
        ],
        'tvdb' => 'http://thetvdb.com/?tab=series&id=',
    ],

];
