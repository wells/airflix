<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->integer('tmdb_movie_id')->index()->unsigned()->default(0);
            $table->string('title')->nullable();
            $table->string('original_title')->nullable();
            $table->string('folder_name');
            $table->string('folder_path', 4096);
            $table->text('overview')->nullable();
            $table->string('homepage')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->integer('runtime')->unsigned()->default(0);
            $table->float('popularity')->default(0.00);
            $table->date('release_date')->nullable();
            $table->bigInteger('budget')->unsigned()->default(0);
            $table->bigInteger('revenue')->unsigned()->default(0);
            $table->integer('total_views')->unsigned()->default(0);
            $table->string('imdb_id')->nullable();
            $table->timestamps();
        });

        Schema::create('movie_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned();
            $table->uuid('uuid')->unique();
            $table->uuid('movie_uuid');
            $table->integer('tmdb_movie_id')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('genre_movie', function (Blueprint $table) {
            $table->integer('genre_id')->unsigned();
            $table->integer('movie_id')->unsigned();

            $table->primary(['genre_id', 'movie_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genre_movie');
        Schema::dropIfExists('movie_views');
        Schema::dropIfExists('movies');
    }
}
