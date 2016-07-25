<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Airflix\Episode;

class EpisodesApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $episode;

    function setUp()
    {
        parent::setUp();

        $this->episode = factory(Episode::class)->create();
    }

    /** @test */
    public function it_fetches_a_single_episode()
    {
        $this->json('GET', '/api/episodes/'.$this->episode->uuid)
            ->seeJson([
                'name' => $this->episode->name,
            ])
            ->seeJsonStructure([
                'data' => [
                    'relationships' => [
                        'show',
                        'season',
                        'views',
                    ],
                ], 
                'included', 
                'meta',
            ]);
    }

    /** @test */
    public function it_404s_if_a_episode_is_not_found()
    {
        $this->json('GET', '/api/episodes/x')
            ->assertResponseStatus(404);
    }
}
