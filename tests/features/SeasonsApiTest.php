<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Airflix\Season;

class SeasonsApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $season;

    function setUp()
    {
        parent::setUp();

        $this->season = factory(Season::class)->create();
    }

    /** @test */
    public function it_fetches_a_single_season()
    {
        $this->json('GET', '/api/seasons/'.$this->season->uuid)
            ->seeJson([
                'name' => $this->season->name,
            ])
            ->seeJsonStructure([
                'data' => [
                    'relationships' => [
                        'episodes',
                        'show',
                        'views',
                    ],
                ], 
                'included', 
                'meta',
            ]);
    }

    /** @test */
    public function it_404s_if_a_season_is_not_found()
    {
        $this->json('GET', '/api/seasons/x')
            ->assertResponseStatus(404);
    }
}
