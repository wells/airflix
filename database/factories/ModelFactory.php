<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Airflix\Episode::class, function (Faker\Generator $faker) {
    return [
        'show_id' =>  factory(Airflix\Show::class)->create()->id ,
        'season_id' =>  factory(Airflix\Season::class)->create()->id ,
        'uuid' =>  $faker->word ,
        'show_uuid' =>  $faker->word ,
        'season_uuid' =>  $faker->word ,
        'tmdb_show_id' =>  $faker->randomNumber() ,
        'tmdb_season_id' =>  $faker->randomNumber() ,
        'tmdb_episode_id' =>  $faker->randomNumber() ,
        'season_number' =>  $faker->randomNumber() ,
        'episode_number' =>  $faker->randomNumber() ,
        'name' =>  $faker->name ,
        'overview' =>  $faker->text ,
        'still_path' =>  $faker->word ,
        'air_date' =>  $faker->dateTimeBetween() ,
        'total_views' =>  $faker->randomNumber() ,
    ];
});

$factory->define(Airflix\EpisodeView::class, function (Faker\Generator $faker) {
    return [
        'show_id' =>  $faker->randomNumber() ,
        'season_id' =>  $faker->randomNumber() ,
        'episode_id' =>  $faker->randomNumber() ,
        'uuid' =>  $faker->word ,
        'show_uuid' =>  $faker->word ,
        'season_uuid' =>  $faker->word ,
        'episode_uuid' =>  $faker->word ,
        'tmdb_show_id' =>  $faker->randomNumber() ,
    ];
});

$factory->define(Airflix\Genre::class, function (Faker\Generator $faker) {
    return [
        'uuid' =>  $faker->word ,
        'tmdb_genre_id' =>  $faker->randomNumber() ,
        'name' =>  $faker->name ,
    ];
});

$factory->define(Airflix\Movie::class, function (Faker\Generator $faker) {
    return [
        'uuid' =>  $faker->word ,
        'tmdb_movie_id' =>  $faker->randomNumber() ,
        'title' =>  $faker->word ,
        'original_title' =>  $faker->word ,
        'folder_name' =>  $faker->word ,
        'folder_path' =>  $faker->word ,
        'overview' =>  $faker->text ,
        'homepage' =>  $faker->word ,
        'poster_path' =>  $faker->word ,
        'backdrop_path' =>  $faker->word ,
        'runtime' =>  $faker->randomNumber() ,
        'popularity' =>  $faker->randomFloat() ,
        'release_date' =>  $faker->dateTimeBetween() ,
        'total_views' =>  $faker->randomNumber() ,
    ];
});

$factory->define(Airflix\MovieView::class, function (Faker\Generator $faker) {
    return [
        'movie_id' =>  $faker->randomNumber() ,
        'uuid' =>  $faker->word ,
        'movie_uuid' =>  $faker->word ,
        'tmdb_movie_id' =>  $faker->randomNumber() ,
    ];
});

$factory->define(Airflix\Season::class, function (Faker\Generator $faker) {
    return [
        'show_id' =>  factory(Airflix\Show::class)->create()->id ,
        'uuid' =>  $faker->word ,
        'show_uuid' =>  $faker->word ,
        'tmdb_show_id' =>  $faker->randomNumber() ,
        'tmdb_season_id' =>  $faker->randomNumber() ,
        'season_number' =>  $faker->randomNumber() ,
        'name' =>  $faker->name ,
        'overview' =>  $faker->text ,
        'poster_path' =>  $faker->word ,
        'episode_count' =>  $faker->randomNumber() ,
        'air_date' =>  $faker->dateTimeBetween() ,
        'total_views' =>  $faker->randomNumber() ,
    ];
});

$factory->define(Airflix\Show::class, function (Faker\Generator $faker) {
    return [
        'uuid' =>  $faker->word ,
        'tmdb_show_id' =>  $faker->randomNumber() ,
        'name' =>  $faker->name ,
        'original_name' =>  $faker->word ,
        'folder_name' =>  $faker->word ,
        'folder_path' =>  $faker->word ,
        'overview' =>  $faker->text ,
        'homepage' =>  $faker->word ,
        'poster_path' =>  $faker->word ,
        'backdrop_path' =>  $faker->word ,
        'number_of_episodes' =>  $faker->randomNumber() ,
        'number_of_seasons' =>  $faker->randomNumber() ,
        'average_runtime' =>  $faker->randomNumber() ,
        'popularity' =>  $faker->randomFloat() ,
        'first_air_date' =>  $faker->dateTimeBetween() ,
        'last_air_date' =>  $faker->dateTimeBetween() ,
        'total_views' =>  $faker->randomNumber() ,
    ];
});

