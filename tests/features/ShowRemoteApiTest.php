<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as M;
use Airflix\Contracts;
use Airflix\Show;

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

        $this->json('GET', $url)
            ->seeJsonStructure([
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

        $this->json('PATCH', $url, $data)
            ->assertResponseStatus(201);
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

        $this->json('GET', $url)
            ->seeJsonStructure([
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

        $this->json('PATCH', $url, $data)
            ->assertResponseStatus(201);
    }

    /** @test */
    public function it_fetches_show_results()
    {
        $tmdbSearch = Mockery::mock('Tmdb\Api\Search');
        $tmdbSearch->shouldReceive('searchTv')
            ->once()->andReturn(null);

        Tmdb::shouldReceive('getSearchApi')
            ->once()->andReturn($tmdbSearch);

        $url = '/api/shows/'.$this->show->uuid.'/results';

        $this->json('GET', $url)
            ->seeJsonStructure([
                'data',
                'meta',
            ]);
    }

    /** @test */
    public function it_patches_a_show_with_result_given_valid_parameters()
    {
        $this->imageClient->shouldReceive('download')->times(2);

        $tmdbShows = Mockery::mock('Tmdb\Api\Shows');
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

        $this->json('PATCH', $url, $data)
            ->assertResponseStatus(201);
    }
}
