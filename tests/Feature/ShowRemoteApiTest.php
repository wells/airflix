<?php

namespace Tests\Feature;

use Airflix\Show;
use Mockery as M;
use Tests\TestCase;
use Airflix\Contracts;
use Tmdb\Laravel\Facades\Tmdb;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowRemoteApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $imageClient;
    protected $show;

    function setUp()
    {
        parent::setUp();

        $this->show = factory(Show::class)->create([
            'folder_name' => 'Firefly',
            'tmdb_show_id' => 1437,
        ]);

        $this->imageClient = M::mock(Contracts\TmdbImageClient::class);

        app()->instance(
            Contracts\TmdbImageClient::class, $this->imageClient
        );
    }

    /** @test */
    public function it_fetches_show_backdrops()
    {
        $tmdbShows = M::mock('Tmdb\Api\Shows');
        $tmdbShows->shouldReceive('getImages')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getTvApi')
            ->once()->andReturn($tmdbShows);

        $url = '/api/shows/'.$this->show->uuid.'/backdrops';

        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    /** @test */
    public function it_updates_a_show_backdrop_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $url = '/api/shows/'.$this->show->uuid;
        $data = [
            'data' => [
                'type' => 'shows',
                'id' => $this->show->uuid,
                'attributes' => [
                    'backdrop_path' => '/backdrop.jpg',
                ],
            ],
        ];

        $response = $this->json('PATCH', $url, $data);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_fetches_show_posters()
    {
        $tmdbShows = M::mock('Tmdb\Api\Shows');
        $tmdbShows->shouldReceive('getImages')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getTvApi')
            ->once()->andReturn($tmdbShows);

        $url = '/api/shows/'.$this->show->uuid.'/posters';

        $response = $this->json('GET', $url);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    /** @test */
    public function it_patches_a_show_poster_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $url = '/api/shows/'.$this->show->uuid;
        $data = [
            'data' => [
                'type' => 'shows',
                'id' => $this->show->uuid,
                'attributes' => [
                    'poster_path' => '/poster.jpg',
                ],
            ],
        ];

        $response = $this->json('PATCH', $url, $data);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_fetches_show_results()
    {
        $tmdbSearch = M::mock('Tmdb\Api\Search');
        $tmdbSearch->shouldReceive('searchTv')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getSearchApi')
            ->once()->andReturn($tmdbSearch);

        $url = '/api/shows/'.$this->show->uuid.'/results';

        $response = $this->json('GET', $url);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
    }

    /** @test */
    public function it_patches_a_show_with_result_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $tmdbShows = M::mock('Tmdb\Api\Shows');
        $tmdbShows->shouldReceive('getTvshow')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getTvApi')
            ->once()->andReturn($tmdbShows);

        $url = '/api/shows/'.$this->show->uuid;
        $data = [
            'data' => [
                'type' => 'shows',
                'id' => $this->show->uuid,
                'attributes' => [
                    'tmdb_show_id' => $this->show->tmdb_show_id,
                ],
            ],
        ];

        $response = $this->json('PATCH', $url, $data);

        $response->assertStatus(201);
    }
}
