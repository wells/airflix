<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shows', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->integer('tmdb_show_id')->index()->unsigned()->default(0);
            $table->string('name')->nullable();
            $table->string('original_name')->nullable();
            $table->string('folder_name');
            $table->string('folder_path', 4096);
            $table->text('overview')->nullable();
            $table->string('homepage')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->integer('number_of_episodes')->unsigned()->default(0);
            $table->integer('number_of_seasons')->unsigned()->default(0);
            $table->integer('average_runtime')->unsigned()->default(0);
            $table->float('popularity')->default(0.00);
            $table->date('first_air_date')->nullable();
            $table->date('last_air_date')->nullable();
            $table->integer('total_views')->unsigned()->default(0);
            $table->string('imdb_id')->nullable();
            $table->integer('tvdb_id')->unsigned()->default(0);
            $table->integer('tvrage_id')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('show_id')->index()->unsigned();
            $table->uuid('uuid')->unique();
            $table->uuid('show_uuid');
            $table->integer('tmdb_show_id')->index()->unsigned()->default(0);
            $table->integer('tmdb_season_id')->index()->unsigned()->unique()->default(0);
            $table->integer('season_number')->index()->unsigned()->default(0);
            $table->string('name')->nullable();
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->integer('episode_count')->unsigned()->default(0);
            $table->date('air_date')->nullable();
            $table->integer('total_views')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('show_id')->index()->unsigned();
            $table->integer('season_id')->index()->unsigned();
            $table->uuid('uuid')->unique();
            $table->uuid('show_uuid');
            $table->uuid('season_uuid');
            $table->integer('tmdb_show_id')->index()->unsigned()->default(0);
            $table->integer('tmdb_season_id')->index()->unsigned()->default(0);
            $table->integer('tmdb_episode_id')->index()->unsigned()->unique()->default(0);
            $table->integer('season_number')->index()->unsigned()->default(0);
            $table->integer('episode_number')->index()->unsigned()->default(0);
            $table->string('name')->nullable();
            $table->text('overview')->nullable();
            $table->string('still_path')->nullable();
            $table->date('air_date')->nullable();
            $table->integer('total_views')->unsigned()->default(0);
            $table->timestamps();
        });
        
        Schema::create('episode_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('show_id')->unsigned();
            $table->integer('season_id')->unsigned();
            $table->integer('episode_id')->unsigned();
            $table->uuid('uuid')->unique();
            $table->uuid('show_uuid');
            $table->uuid('season_uuid');
            $table->uuid('episode_uuid');
            $table->integer('tmdb_show_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('genre_show', function (Blueprint $table) {
            $table->integer('genre_id')->unsigned();
            $table->integer('show_id')->unsigned();

            $table->primary(['genre_id', 'show_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genre_show');
        Schema::dropIfExists('episode_views');
        Schema::dropIfExists('episodes');
        Schema::dropIfExists('seasons');
        Schema::dropIfExists('shows');
    }
}
