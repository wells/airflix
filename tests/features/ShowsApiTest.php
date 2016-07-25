<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as M;
use Airflix\Contracts;
use Airflix\Show;
use Airflix\Genre;

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
        $this->json('GET', '/api/shows')
            ->seeJson([
                'name' => $this->show->name,
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
    public function it_fetches_shows_with_keywords()
    {
        $url = '/api/shows?keywords='.$this->show->name;

        $this->json('GET', $url)
            ->seeJson([
                'name' => $this->show->name,
            ])
            ->seeJsonStructure([
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
    public function it_fetches_shows_with_sort()
    {
        $url = '/api/shows?sort=name';

        $this->json('GET', $url)
            ->seeJsonStructure([
                'data', 
                'included', 
                'meta', 
                'links',
            ]);
    }

    /** @test */
    public function it_fetches_a_single_show()
    {
        $this->json('GET', '/api/shows/'.$this->show->uuid)
            ->seeJson([
                'name' => $this->show->name,
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
    public function it_404s_if_a_show_is_not_found()
    {
        $this->json('GET', '/api/shows/x')
            ->assertResponseStatus(404);
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

        $this->json('PATCH', 'api/shows/'.$this->show->uuid, $data)
            ->assertResponseStatus(201);
    }

    /** @test */
    public function it_throws_a_403_if_a_patch_request_is_unsupported()
    {
        $this->json('PATCH', 'api/shows/'.$this->show->uuid)
            ->seeJson([
                'status' => 403,
            ])
            ->seeJson([
                'message' => 'Unsupported request format (requires JSON-API).',
            ])
            ->assertResponseStatus(403);
    }
}
