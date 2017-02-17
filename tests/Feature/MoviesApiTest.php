<?php

namespace Tests\Feature;

use Mockery as M;
use Airflix\Genre;
use Airflix\Movie;
use Tests\TestCase;
use Airflix\Contracts;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoviesApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $imageClient;
    protected $genre;
    protected $movie;

    function setUp()
    {
        parent::setUp();

        $this->movie = factory(Movie::class)->create();
        $this->genre = factory(Genre::class)->create();

        $this->movie->genres()->save($this->genre);

        $this->imageClient = M::mock(Contracts\TmdbImageClient::class);

        app()->instance(
            Contracts\TmdbImageClient::class, $this->imageClient
        );
    }

    /** @test */
    public function it_fetches_movies()
    {
        $response = $this->json('GET', '/api/movies');
        
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->movie->uuid,
        ]);
        $response->assertJsonFragment([
            'id' => $this->genre->uuid,
        ]);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_movies_with_keywords()
    {
        $url = '/api/movies?keywords='.$this->movie->title;

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->movie->uuid,
        ]);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_movies_with_genre()
    {
        $url = '/api/movies?genre='.$this->genre->uuid;
        
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->genre->uuid,
        ]);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_movies_with_sort()
    {
        $url = '/api/movies?sort=title';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->genre->uuid,
        ]);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_a_single_movie()
    {
        $response = $this->json('GET', '/api/movies/'.$this->movie->uuid);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->movie->uuid,
        ]);
        $response->assertJsonFragment([
            'id' => $this->genre->uuid,
        ]);
        $response->assertJsonStructure([
            'data' => [
                'relationships' => [
                    'genres',
                    'views',
                ],
            ], 
            'included', 
            'meta',
        ]);
    }

    /** @test */
    public function it_404s_if_a_movie_is_not_found()
    {
        $response = $this->json('GET', '/api/movies/x');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_patches_a_movie_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $data = [
            'data' => [
                'type' => 'movies',
                'id' => $this->movie->uuid,
                'attributes' => [
                    'title' => 'Title',
                ],
            ],
        ];

        $response = $this->json('PATCH', 'api/movies/'.$this->movie->uuid, $data);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_throws_a_403_if_a_patch_request_is_unsupported()
    {
        $response = $this->json('PATCH', 'api/movies/'.$this->movie->uuid);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'status' => 403,
        ]);
        $response->assertJsonFragment([
            'message' => 'Unsupported request format (requires JSON-API).',
        ]);
    }
}
