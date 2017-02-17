<?php

namespace Tests\Feature;

use Mockery as M;
use Airflix\Movie;
use Tests\TestCase;
use Airflix\Contracts;
use Tmdb\Laravel\Facades\Tmdb;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovieRemoteApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $imageClient;
    protected $movie;

    function setUp()
    {
        parent::setUp();

        $this->movie = factory(Movie::class)->create([
            'folder_name' => 'Avatar',
            'tmdb_movie_id' => 19995,
        ]);

        $this->imageClient = M::mock(Contracts\TmdbImageClient::class);
        
        app()->instance(
            Contracts\TmdbImageClient::class, $this->imageClient
        );
    }

    /** @test */
    public function it_fetches_movie_backdrops()
    {
        $tmdbMovies = M::mock('Tmdb\Api\Movies');
        $tmdbMovies->shouldReceive('getImages')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getMoviesApi')
            ->once()->andReturn($tmdbMovies);

        $url = '/api/movies/'.$this->movie->uuid.'/backdrops';

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    /** @test */
    public function it_patches_a_movie_backdrop_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $url = '/api/movies/'.$this->movie->uuid;
        $data = [
            'data' => [
                'type' => 'movies',
                'id' => $this->movie->uuid,
                'attributes' => [
                    'backdrop_path' => '/backdrop.jpg',
                ],
            ],
        ];

        $response = $this->json('PATCH', $url, $data);
        
        $response->assertStatus(201);
    }

    /** @test */
    public function it_fetches_movie_posters()
    {
        $tmdbMovies = M::mock('Tmdb\Api\Movies');
        $tmdbMovies->shouldReceive('getImages')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getMoviesApi')
            ->once()->andReturn($tmdbMovies);

        $url = '/api/movies/'.$this->movie->uuid.'/posters';

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    /** @test */
    public function it_patches_a_movie_poster_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $url = '/api/movies/'.$this->movie->uuid;
        $data = [
            'data' => [
                'type' => 'movies',
                'id' => $this->movie->uuid,
                'attributes' => [
                    'poster_path' => '/poster.jpg',
                ],
            ],
        ];

        $response = $this->json('PATCH', $url, $data);
        
        $response->assertStatus(201);
    }

    /** @test */
    public function it_fetches_movie_results()
    {   
        $tmdbSearch = M::mock('Tmdb\Api\Search');
        $tmdbSearch->shouldReceive('searchMovies')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getSearchApi')
            ->once()->andReturn($tmdbSearch);

        $url = '/api/movies/'.$this->movie->uuid.'/results';

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    /** @test */
    public function it_patches_a_movie_with_result_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $tmdbMovies = M::mock('Tmdb\Api\Movies');
        $tmdbMovies->shouldReceive('getMovie')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getMoviesApi')
            ->once()->andReturn($tmdbMovies);

        $url = '/api/movies/'.$this->movie->uuid;
        $data = [
            'data' => [
                'type' => 'movies',
                'id' => $this->movie->uuid,
                'attributes' => [
                    'tmdb_movie_id' => $this->movie->tmdb_movie_id,
                ],
            ],
        ];

        $response = $this->json('PATCH', $url, $data);

        $response->assertStatus(201);
    }
}
