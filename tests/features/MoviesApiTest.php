<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as M;
use Airflix\Contracts;
use Airflix\Movie;
use Airflix\Genre;

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
        $this->json('GET', '/api/movies')
            ->seeJson([
                'title' => $this->movie->title,
            ])
            ->seeJson([
                'name' => $this->genre->name,
            ])
            ->seeJsonStructure([
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
        $this->json('GET', $url)
            ->seeJson([
                'title' => $this->movie->title,
            ])
            ->seeJsonStructure([
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
        $this->json('GET', $url)
            ->seeJson([
                'name' => $this->genre->name,
            ])
            ->seeJsonStructure([
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
        $this->json('GET', $url)
            ->seeJsonStructure([
                'data', 
                'included', 
                'meta', 
                'links',
            ]);
    }

    /** @test */
    public function it_fetches_a_single_movie()
    {
        $this->json('GET', '/api/movies/'.$this->movie->uuid)
            ->seeJson([
                'title' => $this->movie->title,
            ])
            ->seeJson([
                'name' => $this->genre->name,
            ])
            ->seeJsonStructure([
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
        $this->json('GET', '/api/movies/x')
            ->assertResponseStatus(404);
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

        $this->json('PATCH', 'api/movies/'.$this->movie->uuid, $data)
            ->assertResponseStatus(201);
    }

    /** @test */
    public function it_throws_a_403_if_a_patch_request_is_unsupported()
    {
        $this->json('PATCH', 'api/movies/'.$this->movie->uuid)
            ->seeJson([
                'status' => 403,
            ])
            ->seeJson([
                'message' => 'Unsupported request format (requires JSON-API).',
            ])
            ->assertResponseStatus(403);
    }
}
