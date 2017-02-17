<?php

namespace Tests\Feature;

use Airflix\Show;
use Mockery as M;
use Airflix\Genre;
use Tests\TestCase;
use Airflix\Contracts;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowsApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $imageClient;
    protected $genre;
    protected $show;

    function setUp()
    {
        parent::setUp();

        $this->show = factory(Show::class)->create();
        $this->genre = factory(Genre::class)->create();

        $this->show->genres()->save($this->genre);

        $this->imageClient = M::mock(Contracts\TmdbImageClient::class);

        app()->instance(
            Contracts\TmdbImageClient::class, $this->imageClient
        );
    }

    /** @test */
    public function it_fetches_shows()
    {
        $response = $this->json('GET', '/api/shows');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $this->show->name,
        ]);
        $response->assertJsonFragment([
            'name' => $this->genre->name,
        ]);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_shows_with_keywords()
    {
        $url = '/api/shows?keywords='.$this->show->name;

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $this->show->name,
        ]);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_shows_with_genre()
    {
        $url = '/api/shows?genre='.$this->genre->uuid;

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $this->genre->name,
        ]);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_shows_with_sort()
    {
        $url = '/api/shows?sort=name';

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data', 
            'included', 
            'meta', 
            'links',
        ]);
    }

    /** @test */
    public function it_fetches_a_single_show()
    {
        $response = $this->json('GET', '/api/shows/'.$this->show->uuid);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $this->show->name,
        ]);
        $response->assertJsonFragment([
            'name' => $this->genre->name,
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
    public function it_404s_if_a_show_is_not_found()
    {
        $response = $this->json('GET', '/api/shows/x');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_patches_a_show_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $data = [
            'data' => [
                'type' => 'shows',
                'id' => $this->show->uuid,
                'attributes' => [
                    'name' => 'Name',
                ],
            ],
        ];

        $response = $this->json('PATCH', 'api/shows/'.$this->show->uuid, $data);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_throws_a_403_if_a_patch_request_is_unsupported()
    {
        $response = $this->json('PATCH', 'api/shows/'.$this->show->uuid);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'status' => 403,
        ]);
        $response->assertJsonFragment([
            'message' => 'Unsupported request format (requires JSON-API).',
        ]);
    }
}
